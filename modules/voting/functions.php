<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

if (!in_array($op, ['detail', 'result'], true)) {
    define('NV_IS_MOD_VOTING', true);
}

if (!empty($array_op)) {
    unset($matches);
    if (preg_match("/^result\-([0-9]+)$/", $array_op[0], $matches)) {
        $id = (int) $matches[1];
        $op = 'result';
    }
}
