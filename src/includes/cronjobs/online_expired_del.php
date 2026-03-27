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
 * cron_online_expired_del()
 *
 * @return true
 */
function cron_online_expired_del()
{
    global $db;
    $onl_time = NV_CURRENTTIME - NV_ONLINE_UPD_TIME;
    $stmt = $db->prepare('DELETE FROM ' . NV_SESSIONS_GLOBALTABLE . ' WHERE onl_time < :onl_time');
    $stmt->bindValue(':onl_time', $onl_time, PDO::PARAM_INT);
    $stmt->execute();

    return true;
}
