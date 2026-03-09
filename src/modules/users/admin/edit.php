<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use NukeViet\Module\users\Shared\Emails;

$page_title = $nv_Lang->getModule('edit_title');

$userid = $nv_Request->get_int('userid', 'get', 0);

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
}

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$allow = false;

$sql = 'SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
$rowlev = $db->query($sql)->fetch();
if (empty($rowlev)) {
    $allow = true;
} else {
    if ($admin_info['admin_id'] == $userid or $admin_info['level'] < $rowlev['lev']) {
        $allow = true;
    }
}

if ($global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite'] and $admin_info['admin_id'] != $userid) {
    $allow = false;
}

if (!$allow) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Thêm vào menutop
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_2step&amp;userid=' . $row['userid']] = $nv_Lang->getModule('user_2step_mamager');
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_oauth&amp;userid=' . $row['userid']] = $nv_Lang->getModule('user_openid_mamager');

if ($admin_info['admin_id'] == $userid and $admin_info['safemode'] == 1) {
    $xtpl = new XTemplate('user_safemode.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('SAFEMODE_DEACT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/safeshow');
    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Yêu cầu đăng nhập lại
if ($nv_Request->isset_request('forcedrelogin', 'post')) {
    forcedrelogin($userid);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getModule('admin_forcedrelogin_note')
    ]);
}

// Hủy yêu cầu xóa dữ liệu
if ($nv_Request->isset_request('canceldeletion', 'post')) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'admin_cancel_request_deletion', 'User ID:' . $userid, $admin_info['admin_id']);

    $sql = "UPDATE " . NV_MOD_TABLE . " SET delete_at=0 WHERE userid=" . $userid;
    $db->query($sql);

    $sql = "UPDATE " . NV_MOD_TABLE . "_info SET deletion_checkcode='' WHERE userid=" . $userid;
    $db->query($sql);

    $sql = "DELETE FROM " . NV_MOD_TABLE . "_deleted WHERE userid=" . $userid . " AND request_source=''";
    $db->query($sql);

    // Gửi email thông báo hủy yêu cầu xóa tài khoản
    $lang = $row['language'] ?: NV_LANG_INTERFACE;
    $link_change_pass = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password';
    $link_security = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=security-privacy';

    $send_data = [[
        'to' => $row['email'],
        'data' => [
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'username' => $row['username'],
            'email' => $row['email'],
            'gender' => $row['gender'],
            'pass_link' => urlRewriteWithDomain($link_change_pass, NV_MY_DOMAIN),
            'link' => urlRewriteWithDomain($link_security, NV_MY_DOMAIN),
            'lang' => $lang
        ]
    ]];
    nv_sendmail_template_async([$module_name, Emails::DELETE_ACCOUNT_CANCEL], $send_data, $lang);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getModule('delacc_cancel_success')
    ]);
}

// Yêu cầu thay đổi mật khẩu
if ($nv_Request->isset_request('psr', 'post')) {
    if ($nv_Request->isset_request('type', 'post')) {
        $type = $nv_Request->get_int('type', 'post', 0);
        if ($type == 1 or $type == 2) {
            try {
                $db->query('UPDATE ' . NV_MOD_TABLE . ' SET pass_reset_request = ' . $type . ', last_update = ' . NV_CURRENTTIME . ' WHERE userid=' . $userid);
            } catch (PDOException $e) {
                trigger_error(print_r($e, true));
            }

            nv_insert_logs(NV_LANG_DATA, $module_name, 'Change password request', 'userid ' . $userid, $admin_info['userid']);

            $maillang = NV_LANG_INTERFACE;
            if (!empty($row['language']) and in_array($row['language'], $global_config['setup_langs'], true)) {
                if ($row['language'] != NV_LANG_INTERFACE) {
                    $maillang = $row['language'];
                }
            } elseif (NV_LANG_DATA != NV_LANG_INTERFACE) {
                $maillang = NV_LANG_DATA;
            }

            $send_data = [[
                'to' => $row['email'],
                'data' => [
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'gender' => $row['gender'],
                    'lang' => $maillang,
                    'pass_reset' => $type,
                    'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN)
                ]
            ]];
            nv_sendmail_template_async([$module_name, Emails::REQUEST_RESET_PASS], $send_data, $maillang);
        }
        exit($nv_Lang->getModule('pass_reset_request_sent'));
    }
    nv_jsonOutput([
        'userid' => $userid,
        'username' => $row['username'],
        'pass_creation_time' => nv_datetime_format($row['pass_creation_time']),
        'pass_reset_request' => $nv_Lang->getModule('pass_reset_request' . $row['pass_reset_request'])
    ]);
}

