<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$js = $nv_Request->get_int('js', 'get', 0);
$is_system = $nv_Request->get_int('system', 'get', 0);
$log_userid = $is_system ? 0 : $admin_info['admin_id'];

if ($js) {
    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $admin_info['username'] . '] ' . $nv_Lang->getGlobal('admin_logout_title'), ' Client IP:' . NV_CLIENT_IP, $log_userid);
    nv_admin_logout();
    if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
        require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/logout.php';
    }
    exit('1');
}

$ok = $nv_Request->get_int('ok', 'get', 0);
if ($ok) {
    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $admin_info['username'] . '] ' . $nv_Lang->getGlobal('admin_logout_title'), ' Client IP:' . NV_CLIENT_IP, $log_userid);
    nv_admin_logout();
    $info = $nv_Lang->getGlobal('admin_logout_ok');
    $info .= '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />';
    if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
        require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/logout.php';
    }
} else {
    $url = ($client_info['referer'] != '') ? $client_info['referer'] : ($_SERVER['SCRIPT_URI'] ?? '');
    $info = $nv_Lang->getGlobal('admin_logout_question') . " ?<br /><br />\n";
    $info .= '<a href="' . NV_BASE_SITEURL . 'index.php?second=admin_logout&amp;ok=1">' . $nv_Lang->getGlobal('ok') . "</a> | \n";
    $info .= '<a href="' . $url . '">' . $nv_Lang->getGlobal('cancel') . "</a>\n";
}

nv_info_die($global_config['site_description'], $nv_Lang->getGlobal('admin_logout_title'), $info);
