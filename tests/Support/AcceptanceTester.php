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
}
