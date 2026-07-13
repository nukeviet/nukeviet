<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Unit;

use Tests\Support\UnitTester;

class EncryptionTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    private $siteKey = 'f70ec864dd62e6ddc024972ab39566d2';

    /**
     * @var \NukeViet\Core\Encryption
     */
    private $crypt;

    protected function _before()
    {
        $this->crypt = new \NukeViet\Core\Encryption($this->siteKey);
    }

    protected function _after()
    {
    }

    /**
     * @group install
     * @group all
     * @group cache
     */
    public function testEncryptionHash()
    {
        $string = 'testdata';
        $hashEqual = '5812f339cd1d74816b1f2c6e8486bc00f5d1713a';

        $this->assertEquals($hashEqual, $this->crypt->hash($string));
    }

    /**
     * Mã hóa GCM: giải mã lại đúng và không lặp lại (IV ngẫu nhiên)
     *
     * @group all
     */
    public function testGcmRoundtripAndRandomIv()
    {
        $plain = 'sm7p-p@ssw0rd';
        $enc = $this->crypt->encrypt($plain);

        $this->assertSame($plain, $this->crypt->decrypt($enc));
        $this->assertNotEquals($this->crypt->encrypt($plain), $this->crypt->encrypt($plain));
    }

    /**
     * Cutover: decrypt() (GCM) KHÔNG đọc dữ liệu định dạng cũ CBC nữa;
     * dữ liệu cũ chỉ đọc được qua decryptDeterministic() (do tool migrate dùng)
     *
     * @group all
     */
    public function testLegacyCbcNotReadableByGcmDecrypt()
    {
        $plain = 'legacy-value';
        $legacy = $this->crypt->encryptDeterministic($plain); // định dạng CBC như bản cũ

        $this->assertFalse($this->crypt->decrypt($legacy));
        $this->assertSame($plain, $this->crypt->decryptDeterministic($legacy));
    }

    /**
     * Mã hóa xác định: cùng đầu vào cho cùng bản mã (phục vụ so khớp mã dự phòng 2 bước)
     *
     * @group all
     */
    public function testDeterministicEncryption()
    {
        $plain = 'backup-code-1';

        $this->assertSame($this->crypt->encryptDeterministic($plain), $this->crypt->encryptDeterministic($plain));
        $this->assertSame($plain, $this->crypt->decryptDeterministic($this->crypt->encryptDeterministic($plain)));
    }

    /**
     * AAD ràng buộc ngữ cảnh: sai AAD thì giải mã thất bại
     *
     * @group all
     */
    public function testAadBinding()
    {
        $enc = $this->crypt->encrypt('filter-sql', 'session-a');

        $this->assertSame('filter-sql', $this->crypt->decrypt($enc, 'session-a'));
        $this->assertFalse($this->crypt->decrypt($enc, 'session-b'));
    }

    /**
     * Toàn vẹn: bản mã bị chỉnh sửa phải bị từ chối (trả về false)
     *
     * @group all
     */
    public function testTamperDetection()
    {
        $enc = $this->crypt->encrypt('important-data');
        $tampered = substr($enc, 0, 5) . ($enc[5] === 'A' ? 'B' : 'A') . substr($enc, 6);

        $this->assertFalse($this->crypt->decrypt($tampered));
    }

    /**
     * Trả về đối tượng Encryption dùng ĐÚNG sitekey thật của site, hoặc bỏ qua test
     * nếu môi trường không có DB/sitekey (VD: CI không cấu hình).
     *
     * @return \NukeViet\Core\Encryption
     */
    private function realCrypt()
    {
        global $db, $global_config;

        if (empty($db) or empty($global_config['sitekey'])) {
            $this->markTestSkipped('Cần kết nối DB và sitekey thật của site để chạy test này.');
        }

        return new \NukeViet\Core\Encryption($global_config['sitekey']);
    }

    /**
     * Dữ liệu at-rest đang lưu trong DB phải giải mã được bằng GCM.
     * Với mỗi secret cấu hình + api_user.secret khác rỗng, decrypt() PHẢI ra chuỗi
     * (không được false). Nếu false nghĩa là còn dữ liệu CBC chưa migrate sang GCM.
     *
     * @group migration
     * @group all
     */
    public function testAtRestConfigSecretsDecryptToString()
    {
        global $db, $db_config;

        $crypt = $this->realCrypt();

        $config_names = [
            'smtp_password',
            'ftp_user_pass',
            'redis_password',
            'recaptcha_secretkey',
            'turnstile_secretkey',
            'load_files_seccode'
        ];
        $in = "'" . implode("','", $config_names) . "'";
        $rows = $db->query('SELECT lang, config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE
            . " WHERE config_name IN (" . $in . ") AND config_value != ''")->fetchAll();

        foreach ($rows as $row) {
            $plain = $crypt->decrypt($row['config_value']);
            $this->assertIsString(
                $plain,
                "decrypt() thất bại cho config '" . $row['config_name'] . "' (lang=" . $row['lang'] . ") — có thể chưa migrate sang GCM"
            );
        }

        // api_user.secret (module myapi có thể chưa cài -> bỏ qua nếu bảng không tồn tại)
        try {
            $api_rows = $db->query('SELECT userid, method, secret FROM ' . $db_config['prefix'] . "_api_user WHERE secret != ''")->fetchAll();
        } catch (\Throwable $e) {
            $api_rows = null;
        }

        if (is_array($api_rows)) {
            foreach ($api_rows as $row) {
                $plain = $crypt->decrypt($row['secret']);
                $this->assertIsString(
                    $plain,
                    "decrypt() thất bại cho api_user.secret (userid=" . $row['userid'] . ", method=" . $row['method'] . ")"
                );
            }
        }
    }

    /**
     * Mã dự phòng 2 bước lưu trong _users_backupcodes vẫn dùng mã hóa xác định.
     * Với mỗi code khác rỗng/false/null, decryptDeterministic() BẮT BUỘC ra chuỗi
     * khác rỗng (không được false/null/'').
     *
     * @group migration
     * @group all
     */
    public function testBackupcodesDecryptDeterministicToNonEmptyString()
    {
        global $db, $db_config;

        $crypt = $this->realCrypt();

        $table = $db_config['prefix'] . '_users_backupcodes';
        try {
            $rows = $db->query('SELECT userid, code FROM ' . $table)->fetchAll();
        } catch (\Throwable $e) {
            $this->markTestSkipped('Bảng ' . $table . ' không tồn tại (module users chưa cài?).');
        }

        foreach ($rows as $row) {
            if ($row['code'] === null or $row['code'] === '' or $row['code'] === false) {
                continue;
            }
            $plain = $crypt->decryptDeterministic($row['code']);
            $this->assertIsString($plain, 'decryptDeterministic() không ra chuỗi cho backupcode (userid=' . $row['userid'] . ')');
            $this->assertNotSame('', $plain, 'decryptDeterministic() ra chuỗi rỗng cho backupcode (userid=' . $row['userid'] . ')');
        }
    }
}
