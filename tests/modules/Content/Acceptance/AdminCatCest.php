<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\modules\Content\Acceptance;

use Tests\Support\AcceptanceTester;

class AdminCatCest
{
    private $module;

    public function _inject()
    {
        $this->module = getenv('NV_MODULE') ?: 'Content';
    }


    public function _before(AcceptanceTester $I)
    {
        $I->login();
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testViewCatList(AcceptanceTester $I)
    {
        $I->wantTo('Xem danh sách chuyên mục');
        $I->amOnUrl($I->getDomain() . '/admin/vi/' . $this->module . '/cat/');
        $I->wait(1);
        $I->waitForElement('#form-cat-content', 10);

        $I->see('Thêm chuyên mục', 'h5');
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testAddCatSuccess(AcceptanceTester $I)
    {
        $I->wantTo('Thêm chuyên mục mới thành công');
        $I->amOnUrl($I->getDomain() . '/admin/vi/' . $this->module . '/cat/');
        $I->wait(1);
        $I->waitForElement('input[name="title"]', 10);

        $catTitle = 'Category Test ' . time();
        $catAlias = 'category-test-' . time();

        $I->fillField('input[name="title"]', $catTitle);

        // Đợi AJAX (sinh alias) hoàn tất để đảm bảo form ổn định
        $I->waitForJS("return (typeof jQuery !== 'undefined') ? jQuery.active == 0 : true", 10);
        $I->fillField('input[name="alias"]', $catAlias);
        $I->wait(1);

        // Cuộn đến form submit
        $I->scrollTo('button[type="submit"]');
        $I->wait(1);
        $I->click('button[type="submit"]');

        // Chờ thông báo thành công (NukeViet Global lang: save_success)
        $I->waitForText('Các thay đổi đã được ghi nhận', 15);

        // Đợi Ajax reload trang sau thông báo thành công
        $I->wait(3);
        $I->see($catTitle, 'table');
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testAddCatFailureEmptyTitle(AcceptanceTester $I)
    {
        $I->wantTo('Kiểm tra lỗi khi để trống tên chuyên mục');
        $I->amOnUrl($I->getDomain() . '/admin/vi/' . $this->module . '/cat/');
        $I->wait(1);
        $I->waitForElement('input[name="title"]', 10);

        $I->fillField('input[name="title"]', '');
        // Cuộn đến form submit
        #$I->executeJS('document.querySelector("#form-cat-content button[type=\'submit\']").scrollIntoView({block: "center"});');
        $I->scrollTo('button[type="submit"]');
        $I->wait(1);
        #$I->executeJS('document.querySelector("#form-cat-content button[type=\'submit\']").click();');
        $I->click('button[type="submit"]');

        // Thông báo từ lang_module['cat_empty_title']
        $I->waitForText('Chuyên mục chưa có tên', 10);
    }
}
