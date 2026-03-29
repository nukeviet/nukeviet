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

$row = $db->query('SELECT catid, parentid, title, ad_block_cat FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid)->fetch(3);
if (empty($row)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Category not found!'
    ]);
}

[$catid, $parentid, $title, $ad_block_cat] = $row;

// Check quyền xóa chuyên mục
if (!(defined('NV_IS_ADMIN_MODULE') or ($parentid > 0 and isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1))) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('delcat_msg_cat_permissions')
    ]);
}

$ad_block_cat = array_filter(array_unique(array_map('intval', explode(',', $ad_block_cat))));
$submitconfirm = (int) $nv_Request->get_bool('submitconfirm', 'post', false);

// Kiểm tra chuyên mục con
$check_parentid = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid = ' . $catid)->fetchColumn();
if ((int) $check_parentid > 0) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('delcat_msg_cat', $check_parentid)
    ]);
}

// Kiểm tra số bài viết trong chuyên mục
$check_rows = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid)->fetchColumn();
if ((int) $check_rows > 0) {
    if (!$submitconfirm) {
        nv_jsonOutput([
            'status' => 'confirm_rows',
            'mess' => $nv_Lang->getModule('delcat_msg_rows', $check_rows)
        ]);
    }

    $delcatandrows = $nv_Request->get_string('delcatandrows', 'post', '');
    $movecat = $nv_Request->get_string('movecat', 'post', '');
    $catidnews = $nv_Request->get_int('catidnews', 'post', 0);

    if (!empty($delcatandrows)) {
        // Xóa chuyên mục và tất cả bài viết trong chuyên mục đó
        $weight_min = 0;
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('delcatandrows'), $title, $admin_info['userid']);

        $sql = $db->query('SELECT id, catid, listcatid, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' ORDER BY weight DESC');
        while ($row = $sql->fetch()) {
            if ($row['catid'] == $row['listcatid']) {
                nv_del_content_module($row['id']);
                $weight_min = $row['weight'];
            } else {
                $arr_catid_old = explode(',', $row['listcatid']);
                $arr_catid_i = [
                    $catid
                ];
                $arr_catid_news = array_diff($arr_catid_old, $arr_catid_i);
                if ($catid == $row['catid']) {
                    $row['catid'] = $arr_catid_news[0];
                }
                foreach ($arr_catid_news as $catid_i) {
                    if (isset($global_array_cat[$catid_i])) {
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET catid=' . $row['catid'] . ", listcatid = '" . implode(',', $arr_catid_news) . "' WHERE id =" . $row['id']);
                    }
                }
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET catid=' . $row['catid'] . ", listcatid = '" . implode(',', $arr_catid_news) . "' WHERE id =" . $row['id']);
            }
        }
        $db->query('DROP TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE catid=' . $catid);

        foreach ($ad_block_cat as $ad_block_id) {
            nv_unregister_block(nv_get_blcat_tag($catid, $ad_block_id));
        }

        nv_fix_weight_content($weight_min);
        nv_fix_cat_order();
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => '',
            'refresh' => true
        ]);
    } elseif (!empty($movecat) && $catidnews > 0 && $catidnews != $catid) {
        /**
         * Khi xóa chuyên mục và di chuyển bài viết sang chuyên mục khác thì
         * vẫn có trường hợp bài viết này còn trong các chuyên mục khác mà chuyên mục đó
         * vẫn đang bị đình chỉ, do đó phải kiểm tra sau khi di chuyển có còn ở trong
         * chuyên mục bị đình chỉ không nếu không mới trả lại status ban đầu
         */
        [$catidnews, $newstitle] = $db->query('SELECT catid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE status IN(' . implode(',', $global_code_defined['cat_visible_status']) . ') AND catid =' . $catidnews)->fetch(3);
        if (empty($catidnews)) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'catidnews',
                'mess' => $nv_Lang->getModule('search_catid_error')
            ]);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('move'), $title . ' --> ' . $newstitle, $admin_info['userid']);

        $array_cat_locked = [];
        foreach ($global_array_cat as $catid_i => $array_value) {
            if ($catid_i != $catid and !in_array((int) $array_value['status'], array_map('intval', $global_code_defined['cat_visible_status']), true)) {
                $array_cat_locked[] = $catid_i;
            }
        }

        $sql = $db->query('SELECT id, catid, listcatid, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid);

        while ($row = $sql->fetch()) {
            $arr_catid_old = explode(',', $row['listcatid']);
            $arr_catid_i = [
                $catid
            ];
            $arr_catid_news = array_diff($arr_catid_old, $arr_catid_i);
            // Chép vào bảng catid nếu tin này trước đó chưa thuộc chuyên mục được chuyển tới
            if (!in_array((int) $catidnews, array_map('intval', $arr_catid_news), true)) {
                try {
                    $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catidnews . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $row['id']);
                    $arr_catid_news[] = $catidnews;
                } catch (Throwable $e) {
                    trigger_error($e);
                }
            }
            if ($catid == $row['catid']) {
                $row['catid'] = $catidnews;
            }

            $sql_status = '';
            if (array_intersect($arr_catid_news, $array_cat_locked) == [] and $row['status'] > $global_code_defined['row_locked_status']) {
                $sql_status = ', status=' . ($row['status'] - ($global_code_defined['row_locked_status'] + 1));
            }

            foreach ($arr_catid_news as $catid_i) {
                if (isset($global_array_cat[$catid_i])) {
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET catid=' . $row['catid'] . ", listcatid = '" . implode(',', $arr_catid_news) . "'" . $sql_status . ' WHERE id =' . $row['id']);
                }
            }
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET catid=' . $row['catid'] . ", listcatid = '" . implode(',', $arr_catid_news) . "'" . $sql_status . ' WHERE id =' . $row['id']);
        }
        $db->query('DROP TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE catid=' . $catid);

        foreach ($ad_block_cat as $ad_block_id) {
            nv_unregister_block(nv_get_blcat_tag($catid, $ad_block_id));
        }

        nv_fix_cat_order();
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => '',
            'refresh' => true
        ]);
    } elseif (!empty($movecat)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'catidnews',
            'mess' => $nv_Lang->getModule('delcat_msg_rows_noselect')
        ]);
    } else {
        $sql = 'SELECT catid, title, lev FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid !=' . $catid . ' AND status IN(' . implode(',', $global_code_defined['cat_visible_status']) . ') ORDER BY sort ASC';
        $result = $db->query($sql);
        $cat_list = [0 => '&nbsp;'];
        while ($_scratch = $result->fetch(3)) {
            [$catid_i, $title_i, $lev_i] = $_scratch;
            unset($_scratch);
            $xtitle_i = '';
            if ($lev_i > 0) {
                $xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
                for ($i = 1; $i <= $lev_i; ++$i) {
                    $xtitle_i .= '---';
                }
                $xtitle_i .= '>&nbsp;';
            }
            $xtitle_i .= $title_i;
            $cat_list[$catid_i] = $xtitle_i;
        }

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_module_tpl_dir('del_cat.tpl'));
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('MODULE_NAME', $module_name);
        $tpl->assign('OP', $op);
        $tpl->assign('CHECKSS', csrf_create($_csrf_key));
        $tpl->assign('CATID', $catid);
        $tpl->assign('TITLE', $nv_Lang->getModule('delcat_msg_rows_select', $title, $check_rows));
        $tpl->assign('CAT_LIST', $cat_list);

        nv_jsonOutput([
            'status' => 'html',
            'html' => $tpl->fetch('del_cat.tpl')
        ]);
    }
}

if (!$submitconfirm) {
    nv_jsonOutput([
        'status' => 'confirm_delcat',
        'mess' => ''
    ]);
}

$sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid;
if ($db->exec($sql)) {
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('delcatandrows'), $title, $admin_info['userid']);

    foreach ($ad_block_cat as $ad_block_id) {
        nv_unregister_block(nv_get_blcat_tag($catid, $ad_block_id));
    }

    nv_fix_cat_order();
    $db->query('DROP TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid);
}
$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE catid=' . $catid);
$nv_Cache->delMod($module_name);

nv_jsonOutput([
    'status' => 'OK',
    'mess' => ''
]);
