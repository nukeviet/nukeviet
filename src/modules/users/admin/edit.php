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

$stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$allow = false;

$stmt = $db->prepare('SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = :userid');
$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$rowlev = $stmt->fetch();
$stmt->closeCursor();
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
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('user_safemode.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $contents = $tpl->fetch('user_safemode.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Yêu cầu đăng nhập lại
if ($nv_Request->isset_request('forcedrelogin', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    forcedrelogin($userid);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getModule('admin_forcedrelogin_note')
    ]);
}

// Hủy yêu cầu xóa dữ liệu
if ($nv_Request->isset_request('canceldeletion', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, 'admin_cancel_request_deletion', 'User ID:' . $userid, $admin_info['admin_id']);

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET delete_at = 0 WHERE userid = :userid');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_info SET deletion_checkcode = \'\' WHERE userid = :userid');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_deleted WHERE userid = :userid AND request_source = \'\'');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();

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
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    if ($nv_Request->isset_request('type', 'post')) {
        $type = $nv_Request->get_int('type', 'post', 0);
        if ($type == 1 or $type == 2) {
            try {
                $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET pass_reset_request = :type, last_update = :last_update WHERE userid = :userid');
                $stmt->bindValue(':type', $type, PDO::PARAM_INT);
                $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
                $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Throwable $e) {
                trigger_error($e);
            }

            nv_insert_logs(NV_LANG_DATA, $module_name, 'Change password request', 'userid ' . $userid, $admin_info['admin_id']);

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
        nv_jsonOutput(['status' => 'OK', 'mess' => $nv_Lang->getModule('pass_reset_request_sent')]);
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
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    if ($nv_Request->isset_request('type', 'post')) {
        $type = $nv_Request->get_int('type', 'post', 0);
        if ($type == 1 or $type == 2) {
            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET email_reset_request = :type, last_update = :last_update WHERE userid = :userid');
            $stmt->bindValue(':type', $type, PDO::PARAM_INT);
            $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt->execute();

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
        nv_jsonOutput(['status' => 'OK', 'mess' => $nv_Lang->getModule('email_reset_request_sent')]);
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
$stmt = $db->prepare('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid = :userid');
$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
while ($row_gru = $stmt->fetch()) {
    $array_old_groups[] = $row_gru['group_id'];
}
$stmt->closeCursor();
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
if ($nv_Request->isset_request('confirm', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error Session, Please close the browser and try again'
        ]);
    }
    $_user['username'] = $nv_Request->get_title('username', 'post', '');
    $_user['email'] = nv_strtolower($nv_Request->get_title('email', 'post', ''));
    if ($access_passus) {
        $_user['password1'] = $nv_Request->get_title('password1', 'post', '');
        $_user['password2'] = $nv_Request->get_title('password2', 'post', '');
    } else {
        $_user['password1'] = $_user['password2'] = '';
    }
    $_user['pass_reset_request'] = $nv_Request->get_int('pass_reset_request', 'post', 0);
    $_user['email_reset_request'] = $nv_Request->get_int('email_reset_request', 'post', 0);
    $_user['question'] = $nv_Request->get_title('question', 'post', '', 255);
    $_user['answer'] = $nv_Request->get_title('answer', 'post', '', 255);
    $_user['first_name'] = $nv_Request->get_title('first_name', 'post', '', 255);
    $_user['last_name'] = $nv_Request->get_title('last_name', 'post', '', 255);
    $_user['gender'] = nv_substr($nv_Request->get_title('gender', 'post', ''), 0, 1);
    $_user['photo'] = $nv_Request->get_title('photo', 'post', '', 255);
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

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . ' WHERE userid != :userid AND (username LIKE :username OR md5username = :md5username)');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindValue(':username', $_user['username'], PDO::PARAM_STR);
    $stmt->bindValue(':md5username', nv_md5safe($_user['username']), PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $stmt->closeCursor();
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'username',
            'mess' => $nv_Lang->getModule('edit_error_username_exist')
        ]);
    }
    $stmt->closeCursor();

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_reg WHERE username LIKE :username OR md5username = :md5username');
    $stmt->bindValue(':username', $_user['username'], PDO::PARAM_STR);
    $stmt->bindValue(':md5username', nv_md5safe($_user['username']), PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $stmt->closeCursor();
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'username',
            'mess' => $nv_Lang->getModule('edit_error_username_exist')
        ]);
    }
    $stmt->closeCursor();

    $error_xemail = nv_check_valid_email($_user['email'], true);
    if ($error_xemail[0] != '') {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $error_xemail[0]
        ]);
    }
    $_user['email'] = $error_xemail[1];

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . ' WHERE userid != :userid AND email = :email');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindValue(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $stmt->closeCursor();
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $nv_Lang->getModule('edit_error_email_exist')
        ]);
    }
    $stmt->closeCursor();

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_reg WHERE email = :email');
    $stmt->bindValue(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $stmt->closeCursor();
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $nv_Lang->getModule('edit_error_email_exist')
        ]);
    }
    $stmt->closeCursor();

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_openid WHERE userid != :userid AND email = :email');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindValue(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $stmt->closeCursor();
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $nv_Lang->getModule('edit_error_email_exist')
        ]);
    }
    $stmt->closeCursor();

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
                $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers + 1 WHERE group_id = 4');
                $stmt->execute();
            } catch (Throwable $e) {
                trigger_error($e);
            }
            try {
                $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers - 1 WHERE group_id = 7');
                $stmt->execute();
            } catch (Throwable $e) {
                trigger_error($e);
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

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET
        group_id = :group_id,
        username = :username,
        md5username = :md5username,
        password = :password,
        email = :email,
        first_name = :first_name,
        last_name = :last_name,
        gender = :gender,
        photo = :photo,
        birthday = :birthday,
        sig = :sig,
        question = :question,
        answer = :answer,
        view_mail = :view_mail,
        in_groups = :in_groups,
        pass_creation_time = :pass_creation_time,
        pass_reset_request = :pass_reset_request,
        email_reset_request = :email_reset_request,
        email_verification_time = :email_verification_time,
        last_update = :last_update
    WHERE userid = :userid');

    $stmt->bindValue(':group_id', $_user['in_groups_default'], PDO::PARAM_INT);
    $stmt->bindValue(':username', $_user['username'], PDO::PARAM_STR);
    $stmt->bindValue(':md5username', nv_md5safe($_user['username']), PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->bindValue(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->bindValue(':first_name', $_user['first_name'], PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $_user['last_name'], PDO::PARAM_STR);
    $stmt->bindValue(':gender', $_user['gender'], PDO::PARAM_STR);
    $stmt->bindValue(':photo', nv_unhtmlspecialchars($_user['photo']), PDO::PARAM_STR);
    $stmt->bindValue(':birthday', is_string($_user['birthday']) ? nv_d2u_post($_user['birthday']) : $_user['birthday'], PDO::PARAM_INT);
    $stmt->bindValue(':sig', $_user['sig'], PDO::PARAM_STR);
    $stmt->bindValue(':question', $_user['question'], PDO::PARAM_STR);
    $stmt->bindValue(':answer', $_user['answer'], PDO::PARAM_STR);
    $stmt->bindValue(':view_mail', $_user['view_mail'], PDO::PARAM_INT);
    $stmt->bindValue(':in_groups', implode(',', $in_groups), PDO::PARAM_STR);
    $stmt->bindValue(':pass_creation_time', $pass_creation_time, PDO::PARAM_INT);
    $stmt->bindValue(':pass_reset_request', $_user['pass_reset_request'], PDO::PARAM_INT);
    $stmt->bindValue(':email_reset_request', $_user['email_reset_request'], PDO::PARAM_INT);
    $stmt->bindValue(':email_verification_time', $email_verification_time, PDO::PARAM_INT);
    $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();

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

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_user', 'userid ' . $userid, $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);

    $redirect = $nv_redirect != '' ? nv_redirect_decrypt($nv_redirect) . '&userid=' . $userid : '';
    if (empty($redirect)) {
        $redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
    }
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => '',
        'redirect' => $redirect
    ]);
}

