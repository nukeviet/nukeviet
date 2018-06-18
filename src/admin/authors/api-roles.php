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
$array_api_actions = nv_get_api_actions();
$array_api_cats = $array_api_actions[2];
$array_api_keys = $array_api_actions[1];
$array_api_actions = $array_api_actions[0];

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

// Danh sách
$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_role ORDER BY role_id DESC';
$result = $db->query($sql);

$array = [];
while ($row = $result->fetch()) {
    $row['role_data'] = empty($row['role_data']) ? [] : unserialize($row['role_data']);

    // Xử lý các API theo cat
    $row['apis'] = [];
    $row['apis'][''] = $row['apis'][NV_LANG_DATA] = [];
    $row['apitotal'] = 0;
    if (!empty($row['role_data']['sys'])) {
        foreach ($row['role_data']['sys'] as $api_cmd) {
            $cat = $array_api_cats[''][$api_cmd];
            if (!isset($row['apis'][''][$cat['key']])) {
                $row['apis'][''][$cat['key']] = [
                    'title' => $cat['title'],
                    'apis' => []
                ];
            }
            $row['apis'][''][$cat['key']]['apis'][$api_cmd] = $cat['api_title'];
            $row['apitotal']++;
        }
    }
    if (!empty($row['role_data'][NV_LANG_DATA])) {
        foreach ($row['role_data'][NV_LANG_DATA] as $mod_title => $mod_data) {
            if (isset($array_api_cats[$mod_title])) {
                foreach ($mod_data as $api_cmd) {
                    $cat = $array_api_cats[$mod_title][$api_cmd];
                    if (!isset($row['apis'][NV_LANG_DATA][$mod_title])) {
                        $row['apis'][NV_LANG_DATA][$mod_title] = [];
                    }
                    if (!isset($row['apis'][NV_LANG_DATA][$mod_title][$cat['key']])) {
                        $row['apis'][NV_LANG_DATA][$mod_title][$cat['key']] = [
                            'title' => $cat['title'],
                            'apis' => []
                        ];
                    }
                    $row['apis'][NV_LANG_DATA][$mod_title][$cat['key']]['apis'][$api_cmd] = $cat['api_title'];
                    $row['apitotal']++;
                }
            }
        }
    }

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
        $row['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;role_id=' . $row['role_id'];
        $row['addtime'] = nv_date('H:i d/m/Y', $row['addtime']);
        $row['edittime'] = $row['edittime'] ? nv_date('H:i d/m/Y', $row['edittime']) : '';

        $xtpl->assign('ROW', $row);

        $xtpl->parse('main.data.loop');

        // Xuất modal các API
        if (!empty($row['apis'][''])) {
            foreach ($row['apis'][''] as $cat_key => $cat_data) {
                $xtpl->assign('CAT_NAME', $nv_Lang->getModule('api_of_system') . '<i class="fa fa-angle-double-right fa-fw" aria-hidden="true"></i>' . $cat_data['title']);
                foreach ($cat_data['apis'] as $api_data) {
                    $xtpl->assign('API_NAME', $api_data);
                    $xtpl->parse('main.data.loop_detail.cat.loop');
                }
                $xtpl->parse('main.data.loop_detail.cat');
            }
        }
        if (!empty($row['apis'][NV_LANG_DATA])) {
            foreach ($row['apis'][NV_LANG_DATA] as $mod_title => $mod_data) {
                foreach ($mod_data as $cat_key => $cat_data) {
                    $xtpl->assign('CAT_NAME', empty($cat_data['title']) ? $site_mods[$mod_title]['custom_title'] : ($site_mods[$mod_title]['custom_title'] . '<i class="fa fa-angle-double-right fa-fw" aria-hidden="true"></i>' . $cat_data['title']));
                    foreach ($cat_data['apis'] as $api_data) {
                        $xtpl->assign('API_NAME', $api_data);
                        $xtpl->parse('main.data.loop_detail.cat.loop');
                    }
                    $xtpl->parse('main.data.loop_detail.cat');
                }
            }
        }

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
        $array_post['role_data']['sys'] = [];
    }
    if (!isset($array_post['role_data'][NV_LANG_DATA])) {
        $array_post['role_data'][NV_LANG_DATA] = [];
    }
} else {
    $table_caption = $nv_Lang->getModule('api_roles_add');
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    $array_post = array(
        'role_title' => '',
        'role_description' => ''
    );
    $array_post['role_data'] = [];
    $array_post['role_data']['sys'] = [];
    $array_post['role_data'][NV_LANG_DATA] = [];
}

