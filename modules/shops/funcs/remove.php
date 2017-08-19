<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

if (! isset($_SESSION[$module_data . '_cart'])) {
    $_SESSION[$module_data . '_cart'] = array();
}

$id = $nv_Request->get_int('id', 'post,get', 0);
$group = $nv_Request->get_string('group', 'post,get', '');
if ($id > 0) {
    if (isset($_SESSION[$module_data . '_cart'][$id.'_'.$group])) {
        unset($_SESSION[$module_data . '_cart'][$id.'_'.$group]);
        echo $id.'_'.str_replace(',', '_', $group);
    }  else {
        echo "";
    }
} else {
    $listall = $nv_Request->get_string('listall', 'post,get');
    $array_id = explode(',', $listall);
    $array_id = array_map("intval", $array_id);
    foreach ($array_id as $id) {
        if ($id > 0) {
            if (isset($_SESSION[$module_data . '_cart'][$id.'_'.$group])) {
                unset($_SESSION[$module_data . '_cart'][$id.'_'.$group]);
            }
        }
    }
    echo "OK_0";
}