// Yêu cầu thay đổi email
if ($nv_Request->isset_request('esr', 'post')) {
    if ($nv_Request->isset_request('type', 'post')) {
        $type = $nv_Request->get_int('type', 'post', 0);
        if ($type == 1 or $type == 2) {
            $db->query('UPDATE ' . NV_MOD_TABLE . ' SET email_reset_request = ' . $type . ', last_update = ' . NV_CURRENTTIME . ' WHERE userid=' . $userid);

            nv_insert_logs(NV_LANG_DATA, $module_name, 'Change email request', 'userid ' . $userid, $admin_info['userid']);

            $maillang = NV_LANG_INTERFACE;
            if (!empty($row['language']) and in_array($row['language'], $global_config['setup_langs'], true)) {
                if ($row['language'] != NV_LANG_INTERFACE) {
                    $maillang = $row['language'];
                }
            } elseif (NV_LANG_DATA != NV_LANG_INTERFACE) {
                $maillang = NV_LANG_DATA;
            }

            $send_data = [[
                'to' => $row['email'],
                'data' => [
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'gender' => $row['gender'],
                    'lang' => $maillang,
                    'email_reset' => $type,
                    'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN)
                ]
            ]];
            nv_sendmail_template_async([$module_name, Emails::REQUEST_RESET_EMAIL], $send_data, $maillang);
        }
        exit($nv_Lang->getModule('email_reset_request_sent'));
    }
    nv_jsonOutput([
        'userid' => $userid,
        'username' => $row['username'],
        'email_creation_time' => nv_datetime_format($row['email_creation_time']),
        'email_reset_request' => $nv_Lang->getModule('email_reset_request' . $row['email_reset_request'])
    ]);
}

$groups_list = nv_groups_list($module_data);
$array_field_config = nv_get_users_field_config();

