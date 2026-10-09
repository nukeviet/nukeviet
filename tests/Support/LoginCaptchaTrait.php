<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Support;

use Codeception\Scenario;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use NukeViet\Core\LoginTracker;

/**
 * Các thao tác dùng chung cho test chống dò mật khẩu: đọc cấu hình, phiên mới, chèn bộ đếm,
 * bật 2FA tạm thời, đổi cấu hình captcha, giải captcha hình và dọn dẹp sau test.
 *
 * Mọi thay đổi (2FA, mã dự phòng, cấu hình, bộ đếm) được khôi phục trong cleanupLoginState(),
 * Cest dùng trait phải gọi hàm này ở cả _after và _failed.
 *
 * Nếu tiến trình test bị dừng giữa chừng, khôi phục tay:
 * UPDATE nv5_users SET active2step=0, secretkey='', pref_2fa=0 WHERE username='webmaster';
 * DELETE FROM nv5_users_backupcodes WHERE code IN (...mã test...);
 * UPDATE nv5_config SET config_value='captcha' WHERE lang='sys' AND module='site' AND config_name='captcha_type';
 * rồi xóa data/cache/settings/*.cache
 */
trait LoginCaptchaTrait
{
    protected static string $tfaSecret = 'JBSWY3DPEHPK3PXP';

    /**
     * Phần tử captcha của cả 3 loại trên form đăng nhập admin
     */
    protected static string $adminCaptcha = '[name="nv_seccode"], #reCaptcha, [name="g-recaptcha-response"], #cf-turnstile';

    /**
     * Form đăng nhập site đã được gắn một trong các loại captcha
     */
    protected static string $formCaptcha = 'form[data-toggle="userLogin"][data-captcha], form[data-toggle="userLogin"][data-recaptcha2], form[data-toggle="userLogin"][data-recaptcha3], form[data-toggle="userLogin"][data-turnstile]';

    protected string $prefix = '';
    protected string $table = '';
    protected int $limit = 0;
    protected int $ban = 0;
    protected int $userid = 0;
    protected array $captchaArea = [];
    protected string $captchaType = '';

    /**
     * Trạng thái 2FA gốc của tài khoản test, null nếu chưa thay đổi
     */
    protected ?array $backup2fa = null;

    /**
     * Các mã dự phòng đã chèn (đã mã hóa) để xóa khi dọn dẹp
     */
    protected array $backupCodes = [];

    /**
     * Cấu hình gốc đã thay đổi: [[module, lang, [name => old]]]
     */
    protected array $configBackup = [];

    /**
     * Khóa reCAPTCHA/Turnstile gốc (đọc từ form admin) nếu đã đổi, null nếu chưa
     */
    protected ?array $captchaKeysBackup = null;

    /**
     * Đọc cấu hình bộ đếm, captcha và tài khoản test. Bộ đếm tắt thì skip
     *
     * @param AcceptanceTester $I
     * @param Scenario         $scenario
     */
    protected function loadLoginState(AcceptanceTester $I, Scenario $scenario): void
    {
        $this->prefix = $I->getDbConfig('prefix');
        $this->table = $this->prefix . '_users_login_attempts';

        $config = function (string $module, string $name) use ($I) {
            return (string) $I->grabFromDatabase($this->prefix . '_config', 'config_value', [
                'lang' => 'sys',
                'module' => $module,
                'config_name' => $name
            ]);
        };
        $this->limit = (int) $config('global', 'login_number_tracking');
        $this->ban = (int) $config('global', 'login_time_ban');
        if ($this->ban <= 0) {
            $this->ban = max(1, (int) $config('global', 'login_time_tracking'));
        }
        if ($this->limit < 1) {
            $scenario->skip('login_number_tracking = 0, bộ đếm theo tài khoản đang tắt');
        }
        $this->captchaArea = array_filter(explode(',', $config('site', 'captcha_area')));
        $this->captchaType = $config('site', 'captcha_type');

        $this->userid = (int) $I->grabFromDatabase($this->prefix . '_users', 'userid', ['username' => $_ENV['NV_USERNAME']]);
    }

