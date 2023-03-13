<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!(defined('NV_MAINFILE') and (defined('NV_SYS_LOAD') or defined('NV_MOD_LOAD')))) {
    exit('Stop!!!');
}

if (empty($global_config['load_files_seccode'])) {
    exit('NO_PERMISSION');
}

$timestamp = $nv_Request->get_int('timestamp', 'post,get', 0);
$userhash = $nv_Request->get_title('userhash', 'post,get', '');

if (empty($timestamp) or empty($userhash)) {
    exit('INCORRECT_DATA');
}

if ($timestamp + 60 < NV_CURRENTTIME or $timestamp - 60 > NV_CURRENTTIME) {
    exit('ACCESS_EXPIRED');
}

if (!hash_equals(md5($nv_Request->ref_origin . $crypt->decrypt($global_config['load_files_seccode']) . $timestamp), $userhash)) {
    exit('INCORRECT_USERHASH');
}

define('IS_CROSS_SITE_LOAD', true);
