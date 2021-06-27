<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    exit('Stop!!!');
}

/**
 * cron_auto_sendmail_error_log()
 *
 * @return
 */
function cron_auto_sendmail_error_log()
{
    global $global_config, $nv_Lang;

    $result = true;

    $error_log_fileext = preg_match('/[a-z]+/i', NV_LOGS_EXT) ? NV_LOGS_EXT : 'log';
    $file = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/sendmail.' . $error_log_fileext;

    if (file_exists($file) and filesize($file) > 0) {
        $send_data = [[
            'to' => [$global_config['error_send_email']],
            'data' => []
        ]];
        $result = nv_sendmail_from_template(NukeViet\Template\Email\Tpl::E_AUTO_ERROR_REPORT, $send_data, $file);
        if ($result) {
            if (!@unlink($file)) {
                $result = false;
            }
        }
    }

    return $result;
}
