<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 *
 * CẢNH BÁO: Test có thể chạy rất lâu (tùy vào độ lớn của site)
 * Test này Thực hiện hàng loạt thao tác Click Sửa/Xóa. Nó có thể làm thay đổi hoặc mất dữ liệu trên database mà bạn đang cấu hình
 * Hãy đảm bảo bạn đang chạy trên bản copy dữ liệu an toàn. Chỉ nên chạy trên môi trường test/dev.
 *
 * Run all: php vendor/bin/codecept run Acceptance tests/Acceptance/999_WebCrawlerCest.php --steps
 * Run Step 1 (Frontend): php vendor/bin/codecept run Acceptance tests/Acceptance/999_WebCrawlerCest.php:crawlFrontendSite --steps
 * Run Step 2 (SystemModules): php vendor/bin/codecept run Acceptance tests/Acceptance/999_WebCrawlerCest.php:crawlSystemModules --steps
 * Run Step 3 (Admin): php vendor/bin/codecept run Acceptance tests/Acceptance/999_WebCrawlerCest.php:crawlAdminArea --steps
 * Run Specific Module: set NV_MODULE=news && php vendor/bin/codecept run Acceptance tests/Acceptance/999_WebCrawlerCest.php:crawlAdminModule --steps

 */

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

/**
 * @group crawler
 */
class WebCrawlerCest
{
    private array $visited = [];
    private array $queue = [];
    private array $errors = []; // Ghi nhận các link bị lỗi
    private array $referrers = []; // Ghi nhận link mẹ của mỗi link (để truy vết)
    private string $domain = '';
    private string $logPath = '';

    public function _before(AcceptanceTester $I)
    {
        $this->domain = rtrim($I->getDomain(), '/');
        $this->logPath = dirname(__DIR__, 2) . '/tests/_output/crawling_errors.log';
        if (!file_exists(dirname($this->logPath))) {
            mkdir(dirname($this->logPath), 0777, true);
        }
        if (!file_exists($this->logPath)) {
            file_put_contents($this->logPath, "BÁO CÁO CRAWLER LỖI (" . date('Y-m-d H:i:s') . ")\n" . str_repeat('=', 60) . "\n");
        }
    }

    public function _after(AcceptanceTester $I)
    {
        if (!empty($this->errors)) {
            $I->comment("\n" . str_repeat('=', 60));
            $I->comment("TỔNG HỢP LỖI PHÁT HIỆN:");
            foreach ($this->errors as $url => $msg) {
                $I->comment("- [!] $url: $msg");
            }
            $I->comment("Báo cáo chi tiết: tests/_output/crawling_errors.log");
            $I->comment(str_repeat('=', 60) . "\n");
        }
    }

    /**
     * BƯỚC 1: Quét Frontend
     */
    public function crawlFrontendSite(AcceptanceTester $I)
    {
        $I->wantTo('BƯỚC 1: Quét đệ quy toàn bộ giao diện bên ngoài site');
        $this->queue = [$this->domain . '/'];
        $this->runQueueLoop($I, false);
    }

    /**
     * BƯỚC 2: Quét Module từ dropdown (Nhận diện nhanh các module hệ thống)
     */
    public function crawlSystemModules(AcceptanceTester $I)
    {
        $I->wantTo('BƯỚC 2: Mở menu Module hệ thống và quét đệ quy các module được tìm thấy');
        $I->login();
        $I->amOnUrl($this->domain . '/admin/index.php');
        $selector = 'a[aria-label="Các module của hệ thống"]';

        try {
            if ($I->executeJS("return !!document.querySelector('$selector');")) {
                $I->click($selector);
                $I->wait(1);
                $links = $I->grabMultiple('.dropdown-menu a', 'href');
                if (empty($links)) {
                    $links = $I->grabMultiple('a.dropdown-item[href*="nv="]', 'href');
                }

                $I->comment("Tìm thấy " . count($links) . " module. Đang bắt đầu quét...");
                foreach ($links as $link) {
                    if ($this->isValidLink($link)) {
                        $absLink = $this->makeAbsolute($link, $this->domain . '/admin/index.php');
                        if (!isset($this->visited[$absLink])) {
                            $this->queue[] = $absLink;
                            $this->referrers[$absLink] = $this->domain . '/admin/index.php (Dropdown menu)';
                        }
                    }
                }
                $this->runQueueLoop($I, true);
            }
        } catch (\Exception $e) {
            $I->comment("Lỗi truy cập danh sách module: " . $e->getMessage());
        }
    }

    /**
     * BƯỚC 3: Quét mọi link còn lại trong khu vực Admin
     */
    public function crawlAdminArea(AcceptanceTester $I)
    {
        $I->wantTo('BƯỚC 3: Truy cập và quét đệ quy mọi chức năng khác trong Admin');
        $I->login();
        $this->queue = [$this->domain . '/admin/index.php'];
        $this->runQueueLoop($I, true);
    }

    /**
     * NHÁNH RIÊNG: Quét chuyên sâu một Module cụ thể (mặc định là news)
     * Cách dùng: set NV_MODULE=users && php vendor/bin/codecept run ...
     */
    public function crawlAdminModule(AcceptanceTester $I)
    {
        $module = getenv('NV_MODULE') ?: 'news';
        $I->wantTo("NHÁNH RIÊNG: Chỉ quét đệ quy các chức năng thuộc module: $module");
        $I->login();
        // Bắt đầu từ trang chính của module
        $this->queue = [$this->domain . "/admin/index.php?nv=$module"];
        // Chạy loop với filter chỉ cho phép các link thuộc module này
        $this->runQueueLoop($I, true, $module);
    }

