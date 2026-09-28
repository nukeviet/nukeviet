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

/**
 * Plugin chuyển hướng từ www sang non-www
 * Chỉ chuyển hướng khi domain không www cũng nằm trong danh sách domain hợp lệ
 */
if (substr(NV_SERVER_NAME, 0, 4) === 'www.') {
    $_non_www_host = substr(NV_SERVER_NAME, 4);
    if (in_array($_non_www_host, $global_config['my_domains'], true)) {
        nv_redirect_location(NV_SERVER_PROTOCOL . '://' . $_non_www_host . NV_SERVER_PORT . $_SERVER['REQUEST_URI']);
    }
    unset($_non_www_host);
}
