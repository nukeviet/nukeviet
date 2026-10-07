<?php

declare(strict_types = 1);

namespace Tests\Support;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends \Codeception\Actor
{
    // phpcs:disable
    use _generated\AcceptanceTesterActions;

    /**
     * @return string
     */
    public function getSiteKey(): string
    {
        require NV_ROOTDIR . '/config.php';
        return $global_config['sitekey'];
    }

    /**
     * @return mixed
     */
    public function getDbConfig(string $key = ''): mixed
    {
        require NV_ROOTDIR . '/config.php';
        return $key ? ($db_config[$key] ?? null) : $db_config;
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function login(?string $username = null, ?string $password = null)
    {
        $I = $this;

        if ($I->loadSessionSnapshot('adminLogin')) {
            $I->comment('Already logged in!');
            return;
        }

        $I->wantTo('Open admin login page');

        $I->amOnUrl($this->getDomain() . '/admin/index.php');
        $I->seeElement('#nv_login');

        $I->waitForJS("return document.activeElement === document.querySelector('input#nv_login');", 1);

        $username = $username ?? $_ENV['NV_USERNAME'];
        $password = $password ?? $_ENV['NV_PASSWORD'];

        $I->fillField(['name' => 'nv_login'], $username);
        $I->fillField(['name' => 'nv_password'], $password);

        $I->click('[type="submit"]');
        $I->waitForText('Bạn đã đăng nhập thành công', 4);

        $I->saveSessionSnapshot('adminLogin');
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return ($_ENV['HTTPS'] == 'on' ? 'https://' : 'http://') . $_ENV['HTTP_HOST'];
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function userLogin(?string $username = null, ?string $password = null)
    {
        $I = $this;

        if ($I->loadSessionSnapshot('userLogin')) {
            $I->comment('Already logged in!');
            return;
        }

        $I->wantTo('Open user login page');

        $I->amOnUrl($this->getDomain() . '/vi/users/login/');
        $I->seeElement('[name="nv_login"]');

        $username = $username ?? $_ENV['NV_USERNAME'];
        $password = $password ?? $_ENV['NV_PASSWORD'];

        $I->fillField(['name' => 'nv_login'], $username);
        $I->fillField(['name' => 'nv_password'], $password);

        $I->click('[type="submit"]');
        $I->waitForText('Đăng nhập hệ thống thành công', 4);

        $I->saveSessionSnapshot('userLogin');
    }

    /**
     * Cuộn phần tử vào giữa màn hình và đợi cuộn xong mới trả về.
     * Giao diện Bootstrap 5 bật scroll-behavior: smooth nên phải đợi
     * vị trí cuộn đứng yên, nếu không click sẽ bị phần tử khác chặn.
     *
     * @param string $selector CSS selector
     * @param int $timeout
     */
    public function scrollToElement(string $selector, int $timeout = 5)
    {
        $I = $this;
        $sel = json_encode($selector);

        $I->waitForElementVisible($selector, $timeout);
        $I->executeJS('window.nvTestScrollY = null; document.querySelector(' . $sel . ').scrollIntoView({block: "center"});');
        $I->waitForJS('
            const el = document.querySelector(' . $sel . ');
            if (!el) {
                return false;
            }
            const rect = el.getBoundingClientRect();
            const stable = window.nvTestScrollY === window.scrollY;
            window.nvTestScrollY = window.scrollY;
            return stable && rect.top >= 0 && rect.bottom <= window.innerHeight;
        ', $timeout);
    }

    /**
     * Cuộn tới phần tử rồi click
     *
     * @param string $selector CSS selector
     * @param int $timeout
     */
    public function scrollAndClick(string $selector, int $timeout = 5)
    {
        $this->scrollToElement($selector, $timeout);
        $this->click($selector);
    }
}
