<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $table_caption = $lang_module['member_wating'];
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting';

$array_field_config = nv_get_users_field_config();

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

//Xoa thanh vien
if ($nv_Request->isset_request('del', 'post')) {
    $userid = $nv_Request->get_absint('userid', 'post', 0);
    $checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $userid);
    if ($checkss == $nv_Request->get_string('checkss', 'post')) {
        $sql = 'DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $userid;
        if ($global_config['idsite'] > 0) {
            $sql .= ' AND idsite=' . $global_config['idsite'];
        }
        if ($db->exec($sql)) {
            nv_delete_notification(NV_LANG_DATA, $module_name, 'send_active_link_fail', $userid);
            exit('OK');
        }
    }
    exit('NO');
}

//Kich hoat thanh vien
if ($nv_Request->isset_request('userid', 'get')) {
    if ($global_config['max_user_number']) {
        $sql = 'SELECT count(*) FROM ' . NV_MOD_TABLE;
        if ($global_config['idsite'] > 0) {
            $sql .= ' WHERE idsite=' . $global_config['idsite'];
        }
        $user_number = $db->query($sql)->fetchColumn();
        if ($user_number >= $global_config['max_user_number']) {
            $contents = sprintf($lang_global['limit_user_number'], $global_config['max_user_number']);
            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($contents, $showheader);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }

    $userid = $nv_Request->get_int('userid', 'get', 0);
    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $userid;
    if ($global_config['idsite'] > 0) {
        $sql .= ' AND idsite=' . $global_config['idsite'];
    }

    $userdata = $db->query($sql)->fetch();
    if (empty($userdata)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    $userdata['users_info'] = json_decode($userdata['users_info'], true);
    $userdata['photo'] = '';

    $groups_list = nv_groups_list($module_data);
    $checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $userid);
    // Nếu chấp nhận
    if ($checkss == $nv_Request->get_string('checkss', 'post')) {
        $post = [
            'username' => $nv_Request->get_title('username', 'post', '', 1),
            'email' => nv_strtolower(nv_substr($nv_Request->get_title('email', 'post', '', 1), 0, 100)),
            'password' => $nv_Request->get_title('password', 'post', ''),
            're_password' => $nv_Request->get_title('re_password', 'post', ''),
            'pass_reset_request' => $nv_Request->get_int('pass_reset_request', 'post', 0),
            'first_name' => nv_substr($nv_Request->get_title('first_name', 'post', '', 1), 0, 255),
            'last_name' => nv_substr($nv_Request->get_title('last_name', 'post', '', 1), 0, 255),
            'gender' => nv_substr($nv_Request->get_title('gender', 'post', '', 1), 0, 1),
            'birthday' => $nv_Request->get_title('birthday', 'post'),
            'sig' => $nv_Request->get_title('sig', 'post', ''),
            'question' => nv_substr($nv_Request->get_title('question', 'post', '', 1), 0, 255),
            'answer' => nv_substr($nv_Request->get_title('answer', 'post', '', 1), 0, 255),
            'photo' => nv_substr($nv_Request->get_title('photo', 'post', '', 1), 0, 255),
            'view_mail' => (int) $nv_Request->get_bool('view_mail', 'post', false),
            'is_official' => (int) $nv_Request->get_bool('is_official', 'post', false),
            'in_groups' => $nv_Request->get_typed_array('group', 'post', 'int'),
            'group_id' => $nv_Request->get_int('group_default', 'post', 0),
            'is_email_verified' => (int) $nv_Request->get_bool('is_email_verified', 'post', false)
        ];
        $post['question'] = !empty($post['question']) ? $post['question'] : $userdata['question'];
        $post['answer'] = !empty($post['answer']) ? $post['answer'] : $userdata['answer'];

        $custom_fields = $nv_Request->get_array('custom_fields', 'post');
        $custom_fields['first_name'] = $post['first_name'];
        $custom_fields['last_name'] = $post['last_name'];
        $custom_fields['gender'] = $post['gender'];
        $custom_fields['birthday'] = $post['birthday'];
        $custom_fields['sig'] = $post['sig'];
        $custom_fields['question'] = $post['question'];
        $custom_fields['answer'] = $post['answer'];

        $post['md5username'] = nv_md5safe($post['username']);

        if (($error_username = nv_check_valid_login($post['username'], $global_config['nv_unickmax'], $global_config['nv_unickmin'])) != '') {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'username',
                'mess' => $error_username
            ]);
        }

        if ("'" . $post['username'] . "'" != $db->quote($post['username'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'username',
                'mess' => sprintf($lang_module['account_deny_name'], $post['username'])
            ]);
        }

        // Thực hiện câu truy vấn để kiểm tra username đã tồn tại chưa.
        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE username LIKE :username OR md5username= :md5username');
        $stmt->bindParam(':username', $post['username'], PDO::PARAM_STR);
        $stmt->bindParam(':md5username', $post['md5username'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_username = $stmt->fetchColumn();
        if ($query_error_username) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'username',
                'mess' => $lang_module['edit_error_username_exist']
            ]);
        }

        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE userid != ' . $userid . ' AND (username LIKE :username OR md5username= :md5username)');
        $stmt->bindParam(':username', $post['username'], PDO::PARAM_STR);
        $stmt->bindParam(':md5username', $post['md5username'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_username = $stmt->fetchColumn();
        if ($query_error_username) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'username',
                'mess' => $lang_module['edit_error_username_exist']
            ]);
        }

        $error_xemail = nv_check_valid_email($post['email'], true);
        if (!empty($error_xemail[0])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'email',
                'mess' => $error_xemail[0]
            ]);
        }
        $post['email'] = $error_xemail[1];

        // Thực hiện câu truy vấn để kiểm tra email đã tồn tại chưa.
        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE email= :email');
        $stmt->bindParam(':email', $post['email'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_email = $stmt->fetchColumn();
        if ($query_error_email) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'email',
                'mess' => $lang_module['edit_error_email_exist']
            ]);
        }

        // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong users_reg  chưa.
        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE userid != ' . $userid . ' AND email= :email');
        $stmt->bindParam(':email', $post['email'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_email_reg = $stmt->fetchColumn();
        if ($query_error_email_reg) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'email',
                'mess' => $lang_module['edit_error_email_exist']
            ]);
        }

        // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong users_openid chưa.
        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_openid WHERE email= :email');
        $stmt->bindParam(':email', $post['email'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_email_openid = $stmt->fetchColumn();
        if ($query_error_email_openid) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'email',
                'mess' => $lang_module['edit_error_email_exist']
            ]);
        }

        if (!empty($post['password']) and ($check_pass = nv_check_valid_pass($post['password'], $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'password1',
                'mess' => $check_pass
            ]);
        }

        if (!empty($post['password']) and ($post['password'] != $post['re_password'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'password1',
                'mess' => $lang_module['edit_error_password']
            ]);
        }

        if (!empty($post['password'])) {
            $password = $post['password'];
            $post['password'] = $crypt->hash_password($post['password'], $global_config['hashprefix']);
            $post['pass_creation_time'] = NV_CURRENTTIME;
        } else {
            $password = '';
            $post['password'] = $userdata['password'];
            $post['pass_creation_time'] = !empty($post['password']) ? $userdata['regdate'] : 0;
        }
        if (empty($post['is_official'])) {
            // Khi là thành viên mới thì chỉ có nhóm = 7, không có các nhóm khác
            $post['in_groups'] = [7];
            $post['group_id'] = 7;
        } else {
            // Khi là thành viên chính thức thì cho phép chọn nhóm + nhóm = 4
            $in_groups = [];
            foreach ($post['in_groups'] as $_group_id) {
                if ($_group_id > 9) {
                    $in_groups[] = $_group_id;
                }
            }
            $post['in_groups'] = array_intersect($in_groups, array_keys($groups_list));
            $post['in_groups'] = array_map('intval', $post['in_groups']);

            // Kiểm tra nhóm thành viên mặc định phải thuộc các nhóm đã chọn
            !in_array($post['group_id'], $post['in_groups'], true) && $post['group_id'] = 4;

            $post['in_groups'][] = 4;
        }

        if ($post['pass_reset_request'] > 2 or $post['pass_reset_request'] < 0) {
            $post['pass_reset_request'] = 0;
        }

        $post['email_verification_time'] = $post['is_email_verified'] ? -1 : 0;

        // Kiểm tra các trường dữ liệu tùy biến + Hệ thống
        $query_field = [];
        $valid_field = [];
        if (!empty($array_field_config)) {
            $check = fieldsCheck($custom_fields, $post, $query_field, $valid_field);
            if ($check['status'] == 'error') {
                nv_jsonOutput($check);
            }
        }

        // Check photo
        if (!empty($post['photo'])) {
            $tmp_photo = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $post['photo'];

            if (!nv_is_file($tmp_photo, NV_TEMP_DIR)) {
                $post['photo'] = '';
            } else {
                $new_photo_name = $post['photo'];
                $new_photo_path = NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_upload . '/';

                $new_photo_name2 = $new_photo_name;
                $i = 1;
                while (file_exists($new_photo_path . $new_photo_name2)) {
                    $new_photo_name2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $new_photo_name);
                    ++$i;
                }
                $new_photo = $new_photo_path . $new_photo_name2;

                if (nv_copyfile(NV_DOCUMENT_ROOT . $tmp_photo, $new_photo)) {
                    $post['photo'] = substr($new_photo, strlen(NV_ROOTDIR . '/'));
                } else {
                    $post['photo'] = '';
                }

                nv_deletefile(NV_DOCUMENT_ROOT . $tmp_photo);
            }
        }

        if (empty($post['photo'])) {
            $reg_attribs = !empty($userdata['openid_info']) ? unserialize(nv_base64_decode($userdata['openid_info'])) : [];
            if (!empty($reg_attribs['photo'])) {
                $upload = new NukeViet\Files\Upload(['images'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
                $upload->setLanguage($lang_global);
                $upload_info = $upload->save_urlfile($reg_attribs['photo'], NV_UPLOADS_REAL_DIR . '/' . $module_upload, false);

                if (empty($upload_info['error'])) {
                    $basename = change_alias($post['username']) . '.' . nv_getextension($upload_info['basename']);
                    $newname = $basename;
                    $fullname = $upload_info['name'];

                    $i = 1;
                    while (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $newname)) {
                        $newname = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $basename);
                        ++$i;
                    }

                    $check = nv_renamefile($fullname, NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $newname);

                    if ($check[0] == 1) {
                        $post['photo'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . $newname;
                    }
                }
            }
        }

        $sql = 'INSERT INTO ' . NV_MOD_TABLE . ' (
            group_id, username, md5username, password, email, first_name, last_name, gender, photo, birthday, sig,
            regdate, question, answer, view_mail, remember, in_groups, active,
            idsite, pass_creation_time, pass_reset_request, 
            email_verification_time, active_obj
        ) VALUES (
            :group_id, :username, :md5username, :password, :email, :first_name, :last_name, :gender, :photo, :birthday, :sig,
            ' . $userdata['regdate'] . ', :question, :answer, ' . $post['view_mail'] . ', 1, :in_groups, 1, 
            ' . $userdata['idsite'] . ', ' . $post['pass_creation_time'] . ', ' . $post['pass_reset_request'] . ', 
            ' . $post['email_verification_time'] . ', :active_obj
        )';

        $data_insert = [];
        $data_insert['group_id'] = $post['group_id'];
        $data_insert['username'] = $post['username'];
        $data_insert['md5username'] = $post['md5username'];
        $data_insert['password'] = $post['password'];
        $data_insert['email'] = $post['email'];
        $data_insert['first_name'] = $post['first_name'];
        $data_insert['last_name'] = $post['last_name'];
        $data_insert['gender'] = $post['gender'];
        $data_insert['photo'] = $post['photo'];
        $data_insert['birthday'] = $post['birthday'];
        $data_insert['sig'] = $post['sig'];
        $data_insert['question'] = $post['question'];
        $data_insert['answer'] = $post['answer'];
        $data_insert['in_groups'] = implode(',', $post['in_groups']);
        $data_insert['active_obj'] = $admin_info['userid'];
        $user_id = $db->insert_id($sql, 'userid', $data_insert);

        if (!$user_id) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'mess' => 'DB-error1'
            ]);
        }
        // Luu vao bang OpenID
        if (!empty($reg_attribs)) {
            $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . $user_id . ', :server, :opid , :id, :email)');
            $stmt->bindParam(':server', $reg_attribs['server'], PDO::PARAM_STR);
            $stmt->bindParam(':opid', $reg_attribs['opid'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $reg_attribs['openid'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $reg_attribs['email'], PDO::PARAM_STR);
            $stmt->execute();
        }

        $query_field['userid'] = $user_id;
        if (!userInfoTabDb($query_field)) {
            $db->query('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $user_id);
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'mess' => 'DB-error2'
            ]);
        }

        if (!empty($post['in_groups'])) {
            foreach ($post['in_groups'] as $group_id) {
                if ($group_id != 7 and $group_id != 4) {
                    nv_groups_add_user($group_id, $user_id, 1, $module_data);
                }
            }
        }

        $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=' . ($post['is_official'] ? 4 : 7));
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $userid);

        // Callback sau khi đăng ký
        if (nv_function_exists('nv_user_register_callback')) {
            nv_user_register_callback($user_id);
        }
        // Xóa thông báo hệ thống
        nv_delete_notification(NV_LANG_DATA, $module_name, 'send_active_link_fail', $userid);
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['active_users'], 'userid: ' . $user_id . ' - username: ' . $post['username'], $admin_info['userid']);

        $full_name = nv_show_name_user($post['first_name'], $post['last_name'], $post['username']);
        $subject = $lang_module['adduser_register'];

        $_url = urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN);
        if (!empty($userdata['openid_info'])) {
            if (!empty($password)) {
                $message = sprintf($lang_module['adduser_register_openid_info_with_password'], $full_name, $global_config['site_name'], $_url, ucfirst($reg_attribs['server']), $post['username'], $password);
            } else {
                $message = sprintf($lang_module['adduser_register_openid_info'], $full_name, $global_config['site_name'], $_url, ucfirst($reg_attribs['server']));
            }
        } else {
            if (!empty($password)) {
                $message = sprintf($lang_module['adduser_register_info_with_password'], $full_name, $global_config['site_name'], $_url, $post['username'], $password);
            } else {
                $message = sprintf($lang_module['adduser_register_info'], $full_name, $global_config['site_name'], $_url, $post['username']);
            }
        }

        @nv_sendmail_async([$global_config['site_name'], $global_config['site_email']], $post['email'], $subject, $message);
        nv_jsonOutput([
            'status' => 'OK',
            'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name
        ]);
    }

    $userdata['checkss'] = $checkss;

    // Cau hoi lay lai mat khau
    $data_questions = [];
    $sql = 'SELECT qid, title FROM ' . NV_MOD_TABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $data_questions[$row['qid']] = $row['title'];
    }

    // Xuất HTML
    $xtpl = new XTemplate('user_waitting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('FORM_ACTION', $base_url . '&amp;userid=' . $userid);
    $xtpl->assign('NV_UNICKMIN', $global_config['nv_unickmin']);
    $xtpl->assign('NV_UNICKMAX', $global_config['nv_unickmax']);
    $xtpl->assign('NV_UPASSMAX', $global_config['nv_upassmax']);
    $xtpl->assign('NV_UPASSMIN', $global_config['nv_upassmin']);
    $xtpl->assign('DATA', $userdata);

    for ($i = 0; $i <= 2; ++$i) {
        $xtpl->assign('PASSRESET', [
            'num' => $i,
            'title' => $lang_module['pass_reset_request' . $i]
        ]);
        $xtpl->parse('user_details.pass_reset_request');
    }

    $group_exists = false;
    if (!empty($groups_list)) {
        foreach ($groups_list as $group_id => $grtl) {
            if ($group_id > 9) {
                $xtpl->assign('GROUP', [
                    'id' => $group_id,
                    'title' => $grtl,
                    'checked' => ''
                ]);
                $xtpl->parse('user_details.group.list');
                $group_exists = true;
            }
        }
    }
    if ($group_exists) {
        $xtpl->parse('user_details.group');
    }

    $have_custom_fields = false;
    $have_name_field = false;
    foreach ($array_field_config as $row) {
        if ($row['show_register']) {
            $data = !empty($row['system']) ? $userdata : $userdata['users_info'];
            // Value luôn là giá trị mặc định
            if (!empty($row['field_choices'])) {
                if ($row['field_type'] == 'date') {
                    $row['value'] = isset($data[$row['field']]) ? $data[$row['field']] : (($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value']);
                } elseif ($row['field_type'] == 'number') {
                    $row['value'] = isset($data[$row['field']]) ? $data[$row['field']] : $row['default_value'];
                } else {
                    $temp = array_map('strval', array_keys($row['field_choices']));
                    $tempkey = isset($data[$row['field']]) ? $data[$row['field']] : $row['default_value'];
                    $row['value'] = in_array($tempkey, $temp, true) ? $tempkey : '';
                }
            } else {
                $row['value'] = isset($data[$row['field']]) ? $data[$row['field']] : $row['default_value'];
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
                    $xtpl->parse('user_details.' . $show_key . '.required');
                }
                if ($row['field'] == 'gender') {
                    foreach ($global_array_genders as $gender) {
                        $gender['selected'] = $row['value'] == $gender['key'] ? ' selected="selected"' : '';
                        $xtpl->assign('GENDER', $gender);
                        $xtpl->parse('user_details.' . $show_key . '.gender');
                    }
                }
                if ($row['field'] == 'question') {
                    foreach ($data_questions as $question) {
                        $xtpl->assign('QUESTION', $question);
                        $xtpl->parse('user_details.' . $show_key . '.question');
                    }
                }
                if ($row['description']) {
                    $xtpl->parse('user_details.' . $show_key . '.description');
                }
                $xtpl->parse('user_details.' . $show_key);
            } else {
                if ($row['required']) {
                    $xtpl->parse('user_details.field.loop.required');
                }
                if ($row['description']) {
                    $xtpl->parse('user_details.field.loop.description');
                }
                if ($row['field_type'] == 'textbox' or $row['field_type'] == 'number') {
                    $xtpl->parse('user_details.field.loop.textbox');
                } elseif ($row['field_type'] == 'date') {
                    $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
                    $xtpl->assign('FIELD', $row);
                    $xtpl->parse('user_details.field.loop.date');
                } elseif ($row['field_type'] == 'textarea') {
                    $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
                    $xtpl->assign('FIELD', $row);
                    $xtpl->parse('user_details.field.loop.textarea');
                } elseif ($row['field_type'] == 'editor') {
                    $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
                    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                        $array_tmp = explode('@', $row['class']);
                        $edits = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value']);
                        $xtpl->assign('EDITOR', $edits);
                        $xtpl->parse('user_details.field.loop.editor');
                    } else {
                        $row['class'] = '';
                        $xtpl->assign('FIELD', $row);
                        $xtpl->parse('user_details.field.loop.textarea');
                    }
                } elseif ($row['field_type'] == 'select') {
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('user_details.field.loop.select.loop');
                    }
                    $xtpl->parse('user_details.field.loop.select');
                } elseif ($row['field_type'] == 'radio') {
                    $number = 0;
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('user_details.field.loop.radio');
                    }
                } elseif ($row['field_type'] == 'checkbox') {
                    $number = 0;
                    $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => (in_array((string) $key, $valuecheckbox, true)) ? ' checked="checked"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('user_details.field.loop.checkbox');
                    }
                } elseif ($row['field_type'] == 'multiselect') {
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('user_details.field.loop.multiselect.loop');
                    }
                    $xtpl->parse('user_details.field.loop.multiselect');
                }
                $xtpl->parse('user_details.field.loop');
                $have_custom_fields = true;
            }
        }
    }

    if ($have_name_field) {
        $xtpl->parse('user_details.name_show_' . $global_config['name_show']);
    }
    if ($have_custom_fields) {
        $xtpl->parse('user_details.field');
    }

    $xtpl->parse('user_details');
    $contents = $xtpl->text('user_details');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

