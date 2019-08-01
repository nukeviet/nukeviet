<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:23
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    die('Stop!!!');
}

if (!defined('NV_IS_SPADMIN')) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$admin_id = $nv_Request->get_int('admin_id', 'get', 0);

if (empty($admin_id) or $admin_id == $admin_info['admin_id']) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $admin_id;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}


if ($row['lev'] == 1 or (!defined('NV_IS_GODADMIN') and $row['lev'] == 2)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

/**
 * @param string $adminpass
 * @return boolean
 */
function nv_checkAdmpass($adminpass)
{
    global $db, $admin_info, $crypt;

    $sql = 'SELECT password FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_info['userid'];
    $pass = $db->query($sql)->fetchColumn();
    return $crypt->validate_password($adminpass, $pass);
}

$access_admin = $db->query("SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='access_admin'")->fetchColumn();
$access_admin = unserialize($access_admin);
$level = $admin_info['level'];

$array_action_account = [];
$array_action_account[0] = $nv_Lang->getModule('action_account_nochange');
if (isset($access_admin['access_waiting'][$level]) and $access_admin['access_waiting'][$level] == 1) {
    $array_action_account[1] = $nv_Lang->getModule('action_account_suspend');
}
if (isset($access_admin['access_delus'][$level]) and $access_admin['access_delus'][$level] == 1) {
    $array_action_account[2] = $nv_Lang->getModule('action_account_del');
}

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id;
$row_user = $db->query($sql)->fetch();

$action_account = $nv_Request->get_int('action_account', 'post', 0);
$action_account = (isset($array_action_account[$action_account])) ? $action_account : 0;
$error = '';
$checkss = md5($admin_id . NV_CHECK_SESSION);
if ($nv_Request->get_title('ok', 'post', 0) == $checkss) {
    $sendmail = $nv_Request->get_int('sendmail', 'post', 0);
    $reason = $nv_Request->get_title('reason', 'post', '', 1);
    $adminpass = $nv_Request->get_title('adminpass_iavim', 'post');

    if (empty($adminpass)) {
        $error = $nv_Lang->getGlobal('admin_password_empty');
    } elseif (!nv_checkAdmpass($adminpass)) {
        $error = sprintf($nv_Lang->getGlobal('adminpassincorrect'), $adminpass);
        $adminpass = '';
    } else {
        if ($row['lev'] == 3) {
            $is_delCache = false;
            $array_keys = array_keys($site_mods);
            foreach ($array_keys as $mod) {
                if (!empty($mod)) {
                    if (!empty($site_mods[$mod]['admins'])) {
                        $admins = explode(',', $site_mods[$mod]['admins']);
                        if (in_array($admin_id, $admins)) {
                            $admins = array_diff($admins, [$admin_id]);
                            $admins = implode(',', $admins);

                            $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET admins= :admins WHERE title= :mod');
                            $sth->bindParam(':admins', $admins, PDO::PARAM_STR);
                            $sth->bindParam(':mod', $mod, PDO::PARAM_STR);
                            $sth->execute();

                            $is_delCache = true;
                        }
                    }
                }
            }
            if ($is_delCache) {
                $nv_Cache->delMod('modules');
            }
        }
        $db->query('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = ' . $admin_id);
        if ($action_account == 1) {
            $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET active=0 WHERE userid=' . $admin_id);
        } elseif ($action_account == 2) {
            try {
                $db->query('UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers-1 WHERE group_id IN (SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $admin_id . ' AND approved = 1)');
            } catch (PDOException $e) {
                trigger_error(print_r($e, true));
            }
            $db->query('DELETE FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $admin_id);
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE userid=' . $admin_id);
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_info WHERE userid=' . $admin_id);
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id);
            if (!empty($row_user['photo']) and is_file(NV_ROOTDIR . '/' . $row_user['photo'])) {
                @nv_deletefile(NV_ROOTDIR . '/' . $row_user['photo']);
            }
        }

        if ($action_account != 2) {
            nv_groups_del_user($row['lev'], $admin_id);

            // Cập nhật lại nhóm nếu không xóa tài khoản
            if ($row_user['group_id'] == $row['lev']) {
                // Nếu nhóm mặc định là quản trị này thì chuyển về thành viên chính thức
                $row_user['group_id'] = 4;
            }
            $row_user['in_groups'] = explode(',', $row_user['in_groups']);
            $row_user['in_groups'] = array_diff($row_user['in_groups'], [$row['lev']]);
            $row_user['in_groups'] = array_filter(array_unique(array_map('trim', $row_user['in_groups'])));
            $row_user['in_groups'] = empty($row_user['in_groups']) ? '' : implode(',', $row_user['in_groups']);

            $sql = "UPDATE " . NV_USERS_GLOBALTABLE . " SET group_id=" . $row_user['group_id'] . ", in_groups=" . $db->quote($row_user['in_groups']) . " WHERE userid=" . $admin_id;
            $db->query($sql);
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nv_admin_del'), 'Username: ' . $row_user['username'] . ', ' . $array_action_account[$action_account], $admin_info['userid']);

        $db->query('OPTIMIZE TABLE ' . NV_AUTHORS_GLOBALTABLE);

        if ($sendmail) {
            $send_data = [[
                'to' => [$row_user['email']],
                'data' => [
                    $admin_info,
                    $global_config,
                    $reason
                ]
            ]];
            $send = nv_sendmail_from_template(NukeViet\Template\Email\Tpl::E_AUTHOR_DELETE, $send_data);
            if (!$send) {
                $page_title = $nv_Lang->getGlobal('error_info_caption');
                $url_back = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
                $contents = nv_theme_alert($page_title, $nv_Lang->getGlobal('error_sendmail_admin'), 'danger', $url_back, '', 10);

                include NV_ROOTDIR . '/includes/header.php';
                echo nv_admin_theme($contents);
                include NV_ROOTDIR . '/includes/footer.php';
            }
        }
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
} else {
    $sendmail = 1;
    $reason = $adminpass = '';
}

$array = [
    'sendmail' => $sendmail,
    'reason' => $reason,
    'adminpass' => $adminpass,
    'action_account' => $action_account,
];

$page_title = $nv_Lang->getModule('nv_admin_del');

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('CHECKSS', $checkss);
$tpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del&amp;admin_id=' . $admin_id);
$tpl->assign('ERROR', $error);
$tpl->assign('ROW_USER', $row_user);
$tpl->assign('DATA', $array);
$tpl->assign('ARRAY_ACTION_ACCOUNT', $array_action_account);

$contents = $tpl->fetch('del.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
