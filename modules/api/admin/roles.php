<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

// Sắp xếp dạng cây các API
function apiTrees($role_object, $array_post)
{
    global $site_mods, $lang_module, $array_api_actions, $user_array_api_actions;

    $total_api_enabled = 0;
    $array_api_trees = [];
    $array_api_contents = [];

    $_cat_is_active = true;
    $actions = $role_object == 'admin' ? $array_api_actions : $user_array_api_actions;
    foreach ($actions as $keysysmodule => $sysmodule_data) {
        // Lev1: Hệ thống hoặc các module
        $array_api_trees[$keysysmodule] = [
            'active' => false,
            'total_api' => 0,
            'key' => $keysysmodule,
            'name' => $keysysmodule ? $site_mods[$keysysmodule]['custom_title'] : $lang_module['api_of_system'],
            'subs' => []
        ];

        // Lev 2: Các cat của hệ thống hoặc các module, trong HTML đối xử ngang nhau
        $role_data = $role_object == $array_post['role_object'] ? $array_post['role_data'] : ['sys' => [], NV_LANG_DATA => []];
        foreach ($sysmodule_data as $catkey => $catapis) {
            if (!empty($catkey)) {
                $cat2_key = $keysysmodule . '_' . $catkey;
                $cat2_is_active = $_cat_is_active;
                $cat2_total_api = 0;

                $array_api_trees[$keysysmodule]['subs'][$cat2_key] = [
                    'active' => $cat2_is_active,
                    'total_api' => 0,
                    'key' => $cat2_key,
                    'name' => $catapis['title'],
                    'checked' => false
                ];

                // Các API của lev1 (Các api có cat của lev2 trống)
                $array_api_contents[$cat2_key] = [
                    'key' => $cat2_key,
                    'active' => $cat2_is_active,
                    'apis' => [],
                    'checkall' => true
                ];

                foreach ($catapis['apis'] as $api) {
                    $api_checked = ((empty($keysysmodule) and in_array($api['cmd'], $role_data['sys'], true)) or (!empty($keysysmodule) and isset($role_data[NV_LANG_DATA][$keysysmodule]) and in_array($api['cmd'], $role_data[NV_LANG_DATA][$keysysmodule], true)));
                    $api_checked && ++$total_api_enabled;
                    $api_checked && ++$cat2_total_api;
                    !$api_checked && $array_api_contents[$cat2_key]['checkall'] = false;

                    $array_api_contents[$cat2_key]['apis'][] = [
                        'cmd' => $api['cmd'],
                        'name' => $api['title'],
                        'checked' => $api_checked
                    ];
                }

                $array_api_trees[$keysysmodule]['subs'][$cat2_key]['total_api'] = $cat2_total_api;
            } else {
                // Các API của lev1 (Các api có cat của lev2 trống)
                $array_api_contents[$keysysmodule] = [
                    'key' => $keysysmodule,
                    'active' => false,
                    'apis' => [],
                    'checkall' => true
                ];

                foreach ($catapis['apis'] as $api) {
                    $api_checked = ((empty($keysysmodule) and in_array($api['cmd'], $role_data['sys'], true)) or (!empty($keysysmodule) and isset($role_data[NV_LANG_DATA][$keysysmodule]) and in_array($api['cmd'], $role_data[NV_LANG_DATA][$keysysmodule], true)));
                    $api_checked && ++$total_api_enabled;
                    $api_checked && ++$array_api_trees[$keysysmodule]['total_api'];
                    !$api_checked && $array_api_contents[$keysysmodule]['checkall'] = false;

                    $array_api_contents[$keysysmodule]['apis'][] = [
                        'cmd' => $api['cmd'],
                        'name' => $api['title'],
                        'checked' => $api_checked
                    ];
                }
            }

            $_cat_is_active = false;
        }
    }

    return [$array_api_trees, $array_api_contents, $total_api_enabled];
}

