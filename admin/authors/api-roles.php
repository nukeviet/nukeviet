<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:24
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('api_roles');

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

// Danh sách
$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_role ORDER BY role_id DESC';
$result = $db->query($sql);

$array = array();
while ($row = $result->fetch()) {
    $row['role_data'] = empty($row['role_data']) ? array() : unserialize($row['role_data']);
    $array[$row['role_id']] = $row;
}

// Xóa API Role
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL!!!');
    }

    $role_id = $nv_Request->get_int('role_id', 'post', 0);
    if (!isset($array[$role_id])) {
        nv_htmlOutput('NO');
    }

    $db->query('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_role WHERE role_id=' . $role_id);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete API role', $role_id . ': ' . $array[$role_id]['role_title'], $admin_info['userid']);
    nv_htmlOutput('OK');
}

if (empty($array)) {
    $xtpl->parse('main.empty');
} else {
    foreach ($array as $row) {
        $total_api_enabled = 0;

        $row['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;role_id=' . $row['role_id'];
        $row['addtime'] = nv_date('H:i d/m/Y', $row['addtime']);
        $row['edittime'] = $row['edittime'] ? nv_date('H:i d/m/Y', $row['edittime']) : '';

        $xtpl->assign('ROW', $row);

        foreach ($row['role_data']['sys'] as $root_cat => $cat_actions) {
            if (isset($array_api_actions[$root_cat]) and !empty($cat_actions)) {
                $xtpl->assign('ROOT_ACTION_NAME', $nv_Lang->getModule('api_' . $root_cat));

                foreach ($cat_actions as $action) {
                    if (in_array($action, $array_api_actions[$root_cat])) {
                        $total_api_enabled += 1;
                        $xtpl->assign('ACTION_NAME', $nv_Lang->getModule('api_' . $root_cat . '_' . $action));
                        $xtpl->parse('main.data.loop_detail.cat.loop');
                    }
                }

                $xtpl->parse('main.data.loop_detail.cat');
            }
        }

        $xtpl->assign('TOTAL_API_ENABLED', $total_api_enabled);

        $xtpl->parse('main.data.loop');
        $xtpl->parse('main.data.loop_detail');
    }

    $xtpl->parse('main.data');
}

$current_cat = '';
$error = '';
$role_id = $nv_Request->get_int('role_id', 'get', 0);

if ($role_id) {
    if (!isset($array[$role_id])) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
    $table_caption = $nv_Lang->getModule('api_roles_edit');
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;role_id=' . $role_id;
    $array_post = $array[$role_id];
    if (!isset($array_post['role_data']['sys'])) {
        $array_post['role_data']['sys'] = array();
    }
    if (!isset($array_post['role_data'][NV_LANG_DATA])) {
        $array_post['role_data'][NV_LANG_DATA] = array();
    }
} else {
    $table_caption = $nv_Lang->getModule('api_roles_add');
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    $array_post = array(
        'role_title' => '',
        'role_description' => ''
    );
    $array_post['role_data'] = array();
    $array_post['role_data']['sys'] = array();
    $array_post['role_data'][NV_LANG_DATA] = array();
}

$is_submit_form = false;
if ($nv_Request->isset_request('submit', 'post')) {
    $is_submit_form = true;
    $current_cat = $nv_Request->get_title('current_cat', 'post', '');
    if (!isset($array_api_actions[$current_cat])) {
        $current_cat = '';
    }

    $array_post['role_title'] = nv_substr($nv_Request->get_title('role_title', 'post', ''), 0, 250);
    $array_post['role_description'] = nv_substr($nv_Request->get_textarea('role_description', '', ''), 0, 250);
    $array_post['role_data'] = array();
    $array_post['role_data']['sys'] = array();
    $array_post['role_data'][NV_LANG_DATA] = array();

    foreach ($array_api_actions as $root_cat => $cat_actions) {
        $cat_action_submit = $nv_Request->get_typed_array('api_' . $root_cat, 'post', 'string', array());
        $cat_action_submit = array_intersect($cat_action_submit, $cat_actions);
        if (!empty($cat_action_submit)) {
            $array_post['role_data']['sys'][$root_cat] = $cat_action_submit;
        }
    }

    $sql = 'SELECT role_id FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_role WHERE role_title=:role_title' . ($role_id ? (' AND role_id!=' . $role_id) : '');
    $sth = $db->prepare($sql);
    $sth->bindParam(':role_title', $array_post['role_title'], PDO::PARAM_STR);
    $sth->execute();
    $is_exists = $sth->fetchColumn();

    if (empty($array_post['role_title'])) {
        $error = $nv_Lang->getModule('api_roles_error_title');
    } elseif (empty($array_post['role_data']['sys'])) {
        $error = $nv_Lang->getModule('api_roles_error_role');
    } elseif ($is_exists) {
        $error = $nv_Lang->getModule('api_roles_error_exists');
    } else {
        if ($role_id) {
            $sql = 'UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_api_role SET
                role_title=:role_title,
                role_description=:role_description,
                role_data=:role_data,
                edittime=' . NV_CURRENTTIME . '
            WHERE role_id=' . $role_id;
            $sth = $db->prepare($sql);
            $role_data = serialize($array_post['role_data']);
            $sth->bindParam(':role_title', $array_post['role_title'], PDO::PARAM_STR);
            $sth->bindParam(':role_description', $array_post['role_description'], PDO::PARAM_STR, strlen($array_post['role_description']));
            $sth->bindParam(':role_data', $role_data, PDO::PARAM_STR, strlen($role_data));
            if ($sth->execute()) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit API role', $role_id . ': ' . $array[$role_id]['role_title'], $admin_info['userid']);
            } else {
                $error = 'Unknow error!!!';
            }
        } else {
            $sql = 'INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . '_api_role (
                role_title, role_description, role_data, addtime
            ) VALUES (
                :role_title, :role_description, :role_data, ' . NV_CURRENTTIME . '
            )';
            $array_insert = array();
            $array_insert['role_title'] = $array_post['role_title'];
            $array_insert['role_description'] = $array_post['role_description'];
            $array_insert['role_data'] = serialize($array_post['role_data']);

            $_role_id = $db->insert_id($sql, 'role_id', $array_insert);
            if ($_role_id) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Add API role', $_role_id . ': ' . $array_post['role_title'], $admin_info['userid']);
            } else {
                $error = 'Unknow error!!!';
            }
        }

        if (empty($error)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        }
    }
}

