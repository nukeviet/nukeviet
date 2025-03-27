<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Webauthn\RequestPasskey;
use NukeViet\Webauthn\SerializerFactory;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;

// Kiểm tra IP
if (!nv_admin_checkip()) {
    nv_info_die($global_config['site_description'], $nv_Lang->getGlobal('site_info'), $nv_Lang->getGlobal('admin_ipincorrect', NV_CLIENT_IP) . '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />');
}

// Kiểm tra tường lửa
if (!nv_admin_checkfirewall()) {
    // remove non US-ASCII to respect RFC2616
    $server_message = preg_replace('/[^\x20-\x7e]/i', '', $nv_Lang->getGlobal('firewallsystem'));
    if (empty($server_message)) {
        $server_message = 'Administrators Section';
    }
    header('WWW-Authenticate: Basic realm="' . $server_message . '"');
    header(NV_HEADERSTATUS . ' 401 Unauthorized');
    if (php_sapi_name() !== 'cgi-fcgi') {
        header('status: 401 Unauthorized');
    }
    nv_info_die($global_config['site_description'], $nv_Lang->getGlobal('site_info'), $nv_Lang->getGlobal('firewallincorrect') . '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />', 401);
}

// Load ngôn ngữ
$nv_Lang->loadGlobal(true);

// Kiểm tra xem đã login xong bước 1 chưa
$admin_pre_data = nv_admin_check_predata($nv_Request->get_string('admin_pre', 'session', ''));
$admin_login_redirect = $nv_Request->get_string('admin_login_redirect', 'session', '');

$blocker = new NukeViet\Core\Blocker(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs', NV_CLIENT_IP);
$rules = [
    $global_config['login_number_tracking'],
    $global_config['login_time_tracking'],
    $global_config['login_time_ban']
];
$blocker->trackLogin($rules, $global_config['is_login_blocker']);

$error = '';
$array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
if (!empty($array_gfx_chk) and in_array('a', $array_gfx_chk, true)) {
    $gfx_chk = 1;
} else {
    $gfx_chk = 0;
}
$captcha_type = (empty($global_config['captcha_type']) or in_array($global_config['captcha_type'], ['captcha', 'recaptcha', 'turnstile'], true)) ? $global_config['captcha_type'] : 'captcha';
if ($captcha_type == 'recaptcha' and (empty($global_config['recaptcha_sitekey']) or empty($global_config['recaptcha_secretkey']))) {
    $captcha_type = 'captcha';
} elseif ($captcha_type == 'turnstile' and (empty($global_config['turnstile_sitekey']) or empty($global_config['turnstile_secretkey']))) {
    $captcha_type = 'captcha';
}

$admin_login_success = false;
/** @disregard P1011 */
$passkey_allowed = !(defined('SSO_SERVER') and (defined('NV_IS_USER_FORUM') or NV_MY_DOMAIN != SSO_REGISTER_DOMAIN));

// Tạo thử thách đăng nhập passkey
if ($passkey_allowed and $nv_Request->isset_request('create_challenge', 'post')) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if (NV_CHECK_SESSION !== $checkss) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Session error, please reload page!'
        ]);
    }

    $jsonObject = RequestPasskey::create();
    $nv_Request->set_Session('admin_login_challenge', json_encode([
        'opts' => $jsonObject,
        'time' => time(),
    ]));
    nv_jsonOutput([
        'status' => 'ok',
        'requestOptions' => $jsonObject,
    ]);
}

// Tạo thử thách xác thực passkey
if ($passkey_allowed and $nv_Request->isset_request('create_auth_challenge', 'post') and !empty($admin_pre_data)) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if (NV_CHECK_SESSION !== $checkss) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Session error, please reload page!'
        ]);
    }

    // Lấy các khóa được phép
    $allowCredentials = [];
    $sql = 'SELECT keyid, type FROM ' . NV_USERS_GLOBALTABLE . '_passkey WHERE userid=' . $admin_pre_data['userid'];
    $result = $db->query($sql);
    while ($credential = $result->fetch()) {
        $allowCredentials[] = PublicKeyCredentialDescriptor::create($credential['type'], base64_decode($credential['keyid']));
    }
    $result->closeCursor();

    $jsonObject = RequestPasskey::create(false, $allowCredentials);
    $nv_Request->set_Session('admin_auth_challenge', json_encode([
        'opts' => $jsonObject,
        'time' => time(),
    ]));
    nv_jsonOutput([
        'status' => 'ok',
        'requestOptions' => $jsonObject,
    ]);
}

