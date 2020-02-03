<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/30/2009 1:31
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

// Kiểm tra IP
if (!nv_admin_checkip()) {
    nv_info_die($global_config['site_description'], $nv_Lang->get('site_info'), $nv_Lang->get('admin_ipincorrect', NV_CLIENT_IP) . '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />');
}

// Kiểm tra tường lửa
if (!nv_admin_checkfirewall()) {
    // Remove non US-ASCII to respect RFC2616
    $server_message = preg_replace('/[^\x20-\x7e]/i', '', $nv_Lang->get('firewallsystem'));
    if (empty($server_message)) {
        $server_message = 'Administrators Section';
    }
    header('WWW-Authenticate: Basic realm="' . $server_message . '"');
    header(NV_HEADERSTATUS . ' 401 Unauthorized');
    if (php_sapi_name() !== 'cgi-fcgi') {
        header('status: 401 Unauthorized');
    }
    nv_info_die($global_config['site_description'], $nv_Lang->get('site_info'), $nv_Lang->get('firewallincorrect') . '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />', 401);
}

// Ngôn ngôn ngữ admin
$nv_Lang->loadGlobal(true);

// Kiểm tra xem đã login xong bước 1 chưa
$admin_pre_data = nv_admin_check_predata($nv_Request->get_string('admin_pre', 'session', ''));
$admin_login_redirect = $nv_Request->get_string('admin_login_redirect', 'session', '');

