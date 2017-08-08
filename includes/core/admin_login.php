<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/30/2009 1:31
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_admin_checkip()) {
    nv_info_die($global_config['site_description'], $lang_global['site_info'], sprintf($lang_global['admin_ipincorrect'], NV_CLIENT_IP) . '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />');
}

if (! nv_admin_checkfirewall()) {
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

/**
 * validUserLog()
 *
 * @param mixed $array_user
 * @return
 */
function validUserLog($array_user)
{
    global $db, $global_config, $nv_Request, $client_info;

    $checknum = md5(nv_genpass(10));
    $user = array(
        'userid' => $array_user['userid'],
        'current_mode' => 0,
        'checknum' => $checknum,
        'checkhash' => md5($array_user['userid'] . $checknum . $global_config['sitekey'] . $client_info['browser']['key']),
        'current_agent' => NV_USER_AGENT,
        'last_agent' => $array_user['last_agent'],
        'current_ip' => NV_CLIENT_IP,
        'last_ip' => $array_user['last_ip'],
        'current_login' => NV_CURRENTTIME,
        'last_login' => intval($array_user['last_login']),
        'last_openid' => $array_user['last_openid'],
        'current_openid' => ''
    );

    $user = serialize($user);

    $stmt = $db->prepare("UPDATE " . NV_USERS_GLOBALTABLE . " SET
		checknum = :checknum,
		last_login = " . NV_CURRENTTIME . ",
		last_ip = :last_ip,
		last_agent = :last_agent,
		last_openid = '',
		remember = 1
		WHERE userid=" . $array_user['userid']);

    $stmt->bindValue(':checknum', $checknum, PDO::PARAM_STR);
    $stmt->bindValue(':last_ip', NV_CLIENT_IP, PDO::PARAM_STR);
    $stmt->bindValue(':last_agent', NV_USER_AGENT, PDO::PARAM_STR);
    $stmt->execute();

    $nv_Request->set_Cookie('nvloginhash', $user, NV_LIVE_COOKIE_TIME);
}

$blocker = new NukeViet\Core\Blocker(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs', NV_CLIENT_IP);
$rules = array($global_config['login_number_tracking'], $global_config['login_time_tracking'], $global_config['login_time_ban']);
$blocker->trackLogin($rules);

$error = '';
$login = '';
$login_step = 1;
$array_gfx_chk = array(1, 5, 6, 7);
if (in_array($global_config['gfx_chk'], $array_gfx_chk)) {
    $global_config['gfx_chk'] = 1;
} else {
    $global_config['gfx_chk'] = 0;
}

$admin_login_redirect = $nv_Request->get_string('admin_login_redirect', 'session', '');

if ($nv_Request->isset_request('nv_login,nv_password', 'post') and $nv_Request->get_title('checkss', 'post') == NV_CHECK_SESSION) {
    $nv_username = $nv_Request->get_title('nv_login', 'post', '', 1);
    $nv_password = $nv_Request->get_title('nv_password', 'post', '');

    $nv_totppin = $nv_Request->get_title('nv_totppin', 'post', '');
    $nv_backupcodepin = $nv_Request->get_title('nv_backupcodepin', 'post', '');

    $captcha_require = ($global_config['gfx_chk'] == 1 and $nv_Request->get_title('admin_dismiss_captcha', 'session', '') != md5($nv_username));

    if ($global_config['captcha_type'] == 2) {
        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    } else {
        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    if (empty($nv_username)) {
        $error = $lang_global['username_empty'];
    } elseif ($global_config['login_number_tracking'] and $blocker->is_blocklogin($nv_username)) {
        $error = sprintf($lang_global['userlogin_blocked'], $global_config['login_number_tracking'], nv_date('H:i d/m/Y', $blocker->login_block_end));
    } elseif (empty($nv_password)) {
        $error = $lang_global['password_empty'];
    } elseif ($captcha_require and ! nv_capcha_txt($nv_seccode)) {
        $error = ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect']);
    } else {
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

        if (nv_check_valid_email($nv_username) == '') {
            $sql = 't2.email =' . $db->quote($nv_username);
            $login_email = true;
        } else {
            $sql = "t2.md5username ='" . nv_md5safe($nv_username) . "'";
            $login_email = false;
        }

        $sql = 'SELECT t1.admin_id admin_id, t1.lev admin_lev, t1.last_agent admin_last_agent, t1.last_ip admin_last_ip, t1.last_login admin_last_login,
        t2.userid, t2.last_agent, t2.last_ip, t2.last_login, t2.last_openid, t2.username, t2.email, t2.password, t2.active2step, t2.in_groups, t2.secretkey
        FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1, ' . NV_USERS_GLOBALTABLE . ' t2
        WHERE t1.admin_id=t2.userid AND ' . $sql . ' AND t1.lev!=0 AND t1.is_suspend=0 AND t2.active=1';

        $row = $db->query($sql)->fetch();
        $error = '';

        if (empty($row) or !((($row['username'] == $nv_username and $login_email == false) or ($row['email'] == $nv_username and $login_email == true)) and $crypt->validate_password($nv_password, $row['password']))) {
            nv_insert_logs(NV_LANG_DATA, 'login', '[' . $nv_username . '] ' . $lang_global['loginsubmit'] . ' ' . $lang_global['fail'], ' Client IP:' . NV_CLIENT_IP, 0);
            $blocker->set_loginFailed($nv_username, NV_CURRENTTIME);
            $error = $lang_global['loginincorrect'];
        } else {
            $row['admin_lev'] = intval($row['admin_lev']);
            $step2_isvalid = true;

            // Check 2-step login
            $_2step_require = false;
            if (empty($row['active2step'])) {
                $_2step_require = in_array($global_config['two_step_verification'], array(1, 3));
                if (!$_2step_require) {
                    // Thêm tự động nhóm của hệ thống
                    $manual_groups = array(3);
                    if ($row['admin_lev'] == 1 or $row['admin_lev'] == 2) {
                        $manual_groups[] = 2;
                    }
                    if ($row['admin_lev'] == 1 and $global_config['idsite'] == 0) {
                        $manual_groups[] = 1;
                    }
                    $_2step_require = nv_user_groups($row['in_groups'], true, $manual_groups);
                    $_2step_require = $_2step_require[1];
                }
            }
            if ($_2step_require) {
                $url_setup2step = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification&amp;' . NV_OP_VARIABLE . '=setup&amp;nv_redirect=' . nv_redirect_encrypt(NV_BASE_ADMINURL);
                $error = '<a href="' . $url_setup2step . '">' . $lang_global['2teplogin_require'] . '</a>';
                validUserLog($row);
                $tokend_key = md5($row['username'] . '_' . NV_CURRENTTIME . '_users_confirm_pass_' . NV_CHECK_SESSION);
                $tokend = md5('users_confirm_pass_' . NV_CHECK_SESSION);
                $nv_Request->set_Session($tokend_key, $tokend);
                $login_step = 3;
                $captcha_require = 0;
            } elseif (!empty($row['active2step'])) {
                $step2_isvalid = false;
                $login_step = 2;
                $GoogleAuthenticator = new \NukeViet\Core\GoogleAuthenticator();

                if (!empty($nv_totppin)) {
                    if (!$GoogleAuthenticator->verifyOpt($row['secretkey'], $nv_totppin)) {
                        $error = $lang_global['2teplogin_error_opt'];
                    } else {
                        $step2_isvalid = true;
                    }
                }

                if (!empty($nv_backupcodepin)) {
                    $nv_backupcodepin = nv_strtolower($nv_backupcodepin);
                    $sth = $db->prepare('SELECT code FROM ' . NV_USERS_GLOBALTABLE . '_backupcodes WHERE is_used=0 AND code=:code AND userid=' . $row['userid']);
                    $sth->bindParam(':code', $nv_backupcodepin, PDO::PARAM_STR);
                    $sth->execute();

                    if ($sth->rowCount() != 1) {
                        $error = $lang_global['2teplogin_error_backup'];
                    } else {
                        $code = $sth->fetchColumn();
                        $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . "_backupcodes SET is_used=1, time_used=" . NV_CURRENTTIME . " WHERE code='" . $code . "' AND userid=" . $row['userid']);
                        $step2_isvalid = true;
                    }
                }

                $captcha_require = 0;
                $nv_Request->set_Session('admin_dismiss_captcha', md5($nv_username));
            }

            if (empty($error) and $step2_isvalid) {
                if (! defined('ADMIN_LOGIN_MODE')) {
                    define('ADMIN_LOGIN_MODE', 3);
                }
                if (ADMIN_LOGIN_MODE == 2 and ! in_array($row['admin_lev'], array(1, 2))) {
                    $error = $lang_global['admin_access_denied2'];
                } elseif (ADMIN_LOGIN_MODE == 1 and $row['admin_lev'] != 1) {
                    $error = $lang_global['admin_access_denied1'];
                } else {
                    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $nv_username . '] ' . $lang_global['loginsubmit'], ' Client IP:' . NV_CLIENT_IP, 0);
                    $admin_id = intval($row['admin_id']);
                    $checknum = md5(nv_genpass(10));
                    $array_admin = array(
                        'admin_id' => $admin_id,
                        'checknum' => $checknum,
                        'current_agent' => NV_USER_AGENT,
                        'last_agent' => $row['admin_last_agent'],
                        'current_ip' => NV_CLIENT_IP,
                        'last_ip' => $row['admin_last_ip'],
                        'current_login' => NV_CURRENTTIME,
                        'last_login' => intval($row['admin_last_login'])
                    );
                    $admin_serialize = serialize($array_admin);

                    $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . ' SET check_num = :check_num, last_login = ' . NV_CURRENTTIME . ', last_ip = :last_ip, last_agent = :last_agent WHERE admin_id=' . $admin_id);
                    $sth->bindValue(':check_num', $checknum, PDO::PARAM_STR);
                    $sth->bindValue(':last_ip', NV_CLIENT_IP, PDO::PARAM_STR);
                    $sth->bindValue(':last_agent', NV_USER_AGENT, PDO::PARAM_STR);
                    $sth->execute();

                    $nv_Request->set_Session('admin', $admin_serialize);
                    $nv_Request->set_Session('online', '1|' . NV_CURRENTTIME . '|' . NV_CURRENTTIME . '|0');

                    if ($global_config['lang_multi']) {
                        $sql = 'SELECT setup FROM ' . $db_config['prefix'] . '_setup_language WHERE lang=' . $db->quote(NV_LANG_INTERFACE);
                        $setup = $db->query($sql)->fetchColumn();
                        if ($setup) {
                            $nv_Request->set_Cookie('data_lang', NV_LANG_INTERFACE, NV_LIVE_COOKIE_TIME);
                        }
                    }

                    define('NV_IS_ADMIN', true);
                    $blocker->reset_trackLogin($nv_username);

                    $redirect = NV_BASE_SITEURL . NV_ADMINDIR;
                    if (! empty($admin_login_redirect) and strpos($admin_login_redirect, NV_NAME_VARIABLE . '=siteinfo&' . NV_OP_VARIABLE . '=notification') == 0) {
                        $redirect = $admin_login_redirect;
                        $nv_Request->unset_request('admin_login_redirect', 'session');
                    }

                    $nv_Request->unset_request('admin_dismiss_captcha', 'session');
                    nv_info_die($global_config['site_description'], $lang_global['site_info'], $lang_global['admin_loginsuccessfully'] . " \n <meta http-equiv=\"refresh\" content=\"3;URL=" . $redirect . "\" />");
                    die();
                }
            }
        }
    }
} else {
    if (empty($admin_login_redirect)) {
        $nv_Request->set_Session('admin_login_redirect', $nv_Request->request_uri);
    }
    $nv_username = $nv_password = $nv_totppin = $nv_backupcodepin = '';
    $captcha_require = ($global_config['gfx_chk'] == 1);
    $nv_Request->unset_request('admin_dismiss_captcha', 'session');
}

if (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_global.php')) {
    require_once NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_global.php';
} elseif (file_exists(NV_ROOTDIR . '/includes/language/en/admin_global.php')) {
    require_once NV_ROOTDIR . '/includes/language/en/admin_global.php';
}

$info = (! empty($error)) ? '<div class="error">' . $error . '</div>' : '<div class="normal">' . $lang_global['adminlogininfo'] . '</div>';
$size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);

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
$xtpl->assign('CHECK_SC', ($global_config['gfx_chk'] == 1) ? 1 : 0);
$xtpl->assign('LOGIN_INFO', $info);
$xtpl->assign('SITEURL', $global_config['site_url']);
$xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
$xtpl->assign('NV_TITLEBAR_DEFIS', NV_TITLEBAR_DEFIS);