$_user = $row;
$_user['password1'] = $_user['password2'] = '';
$_user['in_groups'] = $array_old_groups;

$stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid = :userid');
$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$custom_fields_db = $stmt->fetch() ?: [];
$stmt->closeCursor();

$custom_fields_db['first_name'] = $_user['first_name'];
$custom_fields_db['last_name'] = $_user['last_name'];
$custom_fields_db['gender'] = $_user['gender'];
$custom_fields_db['birthday'] = $_user['birthday'];
$custom_fields_db['sig'] = $_user['sig'];
$custom_fields_db['question'] = $_user['question'];
$custom_fields_db['answer'] = $_user['answer'];

// Chuẩn bị thông tin ảnh đại diện
$photo_info = ['src' => '', 'width' => 0, 'height' => 0];
if (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . $row['photo'])) {
    $size = @getimagesize(NV_ROOTDIR . '/' . $row['photo']);
    $photo_info = [
        'src' => NV_BASE_SITEURL . $row['photo'],
        'height' => $size[1] ?? 0,
        'width' => $size[0] ?? 0
    ];
}

// Chuẩn bị danh sách nhóm
$groups_for_tpl = [];
$group_exists = false;
if (!empty($groups_list)) {
    foreach ($groups_list as $group_id => $grtl) {
        if ($group_id == 4 or $group_id == 5 or $group_id == 6) {
            continue;
        }
        // Bỏ qua nhóm admin khi tài khoản không phải quản trị
        if ($group_id < 9 and empty($rowlev)) {
            continue;
        }
        $groups_for_tpl[] = [
            'id' => $group_id,
            'title' => $grtl,
            'checked' => in_array((int) $group_id, $_user['in_groups'], true),
            'is_default' => in_array((int) $group_id, $_user['in_groups'], true) && $_user['group_id'] == $group_id,
            'disabled' => $group_id < 9
        ];
        $group_exists = true;
    }
}