// Đăng nhập bằng passkey
if ($passkey_allowed and $nv_Request->isset_request('login_assertion', 'post')) {
    $challenge = json_decode($nv_Request->get_string('admin_login_challenge', 'session', '', false, false), true);
    $nv_Request->unset_request('admin_login_challenge', 'session');
    if (!is_array($challenge)) {
        $challenge = [];
    }
    if (empty($challenge) or empty($challenge['opts']) or empty($challenge['time']) or time() - $challenge['time'] > 300) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_challenge'),
        ]);
    }

    $serializer = SerializerFactory::create();

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
            'mess' => $nv_Lang->getGlobal('passkey_error_challenge1'),
        ]);
    }

    $assertion = $nv_Request->get_string('assertion', 'post', '', false, false);
    if (empty($assertion)) {
        nv_jsonOutput([
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
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential1'),
        ]);
    }
    if (!$publicKeyCredential->response instanceof AuthenticatorAssertionResponse) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential2'),
        ]);
    }

    $keyid = base64_encode($publicKeyCredential->rawId);
    $userhandle = $publicKeyCredential->response->userHandle; // phpcs:ignore

    // Gọi hàm lấy thông tin đăng nhập để đồng bộ với các phương thức khác
    require NV_ROOTDIR . '/modules/users/methods/adm_passkey.php';
    $row = check_admin_login($keyid, $userhandle);

    if (empty($row)) {
        nv_jsonOutput([
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
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_validator'),
        ]);
    }

    // Passkey chỉ là security key, không phải là mật khẩu
    if (empty($row['enable_login'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_only_seckey'),
        ]);
    }

    $row['admin_lev'] = (int) ($row['admin_lev']);

    // Kiểm tra quyền đăng nhập (do cấu hình hệ thống quy định)
    if (!defined('ADMIN_LOGIN_MODE')) {
        define('ADMIN_LOGIN_MODE', 3);
    }
    if (ADMIN_LOGIN_MODE == 2 and !in_array($row['admin_lev'], [1, 2], true)) {
        // Điều hành chung + Tối cao được đăng nhập
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('admin_access_denied2')
        ]);
    }

    if (ADMIN_LOGIN_MODE == 1 and $row['admin_lev'] != 1) {
        // Tối cao được đăng nhập
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('admin_access_denied1')
        ]);
    }

    // Cập nhật lại passkey
    $credential = [];
    $credential['id'] = base64_encode($publicKeyCheck->publicKeyCredentialId);
    $credential['publickey'] = base64_encode($publicKeyCheck->credentialPublicKey);
    $credential['userHandle'] = $publicKeyCheck->userHandle;
    $credential['counter'] = $publicKeyCheck->counter;

    $sql = 'UPDATE ' . NV_USERS_GLOBALTABLE . '_passkey SET
        counter=:counter, last_used_at=' . NV_CURRENTTIME . '
    WHERE id=' . $row['passkey_id'];
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':counter', $credential['counter'], PDO::PARAM_INT);
    $stmt->execute();
    unset($credential);

    $blocker->reset_trackLogin($row['username']);
    $blocker->reset_trackLogin($row['email']);

    $admin_login_success = true;
}

// Đăng xuất tài khoản login bước 1 để login lại
if (!empty($admin_pre_data) and $nv_Request->isset_request('pre_logout', 'get') and $nv_Request->get_title('checkss', 'get') == NV_CHECK_SESSION) {
    $nv_Request->unset_request('admin_pre', 'session');

    // Gỡ phiên đăng nhập của user nếu trước đó setup xác thực 2 bước
    $user_cookie = NukeViet\Core\User::get_userlogin_hash();
    if (!empty($user_cookie['admin_prelogin'])) {
        if ($global_config['allowuserloginmulti']) {
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_login WHERE userid=' . $admin_pre_data['userid'] . ' AND clid=' . $db->quote($client_info['clid']));
        } else {
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_login WHERE userid=' . $admin_pre_data['userid']);
        }
    }

    exit(0);
}

