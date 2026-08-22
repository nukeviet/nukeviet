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

$page_title = $table_caption = $nv_Lang->getModule('member_wating');
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting';

$array_field_config = nv_get_users_field_config();

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

// Xóa tài khoản chờ kích hoạt
if ($nv_Request->isset_request('del', 'post')) {
    $userid = $nv_Request->get_absint('userid', 'post', 0);
    if (csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        $stmt = $db->prepare('SELECT users_info FROM ' . NV_MOD_TABLE . '_reg WHERE userid = :userid' . ($global_config['idsite'] > 0 ? ' AND idsite = :idsite' : ''));
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        if ($global_config['idsite'] > 0) {
            $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
        }
        $stmt->execute();
        $users_info = $stmt->fetchColumn();

        if (!empty($users_info)) {
            $users_info = json_decode($users_info, true);
            foreach ($users_info as $key => $value) {
                if (!empty($value)) {
                    if ($array_field_config[$key]['field_type'] == 'file') {
                        $value = array_map('trim', explode(',', $value));
                        foreach ($value as $file) {
                            $file_save_info = get_file_save_info($file);
                            if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
                                delete_userfile($file_save_info);
                            }
                        }
                    }
                }
            }
        }

        $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid = :userid' . ($global_config['idsite'] > 0 ? ' AND idsite = :idsite' : ''));
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        if ($global_config['idsite'] > 0) {
            $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
        }
        if ($stmt->execute()) {
            nv_delete_notification(NV_LANG_DATA, $module_name, 'send_active_link_fail', $userid);
            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('delete'), 'userid_reg: ' . $userid, $admin_info['userid']);
            nv_jsonOutput([
                'status' => 'success',
                'mess' => '',
                'refresh' => true
            ]);
        }
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'NO'
        ]);
    } else {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
}