// Danh sách câu hỏi bảo mật
$data_questions = [];
$stmt = $db->prepare('SELECT qid, title FROM ' . NV_MOD_TABLE . '_question WHERE lang = :lang ORDER BY weight ASC');
$stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
$stmt->execute();
while ($row_q = $stmt->fetch()) {
    $data_questions[$row_q['qid']] = $row_q['title'];
}
$stmt->closeCursor();

// Chuẩn bị các trường hệ thống
$system_fields = [];
$have_name_field = false;
foreach ($array_field_config as $row_f) {
    if (!empty($row_f['system'])) {
        $row_f['value'] = isset($custom_fields_db[$row_f['field']]) ? $custom_fields_db[$row_f['field']] : get_value_by_lang($row_f['default_value']);

        if ($row_f['field'] == 'birthday') {
            $row_f['value'] = nv_u2d_post($row_f['value']);
        } elseif ($row_f['field'] == 'sig') {
            $row_f['value'] = nv_htmlspecialchars(nv_br2nl($row_f['value']));
        }

        $row_f['required'] = (bool) $row_f['required'];

        if ($row_f['field'] == 'gender') {
            $gender_options = [];
            foreach ($global_array_genders as $g) {
                $g['selected'] = ($row_f['value'] == $g['key']);
                $gender_options[] = $g;
            }
            $row_f['gender_options'] = $gender_options;
        }

        if ($row_f['field'] == 'question') {
            $row_f['questions'] = array_values($data_questions);
        }

        if ($row_f['field'] == 'first_name' || $row_f['field'] == 'last_name') {
            $have_name_field = true;
        }

        $system_fields[$row_f['field']] = $row_f;
    }
}