    /**
     * Khôi phục mọi thay đổi của test, gọi ở _after và _failed
     *
     * @param AcceptanceTester $I
     */
    protected function cleanupLoginState(AcceptanceTester $I): void
    {
        $pdo = $I->nvPdo();

        if ($this->backup2fa !== null) {
            $sth = $pdo->prepare('UPDATE ' . $this->prefix . '_users SET active2step = :a, secretkey = :s, pref_2fa = :p WHERE userid = :u');
            $sth->execute([
                ':a' => $this->backup2fa['active2step'],
                ':s' => $this->backup2fa['secretkey'],
                ':p' => $this->backup2fa['pref_2fa'],
                ':u' => $this->userid
            ]);
            $this->backup2fa = null;
        }

        foreach ($this->backupCodes as $code) {
            $sth = $pdo->prepare('DELETE FROM ' . $this->prefix . '_users_backupcodes WHERE userid = :u AND code = :c');
            $sth->execute([':u' => $this->userid, ':c' => $code]);
        }
        $this->backupCodes = [];

        foreach (array_reverse($this->configBackup) as [$module, $lang, $old]) {
            $I->setNvConfig($old, $module, $lang);
        }
        $this->configBackup = [];

        // Bộ đếm do máy chủ tạo ra trong lúc test (bản ghi chèn bằng haveInDatabase đã tự xóa)
        $sth = $pdo->prepare('DELETE FROM ' . $this->table . ' WHERE keyname IN (:u, :tfa, :pwd)');
        $sth->execute([
            ':u' => LoginTracker::getKey($this->userid, $_ENV['NV_USERNAME']),
            ':tfa' => LoginTracker::getScopeKey('tfa', $this->userid),
            ':pwd' => LoginTracker::getScopeKey('pwd', $this->userid)
        ]);

        // Làm sau cùng vì cần đăng nhập admin, lúc này bộ đếm và cấu hình đã được trả lại
        if ($this->captchaKeysBackup !== null) {
            $backup = $this->captchaKeysBackup;
            $this->captchaKeysBackup = null;
            $this->saveCaptchaKeys($I, $backup);
        }

        // Test đăng nhập lại admin và thành viên nên phiên trong snapshot cũ có thể đã mất hiệu lực
        $I->deleteSessionSnapshot('adminLogin');
        $I->deleteSessionSnapshot('userLogin');
    }

    /**
     * Đổi cấu hình và ghi nhớ giá trị cũ để cleanupLoginState() khôi phục
     *
     * @param AcceptanceTester $I
     * @param array            $values
     * @param string           $module
     * @param string           $lang
     */
    protected function changeConfig(AcceptanceTester $I, array $values, string $module = 'site', string $lang = 'sys'): void
    {
        $this->configBackup[] = [$module, $lang, $I->setNvConfig($values, $module, $lang)];
    }

    /**
     * Đổi khóa reCAPTCHA/Turnstile bằng form cấu hình captcha của admin. Các khóa này thuộc module global,
     * được nạp từ file config_global.php nên phải lưu qua admin để file được sinh lại.
     * Lần đổi đầu tiên ghi nhớ giá trị gốc để cleanupLoginState() khôi phục
     *
     * @param AcceptanceTester $I
     * @param array            $values [tên trường form => giá trị], khóa bí mật để dạng chưa mã hóa
     */
    protected function changeCaptchaKeys(AcceptanceTester $I, array $values): void
    {
        $old = $this->saveCaptchaKeys($I, $values);
        if ($this->captchaKeysBackup === null) {
            $this->captchaKeysBackup = $old;
        }
    }

