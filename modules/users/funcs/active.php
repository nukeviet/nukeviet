<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
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

$check_update_user = false;
$is_change_email = false;

if ($checknum == $row['checknum']) {
    if (empty($row['password']) and substr($row['username'], 0, 20) == 'CHANGE_EMAIL_USERID_') {
        $is_change_email = true;

        $userid_change_email = intval(substr($row['username'], 20));
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET email=:email, email_verification_time=' . NV_CURRENTTIME . ' WHERE userid=' . $userid_change_email);
        $stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid= :userid');
            $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
            $stmt->execute();
            $check_update_user = true;
        }
    } elseif (!defined('NV_IS_USER') and $global_config['allowuserreg'] == 2) {
        $sql = "INSERT INTO " . NV_MOD_TABLE . " (
            group_id, username, md5username, password, email, first_name, last_name,
            gender, photo, birthday, regdate, question, answer,
            passlostkey, view_mail, remember, in_groups,
            active, checknum, last_login, last_ip, last_agent, last_openid, idsite, email_verification_time
        ) VALUES (
            :group_id, :username, :md5_username, :password, :email, :first_name, :last_name,
            :gender, '', :birthday, :regdate, :question, :answer,
            '', 0, 1, :in_groups,
            1, '', 0, '', '', '', " . $global_config['idsite'] . ", " . NV_CURRENTTIME . "
        )";

        $data_insert = array();
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
            $query_field = array();
            $query_field['userid'] = $userid;
            $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY fid ASC');
            while ($row_f = $result_field->fetch()) {
                if ($row_f['system'] == 1) continue;
                if ($row_f['field_type'] == 'number' or $row_f['field_type'] == 'date') {
                    $default_value = floatval($row_f['default_value']);
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

$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/images/load_bar.gif\"><br /><br />\n";
$info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_login'] . "</a>]";

$contents = user_info_exit($info);
$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . "\" />";

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
