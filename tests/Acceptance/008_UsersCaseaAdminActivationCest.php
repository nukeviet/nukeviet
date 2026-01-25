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

/**
 *
 */
class UsersCaseaAdminActivationCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    /**
     * @param AcceptanceTester $I
     *
     * @group users
     * @group all
     */
    public function testTurnOnAdminActivation(AcceptanceTester $I)
    {
        $I->wantTo('Enable the admin-activation-required registration option');
        $I->login();
        $I->amOnUrl($I->getDomain() . '/admin/vi/users/config/');
        $I->seeElement('#element_allowuserreg');

        if (!$I->tryToSeeOptionIsSelected('[name="allowuserreg"]', 'Người quản trị kích hoạt')) {
            $I->selectOption('[name="allowuserreg"]', ['value' => '3']);
        }

        $I->seeElementInDOM('#element_nv_unick_type');
        $I->scrollTo('#element_nv_unick_type');
        $I->wait(1);
        $I->waitForElementVisible('#element_nv_unick_type', 5);

        if ($I->tryToSeeCheckboxIsChecked('#element_email_plus_equivalent')) {
            $I->click('#element_email_plus_equivalent');
            $I->wait(1);
        }

        $I->submitForm('[class="ajax-submit"]', []);
        $I->waitForElementVisible('#site-toasts', 60);
        $I->wait(1);
        $I->see('Các thay đổi đã được ghi nhận');
    }

    /**
     * @param AcceptanceTester $I
     *
     * @group users
     * @group all
     */
    public function testUserRegister(AcceptanceTester $I)
    {
        $I->wantTo('Test the member registration feature on the site');
        $I->amOnUrl($I->getDomain() . '/vi/users/register/');
        $I->seeElement('[name="first_name"]');

        // Cuộn đến ô nhập tên
        $I->scrollTo('[name="first_name"]');
        $I->wait(1);
        $I->waitForElementVisible('[name="first_name"]', 5);

        $I->fillField('input[name="first_name"]', 'Nguyễn Văn B');
        $I->fillField('input[name="username"]', 'testuser2');
        $I->fillField('input[name="email"]', str_replace('@', '+v2@', $_ENV['NV_EMAIL']));
        $I->fillField('input[name="password"]', $_ENV['NV_PASSWORD']);
        $I->fillField('input[name="re_password"]', $_ENV['NV_PASSWORD']);

        // Cuộn đến ngày tháng
        $I->scrollTo('[name="birthday"]');
        $I->wait(1);
        $I->waitForElementVisible('[name="birthday"]', 5);
        $I->executeJS("jQuery('[name=\"birthday\"]').focus();");
        $I->executeJS("jQuery('[name=\"birthday\"]').datepicker('setDate', new Date('1992-01-01'));");
        $I->executeJS("jQuery('[name=\"birthday\"]').datepicker('hide');");
        $I->seeInField('[name="birthday"]', '01/01/1992');

        $I->fillField(['name' => 'question'], $_ENV['NV_QUESTION']);
        $I->fillField(['name' => 'answer'], $_ENV['NV_ANSWER']);

        $I->checkOption('input[name="agreecheck"]');
        $I->click('//input[@value="Đăng ký tài khoản người dùng"]');
        $I->waitForText('Tài khoản của bạn đã được tạo');
    }
}
