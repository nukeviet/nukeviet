<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class DeepCrawlerCest
{
    /**
     * @param AcceptanceTester $I
     *
     * @group crawl
     * @group all
     */
    public function executeSiteWideDeepCrawl(AcceptanceTester $I)
    {
        $I->wantTo('Crawl the entire site (Public + Admin) to detect fatal and SQL errors');
        
        $domain = rtrim($I->getDomain(), '/');
        $queue = [];

        // ==========================================
        // 1. CRAWL TRANG CHỦ (FRONT-END PULIC SITE)
        // ==========================================
        $I->amOnUrl($domain . '/');
        $I->wait(2);
        
        // Thu thập tất cả các thẻ <a> nội bộ ở trang chủ (chứa / hoặc domain)
        $publicLinks = $I->grabMultiple('a[href^="/"], a[href^="' . $domain . '"]', 'href');
        
        foreach ($publicLinks as $link) {
            // Chuẩn hóa đường link thành dạng URL đầy đủ
            $link = strpos($link, 'http') === 0 ? $link : $domain . '/' . ltrim($link, '/');
            $link = strtok($link, '#'); // Loại bỏ các URL hash jump (#)
            
            if ($link !== $domain && filter_var($link, FILTER_VALIDATE_URL)) {
                $queue[] = $link;
            }
        }

        // ==========================================
        // 2. CRAWL QUẢN TRỊ (ADMIN BACK-END)
        // ==========================================
        $I->login(); // Sử dụng hàm login từ _generated/AcceptanceTesterActions
        $I->amOnUrl($domain . '/admin/index.php');
        $I->waitForElement('#left-sidebar', 10);
        
        // Thu thập toàn bộ thẻ <a> nằm trong thanh menu trái của admin
        $adminLinks = $I->grabMultiple('#left-sidebar a[href^="/admin/"], #left-sidebar a[href^="' . $domain . '/admin/"]', 'href');
        
        foreach ($adminLinks as $link) {
            $link = strpos($link, 'http') === 0 ? $link : $domain . '/' . ltrim($link, '/');
            $link = strtok($link, '#'); 
            
            // Bộ lọc an toàn: Bỏ qua các liên kết phá hủy dữ liệu hoặc đăng xuất
            $lower = strtolower($link);
            if (strpos($lower, 'logout') !== false 
                || strpos($lower, 'delete') !== false 
                || strpos($lower, 'del') !== false
                || strpos($lower, 'empty') !== false
            ) {
                continue;
            }
            
            if (filter_var($link, FILTER_VALIDATE_URL)) {
                $queue[] = $link;
            }
        }

        // Loại bỏ các URL trùng lặp để tối ưu hiệu suất
        $queue = array_unique($queue);
        $queue = array_values($queue);

        // ==========================================
        // 3. THỰC HIỆN DUYỆT TẤT CẢ (DEEP CRAWL LOOP)
        // ==========================================
        // Đặt giới hạn an toàn để test không chạy vô hạn (vd max 500 urls)
        $limit = 500; 
        $count = 0;

        foreach ($queue as $url) {
            if ($count >= $limit) break;
            $count++;

            $I->amOnUrl($url);
            $I->wait(1); // Nghỉ 1s mỗi request tránh ngập lụt Server WebDriver / PHP
            
            // XÁC NHẬN KHÔNG CÓ LỖI HỆ THỐNG TRÊN GIAO DIỆN
            $I->dontSee('SQL Error');
            $I->dontSee('SQLSTATE');
            $I->dontSee('Fatal error');
            $I->dontSee('PDOException');
            $I->dontSee('syntax error');
            // Đảm bảo Body tag parse hợp lệ, không bị dính White Screen Of Death (Lỗi 500 trả màn hình trắng)
            $I->seeElement('body'); 

            // CẬP NHẬT: AUTO-SUBMIT MỌI FORM GẶP PHẢI ĐỂ TEST XỬ LÝ POST / UPDATE DỮ LIỆU
            try {
                $hasForm = $I->executeJS("return (document.forms.length > 0);");
                
                if ($hasForm) {
                    // Tắt Validate HTML5 ở client-side để bỏ qua required fields
                    $I->executeJS("
                        var f = document.forms[0];
                        if (f) { f.noValidate = true; }
                        // Ghi đè hàm confirm và alert mặc định của trình duyệt để ngăn chặn các popup hỏi đáp (VD: Bạn có chắc xóa không?)
                        window.confirm = function() { return true; };
                        window.alert = function() { return true; };
                    ");
                    
                    // Gọi nút Submit thẳng vào Controller bằng Codeception native action
                    if ($I->tryToSeeElement('form button[type="submit"], form input[type="submit"]')) {
                        $I->click('form button[type="submit"], form input[type="submit"]');
                        $I->wait(2); // Chờ Server PHP xử lý POST
                        
                        // Tắt cảnh báo popup rời trang nếu có
                        try {
                            $I->executeJS("window.onbeforeunload = null;");
                        } catch (\Exception $e) {}

                        // NGHIỆM THU HIỆN TƯỢNG SAU KHI FORM SUBMIT (BẮT LỖI)
                        $I->dontSee('SQL Error');
                        $I->dontSee('SQLSTATE');
                        $I->dontSee('Fatal error');
                        $I->dontSee('PDOException');
                        $I->dontSee('syntax error');
                    }
                }
            } catch (\Exception $e) {
                // Bỏ qua mọi lỗi liên quan đến Webdriver (UnexpectedAlertOpenException, FileDownload...)
                // do việc Submit Form bừa bãi có thể sinh ra vô số viễn cảnh phá hoạt trình duyệt.
                // Ta chỉ quan tâm nếu nó load thành công và không phòi ra SQL Error.
            }
        }
    }
}