// Thêm/sửa api role
$xtpl->assign('FORM_ACTION', $form_action);
$xtpl->assign('TABLE_CAPTION', $table_caption);

$root_cat_order = 0;
$total_api_enabled = 0;
foreach ($array_api_actions as $root_cat => $cat_actions) {
    $root_cat_order++;
    $iscurrent = (($root_cat_order == 1 and empty($current_cat)) or $current_cat == $root_cat) ? true : false;
    $xtpl->assign('ROOT_ACTION_KEY', $root_cat);
    $xtpl->assign('ROOT_ACTION_NAME', $nv_Lang->getModule('api_' . $root_cat));
    $xtpl->assign('CHILD_DISPLAY', $iscurrent ? ' style="display: block;"' : '');
    $xtpl->assign('CAT_ACTIVE', $iscurrent ? ' class="active"' : '');

    if ($root_cat_order == 1 and empty($current_cat)) {
        $current_cat = $root_cat;
    }

    $cat_api_enabled = !empty($array_post['role_data']['sys'][$root_cat]) ? sizeof($array_post['role_data']['sys'][$root_cat]) : 0;

    foreach ($cat_actions as $action) {
        $api_enabled = (!empty($array_post['role_data']['sys'][$root_cat]) and in_array($action, $array_post['role_data']['sys'][$root_cat])) ? true : false;
        $xtpl->assign('ACTION_KEY', $action);
        $xtpl->assign('ACTION_NAME', $nv_Lang->getModule('api_' . $root_cat . '_' . $action));
        $xtpl->assign('ACTION_CHECKED', $api_enabled ? ' checked="checked"' : '');
        $xtpl->parse('main.api_actions2.loop');

        $total_api_enabled += ($api_enabled ? 1 : 0);
    }

    if ($cat_api_enabled) {
        $xtpl->assign('CAT_API_ENABLED', $cat_api_enabled);
        $xtpl->parse('main.api_actions1.cat_api_enabled');
    }

    $xtpl->parse('main.api_actions1');
    $xtpl->parse('main.api_actions2');
}

$xtpl->assign('DATA', $array_post);
$xtpl->assign('CURRENT_CAT', $current_cat);

if ($total_api_enabled) {
    $xtpl->assign('TOTAL_API_ENABLED', $total_api_enabled);
    $xtpl->parse('main.total_api_enabled');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

if ($role_id) {
    $xtpl->parse('main.icon_edit');
} else {
    $xtpl->parse('main.icon_add');
}

if ($is_submit_form or $role_id) {
    $xtpl->parse('main.is_submit_form');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
