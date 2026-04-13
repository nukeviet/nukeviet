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

class AdminCatApiCest
{
    private $catid;
    private $catTitle;
    private $module;

    public function _inject()
    {
        $this->module = getenv('NV_MODULE') ?: 'Content';
    }


    /**
     * @group content
     * @group content-api
     */
    public function testGetCatList(ApiTester $I)
    {
        $I->wantTo('Lấy danh sách chủ đề qua API');

        $I->sendApiRequest($this->module, 'CatGetList');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'success', 'code' => '0000']);
        $I->seeResponseJsonMatchesJsonPath('$.items');
    }

    /**
     * @group content
     * @group content-api
     */
    public function testAddCatEmptyTitle(ApiTester $I)
    {
        $I->wantTo('Thêm chủ đề với tiêu đề rỗng — phải báo lỗi');

        $I->sendApiRequest($this->module, 'CatAdd', [
            'title' => '',
            'status' => 1,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'error', 'code' => '0000']);
    }

    /**
     * @group content
     * @group content-api
     */
    public function testAddCatSuccess(ApiTester $I)
    {
        $title = 'Chủ đề ' . date('Y-m-d H:i:s');
        $this->catTitle = $title;
        $I->wantTo('Thêm mới chủ đề: ' . $title);

        $I->sendApiRequest($this->module, 'CatAdd', [
            'title' => $title,
            'keywords' => 'chủ đề, test',
            'status' => 1,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'success', 'code' => '0000']);
        $I->seeResponseJsonMatchesJsonPath('$.item.catid');

        $this->catid = $I->grabDataFromResponseByJsonPath('$.item.catid')[0];
    }

    /**
     * @group content
     * @group content-api
     */
    public function testGetCatDetailNotFound(ApiTester $I)
    {
        $I->wantTo('Lấy chi tiết chủ đề khi ID không tồn tại (ID 999999)');

        $I->sendApiRequest($this->module, 'CatGetDetail', [
            'catid' => 999999
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'error', 'code' => '0000', 'message' => 'error_no_data']);
    }

    /**
     * @group content
     * @group content-api
     */
    public function testGetCatDetailSuccess(ApiTester $I)
    {
        $I->wantTo('Lấy chi tiết một chủ đề thành công với ID ' . $this->catid);

        $I->sendApiRequest($this->module, 'CatGetDetail', [
            'catid' => $this->catid
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'code' => '0000',
            'item' => [
                'title' => $this->catTitle
            ]
        ]);
        $I->seeResponseJsonMatchesJsonPath('$.item.catid');
    }
}
