<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$submenu['file'] = $lang_module['file_backup'];
if (defined('NV_IS_GODADMIN')) {
    $submenu['sampledata'] = $lang_module['sampledata'];
    $submenu['setting'] = $lang_global['mod_settings'];
}
