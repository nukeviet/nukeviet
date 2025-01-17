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

use Cose\Algorithms;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\Denormalizer\WebauthnSerializerFactory;

/**
 * Chuẩn bị input loading
 */

// Trong website hoặc xác thực 2 bước chỉ cần Attestation None là đủ
$attestMgr = AttestationStatementSupportManager::create();
$attestMgr->add(NoneAttestationStatementSupport::create());

$factory = new WebauthnSerializerFactory($attestMgr);
$serializer = $factory->create();

// Tạo thử thách
if ($nv_Request->isset_request('create_challenge', 'post')) {
    /**
     * name: tên website
     * id: tên miền website
     * icon: ảnh đại diện dạng base64
     * @var mixed
     */
    $logo = null;
    if (!empty($global_config['site_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_logo'])) {
        $logo = 'data:image/' . nv_getextension($global_config['site_logo']) . ';base64,' . base64_encode(file_get_contents(NV_ROOTDIR . '/' . $global_config['site_logo']));
    }
    $rpEntity = PublicKeyCredentialRpEntity::create(
        $global_config['site_name'],
        NV_SERVER_NAME,
        $logo
    );

    /**
     * name: tên người dùng ví dụ user name
     * id: id người dùng, không thể thay đổi
     * displayName: tên hiển thị như là họ và tên
     * icon: ảnh đại diện dạng base64
     * @var mixed
     */
    $photo = null;
    if (!empty($user_info['photo']) and file_exists(NV_ROOTDIR . '/' . $user_info['photo'])) {
        $photo = 'data:image/' . nv_getextension($user_info['photo']) . ';base64,' . base64_encode(file_get_contents(NV_ROOTDIR . '/' . $user_info['photo']));
    }
    $userEntity = PublicKeyCredentialUserEntity::create(
        $user_info['username'],
        md5('U:' . $edit_userid),
        $user_info['full_name'],
        $photo
    );

    // Loại mã hóa được hỗ trợ
    $credentialParams = [
        PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_ES256K),
        PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_ES256),
        PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_RS256)
    ];

    // Authenticator Selection
    $authSelect = AuthenticatorSelectionCriteria::create(
        // Không chỉ định
        authenticatorAttachment: AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,
        // Bắt buộc phải xác minh khi xác thực
        userVerification: AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
        // Lưu khóa trên thiết bị để login không cần mật khẩu
        residentKey: AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_REQUIRED
    );

    // Xác định các khóa đã đăng ký trước đó
    $publicKeys = [];

    $sql = 'SELECT id, keyid FROM ' . NV_MOD_TABLE . '_passkey WHERE userid=' . $edit_userid;
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        $publicKeys[] = $_row;
    }
    $result->closeCursor();

    // Attestation: không cần chứng thực mà vẫn đảm bảo bảo mật
    // Exclude Credentials - các chứng chỉ đã đăng ký trước đó, sẽ không đăng ký lại
    $excludeCredentials = [];
    foreach ($publicKeys as $publicKey) {
        $excludeCredentials[] = PublicKeyCredentialDescriptor::create('public-key', base64_decode($publicKey['keyid']));
    }

    // Tạo PublicKey Credential
    $challenge = random_bytes(32);
    $credentialOptions = PublicKeyCredentialCreationOptions::create(
        $rpEntity,
        $userEntity,
        $challenge,
        $credentialParams,
        $authSelect,
        null,
        $excludeCredentials,
        60
    );

    $jsonObject = $serializer->serialize(
        $credentialOptions,
        'json',
        [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            JsonEncode::OPTIONS => JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT,
        ]
    );

    $nv_Request->set_Session($module_data . '_creat_challenge', json_encode([
        'opts' => $jsonObject,
        'time' => time(),
    ]));

    nv_jsonOutput([
        'status' => 'ok',
        'credentialOptions' => $jsonObject
    ]);
}

// Lưu khóa truy cập
if ($nv_Request->isset_request('save_credential', 'post')) {

}
