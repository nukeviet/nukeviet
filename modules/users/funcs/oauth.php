<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (! defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
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

    if (! empty($server) and in_array($server, $global_config['openid_servers'])) {
        $global_config['avatar_width'] = $global_users_config['avatar_width'];
        $global_config['avatar_height'] = $global_users_config['avatar_height'];

        if (file_exists(NV_ROOTDIR . '/modules/users/login/oauth-' . $server . '.php')) {
            include NV_ROOTDIR . '/modules/users/login/oauth-' . $server . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/users/login/cas-' . $server . '.php')) {
            include NV_ROOTDIR . '/modules/users/login/cas-' . $server . '.php';
        }
    }
}

nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);