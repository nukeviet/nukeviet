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
 * cron_apilogs_autodel()
 *
 * @return true
 */
function cron_apilogs_autodel()
{
    global $db, $db_config;

    $sql = 'SELECT tb1.role_id, tb1.log_period FROM ' . $db_config['prefix'] . '_api_role tb1 INNER JOIN ' . $db_config['prefix'] . '_api_role_logs tb2 ON (tb1.role_id=tb2.role_id)';
    $result = $db->query($sql);

    $roles = [];
    while ($row = $result->fetch()) {
        if (!empty($row['log_period'])) {
            $roles[$row['role_id']] = $row['log_period'];
        }
    }

    if (!empty($roles)) {
        foreach ($roles as $role_id => $interval) {
            $interval = NV_CURRENTTIME - $interval;
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_logs WHERE role_id = ' . $role_id . ' AND log_time < ' . $interval);
        }
    }

    return true;
}
