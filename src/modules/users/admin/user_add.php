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

// Tạo mật khẩu ngẫu nhiên
if ($nv_Request->isset_request('nv_genpass', 'post')) {
    $_len = round(($global_config['nv_upassmin'] + $global_config['nv_upassmax']) / 2);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'success',
        'value' => nv_genpass($_len, $global_config['nv_upass_type'])
    ]);
}

$showheader = $nv_Request->get_int('showheader', 'post,get', 1);
$page_title = $nv_Lang->getModule('user_add');

if ($global_config['max_user_number'] > 0) {
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . ($global_config['idsite'] > 0 ? ' WHERE idsite = :idsite' : ''));
    if ($global_config['idsite'] > 0) {
        $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    }
    $stmt->execute();
    $user_number = (int) $stmt->fetchColumn();
    if ($user_number >= $global_config['max_user_number']) {
        $contents = $nv_Lang->getGlobal('limit_user_number', $global_config['max_user_number']);
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, $showheader);
        include NV_ROOTDIR . '/includes/footer.php';
    }
}

$groups_list = nv_groups_list($module_data);
$array_field_config = nv_get_users_field_config();

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$_user = $custom_fields = [];
$userid = 0;
$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
}

if ($nv_Request->isset_request('confirm', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $_user['username'] = $nv_Request->get_title('username', 'post', '');
    $_user['email'] = nv_strtolower($nv_Request->get_title('email', 'post', ''));
    $_user['password1'] = $nv_Request->get_title('password1', 'post', '');
    $_user['password2'] = $nv_Request->get_title('password2', 'post', '');
    $_user['pass_reset_request'] = $nv_Request->get_int('pass_reset_request', 'post', 0);
    $_user['email_reset_request'] = $nv_Request->get_int('email_reset_request', 'post', 0);
    $_user['question'] = $nv_Request->get_title('question', 'post', '', 255);
    $_user['answer'] = $nv_Request->get_title('answer', 'post', '', 255);
    $_user['first_name'] = $nv_Request->get_title('first_name', 'post', '', 255);
    $_user['last_name'] = $nv_Request->get_title('last_name', 'post', '', 255);
    $_user['gender'] = nv_substr($nv_Request->get_title('gender', 'post', ''), 0, 1);
    $_user['view_mail'] = $nv_Request->get_int('view_mail', 'post', 0);
    $_user['sig'] = $nv_Request->get_textarea('sig', '', NV_ALLOWED_HTML_TAGS);
    $_user['birthday'] = $nv_Request->get_title('birthday', 'post');
    $_user['in_groups'] = $nv_Request->get_typed_array('group', 'post', 'int');
    $_user['in_groups_default'] = $nv_Request->get_int('group_default', 'post', 0);
    $_user['photo'] = $nv_Request->get_title('photo', 'post', '', 255);
    $_user['is_official'] = $nv_Request->get_int('is_official', 'post', 0);
    $_user['adduser_email'] = $nv_Request->get_int('adduser_email', 'post', 0);
    $_user['is_email_verified'] = (int) $nv_Request->get_bool('is_email_verified', 'post', false);

    $custom_fields = $nv_Request->get_array('custom_fields', 'post');
    $custom_fields['first_name'] = $_user['first_name'];
    $custom_fields['last_name'] = $_user['last_name'];
    $custom_fields['gender'] = $_user['gender'];
    $custom_fields['birthday'] = $_user['birthday'];
    $custom_fields['sig'] = $_user['sig'];
    $custom_fields['question'] = $_user['question'];
    $custom_fields['answer'] = $_user['answer'];

    $md5username = nv_md5safe($_user['username']);

    if (($error_username = nv_check_valid_login($_user['username'], $global_config['nv_unickmax'], $global_config['nv_unickmin'])) != '') {
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
            'mess' => $nv_Lang->getModule('account_deny_name', $_user['username'])
        ]);
    }

    // Thực hiện câu truy vấn để kiểm tra username đã tồn tại chưa.
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . ' WHERE username LIKE :username OR md5username = :md5username');
    $stmt->bindValue(':username', $_user['username'], PDO::PARAM_STR);
    $stmt->bindValue(':md5username', $md5username, PDO::PARAM_STR);
    $stmt->execute();
    $query_error_username = $stmt->fetchColumn();
    if ($query_error_username) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'username',
            'mess' => $nv_Lang->getModule('edit_error_username_exist')
        ]);
    }

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_reg WHERE username LIKE :username OR md5username = :md5username');
    $stmt->bindValue(':username', $_user['username'], PDO::PARAM_STR);
    $stmt->bindValue(':md5username', $md5username, PDO::PARAM_STR);
    $stmt->execute();
    $query_error_username = $stmt->fetchColumn();
    if ($query_error_username) {
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
            'input_parent' => '#email_input_wrap',
            'mess' => $error_xemail[0]
        ]);
    }
    $_user['email'] = $error_xemail[1];

    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại chưa.
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . ' WHERE email = :email');
    $stmt->bindValue(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email = $stmt->fetchColumn();
    if ($query_error_email) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'input_parent' => '#email_input_wrap',
            'mess' => $nv_Lang->getModule('edit_error_email_exist')
        ]);
    }

    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong nv4_users_reg  chưa.
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_reg WHERE email = :email');
    $stmt->bindValue(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_reg = $stmt->fetchColumn();
    if ($query_error_email_reg) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'input_parent' => '#email_input_wrap',
            'mess' => $nv_Lang->getModule('edit_error_email_exist')
        ]);
    }

    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong nv3_users_openid chưa.
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_openid WHERE email = :email');
    $stmt->bindValue(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_openid = $stmt->fetchColumn();
    if ($query_error_email_openid) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'input_parent' => '#email_input_wrap',
            'mess' => $nv_Lang->getModule('edit_error_email_exist')
        ]);
    }

    if (($check_pass = nv_check_valid_pass($_user['password1'], $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'password1',
            'mess' => $check_pass
        ]);
    }

    if ($_user['password1'] != $_user['password2']) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'password1',
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

    if (empty($_user['is_official'])) {
        // Khi là thành viên mới thì chỉ có nhóm = 7, không có các nhóm khác
        $_user['in_groups'] = [7];
        $_user['in_groups_default'] = 7;
    } else {
        // Khi là thành viên chính thức thì cho phép chọn nhóm + nhóm = 4
        $in_groups = [];
        foreach ($_user['in_groups'] as $_group_id) {
            if ($_group_id > 9) {
                $in_groups[] = $_group_id;
            }
        }
        $_user['in_groups'] = array_intersect($in_groups, array_keys($groups_list));
        $_user['in_groups'] = array_map('intval', $_user['in_groups']);

        // Kiểm tra nhóm thành viên mặc định phải thuộc các nhóm đã chọn
        if (!empty($_user['in_groups_default']) and !in_array($_user['in_groups_default'], $_user['in_groups'], true)) {
            $_user['in_groups_default'] = 0;
        }

        // Khi không chọn nhóm mặc định thì là thành viên chính thức
        if (empty($_user['in_groups_default'])) {
            $_user['in_groups_default'] = 4;
        }

        $_user['in_groups'][] = 4;
    }

    if ($_user['pass_reset_request'] > 2 or $_user['pass_reset_request'] < 0) {
        $_user['pass_reset_request'] = 0;
    }
    if ($_user['email_reset_request'] > 2 or $_user['email_reset_request'] < 0) {
        $_user['email_reset_request'] = 0;
    }

    $stmt = $db->prepare("INSERT INTO " . NV_MOD_TABLE . " (
        group_id, username, md5username, password, email, first_name, last_name, gender, birthday, sig, regdate,
        question, answer, passlostkey, view_mail, remember, in_groups, active, checknum, last_login, last_ip,
        last_agent, last_openid, idsite, pass_creation_time, pass_reset_request, email_creation_time, email_reset_request,
        email_verification_time, active_obj
    ) VALUES (
        :group_id, :username, :md5username, :password, :email, :first_name, :last_name, :gender, :birthday, :sig, :regdate,
        :question, :answer, '', :view_mail, 1, :in_groups, 1, '', 0, '', '', '', :idsite, :pass_creation_time, :pass_reset_request,
        :email_creation_time, :email_reset_request, :email_verification_time, 'SYSTEM'
    )");

    $stmt->bindValue(':group_id', $_user['in_groups_default'], PDO::PARAM_INT);
    $stmt->bindValue(':username', $_user['username'], PDO::PARAM_STR);
    $stmt->bindValue(':md5username', $md5username, PDO::PARAM_STR);
    $stmt->bindValue(':password', $crypt->hash_password($_user['password1'], $global_config['hashprefix']), PDO::PARAM_STR);
    $stmt->bindValue(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->bindValue(':first_name', $_user['first_name'], PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $_user['last_name'], PDO::PARAM_STR);
    $stmt->bindValue(':gender', $_user['gender'], PDO::PARAM_STR);
    $stmt->bindValue(':birthday', !empty($_user['birthday']) ? nv_d2u_post($_user['birthday']) : 0, PDO::PARAM_INT);
    $stmt->bindValue(':sig', $_user['sig'], PDO::PARAM_STR);
    $stmt->bindValue(':regdate', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':question', $_user['question'], PDO::PARAM_STR);
    $stmt->bindValue(':answer', $_user['answer'], PDO::PARAM_STR);
    $stmt->bindValue(':view_mail', $_user['view_mail'], PDO::PARAM_INT);
    $stmt->bindValue(':in_groups', implode(',', $_user['in_groups']), PDO::PARAM_STR);
    $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    $stmt->bindValue(':pass_creation_time', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':pass_reset_request', $_user['pass_reset_request'], PDO::PARAM_INT);
    $stmt->bindValue(':email_creation_time', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':email_reset_request', $_user['email_reset_request'], PDO::PARAM_INT);
    $stmt->bindValue(':email_verification_time', ($_user['is_email_verified'] ? -1 : 0), PDO::PARAM_INT);

    $ok = $stmt->execute();
    $userid = $db->lastInsertId();

    if (!$userid) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $nv_Lang->getModule('edit_add_error')
        ]);
    }

    $query_field['userid'] = $userid;
    userInfoTabDb($query_field);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_user', 'userid ' . $userid, $admin_info['userid']);

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

        if (!empty($_user['photo'])) {
            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET photo = :photo WHERE userid = :userid');
            $stmt->bindValue(':photo', $_user['photo'], PDO::PARAM_STR);
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    if (!empty($_user['in_groups'])) {
        foreach ($_user['in_groups'] as $group_id) {
            if ($group_id != 7 and $group_id != 4) {
                nv_groups_add_user($group_id, $userid, 1, $module_data);
            }
        }
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers + 1 WHERE group_id = :gid');
    $stmt->bindValue(':gid', ($_user['is_official'] ? 4 : 7), PDO::PARAM_INT);
    $stmt->execute();
    $nv_Cache->delMod($module_name);

    // Gửi mail thông báo
    if (!empty($_user['adduser_email'])) {
        $maillang = NV_LANG_INTERFACE;
        if (NV_LANG_DATA != NV_LANG_INTERFACE) {
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
                'password' => $_user['password1'],
                'pass_reset' => $_user['pass_reset_request'],
                'email_reset' => $_user['email_reset_request'],
                'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN),
                'lang' => $maillang
            ]
        ]];
        nv_sendmail_template_async([$module_name, Emails::ADDED_BY_ADMIN], $send_data, $maillang);
    }

    $redirect = $nv_redirect != '' ? nv_redirect_decrypt($nv_redirect) . '&userid=' . $userid : '';
    if (isset($admin_mods['authors']) and defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and ($global_config['spadmin_add_admin'] == 1 or $global_config['idsite'] > 0))) {
        $is_admin_add = $nv_Request->get_bool('admin_add', 'post', false);
        if ($is_admin_add) {
            $redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=authors&' . NV_OP_VARIABLE . '=add&userid=' . $_user['username'];
        }
    }
    if (empty($redirect)) {
        $redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
    }
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => '',
        'redirect' => $redirect
    ]);
}

