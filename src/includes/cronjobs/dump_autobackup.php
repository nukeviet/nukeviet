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
 * cron_dump_autobackup()
 *
 * @return
 */
function cron_dump_autobackup()
{
    global $db, $db_config, $global_config;

    $result = true;

    $current_day = mktime(0, 0, 0, date('n', NV_CURRENTTIME), date('j', NV_CURRENTTIME), date('Y', NV_CURRENTTIME));
    $w_day = $current_day - ($global_config['dump_backup_day'] * 86400);

    $contents = array();
    $contents['savetype'] = ($global_config['dump_backup_ext'] == 'sql') ? 'sql' : 'gz';
    $file_ext = ($contents['savetype'] == 'sql') ? 'sql' : 'sql.gz';
    $log_dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup';

    $contents['filename'] = $log_dir . '/' . md5(nv_genpass(10) . NV_CHECK_SESSION) . '_' . $current_day . '.' . $file_ext;

    if (! file_exists($contents['filename'])) {
        if ($dh = opendir($log_dir)) {
            while (($file = readdir($dh)) !== false) {
                if (preg_match('/^([a-zA-Z0-9]+)\_([0-9]+)\.(' . nv_preg_quote($file_ext) . ')/', $file, $m)) {
                    if (intval($m[2]) > 0 and intval($m[2]) < $w_day) {
                        @unlink($log_dir . '/' . $file);
                    }
                }
            }

            closedir($dh);
            clearstatcache();
        }

        if ($global_config['dump_autobackup']) {
            $contents['tables'] = array();

            $res = $db->query("SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'");
            while ($item = $res->fetch(3)) {
                $contents['tables'][] = $item[0];
            }
            $res->closeCursor();

            $contents['type'] = 'all';

            include NV_ROOTDIR . '/includes/core/dump.php' ;

            if (! nv_dump_save($contents)) {
                $result = false;
            }
        }
    }

    return $result;
}