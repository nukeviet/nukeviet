<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_AJAX')) {
    exit('Wrong URL');
}

$id = $nv_Request->get_int('id', 'post', 0);

if (empty($id)) {
    nv_htmlOutput('NO|act_' . $id);
}

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id . ' AND act IN (0,1,2,3,4)';
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_htmlOutput('NO|act_' . $id);
}

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $row['pid'];
$plan = $db->query($sql)->fetch();
if (empty($plan)) {
    nv_htmlOutput('NO|act_' . $id);
}

$act = (int) ($row['act']);
$publ_time = $row['publ_time'];
$exp_time = $row['exp_time'];

if ($act == 0) {
    $act = 1;
} elseif ($act == 1) {
    $act = 3;
} elseif ($act == 3 or $act == 2) {
    $act = 1;
} elseif ($act == 4) {
    $act = 1;
    if ($exp_time > 0) {
        $exp_time = NV_CURRENTTIME + ($exp_time - $publ_time);
        $publ_time = NV_CURRENTTIME;
    }
}

// Xác định lại thời gian đăng quảng cáo khi kích hoạt lại
if ($act == 1) {
    // Nếu hẹn giờ đăng thì cho đăng, Nếu đã bị hết hạn thì đăng lại
    if ($publ_time > NV_CURRENTTIME or ($exp_time > 0 and $exp_time <= NV_CURRENTTIME)) {
        if ($exp_time > 0) {
            $exp_time = NV_CURRENTTIME + ($exp_time - $publ_time);
        }
        $publ_time = NV_CURRENTTIME;
    }
    if ($exp_time > 0 and $exp_time < $publ_time) {
        $exp_time = $publ_time;
    }
}

// Xác định lại weight của banner khi duyệt, đăng lại banner hết hạn
if ($row['act'] == 2 or $row['act'] == 4) {
    $weight = $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE pid=' . $row['pid'] . ' AND act IN(0,1,3)')->fetchColumn();
    ++$weight;
} else {
    $weight = $row['weight'];
}

$sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET act=' . $act . ', publ_time=' . $publ_time . ', exp_time=' . $exp_time . ', weight=' . $weight . ' WHERE id=' . $id;
$return = ($db->exec($sql)) ? 'OK' : 'NO';

$nv_Cache->delMod($module_name);
nv_CreateXML_bannerPlan();
nv_htmlOutput($return . '|act_' . $id);
