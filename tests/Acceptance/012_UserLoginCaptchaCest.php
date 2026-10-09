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
 * Đăng nhập ngoài site (module users) bắt captcha theo tài khoản khi bị dò mật khẩu.
 *
 * Khác với admin, form site không tải lại trang: server trả status captcha kèm thuộc tính captcha,
 * JS gắn vào form, người dùng bấm đăng nhập lại thì modal captcha hiện ra.
 *
 * Test không nhập sai mật khẩu thật mà chèn sẵn bộ đếm vào bảng _users_login_attempts,
 * tránh để Blocker chặn IP của máy test.
 *
 * Điều kiện: login_number_tracking > 0, captcha_area không bật khu vực đăng nhập (l), captcha_type là captcha hình.
 * Case theme future tự skip nếu theme future chưa được thiết lập cho ngôn ngữ vi.
 */
class UserLoginCaptchaCest
{
    use LoginCaptchaTrait;

    private const NOTICE = 'bấm đăng nhập lại và nhập mã xác nhận';
    private const LOGIN_OK = 'Đăng nhập hệ thống thành công';
    private const GHOST = 'ghost_captcha_test';

    public function _before(AcceptanceTester $I, Scenario $scenario)
    {
        $this->loadLoginState($I, $scenario);
        if (in_array('l', $this->captchaArea, true)) {
            $scenario->skip('captcha_area đang bật cho khu vực đăng nhập, form luôn có captcha');
        }
        if ($this->captchaType != 'captcha') {
            $this->changeConfig($I, ['captcha_type' => 'captcha']);
        }

        // Mỗi test bắt đầu bằng một phiên mới, chưa đăng nhập
        $this->newSession($I, '/vi/users/login/');
        $I->seeElement('[name="nv_login"]');
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
     * @param string           $username
     * @param string           $password
     * @param string           $scope Vùng chứa form, dùng cho form đăng nhập popup
     */
    private function submitLogin(AcceptanceTester $I, string $username, string $password, string $scope = ''): void
    {
        $I->fillField(trim($scope . ' [name="nv_login"]'), $username);
        $I->fillField(trim($scope . ' [name="nv_password"]'), $password);
        $this->clickVisibleSubmit($I, trim($scope . ' form[data-toggle="userLogin"]'));
    }

    /**
     * Server yêu cầu captcha: có thông báo, form được gắn captcha, tên đăng nhập vẫn giữ nguyên
     *
     * @param AcceptanceTester $I
     * @param string           $username
     */
    private function seeCaptchaRequired(AcceptanceTester $I, string $username): void
    {
        $I->waitForText(self::NOTICE, 5);
        $I->seeElementInDOM(self::$formCaptcha);
        $I->seeInField('form[data-toggle="userLogin"] [name="nv_login"]', $username);
        $I->dontSee(self::LOGIN_OK);
    }

    /**
     * Đủ ngưỡng, bị yêu cầu captcha, bấm đăng nhập lại và giải captcha thì vào được
     *
     * @param AcceptanceTester $I
     * @param string           $scope
     */
    private function loginThroughCaptcha(AcceptanceTester $I, string $scope = ''): void
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit);

        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD'], $scope);
        $this->seeCaptchaRequired($I, $_ENV['NV_USERNAME']);