// Xác định các phương thức xác thực hai bước hệ thống sử dụng
$cfg_2step = [];
if (!empty($admin_pre_data)) {
    $cfg_2step['opts'] = []; // Các hình thức xác thực được phép
    $cfg_2step['default'] = $global_config['admin_2step_default']; // Hình thức mặc định
    $cfg_2step['active_key'] = false;
    $cfg_2step['active_code'] = (bool) ($admin_pre_data['active2step']); // Đã bật xác thực 2 bước bằng ứng dụng hay chưa
    $cfg_2step['active_facebook'] = false; // Đã login bằng Facebook hay chưa
    $cfg_2step['active_google'] = false; // Đã login bằng Google hay chưa
    $cfg_2step['active_zalo'] = false; // Đã login bằng Zalo hay chưa
    $_2step_opt = explode(',', $global_config['admin_2step_opt']);
    if ($passkey_allowed and in_array('key', $_2step_opt, true)) {
        $cfg_2step['opts'][] = 'key';
        if (!empty($admin_pre_data['sec_keys']) and !empty($admin_pre_data['active2step'])) {
            $cfg_2step['active_key'] = true;
        }
    }
    if (in_array('code', $_2step_opt, true)) {
        $cfg_2step['opts'][] = 'code';
    }
    if (in_array('facebook', $_2step_opt, true) and !empty($global_config['facebook_client_id']) and !empty($global_config['facebook_client_secret'])) {
        $cfg_2step['opts'][] = 'facebook';
        $sql = 'SELECT COUNT(oauth_uid) FROM ' . NV_AUTHORS_GLOBALTABLE . '_oauth WHERE admin_id=' . $admin_pre_data['admin_id'] . " AND oauth_server='facebook'";
        $cfg_2step['active_facebook'] = (bool) ($db->query($sql)->fetchColumn());
    }
    if (in_array('google', $_2step_opt, true) and !empty($global_config['google_client_id']) and !empty($global_config['google_client_secret'])) {
        $cfg_2step['opts'][] = 'google';
        $sql = 'SELECT COUNT(oauth_uid) FROM ' . NV_AUTHORS_GLOBALTABLE . '_oauth WHERE admin_id=' . $admin_pre_data['admin_id'] . " AND oauth_server='google'";
        $cfg_2step['active_google'] = (bool) ($db->query($sql)->fetchColumn());
    }
    if (in_array('zalo', $_2step_opt, true) and !empty($global_config['zaloOfficialAccountID']) and !empty($global_config['zaloAppID']) and !empty($global_config['zaloAppSecretKey'])) {
        $cfg_2step['opts'][] = 'zalo';
        $sql = 'SELECT COUNT(oauth_uid) FROM ' . NV_AUTHORS_GLOBALTABLE . '_oauth WHERE admin_id=' . $admin_pre_data['admin_id'] . " AND oauth_server='zalo'";
        $cfg_2step['active_zalo'] = (bool) ($db->query($sql)->fetchColumn());
    }
    if (empty($cfg_2step['default']) or !in_array($cfg_2step['default'], $cfg_2step['opts'], true)) {
        $cfg_2step['default'] = current($cfg_2step['opts']);
    }
    /*
     * Số phương thức xác thực đã được kích hoạt
     * - Khi chưa có phương thức nào thì cho phép kích hoạt một trong số các phương thức đó
     * - Khi đã có rồi thì chỉ được sử dụng phương thức đó để xác thực (có thể 1 hoặc nhiều tùy cấu hình)
     */
    $cfg_2step['count_active'] = count(array_filter([
        $cfg_2step['active_key'],
        $cfg_2step['active_code'],
        $cfg_2step['active_facebook'],
        $cfg_2step['active_google'],
        $cfg_2step['active_zalo']
    ]));
    $cfg_2step['count_opts'] = count($cfg_2step['opts']);
}

/*
 * Chọn phương thức xác thực
 * - Có thể chưa kích hoạt: Điều kiện là chưa có phương thức xác thực nào
 * - Có thể đã kích hoạt rồi > các oauth
 */
