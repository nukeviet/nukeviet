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

use Codeception\Scenario;
use Facebook\WebDriver\WebDriverKeys;
use NukeViet\Core\LoginTracker;
use Tests\Support\AcceptanceTester;
use Tests\Support\LoginCaptchaTrait;

/**
 * Sai mã xác thực 2 bước và sai mật khẩu xác nhận nhiều lần, đếm theo tài khoản độc lập với IP.
 *
 * - Sai mã 2 bước đủ ngưỡng: bước mật khẩu bị bắt captcha (admin quay về bước 1, site gắn captcha vào form)
 * - Sai mật khẩu xác nhận đủ ngưỡng khi đã đăng nhập: phiên đăng nhập bị đăng xuất
 *
 * Test chèn sẵn bộ đếm ở mức N-1 rồi nhập sai một lần, không nhập sai N lần thật.
 * Các case khôi phục kết thúc bằng một lần đăng nhập 2FA thành công, Blocker theo IP được xóa luôn.
 * 2FA, mã dự phòng và cấu hình được khôi phục ở _after/_failed (xem LoginCaptchaTrait).
 *
 * Điều kiện: login_number_tracking > 0, captcha_type là captcha hình.
 */
class TwoFactorCaptchaCest
{
    use LoginCaptchaTrait;

    private const ADMIN_NOTICE = 'đã đăng nhập sai nhiều lần';
    private const ADMIN_LOGIN_OK = 'Bạn đã đăng nhập thành công';
    private const SITE_TFA_NOTICE = 'sai mã xác thực nhiều lần';
    private const RELOGIN_NOTICE = 'phiên đăng nhập đã kết thúc';
    private const LOGIN_OK = 'Đăng nhập hệ thống thành công';

    private const BACKUP_OK = 'tstcode1';
    private const BACKUP_WRONG = 'zzzzzzzz';

    public function _before(AcceptanceTester $I, Scenario $scenario)
    {
        $this->loadLoginState($I, $scenario);
        if ($this->captchaType != 'captcha') {
            $this->changeConfig($I, ['captcha_type' => 'captcha']);
        }
    }

    public function _after(AcceptanceTester $I)
    {
        $this->cleanupLoginState($I);
    }

    public function _failed(AcceptanceTester $I)
    {
        $this->cleanupLoginState($I);
    }

    /**
     * @param AcceptanceTester $I
     * @param Scenario         $scenario
     */
    private function requireNoAdminCaptchaArea(Scenario $scenario): void
    {
        if (in_array('a', $this->captchaArea, true)) {
            $scenario->skip('captcha_area đang bật cho admin, form luôn có captcha');
        }
    }

    /**
     * @param Scenario $scenario
     */
    private function requireNoSiteCaptchaArea(Scenario $scenario): void
    {
        if (in_array('l', $this->captchaArea, true)) {
            $scenario->skip('captcha_area đang bật cho khu vực đăng nhập, form luôn có captcha');
        }
    }

    /**
     * Đăng nhập admin bằng mật khẩu tới bước 2FA
     *
     * @param AcceptanceTester $I
     */
    private function adminToStep2(AcceptanceTester $I): void
    {
        $this->newSession($I, '/admin/index.php');
        $I->fillField(['name' => 'nv_login'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'nv_password'], $_ENV['NV_PASSWORD']);
        $I->click('[type="submit"]');
        $I->waitForElementVisible('[name="nv_totppin"]', 5);
    }