// Lấy nội dung HTML của cây APIs
function apicheck($role_object, $array_post)
{
    global $global_config, $lang_module, $lang_global, $module_file;

    list($array_api_trees, $array_api_contents, $total_api_enabled) = apiTrees($role_object, $array_post);

    $xtpl = new XTemplate('roles.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TOTAL_API_ENABLED', $total_api_enabled);
    $xtpl->assign('TOTAL_API_CHECKED', $total_api_enabled ? ' checked' : '');

    // Xuất các danh mục API
    foreach ($array_api_trees as $api_tree) {
        $api_tree['api_checked'] = $api_tree['total_api'] ? ' checked' : '';
        $api_tree['total'] = !empty($array_api_contents[$api_tree['key']]['apis']) ? count($array_api_contents[$api_tree['key']]['apis']) : 0;
        $api_tree['expanded'] = $api_tree['active'] ? 'true' : 'false';
        $api_tree['href'] = !empty($array_api_contents[$api_tree['key']]) ? 'api-child-' . $api_tree['key'] : 'empty-content';
        $xtpl->assign('API_TREE', $api_tree);

        foreach ($api_tree['subs'] as $sub) {
            $sub['api_checked'] = $sub['total_api'] ? ' checked' : '';
            $sub['total'] = !empty($array_api_contents[$sub['key']]['apis']) ? count($array_api_contents[$sub['key']]['apis']) : 0;
            $sub['expanded'] = $sub['active'] ? 'true' : 'false';
            $sub['href'] = !empty($array_api_contents[$sub['key']]) ? 'api-child-' . $sub['key'] : 'empty-content';
            $xtpl->assign('SUB', $sub);

            if ($sub['active']) {
                $xtpl->parse('apicheck.api_tree.sub.active');
            }
            if (!empty($sub['total'])) {
                $xtpl->parse('apicheck.api_tree.sub.total_api');
            }

            $xtpl->parse('apicheck.api_tree.sub');
        }

        if ($api_tree['active']) {
            $xtpl->parse('apicheck.api_tree.active');
        }
        if (!empty($api_tree['total'])) {
            $xtpl->parse('apicheck.api_tree.total_api');
        }

        $xtpl->parse('apicheck.api_tree');
    }

    // Xuất danh sách các API
    foreach ($array_api_contents as $api_content) {
        $api_content['input_key'] = str_replace('-', '_', $api_content['key']);
        $api_content['id'] = 'api-child-' . $api_content['key'];
        $api_content['checkall'] = $api_content['checkall'] ? ' checked="checked"' : '';
        $xtpl->assign('API_CONTENT', $api_content);

        foreach ($api_content['apis'] as $api) {
            $api['checked'] = !empty($api['checked']) ? ' checked="checked"' : '';
            $xtpl->assign('API', $api);
            $xtpl->parse('apicheck.api_content.api');
        }

        if (!empty($api_content['active'])) {
            $xtpl->parse('apicheck.api_content.active');
        }

        $xtpl->parse('apicheck.api_content');
    }
    $xtpl->parse('apicheck');

    return $xtpl->text('apicheck');
}

// Thay đổi trạng thái của role
if ($nv_Request->isset_request('changeStatus', 'post')) {
    $id = $nv_Request->get_int('changeStatus', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_select']
        ]);
    }

    $array_post = getRoleDetails($id, true);
    if (empty($array_post)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_select']
        ]);
    }

    $status = !empty($array_post['status']) ? 0 : 1;
    $db->query('UPDATE ' . $db_config['prefix'] . '_api_role SET status=' . $status . ' WHERE role_id = ' . $id);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Xóa role
