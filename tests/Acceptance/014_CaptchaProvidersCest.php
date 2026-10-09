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
use Tests\Support\AcceptanceTester;
use Tests\Support\LoginCaptchaTrait;

/**
 * Bắt captcha theo tài khoản với Cloudflare Turnstile và reCAPTCHA v2, dùng test key chính thức
 * luôn cho qua của Cloudflare và Google. reCAPTCHA v3 không có test key nên không test được.
 *
 * Cần mạng ra ngoài ở cả trình duyệt và máy chủ web (gọi siteverify), không có mạng thì skip.
 * Cấu hình captcha được đổi tạm và khôi phục ở _after/_failed (xem LoginCaptchaTrait).
 */
class CaptchaProvidersCest
{
    use LoginCaptchaTrait;

    private const TURNSTILE_SITEKEY = '1x00000000000000000000AA';
    private const TURNSTILE_SECRET = '1x0000000000000000000000000000000AA';
    private const RECAPTCHA_SITEKEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
    private const RECAPTCHA_SECRET = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

    private const ADMIN_NOTICE = 'đã đăng nhập sai nhiều lần';
    private const ADMIN_LOGIN_OK = 'Bạn đã đăng nhập thành công';
    private const SITE_NOTICE = 'bấm đăng nhập lại và nhập mã xác nhận';
    private const LOGIN_OK = 'Đăng nhập hệ thống thành công';

    public function _before(AcceptanceTester $I, Scenario $scenario)
    {
        $this->loadLoginState($I, $scenario);
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
     * @param Scenario $scenario
     * @param string   $host
     */
    private function requireNetwork(Scenario $scenario, string $host): void
    {
        $fp = @fsockopen('ssl://' . $host, 443, $errno, $errstr, 5);
        if (!$fp) {
            $scenario->skip('Không kết nối được ' . $host . ': ' . $errstr);
        }
        fclose($fp);
    }

    /**
     * @param AcceptanceTester $I
     */
    private function useTurnstile(AcceptanceTester $I): void
    {
        $this->changeCaptchaKeys($I, [
            'turnstile_sitekey' => self::TURNSTILE_SITEKEY,
            'turnstile_secretkey' => self::TURNSTILE_SECRET
        ]);
        $this->changeConfig($I, ['captcha_type' => 'turnstile']);
    }

    /**
     * @param AcceptanceTester $I
     */
    private function useRecaptcha2(AcceptanceTester $I): void
    {
        $this->changeCaptchaKeys($I, [
            'recaptcha_ver' => '2',
            'recaptcha_sitekey' => self::RECAPTCHA_SITEKEY,
            'recaptcha_secretkey' => self::RECAPTCHA_SECRET
        ]);
        $this->changeConfig($I, ['captcha_type' => 'recaptcha']);
    }

    /**
     * Bấm ô "Tôi không phải người máy" của reCAPTCHA v2 trong iframe
     *
     * @param AcceptanceTester $I
     */
    private function tickRecaptcha(AcceptanceTester $I): void
    {
        $I->waitForElementVisible('iframe[title="reCAPTCHA"]', 15);
        $I->switchToIFrame('iframe[title="reCAPTCHA"]');
        $I->waitForElementVisible('#recaptcha-anchor', 10);
        $I->click('#recaptcha-anchor');
        // Không đợi trạng thái trong iframe: callback có thể gửi form và chuyển trang ngay
        $I->switchToIFrame();
    }

    /**
     * Đăng nhập site thành công: thông báo thành công chỉ hiện 3 giây rồi chuyển trang, nên chấp nhận
     * cả trường hợp đã rời trang đăng nhập; bằng chứng chắc chắn là bộ đếm của tài khoản đã bị xóa
     *
     * @param AcceptanceTester $I
     */
    private function seeSiteLoginSucceeded(AcceptanceTester $I): void
    {
        $I->waitForJS('return document.body.innerText.indexOf(' . json_encode(self::LOGIN_OK) . ') !== -1 || location.pathname.indexOf("/users/login") === -1;', 30);
        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * Admin bị yêu cầu captcha, trang tải lại có captcha của nhà cung cấp
     *
     * @param AcceptanceTester $I
     * @param string           $widget
     */
    private function adminToCaptcha(AcceptanceTester $I, string $widget): void
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit);
        $this->newSession($I, '/admin/index.php');
        $I->fillField(['name' => 'nv_login'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'nv_password'], $_ENV['NV_PASSWORD']);
        $I->click('[type="submit"]');

        $I->waitForElement($widget, 10);
        $I->see(self::ADMIN_NOTICE);
    }