$blocker = new NukeViet\Core\Blocker(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs', NV_CLIENT_IP);
$rules = array($global_config['login_number_tracking'], $global_config['login_time_tracking'], $global_config['login_time_ban']);
$blocker->trackLogin($rules);

$error = '';
$array = [];
$array_gfx_chk = [1, 5, 6, 7];
if (in_array($global_config['gfx_chk'], $array_gfx_chk)) {
    $global_config['gfx_chk'] = 1;
} else {
    $global_config['gfx_chk'] = 0;
}
$admin_login_success = false;

// Đăng xuất tài khoản login bước 1 để login lại
if (!empty($admin_pre_data) and $nv_Request->isset_request('pre_logout', 'get') and $nv_Request->get_title('checkss', 'get') == NV_CHECK_SESSION) {
    $nv_Request->unset_request('admin_pre', 'session');
    nv_redirect_location(NV_BASE_ADMINURL);
}

// Xác định các phương thức xác thực hai bước hệ thống sử dụng
$cfg_2step = [];
if (!empty($admin_pre_data)) {
    $cfg_2step['opts'] = []; // Các hình thức xác thực được phép
    $cfg_2step['default'] = $global_config['admin_2step_default']; // Hình thức mặc định
    $cfg_2step['active_code'] = boolval($admin_pre_data['active2step']); // Đã bật xác thực 2 bước bằng ứng dụng hay chưa
    $cfg_2step['active_facebook'] = false; // Đã login bằng Facebook hay chưa
    $cfg_2step['active_google'] = false; // Đã login bằng Google hay chưa
    $_2step_opt = explode(',', $global_config['admin_2step_opt']);
    if (in_array('code', $_2step_opt)) {
        $cfg_2step['opts'][] = 'code';
    }
    if (in_array('facebook', $_2step_opt) and !empty($global_config['facebook_client_id']) and !empty($global_config['facebook_client_secret'])) {
        $cfg_2step['opts'][] = 'facebook';
        $sql = "SELECT COUNT(oauth_uid) FROM " . NV_AUTHORS_GLOBALTABLE . "_oauth WHERE admin_id=" . $admin_pre_data['admin_id'] . " AND oauth_server='facebook'";
        $cfg_2step['active_facebook'] = boolval($db->query($sql)->fetchColumn());
    }
    if (in_array('google', $_2step_opt) and !empty($global_config['google_client_id']) and !empty($global_config['google_client_secret'])) {
        $cfg_2step['opts'][] = 'google';
        $sql = "SELECT COUNT(oauth_uid) FROM " . NV_AUTHORS_GLOBALTABLE . "_oauth WHERE admin_id=" . $admin_pre_data['admin_id'] . " AND oauth_server='google'";
        $cfg_2step['active_google'] = boolval($db->query($sql)->fetchColumn());
    }
    if (empty($cfg_2step['default']) or !in_array($cfg_2step['default'], $cfg_2step['opts'])) {
        $cfg_2step['default'] = current($cfg_2step['opts']);
    }
    /*
     * Số phương thức xác thực đã được kích hoạt
     * - Khi chưa có phương thức nào thì cho phép kích hoạt một trong số các phương thức đó
     * - Khi đã có rồi thì chỉ được sử dụng phương thức đó để xác thực (có thể 1 hoặc nhiều tùy cấu hình)
     */
    $cfg_2step['count_active'] = sizeof(array_filter([$cfg_2step['active_code'], $cfg_2step['active_facebook'], $cfg_2step['active_google']]));
    $cfg_2step['count_opts'] = sizeof($cfg_2step['opts']);
}

/*
 * Chọn phương thức xác thực
 * - Có thể chưa kích hoạt: Điều kiện là chưa có phương thức xác thực nào
 * - Có thể đã kích hoạt rồi
 */
if (
    !empty($admin_pre_data) and in_array(($opt = $nv_Request->get_title('auth', 'get', '')), $cfg_2step['opts'])
    and ((!$cfg_2step['active_' . $opt] and $cfg_2step['count_active'] < 1) or $cfg_2step['active_' . $opt])
) {
    if ($opt == 'code') {
        // Login bằng tài khoản user 1 step để chuyển sang trang kích hoạt
        $checknum = md5(nv_genpass(10));
        $user = [
            'userid' => $admin_pre_data['userid'],
            'current_mode' => 0,
            'checknum' => $checknum,
            'checkhash' => md5($admin_pre_data['userid'] . $checknum . $global_config['sitekey'] . $client_info['browser']['key']),
            'current_agent' => NV_USER_AGENT,
            'last_agent' => $admin_pre_data['user_last_agent'],
            'current_ip' => NV_CLIENT_IP,
            'last_ip' => $admin_pre_data['user_last_ip'],
            'current_login' => NV_CURRENTTIME,
            'last_login' => intval($admin_pre_data['user_last_login']),
            'last_openid' => $admin_pre_data['user_last_openid'],
            'current_openid' => ''
        ];

        $stmt = $db->prepare("UPDATE " . NV_USERS_GLOBALTABLE . " SET
            checknum = :checknum,
            last_login = " . NV_CURRENTTIME . ",
            last_ip = :last_ip,
            last_agent = :last_agent,
            last_openid = '',
            remember = 1
        WHERE userid=" . $admin_pre_data['userid']);

        $stmt->bindValue(':checknum', $checknum, PDO::PARAM_STR);
        $stmt->bindValue(':last_ip', NV_CLIENT_IP, PDO::PARAM_STR);
        $stmt->bindValue(':last_agent', NV_USER_AGENT, PDO::PARAM_STR);
        $stmt->execute();

        $nv_Request->set_Cookie('nvloginhash', json_encode($user), NV_LIVE_COOKIE_TIME);

        $tokend_key = md5($admin_pre_data['username'] . '_' . NV_CURRENTTIME . '_users_confirm_pass_' . NV_CHECK_SESSION);
        $tokend = md5('users_confirm_pass_' . NV_CHECK_SESSION);
        $nv_Request->set_Session($tokend_key, $tokend);

        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification&amp;' . NV_OP_VARIABLE . '=setup&amp;nv_redirect=' . nv_redirect_encrypt(NV_BASE_ADMINURL);
        nv_redirect_location($url);
    }

    // Gọi file xử lý chuyển hướng sang google, facebook để kích hoạt
    $attribs = [];
    define('NV_ADMIN_ACTIVE_2STEP_OAUTH', true);
    require NV_ROOTDIR . '/includes/core/admin_login_' . $opt . '.php';

    // Xử lý trả về
    if (!empty($_GET['code']) and empty($error)) {
        if (empty($attribs)) {
            $error = $nv_Lang->getGlobal('admin_oauth_error_getdata');
        } elseif (!$cfg_2step['active_' . $opt]) {
            // Nếu chưa kích hoạt phương thức này (chưa có gì trong CSDL) thì lưu vào CSDL và xác thực đăng nhập phiên này
            $sql = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_oauth (
                admin_id, oauth_server, oauth_uid, oauth_email, addtime
            ) VALUES (
                " . $admin_pre_data['admin_id'] . ", " . $db->quote($opt) . ", " . $db->quote($attribs['full_identity']) . ",
                " . $db->quote($attribs['email']) . ", " . NV_CURRENTTIME . "
            )";
            if ($db->insert_id($sql, 'id')) {
                $row = $admin_pre_data;
                $admin_login_success = true;
            } else {
                $error = $nv_Lang->getGlobal('admin_oauth_error_savenew');
            }
        } else {
            // Nếu đã kích hoạt rồi thì tìm xem trong CSDL khớp với thông tin xác thực này không!
            $sql = "SELECT * FROM " . NV_AUTHORS_GLOBALTABLE . "_oauth WHERE admin_id=" . $admin_pre_data['admin_id'] . "
            AND oauth_server=" . $db->quote($opt) . " AND oauth_uid=" . $db->quote($attribs['full_identity']);
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
if (
    !empty($admin_pre_data) and $nv_Request->isset_request('submit2scode', 'post')
    and $nv_Request->get_title('checkss', 'post') == NV_CHECK_SESSION
    and $cfg_2step['active_code'] and in_array('code', $cfg_2step['opts'])
) {
    $array['totppin'] = $nv_Request->get_title('nv_totppin', 'post', '');
    $array['backupcodepin'] = $nv_Request->get_title('nv_backupcodepin', 'post', '');

    $step2_isvalid = false;
    $GoogleAuthenticator = new \NukeViet\Core\GoogleAuthenticator();

    if (!empty($array['totppin'])) {
        if (!$GoogleAuthenticator->verifyOpt($admin_pre_data['user_2s_secretkey'], $array['totppin'])) {
            $error = $nv_Lang->getGlobal('2teplogin_error_opt');
        } else {
            $step2_isvalid = true;
        }
    }

    if (!empty($array['backupcodepin'])) {
        $array['backupcodepin'] = nv_strtolower($array['backupcodepin']);
        $sth = $db->prepare('SELECT code FROM ' . NV_USERS_GLOBALTABLE . '_backupcodes WHERE is_used=0 AND code=:code AND userid=' . $admin_pre_data['userid']);
        $sth->bindParam(':code', $array['backupcodepin'], PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            $error = $nv_Lang->getGlobal('2teplogin_error_backup');
        } else {
            $code = $sth->fetchColumn();
            $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . "_backupcodes SET is_used=1, time_used=" . NV_CURRENTTIME . " WHERE code='" . $code . "' AND userid=" . $admin_pre_data['userid']);
            $step2_isvalid = true;
        }
    }

    if ($step2_isvalid) {
        $row = $admin_pre_data;
        $admin_login_success = true;
    }
} else {
    $array['totppin'] = $array['backupcodepin'] = '';
}

// Login bước 1
if (empty($admin_pre_data) and $nv_Request->isset_request('nv_login,nv_password', 'post') and $nv_Request->get_title('checkss', 'post') == NV_CHECK_SESSION) {
    $array['username'] = $nv_Request->get_title('nv_login', 'post', '', 1);
    $array['password'] = $nv_Request->get_title('nv_password', 'post', '');

    if ($global_config['captcha_type'] == 2) {
        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    } else {
        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    if (empty($array['username'])) {
        $error = $nv_Lang->getGlobal('username_empty');
    } elseif ($global_config['login_number_tracking'] and $blocker->is_blocklogin($array['username'])) {
        $error = $nv_Lang->getGlobal('userlogin_blocked', $global_config['login_number_tracking'], nv_date('H:i d/m/Y', $blocker->login_block_end));
    } elseif (empty($array['password'])) {
        $error = $nv_Lang->getGlobal('password_empty');
    } elseif ($global_config['gfx_chk'] and !nv_capcha_txt($nv_seccode)) {
        $error = ($global_config['captcha_type'] == 2 ? $nv_Lang->getGlobal('securitycodeincorrect1') : $nv_Lang->getGlobal('securitycodeincorrect'));
    } else {
        // Đăng nhập khi kích hoạt diễn đàn
        if (defined('NV_IS_USER_FORUM')) {
            define('NV_IS_MOD_USER', true);
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
            if (empty($array['username'])) {
                $array['username'] = $nv_Request->get_title('nv_login', 'post', '', 1);
            }
            if (empty($array['password'])) {
                $array['password'] = $nv_Request->get_title('nv_password', 'post', '');
            }
        }

        // Kiểm tra đăng nhập bằng email hay username
        $check_email = nv_check_valid_email($array['username'], true);
        if ($check_email[0] == '') {
            $array['username'] = $check_email[1];
            $sql = 't2.email =' . $db->quote($array['username']);
            $login_email = true;
        } else {
            $sql = "t2.md5username ='" . nv_md5safe($array['username']) . "'";
            $login_email = false;
        }

        // Lấy thông tin đăng nhập
        $sql = 'SELECT t1.admin_id admin_id, t1.lev admin_lev, t1.last_agent admin_last_agent, t1.last_ip admin_last_ip, t1.last_login admin_last_login,
        t2.userid, t2.last_agent, t2.last_ip, t2.last_login, t2.last_openid, t2.username, t2.email, t2.password, t2.active2step, t2.in_groups, t2.secretkey
        FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1, ' . NV_USERS_GLOBALTABLE . ' t2
        WHERE t1.admin_id=t2.userid AND ' . $sql . ' AND t1.lev!=0 AND t1.is_suspend=0 AND t2.active=1';

        $row = $db->query($sql)->fetch();

        if (empty($row) or !((($row['username'] == $array['username'] and $login_email == false) or ($row['email'] == $array['username'] and $login_email == true)) and $crypt->validate_password($array['password'], $row['password']))) {
            // Đăng nhập bước đầu thất bại
            nv_insert_logs(NV_LANG_DATA, 'login', '[' . $array['username'] . '] ' . $nv_Lang->getGlobal('loginsubmit') . ' ' . $nv_Lang->getGlobal('fail'), ' Client IP:' . NV_CLIENT_IP, 0);
            $blocker->set_loginFailed($array['username'], NV_CURRENTTIME);
            $error = $nv_Lang->getGlobal('loginincorrect');
        } else {
            $row['admin_lev'] = intval($row['admin_lev']);

            // Kiểm tra quyền đăng nhập (do cấu hình hệ thống quy định)
            if (!defined('ADMIN_LOGIN_MODE')) {
                define('ADMIN_LOGIN_MODE', 3);
            }
            if (ADMIN_LOGIN_MODE == 2 and !in_array($row['admin_lev'], [1, 2])) {
                // Điều hành chung + Tối cao được đăng nhập
                $error = $nv_Lang->getGlobal('admin_access_denied2');
            } elseif (ADMIN_LOGIN_MODE == 1 and $row['admin_lev'] != 1) {
                // Tối cao được đăng nhập
                $error = $nv_Lang->getGlobal('admin_access_denied1');
            }
        }

        if (empty($error)) {
            /*
             * Đăng nhập bước đầu thành công, kiểm tra xem hệ thống có bắt xác thực hai bước hay không
             * Nếu không thì xem như đã thành công.
             * Nếu có lưu lại thông tin xác thực bước 1 và load lại trang để kiểm tra xử lý tiếp
             */
            // Kiểm tra cấu hình toàn hệ thống
            $_2step_require = in_array($global_config['two_step_verification'], [1, 3]);
            if (!$_2step_require) {
                // Nếu toàn hệ thống không bắt buộc thì kiểm tra nhóm thành viên
                $manual_groups = [3];
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
                nv_insert_logs(NV_LANG_DATA, 'Pre login', '[' . $array['username'] . '] ' . $nv_Lang->getGlobal('loginsubmit'), ' Client IP:' . NV_CLIENT_IP, 0);
                $admin_id = intval($row['admin_id']);
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
                $blocker->reset_trackLogin($array['username']);

                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?rand=' . nv_genpass());
            }

            $admin_login_success = true;
        }
    }
} else {
    if (empty($admin_login_redirect)) {
        $nv_Request->set_Session('admin_login_redirect', $nv_Request->request_uri);
    }
    $array['username'] = $array['password'] = '';
}

// Đăng nhập admin hoàn toàn thành công
if ($admin_login_success === true) {
    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $row['username'] . '] ' . $nv_Lang->getGlobal('loginsubmit'), ' Client IP:' . NV_CLIENT_IP, 0);
    $admin_id = intval($row['admin_id']);
    $checknum = md5(nv_genpass(10));
    $array_admin = [
        'admin_id' => $admin_id,
        'checknum' => $checknum,
        'current_agent' => NV_USER_AGENT,
        'last_agent' => $row['admin_last_agent'],
        'current_ip' => NV_CLIENT_IP,
        'last_ip' => $row['admin_last_ip'],
        'current_login' => NV_CURRENTTIME,
        'last_login' => intval($row['admin_last_login'])
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

    $nv_Request->set_Session('admin', $admin_encode);
    $nv_Request->set_Session('online', '1|' . NV_CURRENTTIME . '|' . NV_CURRENTTIME . '|0');

    if ($global_config['lang_multi']) {
        $sql = 'SELECT setup FROM ' . $db_config['prefix'] . '_setup_language WHERE lang=' . $db->quote(NV_LANG_INTERFACE);
        $setup = $db->query($sql)->fetchColumn();
        if ($setup) {
            $nv_Request->set_Cookie('data_lang', NV_LANG_INTERFACE, NV_LIVE_COOKIE_TIME);
        }
    }

    define('NV_IS_ADMIN', true);

    $nv_Request->unset_request('admin_pre', 'session');

    $redirect = NV_BASE_SITEURL . NV_ADMINDIR;
    if (!empty($admin_login_redirect) and strpos($admin_login_redirect, NV_NAME_VARIABLE . '=siteinfo&' . NV_OP_VARIABLE . '=notification') == 0) {
        $redirect = $admin_login_redirect;
        $nv_Request->unset_request('admin_login_redirect', 'session');
    }
    $nv_Request->unset_request('admin_dismiss_captcha', 'session');
    nv_redirect_location($redirect);
}

// Gọi file xử lý giao diện đăng nhập của admin
if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/theme_login.php')) {
    require NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/theme_login.php';
} else {
    $global_config['admin_theme'] = 'admin_default';
    require NV_ROOTDIR . '/themes/admin_default/theme_login.php';
}

$contents = nv_admin_login_theme($array, $cfg_2step, $admin_pre_data, $error);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
