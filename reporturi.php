<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_SYSTEM', true);
define('NV_REMOTE_API', true);

// Xác định thư mục gốc của site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';

$log_dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/csp_logs';
$json_data = file_get_contents('php://input');
if ($json_data = json_decode($json_data)) {
    $json_data = json_encode($json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $log_file = $log_dir . '/' . md5($json_data) . '.' . NV_LOGS_EXT;
    if (!is_dir($log_dir)) {
        nv_mkdir(NV_ROOTDIR . '/' . NV_LOGS_DIR, 'csp_logs');
        file_put_contents($log_dir . '/index.html', '');
    }
    if (!file_exists($log_file)) {
        file_put_contents($log_file, $json_data, LOCK_EX);
    }
}