    /**
     * Admin đã qua captcha: nhập lại thông tin và đăng nhập
     *
     * @param AcceptanceTester $I
     */
    private function adminSubmitAfterCaptcha(AcceptanceTester $I): void
    {
        $I->waitForJS('return !document.querySelector(\'form[data-toggle="preForm"] [type="submit"]\').disabled;', 20);
        $I->fillField(['name' => 'nv_login'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'nv_password'], $_ENV['NV_PASSWORD']);
        $I->click('form[data-toggle="preForm"] [type="submit"]');
        $I->waitForText(self::ADMIN_LOGIN_OK, 15);
        $I->dontSeeInDatabase($this->table, ['keyname' => $this->accountKey()]);
    }

    /**
     * Site bị yêu cầu captcha: form được gắn thuộc tính captcha của nhà cung cấp
     *
     * @param AcceptanceTester $I
     * @param string           $attr
     */
    private function siteToCaptcha(AcceptanceTester $I, string $attr): void
    {
        $this->haveAttempts($I, $this->accountKey(), $this->limit);
        $this->newSession($I, '/vi/users/login/');
        $I->fillField(['name' => 'nv_login'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'nv_password'], $_ENV['NV_PASSWORD']);
        $this->clickVisibleSubmit($I);

        $I->waitForText(self::SITE_NOTICE, 10);
        $I->seeElementInDOM('form[data-toggle="userLogin"][' . $attr . ']');
    }

    /**
     * 1. Admin với Turnstile: widget tự qua, đăng nhập được
     *
     * @group captcha-providers
     */
    public function adminTurnstile(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNetwork($scenario, 'challenges.cloudflare.com');
        if (in_array('a', $this->captchaArea, true)) {
            $scenario->skip('captcha_area đang bật cho admin');
        }
        $this->useTurnstile($I);

        $this->adminToCaptcha($I, '#cf-turnstile');
        $this->adminSubmitAfterCaptcha($I);
    }

    /**
     * 2. Site với Turnstile: bấm đăng nhập lại, modal Turnstile tự qua và gửi form
     *
     * @group captcha-providers
     */
    public function siteTurnstile(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNetwork($scenario, 'challenges.cloudflare.com');
        if (in_array('l', $this->captchaArea, true)) {
            $scenario->skip('captcha_area đang bật cho khu vực đăng nhập');
        }
        $this->useTurnstile($I);

        $this->siteToCaptcha($I, 'data-turnstile');
        $I->waitForJS('return typeof turnstile !== "undefined" && typeof turnstile.render === "function";', 15);
        $this->clickVisibleSubmit($I);
        $this->seeSiteLoginSucceeded($I);
    }

    /**
     * 3. Admin với reCAPTCHA v2: bấm ô xác nhận rồi đăng nhập
     *
     * @group captcha-providers
     */
    public function adminRecaptcha2(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNetwork($scenario, 'www.google.com');
        if (in_array('a', $this->captchaArea, true)) {
            $scenario->skip('captcha_area đang bật cho admin');
        }
        $this->useRecaptcha2($I);

        $this->adminToCaptcha($I, '#reCaptcha');
        $this->tickRecaptcha($I);
        $this->adminSubmitAfterCaptcha($I);
    }

    /**
     * 4. Site với reCAPTCHA v2: bấm đăng nhập lại, bấm ô xác nhận trong modal thì form được gửi
     *
     * @group captcha-providers
     */
    public function siteRecaptcha2(AcceptanceTester $I, Scenario $scenario)
    {
        $this->requireNetwork($scenario, 'www.google.com');
        if (in_array('l', $this->captchaArea, true)) {
            $scenario->skip('captcha_area đang bật cho khu vực đăng nhập');
        }
        $this->useRecaptcha2($I);

        $this->siteToCaptcha($I, 'data-recaptcha2');
        $I->waitForJS('return typeof grecaptcha !== "undefined" && typeof grecaptcha.render === "function";', 15);
        $this->clickVisibleSubmit($I);
        $this->tickRecaptcha($I);
        $this->seeSiteLoginSucceeded($I);
    }
}
