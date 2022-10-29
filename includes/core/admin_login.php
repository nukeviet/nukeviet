<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Kiểm tra IP
if (!nv_admin_checkip()) {
    nv_info_die($global_config['site_description'], $lang_global['site_info'], sprintf($lang_global['admin_ipincorrect'], NV_CLIENT_IP) . '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />');
}

// Kiểm tra tường lửa
if (!nv_admin_checkfirewall()) {
    // remove non US-ASCII to respect RFC2616
    $server_message = preg_replace('/[^\x20-\x7e]/i', '', $lang_global['firewallsystem']);
    if (empty($server_message)) {
        $server_message = 'Administrators Section';
    }
    header('WWW-Authenticate: Basic realm="' . $server_message . '"');
    header(NV_HEADERSTATUS . ' 401 Unauthorized');
    if (php_sapi_name() !== 'cgi-fcgi') {
        header('status: 401 Unauthorized');
    }
    nv_info_die($global_config['site_description'], $lang_global['site_info'], $lang_global['firewallincorrect'] . '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />', 401);
}

// Load ngôn ngữ
if (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_global.php')) {
    require_once NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_global.php';
} elseif (file_exists(NV_ROOTDIR . '/includes/language/en/admin_global.php')) {
    require_once NV_ROOTDIR . '/includes/language/en/admin_global.php';
}

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
$captcha_type = (empty($global_config['captcha_type']) or in_array($global_config['captcha_type'], ['captcha', 'recaptcha'], true)) ? $global_config['captcha_type'] : 'captcha';
if ($captcha_type == 'recaptcha' and (empty($global_config['recaptcha_sitekey']) or empty($global_config['recaptcha_secretkey']))) {
    $captcha_type = 'captcha';
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
    $cfg_2step['active_code'] = (bool) ($admin_pre_data['active2step']); // Đã bật xác thực 2 bước bằng ứng dụng hay chưa
    $cfg_2step['active_facebook'] = false; // Đã login bằng Facebook hay chưa
    $cfg_2step['active_google'] = false; // Đã login bằng Google hay chưa
    $cfg_2step['active_zalo'] = false; // Đã login bằng Zalo hay chưa
    $_2step_opt = explode(',', $global_config['admin_2step_opt']);
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
    $cfg_2step['count_active'] = sizeof(array_filter([
        $cfg_2step['active_code'],
        $cfg_2step['active_facebook'],
        $cfg_2step['active_google'],
        $cfg_2step['active_zalo']
    ]));
    $cfg_2step['count_opts'] = sizeof($cfg_2step['opts']);
}

/*
 * Chọn phương thức xác thực
 * - Có thể chưa kích hoạt: Điều kiện là chưa có phương thức xác thực nào
 * - Có thể đã kích hoạt rồi
 */
if (!empty($admin_pre_data) and in_array(($opt = $nv_Request->get_title('auth', 'get', '')), $cfg_2step['opts'], true) and ((!$cfg_2step['active_' . $opt] and $cfg_2step['count_active'] < 1) or $cfg_2step['active_' . $opt])) {
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
            'last_login' => (int) ($admin_pre_data['user_last_login']),
            'last_openid' => $admin_pre_data['user_last_openid'],
            'current_openid' => ''
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

        $nv_Request->set_Cookie('nvloginhash', json_encode($user), NV_LIVE_COOKIE_TIME);

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
            $error = $lang_global['admin_oauth_error_getdata'];
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
                $error = $lang_global['admin_oauth_error_savenew'];
            }
        } else {
            // Nếu đã kích hoạt rồi thì tìm xem trong CSDL khớp với thông tin xác thực này không!
            $sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_oauth WHERE admin_id=' . $admin_pre_data['admin_id'] . '
            AND oauth_server=' . $db->quote($opt) . ' AND oauth_uid=' . $db->quote($attribs['full_identity']);
            $oauth = $db->query($sql)->fetch();
            if (empty($oauth)) {
                $error = $lang_global['admin_oauth_error'];
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
            $error = $lang_global['2teplogin_error_opt'];
        } else {
            $step2_isvalid = true;
        }
    }

    if (!empty($nv_backupcodepin)) {
        $nv_backupcodepin = nv_strtolower($nv_backupcodepin);
        $sth = $db->prepare('SELECT code FROM ' . NV_USERS_GLOBALTABLE . '_backupcodes WHERE is_used=0 AND code=:code AND userid=' . $admin_pre_data['userid']);
        $sth->bindParam(':code', $nv_backupcodepin, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            $error = $lang_global['2teplogin_error_backup'];
        } else {
            $code = $sth->fetchColumn();
            $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . '_backupcodes SET is_used=1, time_used=' . NV_CURRENTTIME . " WHERE code='" . $code . "' AND userid=" . $admin_pre_data['userid']);
            $step2_isvalid = true;
        }
    }

    if ($step2_isvalid) {
        $row = $admin_pre_data;
        $admin_login_success = true;
    }
} else {
    $nv_totppin = $nv_backupcodepin = '';
}

