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

// Chỉ xử lý qua ajax
if (!$nv_Request->isset_request('checkss', 'post')) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat');
}

$catid = $nv_Request->get_int('catid', 'post', 0);
$_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_cat' . $catid;
if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$mod = $nv_Request->get_string('mod', 'post', '');
$new_vid = $nv_Request->get_int('new_vid', 'post', 0);

$stmt = $db->prepare('SELECT catid, parentid, numsubcat, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid= :catid');
$stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (empty($row)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Category not found!'
    ]);
}

$catid = (int) $row['catid'];
$parentid = (int) $row['parentid'];
$numsubcat = (int) $row['numsubcat'];
$curr_status = (int) $row['status'];
$success = false;

if ($mod == 'weight' and $new_vid > 0 and (defined('NV_IS_ADMIN_MODULE') or ($parentid > 0 and isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1))) {
    $stmt = $db->prepare('SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid != :catid AND parentid= :parentid ORDER BY weight ASC');
    $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
    $stmt->bindValue(':parentid', $parentid, PDO::PARAM_INT);
    $stmt->execute();

    $weight = 0;
    $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight= :weight WHERE catid= :catid');
    while ($_row = $stmt->fetch()) {
        ++$weight;
        if ($weight == $new_vid) {
            ++$weight;
        }
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':catid', $_row['catid'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt->closeCursor();

    $stmt_update->bindValue(':weight', $new_vid, PDO::PARAM_INT);
    $stmt_update->bindValue(':catid', $catid, PDO::PARAM_INT);
    $stmt_update->execute();

    nv_fix_cat_order();
    $success = true;
} elseif (defined('NV_IS_ADMIN_MODULE') or (isset($array_cat_admin[$admin_id][$catid]) and $array_cat_admin[$admin_id][$catid]['add_content'] == 1)) {
    if ($mod == 'status' and in_array($new_vid, [0, 1, 2], true) and in_array((int) $curr_status, [0, 1, 2], true) and !(nv_get_mod_countrows() > NV_MIN_MEDIUM_SYSTEM_ROWS and ($new_vid == 0 or $curr_status == 0))) {
        // Đối với các chuyên mục bị khóa bởi chuyên mục cha thì không thay đổi gì
        // Đối với hệ thống lớn thì không thể đình chỉ
        if (($new_vid == 0 or $curr_status == 0) and $new_vid != $curr_status) {
            $sudcatids = GetCatidInParent($catid);
            if ($new_vid == 0) {
                // Đình chỉ
                $query_update_cat = 'status=status+' . ($global_code_defined['cat_locked_status'] + 1);
                $query_update_row = 'status=status+' . ($global_code_defined['row_locked_status'] + 1);
            } else {
                // Cho hoạt động lại
                $query_update_cat = 'status=status-' . ($global_code_defined['cat_locked_status'] + 1);
                $query_update_row = 'status=status-' . ($global_code_defined['row_locked_status'] + 1);

                // Tìm ra các chuyên mục vẫn còn bị khóa sau khi mở khóa chuyên mục này
                $array_cat_locked = [];
                foreach ($global_array_cat as $_catid_i => $_cat_value) {
                    if ($_catid_i != $catid) {
                        if (in_array((int) $_catid_i, array_map('intval', $sudcatids), true)) {
                            // Các chuyên mục con sẽ bị tác động thì trả về trạng thái status ban đầu
                            $_cat_value['status'] -= ($global_code_defined['cat_locked_status'] + 1);
                        }
                        if (!in_array((int) $_cat_value['status'], array_map('intval', $global_code_defined['cat_visible_status']), true)) {
                            $array_cat_locked[] = $_catid_i;
                        }
                    }
                }

                // Khi mở khóa tương tự cũng không ghi log thay đổi status của row
            }

            foreach ($sudcatids as $_catid) {
                // Khóa các chuyên mục con
                if ($_catid != $catid) {
                    try {
                        $stmt1 = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET ' . $query_update_cat . ' WHERE catid= :catid');
                        $stmt1->bindValue(':catid', $_catid, PDO::PARAM_INT);
                        $stmt1->execute();
                    } catch (Throwable $e) {
                        trigger_error($e);
                    }
                }

                /*
                    * Khi khóa chuyên mục thì chỉ cần xác định các bài viết này có listcatid thuộc vào $sudcatids thì sẽ lập tức bị khóa
                    * Không khóa các bài viết hiện tại đang bị khóa
                    */
                if ($new_vid == 0) {
                    // Khóa ở bảng rows
                    try {
                        $stmt2 = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET ' . $query_update_row . ' WHERE status<=' . $global_code_defined['row_locked_status'] . ' AND FIND_IN_SET(:catid, listcatid)');
                        $stmt2->bindValue(':catid', $_catid, PDO::PARAM_INT);
                        $stmt2->execute();
                    } catch (Throwable $e) {
                        trigger_error($e);
                    }
                    // Khóa ở các bảng cat
                    foreach ($global_array_cat as $_catid_i => $_cat_value) {
                        try {
                            $stmt3 = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $_catid_i . ' SET ' . $query_update_row . ' WHERE status<=' . $global_code_defined['row_locked_status'] . ' AND FIND_IN_SET(:catid, listcatid)');
                            $stmt3->bindValue(':catid', $_catid, PDO::PARAM_INT);
                            $stmt3->execute();
                        } catch (Throwable $e) {
                            trigger_error($e);
                        }
                    }
                    // Khi khóa, không ghi log thay đổi của row
                } else {
                    // Lấy các bài viết thuộc chuyên mục hoặc chuyên mục con của chuyên mục đang bị khóa/mở khóa
                    $stmt4 = $db->prepare('SELECT id, listcatid, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE FIND_IN_SET(:catid, listcatid)');
                    $stmt4->bindValue(':catid', $_catid, PDO::PARAM_INT);
                    $stmt4->execute();
                    while ($_row = $stmt4->fetch()) {
                        $_row['listcatid'] = explode(',', $_row['listcatid']);
                        // Xem thử bài viết này còn thuộc chuyên mục nào bị khóa không
                        if (array_intersect($array_cat_locked, $_row['listcatid']) == [] and $_row['status'] > $global_code_defined['row_locked_status']) {
                            // Mở khóa ở bảng rows
                            try {
                                $stmt5 = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET ' . $query_update_row . ' WHERE id= :id');
                                $stmt5->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                                $stmt5->execute();
                            } catch (Throwable $e) {
                                trigger_error($e);
                            }
                            // Mở khóa các bảng cat
                            foreach ($_row['listcatid'] as $_catid_i) {
                                try {
                                    $stmt6 = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $_catid_i . ' SET ' . $query_update_row . ' WHERE id= :id');
                                    $stmt6->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                                    $stmt6->execute();
                                } catch (Throwable $e) {
                                    trigger_error($e);
                                }
                            }
                        }
                    }
                    $stmt4->closeCursor();
                }
            }
        }

        $stmt_final = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET status= :status WHERE catid= :catid');
        $stmt_final->bindValue(':status', $new_vid, PDO::PARAM_INT);
        $stmt_final->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt_final->execute();

        $success = true;
    } elseif ($mod == 'numlinks' and $new_vid >= 0 and $new_vid <= 20) {
        $stmt_upd = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET numlinks= :numlinks WHERE catid= :catid');
        $stmt_upd->bindValue(':numlinks', $new_vid, PDO::PARAM_INT);
        $stmt_upd->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt_upd->execute();
        $success = true;
    } elseif ($mod == 'newday' and $new_vid >= 0 and $new_vid <= 10) {
        $stmt_upd = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET newday= :newday WHERE catid= :catid');
        $stmt_upd->bindValue(':newday', $new_vid, PDO::PARAM_INT);
        $stmt_upd->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt_upd->execute();
        $success = true;
    } elseif ($mod == 'viewcat' and $nv_Request->isset_request('new_vid', 'post')) {
        $viewcat = $nv_Request->get_title('new_vid', 'post');
        $array_viewcat = ($numsubcat > 0) ? $array_viewcat_full : $array_viewcat_nosub;
        if (!array_key_exists($viewcat, $array_viewcat)) {
            $viewcat = 'viewcat_page_new';
        }
        $stmt_upd = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET viewcat= :viewcat WHERE catid= :catid');
        $stmt_upd->bindValue(':viewcat', $viewcat, PDO::PARAM_STR);
        $stmt_upd->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt_upd->execute();
        $success = true;
    }
}

if (!$success) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Invalid request'
    ]);
}

$nv_Cache->delMod($module_name);

nv_jsonOutput([
    'status' => 'OK',
    'mess' => ''
]);
