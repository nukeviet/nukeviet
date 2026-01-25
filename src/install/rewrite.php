<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (isset($_GET['rewritesupport'])) {
    if ($_GET['rewritesupport'] == 'apache') {
        exit('rewrite_mode_apache');
    }
    if ($_GET['rewritesupport'] == 'iis') {
        exit('rewrite_mode_iis');
    }
    if ($_GET['rewritesupport'] == 'nginx') {
        exit('nginx');
    }
    exit(0);
}
