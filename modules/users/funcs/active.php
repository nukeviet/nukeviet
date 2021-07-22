<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

if (defined('NV_IS_USER_FORUM')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$userid = $nv_Request->get_int('userid', 'get', '', 1);
$checknum = $nv_Request->get_title('checknum', 'get', '', 1);

if (empty($userid) or empty($checknum)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$register_active_time = isset($global_users_config['register_active_time']) ? $global_users_config['register_active_time'] : 86400;
if ($register_active_time > 0) {
    $del = NV_CURRENTTIME - $register_active_time;
    $sql = 'DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE regdate < ' . $del;
    $db->query($sql);
}

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $userid;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $mod_title = $lang_module['register'];
$key_words = $module_info['keywords'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&userid=' . $userid . '&checknum=' . $checknum;

$check_update_user = false;
$is_change_email = false;

if ($checknum == $row['checknum']) {
    if (empty($row['password']) and substr($row['username'], 0, 20) == 'CHANGE_EMAIL_USERID_') {
        $is_change_email = true;

        $userid_change_email = (int) (substr($row['username'], 20));
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET email=:email, email_verification_time=' . NV_CURRENTTIME . ' WHERE userid=' . $userid_change_email);
        $stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid= :userid');
            $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
            $stmt->execute();
            $check_update_user = true;
        }
    } elseif (!defined('NV_IS_USER') and $global_config['allowuserreg'] == 2) {
        $sql = 'INSERT INTO ' . NV_MOD_TABLE . " (
            group_id, username, md5username, password, email, first_name, last_name,
            gender, photo, birthday, regdate, question, answer,
            passlostkey, view_mail, remember, in_groups,
            active, checknum, last_login, last_ip, last_agent, last_openid, idsite, email_verification_time,
            active_obj
        ) VALUES (
            :group_id, :username, :md5_username, :password, :email, :first_name, :last_name,
            :gender, '', :birthday, :regdate, :question, :answer,
            '', 0, 1, :in_groups,
            1, '', 0, '', '', '', " . $global_config['idsite'] . ', ' . NV_CURRENTTIME . ",
            'EMAIL'
        )";

        $data_insert = [];
        $data_insert['group_id'] = (!empty($global_users_config['active_group_newusers']) ? 7 : 4);
        $data_insert['username'] = $row['username'];
        $data_insert['md5_username'] = nv_md5safe($row['username']);
        $data_insert['password'] = $row['password'];
        $data_insert['email'] = $row['email'];
        $data_insert['first_name'] = $row['first_name'];
        $data_insert['last_name'] = $row['last_name'];
        $data_insert['gender'] = $row['gender'];
        $data_insert['birthday'] = $row['birthday'];
        $data_insert['regdate'] = $row['regdate'];
        $data_insert['question'] = $row['question'];
        $data_insert['answer'] = $row['answer'];
        $data_insert['in_groups'] = $data_insert['group_id'];

        $userid = $db->insert_id($sql, 'userid', $data_insert);
        if ($userid) {
            $users_info = unserialize(nv_base64_decode($row['users_info']));
            $query_field = [];
            $query_field['userid'] = $userid;
            $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY fid ASC');
            while ($row_f = $result_field->fetch()) {
                if ($row_f['is_system'] == 1) {
                    continue;
                }
                if ($row_f['field_type'] == 'number' or $row_f['field_type'] == 'date') {
                    $default_value = (float) ($row_f['default_value']);
                } else {
                    $default_value = $db->quote($row_f['default_value']);
                }
                $query_field[$row_f['field']] = (isset($users_info[$row_f['field']])) ? $users_info[$row_f['field']] : $default_value;
            }

            if ($db->exec('INSERT INTO ' . NV_MOD_TABLE . '_info (' . implode(', ', array_keys($query_field)) . ') VALUES (' . implode(', ', array_values($query_field)) . ')')) {
                if (!empty($global_users_config['active_group_newusers'])) {
                    nv_groups_add_user(7, $row['userid'], 1, $module_data);
                } else {
                    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=4');
                }
                $db->query('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $row['userid']);

                // Callback sau khi đăng ký
                if (nv_function_exists('nv_user_register_callback')) {
                    nv_user_register_callback($userid);
                }

                $check_update_user = true;
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['account_active_log'], $row['username'] . ' | ' . $client_info['ip'], 0);
            } else {
                $db->query('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid);
            }

            $nv_Cache->delMod($module_name);
        }
    }
}

if ($check_update_user) {
    if ($is_change_email) {
        $info = $lang_module['account_change_mail_ok'] . "<br /><br />\n";
    } else {
        $info = $lang_module['account_active_ok'] . "<br /><br />\n";
    }
} else {
    if ($is_change_email) {
        $info = $lang_module['account_active_error'] . "<br /><br />\n";
    } else {
        $info = $lang_module['account_change_mail_error'] . "<br /><br />\n";
    }
}

$nv_redirect = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true);
if (defined('SSO_REGISTER_SECRET')) {
    $sso_redirect_users = $nv_Request->get_title('sso_redirect_users', 'session', '');
    $iv = substr(SSO_REGISTER_SECRET, 0, 16);
    $sso_redirect_users = strtr($sso_redirect_users, '-_,', '+/=');
    $sso_redirect_users = openssl_decrypt($sso_redirect_users, 'aes-256-cbc', SSO_REGISTER_SECRET, 0, $iv);
    if (!empty($sso_redirect_users)) {
        $nv_redirect = $sso_redirect_users;
    }
    $nv_Request->unset_request('sso_redirect_' . $module_data, 'session');
}

$info .= '<img border="0" src="' . NV_STATIC_URL . NV_ASSETS_DIR . "/images/load_bar.gif\"><br /><br />\n";
$info .= '[<a href="' . $nv_redirect . '">' . $lang_module['redirect_to_login'] . '</a>]';

$contents = user_info_exit($info);
$contents .= '<meta http-equiv="refresh" content="5;url=' . $nv_redirect . '" />';

$canonicalUrl = getCanonicalUrl($page_url, true);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
