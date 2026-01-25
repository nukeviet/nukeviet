<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    exit('Stop!!!');
}

/**
 * cron_apilogs_autodel()
 *
 * @return true
 */
function cron_apilogs_autodel()
{
    global $db, $db_config;

    $sql = 'SELECT role_id, log_period FROM ' . $db_config['prefix'] . '_api_role WHERE log_period > 0';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $interval = NV_CURRENTTIME - $row['log_period'];
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_logs WHERE role_id = ' . $row['role_id'] . ' AND log_time < ' . $interval);
    }

    return true;
}