        $this->submitWithSiteCaptcha($I, trim($scope . ' form[data-toggle="userLogin"]'));
        $I->waitForText(self::LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * 1. Chưa chạm ngưỡng: không có captcha, đăng nhập đúng thì xóa bộ đếm
     *
     * @group user-login-captcha
     */
    public function noCaptchaBelowLimit(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit - 1);

        $I->dontSeeElementInDOM(self::$formCaptcha);
        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $I->waitForText(self::LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * 2. Chạm ngưỡng: mật khẩu đúng nhưng chưa có captcha vẫn bị yêu cầu captcha,
     * mật khẩu chưa được kiểm tra nên bộ đếm giữ nguyên
     *
     * @group user-login-captcha
     */
    public function captchaRequiredForAccount(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit);

        $I->dontSeeElementInDOM(self::$formCaptcha);
        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $this->seeCaptchaRequired($I, $_ENV['NV_USERNAME']);

        $I->seeInDatabase($this->table, ['keyname' => $this->accountKey(), 'count' => $this->limit]);
    }

    /**
     * 3. Tên không tồn tại bị đếm đủ ngưỡng phản hồi giống hệt tài khoản thật
     *
     * @group user-login-captcha
     */
    public function captchaRequiredForUnknownName(AcceptanceTester $I)
    {
        $key = LoginTracker::getKey(null, self::GHOST);
        $this->haveAttempts($I, $key, $this->limit);

        $this->submitLogin($I, self::GHOST, 'wrong-password');
        $this->seeCaptchaRequired($I, self::GHOST);

        $I->seeInDatabase($this->table, ['keyname' => $key, 'count' => $this->limit]);
    }

    /**
     * 4. Hết thời gian cần captcha: đăng nhập bình thường, bộ đếm bị xóa
     *
     * @group user-login-captcha
     */
    public function noCaptchaAfterBanExpired(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit, $this->ban * 60 + 10);

        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $I->waitForText(self::LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * 5. Captcha không lưu theo phiên: tải lại trang thì form trở lại bình thường,
     * nhưng tài khoản vẫn bị đếm nên submit lại vẫn bị yêu cầu captcha
     *
     * @group user-login-captcha
     */
    public function captchaNotStoredInSession(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit);

        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $this->seeCaptchaRequired($I, $_ENV['NV_USERNAME']);

        $I->amOnUrl($I->getDomain() . '/vi/users/login/');
        $I->seeElement('[name="nv_login"]');
        $I->dontSeeElementInDOM(self::$formCaptcha);
        $I->dontSee(self::NOTICE);

        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $this->seeCaptchaRequired($I, $_ENV['NV_USERNAME']);
    }

    /**
     * 6. Giải captcha trong modal thì đăng nhập được, bộ đếm bị xóa
     *
     * @group user-login-captcha
     */
    public function captchaSolvedThenLogin(AcceptanceTester $I)
    {
        $this->loginThroughCaptcha($I);
    }

    /**
     * 7. Form đăng nhập popup ở đầu trang dùng chung cơ chế
     *
     * @group user-login-captcha
     */
    public function popupLoginThroughCaptcha(AcceptanceTester $I, Scenario $scenario)
    {
        $I->amOnUrl($I->getDomain() . '/vi/');
        if (!$I->executeJS('return !!document.querySelector(\'a[data-callback="loginFormLoad"]\');')) {
            $scenario->skip('Giao diện hiện tại không có nút đăng nhập popup');
        }
        $I->click('a[data-callback="loginFormLoad"]');
        $I->waitForElementVisible('#tip form[data-toggle="userLogin"] [name="nv_login"]', 5);

        $this->loginThroughCaptcha($I, '#tip');
    }

    /**
     * 8. Giao diện mobile_default
     *
     * @group user-login-captcha
     */
    public function mobileThemeThroughCaptcha(AcceptanceTester $I, Scenario $scenario)
    {
        $I->amOnUrl($I->getDomain() . '/vi/?nvvithemever=m');
        $I->amOnUrl($I->getDomain() . '/vi/users/login/');
        if (!$I->executeJS('return !!document.querySelector(\'script[src*="themes/mobile_default/"]\');')) {
            $scenario->skip('Site không bật giao diện mobile_default');
        }

        $this->loginThroughCaptcha($I);
    }

    /**
     * 9. Giao diện future
     *
     * @group user-login-captcha
     */
    public function futureThemeThroughCaptcha(AcceptanceTester $I, Scenario $scenario)
    {
        if (!$I->grabNumRecords($this->prefix . '_vi_modthemes', ['theme' => 'future'])) {
            $scenario->skip('Giao diện future chưa được thiết lập cho ngôn ngữ vi');
        }
        $this->changeConfig($I, ['site_theme' => 'future'], 'global', 'vi');
        $I->amOnUrl($I->getDomain() . '/vi/users/login/');
        $I->seeElementInDOM('script[src*="themes/future/"]');

        $this->loginThroughCaptcha($I);
    }

    /**
     * 10. Tài khoản bật 2FA bị yêu cầu captcha: giải captcha xong sang bước 2FA,
     * bước 2FA không hỏi lại captcha, nhập đúng mã thì đăng nhập được
     *
     * @group user-login-captcha
     */
    public function twoFactorAccountThroughCaptcha(AcceptanceTester $I)
    {
        $this->enable2fa($I);
        $this->haveAttempts($I, $this->accountKey(), $this->limit);

        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $this->seeCaptchaRequired($I, $_ENV['NV_USERNAME']);

        $this->submitWithSiteCaptcha($I);
        $I->waitForElementVisible('[name="nv_totppin"]', 5);
        $I->dontSeeElementInDOM(self::$formCaptcha);

        $I->fillField(['name' => 'nv_totppin'], $I->totpCode(self::$tfaSecret));
        $this->clickVisibleSubmit($I);
        $I->waitForText(self::LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
        $I->dontSeeInDatabase($this->table, ['keyname' => LoginTracker::getScopeKey('tfa', $this->userid)]);
    }
}
