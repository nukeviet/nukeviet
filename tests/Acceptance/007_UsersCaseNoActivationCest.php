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
class UsersCaseNoActivationCest
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
    public function testTurnOnNoActivation(AcceptanceTester $I)
    {
        $I->wantTo('Enable the no-activation-required registration option');
        $I->login();
        $I->amOnUrl($I->getDomain() . '/admin/vi/users/config/');
        $I->seeElement('#element_allowuserreg');

        if (!$I->tryToSeeOptionIsSelected('[name="allowuserreg"]', 'Không cần kích hoạt')) {
            $I->selectOption('[name="allowuserreg"]', ['value' => '1']);
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
    public function turnOffSendMail(AcceptanceTester $I)
    {
        $I->wantTo('Turn off sending emails');
        $I->login();

        $I->amOnUrl($I->getDomain() . '/admin/vi/settings/smtp/');

        // Cuộn đến phuong thức gửi mail
        $I->seeElementInDOM('[for="element_mail_tpl"]');
        $I->scrollTo('[for="element_mail_tpl"]');
        $I->wait(1);
        $I->waitForElementVisible('[for="element_mail_tpl"]', 5);

        if (!$I->tryToSeeCheckboxIsChecked('#mailer_mode_no')) {
            $I->checkOption('#mailer_mode_no');
        }

        $I->submitForm('#sendmail-settings', []);
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
    public function testDisableCaptchaRegister(AcceptanceTester $I)
    {
        $I->wantTo('Disable captcha when users register an account');
        $I->login();
        $I->amOnUrl($I->getDomain() . '/admin/vi/settings/security/?selectedtab=2');

        // Cuộn đến heading captcha
        $I->seeElementInDOM('#settingCaptcha-headingThree');
        $I->scrollTo('#settingCaptcha-headingThree');
        $I->wait(1);
        $I->waitForElementVisible('#settingCaptcha-headingThree', 5);

        $I->seeElement('#settingCaptcha-headingThree');
        $I->click('#settingCaptcha-headingThree');
        $I->waitForElementVisible('#captcha_area_r', 5);

        if ($I->tryToSeeCheckboxIsChecked('#captcha_area_r')) {
            $I->uncheckOption('#captcha_area_r');
        }

        $I->submitForm('#captarea-settings', []);
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

        $I->fillField('input[name="first_name"]', 'Nguyễn Văn A');
        $I->fillField('input[name="username"]', 'testuser1');
        $I->fillField('input[name="email"]', str_replace('@', '+v1@', $_ENV['NV_EMAIL']));
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
        $I->waitForText('Đăng ký tài khoản thành công');
    }
}
