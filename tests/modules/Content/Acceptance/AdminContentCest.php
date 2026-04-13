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

class AdminContentCest
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
     * @group content-article
     */
    public function testAddContentSuccess(AcceptanceTester $I)
    {
        $I->wantTo('Thêm bài viết mới thành công');
        $I->amOnUrl($I->getDomain() . '/admin/vi/' . $this->module . '/content/');
        $I->wait(1);
        $I->waitForElement('input[name="title"]', 10);

        $title = 'Article Test ' . time();
        $description = 'Description for Article Test ' . time();
        $bodytext = 'Full content for Article Test ' . time();

        $I->fillField('input[name="title"]', $title);
        $I->fillField('textarea[name="description"]', $description);

        // Xử lý Bodytext (Tìm editor có ID kết thúc bằng _bodytext để đảm bảo tương thích mọi module_data)
        $I->executeJS("
            for (var key in window.nveditor) {
                if (key.endsWith('_bodytext')) {
                    window.nveditor[key].setData('" . $bodytext . "');
                }
            }
        ");

        // Cuộn đến form submit
        $I->scrollTo('button[type="submit"]');
        $I->wait(1);

        $I->click('button[type="submit"]');
        $I->wait(1);

        // Chờ thông báo thành công (NukeViet Global lang: save_success)
        $I->waitForText('Các thay đổi đã được ghi nhận', 10);

        // Đợi chuyển hướng về danh sách hoặc reload
        $I->wait(5);
        $I->see($title, 'table');
    }

    /**
     * @group content
     * @group content-article
     */
    public function testAddContentFailureEmptyTitle(AcceptanceTester $I)
    {
        $I->wantTo('Kiểm tra lỗi khi để trống tiêu đề bài viết');
        $I->amOnUrl($I->getDomain() . '/admin/vi/' . $this->module . '/content/');
        $I->wait(1);
        $I->waitForElement('input[name="title"]', 10);

        $I->fillField('input[name="title"]', '');

        $I->scrollTo('button[type="submit"]');
        $I->wait(1);
        $I->click('button[type="submit"]');

        // Thông báo từ lang_module['empty_title']
        $I->waitForText('Bài viết chưa có tiêu đề', 10);
    }
}
