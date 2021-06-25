<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $admin_info['username'] . '] ' . $lang_global['admin_logout_title'], ' Client IP:' . NV_CLIENT_IP, $log_userid);
    $nv_Request->unset_request('admin,online', 'session');
    session_destroy();
    exit('1');
}

$ok = $nv_Request->get_int('ok', 'get', 0);
if ($ok) {
    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $admin_info['username'] . '] ' . $lang_global['admin_logout_title'], ' Client IP:' . NV_CLIENT_IP, $log_userid);
    $nv_Request->unset_request('admin,online', 'session');
    session_destroy();
    $info = $lang_global['admin_logout_ok'];
    $info .= '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />';
} else {
    $url = ($client_info['referer'] != '') ? $client_info['referer'] : (isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : '');
    $info = $lang_global['admin_logout_question'] . " ?<br /><br />\n";
    $info .= '<a href="' . NV_BASE_SITEURL . 'index.php?second=admin_logout&amp;ok=1">' . $lang_global['ok'] . "</a> | \n";
    $info .= '<a href="' . $url . '">' . $lang_global['cancel'] . "</a>\n";
}

nv_info_die($global_config['site_description'], $lang_global['admin_logout_title'], $info);
