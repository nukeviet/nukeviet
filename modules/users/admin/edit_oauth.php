<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
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

$sql = 'SELECT openid, opid, id, email FROM ' . NV_MOD_TABLE . '_openid WHERE userid=' . $row['userid'];
$array_oauth = $db->query($sql)->fetchAll();

if (empty($array_oauth)) {
    $xtpl->parse('empty');
    $contents = $xtpl->text('empty');
} else {
    // Xóa OpenID của thành viên
    if ($nv_Request->isset_request('del', 'post')) {
        if (!defined('NV_IS_AJAX')) {
            exit('Wrong URL');
        }

        $opid = $nv_Request->get_title('opid', 'post', '');
        $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_openid WHERE opid=' . $db->quote($opid) . ' AND userid=' . $row['userid'];
        $openid = $db->query($sql)->fetch();

        if (!empty($openid)) {
            $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE opid= :opid AND userid=' . $row['userid']);
            $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
            $stmt->execute();

            // Gửi email thông báo
            if (!empty($global_users_config['admin_email'])) {
                $url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/openid', true);
                $message = sprintf($lang_module['security_alert_openid_delete'], $openid['openid'], $row['username'], $url);
                nv_sendmail([
                    $global_config['site_name'],
                    $global_config['site_email']
                ], $row['email'], $lang_module['security_alert'], $message);
            }

            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_delete_one_openid', 'userid ' . $row['userid'], $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            exit('OK');
        }

        exit('NO');
    }

    // Xóa tất cả các OpenID của thành viên
    if ($nv_Request->isset_request('delall', 'post')) {
        if (!defined('NV_IS_AJAX')) {
            exit('Wrong URL');
        }

        if ($db->exec('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE userid=' . $row['userid'])) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_delete_all_openid', 'userid ' . $row['userid'], $admin_info['userid']);

            // Gửi email thông báo
            if (!empty($global_users_config['admin_email'])) {
                $url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/openid', true);
                $message = sprintf($lang_module['security_alert_openid_truncate'], $row['username'], $url);
                nv_sendmail([
                    $global_config['site_name'],
                    $global_config['site_email']
                ], $row['email'], $lang_module['security_alert'], $message);
            }

            $nv_Cache->delMod($module_name);
            exit('OK');
        }

        exit('NO');
    }

    foreach ($array_oauth as $oauth) {
        $oauth['email_or_id'] = !empty($oauth['email']) ? $oauth['email'] : $oauth['id'];
        $xtpl->assign('OAUTH', $oauth);
        $xtpl->parse('main.oauth');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
