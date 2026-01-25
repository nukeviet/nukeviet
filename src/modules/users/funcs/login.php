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

use NukeViet\Module\users\Shared\Emails;
use NukeViet\Webauthn\RequestPasskey;
use NukeViet\Webauthn\SerializerFactory;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialSource;

if (defined('NV_IS_USER') or !$global_config['allowuserlogin']) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$blocker = new NukeViet\Core\Blocker(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs', NV_CLIENT_IP);
$rules = [
    $global_config['login_number_tracking'],
    $global_config['login_time_tracking'],
    $global_config['login_time_ban']
];
$blocker->trackLogin($rules, $global_config['is_login_blocker']);

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

// Dùng để bật giao diện login box
$nv_header = '';
if ($nv_Request->isset_request('nv_header', 'post,get')) {
    $nv_header = $nv_Request->get_title('nv_header', 'post,get', '');
    if ($nv_header != NV_CHECK_SESSION) {
        $nv_header = '';
    }

    if ($nv_Request->isset_request('nv_header', 'get') and !empty($nv_header)) {
        $page_url .= '&nv_header=' . $nv_header;
    }
}

// Chuyển hướng sau khi login
$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();

    if ($nv_Request->isset_request('nv_redirect', 'get') and !empty($nv_redirect)) {
        if (!$nv_Request->isset_request('sso_reset', 'get')) {
            $page_url .= '&nv_redirect=' . $nv_redirect;
        }
        $nv_Request->set_Session('nv_redirect_' . $module_data, $nv_redirect);
    }
} elseif ($nv_Request->isset_request('sso_redirect', 'get')) {
    $sso_redirect = $nv_Request->get_title('sso_redirect', 'get', '');
    if (!empty($sso_redirect)) {
        $nv_Request->set_Session('sso_redirect_' . $module_data, $sso_redirect);
    }
}

if (defined('NV_IS_USER_FORUM') and defined('SSO_SERVER')) {
    // SSO token refresh
    $sso_rcount = $nv_Request->get_absint('sso_rcount', 'get', 0);
    if (isset($_GET['sso_reset'], $_GET['sso_rcount'], $_GET['sso_rtoken']) and hash_equals(md5(NV_CHECK_SESSION . '_sso_reset'), $_GET['sso_rtoken'])) {
        $sso_rcount++;
        if ($sso_rcount > 1) {
            nv_info_die($nv_Lang->getGlobal('error_login_title'), $nv_Lang->getGlobal('error_login_title'), $nv_Lang->getGlobal('error_login_content'), 503);
        }
        require NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/refresh_token.php';
    }

    // SSO login
    $redirect = nv_redirect_decrypt($nv_redirect) ?: (NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    $url = NukeViet\Client\Sso::getLoginUrl($redirect, $sso_rcount);
    $url = nv_apply_hook('', 'modify_sso_login_url', [$url], $url);
    if ($nv_Request->get_int('nv_ajax', 'post', 0) == 1) {
        nv_jsonOutput([
            'sso' => $url
        ]);
    }
    nv_redirect_location($url);
}

if (defined('SSO_CLIENT_DOMAIN')) {
    /** @disregard PHP0415 */
    $allowed_client_origin = explode(',', SSO_CLIENT_DOMAIN);
    $sso_client = $nv_Request->get_title('client', 'get', '');
    if (!empty($sso_client)) {
        if (!in_array($sso_client, $allowed_client_origin, true)) {
            // 406 Not Acceptable
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 406);
        }
        $nv_Request->set_Session('sso_client_' . $module_data, $sso_client);
        // Xử lý nếu client đã đăng nhập rồi mà submit vào đây nữa
        if (defined('NV_IS_USER')) {
            opidr_login([
                'status' => 'success',
                'mess' => $nv_Lang->getModule('login_ok')
            ]);
        }
    }
}

$array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
$gfx_chk = (!empty($array_gfx_chk) and in_array('l', $array_gfx_chk, true)) ? 1 : 0;

/**
 * @param mixed $array
 * @return never
 */
function signin_result($array)
{
    global $nv_redirect, $module_data, $nv_Request;

    $redirect = nv_redirect_decrypt($nv_redirect);
    if (defined('SSO_REGISTER_SECRET')) {
        $sso_client = $nv_Request->get_title('sso_client_' . $module_data, 'session', '');
        $sso_redirect = $nv_Request->get_title('sso_redirect_' . $module_data, 'session', '');
        $sso_redirect = NukeViet\Client\Sso::decrypt($sso_redirect);

        if (!empty($sso_redirect) and !empty($sso_client) and str_starts_with($sso_redirect, $sso_client)) {
            $redirect = $sso_redirect;
        }

        if (in_array(($array['status'] ?? ''), ['success', 'ok']) and !isset($array['requestOptions'])) {
            $nv_Request->unset_request('sso_client_' . $module_data, 'session');
            $nv_Request->unset_request('sso_redirect_' . $module_data, 'session');
        }
    }

    $array['redirect'] = $redirect;
    nv_jsonOutput($array);
}

// Xử lý đăng nhập qua passkey
if ($nv_Request->isset_request('login_with_passkey', 'post')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/login/passkey.php';
}

