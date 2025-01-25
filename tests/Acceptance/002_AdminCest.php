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

class AdminLoginCest
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
    public function login(AcceptanceTester $I)
    {
        $I->login();
    }

    /**
     * @param \Tests\Support\AcceptanceTester $I
     * @return void
     */
    private function setSMTPConfig(AcceptanceTester $I)
    {
        $I->login();

        $I->amOnUrl($I->getDomain() . '/admin/vi/settings/smtp/');
        $I->waitForElement('[data-toggle="smtp_test"]', 5);

        // Cuộn đến phuong thức gửi mail
        $I->seeElementInDOM('[for="mailer_mode_smtp"]');
        $I->scrollTo('[for="mailer_mode_smtp"]');
        $I->wait(1);
        $I->waitForElementVisible('[for="mailer_mode_smtp"]', 5);

        // Chọn phương thức SMTP nếu nó không chọn
        if (!$I->tryToSeeCheckboxIsChecked('input[name="mailer_mode"][value="smtp"]')) {
            $I->click('label[for="mailer_mode_smtp"]');
        }
        $I->wait(1);

        // Cuộn đến form cấu hình SMTP
        $I->seeElementInDOM('[name="smtp_host"]');
        $I->scrollTo('[name="smtp_host"]');
        $I->wait(1);
        $I->waitForElementVisible('[name="smtp_host"]', 5);

        // Điền thông tin SMTP
        $I->fillField('input[name="smtp_host"]', $_ENV['SMTP_HOST']);
        $I->fillField('input[name="smtp_port"]', $_ENV['SMTP_PORT']);
        $I->click('label[for="verify_peer_ssl_0"]');
        $I->click('label[for="verify_peer_name_ssl_0"]');
        $I->fillField('input[name="smtp_username"]', $_ENV['SMTP_USER']);
        $I->fillField('input[name="smtp_password"]', $_ENV['SMTP_PASS']);
    }

    /**
     * @param AcceptanceTester $I
     *
     * @group sendmail
     * @group all
     */
    public function sendEmailSmtp(AcceptanceTester $I)
    {
        $I->wantTo('Test send email with SMTP');
        $this->setSMTPConfig($I);

        // Gửi thử nghiệm
        $I->click('[data-toggle="smtp_test"]');
        $I->waitForElementVisible('#site-toasts', 60);
        $I->wait(1);
        $I->see('Gửi email thành công');
    }

    /**
     * @param \Tests\Support\AcceptanceTester $I
     * @return void
     *
     * @group off-mail
     */
    public function offSendMail(AcceptanceTester $I)
    {
        $I->wantTo('Turn off send mail');

        $I->login();

        $I->amOnUrl($I->getDomain() . '/admin/vi/settings/smtp/');
        $I->waitForElement('[data-toggle="smtp_test"]', 5);

        // Cuộn đến chỗ tệp mẫu thư
        $I->seeElementInDOM('#element_mail_tpl');
        $I->scrollTo('#element_mail_tpl');
        $I->wait(1);
        $I->waitForElementVisible('#element_mail_tpl', 5);

        // Chọn phương thức "No" nếu nó không chọn
        if (!$I->tryToSeeCheckboxIsChecked('input[name="mailer_mode"][value="no"]')) {
            $I->click('label[for="mailer_mode_no"]');
        }
        $I->wait(1);

        // Lưu cấu hình
        $I->submitForm('#sendmail-settings', []);
        $I->waitForElementVisible('#site-toasts', 60);
        $I->wait(1);
        $I->see('Các thay đổi đã được ghi nhận');
    }

    /**
     * @param \Tests\Support\AcceptanceTester $I
     * @return void
     *
     * @group smtp
     */
    public function saveConfigSmtp(AcceptanceTester $I)
    {
        $I->wantTo('Save config SMTP');
        $this->setSMTPConfig($I);

        // Lưu cấu hình
        $I->submitForm('#sendmail-settings', []);
        $I->waitForElementVisible('#site-toasts', 60);
        $I->wait(1);
        $I->see('Các thay đổi đã được ghi nhận');
    }
}
