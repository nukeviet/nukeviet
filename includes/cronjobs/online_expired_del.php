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
 * cron_online_expired_del()
 *
 * @return true
 */
function cron_online_expired_del()
{
    global $db;
    $db->query('DELETE FROM ' . NV_SESSIONS_GLOBALTABLE . ' WHERE onl_time < ' . (NV_CURRENTTIME - NV_ONLINE_UPD_TIME));

    return true;
}
