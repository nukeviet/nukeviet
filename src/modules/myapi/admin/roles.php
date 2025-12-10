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

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

// Sắp xếp dạng cây các API
function apiTrees($role_object, $array_post, $lang)
{
    global $site_mods, $nv_Lang, $array_api_actions, $user_array_api_actions;

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
            'name' => $keysysmodule ? $site_mods[$keysysmodule]['custom_title'] : $nv_Lang->getModule('api_of_system'),
            'subs' => []
        ];

        // Lev 2: Các cat của hệ thống hoặc các module, trong HTML đối xử ngang nhau
        $role_data = $role_object == $array_post['role_object'] ? $array_post['role_data'] : ['sys' => [], $lang => []];
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
                    $api_checked = ((empty($keysysmodule) and in_array($api['cmd'], $role_data['sys'], true)) or (!empty($keysysmodule) and isset($role_data[$lang][$keysysmodule]) and in_array($api['cmd'], $role_data[$lang][$keysysmodule], true)));
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
                    $api_checked = ((empty($keysysmodule) and in_array($api['cmd'], $role_data['sys'], true)) or (!empty($keysysmodule) and isset($role_data[$lang][$keysysmodule]) and in_array($api['cmd'], $role_data[$lang][$keysysmodule], true)));
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
function apicheck($role_object, $array_post, $lang)
{
    global $global_config, $module_file, $nv_Lang;

    [$array_api_trees, $array_api_contents, $total_api_enabled] = apiTrees($role_object, $array_post, $lang);

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('roles-contents.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('TOTAL_API_ENABLED', $total_api_enabled);
    $tpl->assign('TOTAL_API_CHECKED', $total_api_enabled ? ' checked' : '');
    
    // Xuất các danh mục API
    foreach ($array_api_trees as $k => $api_tree) {
        $api_tree['api_checked'] = $api_tree['total_api'] ? ' checked' : '';
        $api_tree['total'] = !empty($array_api_contents[$api_tree['key']]['apis']) ? count($array_api_contents[$api_tree['key']]['apis']) : 0;
        $api_tree['expanded'] = $api_tree['active'] ? 'true' : 'false';
        $api_tree['href'] = !empty($array_api_contents[$api_tree['key']]) ? 'api-child-' . $api_tree['key'] : 'empty-content';

        foreach ($api_tree['subs'] as $k1 => $sub) {
            $sub['api_checked'] = $sub['total_api'] ? ' checked' : '';
            $sub['total'] = !empty($array_api_contents[$sub['key']]['apis']) ? count($array_api_contents[$sub['key']]['apis']) : 0;
            $sub['expanded'] = $sub['active'] ? 'true' : 'false';
            $sub['href'] = !empty($array_api_contents[$sub['key']]) ? 'api-child-' . $sub['key'] : 'empty-content';
            $api_tree['subs'][$k1] = $sub;
        }
        $array_api_trees[$k] = $api_tree;
    }
    $tpl->assign('API_TREES', $array_api_trees);

    // Xuất danh sách các API
    foreach ($array_api_contents as $k => $api_content) {
        $api_content['input_key'] = str_replace('-', '_', $api_content['key']);
        $api_content['id'] = 'api-child-' . $api_content['key'];
        $api_content['checkall'] = $api_content['checkall'] ? ' checked="checked"' : '';

        foreach ($api_content['apis'] as $k1 => $api) {
            $api['checked'] = !empty($api['checked']) ? ' checked="checked"' : '';
            $api_content['apis'][$k1] = $api;
        }
        $array_api_contents[$k] = $api_content;
    }
    $tpl->assign('API_CONTENTS', $array_api_contents);
    return $tpl->fetch('roles-contents.tpl');
}

// Thay đổi trạng thái của role
if ($nv_Request->isset_request('changeStatus', 'post')) {
    $checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
    if ($checkss != $nv_Request->get_title('checkss', 'post', '')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_code_11')
        ]);
    }
    $id = $nv_Request->get_int('changeStatus', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_select')
        ]);
    }

    $array_post = getRoleDetails($id, true);
    if (empty($array_post)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_select')
        ]);
    }

    $status = !empty($array_post['status']) ? 0 : 1;
    $db->query('UPDATE ' . $db_config['prefix'] . '_api_role SET status=' . $status . ' WHERE role_id = ' . $id);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success')
    ]);
}

