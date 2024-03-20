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
 * cron_dump_autobackup()
 *
 * @return bool
 */
function cron_dump_autobackup()
{
    global $db, $db_config, $global_config;

    $result = true;

    $current_day = mktime(0, 0, 0, date('n', NV_CURRENTTIME), date('j', NV_CURRENTTIME), date('Y', NV_CURRENTTIME));
    $w_day = $current_day - ($global_config['dump_backup_day'] * 86400);

    $contents = [];
    $contents['savetype'] = ($global_config['dump_backup_ext'] == 'sql') ? 'sql' : 'gz';
    $file_ext = ($contents['savetype'] == 'sql') ? 'sql' : 'sql.gz';
    $log_dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup';

    $contents['filename'] = $log_dir . '/' . date('Y-m-d-H-i-s') . '_' . md5(nv_genpass(10) . NV_CHECK_SESSION) . '.' . $file_ext;

    if (!file_exists($contents['filename'])) {
        if ($dh = opendir($log_dir)) {
            while (($file = readdir($dh)) !== false) {
                if (preg_match('/^([0-9]{4})\-([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{1,2})\_[a-zA-Z0-9]+\.(' . nv_preg_quote($file_ext) . ')/', $file, $m)) {
                    $fctime = mktime(intval($m[4]), intval($m[5]), intval($m[6]), intval($m[2]), intval($m[3]), intval($m[1]));
                    if ($fctime !== false and $fctime < $w_day) {
                        unlink($log_dir . '/' . $file);
                    }
                }
            }

            closedir($dh);
            clearstatcache();
        }

        if ($global_config['dump_autobackup']) {
            $contents['tables'] = [];

            $res = $db->query("SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'");
            while ($item = $res->fetch(3)) {
                $contents['tables'][] = $item[0];
            }
            $res->closeCursor();

            $contents['type'] = 'all';

            include NV_ROOTDIR . '/includes/core/dump.php';

            if (!nv_dump_save($contents)) {
                $result = false;
            }
        }
    }

    return $result;
}