// Xác định nhóm thành viên, từ bảng groups_users và từ cả trường group_id, in_groups cho chuẩn xác
$array_old_groups = [];
$result_gru = $db->query('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid);
while ($row_gru = $result_gru->fetch()) {
    $array_old_groups[] = $row_gru['group_id'];
}
$row['in_groups'] = empty($row['in_groups']) ? [] : explode(',', $row['in_groups']);
$array_old_groups[] = $row['group_id'];
$array_old_groups_all = array_unique(array_filter(array_map('trim', array_merge_recursive($array_old_groups, $row['in_groups']))));
$array_old_groups_all = array_map('intval', $array_old_groups_all);
$array_old_groups = array_diff($array_old_groups_all, [4, 7]);
$array_old_groups = array_map('intval', $array_old_groups);

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$access_passus = (isset($access_admin['access_passus'][$admin_info['level']]) and $access_admin['access_passus'][$admin_info['level']] == 1) ? true : false;
$_user = $custom_fields = [];
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $userid);
if ($nv_Request->isset_request('confirm', 'post')) {
    if ($checkss != $nv_Request->get_string('checkss', 'post')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error Session, Please close the browser and try again'
        ]);
    }
    $_user['username'] = $nv_Request->get_title('username', 'post', '', 1);
    $_user['email'] = nv_strtolower($nv_Request->get_title('email', 'post', '', 1));
    if ($access_passus) {
        $_user['password1'] = $nv_Request->get_title('password1', 'post', '', 0);
        $_user['password2'] = $nv_Request->get_title('password2', 'post', '', 0);
    } else {
        $_user['password1'] = $_user['password2'] = '';
    }
    $_user['pass_reset_request'] = $nv_Request->get_int('pass_reset_request', 'post', 0);
    $_user['email_reset_request'] = $nv_Request->get_int('email_reset_request', 'post', 0);
    $_user['question'] = nv_substr($nv_Request->get_title('question', 'post', '', 1), 0, 255);
    $_user['answer'] = nv_substr($nv_Request->get_title('answer', 'post', '', 1), 0, 255);
    $_user['first_name'] = nv_substr($nv_Request->get_title('first_name', 'post', '', 1), 0, 255);
    $_user['last_name'] = nv_substr($nv_Request->get_title('last_name', 'post', '', 1), 0, 255);
    $_user['gender'] = nv_substr($nv_Request->get_title('gender', 'post', '', 1), 0, 1);
    $_user['photo'] = nv_substr($nv_Request->get_title('photo', 'post', '', 1), 0, 255);
    $_user['view_mail'] = $nv_Request->get_int('view_mail', 'post', 0);
    $_user['sig'] = $nv_Request->get_textarea('sig', '', NV_ALLOWED_HTML_TAGS);
    $_user['birthday'] = $nv_Request->get_title('birthday', 'post');
    $_user['in_groups'] = $nv_Request->get_typed_array('group', 'post', 'int');
    $_user['in_groups_default'] = $nv_Request->get_int('group_default', 'post', 0);
    $_user['delpic'] = $nv_Request->get_int('delpic', 'post', 0);
    $_user['is_official'] = $nv_Request->get_int('is_official', 'post', 0);
    $_user['adduser_email'] = $nv_Request->get_int('adduser_email', 'post', 0);

    $custom_fields = $nv_Request->get_array('custom_fields', 'post');
    $custom_fields['first_name'] = $_user['first_name'];
    $custom_fields['last_name'] = $_user['last_name'];
    $custom_fields['gender'] = $_user['gender'];
    $custom_fields['birthday'] = $_user['birthday'];
    $custom_fields['sig'] = $_user['sig'];
    $custom_fields['question'] = $_user['question'];
    $custom_fields['answer'] = $_user['answer'];

    if ($_user['username'] != $row['username'] and ($error_username = nv_check_valid_login($_user['username'], $global_config['nv_unickmax'], $global_config['nv_unickmin'])) != '') {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'username',
            'mess' => $error_username
        ]);
    }

    if ("'" . $_user['username'] . "'" != $db->quote($_user['username'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'username',
            'mess' => $nv_Lang->getModule('account_deny_name', '<strong>' . $_user['username'] . '</strong>')
        ]);
    }

    if ($db->query('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE userid!=' . $userid . ' AND (username LIKE ' . $db->quote($_user['username']) . ' OR md5username=' . $db->quote(nv_md5safe($_user['username'])) . ')')->fetchColumn()) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'username',
            'mess' => $nv_Lang->getModule('edit_error_username_exist')
        ]);
    }

    if ($db->query('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE username LIKE ' . $db->quote($_user['username']) . ' OR md5username=' . $db->quote(nv_md5safe($_user['username'])))->fetchColumn()) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'username',
            'mess' => $nv_Lang->getModule('edit_error_username_exist')
        ]);
    }

    $error_xemail = nv_check_valid_email($_user['email'], true);
    if ($error_xemail[0] != '') {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $error_xemail[0]
        ]);
    }
    $_user['email'] = $error_xemail[1];

    if ($db->query('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE userid!=' . $userid . ' AND email=' . $db->quote($_user['email']))->fetchColumn()) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $nv_Lang->getModule('edit_error_email_exist')
        ]);
    }

    if ($db->query('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE email=' . $db->quote($_user['email']))->fetchColumn()) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $nv_Lang->getModule('edit_error_email_exist')
        ]);
    }

    if ($db->query('SELECT userid FROM ' . NV_MOD_TABLE . '_openid WHERE userid!=' . $userid . ' AND email=' . $db->quote($_user['email']))->fetchColumn()) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $nv_Lang->getModule('edit_error_email_exist')
        ]);
    }

    if (!empty($_user['password1']) and ($check_pass = nv_check_valid_pass($_user['password1'], $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'password1',
            'mess' => $check_pass
        ]);
    }

    if (!empty($_user['password1']) and $_user['password1'] != $_user['password2']) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'password2',
            'mess' => $nv_Lang->getModule('edit_error_password')
        ]);
    }

    // Kiểm tra các trường dữ liệu tùy biến + Hệ thống
    $query_field = [];
    $valid_field = [];
    if (!empty($array_field_config)) {
        $check = fieldsCheck($custom_fields, $_user, $query_field, $valid_field);
        if ($check['status'] == 'error') {
            nv_jsonOutput($check);
        }
    }

    if (!empty($_user['password1'])) {
        if (!empty($row['password'])) {
            oldPassSave($userid, $row['password'], $row['pass_creation_time']);
        }
        $password = $crypt->hash_password($_user['password1'], $global_config['hashprefix']);
        $pass_creation_time = NV_CURRENTTIME;
    } else {
        $password = $row['password'];
        $pass_creation_time = (int) $row['pass_creation_time'];
    }

    $in_groups = [];
    // Khi là thành viên mới thì không thể chọn thuộc các nhóm khác
    if (!in_array(7, $array_old_groups_all, true) or $_user['is_official']) {
        foreach (array_keys($groups_list) as $_group_id) {
            if (!empty($rowlev) and $_group_id < 4 and in_array((int) $_group_id, $array_old_groups, true)) {
                // Thêm vào các nhóm quản trị khi tài khoản này là quản trị
                $in_groups[] = $_group_id;
            } elseif ($_group_id > 9 and in_array((int) $_group_id, array_map('intval', $_user['in_groups']), true)) {
                // Các nhóm tài khoản trong phần quản lý nhóm thành viên
                $in_groups[] = $_group_id;
            }
        }
    }

    // Xóa khỏi bảng groups_users
    $in_groups_del = array_diff($array_old_groups, $in_groups);
    if (!empty($in_groups_del)) {
        foreach ($in_groups_del as $gid) {
            nv_groups_del_user($gid, $userid, $module_data);
        }
    }

    // Thêm vào bảng groups_users
    $in_groups_add = array_diff($in_groups, $array_old_groups);
    if (!empty($in_groups_add)) {
        foreach ($in_groups_add as $gid) {
            nv_groups_add_user($gid, $userid, 1, $module_data);
        }
    }

    // Kiểm tra nhóm thành viên mặc định phải thuộc các nhóm đã chọn
    if (!empty($_user['in_groups_default']) and !in_array((int) $_user['in_groups_default'], array_map('intval', $in_groups), true)) {
        $_user['in_groups_default'] = 0;
    }

    // Khi không chọn nhóm mặc định thì tự xác định nhóm mặc định theo từng bước
    if (empty($_user['in_groups_default'])) {
        if (in_array(7, $array_old_groups_all, true) and !$_user['is_official']) {
            // Tài khoản đang là tài khoản mới và không cho làm tài khoản chính thức => Mặc định là tài khoản mới
            $_user['in_groups_default'] = 7;
        } else {
            // Mặc định khi không có nhóm nào sẽ là tài khoản chính thức
            $_user['in_groups_default'] = 4;
        }
    }

    if (in_array(7, $array_old_groups_all, true)) {
        if (!$_user['is_official']) {
            $_user['in_groups_default'] = 7;
            $in_groups[] = 7;
        } else {
            $in_groups[] = 4;
            try {
                $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=4');
            } catch (PDOException $e) {
                trigger_error(print_r($e, true));
            }
            try {
                $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id=7');
            } catch (PDOException $e) {
                trigger_error(print_r($e, true));
            }
        }
    } else {
        $in_groups[] = 4;
    }

    // Check photo
    if (!empty($_user['photo'])) {
        $tmp_photo = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $_user['photo'];

        if (!nv_is_file($tmp_photo, NV_TEMP_DIR)) {
            $_user['photo'] = '';
        } else {
            $new_photo_name = $_user['photo'];
            $new_photo_path = NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_upload . '/';

            $new_photo_name2 = $new_photo_name;
            $i = 1;
            while (file_exists($new_photo_path . $new_photo_name2)) {
                $new_photo_name2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $new_photo_name);
                ++$i;
            }
            $new_photo = $new_photo_path . $new_photo_name2;

            if (nv_copyfile(NV_DOCUMENT_ROOT . $tmp_photo, $new_photo)) {
                $_user['photo'] = substr($new_photo, strlen(NV_ROOTDIR . '/'));
            } else {
                $_user['photo'] = '';
            }

            nv_deletefile(NV_DOCUMENT_ROOT . $tmp_photo);
        }
    }

    if ($_user['delpic'] or !empty($_user['photo'])) {
        // Delete old photo
        if (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . $row['photo'])) {
            nv_deletefile(NV_ROOTDIR . '/' . $row['photo']);
            $row['photo'] = '';
        }
    }

    if (empty($_user['photo'])) {
        $_user['photo'] = $row['photo'];
    }

    if ($row['email'] != $_user['email']) {
        $email_verification_time = 0;
    } else {
        $email_verification_time = $row['email_verification_time'];
    }

    if ($_user['pass_reset_request'] > 2 or $_user['pass_reset_request'] < 0) {
        $_user['pass_reset_request'] = 0;
    }
    if ($_user['email_reset_request'] > 2 or $_user['email_reset_request'] < 0) {
        $_user['email_reset_request'] = 0;
    }

    $db->query('UPDATE ' . NV_MOD_TABLE . ' SET
        group_id=' . $_user['in_groups_default'] . ',
        username=' . $db->quote($_user['username']) . ",
        md5username='" . nv_md5safe($_user['username']) . "',
        password=" . $db->quote($password) . ',
        email=' . $db->quote($_user['email']) . ',
        first_name=' . $db->quote($_user['first_name']) . ',
        last_name=' . $db->quote($_user['last_name']) . ',
        gender=' . $db->quote($_user['gender']) . ',
        photo=' . $db->quote(nv_unhtmlspecialchars($_user['photo'])) . ',
        birthday=' . (int) ($_user['birthday']) . ',
        sig=' . $db->quote($_user['sig']) . ',
        question=' . $db->quote($_user['question']) . ',
        answer=' . $db->quote($_user['answer']) . ',
        view_mail=' . $_user['view_mail'] . ",
        in_groups='" . implode(',', $in_groups) . "',
        pass_creation_time=" . $pass_creation_time . ',
        pass_reset_request=' . $_user['pass_reset_request'] . ',
        email_reset_request=' . $_user['email_reset_request'] . ',
        email_verification_time=' . $email_verification_time . ',
        last_update=' . NV_CURRENTTIME . '
    WHERE userid=' . $userid);

    if (!empty($query_field)) {
        userInfoTabDb($query_field, $userid);
    }

    // Gửi mail thông báo
    if (!empty($_user['adduser_email'])) {
        $maillang = NV_LANG_INTERFACE;
        if (!empty($row['language']) and in_array($row['language'], $global_config['setup_langs'], true)) {
            if ($row['language'] != NV_LANG_INTERFACE) {
                $maillang = $row['language'];
            }
        } elseif (NV_LANG_DATA != NV_LANG_INTERFACE) {
            $maillang = NV_LANG_DATA;
        }

        $send_data = [[
            'to' => $_user['email'],
            'data' => [
                'first_name' => $_user['first_name'],
                'last_name' => $_user['last_name'],
                'username' => $_user['username'],
                'email' => $_user['email'],
                'gender' => $_user['gender'],
                'lang' => $maillang,
                'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN),
                'pass_reset' => $_user['pass_reset_request'],
                'email_reset' => $_user['email_reset_request'],
                'password' => $_user['password1']
            ]
        ]];
        nv_sendmail_template_async([$module_name, Emails::EDIT_BY_ADMIN], $send_data, $maillang);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_user', 'userid ' . $userid, $admin_info['userid']);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'ok',
        'input' => '',
        'admin_add' => 'no',
        'mess' => '',
        'nv_redirect' => $nv_redirect != '' ? nv_redirect_decrypt($nv_redirect) . '&userid=' . $userid : ''
    ]);
}