$xtpl->assign('LOGIN_ERROR_SECURITY', addslashes(sprintf($lang_global['login_error_security'], NV_GFX_NUM)));

$xtpl->assign('V_LOGIN', $nv_username);
$xtpl->assign('V_PASSWORD', $nv_password);
$xtpl->assign('LANGINTERFACE', $lang_global['langinterface']);

if ($login_step == 1) {
    $xtpl->assign('SHOW_STEP1', '');
    $xtpl->assign('SHOW_STEP2', ' hidden');
    $xtpl->assign('SHOW_LANG', '');
} elseif ($login_step == 3) {
    $xtpl->assign('SHOW_STEP1', ' hidden');
    $xtpl->assign('SHOW_STEP2', ' hidden');
    $xtpl->assign('SHOW_LANG', ' hidden');
    $xtpl->assign('SHOW_SUBMIT', ' hidden');
    $xtpl->assign('SHOW_LOSTPASS', ' hidden');
} else {
    $xtpl->assign('SHOW_STEP1', ' hidden');
    $xtpl->assign('SHOW_STEP2', '');
    $xtpl->assign('SHOW_LANG', '');
}

if (!empty($nv_totppin) or empty($nv_backupcodepin)) {
    $xtpl->assign('SHOW_OPT', '');
    $xtpl->assign('SHOW_CODE', ' class="hidden"');
} else {
    $xtpl->assign('SHOW_OPT', ' class="hidden"');
    $xtpl->assign('SHOW_CODE', '');
}

