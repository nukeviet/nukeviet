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

// Dang nhap thanh vien thi khong duoc truy cap
if (defined('NV_IS_USER') and !defined('ACCESS_ADDUS')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
if (defined('ACCESS_ADDUS')) {
    $page_url .= '/' . $group_id;
}

// Ngung dang ki thanh vien
if (!$global_config['allowuserreg']) {
    $page_title = $nv_Lang->getModule('register');
    $key_words = $module_info['keywords'];

    $contents = user_info_exit($nv_Lang->getModule('no_allowuserreg'));
    $contents .= '<meta http-equiv="refresh" content="5;url=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';

    $canonicalUrl = getCanonicalUrl($page_url);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($global_config['max_user_number'] > 0) {
    $sql = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE;
    if ($global_config['idsite'] > 0) {
        $sql .= ' WHERE idsite = :idsite';
    }
    $stmt = $db->prepare($sql);
    if ($global_config['idsite'] > 0) {
        $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    }
    $stmt->execute();
    $user_number = $stmt->fetchColumn();
    if ($user_number >= $global_config['max_user_number']) {
        if (defined('NV_REGISTER_DOMAIN')) {
            /** @disregard P1011 */
            nv_redirect_location(NV_REGISTER_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
        } else {
            $contents = $nv_Lang->getGlobal('limit_user_number', $global_config['max_user_number']);
            $contents .= '<meta http-equiv="refresh" content="5;url=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';

            $canonicalUrl = getCanonicalUrl($page_url);

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_site_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }
}

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
    if ($nv_Request->isset_request('nv_redirect', 'get') and !empty($nv_redirect)) {
        $page_url .= '&nv_redirect=' . $nv_redirect;
    }
} elseif ($nv_Request->isset_request('sso_redirect', 'get')) {
    $sso_redirect = $nv_Request->get_title('sso_redirect', 'get', '');
    if (!empty($sso_redirect)) {
        $nv_Request->set_Session('sso_redirect_' . $module_data, $sso_redirect);
        $page_url .= '&sso_redirect=' . $sso_redirect;
    }
}

// Chuyen trang dang ki neu tich hop dien dan
if (defined('NV_IS_USER_FORUM')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/register.php';
    exit();
}

/**
 * reg_result()
 *
 * @param mixed $array
 */
function reg_result($array)
{
    global $nv_redirect;

    $array['redirect'] ??= nv_redirect_decrypt($nv_redirect);
    nv_jsonOutput($array);
}

// Cau hoi lay lai mat khau
$data_questions = [];
$sth = $db->prepare('SELECT qid, title FROM ' . NV_MOD_TABLE . '_question WHERE lang = :lang ORDER BY weight ASC');
$sth->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
$sth->execute();

while ($row = $sth->fetch()) {
    $data_questions[$row['qid']] = [
        'qid' => $row['qid'],
        'title' => $row['title']
    ];
}
$sth->closeCursor();

// Captcha
$array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
$gfx_chk = (!empty($array_gfx_chk) and in_array('r', $array_gfx_chk, true)) ? 1 : 0;

$array_register = [];
$array_register['checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op);
$array_register['nv_redirect'] = $nv_redirect;
$checkss = $nv_Request->get_title('checkss', 'post', '');

// Check email address for AJAX
if ($nv_Request->isset_request('checkMail', 'post') and $checkss == $array_register['checkss']) {
    $email = nv_strtolower($nv_Request->get_title('email', 'post', '', 100));
    $check_email = nv_check_email_reg($email);
    if (!empty($check_email)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $check_email
        ]);
    }
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'OK'
    ]);
}

// Check Login for AJAX
if ($nv_Request->isset_request('checkLogin', 'post') and $checkss == $array_register['checkss']) {
    $login = $nv_Request->get_title('login', 'post', '');
    $check_login = nv_check_username_reg($login);
    if (!empty($check_login)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $check_login
        ]);
    }
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'OK'
    ]);
}

if (defined('NV_IS_USER') and defined('ACCESS_ADDUS')) {
    $nv_Lang->setModule('register', $nv_Lang->getModule('add_users'));
    $nv_Lang->setModule('info', $nv_Lang->getModule('info_user'));
}