if ($nv_Request->isset_request('roledel', 'post')) {
    $id = $nv_Request->get_int('roledel', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error'
        ]);
    }

    $array_post = getRoleDetails($id, false);
    if (empty($array_post)) {
        nv_jsonOutput([
            'status' => 'error'
        ]);
    }

    $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role WHERE role_id=' . $id);
    $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_credential WHERE role_id=' . $id);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete API-role', $id . ': ' . $array_post['role_title'], $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$xtpl = new XTemplate('roles.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('PAGE_URL', $page_url);
$xtpl->assign('ADD_API_ROLE_URL', $page_url . '&amp;action=role');

$action = $nv_Request->get_title('action', 'get', '');

// Form tạo/sửa API-role
if ($action == 'role') {
    $id = $nv_Request->get_int('id', 'get', 0);

    // Lấy dữ liệu role để sửa
    if (!empty($id)) {
        $array_post = getRoleDetails($id, true);
        // Chuyển hướng về trang chủ nếu không có dữ liệu
        if (empty($array_post)) {
            nv_redirect_location($page_url);
        }

        !isset($array_post['role_data']['sys']) && $array_post['role_data']['sys'] = [];
        !isset($array_post['role_data'][NV_LANG_DATA]) && $array_post['role_data'][NV_LANG_DATA] = [];
        $isAdd = false;
        $page_url .= '&id=' . $id;
    } else {
        $array_post = [
            'role_type' => 'private',
            'role_object' => 'admin',
            'role_title' => '',
            'role_description' => '',
            'flood_rules' => []
        ];
        $array_post['role_data'] = [
            'sys' => [],
            NV_LANG_DATA => []
        ];
        $isAdd = true;
    }

    if ($nv_Request->isset_request('getapitree', 'post')) {
        $role_object = $nv_Request->get_title('getapitree', 'post', 'admin');
        nv_htmlOutput(apicheck($role_object, $array_post));
    }

    if ($nv_Request->isset_request('save', 'post')) {
        $data = [
            'role_title' => nv_substr($nv_Request->get_title('role_title', 'post', ''), 0, 250),
            'role_description' => nv_substr($nv_Request->get_textarea('role_description', '', ''), 0, 250),
            'role_type' => $nv_Request->get_title('role_type', 'post', ''),
            'role_object' => $nv_Request->get_title('role_object', 'post', ''),
            'flood_rules_interval' => $nv_Request->get_typed_array('flood_rules_interval', 'post', 'int', 0),
            'flood_rules_limit' => $nv_Request->get_typed_array('flood_rules_limit', 'post', 'int', 0),
            'role_data' => $array_post['role_data']
        ];
        $data['role_type'] != 'private' && $data['role_type'] = 'public';
        $data['role_object'] != 'admin' && $data['role_object'] = 'user';

        if (empty($data['role_title'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $lang_module['api_roles_error_title']
            ]);
        }

        $md5title = md5($data['role_title']);
        $exists = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role WHERE role_id !=' . $id . ' AND role_md5title = ' . $db->quote($md5title))->fetchColumn();
        if ($exists) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $lang_module['api_roles_error_exists']
            ]);
        }

        $data['flood_rules'] = [];
        if (!empty($data['flood_rules_interval'])) {
            foreach ($data['flood_rules_interval'] as $k => $interval) {
                if (!empty($interval) and !empty($data['flood_rules_limit'][$k])) {
                    $data['flood_rules'][(int) $interval * 60] = (int) $data['flood_rules_limit'][$k];
                }
            }
        }
        $data['flood_rules'] = json_encode($data['flood_rules']);

        $data['role_data']['sys'] = [];
        $data['role_data'][NV_LANG_DATA] = [];
        // Lấy các API được phép
        $actions = $data['role_object'] == 'admin' ? $array_api_actions : $user_array_api_actions;
        $keys = $data['role_object'] == 'admin' ? $array_api_keys : $user_array_api_keys;
        foreach ($actions as $keysysmodule => $sysmodule_data) {
            $input_key = str_replace('-', '_', $keysysmodule);
            // Các API không có CAT
            $api_nocat = $nv_Request->get_typed_array('api_' . $input_key, 'post', 'string', []);
            // Các API theo CAT
            $api_cat = [];
            foreach ($sysmodule_data as $catkey => $catapis) {
                $api_cat = array_merge_recursive($api_cat, $nv_Request->get_typed_array('api_' . $input_key . '_' . $catkey, 'post', 'string', []));
            }
            $api_submits = array_filter(array_unique(array_merge_recursive($api_nocat, $api_cat)));
            $api_submits = array_intersect($api_submits, $keys[$keysysmodule]);
            if (empty($keysysmodule)) {
                $data['role_data']['sys'] = $api_submits;
            } elseif (!empty($api_submits)) {
                $data['role_data'][NV_LANG_DATA][$keysysmodule] = $api_submits;
            }
        }

        if (empty($data['role_data']['sys']) and empty($data['role_data'][NV_LANG_DATA])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $lang_module['api_roles_error_role']
            ]);
        }
        $data['role_data'] = json_encode($data['role_data']);

        if ($isAdd) {
            $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_api_role (
                role_md5title, role_type, role_object, role_title, role_description, role_data, flood_rules, addtime
            ) VALUES (
                :role_md5title, :role_type, :role_object, :role_title, :role_description, :role_data, :flood_rules, ' . NV_CURRENTTIME . '
            )');
            $sth->bindParam(':role_md5title', $md5title, PDO::PARAM_STR);
            $sth->bindParam(':role_type', $data['role_type'], PDO::PARAM_STR);
            $sth->bindParam(':role_object', $data['role_object'], PDO::PARAM_STR);
            $sth->bindParam(':role_title', $data['role_title'], PDO::PARAM_STR);
            $sth->bindParam(':role_description', $data['role_description'], PDO::PARAM_STR);
            $sth->bindParam(':role_data', $data['role_data'], PDO::PARAM_STR);
            $sth->bindParam(':flood_rules', $data['flood_rules'], PDO::PARAM_STR);
            $sth->execute();
            $id = $db->lastInsertId();
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Add API-role', $id . ': ' . $data['role_title'], $admin_info['userid']);
        } else {
            $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_api_role SET
                role_md5title = :role_md5title,
                role_type = :role_type,
                role_object = :role_object,
                role_title = :role_title,
                role_description = :role_description,
                role_data = :role_data,
                flood_rules = :flood_rules,
                edittime = ' . NV_CURRENTTIME . '
                WHERE role_id=' . $id);
            $sth->bindParam(':role_md5title', $md5title, PDO::PARAM_STR);
            $sth->bindParam(':role_type', $data['role_type'], PDO::PARAM_STR);
            $sth->bindParam(':role_object', $data['role_object'], PDO::PARAM_STR);
            $sth->bindParam(':role_title', $data['role_title'], PDO::PARAM_STR);
            $sth->bindParam(':role_description', $data['role_description'], PDO::PARAM_STR);
            $sth->bindParam(':role_data', $data['role_data'], PDO::PARAM_STR);
            $sth->bindParam(':flood_rules', $data['flood_rules'], PDO::PARAM_STR);
            $sth->execute();
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit API-role', $id . ': ' . $array_post['role_title'], $admin_info['userid']);
        }

        nv_jsonOutput([
            'status' => 'OK',
            'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op
        ]);
    }

    $array_post['role_type_private_checked'] = $array_post['role_type'] == 'private' ? ' checked="checked"' : '';
    $array_post['role_type_public_checked'] = $array_post['role_type'] == 'public' ? ' checked="checked"' : '';
    $array_post['role_object_admin_checked'] = $array_post['role_object'] == 'admin' ? ' checked="checked"' : '';
    $array_post['role_object_user_checked'] = $array_post['role_object'] == 'user' ? ' checked="checked"' : '';

    $page_title = $isAdd ? $lang_module['add_role'] : $lang_module['edit_role'];
    $page_url .= '&action=role';

    $xtpl->assign('FORM_ACTION', $page_url);
    $xtpl->assign('DATA', $array_post);
    $xtpl->assign('APICHECK', apicheck($array_post['role_object'], $array_post));

    empty($array_post['flood_rules']) && $array_post['flood_rules'] = ['' => ''];
    foreach ($array_post['flood_rules'] as $interval => $limit) {
        $xtpl->assign('RULE', [
            'interval' => round($interval / 60),
            'limit' => $limit
        ]);
        $xtpl->parse('role.flood_rule');
    }

    $xtpl->parse('role');
    $contents = $xtpl->text('role');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$base_url = $page_url;
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