$_user = $row;
$_user['password1'] = $_user['password2'] = '';
$_user['in_groups'] = $array_old_groups;
$_user['checkss'] = $checkss;

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $userid;
$result = $db->query($sql);
$custom_fields = $result->fetch();

$custom_fields['first_name'] = $_user['first_name'];
$custom_fields['last_name'] = $_user['last_name'];
$custom_fields['gender'] = $_user['gender'];
$custom_fields['birthday'] = $_user['birthday'];
$custom_fields['sig'] = $_user['sig'];
$custom_fields['question'] = $_user['question'];
$custom_fields['answer'] = $_user['answer'];

$_user['view_mail'] = $_user['view_mail'] ? ' checked="checked"' : '';

$groups = [];
if (!empty($groups_list)) {
    foreach ($groups_list as $group_id => $grtl) {
        $groups[] = [
            'id' => $group_id,
            'title' => $grtl,
            'checked' => (in_array((int) $group_id, $_user['in_groups'], true)) ? ' checked="checked"' : '',
            'default' => (in_array((int) $group_id, $_user['in_groups'], true) and $_user['group_id'] == $group_id) ? ' checked="checked"' : '',
            'default_show' => in_array((int) $group_id, $_user['in_groups'], true) ? '' : ' style="display: none;"'
        ];
    }
}

