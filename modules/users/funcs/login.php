<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

if (defined('NV_IS_USER') or !$global_config['allowuserlogin']) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

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
        $page_url .= '&nv_redirect=' . $nv_redirect;
    }
}

$array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
$gfx_chk = (!empty($array_gfx_chk) and in_array('l', $array_gfx_chk, true)) ? 1 : 0;

/**
 * login_result()
 *
 * @param mixed $array
 * @return
 */
function signin_result($array)
{
    global $nv_redirect;

    $array['redirect'] = nv_redirect_decrypt($nv_redirect);
    nv_jsonOutput($array);
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
                break;
            }
        }
    }

    return '';
}

/**
 * set_reg_attribs()
 *
 * @param mixed $attribs
 * @param string $username
 * @return
 */
function set_reg_attribs($attribs, $username)
{
    global $crypt, $global_config, $module_upload, $lang_global;

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
            $upload->setLanguage($lang_global);

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
    global $db, $nv_Cache, $global_config, $global_users_config, $lang_module, $module_name;

    $reg_attribs = set_reg_attribs($attribs, $reg_username);
    $current_mode = isset($attribs['current_mode']) ? $attribs['current_mode'] : 1;

    /*
    * Neu dang ky moi va cho dang ky khong can kich hoat hoac kich hoat qua email (allowuserreg = 1, 2)
    */
    if ($global_config['allowuserreg'] == 1 or $global_config['allowuserreg'] == 2) {
        $sql = 'INSERT INTO ' . NV_MOD_TABLE . ' (
                group_id, username, md5username, password, email, first_name, last_name, gender, photo, birthday, regdate,
                question, answer, passlostkey, view_mail, remember, in_groups,
                active, checknum, last_login, last_ip, last_agent, last_openid, idsite, email_verification_time, active_obj
            ) VALUES (
                ' . ($global_users_config['active_group_newusers'] ? 7 : 4) . ",
                :username,
                :md5username,
                :password,
                :email,
                :first_name,
                :last_name,
                :gender,
                :photo,
                0,
                " . NV_CURRENTTIME . ",
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
                'mess' => $lang_module['err_no_save_account']
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
            $query_field[$row_f['field']] = $db->quote($row_f['default_value']);
        }
        $db->query('INSERT INTO ' . NV_MOD_TABLE . '_info (' . implode(', ', array_keys($query_field)) . ') VALUES (' . implode(', ', array_values($query_field)) . ')');

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
            nv_user_register_callback($userid);
        }

        $subject = $lang_module['account_register'];
        $_url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true);
        $message = sprintf($lang_module['account_register_openid_info'], $reg_attribs['first_name'], $global_config['site_name'], $_url, ucfirst($reg_attribs['server']));
        nv_sendmail([
            $global_config['site_name'],
            $global_config['site_email']
        ], $reg_email, $subject, $message);

        $nv_Cache->delMod($module_name);

        if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/set_user_login.php';
        } else {
            validUserLog($row, 1, [
                'id' => $reg_attribs['opid'],
                'provider' => $reg_attribs['server']
            ], $current_mode);

            opidr_login([
                'status' => 'success',
                'mess' => $lang_module['login_ok']
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
            $query_field[$row_f['field']] = $db->quote($row_f['default_value']);
        }

        $sql = 'INSERT INTO ' . NV_MOD_TABLE . "_reg (
                username, md5username, password, email, first_name, last_name, birthday, regdate, question, answer, checknum, users_info, openid_info
            ) VALUES (
                :username,
                :md5username,
                :password,
                :email,
                :first_name,
                :last_name,
                0,
                " . NV_CURRENTTIME . ",
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
        $data_insert['users_info'] = nv_base64_encode(serialize($query_field));
        $data_insert['openid_info'] = nv_base64_encode(serialize($reg_attribs));
        $userid = $db->insert_id($sql, 'userid', $data_insert);

        if (!$userid) {
            opidr_login([
                'status' => 'error',
                'mess' => $lang_module['err_no_save_account']
            ]);
        }

        $nv_Cache->delMod($module_name);

        opidr_login([
            'status' => 'success',
            'mess' => $lang_module['account_register_to_admin']
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
    $attribs = !empty($attribs) ? unserialize($attribs) : [];

    if (empty($attribs) or $attribs['server'] != $server) {
        opidr_login([
            'status' => 'error',
            'mess' => $lang_module['logged_in_failed']
        ]);
    }

    if ($attribs['result'] == 'cancel') {
        opidr_login([
            'status' => 'error',
            'mess' => $lang_module['canceled_authentication']
        ]);
    }

    if ($attribs['result'] == 'notlogin') {
        opidr_login([
            'status' => 'error',
            'mess' => $lang_module['not_logged_in']
        ]);
    }

    $email = isset($attribs['contact/email']) ? $attribs['contact/email'] : '';
    if (!empty($email)) {
        $check_email = nv_check_valid_email($email, true);
        $email = $check_email[1];
        if (!empty($check_email[0])) {
            opidr_login([
                'status' => 'error',
                'mess' => $lang_module['logged_no_email']
            ]);
        }
    }
    $opid = $crypt->hash($attribs['id']);
    $current_mode = isset($attribs['current_mode']) ? $attribs['current_mode'] : 1;

    /**
     * Oauth này đã có trong CSDL
     */
    $stmt = $db->prepare('SELECT a.userid AS uid, b.email AS uemail, b.active AS uactive, b.safemode AS safemode
    FROM ' . NV_MOD_TABLE . '_openid a, ' . NV_MOD_TABLE . ' b
    WHERE a.opid= :opid
    AND a.userid=b.userid');
    $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
    $stmt->execute();

    list($user_id, $op_email, $user_active, $safemode) = $stmt->fetch(3);

    if ($user_id) {
        if ($safemode == 1) {
            opidr_login([
                'status' => 'error',
                'mess' => $lang_module['safe_deactivate_openidlogin']
            ]);
        }

        if (!$user_active) {
            opidr_login([
                'status' => 'error',
                'mess' => $lang_module['login_no_active']
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
            'mess' => $lang_module['login_ok']
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
                    'mess' => $lang_module['safe_deactivate_openidreg']
                ]);
            }

            if (!$nv_row['active']) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $lang_module['login_no_active']
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
                    // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
                    if ($module_captcha == 'recaptcha') {
                        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
                    }
                    // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
                    elseif ($module_captcha == 'captcha') {
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
                                'mess' => $lang_module['openid_confirm_failed']
                            ]);
                        }
                    } elseif (!$check_seccode) {
                        opidr_login([
                            'status' => 'error',
                            'mess' => ($global_config['ucaptcha_type'] == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect']
                        ]);
                    } elseif (!$crypt->validate_password($password, $nv_row['password'])) {
                        opidr_login([
                            'status' => 'error',
                            'mess' => $lang_module['openid_confirm_failed']
                        ]);
                    }
                } else {
                    $page_title = $lang_global['openid_login'];
                    $key_words = $module_info['keywords'];
                    $mod_title = $lang_global['openid_login'];

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
                    'mess' => $lang_module['login_ok']
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
            'mess' => $lang_module['openid_not_found']
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
                'mess' => $lang_global['username_empty']
            ]);
        }

        if (empty($nv_password)) {
            opidr_login([
                'status' => 'error',
                'mess' => $lang_global['password_empty']
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
            $error1 = $lang_global['loginincorrect'];

            $check_email = nv_check_valid_email($nv_username, true);
            if ($check_email[0] == '') {
                // Email login
                $sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE email =' . $db->quote($check_email[1]);
                $row = $db->query($sql)->fetch();
                if (empty($row)) {
                    opidr_login([
                        'status' => 'error',
                        'mess' => $lang_global['loginincorrect']
                    ]);
                }

                if ($row['email'] != $nv_username) {
                    opidr_login([
                        'status' => 'error',
                        'mess' => $lang_global['loginincorrect']
                    ]);
                }
            } else {
                // Username login
                $sql = 'SELECT * FROM ' . NV_MOD_TABLE . " WHERE md5username ='" . nv_md5safe($nv_username) . "'";
                $row = $db->query($sql)->fetch();
                if (empty($row)) {
                    opidr_login([
                        'status' => 'error',
                        'mess' => $lang_global['loginincorrect']
                    ]);
                }

                if ($row['username'] != $nv_username) {
                    opidr_login([
                        'status' => 'error',
                        'mess' => $lang_global['loginincorrect']
                    ]);
                }
            }

            if (!$crypt->validate_password($nv_password, $row['password'])) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $lang_global['loginincorrect']
                ]);
            }

            if ($row['safemode'] == 1) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $lang_module['safe_deactivate_openidreg']
                ]);
            }

            if (!$row['active']) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $lang_global['login_no_active']
                ]);
            }

            validUserLog($row, 1, '');
        }

        $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . (int) $row['userid'] . ', :server, :opid, :id, :email )');
        $stmt->bindParam(':server', $attribs['server'], PDO::PARAM_STR);
        $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
        $stmt->bindParam(':id', $attribs['id'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        opidr_login([
            'status' => 'success',
            'mess' => $lang_module['login_ok']
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
            $sess_verify = unserialize($sess_verify);
            !isset($sess_verify['code']) && $sess_verify['code'] = '';
        } else {
            $sess_verify = ['code' => '', 'time' => 0];
        }

        if (!empty($sess_verify['code']) and ($pas = 300 - NV_CURRENTTIME + (int)$sess_verify['time']) > 0) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => sprintf($lang_module['verifykey_issend'], ceil($pas / 60))
            ]);
        }

        $verikey = nv_genpass(8);
        $sess_verify = serialize(['code' => md5($verikey), 'time' => NV_CURRENTTIME]);
        $nv_Request->set_Session($md5_reg_email, $sess_verify);

        $sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
        $message = sprintf($lang_module['verify_email_mess'], $reg_email, $verikey, $sitename);
        $send = @nv_sendmail([
            $global_config['site_name'],
            $global_config['site_email']
        ], $reg_email, $lang_module['verify_email_title'], $message);

        if (!$send) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $lang_module['verify_email_sent_error']
            ]);
        }

        nv_jsonOutput([
            'status' => 'ok',
            'mess' => $lang_module['verify_email_sent']
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
                $sess_verify = unserialize($sess_verify);
                !isset($sess_verify['code']) && $sess_verify['code'] = '';
            } else {
                $sess_verify = ['code' => '', 'time' => 0];
            }

            if (empty($verify_code) or md5($verify_code) != $sess_verify['code']) {
                opidr_login([
                    'status' => 'error',
                    'mess' => $lang_module['verify_mail_error']
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
                'mess' => $lang_global['passwordsincorrect']
            ]);
        }

        $reg_password = $crypt->hash_password($reg_password, $global_config['hashprefix']);

        new_openid_user_save($reg_username, $reg_email, $reg_password, $attribs);
    }

    $page_title = $lang_global['openid_login'];
    $key_words = $module_info['keywords'];
    $mod_title = $lang_global['openid_login'];

    $contents .= user_openid_login($attribs, $op_process);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

