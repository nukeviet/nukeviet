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
 * cron_ref_expired_del()
 *
 * @return
 */
function cron_ref_expired_del()
{
    $result = true;
    $log_path = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ref_logs';

    if ($dh = opendir($log_path)) {
        $log_start = mktime(0, 0, 0, date('n', NV_CURRENTTIME), 1, date('Y', NV_CURRENTTIME));

        while (($logfile = readdir($dh)) !== false) {
            if (preg_match('/^([0-9]{10,12})\.' . preg_quote(NV_LOGS_EXT) . '$/', $logfile, $matches)) {
                $d = ( int )$matches[1];
                if ($d < $log_start) {
                    if (! @unlink($log_path . '/' . $logfile)) {
                        $result = false;
                    }
                }
            }
        }

        closedir($dh);
        clearstatcache();
    }

    return $result;
}