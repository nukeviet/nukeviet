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

$user_info = [];

if (defined('NV_IS_ADMIN')) {
    $user_info = $admin_info;

    if (empty($user_info['active2step']) and (in_array((int) $global_config['two_step_verification'], [2, 3], true) or !empty($user_info['2step_require']))) {
        /*
         * Khi hệ thống yêu cầu xác thực hai bước ở ngoài site hoặc tất cả
         * mà admin đã login chưa kích hoạt phương thức xác nhận code thì chỉ xem
         * như là tài khoản user mới xác nhận 1 bước
         */
        define('NV_IS_1STEP_USER', true);
    } else {
        define('NV_IS_USER', true);
    }
} elseif (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/is_user.php';
} else {
    if (!empty($user_cookie)) {
        if ($global_config['allowuserlogin']) {
            if (isset($user_cookie['userid']) and isset($user_cookie['checknum']) and isset($user_cookie['checkhash'])) {
                $user_cookie['userid'] = (int) ($user_cookie['userid']);
                if ($user_cookie['checkhash'] === md5($user_cookie['userid'] . $user_cookie['checknum'] . $global_config['sitekey'] . $client_info['clid'])) {
                    $_sql = 'SELECT a.userid, a.group_id, a.username, a.email, a.first_name, a.last_name, a.gender, a.photo, a.birthday, a.regdate,
                        a.view_mail, a.remember, a.in_groups, a.active2step, a.pref_2fa, a.checknum, a.password, a.question, a.answer, a.safemode, a.pass_creation_time,
                        a.pass_reset_request, a.email_creation_time, a.email_reset_request, a.email_verification_time, a.last_agent, a.last_ip,
                        a.last_login, a.last_openid, a.language, a.delete_at,
                        b.mode current_mode, b.agent AS current_agent, b.ip AS current_ip, b.logtime AS current_login, b.mode_extra AS current_mode_extra
                        FROM ' . NV_USERS_GLOBALTABLE . ' AS a INNER JOIN ' . NV_USERS_GLOBALTABLE . '_login AS b ON a.userid=b.userid
                        WHERE a.userid = ' . $user_cookie['userid'] . ' AND b.clid=' . $db->quote($client_info['clid']) . ' AND a.active=1';

                    $user_info = $db->query($_sql)->fetch();
                    if (!empty($user_info)) {
                        if (
                            ($user_cookie['checknum'] === $user_info['checknum'] and $user_cookie['loginhash'] === substr($user_info['checknum'], 5, 8)) // checknum
                            and isset($user_cookie['current_agent']) and ($user_cookie['current_agent'] === $user_info['current_agent']) // user_agent
                            and isset($user_cookie['current_ip']) and ($user_cookie['current_ip'] === $user_info['current_ip']) // current IP
                            and isset($user_cookie['current_login']) and ($user_cookie['current_login'] === (int) ($user_info['current_login'])) // current login
                        ) {
                            $checknum = true;
                        } else {
                            $checknum = false;
                        }

                        if ($checknum) {
                            $user_info['full_name'] = nv_show_name_user($user_info['first_name'], $user_info['last_name'], $user_info['username']);
                            $user_info['avata'] = !empty($user_info['photo']) ? NV_STATIC_URL . $user_info['photo'] : '';
                            $check_in_groups = nv_user_groups($user_info['in_groups'], true);
                            $user_info['in_groups'] = $check_in_groups[0];
                            $user_info['2step_require'] = $check_in_groups[1] || !empty($user_cookie['admin_prelogin']);
                            $user_info['prev_login'] = (int) ($user_cookie['prev_login']);
                            $user_info['prev_agent'] = $user_cookie['prev_agent'];
                            $user_info['prev_ip'] = $user_cookie['prev_ip'];
                            $user_info['prev_openid'] = $user_cookie['prev_openid'];
                            $user_info['st_login'] = !empty($user_info['password']) ? true : false;
                            if ($global_config['allowquestion']) {
                                $user_info['valid_question'] = (!empty($user_info['question']) and !empty($user_info['answer'])) ? true : false;
                            } else {
                                $user_info['valid_question'] = true;
                            }

                            if (!empty($user_info['current_mode_extra'])) {
                                if (in_array((int) $user_info['current_mode'], [2, 3, 4], true)) {
                                    $user_info['current_openid'] = $user_info['current_mode_extra'];

                                    $sth = $db->prepare('SELECT openid, id, email FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE opid= :current_openid');
                                    $sth->bindParam(':current_openid', $user_info['current_openid'], PDO::PARAM_STR);
                                    $sth->execute();
                                    $row = $sth->fetch();

                                    if (empty($row)) {
                                        $user_info = [];
                                    } else {
                                        $user_info['openid_server'] = $row['openid'];
                                        $user_info['openid_id'] = $row['id'];
                                        $user_info['openid_email'] = $row['email'];
                                    }
                                } elseif ($user_info['current_mode'] == 6) {
                                    $user_info['current_passkey'] = $user_info['current_mode_extra'];
                                }
                            }

                            unset($user_info['checknum'], $user_info['password'], $user_info['question'], $user_info['answer'], $check_in_groups, $user_info['current_mode_extra'], $row);
                        } else {
                            $user_info = [];
                        }
                    }
                }
            }

            if (!empty($user_info) and isset($user_info['userid']) and $user_info['userid'] > 0) {
                if (empty($user_info['active2step']) and (in_array((int) $global_config['two_step_verification'], [2, 3], true) or !empty($user_info['2step_require']))) {
                    define('NV_IS_1STEP_USER', true);
                } else {
                    define('NV_IS_USER', true);
                }
            } else {
                if (!empty($user_cookie['userid'])) {
                    $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_login WHERE userid=' . $user_cookie['userid'] . ' AND clid=' . $db->quote($client_info['clid']));
                }
                NukeViet\Core\User::unset_userlogin_hash();
                $user_info = [];
            }
        } else {
            if (!empty($user_cookie['userid'])) {
                $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_login WHERE userid=' . $user_cookie['userid'] . ' AND clid=' . $db->quote($client_info['clid']));
            }
            NukeViet\Core\User::unset_userlogin_hash();
            $user_info = [];
        }
    }

    unset($_sql);
}

// Tài khoản chờ xóa luôn chuyển hướng đến trang thông báo xóa tài khoản
if (!empty($user_info) and !empty($user_info['delete_at'])) {
    $module_name = $nv_Request->get_title(NV_NAME_VARIABLE, 'get', '');
    $op  = $nv_Request->get_title(NV_OP_VARIABLE, 'get', '');
    $allow_ops = ['datadeletion', 'logout', 'verify-password'];

    if ($module_name !== 'users' or !in_array($op, $allow_ops, true)) {
        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=datadeletion&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']);
        nv_redirect_location($url);
    }
}
