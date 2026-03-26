<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

define('NV_IS_FILE_ADMIN', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:menu';
$array_url_instruction['menu'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:menu#lấy_menu_tự_dộng_từ_ten_cac_chuyen_mục_module';
$array_url_instruction['rows'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:menu#xem_sửa_khối';

$allow_func = ['main', 'blocks'];

// Loai lien ket
$type_target = [];
$type_target[1] = $nv_Lang->getModule('type_target1');
$type_target[2] = $nv_Lang->getModule('type_target2');
$type_target[3] = $nv_Lang->getModule('type_target3');

/**
 * nv_list_menu()
 *
 * @return array
 */
function nv_list_menu()
{
    global $db, $module_data;

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY id ASC';
    $result = $db->query($sql);

    $list = [];
    while ($row = $result->fetch()) {
        $list[$row['id']] = [
            'id' => $row['id'],
            'title' => $row['title'],
        ];
    }
    $result->closeCursor();

    return $list;
}

/**
 * menu_fix_order()
 *
 * @param int $mid
 * @param int $parentid
 * @param int $order
 * @param int $lev
 * @return int
 */
function menu_fix_order($mid, $parentid = 0, $order = 0, $lev = 0)
{
    global $db, $module_data;

    $stmt = $db->prepare('SELECT id, parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE parentid = :parentid AND mid = :mid ORDER BY weight ASC');
    $stmt->bindValue(':parentid', $parentid, PDO::PARAM_INT);
    $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
    $stmt->execute();

    $array_cat_order = [];
    while ($row = $stmt->fetch()) {
        $array_cat_order[] = $row['id'];
    }
    $stmt->closeCursor();

    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }

    $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight = :weight, sort = :sort, lev = :lev WHERE id = :id');

    foreach ($array_cat_order as $catid_i) {
        ++$order;
        ++$weight;
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':sort', $order, PDO::PARAM_INT);
        $stmt_update->bindValue(':lev', $lev, PDO::PARAM_INT);
        $stmt_update->bindValue(':id', $catid_i, PDO::PARAM_INT);
        $stmt_update->execute();

        $order = menu_fix_order($mid, $catid_i, $order, $lev);
    }

    return $order;
}

/**
 * nv_menu_del_sub()
 *
 * @param int $id
 * @param int $parentid
 * @return bool
 * @throws PDOException
 */
function nv_menu_del_sub($id, $parentid)
{
    global $module_data, $module_name, $db, $admin_info;

    $stmt = $db->prepare('SELECT title, subitem FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id AND parentid = :parentid');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':parentid', $parentid, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();

    if (empty($row)) {
        return false;
    }

    $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute() && $stmt->rowCount()) {
        // Cap nhat cho menu cha
        if ($parentid > 0) {
            $stmt_sub = $db->prepare('SELECT subitem FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');
            $stmt_sub->bindValue(':id', $parentid, PDO::PARAM_INT);
            $stmt_sub->execute();
            $subitem = $stmt_sub->fetch();
            $stmt_sub->closeCursor();

            if (!empty($subitem)) {
                $subitem_str = implode(',', array_diff(array_filter(array_unique(explode(',', $subitem['subitem']))), [$id]));

                $stmt_upd = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET subitem = :subitem WHERE id = :id');
                $stmt_upd->bindValue(':subitem', $subitem_str, PDO::PARAM_STR);
                $stmt_upd->bindValue(':id', $parentid, PDO::PARAM_INT);
                $stmt_upd->execute();
            }
        }

        $subitem = (!empty($row['subitem'])) ? explode(',', $row['subitem']) : [];
        $stmt_parent = $db->prepare('SELECT parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');

        foreach ($subitem as $sub_id) {
            $stmt_parent->bindValue(':id', $sub_id, PDO::PARAM_INT);
            $stmt_parent->execute();
            $parentid_child = $stmt_parent->fetchColumn();

            nv_menu_del_sub($sub_id, $parentid_child);
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete menu item', 'Item ID ' . $sub_id, $admin_info['userid']);
        }
    }

    return true;
}

/**
 * nv_menu_get_submenu()
 *
 * @param int    $id
 * @param string $alias_selected
 * @param array  $array_item
 * @param array  $sps
 * @param array  $subs
 */
function nv_menu_get_submenu($id, $alias_selected, $array_item, &$sps, &$subs)
{
    global $array_submenu, $mod_name;

    foreach ($array_item as $item2) {
        if (isset($item2['parentid']) and $item2['parentid'] == $id) {
            ++$subs[$item2['parentid']];
            $sp_title = $sps[$item2['parentid']] . $subs[$item2['parentid']] . '.';
            $sps[$item2['key']] = $sp_title;
            $item2['name'] = $sp_title . ' ' . $item2['title'];
            $item2['module'] = $mod_name;
            $item2['selected'] = ($item2['alias'] == $alias_selected) ? ' selected="selected"' : '';

            $array_submenu[] = $item2;
            nv_menu_get_submenu($item2['key'], $alias_selected, $array_item, $sps, $subs);
        }
    }
}