if (!empty($admin_pre_data) and in_array(($opt = $nv_Request->get_title('auth', 'get', '')), $cfg_2step['opts'], true) and ((!$cfg_2step['active_' . $opt] and $cfg_2step['count_active'] < 1) or $cfg_2step['active_' . $opt])) {
    if ($opt == 'code' or $opt == 'key') {
        // Login bằng tài khoản user 1 step để chuyển sang trang kích hoạt
        $checknum = md5(nv_genpass(10));
        $user = [
            'userid' => $admin_pre_data['userid'],
            'current_mode' => 0,
            'checknum' => $checknum,
            'checkhash' => md5($admin_pre_data['userid'] . $checknum . $global_config['sitekey'] . $client_info['clid']),
            'current_agent' => NV_USER_AGENT,
            'prev_agent' => $admin_pre_data['user_last_agent'],
            'current_ip' => NV_CLIENT_IP,
            'prev_ip' => $admin_pre_data['user_last_ip'],
            'current_login' => NV_CURRENTTIME,
            'prev_login' => (int) ($admin_pre_data['user_last_login']),
            'prev_openid' => $admin_pre_data['user_last_openid'],
            'current_openid' => '',
            'language' => $admin_pre_data['language'],
            'admin_prelogin' => true
        ];

        $stmt = $db->prepare('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET
            checknum = :checknum,
            last_login = ' . NV_CURRENTTIME . ",
            last_ip = :last_ip,
            last_agent = :last_agent,
            last_openid = '',
            remember = 1
        WHERE userid=" . $admin_pre_data['userid']);

        $stmt->bindValue(':checknum', $checknum, PDO::PARAM_STR);
        $stmt->bindValue(':last_ip', NV_CLIENT_IP, PDO::PARAM_STR);
        $stmt->bindValue(':last_agent', NV_USER_AGENT, PDO::PARAM_STR);
        $stmt->execute();

        if ($global_config['allowuserloginmulti']) {
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_login WHERE userid=' . $admin_pre_data['userid'] . ' AND clid=' . $db->quote($client_info['clid']));
        } else {
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_login WHERE userid=' . $admin_pre_data['userid']);
        }

        $sth = $db->prepare('INSERT INTO ' . NV_USERS_GLOBALTABLE . '_login (
            userid, clid, logtime, mode, agent, ip, mode_extra
        ) VALUES (
            ' . $admin_pre_data['userid'] . ', :clid, ' . NV_CURRENTTIME . ', 0, :agent, :ip, \'\'
        )');
        $sth->bindValue(':clid', $client_info['clid'], PDO::PARAM_STR);
        $sth->bindValue(':agent', NV_USER_AGENT, PDO::PARAM_STR);
        $sth->bindValue(':ip', NV_CLIENT_IP, PDO::PARAM_STR);
        $sth->execute();

        NukeViet\Core\User::set_userlogin_hash($user, true);

        $tokend_key = md5($admin_pre_data['username'] . '_' . NV_CURRENTTIME . '_users_confirm_pass_' . NV_CHECK_SESSION);
        $tokend = md5('users_confirm_pass_' . NV_CHECK_SESSION);
        $nv_Request->set_Session($tokend_key, $tokend);

        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification&amp;' . NV_OP_VARIABLE . '=setup&amp;nv_redirect=' . nv_redirect_encrypt(NV_BASE_ADMINURL);
        nv_redirect_location($url);
    }

    // Gọi file xử lý chuyển hướng sang google, facebook, zalo để kích hoạt
    $attribs = [];
    define('NV_ADMIN_ACTIVE_2STEP_OAUTH', true);
    require NV_ROOTDIR . '/includes/core/admin_login_' . $opt . '.php';

    // Xử lý trả về
    if (!empty($_GET['code']) and empty($error)) {
        if (empty($attribs)) {
            $error = $nv_Lang->getGlobal('admin_oauth_error_getdata');
        } elseif (!$cfg_2step['active_' . $opt]) {
            // Nếu chưa kích hoạt phương thức này (chưa có gì trong CSDL) thì lưu vào CSDL và xác thực đăng nhập phiên này
            $sql = 'INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . '_oauth (
                admin_id, oauth_server, oauth_uid, oauth_email, oauth_id, addtime
            ) VALUES (
                ' . $admin_pre_data['admin_id'] . ', ' . $db->quote($opt) . ', ' . $db->quote($attribs['full_identity']) . ',
                ' . $db->quote($attribs['email']) . ', ' . $db->quote($attribs['identity']) . ', ' . NV_CURRENTTIME . '
            )';
            if ($db->insert_id($sql, 'id')) {
                $row = $admin_pre_data;
                $admin_login_success = true;
            } else {
                $error = $nv_Lang->getGlobal('admin_oauth_error_savenew');
            }
        } else {
            // Nếu đã kích hoạt rồi thì tìm xem trong CSDL khớp với thông tin xác thực này không!
            $sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_oauth WHERE admin_id=' . $admin_pre_data['admin_id'] . '
            AND oauth_server=' . $db->quote($opt) . ' AND oauth_uid=' . $db->quote($attribs['full_identity']);
            $oauth = $db->query($sql)->fetch();
            if (empty($oauth)) {
                $error = $nv_Lang->getGlobal('admin_oauth_error');
            } else {
                $row = $admin_pre_data;
                $admin_login_success = true;
            }
        }
    }
}

// Login bước 2 bằng mã xác nhận từ ứng dụng
if (!empty($admin_pre_data) and $nv_Request->isset_request('submit2scode', 'post') and $nv_Request->get_title('checkss', 'post') == NV_CHECK_SESSION and $cfg_2step['active_code'] and in_array('code', $cfg_2step['opts'], true)) {
    $nv_totppin = $nv_Request->get_title('nv_totppin', 'post', '');
    $nv_backupcodepin = $nv_Request->get_title('nv_backupcodepin', 'post', '');

    $step2_isvalid = false;
    $GoogleAuthenticator = new \NukeViet\Core\GoogleAuthenticator();

    if (!empty($nv_totppin)) {
        if (!$GoogleAuthenticator->verifyOpt($admin_pre_data['user_2s_secretkey'], $nv_totppin)) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'nv_totppin',
                'mess' => $nv_Lang->getGlobal('2teplogin_error_opt')
            ]);
        }

        $step2_isvalid = true;
    } elseif (!empty($nv_backupcodepin)) {
        $nv_backupcodepin = nv_strtolower($nv_backupcodepin);
        $sth = $db->prepare('SELECT code FROM ' . NV_USERS_GLOBALTABLE . '_backupcodes WHERE is_used=0 AND code=:code AND userid=' . $admin_pre_data['userid']);
        $sth->bindParam(':code', $nv_backupcodepin, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'nv_backupcodepin',
                'mess' => $nv_Lang->getGlobal('2teplogin_error_backup')
            ]);
        }

        $code = $sth->fetchColumn();
        $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . '_backupcodes SET is_used=1, time_used=' . NV_CURRENTTIME . " WHERE code='" . $code . "' AND userid=" . $admin_pre_data['userid']);
        $step2_isvalid = true;
    }

    if ($step2_isvalid) {
        $row = $admin_pre_data;
        $admin_login_success = true;
    }
} else {
    $nv_totppin = $nv_backupcodepin = '';
}

