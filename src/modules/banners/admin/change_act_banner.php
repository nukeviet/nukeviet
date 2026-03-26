<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_AJAX')) {
    exit('Wrong URL');
}

$_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_main';
if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$id = $nv_Request->get_int('id', 'post', 0);

if (empty($id)) {
    nv_jsonOutput(['status' => 'error', 'mess' => '']);
}

$stmt = $db->prepare('SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . "_rows WHERE id = :id AND act IN (0,1,2,3,4)");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();
if (empty($row)) {
    nv_jsonOutput(['status' => 'error', 'mess' => '']);
}

$stmt = $db->prepare('SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id = :pid');
$stmt->bindValue(':pid', $row['pid'], PDO::PARAM_INT);
$stmt->execute();
$plan = $stmt->fetch();
$stmt->closeCursor();
if (empty($plan)) {
    nv_jsonOutput(['status' => 'error', 'mess' => '']);
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
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . "_rows WHERE pid = :pid AND act IN (0,1,3)");
    $stmt->bindValue(':pid', $row['pid'], PDO::PARAM_INT);
    $stmt->execute();
    $weight = $stmt->fetchColumn();
    ++$weight;
} else {
    $weight = $row['weight'];
}

$stmt = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET act = :act, publ_time = :publ_time, exp_time = :exp_time, weight = :weight WHERE id = :id');
$stmt->bindValue(':act', $act, PDO::PARAM_INT);
$stmt->bindValue(':publ_time', $publ_time, PDO::PARAM_INT);
$stmt->bindValue(':exp_time', $exp_time, PDO::PARAM_INT);
$stmt->bindValue(':weight', $weight, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$ok = (bool) $stmt->execute();

$nv_Cache->delMod($module_name);
nv_CreateXML_bannerPlan();

if ($ok) {
    nv_jsonOutput(['status' => 'OK', 'mess' => '', 'refresh' => true]);
} else {
    nv_jsonOutput(['status' => 'error', 'mess' => '']);
}