// Xóa role
if ($nv_Request->isset_request('roledel', 'post')) {
    $checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
    if ($checkss != $nv_Request->get_title('checkss', 'post', '')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_code_11')
        ]);
    }
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

$action = $nv_Request->get_title('action', 'get', '');

// Form tạo/sửa API-role
if ($action == 'role') {
    $id = $nv_Request->get_int('id', 'get', 0);
    $lg = $nv_Request->get_title('lg', 'get', NV_LANG_DATA);
    $is_getapitree = $nv_Request->isset_request('getapitree', 'post');
    if (!in_array($lg, $global_config['setup_langs'], true)) {
        $lg = NV_LANG_DATA;
    }
    // Lấy dữ liệu role để sửa
    if (!empty($id)) {
        $array_post = getRoleDetails($id, true);
        // Chuyển hướng về trang chủ nếu không có dữ liệu
        if (empty($array_post)) {
            if ($is_getapitree) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getGlobal('error_code_11')
                ]);
            }
            nv_redirect_location($page_url);
        }

        !isset($array_post['role_data']['sys']) && $array_post['role_data']['sys'] = [];
        !isset($array_post['role_data'][$lg]) && $array_post['role_data'][$lg] = [];
        $isAdd = false;
        $page_url .= '&amp;id=' . $id;
    } elseif ($is_getapitree) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_code_11')
        ]);
    } else {
        $array_post = [
            'role_type' => 'private',
            'role_object' => 'admin',
            'role_title' => '',
            'role_description' => '',
            'log_period' => 0,
            'flood_rules' => []
        ];
        $array_post['role_data'] = [
            'sys' => [],
            $lg => []
        ];
        $isAdd = true;
    }

    if ($is_getapitree) {
        $role_object = $nv_Request->get_title('getapitree', 'post', 'admin');
        $html = apicheck($role_object, $array_post, $lg);
        nv_jsonOutput([
            'status' => 'OK',
            'html' => $html
        ]);
    }

    if ($nv_Request->isset_request('save', 'post')) {
        $checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
        if ($checkss != $nv_Request->get_title('checkss', 'post', '')) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_code_11')
            ]);
        }
        $save = $nv_Request->get_title('save', 'post', '');
        $data = [
            'role_title' => nv_substr($nv_Request->get_title('role_title', 'post', ''), 0, 250),
            'role_description' => nv_substr($nv_Request->get_textarea('role_description', '', ''), 0, 250),
            'role_type' => $nv_Request->get_title('role_type', 'post', ''),
            'role_object' => $nv_Request->get_title('role_object', 'post', ''),
            'log_period' => $nv_Request->get_absint('log_period', 'post', 0),
            'flood_rules_interval' => $nv_Request->get_typed_array('flood_rules_interval', 'post', 'int', 0),
            'flood_rules_limit' => $nv_Request->get_typed_array('flood_rules_limit', 'post', 'int', 0),
            'role_data' => $array_post['role_data']
        ];
        $data['role_type'] != 'private' && $data['role_type'] = 'public';
        $data['role_object'] != 'admin' && $data['role_object'] = 'user';

        if (empty($data['role_title'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_roles_error_title')
            ]);
        }

        $md5title = md5($data['role_title']);
        $exists = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role WHERE role_id !=' . $id . ' AND role_md5title = ' . $db->quote($md5title))->fetchColumn();
        if ($exists) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_roles_error_exists')
            ]);
        }

        $data['flood_rules'] = [];
        if (!empty($data['flood_rules_interval'])) {
            foreach ($data['flood_rules_interval'] as $k => $interval) {
                $interval = (int) $interval;
                $limit = (int) $data['flood_rules_limit'][$k];
                if (!empty($interval) and !empty($limit)) {
                    if (!empty($data['log_period']) and $interval > $data['log_period'] * 60) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('flood_interval_error')
                        ]);
                    }

                    $data['flood_rules'][$interval * 60] = $limit;
                }
            }
        }
        $data['flood_rules'] = json_encode($data['flood_rules']);
        $data['log_period'] *= 3600;

        $data['role_data']['sys'] = [];
        $data['role_data'][$lg] = [];
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
                $data['role_data'][$lg][$keysysmodule] = $api_submits;
            }
        }

        if (empty($data['role_data']['sys']) and empty($data['role_data'][$lg])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_roles_error_role')
            ]);
        }

        if ($save == '2' and count($global_config['setup_langs']) > 1) {
            foreach ($global_config['setup_langs'] as $_lg) {
                if ($_lg != $lg) {
                    $data['role_data'][$_lg] = $data['role_data'][$lg];
                }
            }
        }

        $data['role_data'] = json_encode($data['role_data']);

        if ($isAdd) {
            $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_api_role (
                role_md5title, role_type, role_object, role_title, role_description, role_data, log_period, flood_rules, addtime
            ) VALUES (
                :role_md5title, :role_type, :role_object, :role_title, :role_description, :role_data, ' . $data['log_period'] . ', :flood_rules, ' . NV_CURRENTTIME . '
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
                log_period = ' . $data['log_period'] . ',
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

        if (in_array($save, $global_config['setup_langs'], true)) {
            $redirect = str_replace('&amp;', '&', $page_url) . '&amp;action=role&amp;lg=' . $save;
        } else {
            $redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
        }
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success'),
            'redirect' => $redirect
        ]);
    }

    $array_post['role_type_private_checked'] = $array_post['role_type'] == 'private' ? ' checked="checked"' : '';
    $array_post['role_type_public_checked'] = $array_post['role_type'] == 'public' ? ' checked="checked"' : '';
    $array_post['role_object_admin_checked'] = $array_post['role_object'] == 'admin' ? ' checked="checked"' : '';
    $array_post['role_object_user_checked'] = $array_post['role_object'] == 'user' ? ' checked="checked"' : '';
    $array_post['log_period'] = !empty($array_post['log_period']) ? round($array_post['log_period'] / 3600) : '';

    $page_title = $isAdd ? $nv_Lang->getModule('add_role') : $nv_Lang->getModule('edit_role');
    $page_url .= '&amp;action=role&amp;lg=' . $lg;
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('roles-add.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('CHECKSS', md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']));
    $tpl->assign('DATA', $array_post);
    $tpl->assign('APICHECK', apicheck($array_post['role_object'], $array_post, $lg));
    $tpl->assign('FORM_ACTION', $page_url);

    $saveopts = [
        '1' => $nv_Lang->getModule('saveopt1', $language_array[$lg]['name']),
        '2' => $nv_Lang->getModule('saveopt2')
    ];
    if (count($global_config['setup_langs']) > 1) {
        foreach ($global_config['setup_langs'] as $_lg) {
            if ($_lg != $lg) {
                $saveopts[$_lg] = $nv_Lang->getModule('saveopt3', $language_array[$lg]['name'], $language_array[$_lg]['name']);
            }
        }
    }
    $tpl->assign('SAVEOPTS', $saveopts);

    $contents = $tpl->fetch('roles-add.tpl');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$base_url = $page_url;
