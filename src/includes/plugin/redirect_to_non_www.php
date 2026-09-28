<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * Plugin chuyển hướng từ www sang non-www
 * Chỉ chuyển hướng khi domain không www cũng nằm trong danh sách domain hợp lệ
 */
nv_add_hook($module_name, 'check_server', $priority, function (): void {
    global $nv_Server, $global_config;

    $original_host = $nv_Server->getOriginalHost();
    if (str_starts_with($original_host, 'www.')) {
        $non_www_host = substr($original_host, 4);
        if (in_array($non_www_host, $global_config['my_domains'], true)) {
            nv_redirect_location($nv_Server->getOriginalProtocol() . '://' . $non_www_host . $nv_Server->getOriginalPort() . $_SERVER['REQUEST_URI']);
        }
    }
});