// Login bước 1
if (empty($admin_pre_data) and $nv_Request->isset_request('nv_login,nv_password', 'post') and $nv_Request->get_title('checkss', 'post') == NV_CHECK_SESSION) {
    $nv_username = $nv_Request->get_title('nv_login', 'post', '', 1);
    $nv_password = $nv_Request->get_title('nv_password', 'post', '');

    unset($nv_seccode);
    // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
    if ($captcha_type == 'recaptcha') {
        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    }
    // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
    elseif ($captcha_type == 'captcha') {
        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    if (empty($nv_username)) {
        $error = $lang_global['username_empty'];
    } elseif ($global_config['login_number_tracking'] and $blocker->is_blocklogin($nv_username)) {
        $error = sprintf($lang_global['userlogin_blocked'], $global_config['login_number_tracking'], nv_date('H:i d/m/Y', $blocker->login_block_end));
    } elseif (empty($nv_password)) {
        $error = $lang_global['password_empty'];
    }
    // Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
    elseif ($gfx_chk and isset($nv_seccode) and !nv_capcha_txt($nv_seccode, $captcha_type)) {
        $error = ($captcha_type == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'];
    } else {
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
        $check_email = nv_check_valid_email($nv_username, true);
        if ($check_email[0] == '') {
            $nv_username = $check_email[1];
            $sql = 't2.email =' . $db->quote($nv_username);
            $login_email = true;
        } else {
            $sql = "t2.md5username ='" . nv_md5safe($nv_username) . "'";
            $login_email = false;
        }

        // Lấy thông tin đăng nhập
        $sql = 'SELECT t1.admin_id admin_id, t1.lev admin_lev, t1.last_agent admin_last_agent, t1.last_ip admin_last_ip, t1.last_login admin_last_login,
        t2.userid, t2.last_agent, t2.last_ip, t2.last_login, t2.last_openid, t2.username, t2.email, t2.password, t2.active2step, t2.in_groups, t2.secretkey
        FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1, ' . NV_USERS_GLOBALTABLE . ' t2
        WHERE t1.admin_id=t2.userid AND ' . $sql . ' AND t1.lev!=0 AND t1.is_suspend=0 AND t2.active=1';

        $row = $db->query($sql)->fetch();

        if (empty($row) or !((($row['username'] == $nv_username and $login_email == false) or ($row['email'] == $nv_username and $login_email == true)) and $crypt->validate_password($nv_password, $row['password']))) {
            // Đăng nhập bước đầu thất bại
            nv_insert_logs(NV_LANG_DATA, 'login', '[' . $nv_username . '] ' . $lang_global['loginsubmit'] . ' ' . $lang_global['fail'], ' Client IP:' . NV_CLIENT_IP, 0);
            $blocker->set_loginFailed($nv_username, NV_CURRENTTIME);
            $error = $lang_global['loginincorrect'];
        } else {
            $row['admin_lev'] = (int) ($row['admin_lev']);

            // Kiểm tra quyền đăng nhập (do cấu hình hệ thống quy định)
            if (!defined('ADMIN_LOGIN_MODE')) {
                define('ADMIN_LOGIN_MODE', 3);
            }
            if (ADMIN_LOGIN_MODE == 2 and !in_array($row['admin_lev'], [
                1,
                2
            ], true)) {
                // Điều hành chung + Tối cao được đăng nhập
                $error = $lang_global['admin_access_denied2'];
            } elseif (ADMIN_LOGIN_MODE == 1 and $row['admin_lev'] != 1) {
                // Tối cao được đăng nhập
                $error = $lang_global['admin_access_denied1'];
            }
        }

        if (empty($error)) {
            /*
             * Đăng nhập bước đầu thành công, kiểm tra xem hệ thống có bắt xác thực hai bước hay không
             * Nếu không thì xem như đã thành công.
             * Nếu có lưu lại thông tin xác thực bước 1 và load lại trang để kiểm tra xử lý tiếp
             */
            // Kiểm tra cấu hình toàn hệ thống
            $_2step_require = in_array((int) $global_config['two_step_verification'], [
                1,
                3
            ], true);
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
                nv_insert_logs(NV_LANG_DATA, 'Pre login', '[' . $nv_username . '] ' . $lang_global['loginsubmit'], ' Client IP:' . NV_CLIENT_IP, 0);
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

                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?rand=' . nv_genpass());
            }

            $admin_login_success = true;
        }
    }
} else {
    if (empty($admin_login_redirect)) {
        $nv_Request->set_Session('admin_login_redirect', $nv_Request->request_uri);
    }
    $nv_username = $nv_password = '';
}

// Đăng nhập admin hoàn toàn thành công
if ($admin_login_success === true) {
    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $row['username'] . '] ' . $lang_global['loginsubmit'], ' Client IP:' . NV_CLIENT_IP, 0);
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

    $redirect = NV_BASE_SITEURL . NV_ADMINDIR;
    if (!empty($admin_login_redirect) and str_starts_with($admin_login_redirect, NV_NAME_VARIABLE . '=siteinfo&' . NV_OP_VARIABLE . '=notification')) {
        $redirect = $admin_login_redirect;
        $nv_Request->unset_request('admin_login_redirect', 'session');
    }

    $nv_Request->unset_request('admin_pre', 'session');
    nv_info_die($global_config['site_description'], $lang_global['site_info'], $lang_global['admin_loginsuccessfully'] . " \n <meta http-equiv=\"refresh\" content=\"3;URL=" . $redirect . '" />');
    exit();
}

