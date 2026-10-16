<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
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

$listid = $nv_Request->get_typed_array('listid', 'post', 'int', []);
$is_bulk = !empty($listid);
if (!$is_bulk) {
    $listid = [$nv_Request->get_int('id', 'post', 0)];
}
$listid = array_unique(array_filter(array_map('intval', $listid)));

$new_act = $nv_Request->get_int('act', 'post', 0);
if ($is_bulk and $new_act != 1 and $new_act != 3) {
    nv_jsonOutput(['status' => 'error', 'mess' => '']);
}

if (empty($listid)) {
    nv_jsonOutput(['status' => 'error', 'mess' => '']);
}

$result = $db->query('SELECT r.* FROM ' . NV_BANNERS_GLOBALTABLE . '_rows r INNER JOIN ' . NV_BANNERS_GLOBALTABLE . '_plans p ON r.pid = p.id WHERE r.id IN (' . implode(',', $listid) . ') AND r.act IN (0,1,2,3,4) ORDER BY r.id ASC');
$array_rows = $result->fetchAll();
$result->closeCursor();
if (empty($array_rows)) {
    nv_jsonOutput(['status' => 'error', 'mess' => '']);
}

$stmt_weight = $db->prepare('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE pid = :pid AND act IN (0,1,3)');
$stmt_update = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET act = :act, publ_time = :publ_time, exp_time = :exp_time, weight = :weight WHERE id = :id');
$updated = 0;

foreach ($array_rows as $row) {
    $old_act = (int) $row['act'];
    $publ_time = $row['publ_time'];
    $exp_time = $row['exp_time'];

    if ($is_bulk) {
        // Kích hoạt bỏ qua banner đang hoạt động, đình chỉ chỉ áp dụng cho banner đang hoạt động
        if (($new_act == 1 and $old_act == 1) or ($new_act == 3 and $old_act != 1)) {
            continue;
        }
        $act = $new_act;
    } else {
        $act = ($old_act == 1) ? 3 : 1;
    }

    if ($old_act == 4 and $act == 1 and $exp_time > 0) {
        $exp_time = NV_CURRENTTIME + ($exp_time - $publ_time);
        $publ_time = NV_CURRENTTIME;
    }

    // Xác định lại thời gian đăng quảng cáo khi kích hoạt lại
    if ($act == 1) {
        // Nếu hẹn giờ đăng thì cho đăng, Nếu đã bị hết hạn thì đăng lại với thời lượng giữ nguyên
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
    if ($old_act == 2 or $old_act == 4) {
        $stmt_weight->bindValue(':pid', $row['pid'], PDO::PARAM_INT);
        $stmt_weight->execute();
        $weight = (int) $stmt_weight->fetchColumn() + 1;
    } else {
        $weight = $row['weight'];
    }

    $stmt_update->bindValue(':act', $act, PDO::PARAM_INT);
    $stmt_update->bindValue(':publ_time', $publ_time, PDO::PARAM_INT);
    $stmt_update->bindValue(':exp_time', $exp_time, PDO::PARAM_INT);
    $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
    $stmt_update->bindValue(':id', $row['id'], PDO::PARAM_INT);
    if ($stmt_update->execute()) {
        ++$updated;
    }
}

if (empty($updated)) {
    nv_jsonOutput(['status' => 'error', 'mess' => $is_bulk ? $nv_Lang->getModule('banner_bulk_none') : '']);
}

$nv_Cache->delMod($module_name);
nv_CreateXML_bannerPlan();

nv_jsonOutput(['status' => 'OK', 'mess' => '', 'refresh' => true]);
