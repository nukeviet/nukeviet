<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/05/2010
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if ($nv_Request->isset_request('nv_genpass', 'post')) {
    $_len = round(($global_config['nv_upassmin'] + $global_config['nv_upassmax']) / 2);
    echo nv_genpass($_len, $global_config['nv_upass_type']);
    exit();
}

$page_title = $lang_module['user_add'];

$groups_list = nv_groups_list($module_data);

$array_field_config = array();
$result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY weight ASC');
while ($row_field = $result_field->fetch()) {
    $language = unserialize($row_field['language']);
    $row_field['title'] = (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : $row['field'];
    $row_field['description'] = (isset($language[NV_LANG_DATA])) ? nv_htmlspecialchars($language[NV_LANG_DATA][1]) : '';
    if (!empty($row_field['field_choices'])) {
        $row_field['field_choices'] = unserialize($row_field['field_choices']);
    } elseif (!empty($row_field['sql_choices'])) {
        $row_field['sql_choices'] = explode('|', $row_field['sql_choices']);
        $query = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
        $result = $db->query($query);
        $weight = 0;
        while (list ($key, $val) = $result->fetch(3)) {
            $row_field['field_choices'][$key] = $val;
        }
    }
    $array_field_config[$row_field['field']] = $row_field;
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$_user = $custom_fields = array();
$userid = 0;
if ($nv_Request->isset_request('confirm', 'post')) {
    $_user['username'] = $nv_Request->get_title('username', 'post', '', 1);
    $_user['email'] = nv_strtolower($nv_Request->get_title('email', 'post', '', 1));
    $_user['password1'] = $nv_Request->get_title('password1', 'post', '', 0);
    $_user['password2'] = $nv_Request->get_title('password2', 'post', '', 0);
    $_user['question'] = nv_substr($nv_Request->get_title('question', 'post', '', 1), 0, 255);
    $_user['answer'] = nv_substr($nv_Request->get_title('answer', 'post', '', 1), 0, 255);
    $_user['first_name'] = nv_substr($nv_Request->get_title('first_name', 'post', '', 1), 0, 255);
    $_user['last_name'] = nv_substr($nv_Request->get_title('last_name', 'post', '', 1), 0, 255);
    $_user['gender'] = nv_substr($nv_Request->get_title('gender', 'post', '', 1), 0, 1);
    $_user['view_mail'] = $nv_Request->get_int('view_mail', 'post', 0);
    $_user['sig'] = $nv_Request->get_textarea('sig', '', NV_ALLOWED_HTML_TAGS);
    $_user['birthday'] = $nv_Request->get_title('birthday', 'post');
    $_user['in_groups'] = $nv_Request->get_typed_array('group', 'post', 'int');
    $_user['in_groups_default'] = $nv_Request->get_int('group_default', 'post', 0);
    $_user['photo'] = nv_substr($nv_Request->get_title('photo', 'post', '', 1), 0, 255);
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

    $md5username = nv_md5safe($_user['username']);

    if (($error_username = nv_check_valid_login($_user['username'], $global_config['nv_unickmax'], $global_config['nv_unickmin'])) != '') {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'username',
            'mess' => $error_username
        ));
    }

    if ("'" . $_user['username'] . "'" != $db->quote($_user['username'])) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'username',
            'mess' => sprintf($lang_module['account_deny_name'], $_user['username'])
        ));
    }

    // Thực hiện câu truy vấn để kiểm tra username đã tồn tại chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE md5username= :md5username');
    $stmt->bindParam(':md5username', $md5username, PDO::PARAM_STR);
    $stmt->execute();
    $query_error_username = $stmt->fetchColumn();
    if ($query_error_username) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'username',
            'mess' => $lang_module['edit_error_username_exist']
        ));
    }

    if (($error_xemail = nv_check_valid_email($_user['email'])) != '') {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'email',
            'mess' => $error_xemail
        ));
    }

    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE email= :email');
    $stmt->bindParam(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email = $stmt->fetchColumn();
    if ($query_error_email) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'email',
            'mess' => $lang_module['edit_error_email_exist']
        ));
    }

    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong nv4_users_reg  chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE email= :email');
    $stmt->bindParam(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_reg = $stmt->fetchColumn();
    if ($query_error_email_reg) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'email',
            'mess' => $lang_module['edit_error_email_exist']
        ));
    }

    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong nv3_users_openid chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_openid WHERE email= :email');
    $stmt->bindParam(':email', $_user['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_openid = $stmt->fetchColumn();
    if ($query_error_email_openid) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'email',
            'mess' => $lang_module['edit_error_email_exist']
        ));
    }

    if (($check_pass = nv_check_valid_pass($_user['password1'], $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'password1',
            'mess' => $check_pass
        ));
    }

    if ($_user['password1'] != $_user['password2']) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'password1',
            'mess' => $lang_module['edit_error_password']
        ));
    }

    // Kiểm tra các trường dữ liệu tùy biến + Hệ thống
    $query_field = array();
    require NV_ROOTDIR . '/modules/users/fields.check.php';

    $in_groups = array();
    foreach ($_user['in_groups'] as $_group_id) {
        if ($_group_id > 9) {
            $in_groups[] = $_group_id;
        }
    }
    $_user['in_groups'] = array_intersect($in_groups, array_keys($groups_list));

    if (empty($_user['is_official'])) {
        $_user['in_groups'][] = 7;
        $_user['in_groups_default'] = 7;
    } elseif (empty($_user['in_groups_default']) or !in_array($_user['in_groups_default'], $_user['in_groups'])) {
        $_user['in_groups_default'] = 4;
    }

    if (empty($_user['in_groups_default']) and sizeof($_user['in_groups'])) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'group_default',
            'mess' => $lang_module['edit_error_group_default']
        ));
    }

    $sql = "INSERT INTO " . NV_MOD_TABLE . " (
        group_id, username, md5username, password, email, first_name, last_name, gender, birthday, sig, regdate,
        question, answer, passlostkey, view_mail,
        remember, in_groups, active, checknum, last_login, last_ip, last_agent, last_openid, idsite, email_verification_time
    ) VALUES (
        " . $_user['in_groups_default'] . ",
        :username,
        :md5_username,
        :password,
        :email,
        :first_name,
        :last_name,
        :gender,
        " . intval($_user['birthday']) . ",
        :sig,
        " . NV_CURRENTTIME . ",
        :question,
        :answer,
        '',
         " . $_user['view_mail'] . ",
         1,
         '" . implode(',', $_user['in_groups']) . "', 1, '', 0, '', '', '', " . $global_config['idsite'] . ", 0
    )";

    $data_insert = array();
    $data_insert['username'] = $_user['username'];
    $data_insert['md5_username'] = $md5username;
    $data_insert['password'] = $crypt->hash_password($_user['password1'], $global_config['hashprefix']);
    $data_insert['email'] = $_user['email'];
    $data_insert['first_name'] = $_user['first_name'];
    $data_insert['last_name'] = $_user['last_name'];
    $data_insert['gender'] = $_user['gender'];
    $data_insert['sig'] = $_user['sig'];
    $data_insert['question'] = $_user['question'];
    $data_insert['answer'] = $_user['answer'];

    $userid = $db->insert_id($sql, 'userid', $data_insert);

    if (!$userid) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => '',
            'mess' => $lang_module['edit_add_error']
        ));
    }

    $query_field['userid'] = $userid;
    $db->query('INSERT INTO ' . NV_MOD_TABLE . '_info (' . implode(', ', array_keys($query_field)) . ') VALUES (' . implode(', ', array_values($query_field)) . ')');

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
            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET photo= :photo WHERE userid=' . $userid);
            $stmt->bindParam(':photo', $_user['photo'], PDO::PARAM_STR, strlen($_user['photo']));
            $stmt->execute();
        }
    }

    if (!empty($_user['in_groups'])) {
        foreach ($_user['in_groups'] as $group_id) {
            if ($group_id != 7) {
                nv_groups_add_user($group_id, $userid, 1, $module_data);
            }
        }
    }

    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=' . ($_user['is_official'] ? 4 : 7));
    $nv_Cache->delMod($module_name);

    // Gửi mail thông báo
    if (!empty($_user['adduser_email'])) {
        $full_name = nv_show_name_user($_user['first_name'], $_user['last_name'], $_user['username']);
        $subject = $lang_module['adduser_register'];
        $_url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
        $message = sprintf($lang_module['adduser_register_info1'], $full_name, $global_config['site_name'], $_url, $_user['username'], $_user['password1']);
        @nv_sendmail($global_config['site_email'], $_user['email'], $subject, $message);
    }

    nv_jsonOutput(array(
        'status' => 'ok',
        'input' => '',
        'username' => $_user['username'],
        'admin_add' => (isset($admin_mods['authors']) and defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and ($global_config['spadmin_add_admin'] == 1 or $global_config['idsite'] > 0))) ? 'yes' : 'no',
        'mess' => sprintf($lang_module['admin_add'], $_user['username'])
    ));
}

