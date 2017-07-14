<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9/9/2010, 6:38
 */

if (! defined('NV_IS_FILE_SEOTOOLS')) {
    die('Stop!!!');
}

if (defined('NV_IS_GODADMIN')) {
    $redirect_op = 'pagetitle';
} else {
    $redirect_op = 'googleplus';
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $redirect_op);