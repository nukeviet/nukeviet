<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

/**
 * Khi di chuyển bài viết sẽ làm mất hoàn toàn các chuyên mục cũ dó đó nếu bài viết
 * đang bị đình chỉ thì chúng sẽ được trả lại trạng thái trước đó.
 */

$page_title = $lang_module['move'];

$id_array = array();
$listid = $nv_Request->get_string('listid', 'get,post', '');
$catids = array_unique($nv_Request->get_typed_array('catids', 'post', 'int', array()));
$catid = $nv_Request->get_int('catid', 'get,post', 0);

if ($nv_Request->isset_request('idcheck', 'post')) {
    // Kiểm tra ID các chuyên mục phải hợp lệ
    $array_catid_allowed = array();
    foreach ($global_array_cat as $catid_i => $array_value) {
        if (in_array($array_value['status'], $global_code_defined['cat_visible_status'])) {
            $array_catid_allowed[$catid_i] = $catid_i;
        }
    }
    $catids = array_intersect($catids, $array_catid_allowed);
    $id_array = array_unique($nv_Request->get_typed_array('idcheck', 'post', 'int', array()));

    if (!empty($id_array) and !empty($catids)) {
        $listcatid = implode(',', $catids);
        if (empty($catid) or !in_array($catid, $catids)) {
            $catid = $catids[0];
        }

        $result = $db->query('SELECT id, listcatid, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id IN (' . implode(',', $id_array) . ')');
        while (list($id, $listcatid_old, $status) = $result->fetch(3)) {
            // Xóa hết các chuyên mục cũ đi
            $array_catid_old = explode(',', $listcatid_old);
            foreach ($array_catid_old as $catid_i) {
                $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' WHERE id=' . $id);
            }

            // Nếu bài viết hiện tại đang bị khóa bởi chuyên mục thì sau khi di chuyển qua chuyên mục khác nó sẽ trở lại trạng thái ban đầu
            $sql_status = '';
            if ($status > $global_code_defined['row_locked_status']) {
                $sql_status = ', status=' . ($status - ($global_code_defined['row_locked_status'] + 1));
            }
            $db->exec('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET catid=' . $catid . ', listcatid=' . $db->quote($listcatid) . $sql_status . ' WHERE id=' . $id);

            foreach ($catids as $catid_i) {
                try {
                    $db->exec('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id);
                } catch (PDOException $e) {
                    $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' WHERE id=' . $id);
                    $db->exec('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id);
                }
            }
        }

        $nv_Cache->delMod($module_name);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
} else {
    $id_array = array_map('intval', explode(',', $listid));
}

if (empty($id_array)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$db->sqlreset()->select('id, title')->from(NV_PREFIXLANG . '_' . $module_data . '_rows')->where('id IN (' . implode(',', $id_array) . ')')->order('id DESC');
$result = $db->query($db->sql());

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

while (list($id, $title) = $result->fetch(3)) {
    $xtpl->assign('ROW', array(
        'id' => $id,
        'title' => $title,
        'checked' => in_array($id, $id_array) ? ' checked="checked"' : ''
    ));

    $xtpl->parse('main.loop');
}

foreach ($global_array_cat as $catid_i => $array_value) {
    if (in_array($array_value['status'], $global_code_defined['cat_visible_status'])) {
        $space = intval($array_value['lev']) * 30;
        $catiddisplay = (sizeof($catids) > 1 and (in_array($catid_i, $catids))) ? '' : ' display: none;';
        $temp = array(
            'catid' => $catid_i,
            'space' => $space,
            'title' => $array_value['title'],
            'checked' => (in_array($catid_i, $catids)) ? ' checked="checked"' : '',
            'catidchecked' => ($catid_i == $catid) ? ' checked="checked"' : '',
            'catiddisplay' => $catiddisplay
        );
        $xtpl->assign('CATS', $temp);
        $xtpl->parse('main.catid');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
