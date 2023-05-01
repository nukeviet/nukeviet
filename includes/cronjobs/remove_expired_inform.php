<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    exit('Stop!!!');
}

/**
 * cron_remove_expired_inform()
 *
 * @return true
 */
function cron_remove_expired_inform()
{
    global $db, $global_config;

    if ($global_config['inform_active']) {
        $db->query('DELETE FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' WHERE pid IN (SELECT id FROM ' . NV_INFORM_GLOBALTABLE . ' WHERE (exp_time != 0 AND exp_time < ' . (NV_CURRENTTIME - $global_config['inform_exp_del']) . '))');
        $db->query('DELETE FROM ' . NV_INFORM_GLOBALTABLE . ' WHERE (exp_time != 0 AND exp_time < ' . (NV_CURRENTTIME - $global_config['inform_exp_del']) . ')');
        $db->query('OPTIMIZE TABLE ' . NV_INFORM_STATUS_GLOBALTABLE);
        $db->query('OPTIMIZE TABLE ' . NV_INFORM_GLOBALTABLE);
    }

    return true;
}