    /**
     * Admin nhập sai mã 2 bước lần thứ N: quay về bước 1 có captcha. Trả về random_num trước khi tải lại
     *
     * @param AcceptanceTester $I
     * @param string           $field nv_totppin hoặc nv_backupcodepin
     * @param string           $code
     * @return int|null
     */
    private function adminWrongStep2(AcceptanceTester $I, string $field, string $code): ?int
    {
        $before = $I->grabCaptchaRandom();
        $I->fillField(['name' => $field], $code);
        $I->pressKey('[name="' . $field . '"]', WebDriverKeys::ENTER);

        $I->waitForElement('#nv_login', 5);
        $I->seeElementInDOM(self::$adminCaptcha);
        $I->see(self::ADMIN_NOTICE);
        $I->dontSeeElement('[name="nv_totppin"]');

        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('tfa', $this->userid)]);
        $I->seeInDatabase($this->table, ['keyname' => $this->accountKey(), 'count' => $this->limit]);

        return $before;
    }

    /**
     * Admin đăng nhập lại kèm captcha tới bước 2FA
     *
     * @param AcceptanceTester $I
     * @param int|null         $before
     */
    private function adminReloginWithCaptcha(AcceptanceTester $I, ?int $before): void
    {
        $this->fillAdminCaptcha($I, $before);
        $I->fillField(['name' => 'nv_login'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'nv_password'], $_ENV['NV_PASSWORD']);
        $I->click('[type="submit"]');
        $I->waitForElementVisible('[name="nv_totppin"]', 5);
        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * Đăng nhập site bằng mật khẩu tới bước 2FA
     *
     * @param AcceptanceTester $I
     */
    private function siteToStep2(AcceptanceTester $I): void
    {
        $this->newSession($I, '/vi/users/login/');
        $I->fillField(['name' => 'nv_login'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'nv_password'], $_ENV['NV_PASSWORD']);
        if ($I->executeJS('return !!document.querySelector(\'form[data-toggle="userLogin"][data-captcha]\');')) {
            $this->submitWithSiteCaptcha($I);
        } else {
            $this->clickVisibleSubmit($I);
        }
        $I->waitForElementVisible('[name="nv_totppin"]', 5);
        $I->dontSeeElementInDOM(self::$formCaptcha);
    }

    /**
     * Site nhập sai mã 2 bước lần thứ N: có thông báo, form được gắn captcha
     *
     * @param AcceptanceTester $I
     * @param string           $field
     * @param string           $code
     */
    private function siteWrongStep2(AcceptanceTester $I, string $field, string $code): void
    {
        $I->fillField(['name' => $field], $code);
        $this->clickVisibleSubmit($I);

        $I->waitForText(self::SITE_TFA_NOTICE, 5);
        $I->seeElementInDOM(self::$formCaptcha);
        $I->dontSee(self::LOGIN_OK);

        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('tfa', $this->userid)]);
        $I->seeInDatabase($this->table, ['keyname' => $this->accountKey(), 'count' => $this->limit]);
    }

    /**
     * Đăng nhập site thường (tài khoản không bật 2FA)
     *
     * @param AcceptanceTester $I
     */
    private function siteLogin(AcceptanceTester $I): void
    {
        $this->newSession($I, '/vi/users/login/');
        $I->fillField(['name' => 'nv_login'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'nv_password'], $_ENV['NV_PASSWORD']);
        if ($I->executeJS('return !!document.querySelector(\'form[data-toggle="userLogin"][data-captcha]\');')) {
            $this->submitWithSiteCaptcha($I);
        } else {
            $this->clickVisibleSubmit($I);
        }
        $I->waitForText(self::LOGIN_OK, 5);
    }

    /**
     * Mở trang xác nhận mật khẩu verify-password qua link quản lý passkey trong trang sửa thông tin.
     * Trang xóa dữ liệu cá nhân chặn tài khoản quản trị trước bước xác nhận nên không dùng được
     *
     * @param AcceptanceTester $I
     */
    private function openVerifyPassword(AcceptanceTester $I): void
    {
        $I->amOnUrl($I->getDomain() . '/vi/users/editinfo/passkey/');
        $url = (string) $I->executeJS('const a = document.querySelector(\'a[href*="verify-password"]\'); return a ? a.href : "";');
        if ($url === '') {
            throw new \RuntimeException('Không tìm thấy link xác nhận mật khẩu ở trang quản lý passkey');
        }
        $I->amOnUrl($url);
        $I->waitForElementVisible('[name="password"]', 5);
        $I->seeInCurrentUrl('verify-password');
    }

    /**
     * Submit form có ô password, giải captcha nếu form có captcha
     *
     * @param AcceptanceTester $I
     */
    private function submitPasswordForm(AcceptanceTester $I): void
    {
        $form = 'form:has([name="password"])';
        if ($I->executeJS('return !!document.querySelector(arguments[0] + "[data-captcha]");', [$form])) {
            $this->submitWithSiteCaptcha($I, $form);
        } else {
            $this->clickVisibleSubmit($I, $form);
        }
    }

    /**
     * 1. Admin sai mã 2 bước lần thứ N: hủy bước 1, quay về form mật khẩu có captcha,
     * khóa mật khẩu của tài khoản chuyển sang cần captcha
     *
     * @group two-factor-captcha
     */
    public function adminTfaFailedRequiresCaptcha(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoAdminCaptchaArea($scenario);
        $this->enable2fa($I);
        $this->haveAttempts($I, LoginTracker::getScopeKey('tfa', $this->userid), $this->limit - 1);

        $this->adminToStep2($I);
        $this->adminWrongStep2($I, 'nv_totppin', $I->wrongTotpCode(self::$tfaSecret));
    }

    /**
     * 2. Site sai mã 2 bước lần thứ N: form được gắn captcha, khóa mật khẩu chuyển sang cần captcha
     *
     * @group two-factor-captcha
     */
    public function siteTfaFailedRequiresCaptcha(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoSiteCaptchaArea($scenario);
        $this->enable2fa($I);
        $this->haveAttempts($I, LoginTracker::getScopeKey('tfa', $this->userid), $this->limit - 1);

        $this->siteToStep2($I);
        $this->siteWrongStep2($I, 'nv_totppin', $I->wrongTotpCode(self::$tfaSecret));
    }

    /**
     * 3. Đã đăng nhập, sai mật khẩu xác nhận (two-step-verification) lần thứ N: bị đăng xuất
     *
     * @group two-factor-captcha
     */
    public function confirmPasswordFailedLogsOut(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoSiteCaptchaArea($scenario);
        $this->siteLogin($I);

        $this->haveAttempts($I, LoginTracker::getScopeKey('pwd', $this->userid), $this->limit - 1);
        $I->amOnUrl($I->getDomain() . '/vi/two-step-verification/');
        $I->waitForElementVisible('[name="password"]', 5);
        $I->fillField(['name' => 'password'], 'wrong-password-' . time());
        $I->pressKey('[name="password"]', WebDriverKeys::ENTER);

        $I->waitForText(self::RELOGIN_NOTICE, 5);
        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('pwd', $this->userid)]);

        // Phiên đã bị đăng xuất: trang đăng nhập hiện form thay vì chuyển hướng về trang thành viên
        $I->amOnUrl($I->getDomain() . '/vi/users/login/');
        $I->seeElement('[name="nv_login"]');
    }

    /**
     * 4. Admin sai mã 2 bước lần thứ N, đăng nhập lại kèm captcha rồi nhập đúng mã: vào được admin
     *
     * @group two-factor-captcha
     */
    public function adminTfaRecoverWithCaptcha(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoAdminCaptchaArea($scenario);
        $this->enable2fa($I);
        $this->haveAttempts($I, LoginTracker::getScopeKey('tfa', $this->userid), $this->limit - 1);

        $this->adminToStep2($I);
        $before = $this->adminWrongStep2($I, 'nv_totppin', $I->wrongTotpCode(self::$tfaSecret));
        $this->adminReloginWithCaptcha($I, $before);

        $I->fillField(['name' => 'nv_totppin'], $I->totpCode(self::$tfaSecret));
        $I->pressKey('[name="nv_totppin"]', WebDriverKeys::ENTER);
        $I->waitForText(self::ADMIN_LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('tfa', $this->userid)]);
        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * 5. Admin sai mã dự phòng lần thứ N, đăng nhập lại kèm captcha rồi dùng mã dự phòng đúng
     *
     * @group two-factor-captcha
     */
    public function adminBackupCodeRecoverWithCaptcha(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoAdminCaptchaArea($scenario);
        $this->enable2fa($I);
        $code = $this->addBackupCode($I, self::BACKUP_OK);
        $this->haveAttempts($I, LoginTracker::getScopeKey('tfa', $this->userid), $this->limit - 1);

        $this->adminToStep2($I);
        $I->click('a[data-toggle="login2step_change"]');
        $I->waitForElementVisible('[name="nv_backupcodepin"]', 5);
        $before = $this->adminWrongStep2($I, 'nv_backupcodepin', self::BACKUP_WRONG);
        $this->adminReloginWithCaptcha($I, $before);

        $I->click('a[data-toggle="login2step_change"]');
        $I->waitForElementVisible('[name="nv_backupcodepin"]', 5);
        $I->fillField(['name' => 'nv_backupcodepin'], self::BACKUP_OK);
        $I->pressKey('[name="nv_backupcodepin"]', WebDriverKeys::ENTER);
        $I->waitForText(self::ADMIN_LOGIN_OK, 5);

        $I->seeInDatabase($this->prefix . '_users_backupcodes', ['userid' => $this->userid, 'code' => $code, 'is_used' => 1]);
        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('tfa', $this->userid)]);
    }

    /**
     * 6. Site sai mã 2 bước lần thứ N, nhập đúng mã rồi giải captcha: đăng nhập được
     *
     * @group two-factor-captcha
     */
    public function siteTfaRecoverWithCaptcha(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoSiteCaptchaArea($scenario);
        $this->enable2fa($I);
        $this->haveAttempts($I, LoginTracker::getScopeKey('tfa', $this->userid), $this->limit - 1);

        $this->siteToStep2($I);
        $this->siteWrongStep2($I, 'nv_totppin', $I->wrongTotpCode(self::$tfaSecret));

        $I->fillField(['name' => 'nv_totppin'], $I->totpCode(self::$tfaSecret));
        $this->submitWithSiteCaptcha($I);
        $I->waitForText(self::LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('tfa', $this->userid)]);
        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * 7. Site sai mã dự phòng lần thứ N, dùng mã dự phòng đúng kèm captcha: đăng nhập được, mã bị đánh dấu đã dùng
     *
     * @group two-factor-captcha
     */
    public function siteBackupCodeRecoverWithCaptcha(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoSiteCaptchaArea($scenario);
        $this->enable2fa($I);
        $code = $this->addBackupCode($I, self::BACKUP_OK);
        $this->haveAttempts($I, LoginTracker::getScopeKey('tfa', $this->userid), $this->limit - 1);

        $this->siteToStep2($I);
        $I->executeJS('document.querySelector(\'[data-toggle="2fa-choose"][data-method="code"]\').click();');
        $I->waitForElementVisible('[name="nv_backupcodepin"]', 5);
        $this->siteWrongStep2($I, 'nv_backupcodepin', self::BACKUP_WRONG);

        $I->fillField(['name' => 'nv_backupcodepin'], self::BACKUP_OK);
        $this->submitWithSiteCaptcha($I);
        $I->waitForText(self::LOGIN_OK, 5);

        $I->seeInDatabase($this->prefix . '_users_backupcodes', ['userid' => $this->userid, 'code' => $code, 'is_used' => 1]);
        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('tfa', $this->userid)]);
    }

    /**
     * 8. Bật captcha khu vực đăng nhập: bước 2FA được bỏ qua captcha, nhưng sai mã đủ ngưỡng thì bị hỏi lại
     *
     * @group two-factor-captcha
     */
    public function siteTfaWithCaptchaAreaOn(AcceptanceTester $I)
    {
        if (!in_array('l', $this->captchaArea, true)) {
            $this->changeConfig($I, ['captcha_area' => implode(',', array_merge($this->captchaArea, ['l']))]);
        }
        $this->enable2fa($I);
        $this->haveAttempts($I, LoginTracker::getScopeKey('tfa', $this->userid), $this->limit - 1);

        $this->siteToStep2($I);
        $this->siteWrongStep2($I, 'nv_totppin', $I->wrongTotpCode(self::$tfaSecret));

        $I->fillField(['name' => 'nv_totppin'], $I->totpCode(self::$tfaSecret));
        $this->submitWithSiteCaptcha($I);
        $I->waitForText(self::LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('tfa', $this->userid)]);
    }

    /**
     * 9. Đã đăng nhập, sai mật khẩu ở trang verify-password lần thứ N: bị đăng xuất
     *
     * @group two-factor-captcha
     */
    public function verifyPasswordFailedLogsOut(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoSiteCaptchaArea($scenario);
        $this->siteLogin($I);
        $this->haveAttempts($I, LoginTracker::getScopeKey('pwd', $this->userid), $this->limit - 1);

        $this->openVerifyPassword($I);
        $I->fillField('[name="password"]', 'wrong-password-' . time());
        $this->submitPasswordForm($I);

        $I->waitForText(self::RELOGIN_NOTICE, 5);
        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('pwd', $this->userid)]);

        $I->amOnUrl($I->getDomain() . '/vi/users/login/');
        $I->seeElement('[name="nv_login"]');
    }

    /**
     * 10. Xác nhận đúng mật khẩu ở trang verify-password thì xóa bộ đếm
     *
     * @group two-factor-captcha
     */
    public function verifyPasswordSuccessResets(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoSiteCaptchaArea($scenario);
        $this->siteLogin($I);
        $this->haveAttempts($I, LoginTracker::getScopeKey('pwd', $this->userid), 2);

        $this->openVerifyPassword($I);
        $I->fillField('[name="password"]', $_ENV['NV_PASSWORD']);
        $this->submitPasswordForm($I);

        $I->waitForJS('return location.href.indexOf("verify-password") === -1;', 5);
        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('pwd', $this->userid)]);
    }

    /**
     * 11. Xác nhận đúng mật khẩu ở trang two-step-verification thì xóa bộ đếm
     *
     * @group two-factor-captcha
     */
    public function confirmPasswordSuccessResets(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNoSiteCaptchaArea($scenario);
        $this->siteLogin($I);
        $this->haveAttempts($I, LoginTracker::getScopeKey('pwd', $this->userid), 2);

        $I->amOnUrl($I->getDomain() . '/vi/two-step-verification/');
        $I->waitForElementVisible('[name="password"]', 5);
        $I->fillField(['name' => 'password'], $_ENV['NV_PASSWORD']);
        $I->pressKey('[name="password"]', WebDriverKeys::ENTER);

        $I->waitForElementNotVisible('[name="password"]', 5);
        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('pwd', $this->userid)]);
    }
}