$dir_template = '';
if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/login.tpl')) {
    $dir_template = NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system';
} else {
    $dir_template = NV_ROOTDIR . '/themes/admin_default/system';
    $global_config['admin_theme'] = 'admin_default';
}

$xtpl = new XTemplate('login.tpl', $dir_template);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('CHARSET', $global_config['site_charset']);
$xtpl->assign('SITE_NAME', $global_config['site_name']);
$xtpl->assign('ADMIN_THEME', $global_config['admin_theme']);
$xtpl->assign('SITELANG', NV_LANG_INTERFACE);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('CHECK_SC', $gfx_chk ? 1 : 0);
$xtpl->assign('SITEURL', $global_config['site_url']);
$xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
$xtpl->assign('NV_TITLEBAR_DEFIS', NV_TITLEBAR_DEFIS);
$xtpl->assign('LOGIN_ERROR_SECURITY', addslashes(sprintf($lang_global['login_error_security'], NV_GFX_NUM)));
$xtpl->assign('LANGINTERFACE', $lang_global['langinterface']);

// Logo của site
if (!empty($global_config['site_logo'])) {
    $xtpl->assign('LOGO', NV_BASE_SITEURL . $global_config['site_logo']);
    $xtpl->parse('main.logo');
}

if (empty($admin_pre_data)) {
    // Form đăng nhập bằng tài khoản (bước 1)
    $xtpl->assign('ADMIN_LOGIN_TITLE', $lang_global['adminlogin']);
    $xtpl->assign('LANGLOSTPASS', $lang_global['lostpass']);
    $xtpl->assign('LINKLOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass');
    $xtpl->assign('V_LOGIN', $nv_username);
    $xtpl->assign('V_PASSWORD', $nv_password);

    // Đa ngôn ngữ giao diện admin
    if ($global_config['lang_multi'] == 1) {
        $_language_array = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/');
        foreach ($_language_array as $lang_i) {
            if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php') and file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/admin_global.php')) {
                $xtpl->assign('LANGOP', NV_BASE_ADMINURL . 'index.php?langinterface=' . $lang_i);
                $xtpl->assign('LANGTITLE', $lang_global['langinterface']);
                $xtpl->assign('SELECTED', ($lang_i == NV_LANG_INTERFACE) ? "selected='selected'" : '');
                $xtpl->assign('LANGVALUE', $language_array[$lang_i]['name']);
                $xtpl->parse('main.pre_form.lang_multi.option');
            }
        }
        $xtpl->parse('main.pre_form.lang_multi');
    }

    // Kích hoạt mã xác nhận
    if ($gfx_chk) {
        if ($captcha_type == 'recaptcha') {
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->assign('RECAPTCHA_SITEKEY', $global_config['recaptcha_sitekey']);
            $xtpl->assign('RECAPTCHA_TYPE', $global_config['recaptcha_type']);

            if ($global_config['recaptcha_ver'] == 2) {
                $xtpl->parse('main.pre_form.recaptcha.recaptcha2');
            } elseif ($global_config['recaptcha_ver'] == 3) {
                $xtpl->parse('main.pre_form.recaptcha.recaptcha3');
            }
            $xtpl->parse('main.pre_form.recaptcha');
        } elseif ($captcha_type == 'captcha') {
            $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
            $xtpl->assign('CAPTCHA_REFR_SRC', NV_STATIC_URL . NV_ASSETS_DIR . '/images/refresh.png');
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
            $xtpl->assign('GFX_NUM', NV_GFX_NUM);
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->parse('main.pre_form.captcha');
        }
    }

    // Kiểm tra site có dùng SSL không
    if (!((isset($_SERVER['HTTPS']) and (strtolower($_SERVER['HTTPS']) == 'on' or $_SERVER['HTTPS'] == '1')) or $_SERVER['SERVER_PORT'] == 443)) {
        $xtpl->parse('main.pre_form.warning_ssl');
    }

    $xtpl->parse('main.pre_form');
} else {
    // Form xác thực hai bước
    $xtpl->assign('ADMIN_PRE_LOGOUT', NV_BASE_ADMINURL . 'index.php?pre_logout=1&amp;checkss=' . NV_CHECK_SESSION);
    $xtpl->assign('ADMIN_LOGIN_TITLE', $lang_global['2teplogin']);
    $xtpl->assign('ADMIN_2STEP_HELLO', sprintf($lang_global['admin_hello_2step'], $admin_pre_data['full_name']));

    if (empty($cfg_2step['opts'])) {
        // Lỗi khi không có phương thức xác thực 2 bước nào
        $error = $lang_global['admin_noopts_2step'];
    } elseif ($cfg_2step['count_active'] < 1) {
        // Yêu cầu kích hoạt tối thiểu 1 phương thức để xác thực
        $xtpl->assign('LANG_CHOOSE', $cfg_2step['count_opts'] > 1 ? $lang_global['admin_mactive_2step_choose1'] : $lang_global['admin_mactive_2step_choose0']);

        foreach ($cfg_2step['opts'] as $opt) {
            if (!$cfg_2step['active_' . $opt]) {
                $xtpl->assign('BTN', [
                    'key' => $opt,
                    'title' => $lang_global['admin_2step_opt_' . $opt],
                    'link' => NV_BASE_ADMINURL . 'index.php?auth=' . $opt
                ]);
                if ($opt != 'code') {
                    $xtpl->parse('main.2step_form.must_activate.loop.popup');
                }
                $xtpl->parse('main.2step_form.must_activate.loop');
            }
        }
        $xtpl->parse('main.2step_form.must_activate');
    } else {
        // Xuất các phương thức để xác thực
        $html = [];
        foreach ($cfg_2step['opts'] as $opt) {
            if ($cfg_2step['active_' . $opt]) {
                if ($opt == 'code') {
                    if (!empty($nv_backupcodepin)) {
                        $xtpl->assign('SHOW_TOTPPIN', ' hidden');
                        $xtpl->assign('SHOW_BACKUPCODEPIN', '');
                    } else {
                        $xtpl->assign('SHOW_TOTPPIN', '');
                        $xtpl->assign('SHOW_BACKUPCODEPIN', ' hidden');
                    }
                } else {
                    $xtpl->assign('URL', NV_BASE_ADMINURL . 'index.php?auth=' . $opt);
                }

                $xtpl->parse($opt);
                $html[$opt] = $xtpl->text($opt);
            }
        }

        $key_default = isset($html[$cfg_2step['default']]) ? $cfg_2step['default'] : key($html);
        $xtpl->assign('HTML_DEFAULT', $html[$key_default]);
        unset($html[$key_default]);
        if (!empty($html)) {
            $xtpl->assign('HTML_OTHER', implode(PHP_EOL, $html));
            $xtpl->parse('main.2step_form.choose_method.others');
        }
        $xtpl->parse('main.2step_form.choose_method');
    }

    $xtpl->parse('main.2step_form');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
} elseif (empty($admin_pre_data)) {
    $xtpl->parse('main.info');
}

$xtpl->parse('main');

include NV_ROOTDIR . '/includes/header.php';
$xtpl->out('main');
include NV_ROOTDIR . '/includes/footer.php';