/**
 * create_username_from_email()
 *
 * @param mixed $email
 * @return string
 */
function create_username_from_email($email)
{
    global $db, $global_config;

    $username = explode('@', $email);
    $username = array_shift($username);

    $username = str_pad($username, $global_config['nv_unickmin'], '0', STR_PAD_RIGHT);
    $username = substr($username, 0, ($global_config['nv_unickmax'] - 2));
    $username2 = $username;
    for ($i = 0; $i < 100; ++$i) {
        if ($i > 0) {
            $username2 = $username . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $query = 'SELECT userid FROM ' . NV_MOD_TABLE . " WHERE md5username='" . nv_md5safe($username2) . "'";
        $userid = $db->query($query)->fetchColumn();
        if (!$userid) {
            $query = 'SELECT userid FROM ' . NV_MOD_TABLE . "_reg WHERE md5username='" . nv_md5safe($username2) . "'";
            $userid = $db->query($query)->fetchColumn();
            if (!$userid) {
                return $username2;
            }
        }
    }

    return '';
}

/**
 * set_reg_attribs()
 *
 * @param mixed  $attribs
 * @param string $username
 */
function set_reg_attribs($attribs, $username)
{
    global $crypt, $global_config, $module_upload;

    $reg_attribs = [];
    $reg_attribs['server'] = $attribs['server'];
    $reg_attribs['email'] = $attribs['contact/email'];
    $reg_attribs['first_name'] = '';
    $reg_attribs['last_name'] = '';
    $reg_attribs['gender'] = '';
    $reg_attribs['photo'] = (!empty($attribs['picture_url']) and empty($attribs['picture_mode'])) ? $attribs['picture_url'] : '';
    $reg_attribs['openid'] = $attribs['id'];
    $reg_attribs['opid'] = $crypt->hash($attribs['id']);

    if (isset($attribs['namePerson/first']) and !empty($attribs['namePerson/first'])) {
        $reg_attribs['first_name'] = $attribs['namePerson/first'];
    } elseif (isset($attribs['namePerson/friendly']) and !empty($attribs['namePerson/friendly'])) {
        $reg_attribs['first_name'] = $attribs['namePerson/friendly'];
    } elseif (isset($attribs['namePerson']) and !empty($attribs['namePerson'])) {
        $reg_attribs['first_name'] = $attribs['namePerson'];
    }

    if (isset($attribs['namePerson/last']) and !empty($attribs['namePerson/last'])) {
        $reg_attribs['last_name'] = $attribs['namePerson/last'];
    }

    if (isset($attribs['person/gender']) and !empty($attribs['person/gender'])) {
        $reg_attribs['gender'] = $attribs['person/gender'];
    }

    if ($global_config['allowuserreg'] == 1 or $global_config['allowuserreg'] == 2) {
        if (!empty($reg_attribs['photo'])) {
            $upload = new NukeViet\Files\Upload([
                'images'
            ], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $upload->setLanguage(\NukeViet\Core\Language::$lang_global);

            $upload_info = $upload->save_urlfile($reg_attribs['photo'], NV_UPLOADS_REAL_DIR . '/' . $module_upload, false);

            if (empty($upload_info['error'])) {
                $basename = change_alias($username) . '.' . nv_getextension($upload_info['basename']);
                $newname = $basename;
                $fullname = $upload_info['name'];

                $i = 1;
                while (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $newname)) {
                    $newname = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $basename);
                    ++$i;
                }

                $check = nv_renamefile($fullname, NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $newname);

                if ($check[0] == 1) {
                    $reg_attribs['photo'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . $newname;
                }
            }
        }
    }

    return $reg_attribs;
}

/**
 * new_openid_user_save()
 *
 * @param mixed $reg_username
 * @param mixed $reg_email
 * @param mixed $reg_password
 * @param mixed $attribs
 * @throws ValueError
 * @throws PDOException
 */
function new_openid_user_save($reg_username, $reg_email, $reg_password, $attribs)
{
    global $db, $nv_Cache, $global_config, $global_users_config, $nv_Lang, $module_name;

    $reg_attribs = set_reg_attribs($attribs, $reg_username);
    $current_mode = $attribs['current_mode'] ?? 1;

    /*
     * Neu dang ky moi va cho dang ky khong can kich hoat hoac kich hoat qua email (allowuserreg = 1, 2)
     */
    if ($global_config['allowuserreg'] == 1 or $global_config['allowuserreg'] == 2) {
        $sql = 'INSERT INTO ' . NV_MOD_TABLE . ' (
                group_id, username, md5username, password, email, first_name, last_name, gender, photo, birthday, regdate,
                question, answer, passlostkey, view_mail, remember, in_groups,
                active, checknum, last_login, last_ip, last_agent, last_openid, idsite, email_verification_time, active_obj
            ) VALUES (
                ' . ($global_users_config['active_group_newusers'] ? 7 : 4) . ',
                :username,
                :md5username,
                :password,
                :email,
                :first_name,
                :last_name,
                :gender,
                :photo,
                0,
                ' . NV_CURRENTTIME . ",
                '', '', '', 0, 0, '" . ($global_users_config['active_group_newusers'] ? '7' : '') . "', 1, '', 0, '', '', '', " . (int) ($global_config['idsite']) . ',
                -1, ' . $db->quote('OAUTH:' . $reg_attribs['server']) . '
            )';

        $data_insert = [];
        $data_insert['username'] = $reg_username;
        $data_insert['md5username'] = nv_md5safe($reg_username);
        $data_insert['password'] = $reg_password;
        $data_insert['email'] = $reg_email;
        $data_insert['first_name'] = $reg_attribs['first_name'];
        $data_insert['last_name'] = $reg_attribs['last_name'];
        $data_insert['gender'] = !empty($reg_attribs['gender']) ? ucfirst(substr($reg_attribs['gender'], 0, 1)) : 'N';
        $data_insert['photo'] = $reg_attribs['photo'];

        $userid = $db->insert_id($sql, 'userid', $data_insert);

        if (!$userid) {
            opidr_login([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('err_no_save_account')
            ]);
        }

        // Cap nhat so thanh vien
        $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=' . ($global_users_config['active_group_newusers'] ? 7 : 4));

        $query = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid . ' AND active=1';
        $result = $db->query($query);
        $row = $result->fetch();
        $result->closeCursor();

        // Luu vao bang thong tin tuy chinh
        $query_field = [];
        $query_field['userid'] = $userid;
        $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY fid ASC');
        while ($row_f = $result_field->fetch()) {
            if ($row_f['is_system'] == 1) {
                continue;
            }
            $query_field[$row_f['field']] = get_value_by_lang($row_f['default_value']);
        }
        userInfoTabDb($query_field);

        // Luu vao bang OpenID
        $user_id = (int) ($row['userid']);
        $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . $user_id . ', :server, :opid , :id, :email)');
        $stmt->bindParam(':server', $reg_attribs['server'], PDO::PARAM_STR);
        $stmt->bindParam(':opid', $reg_attribs['opid'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $reg_attribs['openid'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $reg_attribs['email'], PDO::PARAM_STR);
        $stmt->execute();

        // Callback sau khi đăng ký
        if (nv_function_exists('nv_user_register_callback')) {
            /** @disregard PHP0417 */
            nv_user_register_callback($userid);
        }

        $send_data = [[
            'to' => $reg_email,
            'data' => [
                'first_name' => $data_insert['first_name'],
                'last_name' => $data_insert['last_name'],
                'username' => $data_insert['username'],
                'email' => $reg_email,
                'gender' => $data_insert['gender'],
                'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN),
                'oauth_name' => ucfirst($reg_attribs['server']),
                'lang' => NV_LANG_INTERFACE
            ]
        ]];
        nv_sendmail_template_async([$module_name, Emails::NEW_INFO_OAUTH], $send_data, NV_LANG_INTERFACE);

        if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/set_user_login.php';
        } else {
            validUserLog($row, 1, [
                'id' => $reg_attribs['opid'],
                'provider' => $reg_attribs['server']
            ], $current_mode);

            opidr_login([
                'status' => 'success',
                'mess' => $nv_Lang->getModule('login_ok')
            ]);
        }
    }

    /*
     * Neu dang ky moi + phai qua kiem duyet cua admin (allowuserreg = 3)
     */
    if ($global_config['allowuserreg'] == 3) {
        $query_field = [];
        $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY fid ASC');
        while ($row_f = $result_field->fetch()) {
            $query_field[$row_f['field']] = get_value_by_lang($row_f['default_value']);
        }

        $sql = 'INSERT INTO ' . NV_MOD_TABLE . '_reg (
                username, md5username, password, email, first_name, last_name, birthday, regdate, question, answer, checknum, users_info, openid_info
            ) VALUES (
                :username,
                :md5username,
                :password,
                :email,
                :first_name,
                :last_name,
                0,
                ' . NV_CURRENTTIME . ",
                '',
                '',
                '',
                :users_info,
                :openid_info
            )";

        $data_insert = [];
        $data_insert['username'] = $reg_username;
        $data_insert['md5username'] = nv_md5safe($reg_username);
        $data_insert['password'] = $reg_password;
        $data_insert['email'] = $reg_email;
        $data_insert['first_name'] = $reg_attribs['first_name'];
        $data_insert['last_name'] = $reg_attribs['last_name'];
        $data_insert['users_info'] = json_encode($query_field, JSON_UNESCAPED_UNICODE);
        $data_insert['openid_info'] = json_encode($reg_attribs, JSON_UNESCAPED_UNICODE);
        $userid = $db->insert_id($sql, 'userid', $data_insert);

        if (!$userid) {
            opidr_login([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('err_no_save_account')
            ]);
        }

        $nv_Cache->delMod($module_name);

        opidr_login([
            'status' => 'success',
            'mess' => $nv_Lang->getModule('account_register_to_admin')
        ]);
    }
}