delOldRegAccount();

$methods = [
    'userid' => [
        'key' => 'userid',
        'sql' => 'userid',
        'value' => $lang_module['search_id'],
        'selected' => ''
    ],
    'username' => [
        'key' => 'username',
        'sql' => 'username',
        'value' => $lang_module['search_account'],
        'selected' => ''
    ],
    'full_name' => [
        'key' => 'full_name',
        'sql' => $global_config['name_show'] == 0 ? "concat(last_name,' ',first_name)" : "concat(first_name,' ',last_name)",
        'value' => $lang_module['search_name'],
        'selected' => ''
    ],
    'email' => [
        'key' => 'email',
        'sql' => 'email',
        'value' => $lang_module['search_mail'],
        'selected' => ''
    ]
];
$method = $nv_Request->isset_request('method', 'post') ? $nv_Request->get_string('method', 'post', '') : ($nv_Request->isset_request('method', 'get') ? urldecode($nv_Request->get_string('method', 'get', '')) : '');
$methodvalue = $nv_Request->isset_request('value', 'post') ? $nv_Request->get_string('value', 'post') : ($nv_Request->isset_request('value', 'get') ? urldecode($nv_Request->get_string('value', 'get', '')) : '');

$orders = [
    'userid',
    'username',
    'full_name',
    'email',
    'regdate'
];
$orderby = $nv_Request->get_string('sortby', 'get', '');
$ordertype = $nv_Request->get_string('sorttype', 'get', '');
if ($ordertype != 'ASC') {
    $ordertype = 'DESC';
}

