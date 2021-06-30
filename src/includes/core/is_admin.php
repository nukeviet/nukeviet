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

$admin_cookie = $nv_Request->get_string('admin', 'session');
$admin_online = $nv_Request->get_string('online', 'session');

if (!empty($admin_cookie)) {
    if (empty($admin_online)) {
        $nv_Request->unset_request('admin,online', 'session');
        $info = 'Hacking attempt';
        $info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
        exit($info);
    }

    if (!nv_admin_checkip()) {
        $nv_Request->unset_request('admin,online', 'session');
        $info = 'Note: You are not signed in as admin!<br />Your IP address is incorrect!';
        $info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
        exit($info);
    }

    if (defined('NV_ADMIN')) {
        if (!nv_admin_checkfirewall()) {
            $nv_Request->unset_request('admin,online', 'session');
            $info = 'Note: You are not signed in as admin!<br />This Firewall system does not accept your login information!';
            $info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
            exit($info);
        }
    }

    $admin_info = nv_admin_checkdata($admin_cookie);

    if ($admin_info == []) {
        $nv_Request->unset_request('admin,online', 'session');
        $info = 'Note: You are not signed in as admin!<br />Session Expired!Please Re-Login!';
        $info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
        exit($info);
    }

    //Admin thoat
    $_second = $nv_Request->get_string('second', 'get');
    if ($_second == 'admin_logout') {
        if (defined('NV_IS_USER_FORUM')) {
            define('NV_IS_MOD_USER', true);
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/logout.php';
        } else {
            $nv_Request->unset_request('nvloginhash', 'cookie');
        }
        require_once NV_ROOTDIR . '/includes/core/admin_logout.php';
    }

    define('NV_IS_ADMIN', true);

    if ($admin_info['level'] == 1 or $admin_info['level'] == 2) {
        define('NV_IS_SPADMIN', true);
    }

    if ($admin_info['level'] == 1 and $global_config['idsite'] == 0) {
        define('NV_IS_GODADMIN', true);
    }

    if (!defined('ADMIN_LOGIN_MODE')) {
        define('ADMIN_LOGIN_MODE', 3);
    }
    if (ADMIN_LOGIN_MODE == 2 and !defined('NV_IS_SPADMIN')) {
        $nv_Request->unset_request('admin,online', 'session');
        $info = 'Note: Access denied in Admin Panel!<br />Only God-Admin and Super-Admin has access in Admin Panel!';
        $info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
        exit($info);
    }

    if (ADMIN_LOGIN_MODE == 1 and !defined('NV_IS_GODADMIN')) {
        $nv_Request->unset_request('admin,online', 'session');
        $info = 'Note: Access denied in Admin Panel!<br />Only God-Admin has access in Admin Panel!';
        $info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
        exit($info);
    }

    if (!empty($admin_info['editor'])) {
        if (file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . $admin_info['editor'] . '/nv.php')) {
            if (!defined('NV_EDITOR')) {
                define('NV_EDITOR', $admin_info['editor']);
            }
            if (!defined('NV_IS_' . strtoupper($admin_info['editor']))) {
                define('NV_IS_' . strtoupper($admin_info['editor']), true);
            }
        }
    }

    if (!empty($admin_info['allow_files_type'])) {
        if (!defined('NV_ALLOW_FILES_TYPE')) {
            define('NV_ALLOW_FILES_TYPE', implode('|', array_intersect($global_config['file_allowed_ext'], $admin_info['allow_files_type'])));
        }
        if (!defined('NV_ALLOW_UPLOAD_FILES')) {
            define('NV_ALLOW_UPLOAD_FILES', true);
        }
    }

    if (!empty($admin_info['allow_modify_files'])) {
        if (!defined('NV_ALLOW_MODIFY_FILES')) {
            define('NV_ALLOW_MODIFY_FILES', true);
        }
    }

    if (!empty($admin_info['allow_create_subdirectories'])) {
        if (!defined('NV_ALLOW_CREATE_SUBDIRECTORIES')) {
            define('NV_ALLOW_CREATE_SUBDIRECTORIES', true);
        }
    }

    if (!empty($admin_info['allow_modify_subdirectories'])) {
        if (!defined('NV_ALLOW_MODIFY_SUBDIRECTORIES')) {
            define('NV_ALLOW_MODIFY_SUBDIRECTORIES', true);
        }
    }

    $admin_online = explode('|', $admin_online);
    $admin_info['checkpass'] = (int) ($admin_online[0]);
    $admin_info['last_online'] = (int) ($admin_online[2]);
    $admin_info['checkhits'] = (int) ($admin_online[3]);
    if ($_second == 'time_login') {
        $time_login = [];
        $time_login['showtimeoutsess'] = (NV_CURRENTTIME + 63 - $admin_info['last_online'] > $global_config['admin_check_pass_time']) ? 1 : 0;
        $time_login['check_pass_time'] = ($global_config['admin_check_pass_time'] - (NV_CURRENTTIME - $admin_info['last_online']) - 63) * 1000;

        nv_jsonOutput($time_login);
    }

    if ($admin_info['checkpass']) {
        if ((NV_CURRENTTIME - $admin_info['last_online']) > $global_config['admin_check_pass_time']) {
            $nv_Request->unset_request('admin,online', 'session');
            if (!defined('NV_IS_AJAX')) {
                nv_redirect_location($client_info['selfurl']);
            }
            exit();
        }
    }
    if ($nv_Request->get_title(NV_OP_VARIABLE, 'get') != 'notification') {
        $nv_Request->set_Session('online', $admin_info['checkpass'] . '|' . $admin_info['last_online'] . '|' . NV_CURRENTTIME . '|' . $admin_info['checkhits']);
    }
    $admin_info['full_name'] = nv_show_name_user($admin_info['first_name'], $admin_info['last_name']);
    if (defined('SSO_REGISTER_DOMAIN') and !empty($admin_info['photo'])) {
        $admin_info['avata'] = SSO_REGISTER_DOMAIN . NV_BASE_SITEURL . $admin_info['photo'];
    }
}

unset($admin_cookie, $admin_online);
