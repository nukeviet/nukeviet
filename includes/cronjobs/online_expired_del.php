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
 * cron_online_expired_del()
 *
 * @return
 */
function cron_online_expired_del()
{
    global $db;
    $db->query('DELETE FROM ' . NV_SESSIONS_GLOBALTABLE . ' WHERE onl_time < ' . (NV_CURRENTTIME - NV_ONLINE_UPD_TIME));

    return true;
}