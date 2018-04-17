<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
}

if (defined('NV_IS_USER') or !$global_config['allowuserlogin']) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$nv_header = '';
if ($nv_Request->isset_request('nv_header', 'post,get')) {
    $nv_header = $nv_Request->get_title('nv_header', 'post,get', '');
    if ($nv_header != NV_CHECK_SESSION) {
        $nv_header = '';
    }
}

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
}

$gfx_chk = (in_array($global_config['gfx_chk'], array(
    2,
    4,
    5,
    7
))) ? 1 : 0;

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
 * opidr()
 *
 * @param mixed $openid_info
 * @return void
 */
function opidr($openid_info)
{
    global $nv_Request, $nv_redirect;

    $nv_Request->unset_request('openid_attribs', 'session');

    $openid_info['redirect'] = nv_redirect_decrypt($nv_redirect);

    $contents = openid_callback($openid_info);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * set_reg_attribs()
 *
 * @param mixed $attribs
 * @return
 */
function set_reg_attribs($attribs)
{
    global $crypt, $db, $global_config, $module_upload;

    $reg_attribs = array();
    $reg_attribs['server'] = $attribs['server'];
    $reg_attribs['username'] = '';
    $reg_attribs['email'] = $attribs['contact/email'];
    $reg_attribs['first_name'] = '';
    $reg_attribs['last_name'] = '';
    $reg_attribs['gender'] = '';
    $reg_attribs['yim'] = '';
    $reg_attribs['photo'] = '';
    $reg_attribs['openid'] = $attribs['id'];
    $reg_attribs['opid'] = $crypt->hash($attribs['id']);

    $username = explode('@', $attribs['contact/email']);
    $username = array_shift($username);

    if ($attribs['server'] == 'yahoo') {
        $reg_attribs['yim'] = $username;
    }

    $username = str_pad($username, $global_config['nv_unickmin'], '0', STR_PAD_RIGHT);
    $username = substr($username, 0, ($global_config['nv_unickmax'] - 2));
    $username2 = $username;
    for ($i = 0; $i < 100; ++$i) {
        if ($i > 0) {
            $username2 = $username . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $query = "SELECT userid FROM " . NV_MOD_TABLE . " WHERE md5username='" . nv_md5safe($username2) . "'";
        $userid = $db->query($query)->fetchColumn();
        if (!$userid) {
            $query = "SELECT userid FROM " . NV_MOD_TABLE . "_reg WHERE md5username='" . nv_md5safe($username2) . "'";
            $userid = $db->query($query)->fetchColumn();
            if (!$userid) {
                $reg_attribs['username'] = $username2;
                break;
            }
        }
    }

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
        if (!empty($attribs['picture_url']) and empty($attribs['picture_mode'])) {
            $upload = new NukeViet\Files\Upload(array(
                'images'
            ), $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $upload->setLanguage($lang_global);

            $upload_info = $upload->save_urlfile($attribs['picture_url'], NV_UPLOADS_REAL_DIR . '/' . $module_upload, false);

            if (empty($upload_info['error'])) {
                $basename = change_alias($reg_attribs['username']) . '.' . nv_getextension($upload_info['basename']);
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

//Dang nhap bang Open ID
$server = $nv_Request->get_string('server', 'get', '');
if (defined('NV_OPENID_ALLOWED') and $nv_Request->isset_request('server', 'get')) {
    $server = $nv_Request->get_string('server', 'get', '');
    $result = $nv_Request->isset_request('result', 'get');

    if (empty($server) or !in_array($server, $global_config['openid_servers']) or !$result) {
        header('Location: ' . NV_BASE_SITEURL);
        die();
    }

    $attribs = $nv_Request->get_string('openid_attribs', 'session', '');
    $attribs = !empty($attribs) ? unserialize($attribs) : array();

    if (empty($attribs) or $attribs['server'] != $server) {
        opidr(array(
            'status' => 'error',
            'mess' => $lang_module['logged_in_failed']
        ));
    }

    if ($attribs['result'] == 'cancel') {
        opidr(array(
            'status' => 'error',
            'mess' => $lang_module['canceled_authentication']
        ));
    }

    if ($attribs['result'] == 'notlogin') {
        opidr(array(
            'status' => 'error',
            'mess' => $lang_module['not_logged_in']
        ));
    }

    $email = (isset($attribs['contact/email']) and nv_check_valid_email($attribs['contact/email']) == '') ? $attribs['contact/email'] : '';
    if (empty($email)) {
        opidr(array(
            'status' => 'error',
            'mess' => $lang_module['logged_no_email']
        ));
    }
    $email = nv_strtolower($email);
    $opid = $crypt->hash($attribs['id']);
    $current_mode = isset($attribs['current_mode']) ? $attribs['current_mode'] : 1;

    /**
     * Neu da co trong CSDL
     */
    $stmt = $db->prepare('SELECT a.userid AS uid, a.email AS uemail, b.active AS uactive, b.safemode AS safemode FROM ' . NV_MOD_TABLE . '_openid a, ' . NV_MOD_TABLE . ' b
		WHERE a.opid= :opid
		AND a.email= :email
		AND a.userid=b.userid');
    $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    list ($user_id, $op_email, $user_active, $safemode) = $stmt->fetch(3);

    if ($user_id) {
        if ($safemode == 1) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['safe_deactivate_openidlogin']
            ));
        }

        if (!$user_active) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['login_no_active']
            ));
        }

        if (defined('NV_IS_USER_FORUM') and file_exists(NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/set_user_login.php')) {
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/set_user_login.php';
        } else {
            $query = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $user_id;
            $row = $db->query($query)->fetch();
            validUserLog($row, 1, $opid, $current_mode);
        }

        opidr(array(
            'status' => 'success',
            'mess' => $lang_module['login_ok']
        ));
    }

    /**
     * Neu chua co trong CSDL nhung email da duoc su dung
     */
    $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . ' WHERE email= :email');
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $nv_row = $stmt->fetch();

    if (!empty($nv_row)) {
        if ($nv_row['safemode'] == 1) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['safe_deactivate_openidreg']
            ));
        }

        if (!$nv_row['active']) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['login_no_active']
            ));
        }

        if (!empty($nv_row['password'])) {
            if ($nv_Request->isset_request('openid_account_confirm', 'post')) {
                $password = $nv_Request->get_string('password', 'post', '');

                if ($global_config['captcha_type'] == 2) {
                    $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
                } else {
                    $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
                }

                $check_seccode = !$gfx_chk ? true : (nv_capcha_txt($nv_seccode) ? true : false);

                $nv_Request->unset_request('openid_attribs', 'session');
                if (defined('NV_IS_USER_FORUM') and file_exists(NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php')) {
                    $nv_username = $nv_row['username'];
                    $nv_password = $password;
                    $error = "";
                    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
                    if (!empty($error)) {
                        opidr(array(
                            'status' => 'error',
                            'mess' => $lang_module['openid_confirm_failed']
                        ));
                    }
                } elseif (!$crypt->validate_password($password, $nv_row['password']) or !$check_seccode) {
                    opidr(array(
                        'status' => 'error',
                        'mess' => $lang_module['openid_confirm_failed']
                    ));
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

        $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . (int) $nv_row['userid'] . ', :server, :opid, :email )');
        $stmt->bindParam(':server', $attribs['server'], PDO::PARAM_STR);
        $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        validUserLog($nv_row, 1, $opid, $current_mode);

        opidr(array(
            'status' => 'success',
            'mess' => $lang_module['login_ok']
        ));
    }

    /**
     * Neu chua co hoan toan trong CSDL
     */

    /**
     * Neu gan OpenID nay vao 1 tai khoan da co
     */
    if ($nv_Request->isset_request('nv_login', 'post')) {
        $nv_username = $nv_Request->get_title('login', 'post', '', 1);
        $nv_password = $nv_Request->get_title('password', 'post', '');
        if ($global_config['captcha_type'] == 2) {
            $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
        } else {
            $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
        }

        $check_seccode = !$gfx_chk ? true : (nv_capcha_txt($nv_seccode) ? true : false);

        if (!$check_seccode) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['securitycodeincorrect']
            ));
        }

        if (empty($nv_username)) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_global['username_empty']
            ));
        }

        if (empty($nv_password)) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['password_empty']
            ));
        }

        if (defined('NV_IS_USER_FORUM')) {
            $error = '';
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
            if (!empty($error)) {
                opidr(array(
                    'status' => 'error',
                    'mess' => $error
                ));
            }
        } else {
            $error1 = $lang_global['loginincorrect'];

            if (nv_check_valid_email($nv_username) == '') {
                // Email login
                $sql = "SELECT * FROM " . NV_MOD_TABLE . " WHERE email =" . $db->quote($nv_username);
                $row = $db->query($sql)->fetch();
                if (empty($row)) {
                    opidr(array(
                        'status' => 'error',
                        'mess' => $lang_global['loginincorrect']
                    ));
                }

                if ($row['email'] != $nv_username) {
                    opidr(array(
                        'status' => 'error',
                        'mess' => $lang_global['loginincorrect']
                    ));
                }
            } else {
                // Username login
                $sql = "SELECT * FROM " . NV_MOD_TABLE . " WHERE md5username ='" . nv_md5safe($nv_username) . "'";
                $row = $db->query($sql)->fetch();
                if (empty($row)) {
                    opidr(array(
                        'status' => 'error',
                        'mess' => $lang_global['loginincorrect']
                    ));
                }

                if ($row['username'] != $nv_username) {
                    opidr(array(
                        'status' => 'error',
                        'mess' => $lang_global['loginincorrect']
                    ));
                }
            }

            if (!$crypt->validate_password($nv_password, $row['password'])) {
                opidr(array(
                    'status' => 'error',
                    'mess' => $lang_global['loginincorrect']
                ));
            }

            if ($row['safemode'] == 1) {
                opidr(array(
                    'status' => 'error',
                    'mess' => $lang_module['safe_deactivate_openidreg']
                ));
            }

            if (!$row['active']) {
                opidr(array(
                    'status' => 'error',
                    'mess' => $lang_global['login_no_active']
                ));
            }

            validUserLog($row, 1, '');
        }

        $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . (int) $row['userid'] . ', :server, :opid, :email )');
        $stmt->bindParam(':server', $attribs['server'], PDO::PARAM_STR);
        $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        opidr(array(
            'status' => 'success',
            'mess' => $lang_module['login_ok']
        ));
    }

    /**
     * Neu dang ky moi va cho dang ky khong can kich hoat hoac kich hoat qua email (allowuserreg = 1, 2)
     * bo qua phuong an kiem tra email
     * Vi ban than xac thuc cua OpenID da du dieu kien
     */
    if ($nv_Request->isset_request('nv_reg', 'post') and ($global_config['allowuserreg'] == 1 or $global_config['allowuserreg'] == 2)) {
        $reg_attribs = set_reg_attribs($attribs);
        if (empty($reg_attribs['username'])) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['logged_in_failed']
            ));
        }

        $sql = "INSERT INTO " . NV_MOD_TABLE . " (
            group_id, username, md5username, password, email, first_name, last_name, gender, photo, birthday,  regdate,
    		question, answer, passlostkey, view_mail, remember, in_groups,
    		active, checknum, last_login, last_ip, last_agent, last_openid, idsite, email_verification_time
        ) VALUES (
    		" . ($global_users_config['active_group_newusers'] ? 7 : 4) . ",
            :username,
    		:md5username,
    		'',
    		:email,
    		:first_name,
    		:last_name,
    		:gender,
    		'', 0,
    		" . NV_CURRENTTIME . ",
    		'', '', '', 0, 0, '" . ($global_users_config['active_group_newusers'] ? '7' : '') . "', 1, '', 0, '', '', '', " . intval($global_config['idsite']) . ",
            -1
		)";

        $data_insert = array();
        $data_insert['username'] = $reg_attribs['username'];
        $data_insert['md5username'] = nv_md5safe($reg_attribs['username']);
        $data_insert['email'] = $reg_attribs['email'];
        $data_insert['first_name'] = $reg_attribs['first_name'];
        $data_insert['last_name'] = $reg_attribs['last_name'];
        $data_insert['gender'] = !empty($reg_attribs['gender']) ? ucfirst(substr($reg_attribs['gender'], 0, 1)) : 'N';

        $userid = $db->insert_id($sql, 'userid', $data_insert);

        if (!$userid) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['err_no_save_account']
            ));
        }

        // Cap nhat thong tin anh dai dien
        if (!empty($reg_attribs['photo'])) {
            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET photo=:photo WHERE userid=' . $userid);
            $stmt->bindParam(':photo', $reg_attribs['photo'], PDO::PARAM_STR);
            $stmt->execute();
        }

        // Cap nhat so thanh vien
        $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=' . ($global_users_config['active_group_newusers'] ? 7 : 4));

        $query = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid . ' AND active=1';
        $result = $db->query($query);
        $row = $result->fetch();
        $result->closeCursor();

        // Luu vao bang thong tin tuy chinh
        $query_field = array();
        $query_field['userid'] = $userid;
        $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY fid ASC');
        while ($row_f = $result_field->fetch()) {
            if ($row_f['system'] == 1) continue;
            $query_field[$row_f['field']] = $db->quote($row_f['default_value']);
        }
        $db->query('INSERT INTO ' . NV_MOD_TABLE . '_info (' . implode(', ', array_keys($query_field)) . ') VALUES (' . implode(', ', array_values($query_field)) . ')');

        // Luu vao bang OpenID
        $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . intval($row['userid']) . ', :server, :opid , :email)');
        $stmt->bindParam(':server', $reg_attribs['server'], PDO::PARAM_STR);
        $stmt->bindParam(':opid', $reg_attribs['opid'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $reg_attribs['email'], PDO::PARAM_STR);
        $stmt->execute();

        validUserLog($row, 1, $reg_attribs['opid'], $current_mode);
        $nv_Cache->delMod($module_name);

        opidr(array(
            'status' => 'success',
            'mess' => $lang_module['login_ok']
        ));
    }

    /**
     * Neu dang ky moi va phai qua kiem duyet cua admin (allowuserreg = 3)
     */
    if ($nv_Request->isset_request('nv_reg', 'post') and $global_config['allowuserreg'] == 3) {
        $reg_attribs = set_reg_attribs($attribs);
        if (empty($reg_attribs['username'])) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['logged_in_failed']
            ));
        }

        $query_field = array();
        $query_field['userid'] = $userid;
        $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY fid ASC');
        while ($row_f = $result_field->fetch()) {
            $query_field[$row_f['field']] = $db->quote($row_f['default_value']);
        }

        $sql = "INSERT INTO " . NV_MOD_TABLE . "_reg (
            username, md5username, password, email, first_name, last_name, regdate, question, answer, checknum, users_info, openid_info
        ) VALUES (
			:username,
			:md5username,
			'',
			:email,
			:first_name,
			:last_name,
			" . NV_CURRENTTIME . ",
			'',
			'',
			'',
			:users_info,
            :openid_info
		)";

        $data_insert = array();
        $data_insert['username'] = $reg_attribs['username'];
        $data_insert['md5username'] = nv_md5safe($reg_attribs['username']);
        $data_insert['email'] = $reg_attribs['email'];
        $data_insert['first_name'] = $reg_attribs['first_name'];
        $data_insert['last_name'] = $reg_attribs['last_name'];
        $data_insert['users_info'] = nv_base64_encode(serialize($query_field));
        $data_insert['openid_info'] = nv_base64_encode(serialize($reg_attribs));
        $userid = $db->insert_id($sql, 'userid', $data_insert);

        if (!$userid) {
            opidr(array(
                'status' => 'error',
                'mess' => $lang_module['err_no_save_account']
            ));
        }
        $nv_Cache->delMod($module_name);

        opidr(array(
            'status' => 'success',
            'mess' => $lang_module['account_register_to_admin']
        ));
    }

    $page_title = $lang_global['openid_login'];
    $key_words = $module_info['keywords'];
    $mod_title = $lang_global['openid_login'];

    $contents .= user_openid_login($gfx_chk, $attribs);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

