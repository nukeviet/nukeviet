<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    exit('Stop!!!');
}

/**
 * cron_del_ip_logs()
 *
 * @return bool
 */
function cron_del_ip_logs()
{
    global $db, $global_config;

    $result = true;
    $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs';

    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if (preg_match("/^([0-9\-]+)\.log$/", $file) and (filemtime($dir . '/' . $file) + 7200) < NV_CURRENTTIME) {
                //2 gio

                if (!@unlink($dir . '/' . $file)) {
                    $result = false;
                }
            }
        }

        closedir($dh);
        clearstatcache();
    }

    // Dọn bộ đếm đăng nhập sai theo tài khoản đã hết hiệu lực
    $loginTracker = new NukeViet\Core\LoginTracker(
        $db,
        NV_USERS_GLOBALTABLE . '_login_attempts',
        (int) $global_config['login_number_tracking'],
        (int) $global_config['login_time_tracking'],
        (int) $global_config['login_time_ban']
    );
    $loginTracker->cleanup();

    return $result;
}