// Dang ky thong thuong
$page_title = $nv_Lang->getModule('register');
$key_words = $module_info['keywords'];

$array_field_config = [];
$result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY weight ASC');
while ($row_field = $result_field->fetch()) {
    $language = unserialize($row_field['language'], NV_UNSERIALIZE_SAFE);
    $row_field['title'] = (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : $row['field'];
    $row_field['description'] = (isset($language[NV_LANG_DATA])) ? nv_htmlspecialchars($language[NV_LANG_DATA][1]) : '';
    if (!empty($row_field['field_choices'])) {
        $row_field['field_choices'] = unserialize($row_field['field_choices'], NV_UNSERIALIZE_SAFE);
    } elseif (!empty($row_field['sql_choices'])) {
        $row_field['sql_choices'] = explode('|', $row_field['sql_choices']);
        foreach ($row_field['sql_choices'] as $key => $val) {
            if ($key >= 0 and $key <= 3 and !preg_match($global_config['check_module_data'], $val)) {
                $row_field['sql_choices'] = [];
                break;
            } elseif ($key == 4 and !preg_match($global_config['check_module_data'], $val)) {
                $row_field['sql_choices'][$key] = '';
            } elseif ($key == 5 and !in_array($val, ['ASC', 'DESC'], true)) {
                $row_field['sql_choices'][$key] = '';
            }
        }
        $row_field['field_choices'] = [];
        if (!empty($row_field['sql_choices'])) {
            $query = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
            if (!empty($row_field['sql_choices'][4]) and !empty($row_field['sql_choices'][5])) {
                $query .= ' ORDER BY ' . $row_field['sql_choices'][4] . ' ' . $row_field['sql_choices'][5];
            }
            $sth = $db->query($query);
            while ($row = $sth->fetch(PDO::FETCH_NUM)) {
                $row_field['field_choices'][$row[0]] = $row[1];
            }
            $sth->closeCursor();
        }
    }
    $row_field['system'] = $row_field['is_system'];
    $array_field_config[$row_field['field']] = $row_field;
}
$result_field->closeCursor();

if (!defined('NV_EDITOR')) {
    define('NV_EDITOR', 'ckeditor5-classic');
}
require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

$custom_fields = $nv_Request->get_array('custom_fields', 'post');

if ($checkss == $array_register['checkss']) {
    $array_register['first_name'] = $nv_Request->get_title('first_name', 'post', '', 255);
    $array_register['last_name'] = $nv_Request->get_title('last_name', 'post', '', 255);
    $array_register['username'] = $nv_Request->get_title('username', 'post', '');
    $array_register['password'] = $nv_Request->get_title('password', 'post', '');
    $array_register['re_password'] = $nv_Request->get_title('re_password', 'post', '');
    $array_register['email'] = nv_strtolower($nv_Request->get_title('email', 'post', '', 100));
    $array_register['question'] = $nv_Request->get_title('question', 'post', '');
    $array_register['answer'] = $nv_Request->get_title('answer', 'post', '', 255);
    $array_register['agreecheck'] = $nv_Request->get_int('agreecheck', 'post', 0);
    $array_register['gender'] = $nv_Request->get_title('gender', 'post', '');
    $array_register['birthday'] = $nv_Request->get_title('birthday', 'post', '');
    $array_register['sig'] = $nv_Request->get_title('sig', 'post', '');

    $custom_fields['first_name'] = $array_register['first_name'];
    $custom_fields['last_name'] = $array_register['last_name'];
    $custom_fields['gender'] = $array_register['gender'];
    $custom_fields['birthday'] = $array_register['birthday'];
    $custom_fields['sig'] = $array_register['sig'];
    $custom_fields['question'] = $array_register['question'];
    $custom_fields['answer'] = $array_register['answer'];

    unset($nv_seccode);
    if ($module_captcha == 'recaptcha') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    } elseif ($module_captcha == 'turnstile') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng turnstile
        $nv_seccode = $nv_Request->get_title('cf-turnstile-response', 'post', '');
    } elseif ($module_captcha == 'captcha') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    // Kiểm tra tính hợp lệ của captcha nhập vào
    $check_seccode = ($gfx_chk and isset($nv_seccode)) ? nv_capcha_txt($nv_seccode, $module_captcha) : true;

    if (!$check_seccode) {
        reg_result([
            'status' => 'error',
            'input' => '',
            'mess' => ($module_captcha == 'recaptcha') ? $nv_Lang->getGlobal('securitycodeincorrect1') : (($module_captcha == 'turnstile') ? $nv_Lang->getGlobal('securitycodeincorrect2') : $nv_Lang->getGlobal('securitycodeincorrect'))
        ]);
    }

    if ((($check_login = nv_check_username_reg($array_register['username']))) != '') {
        reg_result([
            'status' => 'error',
            'input' => 'username',
            'mess' => $check_login
        ]);
    }

    if (($check_email = nv_check_email_reg($array_register['email'])) != '') {
        reg_result([
            'status' => 'error',
            'input' => 'email',
            'mess' => $check_email
        ]);
    }

    if (($check_pass = nv_check_valid_pass($array_register['password'], $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
        reg_result([
            'status' => 'error',
            'input' => 'password',
            'mess' => $check_pass
        ]);
    }

    if ($array_register['password'] != $array_register['re_password']) {
        reg_result([
            'status' => 'error',
            'input' => 're_password',
            'mess' => $nv_Lang->getGlobal('passwordsincorrect')
        ]);
    }

    if (empty($array_register['agreecheck']) and !defined('ACCESS_ADDUS')) {
        reg_result([
            'status' => 'error',
            'input' => 'agreecheck',
            'mess' => $nv_Lang->getGlobal('agreecheck_empty')
        ]);
    }

    // Kiểm tra trường dữ liệu
    $query_field = [
        'userid' => 0
    ];
    $userid = 0;
    $check = fieldsCheck($custom_fields, $array_register, $query_field, $valid_field);
    if ($check['status'] == 'error') {
        nv_jsonOutput($check);
    }

    $password = $crypt->hash_password($array_register['password'], $global_config['hashprefix']);
    $checknum = nv_genpass(10);
    $checknum = md5($checknum);
    if (empty($array_register['first_name'])) {
        $array_register['first_name'] = $array_register['username'];
    }

    if (!defined('ACCESS_ADDUS') and ($global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3)) {
        // Kích hoạt qua email hoặc nguời quản trị kích hoạt
        $sth = $db->prepare("INSERT INTO " . NV_MOD_TABLE . "_reg (
            username, md5username, password, email, first_name, last_name, gender, birthday, sig, regdate, question, answer, checknum, users_info, idsite
        ) VALUES (
            :username, :md5username, :password, :email, :first_name, :last_name, :gender, :birthday, :sig, :regdate, :question, :answer, :checknum, :users_info, :idsite
        )");

        $sth->bindValue(':username', $array_register['username'], PDO::PARAM_STR);
        $sth->bindValue(':md5username', nv_md5safe($array_register['username']), PDO::PARAM_STR);
        $sth->bindValue(':password', $password, PDO::PARAM_STR);
        $sth->bindValue(':email', $array_register['email'], PDO::PARAM_STR);
        $sth->bindValue(':first_name', $array_register['first_name'], PDO::PARAM_STR);
        $sth->bindValue(':last_name', $array_register['last_name'], PDO::PARAM_STR);
        $sth->bindValue(':gender', $array_register['gender'], PDO::PARAM_STR);
        $sth->bindValue(':birthday', (int) $array_register['birthday'], PDO::PARAM_INT);
        $sth->bindValue(':sig', $array_register['sig'], PDO::PARAM_STR);
        $sth->bindValue(':regdate', NV_CURRENTTIME, PDO::PARAM_INT);
        $sth->bindValue(':question', $array_register['question'], PDO::PARAM_STR);
        $sth->bindValue(':answer', $array_register['answer'], PDO::PARAM_STR);
        $sth->bindValue(':checknum', $checknum, PDO::PARAM_STR);
        $sth->bindValue(':users_info', json_encode($query_field, NV_JSON_ENCODE), PDO::PARAM_STR);
        $sth->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
        $sth->execute();
        $userid = $db->lastInsertId();

        if (!$userid) {
            reg_result([
                'status' => 'error',
                'input' => '',
                'mess' => $nv_Lang->getModule('err_no_save_account')
            ]);
        } else {
            if ($global_config['allowuserreg'] == 2) {

                $send_data = [[
                    'to' => $array_register['email'],
                    'data' => [
                        'first_name' => $array_register['first_name'],
                        'last_name' => $array_register['last_name'],
                        'username' => $array_register['username'],
                        'email' => $array_register['email'],
                        'gender' => $array_register['gender'],
                        'active_deadline' => NV_CURRENTTIME + ($global_users_config['register_active_time'] ?? 86400),
                        'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=active&userid=' . $userid . '&checknum=' . $checknum, NV_MY_DOMAIN)
                    ]
                ]];
                $send = nv_sendmail_from_template([$module_name, Emails::REGISTER_ACTIVE], $send_data);
                if ($send) {
                    $info = $nv_Lang->getModule('account_active_mess');
                } else {
                    $info = $nv_Lang->getModule('account_active_mess_error_mail');

                    // Thêm thông báo vào hệ thống
                    $access_admin = unserialize($global_users_config['access_admin'], NV_UNSERIALIZE_SAFE);
                    if (isset($access_admin['access_waiting'])) {
                        for ($i = 1; $i <= 3; ++$i) {
                            if (!empty($access_admin['access_waiting'][$i])) {
                                $admin_view_allowed = $i == 3 ? 0 : $i;
                                nv_insert_notification($module_name, 'send_active_link_fail', [
                                    'title' => $array_register['username']
                                ], $userid, 0, 0, 1, $admin_view_allowed, 1);
                            }
                        }
                    }
                }
            } else {
                $info = $nv_Lang->getModule('account_register_to_admin');
                nv_insert_notification($module_name, 'contact_new', [
                    'title' => $array_register['username']
                ], $userid, 0, 0, 1);
            }

            $array = [
                'status' => 'ok',
                'redirect' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true),
                'mess' => $info,
                'timeout' => 0
            ];
            if (defined('SSO_REGISTER_SECRET')) {
                $sso_redirect_users = $nv_Request->get_title('sso_redirect_' . $module_data, 'session', '');
                $sso_redirect_users = NukeViet\Client\Sso::decrypt($sso_redirect_users);
                if (!empty($sso_redirect_users)) {
                    $array['redirect'] = $sso_redirect_users;
                }
            }
            nv_jsonOutput($array);
        }
    } else {
        // Không cần kích hoạt
        $sth = $db->prepare("INSERT INTO " . NV_MOD_TABLE . " (
            group_id, username, md5username, password, email, first_name, last_name, gender, photo, birthday, sig, regdate,
            question, answer, passlostkey, view_mail, remember, in_groups,
            active, checknum, last_login, last_ip, last_agent, last_openid, idsite,
            pass_creation_time, pass_reset_request, email_creation_time, email_verification_time, active_obj
        ) VALUES (
            :group_id, :username, :md5username, :password, :email, :first_name, :last_name, :gender, '', :birthday, :sig, :regdate,
            :question, :answer, '', 0, 1, :in_groups,
            1, '', 0, '', '', '', :idsite,
            :pass_creation_time, 0, :email_creation_time, -1, 'SYSTEM'
        )");

        $group_id_new = (defined('ACCESS_ADDUS') ? $group_id : ($global_users_config['active_group_newusers'] ? 7 : 4));
        $sth->bindValue(':group_id', $group_id_new, PDO::PARAM_INT);
        $sth->bindValue(':username', $array_register['username'], PDO::PARAM_STR);
        $sth->bindValue(':md5username', nv_md5safe($array_register['username']), PDO::PARAM_STR);
        $sth->bindValue(':password', $password, PDO::PARAM_STR);
        $sth->bindValue(':email', $array_register['email'], PDO::PARAM_STR);
        $sth->bindValue(':first_name', $array_register['first_name'], PDO::PARAM_STR);
        $sth->bindValue(':last_name', $array_register['last_name'], PDO::PARAM_STR);
        $sth->bindValue(':gender', $array_register['gender'], PDO::PARAM_STR);
        $sth->bindValue(':birthday', (int) $array_register['birthday'], PDO::PARAM_INT);
        $sth->bindValue(':sig', $array_register['sig'], PDO::PARAM_STR);
        $sth->bindValue(':regdate', NV_CURRENTTIME, PDO::PARAM_INT);
        $sth->bindValue(':question', $array_register['question'], PDO::PARAM_STR);
        $sth->bindValue(':answer', $array_register['answer'], PDO::PARAM_STR);
        $sth->bindValue(':in_groups', (string) $group_id_new, PDO::PARAM_STR);
        $sth->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
        $sth->bindValue(':pass_creation_time', NV_CURRENTTIME, PDO::PARAM_INT);
        $sth->bindValue(':email_creation_time', NV_CURRENTTIME, PDO::PARAM_INT);
        $sth->execute();
        $userid = $db->lastInsertId();

        if (!$userid) {
            reg_result([
                'status' => 'error',
                'input' => '',
                'mess' => $nv_Lang->getModule('err_no_save_account')
            ]);
        } else {
            $query_field['userid'] = $userid;
            userInfoTabDb($query_field);

            if (defined('ACCESS_ADDUS')) {
                $stmt = $db->prepare("INSERT INTO " . NV_MOD_TABLE . "_groups_users (
                    group_id, userid, is_leader, approved, data, time_requested, time_approved
                ) VALUES (
                    :group_id, :userid, 0, 1, '0', :time_requested, :time_approved
                )");
                $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
                $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
                $stmt->bindValue(':time_requested', NV_CURRENTTIME, PDO::PARAM_INT);
                $stmt->bindValue(':time_approved', NV_CURRENTTIME, PDO::PARAM_INT);
                $stmt->execute();
            }

            $group_id_update = defined('ACCESS_ADDUS') ? $group_id : ($global_users_config['active_group_newusers'] ? 7 : 4);
            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers + 1 WHERE group_id = :group_id');
            $stmt->bindValue(':group_id', $group_id_update, PDO::PARAM_INT);
            $stmt->execute();

            // Gửi email thông báo
            $send_data = [[
                'to' => $array_register['email'],
                'data' => [
                    'first_name' => $array_register['first_name'],
                    'last_name' => $array_register['last_name'],
                    'username' => $array_register['username'],
                    'email' => $array_register['email'],
                    'gender' => $array_register['gender'],
                    'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN),
                    'lang' => NV_LANG_INTERFACE
                ]
            ]];
            nv_sendmail_template_async([$module_name, Emails::NEW_INFO], $send_data, NV_LANG_INTERFACE);

            if (defined('ACCESS_ADDUS')) {
                // Trưởng nhóm thêm thành viên thì chuyển về trang nhóm
                $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=groups/' . $group_id;
            } elseif (!empty($global_config['auto_login_after_reg'])) {
                // Auto login, chuyển hướng về trang trước
                $array_user = [
                    'userid' => $userid,
                    'username' => $array_register['username'],
                    'last_agent' => '',
                    'last_ip' => '',
                    'last_login' => 0,
                    'last_openid' => '',
                    'language' => ''
                ];
                validUserLog($array_user, 1);

                $nv_redirect = nv_redirect_decrypt($nv_redirect);
                $url = !empty($nv_redirect) ? $nv_redirect : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
            } else {
                // Tài khoản sẵn sàng login thì chuyển hướng về trang đăng nhập
                $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login';
                if (!empty($nv_redirect)) {
                    $url .= '&nv_redirect=' . $nv_redirect;
                }
            }
            $nv_Cache->delMod($module_name);

            // Callback sau khi đăng ký
            if (nv_function_exists('nv_user_register_callback')) {
                /** @disregard P1010 */
                nv_user_register_callback($userid); // phpcs:ignore
            }

            reg_result([
                'status' => 'ok',
                'redirect' => nv_url_rewrite($url, true),
                'mess' => $nv_Lang->getModule('register_ok')
            ]);
        }
    }
}

if ($nv_Request->isset_request('get_usage_terms', 'post')) {
    include NV_ROOTDIR . '/includes/header.php';
    echo $global_users_config['siteterms_' . NV_LANG_DATA];
    include NV_ROOTDIR . '/includes/footer.php';
}

$contents = user_register($gfx_chk, $array_register['checkss'], $data_questions, $array_field_config, $custom_fields, $group_id);

$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
