<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
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

if ($global_config['allowuserlogin'] and defined('NV_OPENID_ALLOWED')) {
    $server = $nv_Request->get_string('server', 'get', '');

    if (! empty($server) and in_array($server, $global_config['openid_servers'])) {
        // Add to Global config
        $sql = "SELECT content FROM " . NV_MOD_TABLE . "_config WHERE config='avatar_width'";
        $result = $db->query($sql);
        $global_config['avatar_width'] = $result->fetchColumn();
        $result->closeCursor();

        $sql = "SELECT content FROM " . NV_MOD_TABLE . "_config WHERE config='avatar_height'";
        $result = $db->query($sql);
        $global_config['avatar_height'] = $result->fetchColumn();
        $result->closeCursor();

        if (file_exists(NV_ROOTDIR . '/modules/users/login/oauth-' . $server . '.php')) {
            include NV_ROOTDIR . '/modules/users/login/oauth-' . $server . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/users/login/cas-' . $server . '.php')) {
            include NV_ROOTDIR . '/modules/users/login/cas-' . $server . '.php';
        }
    }
}

Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
die();
