<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    exit('Stop!!!');
}

/**
 * cron_auto_check_version()
 *
 * @return true
 */
function cron_auto_check_version()
{
    global $nv_Request, $global_config, $client_info;

    $admin_cookie = $nv_Request->get_bool('admin', 'session', false);

    if (!empty($admin_cookie) and $global_config['autocheckupdate']) {
        require NV_ROOTDIR . '/includes/core/admin_access.php';
        require NV_ROOTDIR . '/includes/core/is_admin.php';

        if (defined('NV_IS_GODADMIN')) {
            define('NV_ADMIN', true);
            include_once NV_ROOTDIR . '/includes/core/admin_functions.php';
            nv_geVersion($global_config['autoupdatetime'] * 3600);
        }
    }

    return true;
}
