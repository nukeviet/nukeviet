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
 * cron_auto_del_temp_download()
 *
 * @return bool
 */
function cron_auto_del_temp_download()
{
    $dir = NV_ROOTDIR . '/' . NV_TEMP_DIR;
    $result = true;

    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if (preg_match('/^(' . nv_preg_quote(NV_TEMPNAM_PREFIX) . ')[a-zA-Z0-9\_\.]+$/', $file)) {
                if ((filemtime($dir . '/' . $file) + 600) < NV_CURRENTTIME) {
                    if (is_file($dir . '/' . $file)) {
                        if (!@unlink($dir . '/' . $file)) {
                            $result = false;
                        }
                    } else {
                        $rt = nv_deletefile($dir . '/' . $file, true);
                        if ($rt[0] == 0) {
                            $result = false;
                        }
                    }
                }
            }
        }

        closedir($dh);
        clearstatcache();
    }

    return $result;
}