$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 30;

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('roles-list.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('PAGE_URL', $page_url);
$tpl->assign('ADD_API_ROLE_URL', $page_url . '&amp;action=role');
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('LANGUAGE_ARRAY', $language_array);
$tpl->assign('CHECKSS', md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']));

$type = $nv_Request->get_title('type', 'get', '');
(!empty($type) and !in_array($type, ['private', 'public'], true)) && $type = '';
!empty($type) && $base_url .= '&type=' . $type;

$object = $nv_Request->get_title('object', 'get', '');
(!empty($object) and !in_array($object, ['admin', 'user'], true)) && $object = '';
!empty($object) && $base_url .= '&object=' . $object;

[$all_pages, $rolelist] = getRoleList($type, $object, $page, $per_page);
$generate_page = nv_generate_page($base_url, $all_pages, $per_page, $page);

$page_title = $nv_Lang->getModule('role_management');

if (empty($global_config['remote_api_access'])) {
    $tpl->assign('REMOTE_API_OFF', $nv_Lang->getModule('api_remote_off', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=config'));
}

$types = ['private', 'public'];
$tpl->assign('TYPES', $types);
$tpl->assign('TYPE_API', $type);
$objects = ['admin', 'user'];
$tpl->assign('OBJECTS', $objects);
$tpl->assign('OBJECT_API', $object);

$role_list = [];
$tpl->assign('ROLE_LIST', $rolelist);
$tpl->assign('SITE_MOD', $site_mods);
$tpl->assign('GENERATE_PAGE', $generate_page);
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');

$contents = $tpl->fetch('roles-list.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