$initdata = [];
if ($nv_Request->isset_request('initdata', 'post')) {
    $_initdata = $nv_Request->get_title('initdata', 'post');
    $_initdata = json_decode($crypt->decrypt($_initdata, NV_CHECK_SESSION), true);
    $initdata = is_array($_initdata) ? $_initdata : [];
}

$_user = [
    'username' => $initdata['username'] ?? '',
    'email' => $initdata['email'] ?? '',
    'pass_reset_request' => isset($initdata['pass_reset_request']) ? (int) $initdata['pass_reset_request'] : 1,
    'email_reset_request' => isset($initdata['email_reset_request']) ? (int) $initdata['email_reset_request'] : 0,
];

// Danh sách câu hỏi bảo mật
$data_questions = [];
$stmt = $db->prepare('SELECT qid, title FROM ' . NV_MOD_TABLE . '_question WHERE lang = :lang ORDER BY weight ASC');
$stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $data_questions[$row['qid']] = $row['title'];
}
$stmt->closeCursor();

// Chuẩn bị các trường hệ thống cho Smarty
$system_fields = [];
$have_name_field = false;
foreach ($array_field_config as $row) {
    if (!empty($row['system'])) {
        if (!empty($row['field_choices'])) {
            if ($row['field_type'] == 'date') {
                $row['value'] = $initdata[$row['field']] ?? (($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value']);
            } elseif ($row['field_type'] == 'number') {
                $row['value'] = $initdata[$row['field']] ?? $row['default_value'];
            } else {
                $temp = array_map('strval', array_keys($row['field_choices']));
                $tempkey = $initdata[$row['field']] ?? get_value_by_lang($row['default_value']);
                $row['value'] = in_array($tempkey, $temp, true) ? $tempkey : '';
            }
        } else {
            $row['value'] = $initdata[$row['field']] ?? get_value_by_lang($row['default_value']);
        }

        if ($row['field'] == 'birthday') {
            $row['value'] = nv_u2d_post($row['value']);
        } elseif ($row['field'] == 'sig') {
            $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
        }

        $row['required'] = (bool) $row['required'];

        if ($row['field'] == 'gender') {
            $gender_options = [];
            foreach ($global_array_genders as $g) {
                $g['selected'] = ($row['value'] == $g['key']);
                $gender_options[] = $g;
            }
            $row['gender_options'] = $gender_options;
        }

        if ($row['field'] == 'question') {
            $row['questions'] = array_values($data_questions);
        }

        if ($row['field'] == 'first_name' || $row['field'] == 'last_name') {
            $have_name_field = true;
        }

        $system_fields[$row['field']] = $row;
    }
}

// Chuẩn bị các trường tùy biến cho Smarty
$custom_fields_data = [];
$have_custom_fields = false;
foreach ($array_field_config as $row) {
    if (empty($row['system'])) {
        if (!empty($row['field_choices'])) {
            if ($row['field_type'] == 'date') {
                $row['value'] = $initdata[$row['field']] ?? (($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value']);
            } elseif ($row['field_type'] == 'number') {
                $row['value'] = $initdata[$row['field']] ?? $row['default_value'];
            } else {
                $temp = array_map('strval', array_keys($row['field_choices']));
                $tempkey = $initdata[$row['field']] ?? get_value_by_lang($row['default_value']);
                $row['value'] = in_array($tempkey, $temp, true) ? $tempkey : '';
            }
        } else {
            $row['value'] = $initdata[$row['field']] ?? get_value_by_lang($row['default_value']);
        }

        $row['required'] = (bool) $row['required'];

        if (!empty($row['field_choices'])) {
            $choices_prepared = [];
            if ($row['field_type'] == 'checkbox') {
                $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                $number = 0;
                foreach ($row['field_choices'] as $key => $value) {
                    $choices_prepared[] = [
                        'id' => $row['fid'] . '_' . $number++,
                        'key' => $key,
                        'selected' => in_array((string) $key, $valuecheckbox, true),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } elseif ($row['field_type'] == 'radio') {
                $number = 0;
                foreach ($row['field_choices'] as $key => $value) {
                    $choices_prepared[] = [
                        'id' => $row['fid'] . '_' . $number++,
                        'key' => $key,
                        'selected' => ($key == $row['value']),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } elseif ($row['field_type'] == 'multiselect') {
                $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                foreach ($row['field_choices'] as $key => $value) {
                    $choices_prepared[] = [
                        'key' => $key,
                        'selected' => in_array((string) $key, $valueselect, true),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } else {
                foreach ($row['field_choices'] as $key => $value) {
                    $choices_prepared[] = [
                        'key' => $key,
                        'selected' => ($key == $row['value']),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            }
            $row['choices_prepared'] = $choices_prepared;
        }

        if ($row['field_type'] == 'date') {
            $row['value'] = nv_u2d_post($row['value']);
        } elseif ($row['field_type'] == 'textarea') {
            $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
        } elseif ($row['field_type'] == 'editor') {
            $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
            if (defined('NV_EDITOR') && nv_function_exists('nv_aleditor')) {
                $array_tmp = explode('@', $row['class']);
                $row['editor_html'] = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value']);
                $row['field_type_render'] = 'editor';
            } else {
                $row['class'] = '';
                $row['field_type_render'] = 'textarea';
            }
        } elseif ($row['field_type'] == 'file') {
            $row['file_items'] = [];
            $row['limited_values'] = !empty($row['limited_values']) ? json_decode($row['limited_values'], true) : [];
            $row['fileaccept'] = !empty($row['limited_values']['mime']) ? '.' . implode(',.', $row['limited_values']['mime']) : '';
            $row['filemaxsize'] = $row['limited_values']['file_max_size'] ?? 0;
            $row['filemaxsize_format'] = nv_convertfromBytes($row['limited_values']['file_max_size'] ?? 0);
            $row['filemaxnum'] = $row['limited_values']['maxnum'] ?? 0;
            $row['csrf'] = csrf_create($module_name . '_field_' . $row['field']);
            $row['url_module'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
            $row['widthlimit'] = image_size_info($row['limited_values']['widthlimit'] ?? '', 'width');
            $row['heightlimit'] = image_size_info($row['limited_values']['heightlimit'] ?? '', 'height');
            $row['addfile_disabled'] = false;
        }

        if (!isset($row['field_type_render'])) {
            $row['field_type_render'] = $row['field_type'];
        }

        $custom_fields_data[] = $row;
        $have_custom_fields = true;
    }
}

// Chuẩn bị danh sách nhóm
$groups_for_tpl = [];
$group_exists = false;
if (!empty($groups_list)) {
    foreach ($groups_list as $group_id => $grtl) {
        if ($group_id > 9) {
            $groups_for_tpl[] = ['id' => $group_id, 'title' => $grtl];
            $group_exists = true;
        }
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

$show_admin_add = (isset($admin_mods['authors']) && defined('NV_IS_GODADMIN')) || (defined('NV_IS_SPADMIN') && ($global_config['spadmin_add_admin'] == 1 || $global_config['idsite'] > 0));

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('user_add.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('DATA', $_user);
$tpl->assign('NV_REDIRECT', $nv_redirect);
$tpl->assign('IS_FORUM', defined('NV_IS_USER_FORUM'));
$tpl->assign('SYSTEM_FIELDS', $system_fields);
$tpl->assign('CUSTOM_FIELDS', $custom_fields_data);
$tpl->assign('HAVE_CUSTOM_FIELDS', $have_custom_fields);
$tpl->assign('HAVE_NAME_FIELD', $have_name_field);
$tpl->assign('GROUPS_FOR_TPL', $groups_for_tpl);
$tpl->assign('GROUP_EXISTS', $group_exists);
$tpl->assign('PASS_RESET_OPTIONS', $pass_reset_options);
$tpl->assign('EMAIL_RESET_OPTIONS', $email_reset_options);
$tpl->assign('AVATAR_UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('SHOW_ADMIN_ADD', $show_admin_add);
$contents = $tpl->fetch('user_add.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, $showheader);
include NV_ROOTDIR . '/includes/footer.php';