// Login bước 2 bằng passkey
if (!empty($admin_pre_data) and $nv_Request->isset_request('submit2spasskey', 'post') and $nv_Request->get_title('checkss', 'post') == NV_CHECK_SESSION and $cfg_2step['active_key'] and in_array('key', $cfg_2step['opts'], true)) {
    $serializer = SerializerFactory::create();

    $challenge = json_decode($nv_Request->get_string('admin_auth_challenge', 'session', '', false, false), true);
    $nv_Request->unset_request('admin_auth_challenge', 'session');
    if (!is_array($challenge)) {
        $challenge = [];
    }
    if (empty($challenge) or empty($challenge['opts']) or empty($challenge['time']) or time() - $challenge['time'] > 120) {
        nv_jsonOutput([
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
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_challenge1'),
        ]);
    }

    $assertion = $nv_Request->get_string('assertion', 'post', '', false, false);
    if (empty($assertion)) {
        nv_jsonOutput([
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
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential1'),
        ]);
    }
    if (!$publicKeyCredential->response instanceof AuthenticatorAssertionResponse) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_credential2'),
        ]);
    }

    $keyid = base64_encode($publicKeyCredential->rawId);

    $sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_passkey WHERE keyid=' . $db->quote($keyid) . ' AND userid=' . $admin_pre_data['userid'];
    $publickey = $db->query($sql)->fetch();
    if (empty($publickey)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_cannot_auth'),
        ]);
    }

    // Kiểm tra an ninh khóa này
    $publicKeyCredentialSource = PublicKeyCredentialSource::create(
        base64_decode($publickey['keyid']),
        $publickey['type'], [], 'none',
        \Webauthn\TrustPath\EmptyTrustPath::create(),
        \Symfony\Component\Uid\Uuid::fromString($publickey['aaguid']),
        base64_decode($publickey['publickey']),
        $publickey['userhandle'], $publickey['counter']
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
            userHandle: null
        );
    } catch (Throwable $e) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('passkey_error_validator'),
        ]);
    }

    // Cập nhật lại passkey
    $credential = [];
    $credential['id'] = base64_encode($publicKeyCheck->publicKeyCredentialId);
    $credential['publickey'] = base64_encode($publicKeyCheck->credentialPublicKey);
    $credential['userHandle'] = $publicKeyCheck->userHandle;
    $credential['counter'] = $publicKeyCheck->counter;

    $sql = 'UPDATE ' . NV_USERS_GLOBALTABLE . '_passkey SET
        counter=:counter, last_used_at=' . NV_CURRENTTIME . '
    WHERE id=' . $publickey['id'];
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':counter', $credential['counter'], PDO::PARAM_INT);
    $stmt->execute();
    unset($credential, $publickey);

    $row = $admin_pre_data;
    $admin_login_success = true;
}

