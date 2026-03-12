<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class MyModuleCest
{
    public function _before(AcceptanceTester $I) {
        // Chạy trước mỗi test (vd: login)
    }

    public function tryToPostNews(AcceptanceTester $I) {
        $I->wantTo('Đăng bài viết mới');
        $I->login(); // Helper login tùy chỉnh
        $I->amOnUrl($I->getDomain() . '/admin/vi/news/content/');
        $I->fillField(['name' => 'title'], 'Tiêu đề test');
        $I->click('label[for="catid_1"]');
        
        // Thực thi JS nếu cần (vd: nạp dữ liệu editor)
        $I->executeJS("window.nveditor.news_bodyhtml.setData('Content');");
        
        $I->click('#btn_save');
        $I->waitForText('Đã ghi dữ liệu thành công', 5);
    }
}