$_user['username'] = $_user['email'] = $_user['password1'] = $_user['password2'] = $_user['question'] = $_user['answer'] = '';
$_user['first_name'] = $_user['last_name'] = $_user['gender'] = $_user['sig'] = $_user['birthday'] = '';
$_user['view_mail'] = 0;
$_user['in_groups'] = array();
$_user['is_official'] = ' checked="checked"';
$_user['adduser_email'] = '';
$_user['view_mail'] = '';

$groups = array();
if (!empty($groups_list)) {
    foreach ($groups_list as $group_id => $grtl) {
        $groups[] = array(
            'id' => $group_id,
            'title' => $grtl,
            'checked' => ''
        );
    }
}

$xtpl = new XTemplate('user_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $_user);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_add');
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);

$xtpl->assign('NV_UNICKMIN', $global_config['nv_unickmin']);
$xtpl->assign('NV_UNICKMAX', $global_config['nv_unickmax']);
$xtpl->assign('NV_UPASSMAX', $global_config['nv_upassmax']);
$xtpl->assign('NV_UPASSMIN', $global_config['nv_upassmin']);

if (defined('NV_IS_USER_FORUM')) {
    $xtpl->parse('main.is_forum');
} else {
    $a = 0;
    foreach ($groups as $group) {
        if ($group['id'] > 9) {
            $xtpl->assign('GROUP', $group);
            $xtpl->parse('main.edit_user.group.list');
            ++$a;
        }
    }
    if ($a > 0) {
        $xtpl->parse('main.edit_user.group');
    }

    $have_custom_fields = false;
    $have_name_field = false;
    foreach ($array_field_config as $row) {
        if (($row['show_register'] and $userid == 0) or $userid > 0) {
            // Value luôn là giá trị mặc định
            if (!empty($row['field_choices'])) {
                if ($row['field_type'] == 'date') {
                    $row['value'] = ($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value'];
                } elseif ($row['field_type'] == 'number') {
                    $row['value'] = $row['default_value'];
                } else {
                    $temp = array_keys($row['field_choices']);
                    $tempkey = intval($row['default_value']) - 1;
                    $row['value'] = (isset($temp[$tempkey])) ? $temp[$tempkey] : '';
                }
            } else {
                $row['value'] = $row['default_value'];
            }

            $row['required'] = ($row['required']) ? 'required' : '';
            $xtpl->assign('FIELD', $row);

            // Các trường hệ thống xuất độc lập
            if (!empty($row['system'])) {
                if ($row['field'] == 'birthday') {
                    $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
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
                if ($row['description']) {
                    $xtpl->parse('main.edit_user.' . $show_key . '.description');
                }
                $xtpl->parse('main.edit_user.' . $show_key);
            } else {
                if ($row['required']) {
                    $xtpl->parse('main.edit_user.field.loop.required');
                }
                if ($row['description']) {
                    $xtpl->parse('main.edit_user.field.loop.description');
                }
                if ($row['field_type'] == 'textbox' or $row['field_type'] == 'number') {
                    $xtpl->parse('main.edit_user.field.loop.textbox');
                } elseif ($row['field_type'] == 'date') {
                    $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
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
                        $xtpl->assign('FIELD_CHOICES', array(
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.edit_user.field.loop.select.loop');
                    }
                    $xtpl->parse('main.edit_user.field.loop.select');
                } elseif ($row['field_type'] == 'radio') {
                    $number = 0;
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.edit_user.field.loop.radio');
                    }
                } elseif ($row['field_type'] == 'checkbox') {
                    $number = 0;
                    $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : array();
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => (in_array($key, $valuecheckbox)) ? ' checked="checked"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.edit_user.field.loop.checkbox');
                    }
                } elseif ($row['field_type'] == 'multiselect') {
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.edit_user.field.loop.multiselect.loop');
                    }
                    $xtpl->parse('main.edit_user.field.loop.multiselect');
                }
                $xtpl->parse('main.edit_user.field.loop');
                $have_custom_fields = true;
            }
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