// Đăng nhập qua Oauth
if (defined('NV_OPENID_ALLOWED') and $nv_Request->isset_request('server', 'get')) {
    $server = $nv_Request->get_string('server', 'get', '');
    $result = $nv_Request->isset_request('result', 'get');

    if (empty($server) or !in_array($server, $global_config['openid_servers'], true) or !$result) {
        nv_redirect_location(NV_BASE_SITEURL);
    }

    $attribs = $nv_Request->get_string('openid_attribs', 'session', '');
    $attribs = !empty($attribs) ? json_decode($attribs, true) : [];
    if (!is_array($attribs)) {
        $attribs = [];
    }
    $attribs = nv_apply_hook($module_name, 'custom_login_openid_attribs', [$server, $attribs], $attribs);

    if (empty($attribs) or $attribs['server'] != $server) {
        opidr_login([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('logged_in_failed')
        ]);
    }

    if ($attribs['result'] == 'cancel') {
        opidr_login([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('canceled_authentication')
        ]);
    }

    if ($attribs['result'] == 'notlogin') {
        opidr_login([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('not_logged_in')
        ]);
    }

    $email = $attribs['contact/email'] ?? '';
    if (!empty($email)) {
        $check_email = nv_check_valid_email($email, true);
        $email = $check_email[1];
        if (!empty($check_email[0])) {
            opidr_login([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('logged_no_email')
            ]);
        }
    }
    $opid = $crypt->hash($attribs['id']);
    $current_mode = $attribs['current_mode'] ?? 1;

    $page_title = $nv_Lang->getGlobal('openid_login') . ' ' . ucwords(str_replace('-', ' ', $server));

    /**
     * Oauth này đã có trong CSDL
     */
    $stmt = $db->prepare('SELECT a.userid AS uid, b.email AS uemail, b.active AS uactive, b.safemode AS safemode
    FROM ' . NV_MOD_TABLE . '_openid a
    INNER JOIN ' . NV_MOD_TABLE . ' b ON a.userid=b.userid
    WHERE a.openid=:openid AND a.opid= :opid');
    $stmt->bindParam(':openid', $server, PDO::PARAM_STR);
    $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
    $stmt->execute();

    [$user_id, $op_email, $user_active, $safemode] = $stmt->fetch(3);

    if ($user_id) {
        if ($safemode == 1) {
            opidr_login([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('safe_deactivate_openidlogin')
            ]);
        }

        if (!$user_active) {
            opidr_login([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('login_no_active')
            ]);
        }

        if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/set_user_login.php';
        } else {
            $query = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $user_id;
            $row = $db->query($query)->fetch();
            validUserLog($row, 1, [
                'id' => $opid,
                'provider' => $attribs['server']
            ], $current_mode);
        }

        opidr_login([
            'status' => 'success',
            'mess' => $nv_Lang->getModule('login_ok')
        ]);
    }

    if (!empty($email)) {
        /**
         * Oauth này chưa có nhưng email đã được sử dụng
         */
        $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . ' WHERE email= :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $nv_row = $stmt->fetch();

        if (!empty($nv_row)) {
            if ($nv_row['safemode'] == 1) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('safe_deactivate_openidreg')
                ]);
            }

            if (!$nv_row['active']) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('login_no_active')
                ]);
            }

            /*
             * Nếu tài khoản trùng email này có mật khẩu và chức năng tự động gán Oauh bị tắt
             * thì yêu cầu nhập mật khẩu xác nhận
             */
            if (!empty($nv_row['password']) and empty($global_users_config['auto_assign_oauthuser'])) {
                if ($nv_Request->isset_request('openid_account_confirm', 'post')) {
                    $password = $nv_Request->get_string('password', 'post', '');

                    unset($nv_seccode);
                    if ($module_captcha == 'recaptcha') {
                        // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
                        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
                    } elseif ($module_captcha == 'turnstile') {
                        // Xác định giá trị của captcha nhập vào nếu sử dụng Turnstile
                        $nv_seccode = $nv_Request->get_title('cf-turnstile-response', 'post', '');
                    } elseif ($module_captcha == 'captcha') {
                        // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
                        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
                    }

                    // Kiểm tra tính hợp lệ của captcha nhập vào
                    $check_seccode = ($gfx_chk and isset($nv_seccode)) ? nv_capcha_txt($nv_seccode, $module_captcha) : true;

                    $nv_Request->unset_request('openid_attribs', 'session');
                    if (defined('NV_IS_USER_FORUM') and file_exists(NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php')) {
                        $nv_username = $nv_row['username'];
                        $nv_password = $password;
                        $error = '';
                        require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
                        if (!empty($error)) {
                            opidr_login([
                                'status' => 'error',
                                'mess' => $nv_Lang->getModule('openid_confirm_failed')
                            ]);
                        }
                    } elseif (!$check_seccode) {
                        opidr_login([
                            'status' => 'error',
                            'mess' => ($module_captcha == 'recaptcha') ? $nv_Lang->getGlobal('securitycodeincorrect1') : (($module_captcha == 'turnstile') ? $nv_Lang->getGlobal('securitycodeincorrect2') : $nv_Lang->getGlobal('securitycodeincorrect'))
                        ]);
                    } elseif (!$crypt->validate_password($password, $nv_row['password'])) {
                        opidr_login([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('openid_confirm_failed')
                        ]);
                    }
                } else {
                    $key_words = $module_info['keywords'];

                    unset($nv_row['password']);

                    $contents = openid_account_confirm($gfx_chk, $attribs, $nv_row);

                    include NV_ROOTDIR . '/includes/header.php';
                    echo nv_site_theme($contents, false);
                    include NV_ROOTDIR . '/includes/footer.php';
                }
            }

            $user_id = (int) $nv_row['userid'];
            $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . $user_id . ', :server, :opid, :id, :email )');
            $stmt->bindParam(':server', $attribs['server'], PDO::PARAM_STR);
            $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
            $stmt->bindParam(':id', $attribs['id'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
                require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/set_user_login.php';
            } else {
                validUserLog($nv_row, 1, [
                    'id' => $opid,
                    'provider' => $attribs['server']
                ], $current_mode);

                opidr_login([
                    'status' => 'success',
                    'mess' => $nv_Lang->getModule('login_ok')
                ]);
            }
        }
    }

    /*
     * Neu chua co hoan toan trong CSDL
     */
    $op_process = !empty($global_config['openid_processing']) ? array_flip(array_map('trim', explode(',', $global_config['openid_processing']))) : [];
    if (empty($email)) {
        unset($op_process['auto']);
        !isset($op_process['create']) && $op_process['create'] = 1;
    }
    if (empty($global_config['allowuserreg'])) {
        unset($op_process['auto'], $op_process['create']);
    }
    $op_process_count = count($op_process);

    // Neu khong co hanh dong gi duoc xac dinh
    if (empty($op_process)) {
        opidr_login([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('openid_not_found')
        ]);
    }

    // Neu he thong tu dong tao tai khoan moi va gan Oauth nay vao
    if (isset($op_process['auto']) and ($op_process_count == 1 or $nv_Request->isset_request('nv_auto', 'post'))) {
        $reg_username = create_username_from_email($email);
        new_openid_user_save($reg_username, $email, '', $attribs);
    }

    /*
     * Neu gan OpenID nay vao 1 tai khoan da co
     */
    if (isset($op_process['connect']) and $nv_Request->isset_request('nv_login', 'post')) {
        $nv_username = $nv_Request->get_title('login', 'post', '', 1);
        $nv_password = $nv_Request->get_title('password', 'post', '');

        if (empty($nv_username)) {
            opidr_login([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('username_empty')
            ]);
        }

        if (empty($nv_password)) {
            opidr_login([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('password_empty')
            ]);
        }

        if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
            $error = '';
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
            if (!empty($error)) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $error
                ]);
            }
        } else {
            $row = false;
            $method = (preg_match('/^([^0-9]+[a-z0-9\_]+)$/', $global_config['login_name_type']) and module_file_exists('users/methods/' . $global_config['login_name_type'] . '.php')) ? $global_config['login_name_type'] : 'username';
            require NV_ROOTDIR . '/modules/users/methods/' . $method . '.php';
            $row = check_user_login($nv_username);

            if (empty($row) or !$crypt->validate_password($nv_password, $row['password'])) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $nv_Lang->getGlobal('loginincorrect')
                ]);
            }

            if ($row['safemode'] == 1) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('safe_deactivate_openidreg')
                ]);
            }

            if (!$row['active']) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $nv_Lang->getGlobal('login_no_active')
                ]);
            }

            validUserLog($row, 1);
        }

        $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . (int) $row['userid'] . ', :server, :opid, :id, :email )');
        $stmt->bindParam(':server', $attribs['server'], PDO::PARAM_STR);
        $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
        $stmt->bindParam(':id', $attribs['id'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        opidr_login([
            'status' => 'success',
            'mess' => $nv_Lang->getModule('login_ok')
        ]);
    }

    /*
     * Gui ma xac minh email do nguoi dung khai bao
     */
    if ($global_config['allowuserreg'] != 0 and $nv_Request->isset_request('verify_send, reg_email', 'post')) {
        $reg_email = nv_strtolower(nv_substr($nv_Request->get_title('reg_email', 'post', '', 1), 0, 100));
        $check_reg_email = nv_check_email_reg($reg_email);
        if (!empty($check_reg_email)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $check_reg_email
            ]);
        }

        $md5_reg_email = md5($reg_email);
        $sess_verify = $nv_Request->get_string($md5_reg_email, 'session', '');
        if (!empty($sess_verify)) {
            $sess_verify = json_decode($sess_verify, true);
            if (!is_array($sess_verify)) {
                $sess_verify = [];
            }
            !isset($sess_verify['code']) && $sess_verify['code'] = '';
        } else {
            $sess_verify = ['code' => '', 'time' => 0];
        }

        if (!empty($sess_verify['code']) and ($pas = 300 - NV_CURRENTTIME + (int) $sess_verify['time']) > 0) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('verifykey_issend', ceil($pas / 60))
            ]);
        }

        $verikey = nv_genpass(8);
        $sess_verify = json_encode(['code' => md5($verikey), 'time' => NV_CURRENTTIME]);
        $nv_Request->set_Session($md5_reg_email, $sess_verify);

        $send_data = [[
            'to' => $reg_email,
            'data' => [
                'email' => $reg_email,
                'lang' => NV_LANG_INTERFACE,
                'code' => $verikey
            ]
        ]];
        $send = nv_sendmail_from_template([$module_name, Emails::OAUTH_VERIFY_EMAIL], $send_data, NV_LANG_INTERFACE);
        if (!$send) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('verify_email_sent_error')
            ]);
        }

        nv_jsonOutput([
            'status' => 'ok',
            'mess' => $nv_Lang->getModule('verify_email_sent')
        ]);
    }

    /*
     * Neu dang ky moi
     */
    if (isset($op_process['create']) and $nv_Request->isset_request('nv_reg', 'post')) {
        $reg_username = $nv_Request->get_title('reg_username', 'post', '', 1);
        if (($check_reg_username = nv_check_username_reg($reg_username)) != '') {
            opidr_login([
                'status' => 'error',
                'mess' => $check_reg_username
            ]);
        }

        if (empty($email)) {
            $reg_email = nv_strtolower(nv_substr($nv_Request->get_title('reg_email', 'post', '', 1), 0, 100));
            $check_reg_email = nv_check_email_reg($reg_email);
            if (!empty($check_reg_email)) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $check_reg_email
                ]);
            }

            $verify_code = $nv_Request->get_title('verify_code', 'post', '');
            $sess_verify = $nv_Request->get_string(md5($reg_email), 'session', '');
            if (!empty($sess_verify)) {
                $sess_verify = json_decode($sess_verify, true);
                if (!is_array($sess_verify)) {
                    $sess_verify = [];
                }
                !isset($sess_verify['code']) && $sess_verify['code'] = '';
            } else {
                $sess_verify = ['code' => '', 'time' => 0];
            }

            if (empty($verify_code) or md5($verify_code) != $sess_verify['code']) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('verify_mail_error')
                ]);
            }

            $nv_Request->unset_request('sess_verify_code', 'session');
        } else {
            $reg_email = $email;
        }

        $reg_password = $nv_Request->get_title('reg_password', 'post', '');
        $reg_repassword = $nv_Request->get_title('reg_repassword', 'post', '');

        if (($reg_check_pass = nv_check_valid_pass($reg_password, $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
            opidr_login([
                'status' => 'error',
                'mess' => $reg_check_pass
            ]);
        }

        if ($reg_password != $reg_repassword) {
            opidr_login([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('passwordsincorrect')
            ]);
        }

        $reg_password = $crypt->hash_password($reg_password, $global_config['hashprefix']);

        new_openid_user_save($reg_username, $reg_email, $reg_password, $attribs);
    }

    $key_words = $module_info['keywords'];

    $contents .= user_openid_login($attribs, $op_process);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Dang nhap kieu thong thuong
if ($nv_Request->isset_request('_csrf, nv_login', 'post')) {
    $checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op);
    $csrf = $nv_Request->get_title('_csrf', 'post', '');
    if (!hash_equals($checkss, $csrf)) {
        exit('Stop!!!');
    }

    $nv_username = nv_substr($nv_Request->get_title('nv_login', 'post', '', 1), 0, 100);
    $nv_password = $nv_Request->get_title('nv_password', 'post', '');
    $nv_totppin = $nv_Request->get_title('nv_totppin', 'post', '');
    $nv_backupcodepin = $nv_Request->get_title('nv_backupcodepin', 'post', '');
    $cant_do_2step = $nv_Request->get_bool('cant_do_2step', 'post', false);
    $auth_assertion = $nv_Request->get_string('auth_assertion', 'post', '', false, false);
    $create_challenge = (int) $nv_Request->get_bool('create_challenge', 'post', false);

    // Kiểm tra captcha nếu đăng nhập bằng mật khẩu hoặc chưa đăng nhập thành công
    if (
        empty($nv_username) or $nv_Request->get_title('users_dismiss_captcha', 'session', '') != md5($nv_username) or (
        empty($nv_totppin) and empty($nv_backupcodepin) and empty($auth_assertion) and empty($create_challenge)
        )
    ) {
        unset($nv_seccode);
        if ($module_captcha == 'recaptcha') {
            // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
            $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
        } elseif ($module_captcha == 'turnstile') {
            // Xác định giá trị của captcha nhập vào nếu sử dụng Turnstile
            $nv_seccode = $nv_Request->get_title('cf-turnstile-response', 'post', '');
        } elseif ($module_captcha == 'captcha') {
            // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
            $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
        }

        // Kiểm tra tính hợp lệ của captcha nhập vào
        $check_seccode = ($gfx_chk and isset($nv_seccode)) ? nv_capcha_txt($nv_seccode, $module_captcha) : true;

        if (!$check_seccode) {
            signin_result([
                'status' => 'error',
                'input' => '',
                'mess' => ($module_captcha == 'recaptcha') ? $nv_Lang->getGlobal('securitycodeincorrect1') : (($module_captcha == 'turnstile') ? $nv_Lang->getGlobal('securitycodeincorrect2') : $nv_Lang->getGlobal('securitycodeincorrect'))
            ]);
        }
    }

    if (empty($nv_username)) {
        signin_result([
            'status' => 'error',
            'input' => 'nv_login',
            'mess' => $nv_Lang->getGlobal('username_empty')
        ]);
    }

    if ($global_config['login_number_tracking'] and $blocker->is_blocklogin($nv_username)) {
        signin_result([
            'status' => 'error',
            'input' => '',
            'mess' => $nv_Lang->getGlobal('userlogin_blocked', $global_config['login_number_tracking'], nv_datetime_format($blocker->login_block_end, 1))
        ]);
    }

    if (empty($nv_password)) {
        signin_result([
            'status' => 'error',
            'input' => 'nv_password',
            'mess' => $nv_Lang->getGlobal('password_empty')
        ]);
    }

    // Nếu đăng nhập bằng tài khoản kết nối
    if (defined('NV_IS_USER_FORUM')) {
        $error = '';
        require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
        if (!empty($error)) {
            signin_result([
                'status' => 'error',
                'input' => 'nv_login',
                'mess' => $error
            ]);
        }

        signin_result([
            'status' => 'ok',
            'input' => '',
            'mess' => $nv_Lang->getModule('login_ok')
        ]);
    }

    // Đăng nhập qua hệ thống nukeviet
    $row = false;
    $method = (preg_match('/^([^0-9]+[a-z0-9\_]+)$/', $global_config['login_name_type']) and module_file_exists('users/methods/' . $global_config['login_name_type'] . '.php')) ? $global_config['login_name_type'] : 'username';
    require NV_ROOTDIR . '/modules/users/methods/' . $method . '.php';
    $row = check_user_login($nv_username);
    $row_reg = empty($row) ? check_user_login($nv_username, true) : false;

    // Nếu tài khoản đang chờ kích hoạt
    if (!empty($row_reg) and $crypt->validate_password($nv_password, $row_reg['password'])) {
        $nv_Request->set_Session($module_data . '_preactivation_verified', json_encode([
            'username' => $row_reg['username'],
            'email' => $row_reg['email'],
            'time' => NV_CURRENTTIME,
        ]));
        signin_result([
            'status' => 'activation',
            'input' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=lostactivelink&autosubmit=1' . (!empty($nv_redirect) ? '&nv_redirect=' . $nv_redirect : ''), true),
            'mess' => $nv_Lang->getModule('account_waiting_activation')
        ]);
    }

    // Nếu không tìm thấy tài khoản hoặc mật khẩu không khớp
    if (empty($row) or !$crypt->validate_password($nv_password, $row['password'])) {
        if ($global_config['login_number_tracking']) {
            $blocker->set_loginFailed($nv_username, NV_CURRENTTIME);
        }
        signin_result([
            'status' => 'error',
            'input' => '',
            'mess' => $nv_Lang->getGlobal('loginincorrect')
        ]);
    }

    // Nếu tài khoản bị đình chỉ
    if (empty($row['active'])) {
        signin_result([
            'status' => 'error',
            'input' => '',
            'mess' => $nv_Lang->getModule('login_no_active')
        ]);
    }

    // Nếu cần đăng nhập 2 bước
    if (empty($row['active2step'])) {
        // Hacking detected
        if (!empty($nv_totppin) or !empty($nv_backupcodepin) or !empty($auth_assertion) or !empty($create_challenge) or $cant_do_2step) {
            signin_result([
                'status' => 'error',
                'input' => '',
                'mess' => 'Stop!!!'
            ]);
        }
    } else {
        // Nếu có xác nhận không thể xác thực 2 bước
        if ($cant_do_2step and !empty($global_config['remove_2step_allow'])) {
            $nv_Request->set_Session('cant_do_2step', $row['userid'] . '.' . NV_CURRENTTIME . '.0.');
            signin_result([
                'status' => 'remove2step',
                'input' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=r2s' . (!empty($nv_redirect) ? '&nv_redirect=' . $nv_redirect : ''), true),
                'mess' => $nv_Lang->getGlobal('remove2step_info')
            ]);
        }

        // Tạo thử thách xác thực 2 bước bằng khóa truy cập
        if ($create_challenge and !empty($row['sec_keys'])) {
            // Lấy các khóa được phép
            $allowCredentials = [];
            $sql = 'SELECT keyid, type FROM ' . NV_MOD_TABLE . '_passkey WHERE userid=' . $row['userid'];
            $result = $db->query($sql);
            while ($credential = $result->fetch()) {
                $allowCredentials[] = PublicKeyCredentialDescriptor::create($credential['type'], base64_decode($credential['keyid']));
            }
            $result->closeCursor();

            $jsonObject = RequestPasskey::create(false, $allowCredentials);
            $nv_Request->set_Session($module_data . '_auth_challenge', json_encode([
                'opts' => $jsonObject,
                'time' => time(),
            ]));
            signin_result([
                'status' => 'ok',
                'requestOptions' => $jsonObject,
            ]);
        }

        // Nếu cả mã từ app và mã dự phòng không được xác định
        if (empty($nv_totppin) and empty($nv_backupcodepin) and empty($auth_assertion)) {
            $nv_Request->set_Session('users_dismiss_captcha', md5($nv_username));
            signin_result([
                'status' => '2step',
                'input' => '',
                'mess' => '',
                'method_code' => 1,
                'method_key' => !empty($row['sec_keys']) ? 1 : 0,
                'pref_method' => (!empty($row['sec_keys']) and in_array((int) $row['pref_2fa'], [2, 0])) ? 'key' : 'app',
                'tfa_recovery' => !empty($global_config['remove_2step_allow']) ? 1 : 0
            ]);
        }

        // Nếu mã từ app nhập vào không chính xác
        $GoogleAuthenticator = new \NukeViet\Core\GoogleAuthenticator();
        if (!empty($nv_totppin) and !$GoogleAuthenticator->verifyOpt($row['secretkey'], $nv_totppin)) {
            signin_result([
                'status' => 'error',
                'input' => 'nv_totppin',
                'mess' => $nv_Lang->getGlobal('2teplogin_error_opt')
            ]);
        }

        if (!empty($nv_backupcodepin)) {
            $nv_backupcodepin = nv_strtolower($nv_backupcodepin);
            $sth = $db->prepare('SELECT code FROM ' . NV_MOD_TABLE . '_backupcodes WHERE is_used=0 AND code=:code AND userid=' . $row['userid']);
            $sth->bindParam(':code', $nv_backupcodepin, PDO::PARAM_STR);
            $sth->execute();

            // Nếu không tìm thấy trong CSDL mã dự phòng
            if ($sth->rowCount() != 1) {
                signin_result([
                    'status' => 'error',
                    'input' => 'nv_backupcodepin',
                    'mess' => $nv_Lang->getGlobal('2teplogin_error_backup')
                ]);
            }

            // Nếu mã dự phòng khớp thì đánh dấu trong CSDL là mã này đã được sử dụng
            $code = $sth->fetchColumn();
            $db->query('UPDATE ' . NV_MOD_TABLE . '_backupcodes SET is_used=1, time_used=' . NV_CURRENTTIME . " WHERE code='" . $code . "' AND userid=" . $row['userid']);
        }

        // Kiểm tra passkey
        if (!empty($auth_assertion)) {
            $serializer = SerializerFactory::create();

            $challenge = json_decode($nv_Request->get_string($module_data . '_auth_challenge', 'session', '', false, false), true);
            $nv_Request->unset_request($module_data . '_auth_challenge', 'session');
            if (!is_array($challenge)) {
                $challenge = [];
            }
            if (empty($challenge) or empty($challenge['opts']) or empty($challenge['time']) or time() - $challenge['time'] > 120) {
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

            try {
                $publicKeyCredential = $serializer->deserialize(
                    $auth_assertion,
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

            $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_passkey WHERE keyid=' . $db->quote($keyid) . ' AND userid=' . $row['userid'];
            $publickey = $db->query($sql)->fetch();
            if (empty($publickey)) {
                signin_result([
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
                signin_result([
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

            $sql = 'UPDATE ' . NV_MOD_TABLE . '_passkey SET
                counter=:counter, last_used_at=' . NV_CURRENTTIME . '
            WHERE id=' . $publickey['id'];
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':counter', $credential['counter'], PDO::PARAM_INT);
            $stmt->execute();
            unset($credential, $publickey);
        }
    }

    $blocker->reset_trackLogin($nv_username);

    // Xác nhận đăng nhập thành công
    if (defined('SSO_SERVER')) {
        define('NV_SET_LOGIN_MODE', 'NORMALLY');
        require NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/set_user_login.php';
        if (!empty($error)) {
            signin_result([
                'status' => 'error',
                'mess' => $error
            ]);
        }
    } else {
        validUserLog($row, 1);
    }

    $nv_Request->unset_request('users_dismiss_captcha', 'session');

    // Nếu tài khoản không xác thực 2 bước, nhưng hệ thống hoặc nhóm bắt buộc phải xác thực 2 bước
    if (empty($row['active2step'])) {
        $_2step_require = in_array((int) $global_config['two_step_verification'], [2, 3], true);
        if (!$_2step_require) {
            [, $_2step_require] = nv_user_groups($row['in_groups'], true);
        }
        if ($_2step_require) {
            if (empty($nv_redirect)) {
                $nv_redirect = nv_redirect_encrypt($page_url);
            }
            signin_result([
                'status' => '2steprequire',
                'input' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . NV_2STEP_VERIFICATION_MODULE . '&' . NV_OP_VARIABLE . '=setup&nv_redirect=' . $nv_redirect, true),
                'mess' => $nv_Lang->getGlobal('2teplogin_require')
            ]);
        }
    }

    // Trả kết quả OK
    signin_result([
        'status' => 'ok',
        'input' => '',
        'mess' => $nv_Lang->getModule('login_ok')
    ]);
}

$nv_Request->unset_request('users_dismiss_captcha', 'session');

// Login popup
if ($nv_Request->get_int('nv_ajax', 'post', 0) == 1) {
    nv_jsonOutput([
        'html' => nv_change_buffer(nv_url_rewrite(user_login(true)))
    ]);
}

$page_title = $nv_Lang->getModule('login');
$key_words = $module_info['keywords'];

$canonicalUrl = getCanonicalUrl($page_url);

$contents = user_login();

$full = empty($nv_header);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, $full);
include NV_ROOTDIR . '/includes/footer.php';