    /**
     * Vòng lặp chính xử lý hàng đợi
     */
    private function runQueueLoop(AcceptanceTester $I, bool $isAdmin, string $moduleFilter = '')
    {
        $count = 0;
        while (!empty($this->queue)) {
            $url = array_shift($this->queue);
            $url = explode('#', $url)[0];

            if (isset($this->visited[$url]))
                continue;
            if (!$isAdmin && str_contains($url, '/admin/'))
                continue;

            $this->visited[$url] = true;
            $count++;

            $I->comment(">>> [" . ($isAdmin ? 'Admin' : 'Frontend') . " #{$count}] " . $url);

            try {
                $I->amOnUrl($url);
                $I->wait(0.2);

                // KIỂM TRA LỖI NỘI DUNG
                $pageSource = $I->executeJS("return document.body.innerText;");
                $errorPatterns = [
                    'Fatal error' => 'Lỗi PHP Fatal Error',
                    'Syntax error' => 'Lỗi cú pháp PHP',
                    'Internal Server Error' => 'Lỗi máy chủ 500',
                    '404 Not Found' => 'Trang không tồn tại 404',
                    'Warning:' => 'Cảnh báo PHP Warning',
                    'mysql error' => 'Lỗi Database',
                    'deprecated' => 'Cảnh báo hàm bị khai tử'
                ];
                foreach ($errorPatterns as $p => $l) {
                    if (str_contains($pageSource, $p)) {
                        $this->logError($url, $l);
                        break;
                    }
                }

                // Lấy link mới
                $links = $I->grabMultiple('a', 'href');
                foreach ($links as $link) {
                    if ($this->isValidLink($link)) {
                        $absLink = $this->makeAbsolute($link, $url);

                        // Nếu có filter module
                        if (!empty($moduleFilter)) {
                            // Chấp nhận link nếu chứa nv=[module] hoặc /[module]/
                            if (!str_contains($absLink, "nv=$moduleFilter") && !str_contains($absLink, "/$moduleFilter/")) {
                                continue;
                            }
                        }

                        if ($isAdmin === str_contains($absLink, '/admin/')) {
                            if (!isset($this->visited[$absLink]) && !in_array($absLink, $this->queue)) {
                                $this->queue[] = $absLink;
                                $this->referrers[$absLink] = $url; // Ghi nhớ link mẹ
                            }
                        }
                    }
                }

                $this->clickActionElements($I);
            } catch (\Exception $e) {
                $this->logError($url, "Exception: " . $e->getMessage());
            }
        }
    }

    private function logError(string $url, string $msg)
    {
        $this->errors[$url] = $msg;
        $referrer = $this->referrers[$url] ?? 'Direct/Start link';
        $line = "[" . date('Y-m-d H:i:s') . "] [!] URL: $url\n    Lỗi: $msg\n    Nguồn từ (Referrer): $referrer\n" . str_repeat('-', 40) . "\n";
        file_put_contents($this->logPath, $line, FILE_APPEND);
    }

    private function isValidLink(?string $link): bool
    {
        if (empty($link) || $link === '#' || str_starts_with($link, 'javascript:'))
            return false;
        $exclude = ['mailto:', 'tel:', 'skype:', 'sms:', 'callto:'];
        foreach ($exclude as $proto)
            if (str_starts_with(strtolower($link), $proto))
                return false;
        if (str_contains($link, 'logout'))
            return false;
        if (str_starts_with($link, 'http') && !str_contains($link, $this->domain))
            return false;
        return true;
    }

    private function makeAbsolute(string $link, string $currentUrl): string
    {
        if (str_starts_with($link, 'http'))
            return $link;
        if (str_starts_with($link, '//'))
            return 'http:' . $link;
        if (str_starts_with($link, '?')) {
            $path = parse_url($currentUrl, PHP_URL_PATH) ?: '/index.php';
            return $this->domain . $path . $link;
        }
        if (str_contains($currentUrl, '/admin/') && !str_starts_with($link, '/')) {
            return $this->domain . '/admin/' . ltrim($link, '/');
        }
        return (str_starts_with($link, '/') ? $this->domain : $this->domain . '/') . $link;
    }

    private function clickActionElements(AcceptanceTester $I)
    {
        // 1. Tương tác với Checkbox data-toggle (thường là bật/tắt nhanh)
        try {
            $checkboxXpath = "//input[@type='checkbox' and @data-toggle]";
            if ($I->executeJS("return !!document.querySelector(\"input[type='checkbox'][data-toggle]\");")) {
                $I->click($checkboxXpath);
                $I->wait(0.5);
            }
        } catch (\Exception $e) {
        }

        // 2. Danh sách các nút hành động quan trọng
        $keywords = [
            'edit',
            'sua',
            'Sửa',
            'delete',
            'xoa',
            'Xóa',
            'remove',
            'Lưu cấu hình',
            'Lưu thay đổi',
            'Thực hiện',
            'Lưu'
        ];

        foreach ($keywords as $key) {
            $xpath = "//a[contains(translate(@href, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '" . strtolower($key) . "')] " .
                "| //a[contains(text(), '$key')] " .
                "| //button[contains(text(), '$key')] " .
                "| //input[@type='submit' and contains(@value, '$key')] " .
                "| //input[@type='button' and contains(@value, '$key')]";

            try {
                if ($I->executeJS("return !!document.evaluate(\"$xpath\", document, null, XPathResult.ANY_TYPE, null).iterateNext();")) {
                    $I->comment("Đang thử tương tác với: '$key'");
                    $I->click($xpath);
                    try {
                        $I->acceptAlert();
                    } catch (\Exception $e) {
                    }
                    $I->wait(1);
                    break;
                }
            } catch (\Exception $e) {
            }
        }
    }
}