$is_submit_form = false;
if ($nv_Request->isset_request('submit', 'post')) {
    $is_submit_form = true;
    $current_cat = $nv_Request->get_title('current_cat', 'post', '');

    $array_post['role_title'] = nv_substr($nv_Request->get_title('role_title', 'post', ''), 0, 250);
    $array_post['role_description'] = nv_substr($nv_Request->get_textarea('role_description', '', ''), 0, 250);
    $array_post['role_data'] = [];
    // Các API của hệ thống
    $array_post['role_data']['sys'] = [];
    // Các API của module theo ngôn ngữ
    $array_post['role_data'][NV_LANG_DATA] = [];

    // Lấy các API được phép
    foreach ($array_api_actions as $keysysmodule => $sysmodule_data) {
        // Các API không có CAT
        $api_nocat = $nv_Request->get_typed_array('api_' . $keysysmodule, 'post', 'string', []);
        $api_cat = [];
        foreach ($sysmodule_data as $catkey => $catapis) {
            $api_cat = array_merge_recursive($api_cat, $nv_Request->get_typed_array('api_' . $keysysmodule . '_' . $catkey, 'post', 'string', []));
        }
        $api_submits = array_filter(array_unique(array_merge_recursive($api_nocat, $api_cat)));
        $api_submits = array_intersect($api_submits, $array_api_keys[$keysysmodule]);
        if (empty($keysysmodule)) {
            $array_post['role_data']['sys'] = $api_submits;
        } elseif (!empty($api_submits)) {
            $array_post['role_data'][NV_LANG_DATA][$keysysmodule] = $api_submits;
        }
    }

    $sql = 'SELECT role_id FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_role WHERE role_title=:role_title' . ($role_id ? (' AND role_id!=' . $role_id) : '');
    $sth = $db->prepare($sql);
    $sth->bindParam(':role_title', $array_post['role_title'], PDO::PARAM_STR);
    $sth->execute();
    $is_exists = $sth->fetchColumn();

    if (empty($array_post['role_title'])) {
        $error = $nv_Lang->getModule('api_roles_error_title');
    } elseif (empty($array_post['role_data']['sys']) and empty($array_post['role_data'][NV_LANG_DATA])) {
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
            $array_insert = [];
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

$cat_order = 0;
$total_api_enabled = 0;

foreach ($array_api_actions as $keysysmodule => $sysmodule_data) {
    $cat1_is_active = ($keysysmodule == $current_cat and !empty($current_cat)) ? true : false;
    $cat1_total_api = 0;

    // Lev1: Hệ thống hoặc các module
    $xtpl->assign('CAT1_KEY', $keysysmodule);
    $xtpl->assign('CAT1_NAME', $keysysmodule ? $site_mods[$keysysmodule]['custom_title'] : $nv_Lang->getModule('api_of_system'));

    // Lev 2: Các cat của hệ thống hoặc các module, trong HTML đối xử ngang nhau
    foreach ($sysmodule_data as $catkey => $catapis) {
        $cat_order++;

        if (!empty($catkey)) {
            $cat2_key = $keysysmodule . '_' . $catkey;
            $cat2_is_active = ($cat2_key == $current_cat or (!$cat1_is_active and $cat_order == 1 and empty($current_cat))) ? true : false;
            $cat2_total_api = 0;

            $xtpl->assign('CAT2_KEY', $cat2_key);
            $xtpl->assign('CAT2_NAME', '&nbsp; &nbsp; ' . $catapis['title']);
            $xtpl->assign('CAT2_ACTIVE', $cat2_is_active ? ' class="active"' : '');

            // Các API của lev1 (Các api có cat của lev2 trống)
            $xtpl->assign('CTN_KEY', $cat2_key);
            $xtpl->assign('CTN_DISPLAY', $cat2_is_active ? ' style="display: block;"' : '');


            foreach ($catapis['apis'] as $api) {
                $api_checked = ((empty($keysysmodule) and in_array($api['cmd'], $array_post['role_data']['sys'])) or (!empty($keysysmodule) and isset($array_post['role_data'][NV_LANG_DATA][$keysysmodule]) and in_array($api['cmd'], $array_post['role_data'][NV_LANG_DATA][$keysysmodule])));
                $total_api_enabled += $api_checked ? 1 : 0;
                $cat2_total_api += $api_checked ? 1 : 0;

                $xtpl->assign('API_CMD', $api['cmd']);
                $xtpl->assign('API_NAME', $api['title']);
                $xtpl->assign('API_CHECKED', $api_checked ? ' checked="checked"' : '');
                $xtpl->parse('main.catcontents.loop');
            }

            if ($cat2_total_api) {
                $xtpl->assign('CAT_API_ENABLED', $cat2_total_api);
                $xtpl->parse('main.catlev1.catlev2.cat_api_enabled');
            }

            $xtpl->parse('main.catcontents');
            $xtpl->parse('main.catlev1.catlev2');
        } else {
            // Các API của lev1 (Các api có cat của lev2 trống)
            $xtpl->assign('CTN_KEY', $keysysmodule);
            $xtpl->assign('CTN_DISPLAY', $cat1_is_active ? ' style="display: block;"' : '');

            foreach ($catapis['apis'] as $api) {
                $api_checked = ((empty($keysysmodule) and in_array($api['cmd'], $array_post['role_data']['sys'])) or (!empty($keysysmodule) and isset($array_post['role_data'][NV_LANG_DATA][$keysysmodule]) and in_array($api['cmd'], $array_post['role_data'][NV_LANG_DATA][$keysysmodule])));
                $total_api_enabled += $api_checked ? 1 : 0;
                $cat1_total_api += $api_checked ? 1 : 0;

                $xtpl->assign('API_CMD', $api['cmd']);
                $xtpl->assign('API_NAME', $api['title']);
                $xtpl->assign('API_CHECKED', $api_checked ? ' checked="checked"' : '');
                $xtpl->parse('main.catcontents.loop');
            }

            $xtpl->parse('main.catcontents');
        }
    }

    $xtpl->assign('CAT1_ACTIVE', $cat1_is_active ? ' class="active"' : '');

    if ($cat1_total_api) {
        $xtpl->assign('CAT_API_ENABLED', $cat1_total_api);
        $xtpl->parse('main.catlev1.cat_api_enabled');
    }

    $xtpl->parse('main.catlev1');
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
if (!$is_submit_form) {
    $xtpl->parse('main.note_lang');
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