$type = $nv_Request->get_title('type', 'get', '');
(!empty($type) and !in_array($type, ['private', 'public'], true)) && $type = '';
!empty($type) && $base_url .= '&type=' . $type;

$object = $nv_Request->get_title('object', 'get', '');
(!empty($object) and !in_array($object, ['admin', 'user'], true)) && $object = '';
!empty($object) && $base_url .= '&object=' . $object;

list($all_pages, $rolelist) = getRoleList($type, $object, $page, $per_page);
$generate_page = nv_generate_page($base_url, $all_pages, $per_page, $page);

$page_title = $lang_module['role_management'];

if (empty($global_config['remote_api_access'])) {
    $xtpl->assign('REMOTE_API_OFF', sprintf($lang_module['api_remote_off'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=config'));
    $xtpl->parse('main.remote_api_off');
}

$types = ['private', 'public'];
foreach ($types as $key) {
    $xtpl->assign('TYPE', [
        'key' => $key,
        'sel' => $key == $type ? ' selected="selected"' : '',
        'name' => $lang_module['api_role_type_' . $key]
    ]);
    $xtpl->parse('main.role_type');
}

$objects = ['admin', 'user'];
foreach ($objects as $key) {
    $xtpl->assign('OBJECT', [
        'key' => $key,
        'sel' => $key == $object ? ' selected="selected"' : '',
        'name' => $lang_module['api_role_object_' . $key]
    ]);
    $xtpl->parse('main.role_object');
}

if (empty($rolelist)) {
    $xtpl->parse('main.role_list_empty');
} else {
    foreach ($rolelist as $role) {
        $xtpl->assign('ROLE', [
            'title' => $role['role_title'],
            'apitotal' => $role['apitotal'],
            'type' => $lang_module['api_role_type_' . $role['role_type']],
            'object' => $lang_module['api_role_object_' . $role['role_object']],
            'addtime' => nv_date('d/m/Y H:i', $role['addtime']),
            'edittime' => $role['edittime'] ? nv_date('d/m/Y H:i', $role['edittime']) : '',
            'id' => $role['role_id']
        ]);

        // List API hệ thống
        if (!empty($role['apis'][''])) {
            foreach ($role['apis'][''] as $cat_key => $cat_data) {
                $xtpl->assign('CAT_DATA', $cat_data);

                foreach ($cat_data['apis'] as $api_data) {
                    $xtpl->assign('API_DATA', $api_data);
                    $xtpl->parse('main.role_list.loop.catsys.loop');
                }

                $xtpl->parse('main.role_list.loop.catsys');
            }
        }

        // List API theo ngôn ngữ
        if (!empty($role['apis'][NV_LANG_DATA])) {
            foreach ($role['apis'][NV_LANG_DATA] as $mod_title => $mod_data) {
                $xtpl->assign('MOD_TITLE', $site_mods[$mod_title]['custom_title']);

                foreach ($mod_data as $cat_data) {
                    $xtpl->assign('CAT_DATA', $cat_data);

                    foreach ($cat_data['apis'] as $api_data) {
                        $xtpl->assign('API_DATA', $api_data);
                        $xtpl->parse('main.role_list.loop.apimod.mod.loop');
                    }

                    if (!empty($cat_data['title'])) {
                        $xtpl->parse('main.role_list.loop.apimod.mod.title');
                    }

                    $xtpl->parse('main.role_list.loop.apimod.mod');
                }

                $xtpl->parse('main.role_list.loop.apimod');
            }
        }

        $sts = [$lang_module['inactive'], $lang_module['active']];
        foreach ($sts as $k => $v) {
            $xtpl->assign('STATUS', [
                'val' => $k,
                'sel' => $k == $role['status'] ? ' selected="selected"' : '',
                'title' => $v
            ]);
            $xtpl->parse('main.role_list.loop.status');
        }

        $xtpl->parse('main.role_list.loop');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.role_list.generate_page');
    }

    $xtpl->parse('main.role_list');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