// Kích hoạt tài khoản
if ($nv_Request->isset_request('userid', 'get')) {
    if ($global_config['max_user_number']) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . ($global_config['idsite'] > 0 ? ' WHERE idsite = :idsite' : ''));
        if ($global_config['idsite'] > 0) {
            $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
        }
        $stmt->execute();
        $user_number = $stmt->fetchColumn();

        if ($user_number >= $global_config['max_user_number']) {
            $contents = $nv_Lang->getGlobal('limit_user_number', $global_config['max_user_number']);
            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($contents, $showheader);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }

    $userid = $nv_Request->get_int('userid', 'get', 0);
    $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE userid = :userid' . ($global_config['idsite'] > 0 ? ' AND idsite = :idsite' : ''));
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    if ($global_config['idsite'] > 0) {
        $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    }
    $stmt->execute();
    $userdata = $stmt->fetch();
    $stmt->closeCursor();
    if (empty($userdata)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    $userdata['users_info'] = json_decode($userdata['users_info'], true);
    $userdata['photo'] = '';

    $groups_list = nv_groups_list($module_data);

    // Nếu chấp nhận kích hoạt
    if (csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        $post = [
            'username' => $nv_Request->get_title('username', 'post', ''),
            'email' => nv_strtolower($nv_Request->get_title('email', 'post', '', 100)),
            'password' => $nv_Request->get_title('password', 'post', ''),
            're_password' => $nv_Request->get_title('re_password', 'post', ''),
            'pass_reset_request' => $nv_Request->get_int('pass_reset_request', 'post', 0),
            'email_reset_request' => $nv_Request->get_int('email_reset_request', 'post', 0),
            'first_name' => $nv_Request->get_title('first_name', 'post', '', 255),
            'last_name' => $nv_Request->get_title('last_name', 'post', '', 255),
            'gender' => nv_substr($nv_Request->get_title('gender', 'post', ''), 0, 1),
            'birthday' => $nv_Request->get_title('birthday', 'post'),
            'sig' => $nv_Request->get_title('sig', 'post', ''),
            'question' => $nv_Request->get_title('question', 'post', '', 255),
            'answer' => $nv_Request->get_title('answer', 'post', '', 255),
            'photo' => $nv_Request->get_title('photo', 'post', '', 255),
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
                'mess' => $nv_Lang->getModule('account_deny_name', $post['username'])
            ]);
        }

        // Kiểm tra username đã tồn tại chưa
        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE username LIKE :username OR md5username = :md5username');
        $stmt->bindValue(':username', $post['username'], PDO::PARAM_STR);
        $stmt->bindValue(':md5username', $post['md5username'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_username = $stmt->fetchColumn();

        if ($query_error_username) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'username',
                'mess' => $nv_Lang->getModule('edit_error_username_exist')
            ]);
        }

        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE userid != :userid AND (username LIKE :username OR md5username = :md5username)');
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt->bindValue(':username', $post['username'], PDO::PARAM_STR);
        $stmt->bindValue(':md5username', $post['md5username'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_username = $stmt->fetchColumn();

        if ($query_error_username) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'username',
                'mess' => $nv_Lang->getModule('edit_error_username_exist')
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

        // Kiểm tra email đã tồn tại chưa
        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE email = :email');
        $stmt->bindValue(':email', $post['email'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_email = $stmt->fetchColumn();

        if ($query_error_email) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'email',
                'mess' => $nv_Lang->getModule('edit_error_email_exist')
            ]);
        }

        // Kiểm tra email đã tồn tại trong users_reg chưa
        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE userid != :userid AND email = :email');
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt->bindValue(':email', $post['email'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_email_reg = $stmt->fetchColumn();

        if ($query_error_email_reg) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'email',
                'mess' => $nv_Lang->getModule('edit_error_email_exist')
            ]);
        }

        // Kiểm tra email đã tồn tại trong users_openid chưa
        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_openid WHERE email = :email');
        $stmt->bindValue(':email', $post['email'], PDO::PARAM_STR);
        $stmt->execute();
        $query_error_email_openid = $stmt->fetchColumn();

        if ($query_error_email_openid) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'email',
                'mess' => $nv_Lang->getModule('edit_error_email_exist')
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
                'mess' => $nv_Lang->getModule('edit_error_password')
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
            // Khi là thành viên mới thì chỉ có nhóm = 7
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
            $post['in_groups'][] = 4; // Add default official group
            $post['in_groups'] = array_map('intval', $post['in_groups']);
            $post['in_groups'] = array_unique($post['in_groups']);

            // Kiểm tra nhóm mặc định phải thuộc các nhóm đã chọn
            if (!in_array($post['group_id'], $post['in_groups'], true)) {
                $post['group_id'] = 4;
            }
        }

        if ($post['pass_reset_request'] > 2 or $post['pass_reset_request'] < 0) {
            $post['pass_reset_request'] = 0;
        }
        if ($post['email_reset_request'] > 2 or $post['email_reset_request'] < 0) {
            $post['email_reset_request'] = 0;
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

        // Kiểm tra ảnh đại diện
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

        // Thông tin OAuth lưu khi đăng ký, dùng cho cả ảnh đại diện và bảng _openid
        $reg_attribs = !empty($userdata['openid_info']) ? json_decode($userdata['openid_info'], true) : [];

        // Lấy ảnh đại diện từ OAuth nếu admin không chọn ảnh khác
        if (empty($post['photo']) and !empty($reg_attribs['photo'])) {
            $upload = new NukeViet\Files\Upload(['images'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $upload->setLanguage(\NukeViet\Core\Language::$lang_global);
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
                    $post['photo'] = substr(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $newname, strlen(NV_ROOTDIR . '/'));
                }
            }
        }

        $sql = 'INSERT INTO ' . NV_MOD_TABLE . ' (
            group_id, username, md5username, password, email, first_name, last_name, gender, photo, birthday, sig,
            regdate, question, answer, view_mail, remember, in_groups, active,
            idsite, pass_creation_time, pass_reset_request, email_creation_time, email_reset_request,
            email_verification_time, active_obj
        ) VALUES (
            :group_id, :username, :md5username, :password, :email, :first_name, :last_name, :gender, :photo, :birthday, :sig,
            :regdate, :question, :answer, :view_mail, 1, :in_groups, 1,
            :idsite, :pass_creation_time, :pass_reset_request, :email_creation_time, :email_reset_request,
            :email_verification_time, :active_obj
        )';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':group_id', $post['group_id'], PDO::PARAM_INT);
        $stmt->bindValue(':username', $post['username'], PDO::PARAM_STR);
        $stmt->bindValue(':md5username', $post['md5username'], PDO::PARAM_STR);
        $stmt->bindValue(':password', $post['password'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $post['email'], PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $post['first_name'], PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $post['last_name'], PDO::PARAM_STR);
        $stmt->bindValue(':gender', $post['gender'], PDO::PARAM_STR);
        $stmt->bindValue(':photo', $post['photo'], PDO::PARAM_STR);
        $stmt->bindValue(':birthday', !empty($post['birthday']) ? nv_d2u_post($post['birthday']) : 0, PDO::PARAM_INT);
        $stmt->bindValue(':sig', $post['sig'], PDO::PARAM_STR);
        $stmt->bindValue(':regdate', $userdata['regdate'], PDO::PARAM_INT);
        $stmt->bindValue(':question', $post['question'], PDO::PARAM_STR);
        $stmt->bindValue(':answer', $post['answer'], PDO::PARAM_STR);
        $stmt->bindValue(':view_mail', $post['view_mail'], PDO::PARAM_INT);
        $stmt->bindValue(':in_groups', implode(',', $post['in_groups']), PDO::PARAM_STR);
        $stmt->bindValue(':idsite', $userdata['idsite'], PDO::PARAM_INT);
        $stmt->bindValue(':pass_creation_time', $post['pass_creation_time'], PDO::PARAM_INT);
        $stmt->bindValue(':pass_reset_request', $post['pass_reset_request'], PDO::PARAM_INT);
        $stmt->bindValue(':email_creation_time', $userdata['regdate'], PDO::PARAM_INT);
        $stmt->bindValue(':email_reset_request', $post['email_reset_request'], PDO::PARAM_INT);
        $stmt->bindValue(':email_verification_time', $post['email_verification_time'], PDO::PARAM_INT);
        $stmt->bindValue(':active_obj', $admin_info['userid'], PDO::PARAM_INT);

        $ok = $stmt->execute();
        $user_id = $db->lastInsertId();

        if (!$user_id) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'mess' => 'DB-error1'
            ]);
        }
        // Lưu vào bảng OpenID
        if (!empty($reg_attribs)) {
            $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid (
                userid, openid, opid, id, email
            ) VALUES (
                :userid, :openid, :opid, :id, :email
            )');
            $stmt->bindValue(':userid', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':openid', $reg_attribs['server'], PDO::PARAM_STR);
            $stmt->bindValue(':opid', $reg_attribs['opid'], PDO::PARAM_STR);
            $stmt->bindValue(':id', $reg_attribs['openid'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $reg_attribs['email'], PDO::PARAM_STR);
            $stmt->execute();
        }

        $query_field['userid'] = $user_id;
        if (!userInfoTabDb($query_field)) {
            $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
            $stmt->bindValue(':userid', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'mess' => 'DB-error2'
            ]);
        }

        if (!empty($userdata['users_info'])) {
            foreach ($userdata['users_info'] as $key => $value) {
                if (!empty($value)) {
                    if ($array_field_config[$key]['field_type'] == 'file') {
                        $old_values = array_map('trim', explode(',', $value));
                        $temp_value = $query_field[$array_field_config[$key]['field']];
                        !empty($temp_value) && $temp_value = array_map('trim', explode(',', $temp_value));
                        foreach ($old_values as $old_value) {
                            if (empty($temp_value) or !in_array($old_value, $temp_value, true)) {
                                $file_save_info = get_file_save_info($old_value);
                                if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
                                    delete_userfile($file_save_info);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($post['in_groups'])) {
            foreach ($post['in_groups'] as $group_id) {
                if ($group_id != 7 and $group_id != 4) {
                    nv_groups_add_user($group_id, $user_id, 1, $module_data);
                }
            }
        }

        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers + 1 WHERE group_id = :gid');
        $stmt->bindValue(':gid', ($post['is_official'] ? 4 : 7), PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid = :userid');
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt->execute();

        // Callback sau khi đăng ký
        if (nv_function_exists('nv_user_register_callback')) {
            /** @disregard P1010 */
            // phpcs:ignore
            nv_user_register_callback($user_id);
        }
        // Xóa thông báo hệ thống
        nv_delete_notification(NV_LANG_DATA, $module_name, 'send_active_link_fail', $userid);
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('active_users'), 'userid: ' . $user_id . ' - username: ' . $post['username'], $admin_info['userid']);

        $maillang = NV_LANG_INTERFACE;
        if (NV_LANG_DATA != NV_LANG_INTERFACE) {
            $maillang = NV_LANG_DATA;
        }

        if (!nv_apply_hook($module_name, 'admin_active_account', [$user_id, $post, $userdata, $maillang], false)) {
            $send_data = [[
                'to' => $post['email'],
                'data' => [
                    'first_name' => $post['first_name'],
                    'last_name' => $post['last_name'],
                    'username' => $post['username'],
                    'email' => $post['email'],
                    'gender' => $post['gender'],
                    'lang' => $maillang,
                    'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN),
                    'oauth_name' => !empty($reg_attribs['server']) ? ucfirst($reg_attribs['server']) : '',
                    'password' => $password,
                    'pass_reset' => $post['pass_reset_request'],
                    'email_reset' => $post['email_reset_request']
                ]
            ]];
            nv_sendmail_template_async([$module_name, Emails::ACTIVE_BY_ADMIN], $send_data, $maillang);
        }
        nv_jsonOutput([
            'status' => 'success',
            'mess' => '',
            'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=user_waiting', true)
        ]);
    }

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
        if ($row['show_register'] && !empty($row['system'])) {
            $data = $userdata;
            // Tính value từ dữ liệu
            if (!empty($row['field_choices'])) {
                if ($row['field_type'] == 'date') {
                    $row['value'] = $data[$row['field']] ?? (($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value']);
                } elseif ($row['field_type'] == 'number') {
                    $row['value'] = $data[$row['field']] ?? $row['default_value'];
                } else {
                    $temp = array_map('strval', array_keys($row['field_choices']));
                    $tempkey = $data[$row['field']] ?? get_value_by_lang($row['default_value']);
                    $row['value'] = in_array($tempkey, $temp, true) ? $tempkey : '';
                }
            } else {
                $row['value'] = $data[$row['field']] ?? get_value_by_lang($row['default_value']);
            }

            // Xử lý đặc biệt cho một số trường
            if ($row['field'] == 'birthday') {
                $row['value'] = nv_u2d_post($row['value']);
            } elseif ($row['field'] == 'sig') {
                $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
            }

            $row['required'] = (bool) $row['required'];

            // Chuẩn bị dữ liệu gender
            if ($row['field'] == 'gender') {
                $gender_options = [];
                foreach ($global_array_genders as $g) {
                    $g['selected'] = ($row['value'] == $g['key']);
                    $gender_options[] = $g;
                }
                $row['gender_options'] = $gender_options;
            }

            // Chuẩn bị danh sách câu hỏi bảo mật
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
        if ($row['show_register'] && empty($row['system'])) {
            $data = $userdata['users_info'] ?? [];
            // Tính value từ dữ liệu tùy biến
            if (!empty($row['field_choices'])) {
                if ($row['field_type'] == 'date') {
                    $row['value'] = $data[$row['field']] ?? (($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value']);
                } elseif ($row['field_type'] == 'number') {
                    $row['value'] = $data[$row['field']] ?? $row['default_value'];
                } else {
                    $temp = array_map('strval', array_keys($row['field_choices']));
                    $tempkey = $data[$row['field']] ?? get_value_by_lang($row['default_value']);
                    $row['value'] = in_array($tempkey, $temp, true) ? $tempkey : '';
                }
            } else {
                $row['value'] = $data[$row['field']] ?? get_value_by_lang($row['default_value']);
            }

            $row['required'] = (bool) $row['required'];

            // Chuẩn bị choices với trạng thái selected/checked
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

            // Xử lý theo field_type
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
                $filelist = !empty($row['value']) ? explode(',', $row['value']) : [];
                $file_items = [];
                foreach ($filelist as $file_item) {
                    $assign = file_type_name($file_item);
                    $assign['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;userfile=' . $file_item;
                    $file_items[] = $assign;
                }
                $row['file_items'] = $file_items;
                $row['limited_values'] = !empty($row['limited_values']) ? json_decode($row['limited_values'], true) : [];
                $row['fileaccept'] = !empty($row['limited_values']['mime']) ? '.' . implode(',.', $row['limited_values']['mime']) : '';
                $row['filemaxsize'] = $row['limited_values']['file_max_size'] ?? 0;
                $row['filemaxsize_format'] = nv_convertfromBytes($row['limited_values']['file_max_size'] ?? 0);
                $row['filemaxnum'] = $row['limited_values']['maxnum'] ?? 0;
                $row['csrf'] = csrf_create($module_name . '_field_' . $row['field']);
                $row['url_module'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
                $row['widthlimit'] = image_size_info($row['limited_values']['widthlimit'] ?? '', 'width');
                $row['heightlimit'] = image_size_info($row['limited_values']['heightlimit'] ?? '', 'height');
                $row['addfile_disabled'] = !(empty($row['limited_values']['maxnum']) or (count($filelist) < $row['limited_values']['maxnum']));
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

    // Chuẩn bị options cho pass_reset_request
    $pass_reset_options = [];
    for ($i = 0; $i <= 2; ++$i) {
        $pass_reset_options[] = [
            'num' => $i,
            'title' => $nv_Lang->getModule('pass_reset_request' . $i),
            'selected' => ($userdata['pass_reset_request'] ?? 0) == $i
        ];
    }

    // Chuẩn bị options cho email_reset_request
    $email_reset_options = [];
    for ($i = 0; $i <= 2; ++$i) {
        $email_reset_options[] = [
            'num' => $i,
            'title' => $nv_Lang->getModule('email_reset_request' . $i),
            'selected' => ($userdata['email_reset_request'] ?? 0) == $i
        ];
    }

    // Xuất giao diện form kích hoạt
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('user_waitting.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('VIEW', 'detail');
    $tpl->assign('FORM_ACTION', $base_url . '&amp;userid=' . $userid);
    $tpl->assign('NV_UNICKMIN', $global_config['nv_unickmin']);
    $tpl->assign('NV_UNICKMAX', $global_config['nv_unickmax']);
    $tpl->assign('NV_UPASSMAX', $global_config['nv_upassmax']);
    $tpl->assign('NV_UPASSMIN', $global_config['nv_upassmin']);
    $tpl->assign('DATA', $userdata);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('SYSTEM_FIELDS', $system_fields);
    $tpl->assign('CUSTOM_FIELDS', $custom_fields_data);
    $tpl->assign('HAVE_CUSTOM_FIELDS', $have_custom_fields);
    $tpl->assign('HAVE_NAME_FIELD', $have_name_field);
    $tpl->assign('NAME_SHOW', (int) $global_config['name_show']);
    $tpl->assign('GROUPS_FOR_TPL', $groups_for_tpl);
    $tpl->assign('GROUP_EXISTS', $group_exists);
    $tpl->assign('PASS_RESET_OPTIONS', $pass_reset_options);
    $tpl->assign('EMAIL_RESET_OPTIONS', $email_reset_options);
    $tpl->assign('AVATAR_UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
    $contents = $tpl->fetch('user_waitting.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

delOldRegAccount();

// Các phương thức tìm kiếm
$methods = [
    'userid' => [
        'key' => 'userid',
        'sql' => 'userid',
        'value' => $nv_Lang->getModule('search_id'),
        'selected' => false
    ],
    'username' => [
        'key' => 'username',
        'sql' => 'username',
        'value' => $nv_Lang->getModule('search_account'),
        'selected' => false
    ],
    'full_name' => [
        'key' => 'full_name',
        'sql' => $global_config['name_show'] == 0 ? "concat(last_name,' ',first_name)" : "concat(first_name,' ',last_name)",
        'value' => $nv_Lang->getModule('search_name'),
        'selected' => false
    ],
    'email' => [
        'key' => 'email',
        'sql' => 'email',
        'value' => $nv_Lang->getModule('search_mail'),
        'selected' => false
    ]
];
$method = $nv_Request->isset_request('method', 'post') ? $nv_Request->get_string('method', 'post', '') : ($nv_Request->isset_request('method', 'get') ? urldecode($nv_Request->get_string('method', 'get', '')) : '');
$methodvalue = $nv_Request->isset_request('value', 'post') ? $nv_Request->get_string('value', 'post') : ($nv_Request->isset_request('value', 'get') ? urldecode($nv_Request->get_string('value', 'get', '')) : '');
$ar_where = [];
$params = [];
if ($global_config['idsite'] > 0) {
    $ar_where[] = 'idsite = :idsite';
    $params[':idsite'] = [$global_config['idsite'], PDO::PARAM_INT];
}

if (!empty($method) and isset($methods[$method]) and !empty($methodvalue)) {
    $base_url .= '&amp;method=' . urlencode($method) . '&amp;value=' . urlencode($methodvalue);
    $methods[$method]['selected'] = true;
    $table_caption = $nv_Lang->getModule('search_page_title');
    $ar_where[] = $methods[$method]['sql'] . ' LIKE :methodvalue';
    $params[':methodvalue'] = ['%' . $db->dblikeescape($methodvalue, true) . '%', PDO::PARAM_STR];
}

$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 30;

$where_sql = !empty($ar_where) ? ' WHERE ' . implode(' AND ', $ar_where) : '';

$stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_reg' . $where_sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val[0], $val[1]);
}
$stmt->execute();
$num_items = (int) $stmt->fetchColumn();
$stmt->closeCursor();

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

$orderby_sql = '';
if (!empty($orderby) and in_array($orderby, $orders, true)) {
    $orderby_sql_field = $orderby != 'full_name' ? $orderby : ($global_config['name_show'] == 0 ? 'concat(first_name,\' \',last_name)' : 'concat(last_name,\' \',first_name)');
    $orderby_sql = ' ORDER BY ' . $orderby_sql_field . ' ' . $ordertype;
    $base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
}

$stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_reg' . $where_sql . $orderby_sql . ' LIMIT :limit OFFSET :offset');
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val[0], $val[1]);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
$stmt->execute();

// Chuẩn bị danh sách user cho Smarty
$users_list_for_tpl = [];
while ($row = $stmt->fetch()) {
    $users_list_for_tpl[] = [
        'userid' => $row['userid'],
        'username' => $row['username'],
        'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username']),
        'email' => $row['email'],
        'regdate' => nv_datetime_format($row['regdate']),
        'checkss' => csrf_create($csrf_key),
        'activate_url' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;userid=' . $row['userid']
    ];
}
$stmt->closeCursor();

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

// Chuẩn bị tiêu đề cột bảng với link sắp xếp
$head_tds = [];
$head_tds['userid']['title'] = $nv_Lang->getModule('userid');
$head_tds['userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=userid&amp;sorttype=ASC';
$head_tds['username']['title'] = $nv_Lang->getGlobal('username');
$head_tds['username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=username&amp;sorttype=ASC';
$head_tds['full_name']['title'] = $nv_Lang->getModule('name');
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=full_name&amp;sorttype=ASC';
$head_tds['email']['title'] = $nv_Lang->getModule('email');
$head_tds['email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=email&amp;sorttype=ASC';
$head_tds['regdate']['title'] = $nv_Lang->getModule('register_date');
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

// Chuẩn bị cảnh báo và thời gian kích hoạt
$register_active_time = 0;
if (defined('NV_IS_USER_FORUM')) {
    $nv_Lang->setModule('warning', $nv_Lang->getModule('modforum'));
} else {
    $register_active_time = isset($global_users_config['register_active_time']) ? round((int) $global_users_config['register_active_time'] / 3600) : 24;
    $nv_Lang->setModule('warning', $nv_Lang->getModule('userwait_note', $register_active_time));
}

// URL gửi lại email
$resend_url = ($num_items > 0) ? NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting_remail' : '';

// Xuất giao diện danh sách
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('user_waitting.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('VIEW', 'list');
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting');
$tpl->assign('SEARCH_VALUE', nv_htmlspecialchars($methodvalue));
$tpl->assign('TABLE_CAPTION', $table_caption);
$tpl->assign('METHODS', $methods);
$tpl->assign('NUM_ITEMS', (int) $num_items);
$tpl->assign('RESEND_URL', $resend_url);
$tpl->assign('REGISTER_ACTIVE_TIME', $register_active_time);
$tpl->assign('HEAD_TDS', $head_tds);
$tpl->assign('USERS_LIST', $users_list_for_tpl);
$tpl->assign('GENERATE_PAGE', $generate_page);
$contents = $tpl->fetch('user_waitting.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