/**
 * nv_menu_get_subcat()
 *
 * @param int   $id
 * @param array $menulist
 * @param array $array_subcat
 */
function nv_menu_get_subcat($id, $menulist, &$array_subcat)
{
    foreach ($menulist as $row) {
        if ($row['parentid'] == $id) {
            $array_subcat[] = $row;
            nv_menu_get_subcat($row['id'], $menulist, $array_subcat);
        }
    }
}

function nv_get_menulist($mid)
{
    global $db, $module_data;

    $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid ORDER BY parentid, sort ASC');
    $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
    $stmt->execute();

    $sps = [];
    $subs = [];
    $i = 0;
    $menulist = [];
    while ($row = $stmt->fetch()) {
        $row['parentid'] = (int) $row['parentid'];
        $sp_title = '';
        if ($row['parentid'] > 0) {
            !isset($subs[$row['parentid']]) && $subs[$row['parentid']] = 0;
            ++$subs[$row['parentid']];
            $sp_title = $sps[$row['parentid']] . $subs[$row['parentid']] . '.';
            $sps[$row['id']] = $sp_title;
        } else {
            ++$i;
            $sp_title = $i . '.';
            $sps[$row['id']] = $sp_title;
            $subs[$row['id']] = 0;
        }
        $row['name'] = $sp_title . ' ' . $row['title'];
        $menulist[$row['id']] = $row;
    }
    $stmt->closeCursor();

    return $menulist;
}

/**
 * nv_menu_insert_id()
 *
 * @param int    $mid
 * @param int    $parentid
 * @param string $title
 * @param int    $weight
 * @param int    $sort
 * @param int    $lev
 * @param string $mod_name
 * @param string $op_mod
 * @param string $groups_view
 * @return false|int
 */
function nv_menu_insert_id($mid, $parentid, $title, $weight, $sort, $lev, $mod_name, $op_mod, $groups_view)
{
    global $module_data, $db;

    $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_rows (parentid, mid, title, link, note, weight, sort, lev, subitem, groups_view, module_name, op, target, css, active_type, status) VALUES (:parentid, :mid, :title, :link, :note, :weight, :sort, :lev, '', :groups_view, :module_name, :op, 1, '', 1, 1)");

    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $mod_name;
    if (!empty($op_mod)) {
        $link .= '&amp;' . NV_OP_VARIABLE . '=' . $op_mod;
    }

    $stmt->bindValue(':parentid', $parentid, PDO::PARAM_INT);
    $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':link', $link, PDO::PARAM_STR);
    $stmt->bindValue(':note', '', PDO::PARAM_STR);
    $stmt->bindValue(':weight', $weight, PDO::PARAM_INT);
    $stmt->bindValue(':sort', $sort, PDO::PARAM_INT);
    $stmt->bindValue(':lev', $lev, PDO::PARAM_INT);
    $stmt->bindValue(':groups_view', $groups_view, PDO::PARAM_STR);
    $stmt->bindValue(':module_name', $mod_name, PDO::PARAM_STR);
    $stmt->bindValue(':op', $op_mod, PDO::PARAM_STR);
    $stmt->execute();

    return $db->lastInsertId();
}

/**
 * nv_menu_insert_submenu()
 *
 * @param int    $mid
 * @param int    $parentid
 * @param int    $sort
 * @param int    $lev
 * @param string $mod_name
 * @param array  $array_item
 * @param int    $key
 */
function nv_menu_insert_submenu($mid, $parentid, &$sort, $lev, $mod_name, $array_item, $key)
{
    global $db, $module_data;

    $array_sub_id = [];
    $subweight = 0;
    $sublev = $lev + 1;
    foreach ($array_item as $subkey => $subitem) {
        if (isset($subitem['parentid']) and $subitem['parentid'] == $key) {
            ++$subweight;
            ++$sort;
            $groups_view = (isset($subitem['groups_view'])) ? $subitem['groups_view'] : '6';
            $subparentid = nv_menu_insert_id($mid, $parentid, $subitem['title'], $subweight, $sort, $lev, $mod_name, $subitem['alias'], $groups_view);
            $array_sub_id[] = $subparentid;

            nv_menu_insert_submenu($mid, $subparentid, $sort, $sublev, $mod_name, $array_item, $subkey);
        }
    }

    if (!empty($array_sub_id)) {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET subitem = :subitem WHERE id = :id');
        $stmt->bindValue(':subitem', implode(',', $array_sub_id), PDO::PARAM_STR);
        $stmt->bindValue(':id', $parentid, PDO::PARAM_INT);
        $stmt->execute();
    }
}
