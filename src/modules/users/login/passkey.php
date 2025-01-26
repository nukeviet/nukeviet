<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER') or !defined('NV_MOD_LOAD')) {
    exit('Stop!!!');
}

use Webauthn\PublicKeyCredentialRequestOptions;
use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\ECDSA;
use Cose\Algorithm\Signature\RSA;
use Cose\Algorithms;
use NukeViet\Module\users\Shared\Emails;
use NukeViet\Webauthn\CertificateChainValidator;
use NukeViet\Webauthn\MetadataStatementRepository;
use NukeViet\Webauthn\StatusReportRepository;
use Symfony\Component\Clock\NativeClock;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Webauthn\AttestationStatement\AndroidKeyAttestationStatementSupport;
use Webauthn\AttestationStatement\AppleAttestationStatementSupport;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\FidoU2FAttestationStatementSupport;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AttestationStatement\PackedAttestationStatementSupport;
use Webauthn\AttestationStatement\TPMAttestationStatementSupport;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\AuthenticatorAssertionResponseValidator;

/**
 * Chuẩn bị input loading
 */
$clock = new NativeClock();

// Trong website hoặc xác thực 2 bước chỉ cần Attestation None là đủ
$attestMgr = AttestationStatementSupportManager::create();
$attestMgr->add(NoneAttestationStatementSupport::create());

$attestMgr->add(FidoU2FAttestationStatementSupport::create());
$attestMgr->add(AppleAttestationStatementSupport::create());

$attestMgr->add(AndroidKeyAttestationStatementSupport::create());
$attestMgr->add(TPMAttestationStatementSupport::create($clock));

$coseAlgorithmManager = Manager::create();
$coseAlgorithmManager->add(ECDSA\ES256K::create());
$coseAlgorithmManager->add(ECDSA\ES256::create());
$coseAlgorithmManager->add(RSA\RS256::create());

$attestMgr->add(PackedAttestationStatementSupport::create($coseAlgorithmManager));

$factory = new WebauthnSerializerFactory($attestMgr);
$serializer = $factory->create();

// Tạo thử thách đăng nhập passkey
if ($nv_Request->isset_request('create_challenge', 'post')) {
    $requestOptions = PublicKeyCredentialRequestOptions::create(
        random_bytes(32),
        userVerification: PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_REQUIRED,
        rpId: NV_SERVER_NAME,
        timeout: 180
    );

    $jsonObject = $serializer->serialize(
        $requestOptions,
        'json',
        [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            JsonEncode::OPTIONS => JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT,
        ]
    );

    $nv_Request->set_Session($module_data . '_login_challenge', json_encode([
        'opts' => $jsonObject,
        'time' => time(),
    ]));

    nv_jsonOutput([
        'status' => 'ok',
        'requestOptions' => $jsonObject,
    ]);
}

// Đăng nhập passkey
if ($nv_Request->isset_request('auth_assertion', 'post')) {
    $challenge = json_decode($nv_Request->get_string($module_data . '_login_challenge', 'session', ''), true);
    if (!is_array($challenge)) {
        $challenge = [];
    }
    if (empty($challenge) or empty($challenge['opts']) or empty($challenge['time']) or time() - $challenge['time'] > 300) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_error_challenge'),
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
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_error_challenge1'),
        ]);
    }

    $assertion = $nv_Request->get_string('assertion', 'post', '', false, false);
    if (empty($assertion)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_error_credential'),
        ]);
    }

    try {
        $publicKeyCredential = $serializer->deserialize(
            $assertion,
            PublicKeyCredential::class,
            'json'
        );
    } catch (Throwable $e) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_error_credential1'),
        ]);
    }
    if (!$publicKeyCredential->response instanceof AuthenticatorAssertionResponse) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_error_credential2'),
        ]);
    }

    $keyid = base64_encode($publicKeyCredential->rawId);
    $userhandle = $publicKeyCredential->response->userHandle;

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
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_cannot_login'),
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
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_error_validator'),
        ]);
    }

    // Passkey chỉ là security key, không phải là mật khẩu
    if (empty($row['enable_login'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_only_seckey'),
        ]);
    }
    // Nếu tài khoản bị đình chỉ
    if (empty($row['active'])) {
        nv_jsonOutput([
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

    validUserLog($row, 1, [
        'nickname' => $row['passkey_name'],
    ], 6);
    $blocker->reset_trackLogin($row['username']);
    $blocker->reset_trackLogin($row['email']);

    nv_jsonOutput([
        'status' => 'ok',
        'mess' => $nv_Lang->getModule('login_ok')
    ]);
}

nv_htmlOutput('Not found');