// Login bước 1
if (empty($admin_pre_data) and $nv_Request->isset_request('nv_login,nv_password', 'post') and $nv_Request->get_title('checkss', 'post') == NV_CHECK_SESSION) {
    $nv_username = $nv_Request->get_title('nv_login', 'post', '', 1);
    $nv_password = $nv_Request->get_title('nv_password', 'post', '');

    unset($nv_seccode);

    if ($captcha_type == 'recaptcha') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    } elseif ($captcha_type == 'turnstile') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng Turnstile
        $nv_seccode = $nv_Request->get_title('cf-turnstile-response', 'post', '');
    } elseif ($captcha_type == 'captcha') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    if (empty($nv_username)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'nv_login',
            'mess' => $nv_Lang->getGlobal('username_empty')
        ]);
    }

    if ($global_config['login_number_tracking'] and $blocker->is_blocklogin($nv_username)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $nv_Lang->getGlobal('userlogin_blocked', $global_config['login_number_tracking'], nv_datetime_format($blocker->login_block_end, 1))
        ]);
    }

    if (empty($nv_password)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'nv_password',
            'mess' => $nv_Lang->getGlobal('password_empty')
        ]);
    }

    // Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
    if ($gfx_chk and isset($nv_seccode) and !nv_capcha_txt($nv_seccode, $captcha_type)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => ($captcha_type == 'recaptcha') ? '' : 'nv_seccode',
            'mess' => ($captcha_type == 'recaptcha') ? $nv_Lang->getGlobal('securitycodeincorrect1') : (($captcha_type == 'turnstile') ? $nv_Lang->getGlobal('securitycodeincorrect2') : $nv_Lang->getGlobal('securitycodeincorrect'))
        ]);
    }

    // Đăng nhập khi kích hoạt diễn đàn
    if (defined('NV_IS_USER_FORUM')) {
        define('NV_IS_MOD_USER', true);
        require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
        if (empty($nv_username)) {
            $nv_username = $nv_Request->get_title('nv_login', 'post', '', 1);
        }
        if (empty($nv_password)) {
            $nv_password = $nv_Request->get_title('nv_password', 'post', '');
        }
    }

    // Kiểm tra đăng nhập bằng email hay username
    $row = false;
    $method = (preg_match('/^([^0-9]+[a-z0-9\_]+)$/', $global_config['login_name_type']) and file_exists(NV_ROOTDIR . '/modules/users/methods/' . $global_config['login_name_type'] . '.php')) ? $global_config['login_name_type'] : 'username';
    require NV_ROOTDIR . '/modules/users/methods/' . $method . '.php';
    $row = check_admin_login($nv_username);
    if (empty($row) or !$crypt->validate_password($nv_password, $row['password'])) {
        // Đăng nhập bước đầu thất bại
        nv_insert_logs(NV_LANG_DATA, 'login', '[' . $nv_username . '] ' . $nv_Lang->getGlobal('loginsubmit') . ' ' . $nv_Lang->getGlobal('fail'), ' Client IP:' . NV_CLIENT_IP, 0);
        $blocker->set_loginFailed($nv_username, NV_CURRENTTIME);

        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $nv_Lang->getGlobal('loginincorrect')
        ]);
    }

    $row['admin_lev'] = (int) ($row['admin_lev']);

    // Kiểm tra quyền đăng nhập (do cấu hình hệ thống quy định)
    if (!defined('ADMIN_LOGIN_MODE')) {
        define('ADMIN_LOGIN_MODE', 3);
    }
    if (ADMIN_LOGIN_MODE == 2 and !in_array($row['admin_lev'], [1, 2], true)) {
        // Điều hành chung + Tối cao được đăng nhập
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $nv_Lang->getGlobal('admin_access_denied2')
        ]);
    }

    if (ADMIN_LOGIN_MODE == 1 and $row['admin_lev'] != 1) {
        // Tối cao được đăng nhập
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $nv_Lang->getGlobal('admin_access_denied1')
        ]);
    }

    /*
     * Đăng nhập bước đầu thành công, kiểm tra xem hệ thống có bắt xác thực hai bước hay không
     * Nếu không thì xem như đã thành công.
     * Nếu có lưu lại thông tin xác thực bước 1 và load lại trang để kiểm tra xử lý tiếp
     */
    // Kiểm tra cấu hình toàn hệ thống
    $_2step_require = in_array((int) $global_config['two_step_verification'], [1, 3], true);
    if (!$_2step_require) {
        // Nếu toàn hệ thống không bắt buộc thì kiểm tra nhóm thành viên
        $manual_groups = [
            3
        ];
        if ($row['admin_lev'] == 1 or $row['admin_lev'] == 2) {
            $manual_groups[] = 2;
        }
        if ($row['admin_lev'] == 1 and $global_config['idsite'] == 0) {
            $manual_groups[] = 1;
        }
        $_2step_require = nv_user_groups($row['in_groups'], true, $manual_groups);
        $_2step_require = $_2step_require[1];
    }

    if ($_2step_require or $row['active2step']) {
        // Ghi nhận thông tin bước 1, lưu lại và chuyển đến bước 2
        nv_insert_logs(NV_LANG_DATA, 'Pre login', '[' . $nv_username . '] ' . $nv_Lang->getGlobal('loginsubmit'), ' Client IP:' . NV_CLIENT_IP, 0);
        $admin_id = (int) ($row['admin_id']);
        $checknum = md5(nv_genpass(10));
        $array_admin = [
            'admin_id' => $admin_id,
            'checknum' => $checknum,
            'current_agent' => NV_USER_AGENT,
            'current_ip' => NV_CLIENT_IP,
            'current_login' => NV_CURRENTTIME
        ];
        $admin_serialize = json_encode($array_admin);

        $sql = 'UPDATE ' . NV_AUTHORS_GLOBALTABLE . ' SET
                    pre_check_num = :check_num,
                    pre_last_login = ' . NV_CURRENTTIME . ',
                    pre_last_ip = :last_ip,
                    pre_last_agent = :last_agent
                WHERE admin_id=' . $admin_id;
        $sth = $db->prepare($sql);
        $sth->bindValue(':check_num', $checknum, PDO::PARAM_STR);
        $sth->bindValue(':last_ip', NV_CLIENT_IP, PDO::PARAM_STR);
        $sth->bindValue(':last_agent', NV_USER_AGENT, PDO::PARAM_STR);
        $sth->execute();

        $nv_Request->set_Session('admin_pre', $admin_serialize);
        $blocker->reset_trackLogin($nv_username);
        nv_jsonOutput([
            'status' => '2step',
            'input' => '',
            'mess' => ''
        ]);
    }
    $admin_login_success = true;
} else {
    if (empty($admin_login_redirect) and strpos($nv_Request->request_uri, 'nocache=') === false) {
        $nv_Request->set_Session('admin_login_redirect', $nv_Request->request_uri);
    }
    $nv_username = $nv_password = '';
}

