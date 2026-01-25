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

if (!function_exists('_nv_cron_auto_del_cache')) {
    function _nv_cron_auto_del_cache($directory)
    {
        if (!is_dir($directory)) {
            return false;
        }
        $items = scandir($directory);
        if ($items === false) {
            return false;
        }
        foreach ($items as $item) {
            if ($item === '.' or $item === '..') {
                continue;
            }

            $path = $directory . '/' . $item;
            if (is_dir($path)) {
                if (!_nv_cron_auto_del_cache($path)) {
                    return false;
                }
            } else {
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if ($extension === 'cache' or $extension === 'php') {
                    $lastModified = filemtime($path);
                    if ($lastModified === false) {
                        continue;
                    }
                    $timeDiff = NV_CURRENTTIME - $lastModified;
                    if ($timeDiff > 604800) {
                        if (!unlink($path)) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }
}

/**
 * Xóa các tệp cache cũ
 * @return bool
 */
function cron_auto_del_cache()
{
    $dir = NV_ROOTDIR . '/' . NV_CACHEDIR;
    return _nv_cron_auto_del_cache($dir);
}
