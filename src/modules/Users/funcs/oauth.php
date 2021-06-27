<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
}

if (!empty($nv_redirect) and nv_redirect_decrypt($nv_redirect) != '') {
    $nv_Request->set_Session('nv_redirect_' . $module_data, $nv_redirect);
}

if ($global_config['allowuserlogin'] and defined('NV_OPENID_ALLOWED')) {
    $server = $nv_Request->get_string('server', 'get', '');

    if (!empty($server) and in_array($server, $global_config['openid_servers'], true)) {
        $global_config['avatar_width'] = $global_users_config['avatar_width'];
        $global_config['avatar_height'] = $global_users_config['avatar_height'];

        if (file_exists(NV_ROOTDIR . '/modules/Users/login/oauth-' . $server . '.php')) {
            include NV_ROOTDIR . '/modules/Users/login/oauth-' . $server . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/Users/login/cas-' . $server . '.php')) {
            include NV_ROOTDIR . '/modules/Users/login/cas-' . $server . '.php';
        }
    }
}

nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
