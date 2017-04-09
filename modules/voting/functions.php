<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (! defined('NV_SYSTEM')) {
    die('Stop!!!');
}

if (! in_array($op, array( 'detail', 'result' ))) {
    define('NV_IS_MOD_VOTING', true);
}

if (! empty($array_op)) {
    unset($matches);
    if (preg_match("/^result\-([0-9]+)$/", $array_op[0], $matches)) {
        $id = ( int )$matches[1];
        $op = "result";
    }
}
