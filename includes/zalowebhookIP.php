<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (preg_match('#^(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$#', NV_CLIENT_IP)) {
    $ip2long = ip2long(NV_CLIENT_IP);
} else {
    $zaloip = NV_CLIENT_IP;
    if (substr_count($zaloip, '::')) {
        $zaloip = str_replace('::', str_repeat(':0000', 8 - substr_count($zaloip, ':')) . ':', $zaloip);
    }
    $zaloip = explode(':', $zaloip);
    $r_ip = '';
    foreach ($zaloip as $v) {
        $r_ip .= str_pad(base_convert($v, 16, 2), 16, 0, STR_PAD_LEFT);
    }
    $ip2long = base_convert($r_ip, 2, 10);
}
if (!($ip2long == -1 or $ip2long === false)) {
    $zalo_logs_file = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/zalo_logs/' . $ip2long . '.' . NV_LOGS_EXT;
    file_put_contents($zalo_logs_file, NV_CLIENT_IP, LOCK_EX);
}
