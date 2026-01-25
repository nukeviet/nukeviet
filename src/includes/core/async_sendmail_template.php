<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_SYS_LOAD')) {
    exit('Stop!!!');
}

$file = $nv_Request->get_title('__sendmail_template', 'post', '');
if (preg_match('/^[a-zA-Z0-9]{8}$/', $file)) {
    $md5file = md5($global_config['sitekey'] . $file);
    if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $md5file)) {
        $cts = file_get_contents(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $md5file);
        $cts = json_decode($cts, true);
        @unlink(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $md5file);
        nv_sendmail_from_template($cts['emailid'], $cts['data'], $cts['lang'], $cts['attachments']);
    }
}
