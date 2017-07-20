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

$userid = $nv_Request->get_int('userid', 'get,post', 0);

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $lang_module['user_oauthmanager'] . ' ' . $row['username'];

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

// Thêm vào menu top
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $row['userid']] = $lang_module['edit_title'];
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_2step&amp;userid=' . $row['userid']] = $lang_module['user_2step_mamager'];

$xtpl = new XTemplate('user_oauth.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('USERID', $row['userid']);

$sql = "SELECT openid, opid, email FROM " . NV_MOD_TABLE . "_openid WHERE userid=" . $row['userid'];
$array_oauth = $db->query($sql)->fetchAll();

if (empty($array_oauth)) {
    $xtpl->parse('empty');
    $contents = $xtpl->text('empty');
} else {
    // Xóa OpenID của thành viên
    if ($nv_Request->isset_request('del', 'post')) {
        if (! defined('NV_IS_AJAX')) {
            die('Wrong URL');
        }

        $opid = $nv_Request->get_title('opid', 'post', '');
        if ($opid) {
            $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE opid= :opid AND userid=' . $row['userid']);
            $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
            $stmt->execute();
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_delete_one_openid', 'userid ' . $row['userid'], $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            die('OK');
        }

        die('NO');
    }

    // Xóa tất cả các OpenID của thành viên
    if ($nv_Request->isset_request('delall', 'post')) {
        if (! defined('NV_IS_AJAX')) {
            die('Wrong URL');
        }

        if ($db->exec("DELETE FROM " . NV_MOD_TABLE . "_openid WHERE userid=" . $row['userid'])) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_delete_all_openid', 'userid ' . $row['userid'], $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            die('OK');
        }

        die('NO');
    }

    foreach ($array_oauth as $oauth) {
        $xtpl->assign('OAUTH', $oauth);
        $xtpl->parse('main.oauth');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';