// Chuẩn bị các trường tùy biến
$custom_fields_data = [];
$have_custom_fields = false;
foreach ($array_field_config as $row_f) {
    if (empty($row_f['system'])) {
        $row_f['value'] = isset($custom_fields_db[$row_f['field']]) ? $custom_fields_db[$row_f['field']] : get_value_by_lang($row_f['default_value']);
        $row_f['required'] = (bool) $row_f['required'];

        if (!empty($row_f['field_choices'])) {
            $choices_prepared = [];
            if ($row_f['field_type'] == 'checkbox') {
                $valuecheckbox = (!empty($row_f['value'])) ? explode(',', $row_f['value']) : [];
                $number = 0;
                foreach ($row_f['field_choices'] as $key => $value) {
                    $choices_prepared[] = [
                        'id' => $row_f['fid'] . '_' . $number++,
                        'key' => $key,
                        'selected' => in_array((string) $key, $valuecheckbox, true),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } elseif ($row_f['field_type'] == 'radio') {
                $number = 0;
                foreach ($row_f['field_choices'] as $key => $value) {
                    $choices_prepared[] = [
                        'id' => $row_f['fid'] . '_' . $number++,
                        'key' => $key,
                        'selected' => ($key == $row_f['value']),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } elseif ($row_f['field_type'] == 'multiselect') {
                $valueselect = (!empty($row_f['value'])) ? explode(',', $row_f['value']) : [];
                foreach ($row_f['field_choices'] as $key => $value) {
                    $choices_prepared[] = [
                        'key' => $key,
                        'selected' => in_array((string) $key, $valueselect, true),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } else {
                foreach ($row_f['field_choices'] as $key => $value) {
                    $choices_prepared[] = [
                        'key' => $key,
                        'selected' => ($key == $row_f['value']),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            }
            $row_f['choices_prepared'] = $choices_prepared;
        }

        if ($row_f['field_type'] == 'date') {
            $row_f['value'] = nv_u2d_post($row_f['value']);
        } elseif ($row_f['field_type'] == 'textarea') {
            $row_f['value'] = nv_htmlspecialchars(nv_br2nl($row_f['value']));
        } elseif ($row_f['field_type'] == 'editor') {
            $row_f['value'] = htmlspecialchars(nv_editor_br2nl($row_f['value']));
            if (defined('NV_EDITOR') && nv_function_exists('nv_aleditor')) {
                $array_tmp = explode('@', $row_f['class']);
                $row_f['editor_html'] = nv_aleditor('custom_fields[' . $row_f['field'] . ']', $array_tmp[0], $array_tmp[1], $row_f['value']);
                $row_f['field_type_render'] = 'editor';
            } else {
                $row_f['class'] = '';
                $row_f['field_type_render'] = 'textarea';
            }
        } elseif ($row_f['field_type'] == 'file') {
            $filelist = !empty($row_f['value']) ? explode(',', $row_f['value']) : [];
            $file_items = [];
            foreach ($filelist as $file_item) {
                $assign = file_type_name($file_item);
                $assign['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;userfile=' . $file_item;
                $file_items[] = $assign;
            }
            $row_f['file_items'] = $file_items;
            $row_f['limited_values'] = !empty($row_f['limited_values']) ? json_decode($row_f['limited_values'], true) : [];
            $row_f['fileaccept'] = !empty($row_f['limited_values']['mime']) ? '.' . implode(',.', $row_f['limited_values']['mime']) : '';
            $row_f['filemaxsize'] = $row_f['limited_values']['file_max_size'] ?? 0;
            $row_f['filemaxsize_format'] = nv_convertfromBytes($row_f['limited_values']['file_max_size'] ?? 0);
            $row_f['filemaxnum'] = $row_f['limited_values']['maxnum'] ?? 0;
            $row_f['csrf'] = csrf_create($module_name . '_field_' . $row_f['field']);
            $row_f['url_module'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
            $row_f['widthlimit'] = image_size_info($row_f['limited_values']['widthlimit'] ?? '', 'width');
            $row_f['heightlimit'] = image_size_info($row_f['limited_values']['heightlimit'] ?? '', 'height');
            $row_f['addfile_disabled'] = !empty($row_f['limited_values']['maxnum']) && count($filelist) >= $row_f['limited_values']['maxnum'];
        }

        if (!isset($row_f['field_type_render'])) {
            $row_f['field_type_render'] = $row_f['field_type'];
        }

        $custom_fields_data[] = $row_f;
        $have_custom_fields = true;
    }
}

// Options cho pass_reset_request
$pass_reset_options = [];
for ($i = 0; $i <= 2; ++$i) {
    $pass_reset_options[] = [
        'num' => $i,
        'title' => $nv_Lang->getModule('pass_reset_request' . $i),
        'selected' => $_user['pass_reset_request'] == $i
    ];
}

// Options cho email_reset_request
$email_reset_options = [];
for ($i = 0; $i <= 2; ++$i) {
    $email_reset_options[] = [
        'num' => $i,
        'title' => $nv_Lang->getModule('email_reset_request' . $i),
        'selected' => $_user['email_reset_request'] == $i
    ];
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('user_edit.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('DATA', $_user);
$tpl->assign('NV_REDIRECT', $nv_redirect);
$tpl->assign('IS_FORUM', defined('NV_IS_USER_FORUM'));
$tpl->assign('PHOTO', $photo_info);
$tpl->assign('AVATAR_UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('SYSTEM_FIELDS', $system_fields);
$tpl->assign('CUSTOM_FIELDS', $custom_fields_data);
$tpl->assign('HAVE_CUSTOM_FIELDS', $have_custom_fields);
$tpl->assign('HAVE_NAME_FIELD', $have_name_field);
$tpl->assign('GROUPS', $groups_for_tpl);
$tpl->assign('GROUP_EXISTS', $group_exists);
$tpl->assign('SHOW_BTN_CLEAR', (count($array_old_groups) > 0 and !in_array(7, $array_old_groups_all, true)));
$tpl->assign('IS_NEW_USER', in_array(7, $array_old_groups_all, true));
$tpl->assign('SHOW_CHANGEPASS', $access_passus);
$tpl->assign('PASS_RESET_OPTIONS', $pass_reset_options);
$tpl->assign('EMAIL_RESET_OPTIONS', $email_reset_options);
$contents = $tpl->fetch('user_edit.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
