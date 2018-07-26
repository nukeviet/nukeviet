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

$page_title = $lang_module['edit_title'];

$userid = $nv_Request->get_int('userid', 'get', 0);

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
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_2step&amp;userid=' . $row['userid']] = $lang_module['user_2step_mamager'];
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_oauth&amp;userid=' . $row['userid']] = $lang_module['user_openid_mamager'];

if ($admin_info['admin_id'] == $userid and $admin_info['safemode'] == 1) {
    $xtpl = new XTemplate('user_safemode.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('SAFEMODE_DEACT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/safeshow');
    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

$groups_list = nv_groups_list($module_data);

$array_old_groups = array();
$result_gru = $db->query('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid);
while ($row_gru = $result_gru->fetch()) {
    $array_old_groups[] = $row_gru['group_id'];
}

$array_field_config = array();
$result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY weight ASC');
while ($row_field = $result_field->fetch()) {
    $language = unserialize($row_field['language']);
    $row_field['title'] = (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : $row_field['field'];
    $row_field['description'] = (isset($language[NV_LANG_DATA])) ? nv_htmlspecialchars($language[NV_LANG_DATA][1]) : '';
    if (!empty($row_field['field_choices'])) {
        $row_field['field_choices'] = unserialize($row_field['field_choices']);
    } elseif (!empty($row_field['sql_choices'])) {
        $row_field['sql_choices'] = explode('|', $row_field['sql_choices']);
        $query = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
        $result = $db->query($query);
        $weight = 0;
        while (list($key, $val) = $result->fetch(3)) {
            $row_field['field_choices'][$key] = $val;
        }
    }
    $array_field_config[$row_field['field']] = $row_field;
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$access_passus = (isset($access_admin['access_passus'][$admin_info['level']]) and $access_admin['access_passus'][$admin_info['level']] == 1) ? true : false;
$_user = $custom_fields = array();

if ($nv_Request->isset_request('confirm', 'post')) {
    $_user['username'] = $nv_Request->get_title('username', 'post', '', 1);
    $_user['email'] = nv_strtolower($nv_Request->get_title('email', 'post', '', 1));
    if ($access_passus) {
        $_user['password1'] = $nv_Request->get_title('password1', 'post', '', 0);
        $_user['password2'] = $nv_Request->get_title('password2', 'post', '', 0);
    } else {
        $_user['password1'] = $_user['password2'] = '';
    }
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
            'mess' => sprintf($lang_module['account_deny_name'], '<strong>' . $_user['username'] . '</strong>')
        ));
    }

    if ($db->query('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE userid!=' . $userid . ' AND md5username=' . $db->quote(nv_md5safe($_user['username'])))->fetchColumn()) {
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

    if ($db->query('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE userid!=' . $userid . ' AND email=' . $db->quote($_user['email']))->fetchColumn()) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'email',
            'mess' => $lang_module['edit_error_email_exist']
        ));
    }

    if ($db->query('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE email=' . $db->quote($_user['email']))->fetchColumn()) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'email',
            'mess' => $lang_module['edit_error_email_exist']
        ));
    }

    if ($db->query('SELECT userid FROM ' . NV_MOD_TABLE . '_openid WHERE userid!=' . $userid . ' AND email=' . $db->quote($_user['email']))->fetchColumn()) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'email',
            'mess' => $lang_module['edit_error_email_exist']
        ));
    }

    if (!empty($_user['password1']) and ($check_pass = nv_check_valid_pass($_user['password1'], $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'password1',
            'mess' => $check_pass
        ));
    }

    if (!empty($_user['password1']) and $_user['password1'] != $_user['password2']) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'password2',
            'mess' => $lang_module['edit_error_password']
        ));
    }

    // Kiểm tra các trường dữ liệu tùy biến + Hệ thống
    $query_field = array();
    if (!empty($array_field_config)) {
        require NV_ROOTDIR . '/modules/users/fields.check.php';
    }

    $password = !empty($_user['password1']) ? $crypt->hash_password($_user['password1'], $global_config['hashprefix']) : $row['password'];

    $in_groups = array();
    foreach (array_keys($groups_list) as $_group_id) {
        if (!empty($rowlev) and $_group_id < 4 and in_array($_group_id, $array_old_groups)) {
            $in_groups[] = $_group_id;
        } elseif ($_group_id > 9 and in_array($_group_id, $_user['in_groups'])) {
            $in_groups[] = $_group_id;
        }
    }

    $in_groups_del = array_diff($array_old_groups, $in_groups);
    if (!empty($in_groups_del)) {
        foreach ($in_groups_del as $gid) {
            nv_groups_del_user($gid, $userid, $module_data);
        }
    }

    $in_groups_add = array_diff($in_groups, $array_old_groups);
    if (!empty($in_groups_add)) {
        foreach ($in_groups_add as $gid) {
            nv_groups_add_user($gid, $userid, 1, $module_data);
        }
    }

    if (!empty($_user['in_groups_default']) and !in_array($_user['in_groups_default'], $in_groups)) {
        $_user['in_groups_default'] = 0;
    }

    if (!$_user['in_groups_default'] and sizeof($in_groups) == 1) {
        $_user['in_groups_default'] = array_values($in_groups);
        $_user['in_groups_default'] = $_user['in_groups_default'][0];
    }

    if (empty($_user['in_groups_default']) and sizeof($in_groups)) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'group_default',
            'mess' => $lang_module['edit_error_group_default']
        ));
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

    if ($row['group_id'] == 7) {
        if (!$_user['is_official']) {
            $_user['in_groups_default'] = 7;
            $in_groups[] = 7;
        } else {
            $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=4');
            $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id=7');

            if ($_user['in_groups_default'] == 7) {
                $_user['in_groups_default'] = 4;
            }
        }
    }

    if ($row['email'] != $_user['email']) {
        $email_verification_time = 0;
    } else {
        $email_verification_time = $row['email_verification_time'];
    }

    $db->query("UPDATE " . NV_MOD_TABLE . " SET
        group_id=" . $_user['in_groups_default'] . ",
        username=" . $db->quote($_user['username']) . ",
        md5username='" . nv_md5safe($_user['username']) . "',
        password=" . $db->quote($password) . ",
        email=" . $db->quote($_user['email']) . ",
        first_name=" . $db->quote($_user['first_name']) . ",
        last_name=" . $db->quote($_user['last_name']) . ",
        gender=" . $db->quote($_user['gender']) . ",
        photo=" . $db->quote(nv_unhtmlspecialchars($_user['photo'])) . ",
        birthday=" . intval($_user['birthday']) . ",
        sig=" . $db->quote($_user['sig']) . ",
        question=" . $db->quote($_user['question']) . ",
        answer=" . $db->quote($_user['answer']) . ",
        view_mail=" . $_user['view_mail'] . ",
        in_groups='" . implode(',', $in_groups) . "',
        email_verification_time=" . $email_verification_time . "
    WHERE userid=" . $userid);

    if (!empty($query_field)) {
        $db->query('UPDATE ' . NV_MOD_TABLE . '_info SET ' . implode(', ', $query_field) . ' WHERE userid=' . $userid);
    }

    // Gửi mail thông báo
    if (!empty($_user['adduser_email'])) {
        $full_name = nv_show_name_user($_user['first_name'], $_user['last_name'], $_user['username']);
        $subject = $lang_module['adduser_register1'];
        $_url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
        $message = sprintf($lang_module['adduser_register_info2'], $full_name, $global_config['site_name'], $_url, $_user['username']);
        if (!empty($_user['password1'])) {
            $message .= sprintf($lang_module['adduser_register_info3'], $_user['password1']);
        }
        $message .= sprintf($lang_module['adduser_register_info4'], $global_config['site_name']);
        @nv_sendmail($global_config['site_email'], $_user['email'], $subject, $message);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_user', 'userid ' . $userid, $admin_info['userid']);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput(array(
        'status' => 'ok',
        'input' => '',
        'admin_add' => 'no',
        'mess' => ''
    ));
}

