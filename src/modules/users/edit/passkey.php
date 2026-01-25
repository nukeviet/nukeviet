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
use NukeViet\Module\users\Shared\Emails;
use NukeViet\Webauthn\CertificateChainValidator;
use NukeViet\Webauthn\MetadataStatementRepository;
use NukeViet\Webauthn\SerializerFactory;
use NukeViet\Webauthn\StatusReportRepository;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;

$serializer = SerializerFactory::create();

// Dữ liệu chung của các email liên quan passkey
$email_fields = [
    'first_name' => $user_info['first_name'],
    'last_name' => $user_info['last_name'],
    'username' => $user_info['username'],
    'email' => $user_info['email'],
    'gender' => $user_info['gender'],
    'lang' => NV_LANG_INTERFACE,
    'ip' => NV_CLIENT_IP,
    'user_agent' => nv_autoLinkDisable(NV_USER_AGENT),
    'action_time' => NV_CURRENTTIME,
    'tstep_link' => NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification', true),
    'pass_link' => NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password', true),
    'passkey_link' => NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/passkey', true),
    'code_link' => NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification&amp;type=code', true),
];

// Tạo thử thách
if ($nv_Request->isset_request('create_challenge', 'post')) {
    $enable_login = $nv_Request->get_bool('enable_login', 'post', false);

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
    if ($enable_login) {
        // Yêu cầu tạo passkey
        $authSelect = AuthenticatorSelectionCriteria::create(
            // Sử dụng bất kì thiết bị nào
            authenticatorAttachment: AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,
            // Bắt buộc phải xác minh khi xác thực
            userVerification: AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            // Lưu khóa trên thiết bị để login không cần mật khẩu
            residentKey: AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_REQUIRED
        );
    } else {
        // Yêu cầu tạo security key
        $authSelect = AuthenticatorSelectionCriteria::create(
            // Sử dụng bất kì thiết bị nào
            authenticatorAttachment: AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,
            // Xác minh người dùng nếu có thể, nếu không thì vẫn được
            userVerification: AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_PREFERRED,
            // Không yêu cầu tạo Resident Key
            residentKey: AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_DISCOURAGED
        );
    }

    // Exclude Credentials - các chứng chỉ đã đăng ký trước đó, sẽ không đăng ký lại
    $excludeCredentials = [];
    foreach ($array_data['publicKeys'] as $publicKey) {
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
        PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_DIRECT,
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
    $challenge = json_decode($nv_Request->get_string($module_data . '_creat_challenge', 'session', '', false, false), true);
    if (!is_array($challenge)) {
        $challenge = [];
    }
    if (empty($challenge) or empty($challenge['opts']) or empty($challenge['time']) or time() - $challenge['time'] > 300) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_challenge'),
        ]);
    }

    // Dịch ngược lại PublicKeyCredentialCreationOptions
    try {
        $credentialOptions = $serializer->deserialize(
            $challenge['opts'],
            PublicKeyCredentialCreationOptions::class,
            'json'
        );
    } catch (Throwable $e) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_challenge1'),
        ]);
    }

    $credential = $nv_Request->get_string('credential', 'post', '', false, false);
    if (empty($credential)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential'),
        ]);
    }

    try {
        $publicKeyCredential = $serializer->deserialize(
            $credential,
            PublicKeyCredential::class,
            'json'
        );
    } catch (Throwable $e) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential1'),
        ]);
    }
    if (!$publicKeyCredential->response instanceof AuthenticatorAttestationResponse) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential2'),
        ]);
    }

    // Khởi tạo Validation
    $metadataStatementRepository = new MetadataStatementRepository();
    $statusReportRepository = new StatusReportRepository();
    $certificateChainValidator = new CertificateChainValidator();

    $csmFactory = new CeremonyStepManagerFactory();
    $csmFactory->setAttestationStatementSupportManager(SerializerFactory::getAttestationManager());
    $csmFactory->enableMetadataStatementSupport(
        $metadataStatementRepository,
        $statusReportRepository,
        $certificateChainValidator,
    );

    $creationCSM = $csmFactory->creationCeremony();
    $attestValidator = AuthenticatorAttestationResponseValidator::create($creationCSM);

    try {
        $publicKeyCredentialSource = $attestValidator->check(
            $publicKeyCredential->response,
            $credentialOptions,
            NV_SERVER_NAME
        );
    } catch (Throwable $e) {
        trigger_error($e);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_validator'),
        ]);
    }

    $credential = [];
    $credential['id'] = base64_encode($publicKeyCredentialSource->publicKeyCredentialId);
    $credential['publickey'] = base64_encode($publicKeyCredentialSource->credentialPublicKey);
    $credential['userhandle'] = $publicKeyCredentialSource->userHandle;
    $credential['counter'] = $publicKeyCredentialSource->counter;
    $credential['aaguid'] = $publicKeyCredentialSource->aaguid;
    $credential['type'] = $publicKeyCredentialSource->type;

    $sql = 'SELECT id FROM ' . NV_MOD_TABLE . '_passkey WHERE userid=' . $edit_userid . ' AND keyid=' . $db->quote($credential['id']);
    if ($db->query($sql)->fetchColumn()) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_error_exist'),
        ]);
    }

    $enable_login = (int) $nv_Request->get_bool('enable_login', 'post', false);
    if ($enable_login) {
        $nickname = 'Passkey ' . ($array_data['login_keys'] + 1);
    } else {
        $nickname = 'Security key ' . ($array_data['security_keys'] + 1);
    }

    if (empty($credential['userhandle']) and $enable_login) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('passkey_error_userhandle'),
        ]);
    }

    $sql = 'INSERT INTO ' . NV_MOD_TABLE . '_passkey (
        userid, keyid, publickey, userhandle, counter, aaguid, type, created_at, last_used_at, clid, enable_login, nickname
    ) VALUES (
        ' . $edit_userid . ', :keyid, :publickey, :userhandle, :counter, :aaguid, :type,
        ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . $db->quote($client_info['clid']) . ', ' . $enable_login . ', :nickname
    )';

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_passkey', $nickname, $edit_userid);

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':keyid', $credential['id'], PDO::PARAM_STR);
    $stmt->bindParam(':publickey', $credential['publickey'], PDO::PARAM_STR);
    $stmt->bindParam(':userhandle', $credential['userhandle'], PDO::PARAM_STR);
    $stmt->bindParam(':counter', $credential['counter'], PDO::PARAM_INT);
    $stmt->bindParam(':aaguid', $credential['aaguid'], PDO::PARAM_STR);
    $stmt->bindParam(':type', $credential['type'], PDO::PARAM_STR);
    $stmt->bindParam(':nickname', $nickname, PDO::PARAM_STR);
    $stmt->execute();

    // Cập nhật số lượng khóa
    $sql = 'UPDATE ' . NV_MOD_TABLE . ' SET sec_keys=sec_keys+1, last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid;
    $db->query($sql);

    if ($enable_login) {
        // Thông báo về khóa đăng nhập
        $email_fields['passkey'] = $nickname;
        $send_data = [[
            'to' => $user_info['email'],
            'data' => $email_fields
        ]];
        nv_sendmail_template_async([$module_name, Emails::PASSKEY_ADD], $send_data);
    } else {
        // Thông báo về khóa bảo mật
        $email_fields['security_key'] = $nickname;
        $send_data = [[
            'to' => $user_info['email'],
            'data' => $email_fields
        ]];
        nv_sendmail_template_async([$module_name, Emails::SECURITY_KEY_ADD], $send_data);
    }

    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'Success',
    ]);
}

