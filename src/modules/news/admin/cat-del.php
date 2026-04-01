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

$stmt = $db->prepare('SELECT catid, parentid, title, ad_block_cat FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid = :catid');
$stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
$stmt->execute();
$_row = $stmt->fetch();
$stmt->closeCursor();

if (empty($_row)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Category not found!'
    ]);
}

$catid = (int) $_row['catid'];
$parentid = (int) $_row['parentid'];
$title = $_row['title'];
$ad_block_cat = $_row['ad_block_cat'];

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
$stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid = :parentid');
$stmt->bindValue(':parentid', $catid, PDO::PARAM_INT);
$stmt->execute();
$check_parentid = $stmt->fetchColumn();
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

        $stmt_upd_rows = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET catid= :catid, listcatid = :listcatid WHERE id= :id');

        $sql = $db->query('SELECT id, catid, listcatid, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' ORDER BY weight DESC');
        while ($_row = $sql->fetch()) {
            if ($_row['catid'] == $_row['listcatid']) {
                nv_del_content_module($_row['id']);
                $weight_min = $_row['weight'];
            } else {
                $arr_catid_old = explode(',', $_row['listcatid']);
                $arr_catid_i = [
                    $catid
                ];
                $arr_catid_news = array_diff($arr_catid_old, $arr_catid_i);
                if ($catid == $_row['catid']) {
                    $_row['catid'] = $arr_catid_news[0];
                }
                $listcatid_new = implode(',', $arr_catid_news);
                foreach ($arr_catid_news as $catid_i) {
                    if (isset($global_array_cat[$catid_i])) {
                        $stmt_upd_cat_i = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET catid= :catid, listcatid = :listcatid WHERE id= :id');
                        $stmt_upd_cat_i->bindValue(':catid', $_row['catid'], PDO::PARAM_INT);
                        $stmt_upd_cat_i->bindValue(':listcatid', $listcatid_new, PDO::PARAM_STR);
                        $stmt_upd_cat_i->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                        $stmt_upd_cat_i->execute();
                    }
                }
                $stmt_upd_rows->bindValue(':catid', $_row['catid'], PDO::PARAM_INT);
                $stmt_upd_rows->bindValue(':listcatid', $listcatid_new, PDO::PARAM_STR);
                $stmt_upd_rows->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                $stmt_upd_rows->execute();
            }
        }
        $sql->closeCursor();
        $db->exec('DROP TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid);

        $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid= :catid');
        $stmt_del->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt_del->execute();

        $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE catid= :catid');
        $stmt_del->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt_del->execute();

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
        $stmt_sel = $db->prepare('SELECT catid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE status IN(' . implode(',', $global_code_defined['cat_visible_status']) . ') AND catid = :catid');
        $stmt_sel->bindValue(':catid', $catidnews, PDO::PARAM_INT);
        $stmt_sel->execute();
        $_row_sel = $stmt_sel->fetch();
        $stmt_sel->closeCursor();

        $catidnews = (int) ($_row_sel['catid'] ?? 0);
        $newstitle = $_row_sel['title'] ?? '';

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

        $stmt_ins = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catidnews . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id= :id');

        $sql = $db->query('SELECT id, catid, listcatid, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid);

        while ($_row = $sql->fetch()) {
            $arr_catid_old = explode(',', $_row['listcatid']);
            $arr_catid_i = [
                $catid
            ];
            $arr_catid_news = array_diff($arr_catid_old, $arr_catid_i);
            // Chép vào bảng catid nếu tin này trước đó chưa thuộc chuyên mục được chuyển tới
            if (!in_array((int) $catidnews, array_map('intval', $arr_catid_news), true)) {
                try {
                    $stmt_ins->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                    $stmt_ins->execute();
                    $arr_catid_news[] = $catidnews;
                } catch (Throwable $e) {
                    trigger_error($e);
                }
            }
            if ($catid == $_row['catid']) {
                $_row['catid'] = $catidnews;
            }

            $sql_status = '';
            if (array_intersect($arr_catid_news, $array_cat_locked) == [] and $_row['status'] > $global_code_defined['row_locked_status']) {
                $sql_status = ', status=' . ($_row['status'] - ($global_code_defined['row_locked_status'] + 1));
            }

            $listcatid_new = implode(',', $arr_catid_news);
            foreach ($arr_catid_news as $catid_i) {
                if (isset($global_array_cat[$catid_i])) {
                    $stmt_upd_cat_i = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET catid= :catid, listcatid= :listcatid' . $sql_status . ' WHERE id= :id');
                    $stmt_upd_cat_i->bindValue(':catid', $_row['catid'], PDO::PARAM_INT);
                    $stmt_upd_cat_i->bindValue(':listcatid', $listcatid_new, PDO::PARAM_STR);
                    $stmt_upd_cat_i->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                    $stmt_upd_cat_i->execute();
                }
            }
            $stmt_upd_rows = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET catid= :catid, listcatid= :listcatid' . $sql_status . ' WHERE id= :id');
            $stmt_upd_rows->bindValue(':catid', $_row['catid'], PDO::PARAM_INT);
            $stmt_upd_rows->bindValue(':listcatid', $listcatid_new, PDO::PARAM_STR);
            $stmt_upd_rows->bindValue(':id', $_row['id'], PDO::PARAM_INT);
            $stmt_upd_rows->execute();
        }
        $sql->closeCursor();

        $db->exec('DROP TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid);

        $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid= :catid');
        $stmt_del->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt_del->execute();

        $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE catid= :catid');
        $stmt_del->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt_del->execute();

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
        while ($_row_cat = $result->fetch()) {
            $xtitle_i = '';
            if ($_row_cat['lev'] > 0) {
                $xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
                for ($i = 1; $i <= $_row_cat['lev']; ++$i) {
                    $xtitle_i .= '---';
                }
                $xtitle_i .= '>&nbsp;';
            }
            $xtitle_i .= $_row_cat['title'];
            $cat_list[$_row_cat['catid']] = $xtitle_i;
        }
        $result->closeCursor();

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_module_tpl_dir('cat-del.tpl'));
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('MODULE_NAME', $module_name);
        $tpl->assign('OP', $op);
        $tpl->assign('CHECKSS', csrf_create($_csrf_key));
        $tpl->assign('CATID', $catid);
        $tpl->assign('TITLE', $nv_Lang->getModule('delcat_msg_rows_select', $title, $check_rows));
        $tpl->assign('CAT_LIST', $cat_list);

        nv_jsonOutput([
            'status' => 'html',
            'html' => $tpl->fetch('cat-del.tpl')
        ]);
    }
}

if (!$submitconfirm) {
    nv_jsonOutput([
        'status' => 'confirm_delcat',
        'mess' => ''
    ]);
}

$stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid = :catid');
$stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('delcatandrows'), $title, $admin_info['userid']);

    foreach ($ad_block_cat as $ad_block_id) {
        nv_unregister_block(nv_get_blcat_tag($catid, $ad_block_id));
    }

    nv_fix_cat_order();
    $db->exec('DROP TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid);
}
$stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE catid = :catid');
$stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
$stmt->execute();
$nv_Cache->delMod($module_name);

nv_jsonOutput([
    'status' => 'OK',
    'mess' => ''
]);