$xtpl = new XTemplate('user_edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('DATA', $_user);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $userid);
$xtpl->assign('AVATAR_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar/opener');
$xtpl->assign('NV_REDIRECT', $nv_redirect);

if (defined('NV_IS_USER_FORUM')) {
    $xtpl->parse('main.is_forum');
} else {
    if (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . $row['photo'])) {
        $size = @getimagesize(NV_ROOTDIR . '/' . $row['photo']);
        $img = [
            'src' => NV_BASE_SITEURL . $row['photo'],
            'height' => $size[1],
            'width' => $size[0]
        ];
        $xtpl->assign('IMG', $img);
        $xtpl->parse('main.edit_user.photo');
    } else {
        $xtpl->parse('main.edit_user.add_photo');
    }

    $xtpl->assign('SHOW_BTN_CLEAR', (count($array_old_groups) > 0 and !in_array(7, $array_old_groups_all, true)) ? '' : ' style="display: none;"');

    $a = 0;
    foreach ($groups as $group) {
        if ($group['id'] != 4 and $group['id'] != 5 and $group['id'] != 6) {
            $group['disabled'] = ($group['id'] < 9) ? 'disabled="disabled"' : '';
            $xtpl->assign('GROUP', $group);
            if ($group['id'] < 9 and empty($rowlev)) {
                continue;
            }
            $xtpl->parse('main.edit_user.group.list');
            ++$a;
        }
    }
    if ($a > 0) {
        if (in_array(7, $array_old_groups_all, true)) {
            $xtpl->parse('main.edit_user.group.hide');
        }
        $xtpl->parse('main.edit_user.group');
    }

    for ($i = 0; $i <= 2; ++$i) {
        $xtpl->assign('PASSRESET', [
            'num' => $i,
            'sel' => $i == $_user['pass_reset_request'] ? ' selected="selected"' : '',
            'title' => $nv_Lang->getModule('pass_reset_request' . $i)
        ]);
        $xtpl->parse('main.edit_user.pass_reset_request');
    }

    for ($i = 0; $i <= 2; ++$i) {
        $xtpl->assign('EMAILRESET', [
            'num' => $i,
            'sel' => $i == $_user['email_reset_request'] ? ' selected="selected"' : '',
            'title' => $nv_Lang->getModule('email_reset_request' . $i)
        ]);
        $xtpl->parse('main.edit_user.email_reset_request');
    }

    if ($access_passus) {
        $xtpl->parse('main.edit_user.changepass');
    }

    if (in_array(7, $array_old_groups_all, true)) {
        $xtpl->parse('main.edit_user.is_official');
    }

    $have_custom_fields = false;
    $have_name_field = false;

    foreach ($array_field_config as $row) {
        $row['value'] = (isset($custom_fields[$row['field']])) ? $custom_fields[$row['field']] : get_value_by_lang($row['default_value']);
        $row['required'] = ($row['required']) ? 'required' : '';

        $xtpl->assign('FIELD', $row);

        // Các trường hệ thống xuất độc lập
        if (!empty($row['system'])) {
            if ($row['field'] == 'birthday') {
                $row['value'] = nv_u2d_post($row['value']);
            } elseif ($row['field'] == 'sig') {
                $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
            }
            $xtpl->assign('FIELD', $row);
            if ($row['field'] == 'first_name' or $row['field'] == 'last_name') {
                $show_key = 'name_show_' . $global_config['name_show'] . '.show_' . $row['field'];
                $have_name_field = true;
            } else {
                $show_key = 'show_' . $row['field'];
            }
            if ($row['required']) {
                $xtpl->parse('main.edit_user.' . $show_key . '.required');
            }
            if ($row['field'] == 'gender') {
                foreach ($global_array_genders as $gender) {
                    $gender['selected'] = $row['value'] == $gender['key'] ? ' selected="selected"' : '';
                    $xtpl->assign('GENDER', $gender);
                    $xtpl->parse('main.edit_user.' . $show_key . '.gender');
                }
            }
            if ($row['for_admin']) {
                $xtpl->parse('main.edit_user.' . $show_key . '.for_admin');
            }
            if ($row['description']) {
                $xtpl->parse('main.edit_user.' . $show_key . '.description');
            }
            $xtpl->parse('main.edit_user.' . $show_key);
        } else {
            if ($row['required']) {
                $xtpl->parse('main.edit_user.field.loop.required');
            }
            if ($row['for_admin']) {
                $xtpl->parse('main.edit_user.field.loop.for_admin');
            }
            if ($row['description']) {
                $xtpl->parse('main.edit_user.field.loop.description');
            }
            if ($row['field_type'] == 'textbox' or $row['field_type'] == 'number') {
                $xtpl->parse('main.edit_user.field.loop.textbox');
            } elseif ($row['field_type'] == 'date') {
                $row['value'] = nv_u2d_post($row['value']);
                $xtpl->assign('FIELD', $row);
                $xtpl->parse('main.edit_user.field.loop.date');
            } elseif ($row['field_type'] == 'textarea') {
                $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
                $xtpl->assign('FIELD', $row);
                $xtpl->parse('main.edit_user.field.loop.textarea');
            } elseif ($row['field_type'] == 'editor') {
                $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
                if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                    $array_tmp = explode('@', $row['class']);
                    $edits = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value']);
                    $xtpl->assign('EDITOR', $edits);
                    $xtpl->parse('main.edit_user.field.loop.editor');
                } else {
                    $row['class'] = '';
                    $xtpl->assign('FIELD', $row);
                    $xtpl->parse('main.edit_user.field.loop.textarea');
                }
            } elseif ($row['field_type'] == 'select') {
                foreach ($row['field_choices'] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES', [
                        'key' => $key,
                        'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                        'value' => get_value_by_lang2($key, $value)
                    ]);
                    $xtpl->parse('main.edit_user.field.loop.select.loop');
                }
                $xtpl->parse('main.edit_user.field.loop.select');
            } elseif ($row['field_type'] == 'radio') {
                $number = 0;
                foreach ($row['field_choices'] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES', [
                        'id' => $row['fid'] . '_' . $number++,
                        'key' => $key,
                        'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                        'value' => get_value_by_lang2($key, $value)
                    ]);
                    $xtpl->parse('main.edit_user.field.loop.radio');
                }
            } elseif ($row['field_type'] == 'checkbox') {
                $number = 0;
                $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                foreach ($row['field_choices'] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES', [
                        'id' => $row['fid'] . '_' . $number++,
                        'key' => $key,
                        'checked' => (in_array((string) $key, $valuecheckbox, true)) ? ' checked="checked"' : '',
                        'value' => get_value_by_lang2($key, $value)
                    ]);
                    $xtpl->parse('main.edit_user.field.loop.checkbox');
                }
            } elseif ($row['field_type'] == 'multiselect') {
                $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                foreach ($row['field_choices'] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES', [
                        'key' => $key,
                        'selected' => (in_array((string) $key, $valueselect, true)) ? ' selected="selected"' : '',
                        'value' => get_value_by_lang2($key, $value)
                    ]);
                    $xtpl->parse('main.edit_user.field.loop.multiselect.loop');
                }
                $xtpl->parse('main.edit_user.field.loop.multiselect');
            } elseif ($row['field_type'] == 'file') {
                $filelist = !empty($row['value']) ? explode(',', $row['value']) : [];
                if (!empty($filelist)) {
                    foreach ($filelist as $file_item) {
                        $assign = file_type_name($file_item);
                        $assign['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;userfile=' . $file_item;
                        $xtpl->assign('FILE_ITEM', $assign);
                        $xtpl->parse('main.edit_user.field.loop.file.loop');
                    }
                }
                $row['limited_values'] = !empty($row['limited_values']) ? json_decode($row['limited_values'], true) : [];
                $xtpl->assign('FILEACCEPT', !empty($row['limited_values']['mime']) ? '.' . implode(',.', $row['limited_values']['mime']) : '');
                $xtpl->assign('FILEMAXSIZE', $row['limited_values']['file_max_size']);
                $xtpl->assign('FILEMAXSIZE_FORMAT', nv_convertfromBytes($row['limited_values']['file_max_size']));
                $xtpl->assign('FILEMAXNUM', $row['limited_values']['maxnum']);
                $xtpl->assign('CSRF', md5(NV_CHECK_SESSION . '_' . $module_name . $row['field']));
                $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
                $widthlimit = image_size_info($row['limited_values']['widthlimit'], 'width');
                $heightlimit = image_size_info($row['limited_values']['heightlimit'], 'height');
                if (!empty($widthlimit)) {
                    $xtpl->assign('WIDTHLIMIT', $widthlimit);
                    $xtpl->parse('main.edit_user.field.loop.file.widthlimit');
                }
                if (!empty($heightlimit)) {
                    $xtpl->assign('HEIGHTLIMIT', $heightlimit);
                    $xtpl->parse('main.edit_user.field.loop.file.heightlimit');
                }
                if (!(empty($row['limited_values']['maxnum']) or (count($filelist) < $row['limited_values']['maxnum']))) {
                    $xtpl->parse('main.edit_user.field.loop.file.addfile');
                }
                $xtpl->parse('main.edit_user.field.loop.file');
            }
            $xtpl->parse('main.edit_user.field.loop');
            $have_custom_fields = true;
        }
    }
    if ($have_name_field) {
        $xtpl->parse('main.edit_user.name_show_' . $global_config['name_show']);
    }
    if ($have_custom_fields) {
        $xtpl->parse('main.edit_user.field');
    }
    $xtpl->parse('main.edit_user');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
