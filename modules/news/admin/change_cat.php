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

$catid = $nv_Request->get_int('catid', 'post', 0);
$mod = $nv_Request->get_string('mod', 'post', '');
$new_vid = $nv_Request->get_int('new_vid', 'post', 0);
$content = 'NO_' . $catid;

list($catid, $parentid, $numsubcat, $curr_status) = $db->query('SELECT catid, parentid, numsubcat, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid)->fetch(3);
if ($catid > 0) {
    if ($mod == 'weight' and $new_vid > 0 and (defined('NV_IS_ADMIN_MODULE') or ($parentid > 0 and isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1))) {
        $sql = 'SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid!=' . $catid . ' AND parentid=' . $parentid . ' ORDER BY weight ASC';
        $result = $db->query($sql);

        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight == $new_vid) {
                ++$weight;
            }
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE catid=' . $row['catid'];
            $db->query($sql);
        }

        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $new_vid . ' WHERE catid=' . $catid;
        $db->query($sql);

        nv_fix_cat_order();
        $content = 'OK_' . $parentid;
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
                            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET ' . $query_update_cat . ' WHERE catid=' . $_catid);
                        } catch (Exception $e) {
                            trigger_error($e->getMessage());
                        }
                    }

                    /*
                     * Khi khóa chuyên mục thì chỉ cần xác định các bài viết này có listcatid thuộc vào $sudcatids thì sẽ lập tức bị khóa
                     * Không khóa các bài viết hiện tại đang bị khóa
                     */
                    if ($new_vid == 0) {
                        // Khóa ở bảng rows
                        try {
                            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET ' . $query_update_row . ' WHERE status<=' . $global_code_defined['row_locked_status'] . ' AND FIND_IN_SET(' . $_catid . ',listcatid)');
                        } catch (Exception $e) {
                            trigger_error($e->getMessage());
                        }
                        // Khóa ở các bảng cat
                        foreach ($global_array_cat as $_catid_i => $_cat_value) {
                            try {
                                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $_catid_i . ' SET ' . $query_update_row . ' WHERE status<=' . $global_code_defined['row_locked_status'] . ' AND FIND_IN_SET(' . $_catid . ',listcatid)');
                            } catch (Exception $e) {
                                trigger_error($e->getMessage());
                            }
                        }
                        // Khi khóa, không ghi log thay đổi của row
                    } else {
                        // Lấy các bài viết thuộc chuyên mục hoặc chuyên mục con của chuyên mục đang bị khóa/mở khóa
                        $sql = 'SELECT id, catid, listcatid, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE FIND_IN_SET(' . $_catid . ', listcatid)';
                        $result = $db->query($sql);
                        while ($row = $result->fetch()) {
                            $row['listcatid'] = explode(',', $row['listcatid']);
                            // Xem thử bài viết này còn thuộc chuyên mục nào bị khóa không
                            if (array_intersect($array_cat_locked, $row['listcatid']) == [] and $row['status'] > $global_code_defined['row_locked_status']) {
                                // Mở khóa ở bảng rows
                                try {
                                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET ' . $query_update_row . ' WHERE id=' . $row['id']);
                                } catch (Exception $e) {
                                    trigger_error($e->getMessage());
                                }
                                // Mở khóa các bảng cat
                                foreach ($row['listcatid'] as $_catid_i) {
                                    try {
                                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $_catid_i . ' SET ' . $query_update_row . ' WHERE id=' . $row['id']);
                                    } catch (Exception $e) {
                                        trigger_error($e->getMessage());
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET status=' . $new_vid . ' WHERE catid=' . $catid;
            $db->query($sql);

            $content = 'OK_' . $parentid;
        } elseif ($mod == 'numlinks' and $new_vid >= 0 and $new_vid <= 20) {
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET numlinks=' . $new_vid . ' WHERE catid=' . $catid;
            $db->query($sql);
            $content = 'OK_' . $parentid;
        } elseif ($mod == 'newday' and $new_vid >= 0 and $new_vid <= 10) {
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET newday=' . $new_vid . ' WHERE catid=' . $catid;
            $db->query($sql);
            $content = 'OK_' . $parentid;
        } elseif ($mod == 'viewcat' and $nv_Request->isset_request('new_vid', 'post')) {
            $viewcat = $nv_Request->get_title('new_vid', 'post');
            $array_viewcat = ($numsubcat > 0) ? $array_viewcat_full : $array_viewcat_nosub;
            if (!array_key_exists($viewcat, $array_viewcat)) {
                $viewcat = 'viewcat_page_new';
            }
            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET viewcat= :viewcat WHERE catid=' . $catid);
            $stmt->bindParam(':viewcat', $viewcat, PDO::PARAM_STR);
            $stmt->execute();
            $content = 'OK_' . $parentid;
        }
    }
    $nv_Cache->delMod($module_name);
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';
