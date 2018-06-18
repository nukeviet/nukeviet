<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    die('Stop!!!');
}

/**
 * cron_notification_autodel()
 *
 * @return
 */
function cron_notification_autodel()
{
    global $db, $global_config;

    if ($global_config['notification_autodel']) {
        $db->query('DELETE FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE add_time > ' . (NV_CURRENTTIME - (86400 * $global_config['notification_autodel'])));
    }

    return true;
}