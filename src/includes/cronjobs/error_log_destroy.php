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
 * cron_auto_del_error_log()
 *
 * @return bool
 */
function cron_auto_del_error_log()
{
    $result = true;

    list($currday, $currmonth, $curryear) = explode('.', gmdate('d.m.Y', NV_CURRENTTIME));
    $day_mktime = mktime(0, 0, 0, $currmonth, $currday, $curryear);
    $month = gmdate('Y-m', NV_CURRENTTIME);

    $error_log_fileext = preg_match('/[a-z]+/i', NV_LOGS_EXT) ? NV_LOGS_EXT : 'log';
    $error_log_filename = preg_match("/[a-z0-9\_]+/i", NV_ERRORLOGS_FILENAME) ? NV_ERRORLOGS_FILENAME : 'error_log';
    $notice_log_filename = preg_match("/[a-z0-9\_]+/i", NV_NOTICELOGS_FILENAME) ? NV_NOTICELOGS_FILENAME : 'notice_log';

    // Xóa log cũ
    $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old';
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            unset($m);
            if (preg_match("/^(\d{4})\-(\d{2})\-(\d{2})\_(" . $error_log_filename . '|' . $notice_log_filename . ")([^\.]*)\.(" . $error_log_fileext . ')$/', $file, $m)) {
                $old_day_mktime = mktime(0, 0, 0, $m[2], $m[3], $m[1]);
                if ($old_day_mktime + 864000 < $day_mktime) {
                    if (!@unlink($dir . '/' . $file)) {
                        $result = false;
                    }
                }
            }
        }
        closedir($dh);
    }

    // Chuyển log hiện hành thành log cũ
    $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            unset($m);
            if (preg_match("/^(\d{4})\-(\d{2})\-(\d{2})\_(" . $error_log_filename . '|' . $notice_log_filename . ")([^\.]*)\.(" . $error_log_fileext . ')$/', $file, $m)) {
                $old_day_mktime = mktime(0, 0, 0, $m[2], $m[3], $m[1]);
                if ($old_day_mktime != $day_mktime) {
                    @rename($dir . '/' . $file, $dir . '/old/' . $file);
                }
            }
        }
        closedir($dh);
    }

    // Xóa log tạm
    $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp';
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            unset($m);
            if (preg_match("/^(\d{4})\-(\d{2})\-(\d{2})\_([a-zA-Z0-9]{32})\.(" . $error_log_fileext . ')$/', $file, $m)) {
                $old_day_mktime = mktime(0, 0, 0, $m[2], $m[3], $m[1]);
                if ($old_day_mktime < $day_mktime) {
                    if (!@unlink($dir . '/' . $file)) {
                        $result = false;
                    }
                }
            }
        }
        closedir($dh);
    }

    // Xóa log 256
    $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            unset($m);
            if (preg_match("/^(\d{4})\-(\d{2})\_([a-z0-9]{32})\.(" . $error_log_fileext . ')$/', $file, $m)) {
                if ($m[2] != $month) {
                    if (!@unlink($dir . '/' . $file)) {
                        $result = false;
                    }
                }
            }
        }
        closedir($dh);
    }

    clearstatcache();

    return $result;
}
