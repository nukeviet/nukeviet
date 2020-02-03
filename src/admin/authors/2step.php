<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:17
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    die('Stop!!!');
}

$admin_id = $nv_Request->get_absint('admin_id', 'get', $admin_info['admin_id']);

$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $admin_id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$allowed = false;
if (defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) {
    $allowed = true;
} elseif (defined('NV_IS_SPADMIN')) {
    if ($row['admin_id'] == $admin_info['admin_id']) {
        $allowed = true;
    } elseif ($row['lev'] == 3 and $global_config['spadmin_add_admin'] == 1) {
        $allowed = true;
    }
} else {
    if ($row['admin_id'] == $admin_info['admin_id']) {
        $allowed = true;
    }
}

if (empty($allowed)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id;
$row_user = $db->query($sql)->fetch();
if (empty($row_user)) {
    trigger_error('Data error: No user for admin account!', 256);
}
$error = '';

// Xác định quyền sửa tài khoản thành viên
$sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='access_admin'";
$config_user = $db->query($sql)->fetchColumn();
$config_user = empty($config_user) ? [] : unserialize($config_user);
$manager_user_2step = false;
if (
    isset($site_mods['users']) and isset($config_user['access_editus']) and !empty($config_user['access_editus'][$admin_info['level']])
    and ($admin_info['admin_id'] == $row['admin_id'] or $admin_info['level'] < $row['lev'])
    and (empty($global_config['idsite']) or $global_config['idsite'] == $row_user['idsite'])
) {
    $manager_user_2step = true;
}

$page_title = $nv_Lang->getModule('2step_manager') . ': ' . $row_user['username'];

$tpl = new \NukeViet\Template\Smarty();
$tpl->registerPlugin('modifier', 'date', 'nv_date');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('USERID', $row['admin_id']);
$tpl->assign('ADMIN_INFO', $admin_info);
$tpl->assign('ACTIVE2STEP', $row_user['active2step']);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('ALLOWED_MANAGER_2STEP', $manager_user_2step);

if ($row['admin_id'] == $admin_info['admin_id']) {
    // Xác định các cổng Oauth hỗ trợ
    $server_allowed = [];
    if (!empty($global_config['facebook_client_id']) and !empty($global_config['facebook_client_secret'])) {
        $server_allowed['facebook'] = 1;
    }
    if (!empty($global_config['google_client_id']) and !empty($global_config['google_client_secret'])) {
        $server_allowed['google'] = 1;
    }

    // Thêm mới tài khoản Oauth
    if (isset($server_allowed[($opt = $nv_Request->get_title('auth', 'get', ''))])) {
        define('NV_ADMIN_2STEP_OAUTH', true);
        require NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_file . '/2step_' . $opt . '.php';

        if (!empty($_GET['code']) and empty($error)) {
            if (empty($attribs)) {
                $error = $nv_Lang->getGlobal('admin_oauth_error_getdata');
            } else {
                // Kiểm tra trùng
                $sql = "SELECT * FROM " . NV_AUTHORS_GLOBALTABLE . "_oauth WHERE oauth_uid=" . $db->quote($attribs['full_identity']) . "
                AND admin_id=" . $row['admin_id'] . " AND oauth_server=" . $db->quote($opt);
                if ($db->query($sql)->fetch()) {
                    $error = $nv_Lang->getModule('2step_error_oauth_exists');
                }
            }

            if (empty($error)) {
                // Thêm mới vào CSDL
                $sql = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_oauth (
                    admin_id, oauth_server, oauth_uid, oauth_email, addtime
                ) VALUES (
                    " . $row['admin_id'] . ", " . $db->quote($opt) . ", " . $db->quote($attribs['full_identity']) . ",
                    " . $db->quote($attribs['email']) . ", " . NV_CURRENTTIME . "
                )";
                if (!$db->insert_id($sql, 'id')) {
                    $error = $nv_Lang->getGlobal('admin_oauth_error_savenew');
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_OAUTH', $opt . ': ' . $attribs['email'], $admin_info['userid']);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                }
            }
        }
    }

    $tpl->assign('SERVER_ALLOWED', $server_allowed);
}

// Danh sách các cổng xác thực
$array_oauth = [];
$sql = "SELECT * FROM " . NV_AUTHORS_GLOBALTABLE . "_oauth WHERE admin_id=" . $row['admin_id'] . " ORDER BY addtime DESC";
$result = $db->query($sql);
while ($_row = $result->fetch()) {
    $array_oauth[$_row['id']] = $_row;
}

// Xóa tất cả
if ($nv_Request->get_title('delall', 'post', '') === NV_CHECK_SESSION) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $sql = "DELETE FROM " . NV_AUTHORS_GLOBALTABLE . "_oauth WHERE admin_id=" . $row['admin_id'];
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_TRUNCATE_OAUTH', 'AID ' . $row['admin_id'], $admin_info['userid']);
    nv_htmlOutput('OK');
}

// Xóa một tài khoản
if ($nv_Request->get_title('del', 'post', '') === NV_CHECK_SESSION) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $id = $nv_Request->get_absint('id', 'post', 0);
    if (!isset($array_oauth[$id])) {
        nv_htmlOutput('NO');
    }

    $sql = "DELETE FROM " . NV_AUTHORS_GLOBALTABLE . "_oauth WHERE admin_id=" . $row['admin_id'] . " AND id=" . $id;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_OAUTH', 'AID ' . $row['admin_id'] . ': ' . $array_oauth[$id]['oauth_server'] . '|' . $array_oauth[$id]['oauth_email'], $admin_info['userid']);
    nv_htmlOutput('OK');
}

$tpl->assign('ERROR', $error);
$tpl->assign('ARRAY_OAUTH', $array_oauth);

$contents = $tpl->fetch('2step.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