$ar_where = [];
if ($global_config['idsite'] > 0) {
    $ar_where[] = 'idsite=' . $global_config['idsite'];
}
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_MOD_TABLE . '_reg');

if (!empty($method) and isset($methods[$method]) and !empty($methodvalue)) {
    $base_url .= '&amp;method=' . urlencode($method) . '&amp;value=' . urlencode($methodvalue);
    $methods[$method]['selected'] = ' selected="selected"';
    $table_caption = $lang_module['search_page_title'];
    $ar_where[] = $methods[$method]['sql'] . " LIKE '%" . $db->dblikeescape($methodvalue) . "%'";
}
if (!empty($ar_where)) {
    $db->where(implode(' AND ', $ar_where));
}

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

$num_items = $db->query($db->sql())
    ->fetchColumn();

$db->select('*')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

if (!empty($orderby) and in_array($orderby, $orders, true)) {
    $orderby_sql = $orderby != 'full_name' ? $orderby : ($global_config['name_show'] == 0 ? "concat(first_name,' ',last_name)" : "concat(last_name,' ',first_name)");
    $db->order($orderby_sql . ' ' . $ordertype);
    $base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
}

$result = $db->query($db->sql());

$users_list = [];
while ($row = $result->fetch()) {
    $users_list[$row['userid']] = [
        'userid' => $row['userid'],
        'username' => $row['username'],
        'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username']),
        'email' => $row['email'],
        'regdate' => date('d/m/Y H:i', $row['regdate'])
    ];
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$head_tds = [];
$head_tds['userid']['title'] = $lang_module['userid'];
$head_tds['userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=userid&amp;sorttype=ASC';
$head_tds['username']['title'] = $lang_module['account'];
$head_tds['username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=username&amp;sorttype=ASC';
$head_tds['full_name']['title'] = $lang_module['name'];
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=full_name&amp;sorttype=ASC';
$head_tds['email']['title'] = $lang_module['email'];
$head_tds['email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=email&amp;sorttype=ASC';
$head_tds['regdate']['title'] = $lang_module['register_date'];
$head_tds['regdate']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=regdate&amp;sorttype=ASC';

foreach ($orders as $order) {
    if ($orderby == $order and $ordertype == 'ASC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=' . $order . '&amp;sorttype=DESC';
        $head_tds[$order]['title'] .= ' &darr;';
    } elseif ($orderby == $order and $ordertype == 'DESC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=' . $order . '&amp;sorttype=ASC';
        $head_tds[$order]['title'] .= ' &uarr;';
    }
}

if (defined('NV_IS_USER_FORUM')) {
    $lang_module['warning'] = $lang_module['modforum'];
} else {
    $register_active_time = isset($global_users_config['register_active_time']) ? round((int) $global_users_config['register_active_time'] / 3600) : 24;
    $lang_module['warning'] = sprintf($lang_module['userwait_note'], $register_active_time);
}

$xtpl = new XTemplate('user_waitting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting');
$xtpl->assign('SORTURL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
$xtpl->assign('SEARCH_VALUE', nv_htmlspecialchars($methodvalue));
$xtpl->assign('TABLE_CAPTION', $table_caption);

foreach ($methods as $m) {
    $xtpl->assign('METHODS', $m);
    $xtpl->parse('main.method');
}

if ($num_items > 0) {
    $xtpl->assign('RESEND_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting_remail');
    $xtpl->parse('main.resend_email');

    if ($register_active_time) {
        $xtpl->parse('main.userlist.warning');
    }

    foreach ($head_tds as $head_td) {
        $xtpl->assign('HEAD_TD', $head_td);
        $xtpl->parse('main.userlist.head_td');
    }

    foreach ($users_list as $u) {
        $u['checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $u['userid']);
        $xtpl->assign('CONTENT_TD', $u);
        $xtpl->assign('ACTIVATE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;userid=' . $u['userid']);
        $xtpl->parse('main.userlist.xusers');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.userlist.generate_page');
    }

    $xtpl->parse('main.userlist');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
