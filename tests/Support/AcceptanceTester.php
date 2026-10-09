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

    /**
     * Kết nối PDO tới CSDL của site, dùng cho thao tác mà module Db không có như xóa bản ghi
     *
     * @return \PDO
     */
    public function nvPdo(): \PDO
    {
        $db = $this->getDbConfig();
        $dsn = 'mysql:host=' . $db['dbhost'] . ';dbname=' . $db['dbname'] . (!empty($db['dbport']) ? ';port=' . $db['dbport'] : '') . ';charset=utf8mb4';

        return new \PDO($dsn, $db['dbuname'], $db['dbpass'], [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    /**
     * Mã hóa giống $crypt của site (khóa bí mật reCAPTCHA, Turnstile, mã dự phòng 2FA)
     *
     * @return \NukeViet\Core\Encryption
     */
    public function nvCrypt(): \NukeViet\Core\Encryption
    {
        return new \NukeViet\Core\Encryption($this->getSiteKey());
    }

    /**
     * Đổi cấu hình trong bảng config rồi xóa cache settings giống như khi lưu ở admin.
     * Trả về giá trị cũ để khôi phục bằng chính hàm này.
     *
     * @param array  $values [config_name => config_value]
     * @param string $module
     * @param string $lang
     * @return array
     */
    public function setNvConfig(array $values, string $module = 'site', string $lang = 'sys'): array
    {
        $pdo = $this->nvPdo();
        $table = $this->getDbConfig('prefix') . '_config';
        $old = [];

        // Đọc đủ giá trị cũ trước, thiếu dòng nào thì dừng khi chưa sửa gì để không để lại cấu hình dở dang
        foreach ($values as $name => $value) {
            $sth = $pdo->prepare('SELECT config_value FROM ' . $table . ' WHERE lang = :lang AND module = :module AND config_name = :name');
            $sth->execute([':lang' => $lang, ':module' => $module, ':name' => $name]);
            $current = $sth->fetchColumn();
            if ($current === false) {
                throw new \RuntimeException('Không có cấu hình ' . $lang . '/' . $module . '/' . $name);
            }
            $old[$name] = $current;
        }

        foreach ($values as $name => $value) {
            $sth = $pdo->prepare('UPDATE ' . $table . ' SET config_value = :value WHERE lang = :lang AND module = :module AND config_name = :name');
            $sth->execute([':value' => (string) $value, ':lang' => $lang, ':module' => $module, ':name' => $name]);
        }

        $cache = new \NukeViet\Cache\FileCache(NV_ROOTDIR . '/data/cache', 'vi', '', '');
        $cache->delMod('settings');

        return $old;
    }

    /**
     * Giá trị random_num của captcha hình trong session hiện tại của trình duyệt, null nếu chưa có
     *
     * @return int|null
     */
    public function grabCaptchaRandom(): ?int
    {
        $sessId = '';
        $cookies = $this->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $wd) {
            return $wd->manage()->getCookies();
        });
        foreach ($cookies as $cookie) {
            if (str_ends_with($cookie->getName(), '_sess')) {
                $sessId = $cookie->getValue();
            }
        }
        if (!preg_match('/^[a-zA-Z0-9,\-]+$/', $sessId)) {
            return null;
        }

        $path = $_ENV['SESSION_SAVE_PATH'] ?? (string) ini_get('session.save_path');
        $path = rtrim(substr($path, (int) strrpos(';' . $path, ';')), '/\\');
        $file = $path . '/sess_' . $sessId;
        clearstatcache(true, $file);
        if (!is_file($file)) {
            return null;
        }

        if (preg_match('/_random_num\|(?:i:(\d+)|s:\d+:"(\d+)")/', (string) file_get_contents($file), $m)) {
            return (int) ($m[1] !== '' ? $m[1] : $m[2]);
        }

        return null;
    }

    /**
     * Đợi ảnh captcha mới được nạp (random_num khác $before) rồi tính mã giống includes/core/captcha.php
     *
     * @param int|null $before Giá trị grabCaptchaRandom() trước khi nạp ảnh mới
     * @return string
     */
    public function waitCaptchaCode(?int $before): string
    {
        $random = null;
        for ($i = 0; $i < 50; $i++) {
            $random = $this->grabCaptchaRandom();
            if ($random !== null and $random !== $before) {
                break;
            }
            usleep(100000);
        }
        if ($random === null or $random === $before) {
            throw new \RuntimeException('Không đọc được random_num của captcha trong session, kiểm tra SESSION_SAVE_PATH');
        }

        $ua = trim(substr(htmlspecialchars((string) $this->executeJS('return navigator.userAgent;')), 0, 255));
        $tz = (string) $this->executeJS('return Intl.DateTimeFormat().resolvedOptions().timeZone;');
        $datekey = (new \DateTime('now', new \DateTimeZone($tz ?: date_default_timezone_get())))->format('F j');

        $length = 6;
        $config = (string) file_get_contents(NV_ROOTDIR . '/data/config/config_global.php');
        if (preg_match('/define\(\'NV_GFX_NUM\',\s*(\d+)\)/', $config, $m)) {
            $length = (int) $m[1];
        }

        return substr(strtoupper(md5($ua . $this->getSiteKey() . $random . $datekey)), 2, $length);
    }

    /**
     * Mã TOTP đúng của secret, $offset là số khung 30 giây lệch so với hiện tại
     *
     * @param string $secret
     * @param int    $offset
     * @return string
     */
    public function totpCode(string $secret, int $offset = 0): string
    {
        $ga = new \NukeViet\Core\GoogleAuthenticator();
        $method = new \ReflectionMethod($ga, 'getTrueCode');
        $method->setAccessible(true);

        return (string) $method->invoke($ga, $secret, (int) floor(time() / 30) + $offset);
    }

    /**
     * Mã 6 số chắc chắn sai: khác mọi mã đúng trong các khung 30 giây quanh thời điểm hiện tại
     *
     * @param string $secret
     * @return string
     */
    public function wrongTotpCode(string $secret): string
    {
        $valid = [];
        for ($i = -2; $i <= 2; $i++) {
            $valid[] = $this->totpCode($secret, $i);
        }

        $code = 0;
        while (in_array(str_pad((string) $code, 6, '0', STR_PAD_LEFT), $valid, true)) {
            $code++;
        }

        return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
    }
}
