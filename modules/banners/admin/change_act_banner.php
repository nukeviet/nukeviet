<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/15/2010 15:32
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (!defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$id = $nv_Request->get_int('id', 'post', 0);

if (empty($id)) {
    nv_htmlOutput('NO|act_' . $id);
}

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id . ' AND act IN (0,1,3,4)';
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_htmlOutput('NO|act_' . $id);
}

$act = intval($row['act']);
$publ_time = $row['publ_time'];
$exp_time = $row['exp_time'];

if ($act == 0) {
    $act = 1;
} elseif ($act == 1) {
    $act = 3;
} elseif ($act == 3) {
    $act = 1;
} elseif ($act == 4) {
    $act = 1;
    if ($exp_time > 0) {
        $exp_time = NV_CURRENTTIME + ($exp_time - $publ_time);
        $publ_time = NV_CURRENTTIME;
    }
}

$sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET act=' . $act . ', publ_time=' . $publ_time . ', exp_time=' . $exp_time . ' WHERE id=' . $id;
$return = ($db->exec($sql)) ? 'OK' : 'NO';

$nv_Cache->delMod($module_name);
nv_CreateXML_bannerPlan();
nv_htmlOutput($return . '|act_' . $id);
