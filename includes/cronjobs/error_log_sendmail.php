<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    exit('Stop!!!');
}

/**
 * cron_auto_sendmail_error_log()
 *
 * @return bool
 */
function cron_auto_sendmail_error_log()
{
    global $global_config, $nv_Lang;

    $result = true;

    $error_log_fileext = preg_match('/[a-z]+/i', NV_LOGS_EXT) ? NV_LOGS_EXT : 'log';
    $file = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/sendmail.' . $error_log_fileext;

    if (file_exists($file) and filesize($file) > 0) {
        $result = nv_sendmail([$global_config['site_name'], $global_config['site_email']], $global_config['error_send_email'], $nv_Lang->getGlobal('error_sendmail_subject', $global_config['site_name']), $nv_Lang->getGlobal('error_sendmail_content'), $file);

        if ($result) {
            if (!@unlink($file)) {
                $result = false;
            }
        }
    }

    return $result;
}