$_user = $row;
$_user['password1'] = $_user['password2'] = '';
$_user['in_groups'] = $array_old_groups;

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

$groups = array();
if (!empty($groups_list)) {
    foreach ($groups_list as $group_id => $grtl) {
        $groups[] = array(
            'id' => $group_id,
            'title' => $grtl,
            'checked' => (in_array($group_id, $_user['in_groups'])) ? ' checked="checked"' : '',
            'default' => (in_array($group_id, $_user['in_groups']) and $_user['group_id'] == $group_id) ? ' checked="checked"' : ''
        );
    }
}

$xtpl = new XTemplate('user_edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $_user);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $userid);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);

if (defined('NV_IS_USER_FORUM')) {
    $xtpl->parse('main.is_forum');
} else {
    if (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . $row['photo'])) {
        $size = @getimagesize(NV_ROOTDIR . '/' . $row['photo']);
        $img = array(
            'src' => NV_BASE_SITEURL . $row['photo'],
            'height' => $size[1],
            'width' => $size[0]
        );
        $xtpl->assign('IMG', $img);
        $xtpl->parse('main.edit_user.photo');
    } else {
        $xtpl->parse('main.edit_user.add_photo');
    }

    $xtpl->assign('GROUP_DEFAULT_STYLE', (sizeof($_user['in_groups']) > 1) ? '' : ' style="display:none"');

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
        $xtpl->parse('main.edit_user.group');
    }

    if ($access_passus) {
        $xtpl->parse('main.edit_user.changepass');
    }

    if ($_user['group_id'] == 7) {
        $xtpl->parse('main.edit_user.is_official');
    }

    $have_custom_fields = false;
    $have_name_field = false;
    foreach ($array_field_config as $row) {
        $row['value'] = (isset($custom_fields[$row['field']])) ? $custom_fields[$row['field']] : $row['default_value'];
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
                $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : array();
                foreach ($row['field_choices'] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES', array(
                        'key' => $key,
                        'selected' => (in_array($key, $valueselect)) ? ' selected="selected"' : '',
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