$blocker = new NukeViet\Core\Blocker(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs', NV_CLIENT_IP);
$rules = [
    $global_config['login_number_tracking'],
    $global_config['login_time_tracking'],
    $global_config['login_time_ban']
];
$blocker->trackLogin($rules, $global_config['is_login_blocker']);

// Dang nhap kieu thong thuong
if ($nv_Request->isset_request('nv_login', 'post')) {
    $nv_username = nv_substr($nv_Request->get_title('nv_login', 'post', '', 1), 0, 100);
    $nv_password = $nv_Request->get_title('nv_password', 'post', '');

    unset($nv_seccode);
    // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
    if ($module_captcha == 'recaptcha') {
        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    }
    // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
    elseif ($module_captcha == 'captcha') {
        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    $gfx_chk = ($gfx_chk and $nv_Request->get_title('users_dismiss_captcha', 'session', '') != md5($nv_username));
    // Kiểm tra tính hợp lệ của captcha nhập vào
    $check_seccode = ($gfx_chk and isset($nv_seccode)) ? nv_capcha_txt($nv_seccode, $module_captcha) : true;

    if (!$check_seccode) {
        signin_result([
            'status' => 'error',
            'input' => ($module_captcha == 'recaptcha') ? '' : 'nv_seccode',
            'mess' => ($module_captcha == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect']
        ]);
    }

    if (empty($nv_username)) {
        signin_result([
            'status' => 'error',
            'input' => 'nv_login',
            'mess' => $lang_global['username_empty']
        ]);
    }

    if ($global_config['login_number_tracking'] and $blocker->is_blocklogin($nv_username)) {
        signin_result([
            'status' => 'error',
            'input' => '',
            'mess' => sprintf($lang_global['userlogin_blocked'], $global_config['login_number_tracking'], nv_date('H:i d/m/Y', $blocker->login_block_end))
        ]);
    }

    if (empty($nv_password)) {
        signin_result([
            'status' => 'error',
            'input' => 'nv_password',
            'mess' => $lang_global['password_empty']
        ]);
    }

    if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
        $error = '';
        require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
        if (!empty($error)) {
            signin_result([
                'status' => 'error',
                'input' => 'nv_login',
                'mess' => $error
            ]);
        }
    } else {
        $error1 = $lang_global['loginincorrect'];

        $check_email = nv_check_valid_email($nv_username, true);
        if ($check_email[0] == '') {
            // Email login
            $nv_username = $check_email[1];
            $sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE email =' . $db->quote($nv_username);
            $login_email = true;
        } else {
            // Username login
            $sql = 'SELECT * FROM ' . NV_MOD_TABLE . " WHERE md5username ='" . nv_md5safe($nv_username) . "'";
            $login_email = false;
        }

        $row = $db->query($sql)->fetch();

        if (!empty($row)) {
            if ((($row['md5username'] == nv_md5safe($nv_username) and $login_email == false) or ($row['email'] == $nv_username and $login_email == true)) and $crypt->validate_password($nv_password, $row['password'])) {
                if (!$row['active']) {
                    $error1 = $lang_module['login_no_active'];
                } else {
                    if (!empty($row['active2step'])) {
                        $nv_totppin = $nv_Request->get_title('nv_totppin', 'post', '');
                        $nv_backupcodepin = $nv_Request->get_title('nv_backupcodepin', 'post', '');

                        if (empty($nv_totppin) and empty($nv_backupcodepin)) {
                            $nv_Request->set_Session('users_dismiss_captcha', md5($nv_username));
                            signin_result([
                                'status' => '2step',
                                'input' => '',
                                'mess' => ''
                            ]);
                        }

                        $GoogleAuthenticator = new \NukeViet\Core\GoogleAuthenticator();

                        if (!empty($nv_totppin) and !$GoogleAuthenticator->verifyOpt($row['secretkey'], $nv_totppin)) {
                            signin_result([
                                'status' => 'error',
                                'input' => 'nv_totppin',
                                'mess' => $lang_global['2teplogin_error_opt']
                            ]);
                        }

                        if (!empty($nv_backupcodepin)) {
                            $nv_backupcodepin = nv_strtolower($nv_backupcodepin);
                            $sth = $db->prepare('SELECT code FROM ' . NV_MOD_TABLE . '_backupcodes WHERE is_used=0 AND code=:code AND userid=' . $row['userid']);
                            $sth->bindParam(':code', $nv_backupcodepin, PDO::PARAM_STR);
                            $sth->execute();

                            if ($sth->rowCount() != 1) {
                                signin_result([
                                    'status' => 'error',
                                    'input' => 'nv_backupcodepin',
                                    'mess' => $lang_global['2teplogin_error_backup']
                                ]);
                            }

                            $code = $sth->fetchColumn();
                            $db->query('UPDATE ' . NV_MOD_TABLE . '_backupcodes SET is_used=1, time_used=' . NV_CURRENTTIME . " WHERE code='" . $code . "' AND userid=" . $row['userid']);
                        }

                        $error1 = '';
                    } else {
                        $error1 = '';
                    }

                    if (empty($error1)) {
                        validUserLog($row, 1, '');
                        $nv_Request->unset_request('users_dismiss_captcha', 'session');
                        $blocker->reset_trackLogin($nv_username);
                    }
                }
            }
        }

        if ($global_config['login_number_tracking'] and (empty($row) or ($row['active'] and !empty($error1)))) {
            $blocker->set_loginFailed($nv_username, NV_CURRENTTIME);
        }

        if (!empty($error1)) {
            signin_result([
                'status' => 'error',
                'input' => '',
                'mess' => $error1
            ]);
        } elseif (empty($row['active2step'])) {
            $_2step_require = in_array((int) $global_config['two_step_verification'], [
                2,
                3
            ], true);
            if (!$_2step_require) {
                $_2step_require = nv_user_groups($row['in_groups'], true);
                $_2step_require = $_2step_require[1];
            }
            if ($_2step_require) {
                signin_result([
                    'status' => '2steprequire',
                    'input' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . NV_2STEP_VERIFICATION_MODULE . '&' . NV_OP_VARIABLE . '=setup' . ($nv_redirect ? '&nv_redirect=' . $nv_redirect : ''), true),
                    'mess' => $lang_global['2teplogin_require']
                ]);
            }
        }
    }

    signin_result([
        'status' => 'ok',
        'input' => '',
        'mess' => $lang_module['login_ok']
    ]);
}

$nv_Request->unset_request('users_dismiss_captcha', 'session');

if ($nv_Request->get_int('nv_ajax', 'post', 0) == 1) {
    exit(nv_url_rewrite(user_login(true), true));
}

$page_title = $lang_module['login'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['login'];

$canonicalUrl = getCanonicalUrl($page_url);

$contents = user_login();

$full = empty($nv_header);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, $full);
include NV_ROOTDIR . '/includes/footer.php';
