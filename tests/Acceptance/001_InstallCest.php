<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class InstallCest
{
    public function _before(AcceptanceTester $I)
    {
        // Xóa để cài đặt site mới
        if (is_file(NV_ROOTDIR . '/config.php')) {
            unlink(NV_ROOTDIR . '/config.php');
        }
        if (is_file(NV_ROOTDIR . '/data/config/robots.php')) {
            unlink(NV_ROOTDIR . '/data/config/robots.php');
        }

        // Xóa error log
        $files = scandir(NV_ROOTDIR . '/data/logs/error_logs');
        if (!empty($files)) {
            foreach ($files as $file) {
                if (preg_match('/\.log$/i', $file)) {
                    unlink(NV_ROOTDIR . '/data/logs/error_logs/' . $file);
                }
            }
        }
        $files = scandir(NV_ROOTDIR . '/data/logs/error_logs/tmp');
        if (!empty($files)) {
            foreach ($files as $file) {
                if (preg_match('/\.log$/i', $file)) {
                    unlink(NV_ROOTDIR . '/data/logs/error_logs/tmp/' . $file);
                }
            }
        }

        // Xóa file config_ini
        $files = scandir(NV_ROOTDIR . '/data/config');
        if (!empty($files)) {
            foreach ($files as $file) {
                if (preg_match('/^config\_ini\.(.*?)\.php$/i', $file)) {
                    unlink(NV_ROOTDIR . '/data/config/' . $file);
                }
            }
        }

        // Xóa cache
        $dirs = scandir(NV_ROOTDIR . '/data/cache');
        if (!empty($dirs)) {
            foreach ($dirs as $dir) {
                if ($dir != '.' and $dir != '..' and is_dir(NV_ROOTDIR . '/data/cache/' . $dir)) {
                    $files = scandir(NV_ROOTDIR . '/data/cache/' . $dir);
                    foreach ($files as $file) {
                        if (preg_match('/\.(cache|php)$/i', $file)) {
                            unlink(NV_ROOTDIR . '/data/cache/' . $dir . '/' . $file);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param AcceptanceTester $I
     *
     * @group install
     * @group install-only
     * @group all
     */
    public function installStep1(AcceptanceTester $I)
    {
        $I->wantTo('Install NukeViet for testing');

        $I->amOnUrl($I->getDomain() . '/install/index.php');
        $I->seeElement('#lang');

        // Step 1
        $I->selectOption('#lang', $_ENV['NV_LANG']);
        $I->click('.next_step a');

        // Step 2
        $I->waitForElement('#checkchmod', 5);
        $I->seeElement('.next_step a');
        $I->click('.next_step a');

        // Step 3
        $I->waitForElement('#license', 5);
        $I->seeElement('.next_step a');
        $I->click('.next_step a');

        // Step 4
        $I->waitForElement('#checkserver', 5);
        $I->seeElement('.next_step a');
        $I->click('.next_step a');

        // Step 5: CSDL
        $I->waitForElement('#database_config', 5);

        $I->fillField(['name' => 'dbuname'], $_ENV['DB_UNAME']);
        $I->fillField(['name' => 'dbpass'], $_ENV['DB_UPASS']);
        $I->fillField(['name' => 'dbname'], $_ENV['DB_NAME']);

        // JS sẽ intercept form submit và chạy AJAX tuần tự
        $I->click('[type="submit"]');

        // Đợi AJAX system phản hồi (progress bar hiện ra)
        $I->waitForElement('#nv_install_progress', 10);

        // Nếu DB đã tồn tại → dialog xác nhận xóa xuất hiện
        // JS sẽ show lại form với checkbox #db_detete khi server trả has_table=true
        $I->wait(2);
        if ($I->tryToSeeElement('#db_detete')) {
            $I->checkOption('#db_detete');
            $I->click('[type="submit"]');
            $I->waitForElement('#nv_install_progress', 10);
        }

        // Đợi AJAX hoàn tất toàn bộ (cài tất cả module) và redirect sang step 6
        // Timeout 120s để đủ thời gian cài nhiều module
        $I->waitForElement('#site_config', 120);

        $I->fillField(['name' => 'site_name'], $_ENV['NV_SITE_NAME']);
        $I->fillField(['name' => 'nv_login'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'nv_email'], $_ENV['NV_EMAIL']);
        $I->fillField(['name' => 'nv_password'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 're_password'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 'question'], $_ENV['NV_QUESTION']);
        $I->fillField(['name' => 'answer_question'], $_ENV['NV_ANSWER']);

        if (!empty($_ENV['LANG_MULTI'])) {
            $I->checkOption('[name="lang_multi"]');
        }

        $I->checkOption('[name="dev_mode"]');

        $I->click('[type="submit"]');

        // Step 7 thành công
        $I->waitForElement('.home', 5);
        $I->seeElement('.okay');
    }
}