// Xóa passkey
if ($nv_Request->isset_request('del', 'post')) {
    $id = $nv_Request->get_int('del', 'post', 0);
    $key_info = [];
    foreach ($array_data['publicKeys'] as $publicKey) {
        if ($publicKey['id'] == $id) {
            $key_info = $publicKey;
            break;
        }
    }
    if (empty($key_info)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Passkey not found',
        ]);
    }

    $sql = 'DELETE FROM ' . NV_MOD_TABLE . '_passkey WHERE userid=' . $edit_userid . ' AND id=' . $id;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_passkey', 'id: ' . $id . '. Type: ' . (empty($key_info['enable_login']) ? 'security key' : 'passkey'), $edit_userid);

    // Cập nhật số lượng khóa
    $sql = 'UPDATE ' . NV_MOD_TABLE . ' SET sec_keys=IF(sec_keys > 0, sec_keys - 1, 0), last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid;
    $db->query($sql);

    // Xóa hết khóa thì set xác thực 2 bước ưu thích về 0 nếu nó là = 2
    if (count($array_data['publicKeys']) == 1) {
        $sql = 'UPDATE ' . NV_MOD_TABLE . ' SET pref_2fa=0 WHERE userid=' . $edit_userid . ' AND pref_2fa=2';
        $db->query($sql);
    }

    if (!empty($key_info['enable_login'])) {
        // Thông báo về khóa đăng nhập
        $email_fields['passkey'] = $key_info['nickname'];
        $send_data = [[
            'to' => $user_info['email'],
            'data' => $email_fields
        ]];
        nv_sendmail_template_async([$module_name, Emails::PASSKEY_DEL], $send_data);
    } else {
        // Thông báo về khóa bảo mật
        $email_fields['security_key'] = $key_info['nickname'];
        $send_data = [[
            'to' => $user_info['email'],
            'data' => $email_fields
        ]];
        nv_sendmail_template_async([$module_name, Emails::SECURITY_KEY_DEL], $send_data);
    }

    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'Success',
    ]);
}

// Sửa nickname
if ($nv_Request->isset_request('edit', 'post')) {
    $id = $nv_Request->get_int('edit', 'post', 0);
    $check_exists = false;
    foreach ($array_data['publicKeys'] as $publicKey) {
        if ($publicKey['id'] == $id) {
            $check_exists = true;
            break;
        }
    }
    if (!$check_exists) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Passkey not found',
        ]);
    }

    $nickname = nv_substr($nv_Request->get_title('nickname', 'post', ''), 0, 100);
    if (empty($nickname)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Nickname is empty',
        ]);
    }

    $sql = 'UPDATE ' . NV_MOD_TABLE . '_passkey SET nickname=' . $db->quote($nickname) . ' WHERE userid=' . $edit_userid . ' AND id=' . $id;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_passkey', 'id: ' . $id . '. New: ' . $nickname, $edit_userid);

    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'Success',
    ]);
}