    /**
     * Đăng nhập admin bằng phiên mới rồi gửi form cấu hình captcha chung với các giá trị mới
     *
     * @param AcceptanceTester $I
     * @param array            $values
     * @return array Giá trị cũ của các trường đã đổi
     */
    protected function saveCaptchaKeys(AcceptanceTester $I, array $values): array
    {
        $this->newSession($I, '/admin/index.php');
        $I->fillField(['name' => 'nv_login'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'nv_password'], $_ENV['NV_PASSWORD']);
        $I->click('[type="submit"]');
        $I->waitForText('Bạn đã đăng nhập thành công', 5);

        $I->amOnUrl($I->getDomain() . '/admin/index.php?language=vi&nv=settings&op=security');
        $I->waitForElement('#captcha-general-settings', 10);
        $result = $I->executeAsyncJS('
            const values = arguments[0];
            const done = arguments[arguments.length - 1];
            const form = document.getElementById("captcha-general-settings");
            const data = new FormData(form);
            const old = {};
            Object.keys(values).forEach(name => {
                old[name] = data.get(name);
                data.set(name, values[name]);
            });
            fetch(form.action, {
                method: "POST",
                body: data,
                credentials: "same-origin",
                headers: {"X-Requested-With": "XMLHttpRequest"}
            }).then(r => r.json()).then(res => done({old: old, status: res.status})).catch(e => done({error: String(e)}));
        ', [$values]);

        if (!is_array($result) or ($result['status'] ?? '') !== 'OK') {
            throw new \RuntimeException('Lưu cấu hình captcha thất bại: ' . json_encode($result));
        }

        return $result['old'];
    }

    /**
     * Xóa cookie để có phiên mới (giống trình duyệt/IP khác) rồi mở URL
     *
     * @param AcceptanceTester $I
     * @param string           $path
     */
    protected function newSession(AcceptanceTester $I, string $path): void
    {
        $I->amOnUrl($I->getDomain() . $path);
        $I->executeInSelenium(function (RemoteWebDriver $wd) {
            $wd->manage()->deleteAllCookies();
        });
        $I->amOnUrl($I->getDomain() . $path);
    }

    /**
     * Chèn bộ đếm cho khóa, $ago là số giây kể từ lần sai gần nhất
     *
     * @param AcceptanceTester $I
     * @param string           $key
     * @param int              $count
     * @param int              $ago
     */
    protected function haveAttempts(AcceptanceTester $I, string $key, int $count, int $ago = 0): void
    {
        $time = time() - $ago;
        $I->haveInDatabase($this->table, [
            'keyname' => $key,
            'count' => $count,
            'starttime' => $time,
            'lasttime' => $time
        ]);
    }

    /**
     * Khóa bộ đếm mật khẩu của tài khoản test
     *
     * @return string
     */
    protected function accountKey(): string
    {
        return LoginTracker::getKey($this->userid, $_ENV['NV_USERNAME']);
    }

    /**
     * Tạm bật 2FA bằng ứng dụng cho tài khoản test
     *
     * @param AcceptanceTester $I
     */
    protected function enable2fa(AcceptanceTester $I): void
    {
        if ($this->backup2fa === null) {
            $this->backup2fa = [
                'active2step' => (int) $I->grabFromDatabase($this->prefix . '_users', 'active2step', ['userid' => $this->userid]),
                'secretkey' => (string) $I->grabFromDatabase($this->prefix . '_users', 'secretkey', ['userid' => $this->userid]),
                'pref_2fa' => (int) $I->grabFromDatabase($this->prefix . '_users', 'pref_2fa', ['userid' => $this->userid])
            ];
        }
        $sth = $I->nvPdo()->prepare('UPDATE ' . $this->prefix . '_users SET active2step = 1, secretkey = :s, pref_2fa = 1 WHERE userid = :u');
        $sth->execute([':s' => self::$tfaSecret, ':u' => $this->userid]);
    }

    /**
     * Thêm một mã dự phòng 2FA chưa dùng cho tài khoản test
     *
     * @param AcceptanceTester $I
     * @param string           $code 8 ký tự
     * @return string Mã đã mã hóa như trong CSDL
     */
    protected function addBackupCode(AcceptanceTester $I, string $code): string
    {
        $encrypted = $I->nvCrypt()->encryptDeterministic(mb_strtolower($code));
        $sth = $I->nvPdo()->prepare('INSERT INTO ' . $this->prefix . '_users_backupcodes (userid, code, is_used, time_used, time_creat) VALUES (:u, :c, 0, 0, :t)');
        $sth->execute([':u' => $this->userid, ':c' => $encrypted, ':t' => time()]);
        $this->backupCodes[] = $encrypted;

        return $encrypted;
    }

    /**
     * Giải captcha hình trên form đăng nhập admin đã có sẵn ảnh captcha
     *
     * @param AcceptanceTester $I
     * @param int|null         $before random_num trước khi trang có captcha được nạp
     */
    protected function fillAdminCaptcha(AcceptanceTester $I, ?int $before): void
    {
        $I->waitForElementVisible('#seccode', 5);
        $I->fillField('#seccode', $I->waitCaptchaCode($before));
    }

    /**
     * Bấm nút submit đang hiển thị của form (qua sự kiện click để captcha của site được kích hoạt)
     *
     * @param AcceptanceTester $I
     * @param string           $form CSS selector của form
     */
    protected function clickVisibleSubmit(AcceptanceTester $I, string $form = 'form[data-toggle="userLogin"]'): void
    {
        $I->executeJS('
            const btn = Array.from(document.querySelectorAll(arguments[0] + " [type=submit]")).find(b => b.offsetParent !== null);
            btn.click();
        ', [$form]);
    }

    /**
     * Bấm submit form site rồi giải captcha hình trong modal
     *
     * @param AcceptanceTester $I
     * @param string           $form
     */
    protected function submitWithSiteCaptcha(AcceptanceTester $I, string $form = 'form[data-toggle="userLogin"]'): void
    {
        $before = $I->grabCaptchaRandom();
        $this->clickVisibleSubmit($I, $form);
        $I->waitForElementVisible('#modal-captcha-value', 5);
        $I->fillField('#modal-captcha-value', $I->waitCaptchaCode($before));
        $I->click('#modal-captcha-button');
    }
}
