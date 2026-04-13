<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\modules\Content\API;

use Tests\Support\ApiTester;

class AdminContentApiCest
{
    private $contentId;
    private $contentTitle;
    private $module;

    public function _inject()
    {
        $this->module = getenv('NV_MODULE') ?: 'Content';
    }


    /**
     * @group content
     * @group content-api
     */
    public function testGetContentList(ApiTester $I)
    {
        $I->wantTo('Lấy danh sách bài viết qua API');

        $I->sendApiRequest($this->module, 'ContentGetList');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'success', 'code' => '0000']);
        $I->seeResponseJsonMatchesJsonPath('$.items');
    }

    /**
     * @group content
     * @group content-api
     */
    public function testAddContentEmptyTitle(ApiTester $I)
    {
        $I->wantTo('Thêm bài viết với tiêu đề rỗng — phải báo lỗi');

        $I->sendApiRequest($this->module, 'ContentAdd', [
            'title' => '',
            'bodytext' => 'Content here',
            'status' => 1,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'error']);
        $I->seeResponseContains('Bài viết chưa có tiêu đề');
    }

    /**
     * @group content
     * @group content-api
     */
    public function testAddContentEmptyBody(ApiTester $I)
    {
        $I->wantTo('Thêm bài viết với nội dung rỗng — phải báo lỗi');

        $I->sendApiRequest($this->module, 'ContentAdd', [
            'title' => 'Title only',
            'bodytext' => '',
            'status' => 1,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'error']);
        $I->seeResponseContains('Bài viết chưa có nội dung');
    }

    /**
     * @group content
     * @group content-api
     */
    public function testAddContentSuccess(ApiTester $I)
    {
        $title = 'API Content ' . time();
        $this->contentTitle = $title;
        $I->wantTo('Thêm mới bài viết qua API: ' . $title);

        $I->sendApiRequest($this->module, 'ContentAdd', [
            'title' => $title,
            'bodytext' => '<p>This is content added via API</p>',
            'description' => 'Summary of article',
            'status' => 1,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'success', 'code' => '0000']);
        $I->seeResponseJsonMatchesJsonPath('$.item.id');

        $this->contentId = $I->grabDataFromResponseByJsonPath('$.item.id')[0];
    }

    /**
     * @group content
     * @group content-api
     */
    public function testGetContentDetailSuccess(ApiTester $I)
    {
        $I->wantTo('Lấy chi tiết bài viết vừa tạo qua API, ID: ' . $this->contentId);

        $I->sendApiRequest($this->module, 'ContentGetDetail', [
            'id' => $this->contentId
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'code' => '0000',
            'item' => [
                'id' => $this->contentId,
                'title' => $this->contentTitle
            ]
        ]);
    }
}
