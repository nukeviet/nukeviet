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
use NukeViet\Core\LoginTracker;
use Tests\Support\AcceptanceTester;
use Tests\Support\LoginCaptchaTrait;

/**
 * Đăng nhập admin bắt captcha theo tài khoản khi bị dò mật khẩu.
 *
 * Test không nhập sai mật khẩu thật mà chèn sẵn bộ đếm vào bảng _users_login_attempts,
 * mô phỏng tài khoản đã bị dò từ IP khác. Nhập sai thật sẽ làm Blocker chặn IP của máy test
 * và hỏng các test đăng nhập phía sau.
 *
 * Điều kiện: login_number_tracking > 0, captcha_area không bật khu vực admin (a), captcha_type là captcha hình.
 * Trang đăng nhập admin luôn dùng admin_future (admin/index.php ép giao diện), nên không test được admin_default.
 */
class AdminLoginCaptchaCest
{
    use LoginCaptchaTrait;

    private const NOTICE = 'đã đăng nhập sai nhiều lần';
    private const LOGIN_OK = 'Bạn đã đăng nhập thành công';
    private const WRONG_CAPTCHA = 'Mã bảo mật không chính xác';
    private const GHOST = 'ghost_captcha_test';

    public function _before(AcceptanceTester $I, Scenario $scenario)
    {
        $this->loadLoginState($I, $scenario);
        if (in_array('a', $this->captchaArea, true)) {
            $scenario->skip('captcha_area đang bật cho admin, form luôn có captcha');
        }
        if ($this->captchaType != 'captcha') {
            $this->changeConfig($I, ['captcha_type' => 'captcha']);
        }

        // Mỗi test bắt đầu bằng một phiên mới, chưa đăng nhập
        $this->newSession($I, '/admin/index.php');
        $I->seeElement('#nv_login');
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
     */
    private function submitLogin(AcceptanceTester $I, string $username, string $password): void
    {
        $I->fillField(['name' => 'nv_login'], $username);
        $I->fillField(['name' => 'nv_password'], $password);
        $I->click('[type="submit"]');
    }

    /**
     * Sau khi submit, trang tải lại với captcha và thông báo. Tên đăng nhập không được điền sẵn
     * để không lộ tài khoản admin cho người dùng sau trên máy dùng chung
     *
     * @param AcceptanceTester $I
     */
    private function seeCaptchaRequired(AcceptanceTester $I): void
    {
        $I->waitForElement(self::$adminCaptcha, 5);
        $I->see(self::NOTICE);
        $I->seeInField(['name' => 'nv_login'], '');
        $I->dontSee(self::LOGIN_OK);
    }

    /**
     * 1. Chưa chạm ngưỡng: không có captcha, đăng nhập đúng thì xóa bộ đếm
     *
     * @group admin-login-captcha
     */
    public function noCaptchaBelowLimit(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit - 1);

        $I->dontSeeElementInDOM(self::$adminCaptcha);
        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $I->waitForText(self::LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * 2. Chạm ngưỡng: mật khẩu đúng nhưng không có captcha vẫn bị yêu cầu captcha,
     * mật khẩu chưa được kiểm tra nên bộ đếm giữ nguyên
     *
     * @group admin-login-captcha
     */
    public function captchaRequiredForAccount(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit);

        $I->dontSeeElementInDOM(self::$adminCaptcha);
        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $this->seeCaptchaRequired($I);

        $I->seeInDatabase($this->table, ['keyname' => $this->accountKey(), 'count' => $this->limit]);

        // Tải lại trong cùng phiên: vẫn còn captcha nhưng thông báo chỉ hiện một lần
        $I->amOnUrl($I->getDomain() . '/admin/index.php');
        $I->seeElementInDOM(self::$adminCaptcha);
        $I->dontSee(self::NOTICE);
    }

    /**
     * 3. Tên không tồn tại bị đếm đủ ngưỡng phản hồi giống hệt tài khoản thật
     *
     * @group admin-login-captcha
     */
    public function captchaRequiredForUnknownName(AcceptanceTester $I)
    {
        $key = LoginTracker::getKey(null, self::GHOST);
        $this->haveAttempts($I, $key, $this->limit);

        $this->submitLogin($I, self::GHOST, 'wrong-password');
        $this->seeCaptchaRequired($I);

        $I->seeInDatabase($this->table, ['keyname' => $key, 'count' => $this->limit]);
    }

    /**
     * 4. Hết thời gian cần captcha: đăng nhập bình thường, bộ đếm bị xóa
     *
     * @group admin-login-captcha
     */
    public function noCaptchaAfterBanExpired(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit, $this->ban * 60 + 10);

        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $I->waitForText(self::LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * 5. Bộ đếm bám theo tài khoản chứ không theo phiên: đổi phiên (trình duyệt/IP khác)
     * vẫn bị yêu cầu captcha
     *
     * @group admin-login-captcha
     */
    public function captchaFollowsAccountNotSession(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit);

        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $this->seeCaptchaRequired($I);

        $this->newSession($I, '/admin/index.php');
        $I->dontSeeElementInDOM(self::$adminCaptcha);
        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $this->seeCaptchaRequired($I);
    }

    /**
     * 6. Giải đúng captcha và nhập đúng mật khẩu: đăng nhập được, bộ đếm bị xóa
     *
     * @group admin-login-captcha
     */
    public function captchaSolvedThenLogin(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit);

        $before = $I->grabCaptchaRandom();
        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $this->seeCaptchaRequired($I);

        $this->fillAdminCaptcha($I, $before);
        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $I->waitForText(self::LOGIN_OK, 5);

        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * 7. Captcha sai: báo lỗi captcha, mật khẩu không được kiểm tra nên bộ đếm giữ nguyên
     *
     * @group admin-login-captcha
     */
    public function wrongCaptchaKeepsCount(AcceptanceTester $I)
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit);

        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $this->seeCaptchaRequired($I);

        $I->fillField('#seccode', '000000');
        $this->submitLogin($I, $_ENV['NV_USERNAME'], $_ENV['NV_PASSWORD']);
        $I->waitForText(self::WRONG_CAPTCHA, 5);
        $I->dontSee(self::LOGIN_OK);

        $I->seeInDatabase($this->table, ['keyname' => $this->accountKey(), 'count' => $this->limit]);
    }
}
