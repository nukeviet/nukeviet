<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (isset($_GET['response_headers_detect'])) {
    if ((isset($_SERVER['HTTPS']) and (strtolower($_SERVER['HTTPS']) == 'on' or $_SERVER['HTTPS'] == '1')) or $_SERVER['SERVER_PORT'] == 443) {
        header('x-is-https: 1');
    } else {
        header('x-is-http: 1');
    }
    exit(0);
}

define('NV_SYSTEM', true);
define('NV_SYS_LOAD', true);

// Xác định thư mục gốc của site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';

exit('WRONG_URL');
