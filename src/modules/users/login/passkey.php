<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

use NukeViet\Webauthn\RequestPasskey;
use NukeViet\Webauthn\SerializerFactory;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op);
$csrf = $nv_Request->get_title('checkss', 'post', '');
if (!hash_equals($checkss, $csrf)) {
    signin_result([
        'status' => 'error',
        'mess' => 'Session error, please reload page and try again!',
    ]);
}

// Tạo thử thách đăng nhập passkey
if ($nv_Request->isset_request('create_challenge', 'post')) {
    $jsonObject = RequestPasskey::create();
    $nv_Request->set_Session($module_data . '_login_challenge', json_encode([
        'opts' => $jsonObject,
        'time' => time(),
    ]));
    signin_result([
        'status' => 'ok',
        'requestOptions' => $jsonObject,
    ]);
}

$serializer = SerializerFactory::create();

// Đăng nhập passkey
if ($nv_Request->isset_request('auth_assertion', 'post')) {
    $challenge = json_decode($nv_Request->get_string($module_data . '_login_challenge', 'session', '', false, false), true);
    $nv_Request->unset_request($module_data . '_login_challenge', 'session');
    if (!is_array($challenge)) {
        $challenge = [];
    }
    if (empty($challenge) or empty($challenge['opts']) or empty($challenge['time']) or time() - $challenge['time'] > 300) {
        signin_result([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_challenge'),
        ]);
    }

    // Dịch ngược lại PublicKeyCredentialRequestOptions
    try {
        $requestOptions = $serializer->deserialize(
            $challenge['opts'],
            PublicKeyCredentialRequestOptions::class,
            'json'
        );
    } catch (Throwable $e) {
        signin_result([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_challenge1'),
        ]);
    }

    $assertion = $nv_Request->get_string('assertion', 'post', '', false, false);
    if (empty($assertion)) {
        signin_result([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential'),
        ]);
    }

    try {
        $publicKeyCredential = $serializer->deserialize(
            $assertion,
            PublicKeyCredential::class,
            'json'
        );
    } catch (Throwable $e) {
        signin_result([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential1'),
        ]);
    }
    if (!$publicKeyCredential->response instanceof AuthenticatorAssertionResponse) {
        signin_result([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential2'),
        ]);
    }

    $keyid = base64_encode($publicKeyCredential->rawId);
    $userhandle = $publicKeyCredential->response->userHandle; // phpcs:ignore

    $sql = 'SELECT
        tb2.*, tb1.id passkey_id, tb1.enable_login, tb1.keyid, tb1.userhandle,
        tb1.publickey, tb1.counter keycounter, tb1.aaguid, tb1.type keytype, tb1.nickname passkey_name
    FROM ' . NV_MOD_TABLE . '_passkey tb1
    INNER JOIN ' . NV_MOD_TABLE . ' tb2 ON tb1.userid=tb2.userid
    WHERE tb1.userhandle=:userhandle AND tb1.keyid=:keyid';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':userhandle', $userhandle, PDO::PARAM_STR);
    $stmt->bindParam(':keyid', $keyid, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    if (empty($row)) {
        signin_result([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_cannot_login'),
        ]);
    }

    // Kiểm tra an ninh khóa này
    $publicKeyCredentialSource = PublicKeyCredentialSource::create(
        base64_decode($row['keyid']),
        $row['keytype'], [], 'none',
        \Webauthn\TrustPath\EmptyTrustPath::create(),
        \Symfony\Component\Uid\Uuid::fromString($row['aaguid']),
        base64_decode($row['publickey']),
        $row['userhandle'], $row['keycounter']
    );

    // Khởi tạo Validation
    $csmFactory = new CeremonyStepManagerFactory();
    $requestCSM = $csmFactory->requestCeremony();
    $assertValidator = AuthenticatorAssertionResponseValidator::create($requestCSM);

    try {
        $publicKeyCheck = $assertValidator->check(
            $publicKeyCredentialSource,
            $publicKeyCredential->response,
            $requestOptions,
            NV_SERVER_NAME,
            $userhandle
        );
    } catch (Throwable $e) {
        signin_result([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_validator'),
        ]);
    }

    // Passkey chỉ là security key, không phải là mật khẩu
    if (empty($row['enable_login'])) {
        signin_result([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_only_seckey'),
        ]);
    }
    // Nếu tài khoản bị đình chỉ
    if (empty($row['active'])) {
        signin_result([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('login_no_active')
        ]);
    }

    // Cập nhật lại passkey
    $credential = [];
    $credential['id'] = base64_encode($publicKeyCheck->publicKeyCredentialId);
    $credential['publickey'] = base64_encode($publicKeyCheck->credentialPublicKey);
    $credential['userHandle'] = $publicKeyCheck->userHandle;
    $credential['counter'] = $publicKeyCheck->counter;

    $sql = 'UPDATE ' . NV_MOD_TABLE . '_passkey SET
        counter=:counter, last_used_at=' . NV_CURRENTTIME . '
    WHERE id=' . $row['passkey_id'];
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':counter', $credential['counter'], PDO::PARAM_INT);
    $stmt->execute();
    unset($credential);

    $blocker->reset_trackLogin($row['username']);
    $blocker->reset_trackLogin($row['email']);

    // Nếu đăng nhập bằng forum hoặc sso
    if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
        define('NV_SET_LOGIN_MODE', 'PASSKEY');
        require NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/set_user_login.php';
    }

    validUserLog($row, 1, [
        'nickname' => $row['passkey_name'],
    ], 6);

    signin_result([
        'status' => 'ok',
        'mess' => $nv_Lang->getModule('login_ok')
    ]);
}

nv_error404();
