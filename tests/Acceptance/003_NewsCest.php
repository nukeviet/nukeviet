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

class NewsAutoTagsCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    /**
     * @param AcceptanceTester $I
     *
     * @group install
     * @group all
     */
    public function activeAutoTags(AcceptanceTester $I)
    {
        $I->wantTo('Turn on auto tags');
        $I->login();

        $I->amOnUrl($I->getDomain() . '/admin/vi/news/setting/');

        $I->waitForElement('[name="auto_tags"]', 5);
        if ($I->tryToDontSeeCheckboxIsChecked('[name="auto_tags"]')) {
            $I->checkOption('[name="auto_tags"]');
        }
        $I->click('#btn_savesetting');
    }

    /**
     * @param AcceptanceTester $I
     *
     * @group install
     * @group all
     */
    public function postNews(AcceptanceTester $I)
    {
        $I->wantTo('Post a new article');
        $I->login();

        $title = 'Bảo đảm công bằng trong thu, nộp tiền cấp quyền khai thác khoáng sản';
        $content = '<p>Theo đó, một doanh nghiệp kinh doanh trong lĩnh vực khai thác khoáng sản đã dùng một số tài sản bảo đảm là quyền sử dụng đất thuê, quyền khai thác khoáng sản, phương tiện vận tải, máy móc… để bảo đảm cho khoản vay của mình tại Agribank Chi nhánh Hòa Bình.&nbsp;</p><p>Do không đủ khả năng trả nợ và khoản nợ tại Ngân hàng đã quá hạn, Agribank đã đưa lô tài sản này bán đấu giá để thu hồi nợ. Cụ thể, lô tài sản gồm: quyền sử dụng đất thuê với diện tích 11.828,3 m2 (đất sản xuất vật liệu xây dựng, gốm sứ tại xã Mông Hóa và xã Dân Hạ, huyện Kỳ Sơn, tỉnh Hòa Bình; quyền khai thác đá vôi làm vật liệu xây dựng thông thường tại vị trí khu vực khai thác núi Thau, xã Dân Hạ và núi Mu Đôi, xã Mông Hóa, huyện Kỳ Sơn (diện tích khu vực khai thác là 5,6ha, trữ lượng đá vôi khai thác ở cấp 121 là 3,6 triệu m3, thời hạn khai thác đến ngày 25/10/2040).</p><p>Được biết, phiên đấu giá sẽ được tổ chức theo hình thức bỏ phiếu trực tiếp tại cuộc đấu giá, phương thức trả giá lên.&nbsp;</p>';

        $I->amOnUrl($I->getDomain() . '/admin/vi/news/content/');
        $I->see('Thêm bài viết');

        $I->fillField(['name' => 'title'], $title);
        //$I->checkOption('[name="catids[]"][value="1"]');
        $I->click('label[for="catid_1"]');

        $I->wait(1);
        $I->executeJS("window.nveditor.news_bodyhtml.setData('" . $content . "');");

        //$I->click('[name="status1"]');
        $I->executeJS('document.querySelector("[name=\"status1\"]").click();');
        $I->waitForText('Đã ghi dữ liệu thành công', 5);
    }
}
