<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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

$allow_func = ['main', 'menu', 'rows', 'link_menu', 'link_module', 'change_weight_row', 'del_row', 'change_active'];

// Loai lien ket
$type_target = [];
$type_target[1] = $lang_module['type_target1'];
$type_target[2] = $lang_module['type_target2'];
$type_target[3] = $lang_module['type_target3'];

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

    $sql = 'SELECT id, parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE parentid=' . $parentid . ' AND mid= ' . $mid . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $array_cat_order = [];
    while ($row = $result->fetch()) {
        $array_cat_order[] = $row['id'];
    }
    $result->closeCursor();

    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }

    foreach ($array_cat_order as $catid_i) {
        ++$order;
        ++$weight;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight=' . $weight . ', sort=' . $order . ", lev='" . $lev . "' WHERE id=" . (int) $catid_i;
        $db->query($sql);
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

    $sql = 'SELECT title, subitem FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id . ' AND parentid=' . $parentid;
    $row = $db->query($sql)->fetch();

    if (empty($row)) {
        return false;
    }

    $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;
    if ($db->exec($sql)) {
        // Cap nhat cho menu cha
        if ($parentid > 0) {
            $sql = 'SELECT subitem FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $parentid;
            $subitem = $db->query($sql)->fetch();
            if (!empty($subitem)) {
                $subitem = implode(',', array_diff(array_filter(array_unique(explode(',', $subitem['subitem']))), [$id]));

                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET subitem= :subitem WHERE id=' . $parentid);
                $stmt->bindParam(':subitem', $subitem, PDO::PARAM_STR, strlen($subitem));
                $stmt->execute();
            }
        }

        $subitem = (!empty($row['subitem'])) ? explode(',', $row['subitem']) : [];
        foreach ($subitem as $id) {
            $sql = 'SELECT parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;

            list($parentid) = $db->query($sql)->fetch(3);
            nv_menu_del_sub($id, $parentid);
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete menu item', 'Item ID ' . $id, $admin_info['userid']);
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
 * @param string $sp_i
 */
function nv_menu_get_submenu($id, $alias_selected, $array_item, $sp_i)
{
    global $array_submenu, $sp, $mod_name;

    foreach ($array_item as $item2) {
        if (isset($item2['parentid']) and $item2['parentid'] == $id) {
            $item2['title'] = $sp_i . $item2['title'];
            $item2['module'] = $mod_name;
            $item2['selected'] = ($item2['alias'] == $alias_selected) ? ' selected="selected"' : '';

            $array_submenu[] = $item2;
            nv_menu_get_submenu($item2['key'], $alias_selected, $array_item, $sp_i . $sp);
        }
    }
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

    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows (parentid, mid, title, link, note, weight, sort, lev, subitem, groups_view, module_name, op, target, css, active_type, status) VALUES (
		' . $parentid . ',
		' . $mid . ',
		:title,
		:link,
		:note,
		' . $weight . ',
		' . $sort . ',
		' . $lev . ",
		'',
		:groups_view,
		:module_name,
		:op,
		1,
		'',
		1,
		1
	)";

    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $mod_name;
    if (!empty($op_mod)) {
        $link .= '&amp;' . NV_OP_VARIABLE . '=' . $op_mod;
    }
    $data_insert = [];
    $data_insert['title'] = $title;
    $data_insert['link'] = $link;
    $data_insert['note'] = '';
    $data_insert['groups_view'] = $groups_view;
    $data_insert['module_name'] = $mod_name;
    $data_insert['op'] = $op_mod;

    return $db->insert_id($sql, 'id', $data_insert);
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
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . "_rows SET subitem='" . implode(',', $array_sub_id) . "' WHERE id=" . $parentid);
    }
}
