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
        $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = :id');
        $stmt->bindValue(':id', $arr['id'], PDO::PARAM_INT);
        $stmt->execute();
        $arr = $stmt->fetch();
        $stmt->closeCursor();
        if (empty($arr)) {
            exit('Error');
        }
    }

    // Ghi CSDL
    if ($nv_Request->get_int('save', 'post')) {
        if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }

        $arr['title'] = $nv_Request->get_title('title', 'post', '');
        if (empty($arr['title'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('error_menu_block')
            ]);
        }

        if (empty($arr['id'])) {
            $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title) VALUES (:title)');
            $stmt->bindValue(':title', $arr['title'], PDO::PARAM_STR);
            $stmt->execute();
            $arr['id'] = $db->lastInsertId();
            if (empty($arr['id'])) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('errorsave')
                ]);
            }
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Add menu-block', 'Menu-block id: ' . $arr['id'], $admin_info['userid']);
        } else {
            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title = :title WHERE id = :id');
            $stmt->bindValue(':title', $arr['title'], PDO::PARAM_STR);
            $stmt->bindValue(':id', $arr['id'], PDO::PARAM_INT);
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
            $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid');
            $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
            $stmt->execute();
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
                        $stmt_subitem = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET subitem = :subitem WHERE id = :id');
                        $stmt_subitem->bindValue(':subitem', implode(',', $array_sub_id), PDO::PARAM_STR);
                        $stmt_subitem->bindValue(':id', $parentid, PDO::PARAM_INT);
                        $stmt_subitem->execute();
                    }
                }
            }
        } elseif (isset($site_mods[$action_menu])) {
            $mod_name = $action_menu;
            $modvalues = $site_mods[$action_menu];

            $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid');
            $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
            $stmt->execute();
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
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success'),
            'refresh' => true
        ]);
    }

    // Xuất HTML cho modal
    $site_mods_filtered = $site_mods;
    unset($site_mods_filtered['menu'], $site_mods_filtered['comment'], $site_mods_filtered['zalo']);
    $action_menu_options = [];
    foreach ($site_mods_filtered as $mod_name => $modvalues) {
        $action_menu_options[] = [
            'value' => $mod_name,
            'title' => $modvalues['custom_title']
        ];
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('blocks-modal.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('FORM_CAPTION', !empty($arr['id']) ? $nv_Lang->getModule('edit_menu') : $nv_Lang->getModule('add_menu'));
    $tpl->assign('DATAFORM', $arr);
    $tpl->assign('ACTION_MENU_OPTIONS', $action_menu_options);

    nv_htmlOutput($tpl->fetch('blocks-modal.tpl'));
}

// Xóa khối menu
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $id = $nv_Request->get_int('id', 'post', 0);

    $stmt = $db->prepare('SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $title = $stmt->fetchColumn();

    if (!empty($title)) {
        $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid');
            $stmt_del->bindValue(':mid', $id, PDO::PARAM_INT);
            $stmt_del->execute();
            nv_insert_logs(NV_LANG_DATA, $module_name, 'delete menu-block id: ' . $id, $title, $admin_info['userid']);
            $nv_Cache->delMod($module_name);
        }
    }

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

$page_title = $nv_Lang->getModule('name_block');

// List menu
$stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY id DESC');
$stmt->execute();

$array = [];
$stmt_items = $db->prepare('SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid ORDER BY sort ASC');

while ($row = $stmt->fetch()) {
    $arr_items = [];
    $stmt_items->bindValue(':mid', $row['id'], PDO::PARAM_INT);
    $stmt_items->execute();
    while ($_row_item = $stmt_items->fetch()) {
        $arr_items[] = $_row_item['title'];
    }
    $stmt_items->closeCursor();

    $array[$row['id']] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'menu_item' => implode('&nbsp;&nbsp; ', $arr_items),
        'num' => count($arr_items),
        'link_view' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mid=' . $row['id'],
        'edit_url' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=blocks&amp;action=block&amp;id=' . $row['id']
    ];
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('blocks.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ARRAY', $array);

$contents = $tpl->fetch('blocks.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
