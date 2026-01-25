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

// Thêm/Sửa khối menu
$action = $nv_Request->get_title('action', 'get', '');
if ($action == 'block') {
    $arr = [
        'id' => $nv_Request->get_int('id', 'get', 0),
        'title' => ''
    ];
    if (!empty($arr['id'])) {
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $arr['id'];
        $result = $db->query($sql);
        $arr = $result->fetch();
        if (empty($arr)) {
            exit('Error');
        }
    }

    // Ghi CSDL
    if ($nv_Request->get_int('save', 'post')) {
        $arr['title'] = $nv_Request->get_title('title', 'post', '');
        if (empty($arr['title'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('error_menu_block')
            ]);
        }

        if (empty($arr['id'])) {
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title) VALUES ( :title )';
            $data_insert = [];
            $data_insert['title'] = $arr['title'];
            $arr['id'] = $db->insert_id($sql, 'id', $data_insert);
            if (empty($arr['id'])) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('errorsave')
                ]);
            }
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Add menu-block', 'Menu-block id: ' . $arr['id'], $admin_info['userid']);
        } else {
            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title= :title WHERE id =' . $arr['id']);
            $stmt->bindParam(':title', $arr['title'], PDO::PARAM_STR);
            if (!$stmt->execute()) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('errorsave')
                ]);
            }
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit menu-block', 'Menu-block id: ' . $arr['id'], $admin_info['userid']);
        }

        $action_menu = $nv_Request->get_title('action_menu', 'post', '', 1);
        $weight = 0;
        $sort = 0;
        $mid = $arr['id'];
        if ($action_menu == 'sys_mod' or $action_menu == 'sys_mod_sub') {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . $mid);
            unset($site_mods['menu'], $site_mods['comment'], $site_mods['zalo']);
            foreach ($site_mods as $mod_name => $modvalues) {
                ++$weight;
                ++$sort;
                $lev = 0;
                $subitem = '';
                $parentid = nv_menu_insert_id($mid, 0, $modvalues['custom_title'], $weight, $sort, 0, $mod_name, '', $modvalues['groups_view']);
                if ($parentid and $action_menu == 'sys_mod_sub') {
                    // Thêm menu từ các chủ đề của module
                    $subweight = 0;
                    $array_sub_id = [];
                    if (file_exists(NV_ROOTDIR . '/modules/' . $modvalues['module_file'] . '/menu.php')) {
                        $array_item = [];
                        $mod_data = $modvalues['module_data'];
                        include NV_ROOTDIR . '/modules/' . $modvalues['module_file'] . '/menu.php';
                        foreach ($array_item as $key => $item) {
                            $pid = (isset($item['parentid'])) ? $item['parentid'] : 0;
                            if (empty($pid)) {
                                ++$subweight;
                                ++$sort;
                                $groups_view = (isset($item['groups_view'])) ? $item['groups_view'] : '6';
                                $subparentid = nv_menu_insert_id($mid, $parentid, $item['title'], $subweight, $sort, 1, $mod_name, $item['alias'], $groups_view);
                                $array_sub_id[] = $subparentid;
                                nv_menu_insert_submenu($mid, $subparentid, $sort, 2, $mod_name, $array_item, $key);
                            }
                        }
                    }
                    // Thêm menu từ các funtion
                    if (!empty($modvalues['funcs'])) {
                        foreach ($modvalues['funcs'] as $key => $sub_item) {
                            if ($sub_item['in_submenu'] == 1) {
                                ++$subweight;
                                ++$sort;
                                $array_sub_id[] = nv_menu_insert_id($mid, $parentid, $sub_item['func_custom_name'], $subweight, $sort, 1, $mod_name, $key, $modvalues['groups_view']);
                            }
                        }
                    }
                    if (!empty($array_sub_id)) {
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . "_rows SET subitem='" . implode(',', $array_sub_id) . "' WHERE id=" . $parentid);
                    }
                }
            }
        } elseif (isset($site_mods[$action_menu])) {
            $mod_name = $action_menu;
            $modvalues = $site_mods[$action_menu];
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . $mid);
            // Thêm menu từ các chủ đề của module
            if (file_exists(NV_ROOTDIR . '/modules/' . $modvalues['module_file'] . '/menu.php')) {
                $mod_data = $modvalues['module_data'];

                $array_item = [];
                include NV_ROOTDIR . '/modules/' . $modvalues['module_file'] . '/menu.php';
                foreach ($array_item as $key => $item) {
                    $pid = (isset($item['parentid'])) ? $item['parentid'] : 0;
                    if (empty($pid)) {
                        ++$weight;
                        ++$sort;
                        $groups_view = (isset($item['groups_view'])) ? $item['groups_view'] : '6';
                        $parentid = nv_menu_insert_id($mid, 0, $item['title'], $weight, $sort, 0, $mod_name, $item['alias'], $groups_view);
                        nv_menu_insert_submenu($mid, $parentid, $sort, 1, $mod_name, $array_item, $key);
                    }
                }
            }

            // Thêm menu từ các funtion
            if (!empty($modvalues['funcs'])) {
                foreach ($modvalues['funcs'] as $key => $sub_item) {
                    if ($sub_item['in_submenu'] == 1) {
                        ++$weight;
                        ++$sort;
                        $array_sub_id[] = nv_menu_insert_id($mid, 0, $sub_item['func_custom_name'], $weight, $sort, 0, $mod_name, $key, $modvalues['groups_view']);
                    }
                }
            }
        }

        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }

    // Xuất HTML cho modal
    $xtpl = new XTemplate('blocks.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=blocks&amp;action=block' . (!empty($arr['id']) ? '&amp;id=' . $arr['id'] : ''));
    $xtpl->assign('FORM_CAPTION', $arr['id'] ? $nv_Lang->getModule('edit_menu') : $nv_Lang->getModule('add_menu'));
    $xtpl->assign('OP', $op);

    $xtpl->assign('DATAFORM', $arr);
    unset($site_mods['menu'], $site_mods['comment'], $site_mods['zalo']);
    foreach ($site_mods as $mod_name => $modvalues) {
        $xtpl->assign('OPTIONVALUE', $mod_name);
        $xtpl->assign('OPTIONTITLE', $modvalues['custom_title']);
        $xtpl->parse('block.action_menu');
    }

    $xtpl->parse('block');
    nv_htmlOutput($xtpl->text('block'));
}

