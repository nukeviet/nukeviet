<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('nv_admin_del');

if (!defined('NV_IS_SPADMIN')) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$admin_id = $nv_Request->get_int('admin_id', 'get', 0);

if (empty($admin_id) or $admin_id == $admin_info['admin_id']) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$stmt = $db->prepare('SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = :admin_id');
$stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if ($row['lev'] == 1 or (!defined('NV_IS_GODADMIN') and $row['lev'] == 2)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

/**
 * @param string $adminpass
 * @return bool
 */
function nv_checkAdmpass($adminpass)
{
    global $db, $admin_info, $crypt;

    $stmt = $db->prepare('SELECT password FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :userid');
    $stmt->bindValue(':userid', $admin_info['userid'], PDO::PARAM_INT);
    $stmt->execute();
    $pass = $stmt->fetchColumn();

    return $crypt->validate_password($adminpass, $pass);
}

$access_admin = $db->query('SELECT content FROM ' . NV_USERS_GLOBALTABLE . "_config WHERE config='access_admin'")->fetchColumn();
$access_admin = unserialize($access_admin, NV_UNSERIALIZE_SAFE);
$level = $admin_info['level'];

$array_action_account = [];
$array_action_account[0] = $nv_Lang->getModule('action_account_nochange');
if (isset($access_admin['access_waiting'][$level]) and $access_admin['access_waiting'][$level] == 1) {
    $array_action_account[1] = $nv_Lang->getModule('action_account_suspend');
}
if (isset($access_admin['access_delus'][$level]) and $access_admin['access_delus'][$level] == 1) {
    $array_action_account[2] = $nv_Lang->getModule('action_account_del');
}

$stmt = $db->prepare('SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :userid');
$stmt->bindValue(':userid', $admin_id, PDO::PARAM_INT);
$stmt->execute();
$row_user = $stmt->fetch();
$stmt->closeCursor();

$action_account = $nv_Request->get_int('action_account', 'post', 0);
$action_account = (isset($array_action_account[$action_account])) ? $action_account : 0;
$_csrf_key = $csrf_key . '_' . $admin_id;

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
} else {
    $respon = [
        'status' => 'error',
        'mess' => '',
    ];

    $sendmail = $nv_Request->get_int('sendmail', 'post', 0);
    $reason = $nv_Request->get_title('reason', 'post', '', 1);
    $adminpass = $nv_Request->get_title('adminpass_iavim', 'post');

    if (empty($adminpass)) {
        $respon['input'] = 'adminpass_iavim';
        $respon['mess'] = $nv_Lang->getGlobal('admin_password_empty');
        nv_jsonOutput($respon);
    }
    if (!nv_checkAdmpass($adminpass)) {
        $respon['input'] = 'adminpass_iavim';
        $respon['mess'] = $nv_Lang->getGlobal('adminpassincorrect', $adminpass);
        nv_jsonOutput($respon);
    }

    if ($row['lev'] == 3) {
        foreach ($global_config['setup_langs'] as $l) {
            $is_delCache = false;
            $_site_mods = nv_site_mods($l);
            $array_keys = array_keys($_site_mods);
            foreach ($array_keys as $mod) {
                if (!empty($mod)) {
                    if (!empty($_site_mods[$mod]['admins'])) {
                        $admins = array_map('intval', explode(',', $_site_mods[$mod]['admins']));
                        if (in_array($admin_id, $admins, true)) {
                            $admins = array_diff($admins, [$admin_id]);
                            $admins = implode(',', $admins);

                            $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $l . '_modules SET admins= :admins WHERE title= :mod');
                            $sth->bindParam(':admins', $admins, PDO::PARAM_STR);
                            $sth->bindParam(':mod', $mod, PDO::PARAM_STR);
                            $sth->execute();

                            $is_delCache = true;
                        }
                    }
                }
            }
            if ($is_delCache) {
                $nv_Cache->delMod('modules', $l);
            }
        }
    }

    $stmt = $db->prepare('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = :admin_id');
    $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $db->prepare('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . '_vars WHERE admin_id = :admin_id');
    $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($action_account == 1) {
        // Đình chỉ tài khoản
        $stmt_update = $db->prepare('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET active = 0 WHERE userid = :userid');
        $stmt_update->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt_update->execute();
    } elseif ($action_account == 2) {
        // Xóa tài khoản
        try {
            $stmt_update = $db->prepare('UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers - 1 WHERE group_id IN (SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid = :userid AND approved = 1)');
            $stmt_update->bindValue(':userid', $admin_id, PDO::PARAM_INT);
            $stmt_update->execute();
        } catch (PDOException $e) {
            trigger_error(print_r($e, true));
        }
        $stmt_del = $db->prepare('DELETE FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid = :userid');
        $stmt_del->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt_del->execute();

        $stmt_del = $db->prepare('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE userid = :userid');
        $stmt_del->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt_del->execute();

        $stmt_del = $db->prepare('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_info WHERE userid = :userid');
        $stmt_del->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt_del->execute();

        $stmt_del = $db->prepare('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_passkey WHERE userid = :userid');
        $stmt_del->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt_del->execute();

        $stmt_del = $db->prepare('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_deleted WHERE userid = :userid');
        $stmt_del->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt_del->execute();

        $stmt_del = $db->prepare('DELETE FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :userid');
        $stmt_del->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt_del->execute();
        if (!empty($row_user['photo']) and is_file(NV_ROOTDIR . '/' . $row_user['photo'])) {
            @nv_deletefile(NV_ROOTDIR . '/' . $row_user['photo']);
        }
        // Xóa API
        $stmt_del = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = :userid');
        $stmt_del->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt_del->execute();
        nv_apply_hook('users', 'user_delete', [$admin_id, $row_user]);
    }

    if ($action_account != 2) {
        // Xóa API cho admin
        $stmt = $db->prepare("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_api_role_credential tb1 INNER JOIN " . $db_config['prefix'] . "_api_role tb2 ON (tb2.role_id = tb1.role_id) WHERE tb1.userid = :userid AND tb2.role_object = 'admin'");
        $stmt->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        if ($count) {
            $stmt = $db->prepare("SELECT tb1.id FROM " . $db_config['prefix'] . "_api_role_credential tb1 INNER JOIN " . $db_config['prefix'] . "_api_role tb2 ON (tb2.role_id = tb1.role_id) WHERE tb1.userid = :userid AND tb2.role_object = 'admin'");
            $stmt->bindValue(':userid', $admin_id, PDO::PARAM_INT);
            $stmt->execute();
            $credential_ids = [];
            while ($row = $stmt->fetch()) {
                $credential_ids[] = $row['id'];
            }
            $stmt->closeCursor();

            $credential_ids = implode(', ', $credential_ids);
            $stmt_del = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_api_role_credential WHERE id IN (' . $credential_ids . ') AND userid = :userid');
            $stmt_del->bindValue(':userid', $admin_id, PDO::PARAM_INT);
            $stmt_del->execute();
        }

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

        $stmt = $db->prepare('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET group_id = :group_id, in_groups = :in_groups WHERE userid = :userid');
        $stmt->bindValue(':group_id', $row_user['group_id'], PDO::PARAM_INT);
        $stmt->bindValue(':in_groups', $row_user['in_groups'], PDO::PARAM_STR);
        $stmt->bindValue(':userid', $admin_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nv_admin_del'), 'Username: ' . $row_user['username'] . ', ' . $array_action_account[$action_account], $admin_info['userid']);

    $db->query('OPTIMIZE TABLE ' . NV_AUTHORS_GLOBALTABLE);

    if ($sendmail) {
        $maillang = NV_LANG_INTERFACE;
        if (!empty($row_user['language']) and in_array($row_user['language'], $global_config['setup_langs'], true)) {
            if ($row_user['language'] != NV_LANG_INTERFACE) {
                $maillang = $row_user['language'];
            }
        } elseif (NV_LANG_DATA != NV_LANG_INTERFACE) {
            $maillang = NV_LANG_DATA;
        }

        $send_data = [[
            'to' => $row_user['email'],
            'data' => [
                'lang' => $maillang,
                'time' => NV_CURRENTTIME,
                'note' => $reason,
                'email' => $admin_info['view_mail'] ? $admin_info['email'] : $global_config['site_email'],
                'sig' => (!empty($admin_info['sig']) ? $admin_info['sig'] : 'All the best'),
                'position' => $admin_info['position'],
                'username' => $admin_info['username']
            ]
        ]];
        $send = nv_sendmail_from_template(NukeViet\Template\Email\Tpl::E_AUTHOR_DELETE, $send_data, $maillang);
        if (!$send) {
            $respon['redirect'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
            $respon['status'] = 'OK';
            $respon['warning'] = 1;
            $respon['timeout'] = 5000;
            $respon['mess'] = $nv_Lang->getGlobal('error_sendmail_admin');
            nv_jsonOutput($respon);
        }
    }

    $respon['redirect'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
    $respon['status'] = 'OK';
    nv_jsonOutput($respon);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('del.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('CHECKSS', csrf_create($_csrf_key));
$tpl->assign('OP', $op);
$tpl->assign('MODULE_NAME', $module_name);

$tpl->assign('ADMIN_ID', $admin_id);
$tpl->assign('USER', $row_user);
$tpl->assign('LIST_ACTION_ACCOUNT', $array_action_account);
$tpl->assign('ACTION_ACCOUNT', $action_account);

$contents = $tpl->fetch('del.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
