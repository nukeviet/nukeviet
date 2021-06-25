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

$arr = [];
$arr['title'] = '';
$arr['id'] = $nv_Request->get_int('id', 'post,get', 0);
$error = '';

// Add/Edit menu
if ($nv_Request->get_int('save', 'post')) {
    $arr['title'] = $nv_Request->get_title('title', 'post', '', 1);
    if (empty($arr['title'])) {
        $error = $lang_module['error_menu_block'];
    } elseif ($arr['id'] == 0) {
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title) VALUES ( :title )';
        $data_insert = [];
        $data_insert['title'] = $arr['title'];
        $arr['id'] = $db->insert_id($sql, 'id', $data_insert);
        if (empty($arr['id'])) {
            $error = $lang_module['errorsave'];
        } else {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Add menu', 'Menu id: ' . $arr['id'], $admin_info['userid']);
        }
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title= :title WHERE id =' . $arr['id']);
        $stmt->bindParam(':title', $arr['title'], PDO::PARAM_STR);
        if (!$stmt->execute()) {
            $error = $lang_module['errorsave'];
        } else {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit menu', 'Menu id: ' . $arr['id'], $admin_info['userid']);
        }
    }
    if (empty($error)) {
        $action_menu = $nv_Request->get_title('action_menu', 'post', '', 1);
        $weight = 0;
        $sort = 0;
        $mid = $arr['id'];
        if ($action_menu == 'sys_mod' or $action_menu == 'sys_mod_sub') {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . $mid);
            unset($site_mods['menu'], $site_mods['comment']);
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
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
} elseif (!empty($arr['id'])) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $arr['id'];
    $result = $db->query($sql);
    $arr = $result->fetch();
    if (empty($arr)) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->assign('DATAFORM', $arr);
unset($site_mods['menu'], $site_mods['comment']);
foreach ($site_mods as $mod_name => $modvalues) {
    $xtpl->assign('OPTIONVALUE', $mod_name);
    $xtpl->assign('OPTIONTITLE', $modvalues['custom_title']);
    $xtpl->parse('main.action_menu');
}

if ($arr['id']) {
    $page_title = $lang_module['edit_menu'];
    $op = '';
} else {
    $page_title = $lang_module['add_menu'];
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