$blocker = new NukeViet\Core\Blocker(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs', NV_CLIENT_IP);
$rules = array(
    $global_config['login_number_tracking'],
    $global_config['login_time_tracking'],
    $global_config['login_time_ban']
);
$blocker->trackLogin($rules);

// Dang nhap kieu thong thuong
if ($nv_Request->isset_request('nv_login', 'post')) {
    $nv_username = nv_substr($nv_Request->get_title('nv_login', 'post', '', 1), 0, 100);
    $nv_password = $nv_Request->get_title('nv_password', 'post', '');
    if ($global_config['captcha_type'] == 2) {
        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    } else {
        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    $gfx_chk = ($gfx_chk and $nv_Request->get_title('users_dismiss_captcha', 'session', '') != md5($nv_username));
    $check_seccode = !$gfx_chk ? true : (nv_capcha_txt($nv_seccode) ? true : false);

    if (!$check_seccode) {
        signin_result(array(
            'status' => 'error',
            'input' => ($global_config['captcha_type'] == 2 ? '' : 'nv_seccode'),
            'mess' => ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'])
        ));
    }

    if (empty($nv_username)) {
        signin_result(array(
            'status' => 'error',
            'input' => 'nv_login',
            'mess' => $lang_global['username_empty']
        ));
    }

    if ($global_config['login_number_tracking'] and $blocker->is_blocklogin($nv_username)) {
        signin_result(array(
            'status' => 'error',
            'input' => '',
            'mess' => sprintf($lang_global['userlogin_blocked'], $global_config['login_number_tracking'], nv_date('H:i d/m/Y', $blocker->login_block_end))
        ));
    }

    if (empty($nv_password)) {
        signin_result(array(
            'status' => 'error',
            'input' => 'nv_password',
            'mess' => $lang_global['password_empty']
        ));
    }

    if (defined('NV_IS_USER_FORUM')) {
        $error = '';
        require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
        if (!empty($error)) {
            signin_result(array(
                'status' => 'error',
                'input' => 'nv_login',
                'mess' => $error
            ));
        }
    } else {
        $error1 = $lang_global['loginincorrect'];

        if (nv_check_valid_email($nv_username) == '') {
            // Email login
            $nv_username = nv_strtolower($nv_username);
            $sql = "SELECT * FROM " . NV_MOD_TABLE . " WHERE email =" . $db->quote($nv_username);
            $login_email = true;
        } else {
            // Username login
            $sql = "SELECT * FROM " . NV_MOD_TABLE . " WHERE md5username ='" . nv_md5safe($nv_username) . "'";
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
                            signin_result(array(
                                'status' => '2step',
                                'input' => '',
                                'mess' => ''
                            ));
                        }

                        $GoogleAuthenticator = new \NukeViet\Core\GoogleAuthenticator();

                        if (!empty($nv_totppin) and !$GoogleAuthenticator->verifyOpt($row['secretkey'], $nv_totppin)) {
                            signin_result(array(
                                'status' => 'error',
                                'input' => 'nv_totppin',
                                'mess' => $lang_global['2teplogin_error_opt']
                            ));
                        }

                        if (!empty($nv_backupcodepin)) {
                            $nv_backupcodepin = nv_strtolower($nv_backupcodepin);
                            $sth = $db->prepare('SELECT code FROM ' . NV_MOD_TABLE . '_backupcodes WHERE is_used=0 AND code=:code AND userid=' . $row['userid']);
                            $sth->bindParam(':code', $nv_backupcodepin, PDO::PARAM_STR);
                            $sth->execute();

                            if ($sth->rowCount() != 1) {
                                signin_result(array(
                                    'status' => 'error',
                                    'input' => 'nv_backupcodepin',
                                    'mess' => $lang_global['2teplogin_error_backup']
                                ));
                            }

                            $code = $sth->fetchColumn();
                            $db->query('UPDATE ' . NV_MOD_TABLE . "_backupcodes SET is_used=1, time_used=" . NV_CURRENTTIME . " WHERE code='" . $code . "' AND userid=" . $row['userid']);
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
            signin_result(array(
                'status' => 'error',
                'input' => '',
                'mess' => $error1
            ));
        } elseif (empty($row['active2step'])) {
            $_2step_require = in_array($global_config['two_step_verification'], array(
                2,
                3
            ));
            if (!$_2step_require) {
                $_2step_require = nv_user_groups($row['in_groups'], true);
                $_2step_require = $_2step_require[1];
            }
            if ($_2step_require) {
                signin_result(array(
                    'status' => '2steprequire',
                    'input' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . NV_2STEP_VERIFICATION_MODULE . '&' . NV_OP_VARIABLE . '=setup' . ($nv_redirect ? '&nv_redirect=' . $nv_redirect : ''), true),
                    'mess' => $lang_global['2teplogin_require']
                ));
            }
        }
    }

    signin_result(array(
        'status' => 'ok',
        'input' => '',
        'mess' => $lang_module['login_ok']
    ));
}

$nv_Request->unset_request('users_dismiss_captcha', 'session');

if ($nv_Request->get_int('nv_ajax', 'post', 0) == 1) {
    die(user_login(true));
}

$page_title = $lang_module['login'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['login'];

$contents = user_login();

$full = empty($nv_header);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, $full);
include NV_ROOTDIR . '/includes/footer.php';