// Xóa khối menu
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    $id = $nv_Request->get_int('id', 'post', 0);

    $query = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
    $title = $db->query($query)->fetchColumn();

    if (!empty($title)) {
        if ($db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id)) {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = ' . $id);
            nv_insert_logs(NV_LANG_DATA, $module_name, 'delete menu-block id: ' . $id, $title, $admin_info['userid']);
            $nv_Cache->delMod($module_name);
        }
    }
    exit('OK');
}

$page_title = $nv_Lang->getModule('name_block');

// List menu
$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data)
    ->order('id DESC');

$query2 = $db->query($db->sql());

$array = [];
while ($row = $query2->fetch()) {
    $arr_items = [];
    $sql = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = ' . $row['id'] . ' ORDER BY sort ASC';
    $result = $db->query($sql);
    while ([$title_i] = $result->fetch(3)) {
        $arr_items[] = $title_i;
    }

    $array[$row['id']] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'menu_item' => implode('&nbsp;&nbsp; ', $arr_items),
        'num' => count($arr_items),
        'link_view' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mid=' . $row['id'],
        'edit_url' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=blocks&amp;action=block&amp;id=' . $row['id']
    ];
}

$xtpl = new XTemplate('blocks.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=blocks&amp;action=block');

if (empty($array)) {
    $xtpl->assign('ERROR', $nv_Lang->getModule('data_no'));
    $xtpl->parse('main.error');
} else {
    foreach ($array as $row) {
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.table.loop1');
    }
    $xtpl->parse('main.table');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
