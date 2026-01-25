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

/**
 *
 */
class UsersSiteCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    /**
     * @param AcceptanceTester $I
     * @link https://github.com/nukeviet/nukeviet/issues/3807
     *
     * @group users
     * @group all
     */
    public function testErrorUrlEditInfo(AcceptanceTester $I)
    {
        $I->wantTo('Check for errors when entering arbitrary parameters into the URL while editing account information');
        $I->userLogin();

        $I->amOnUrl($I->getDomain() . '/vi/users/editinfo/basic//');

        // Không được phép xuất hiện json này
        $I->cantSee('"error"');
    }

    // Tạo signed_request giả (base64url(sig) . '.' . base64url(payload_json))
    protected function makeSignedRequest(array $payload)
    {
        // payload phải là JSON (facebook thường gửi 'algorithm' field etc)
        $payloadWithMeta = array_merge([
            'algorithm' => 'HMAC-SHA256',
            'expires' => time() + 86400,
            'issued_at' => time(),
        ], $payload);
        $payloadJson = json_encode($payloadWithMeta);

        // $sig = hash_hmac('sha256', $payloadJson, $_ENV['FACEBOOK_APP_SECRET'], true); // raw binary
        // $sig_b64 = $this->base64UrlEncode($sig);
        // $payload_b64 = $this->base64UrlEncode($payloadJson);

        // return $sig_b64 . '.' . $payload_b64;

        // New version with payload base64url-encoded first
        $payload_b64 = $this->base64UrlEncode($payloadJson);
        $sig = hash_hmac('sha256', $payload_b64, $_ENV['FACEBOOK_APP_SECRET'], true);
        $sig_b64 = $this->base64UrlEncode($sig);

        return $sig_b64 . '.' . $payload_b64;
    }

    protected function base64UrlEncode($input)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($input));
    }

    /**
     * @param AcceptanceTester $I
     *
     * @group users
     * @group user-datadeletion
     * @group user-datadeletion-only
     * @group all
     */
    public function requestUserDataDeletion(AcceptanceTester $I)
    {
        $I->wantTo('Check User Data Deletion Requests feature works correctly');

        // Đưa 1 bản ghi openid vào CSDL
        $sitekey = $I->getSiteKey();
        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $crypt = new \NukeViet\Core\Encryption($sitekey);

        $user_id = '1234567890';
        $opid = $crypt->hash($user_id);

        $I->haveInDatabase($prefix . '_users_openid', [
            'userid' => 2,
            'openid' => 'facebook',
            'opid' => $opid,
            'id' => $user_id,
            'email' => ''
        ]);
        $I->seeInDatabase($prefix . '_users_openid', [
            'openid' => 'facebook',
            'opid' => $opid,
        ]);

        // Gửi request giả lập
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->haveHttpHeader('Origin', $I->getDomain());
        $I->haveHttpHeader('Referer', $I->getDomain());

        $signed_request = $this->makeSignedRequest(['user_id' => $user_id]);

        $I->sendPost($I->getDomain() . '/vi/users/datadeletion/facebook/', [
            'signed_request' => $signed_request
        ]);

        // Kiểm tra json thành công
        $json = $I->grabResponse();
        $data = json_decode($json, true);
        if (!is_array($data) or empty($data['url']) or empty($data['confirmation_code'])) {
            throw new \Exception('Data deletion request failed or invalid response: ' . $json);
        }

        // Kiểm tra bản ghi đã được tạo trong bảng deleted
        $I->seeInDatabase($prefix . '_users_deleted', [
            'confirmation_code' => $data['confirmation_code']
        ]);

        // Vào trang url xem kết quả
        $I->amOnUrl($data['url']);
        $I->see('Yêu cầu của bạn để xóa dữ liệu cá nhân đã được tiếp nhận và xử lý thành công');
    }
}