if (isset($size[1])) {
    if ($size[0] > 490) {
        $size[1] = ceil(490 * $size[1] / $size[0]);
        $size[0] = 490;
    }
    $xtpl->assign('LOGO', NV_BASE_SITEURL . $global_config['site_logo']);
    $xtpl->assign('WIDTH', $size[0]);
    $xtpl->assign('HEIGHT', $size[1]);

    if (isset($size['mime']) and $size['mime'] == 'application/x-shockwave-flash') {
        $xtpl->parse('main.swf');
    } else {
        $xtpl->parse('main.image');
    }
}

$xtpl->assign('LANGLOSTPASS', $lang_global['lostpass']);
$xtpl->assign('LINKLOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass');

if ($captcha_require) {
    if ($global_config['captcha_type'] == 2) {
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
        $xtpl->assign('RECAPTCHA_SITEKEY', $global_config['recaptcha_sitekey']);
        $xtpl->assign('RECAPTCHA_TYPE', $global_config['recaptcha_type']);
        $xtpl->parse('main.recaptcha');
    } else {
        $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
        $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/refresh.png');
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
        $xtpl->assign('GFX_NUM', NV_GFX_NUM);
        $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
        $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
        $xtpl->parse('main.captcha');
    }
}

if ($global_config['lang_multi'] == 1) {
    $_language_array = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/');
    foreach ($_language_array as $lang_i) {
        if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php') and file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/admin_global.php')) {
            $xtpl->assign('LANGOP', NV_BASE_ADMINURL . 'index.php?langinterface=' . $lang_i);
            $xtpl->assign('LANGTITLE', $lang_global['langinterface']);
            $xtpl->assign('SELECTED', ($lang_i == NV_LANG_INTERFACE) ? "selected='selected'" : "");
            $xtpl->assign('LANGVALUE', $language_array[$lang_i]['name']);
            $xtpl->parse('main.lang_multi.option');
        }
    }
    $xtpl->parse('main.lang_multi');
}
$xtpl->parse('main');

include NV_ROOTDIR . '/includes/header.php';
$xtpl->out('main');
include NV_ROOTDIR . '/includes/footer.php';