// Đăng nhập admin hoàn toàn thành công
if ($admin_login_success === true) {
    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $row['username'] . '] ' . $nv_Lang->getGlobal('loginsubmit'), ' Client IP:' . NV_CLIENT_IP, 0);
    $admin_id = (int) ($row['admin_id']);
    $checknum = md5(nv_genpass(10));
    $array_admin = [
        'admin_id' => $admin_id,
        'checknum' => $checknum,
        'current_agent' => NV_USER_AGENT,
        'last_agent' => $row['admin_last_agent'],
        'current_ip' => NV_CLIENT_IP,
        'last_ip' => $row['admin_last_ip'],
        'current_login' => NV_CURRENTTIME,
        'last_login' => (int) ($row['admin_last_login'])
    ];
    $admin_encode = json_encode($array_admin);

    $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . ' SET
        check_num = :check_num, last_login = ' . NV_CURRENTTIME . ',
        last_ip = :last_ip, last_agent = :last_agent
    WHERE admin_id=' . $admin_id);
    $sth->bindValue(':check_num', $checknum, PDO::PARAM_STR);
    $sth->bindValue(':last_ip', NV_CLIENT_IP, PDO::PARAM_STR);
    $sth->bindValue(':last_agent', NV_USER_AGENT, PDO::PARAM_STR);
    $sth->execute();

    $nv_Request->sessionRegenerateId(true);
    $nv_Request->set_Session('admin', $admin_encode);
    $nv_Request->set_Session('online', '1|' . NV_CURRENTTIME . '|' . NV_CURRENTTIME . '|0');
    $nv_Request->set_Cookie('isal', 1, NV_LIVE_COOKIE_TIME, false);

    if ($global_config['lang_multi']) {
        $sql = 'SELECT setup FROM ' . $db_config['prefix'] . '_setup_language WHERE lang=' . $db->quote(NV_LANG_INTERFACE);
        $setup = $db->query($sql)->fetchColumn();
        if ($setup) {
            $nv_Request->set_Cookie(DATA_LANG_COOKIE_NAME, NV_LANG_INTERFACE, NV_LIVE_COOKIE_TIME);
        }
    }

    define('NV_IS_ADMIN', true);
    $nv_Request->unset_request('admin_login_redirect', 'session');
    $nv_Request->unset_request('admin_pre', 'session');

    if ($global_config['admin_user_logout']) {
        NukeViet\Core\User::unset_userlogin_hash();
    }

    if (
        $nv_Request->isset_request('nv_login,nv_password', 'post') or
        $nv_Request->isset_request('submit2scode', 'post') or
        $nv_Request->isset_request('submit2spasskey', 'post') or
        $nv_Request->isset_request('login_assertion', 'post')
    ) {
        nv_jsonOutput([
            'status' => 'success',
            'input' => '',
            'mess' => $nv_Lang->getGlobal('admin_loginsuccessfully'),
            'redirect' => (!empty($admin_login_redirect) and str_starts_with($admin_login_redirect, NV_BASE_ADMINURL)) ? $admin_login_redirect : ''
        ]);
    }

    $redirect = (!empty($admin_login_redirect) and str_starts_with($admin_login_redirect, NV_BASE_ADMINURL)) ? $admin_login_redirect : NV_BASE_SITEURL . NV_ADMINDIR;
    nv_info_die($global_config['site_description'], $nv_Lang->getGlobal('site_info'), $nv_Lang->getGlobal('admin_loginsuccessfully') . " \n <meta http-equiv=\"refresh\" content=\"3;URL=" . $redirect . '" />');
    exit();
}

$method = (preg_match('/^([^0-9]+[a-z0-9\_]+)$/', $global_config['login_name_type']) and file_exists(NV_ROOTDIR . '/modules/users/methods/' . $global_config['login_name_type'] . '.php')) ? $global_config['login_name_type'] : 'username';
if ($nv_Lang->existsGlobal('login_name_type_' . $method)) {
    $nv_Lang->setGlobal('login_name', $nv_Lang->getGlobal('login_name_type_' . $method));
} elseif ($nv_Lang->existsGlobal($method)) {
    $nv_Lang->setGlobal('login_name', $nv_Lang->getGlobal($method));
}

$dir_php = get_tpl_dir($global_config['admin_theme'], NV_DEFAULT_ADMIN_THEME, 'theme_login.php');
$contents = require NV_ROOTDIR . '/themes/' . $dir_php . '/theme_login.php';

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
