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

if (!function_exists('nv_array_cat_admin')) {
    /**
     * nv_array_cat_admin()
     *
     * @return array
     */
    function nv_array_cat_admin()
    {
        global $db, $module_data;
        $array_cat_admin = [];
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins ORDER BY userid ASC';
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            $array_cat_admin[$row['userid']][$row['catid']] = $row;
        }

        return $array_cat_admin;
    }
}

$is_refresh = false;
$array_cat_admin = nv_array_cat_admin();

$module_admin = array_values(array_filter(array_map('intval', explode(',', (string) ($module_info['admins'] ?? '')))));

// Xoa cac dieu hanh vien khong co quyen tai module
foreach ($array_cat_admin as $userid_i => $value) {
    if (!in_array((int) $userid_i, $module_admin, true)) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE userid = ' . $userid_i);
        $is_refresh = true;
    }
}

foreach ($module_admin as $userid_i) {
    $userid_i = (int) $userid_i;
    if ($userid_i > 0 and !isset($array_cat_admin[$userid_i])) {
        // Them nguoi dieu hanh chung, voi quyen han Quan ly module
        $sql = 'SELECT userid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE userid=' . $userid_i . ' AND catid=0';
        $numrows = $db->query($sql)->fetchColumn();
        if ($numrows == 0) {
            $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_admins (userid, catid, admin, add_content, pub_content, edit_content, del_content, app_content) VALUES ('" . $userid_i . "', '0', '1', '1', '1', '1', '1', '1')");
            $is_refresh = true;
        }
    }
}
if ($is_refresh) {
    $array_cat_admin = nv_array_cat_admin();
}

$view_mode = 'readonly';
if (defined('NV_IS_ADMIN_FULL_MODULE')) {
    $view_mode = 'full';
} elseif (defined('NV_IS_ADMIN_MODULE')) {
    $view_mode = 'module';
}

$table_headers = [];
$users_list = [];
$edit_user = [
    'userid' => 0,
    'username' => '',
    'permission_level' => 0
];
$permission_options = [];
$category_permissions = [];
$readonly_rows = [];
$orderby = 'userid';
$ordertype = 'DESC';
$can_edit_user = false;

if ($view_mode === 'full') {
    $orders = [
        'userid',
        'username',
        'full_name',
        'email'
    ];

    $orderby = $nv_Request->get_string('sortby', 'get', 'userid');
    if (!in_array($orderby, $orders, true)) {
        $orderby = 'userid';
    }

    $ordertype = $nv_Request->get_string('sorttype', 'get', 'DESC');
    if ($ordertype != 'ASC') {
        $ordertype = 'DESC';
    }

    $userid = $nv_Request->get_int('userid', 'post,get', 0);

    $array_permissions_mod = [
        $nv_Lang->getModule('admin_cat'),
        $nv_Lang->getModule('admin_module'),
        $nv_Lang->getModule('admin_full_module')
    ];

    if ($nv_Request->isset_request('save', 'post') and $userid > 0) {
        if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }

        $admin_module = $nv_Request->get_int('admin_module', 'post', 0);
        if ($admin_module == 1 or $admin_module == 2) {
            if (!defined('NV_IS_SPADMIN')) {
                $admin_module = 1;
            }
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE userid = ' . $userid);
            $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_admins (userid, catid, admin, add_content, pub_content, edit_content, del_content, app_content) VALUES ('" . $userid . "', '0', '" . $admin_module . "', '1', '1', '1', '1', '1')");
        } else {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE userid = ' . $userid);
            $array_admin = $nv_Request->get_typed_array('admin_content', 'post', 'int', []);
            $array_add_content = $nv_Request->get_typed_array('add_content', 'post', 'int', []);
            $array_pub_content = $nv_Request->get_typed_array('pub_content', 'post', 'int', []);
            $array_edit_content = $nv_Request->get_typed_array('edit_content', 'post', 'int', []);
            $array_del_content = $nv_Request->get_typed_array('del_content', 'post', 'int', []);
            $array_app_content = $nv_Request->get_typed_array('app_content', 'post', 'int', []);

            $sql = 'SELECT catid, title, subcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
            $result_cat = $db->query($sql);
            while ($row = $result_cat->fetch()) {
                $admin_i = (in_array((int) $row['catid'], $array_admin, true)) ? 1 : 0;
                if ($admin_i) {
                    $add_content_i = $pub_content_i = $edit_content_i = $del_content_i = $app_content_i = 1;
                    if (!empty($row['subcatid'])) {
                        $array_subcatid_i = explode(',', $row['subcatid']);
                        foreach ($array_subcatid_i as $value) {
                            $array_admin[] = $value;
                        }
                    }
                } else {
                    $add_content_i = (in_array((int) $row['catid'], $array_add_content, true)) ? 1 : 0;
                    $pub_content_i = (in_array((int) $row['catid'], $array_pub_content, true)) ? 1 : 0;
                    $edit_content_i = (in_array((int) $row['catid'], $array_edit_content, true)) ? 1 : 0;
                    $del_content_i = (in_array((int) $row['catid'], $array_del_content, true)) ? 1 : 0;
                    $app_content_i = (in_array((int) $row['catid'], $array_app_content, true)) ? 1 : 0;
                    if (!empty($row['subcatid'])) {
                        $array_subcatid_i = explode(',', $row['subcatid']);
                        foreach ($array_subcatid_i as $value) {
                            if (!empty($add_content_i)) {
                                $array_add_content[] = $value;
                            }
                            if (!empty($pub_content_i)) {
                                $array_pub_content[] = $value;
                            }
                            if (!empty($edit_content_i)) {
                                $array_edit_content[] = $value;
                            }
                            if (!empty($del_content_i)) {
                                $array_del_content[] = $value;
                            }
                            if (!empty($app_content_i)) {
                                $array_app_content[] = $value;
                            }
                        }
                    }
                }
                $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_admins (userid, catid, admin, add_content, pub_content, edit_content, del_content, app_content) VALUES ('" . $userid . "', '" . $row['catid'] . "', '" . $admin_i . "', '" . $add_content_i . "', '" . $pub_content_i . "', '" . $edit_content_i . "', '" . $del_content_i . "', '" . $app_content_i . "')");
            }
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_update_admin_permissions', 'userid ' . $userid, $admin_info['userid']);
        $nv_Cache->delMod($module_name);

        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success'),
            'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&userid=' . $userid, true)
        ]);
    }

    if (!empty($module_info['admins'])) {
        $sql = 'SELECT userid, username, first_name, last_name, email FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . implode(',', $module_admin) . ')';
        if (!empty($orderby)) {
            $orderby_sql = $orderby != 'full_name' ? $orderby : ($global_config['name_show'] == 0 ? "concat(first_name,' ',last_name)" : "concat(last_name,' ',first_name)");
            $sql .= ' ORDER BY ' . $orderby_sql . ' ' . $ordertype;
        }

        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            $userid_i = (int) $row['userid'];
            $admin_module = (isset($array_cat_admin[$userid_i][0])) ? (int) ($array_cat_admin[$userid_i][0]['admin']) : 0;
            $admin_module_cat = $array_permissions_mod[$admin_module];
            $is_edit = true;
            if ($admin_module == 2 and !defined('NV_IS_SPADMIN')) {
                $is_edit = false;
            }

            $users_list[$row['userid']] = [
                'userid' => $userid_i,
                'username' => (string) $row['username'],
                'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username']),
                'email' => (string) $row['email'],
                'admin_module_cat' => $admin_module_cat,
                'is_edit' => $is_edit
            ];
        }
    }

    $table_headers = [
        [
            'key' => 'userid',
            'title' => $nv_Lang->getModule('admin_userid')
        ],
        [
            'key' => 'username',
            'title' => $nv_Lang->getModule('admin_username')
        ],
        [
            'key' => 'full_name',
            'title' => $global_config['name_show'] == 0 ? $nv_Lang->getModule('lastname_firstname') : $nv_Lang->getModule('firstname_lastname')
        ],
        [
            'key' => 'email',
            'title' => $nv_Lang->getModule('admin_email')
        ]
    ];

    if ($userid > 0 and $userid != $admin_id and isset($users_list[$userid])) {
        $admin_module = (isset($array_cat_admin[$userid][0])) ? (int) ($array_cat_admin[$userid][0]['admin']) : 0;
        $is_edit = true;
        if ($admin_module == 2 and !defined('NV_IS_SPADMIN')) {
            $is_edit = false;
        }

        if ($is_edit) {
            $permission_levels = $array_permissions_mod;
            if (!defined('NV_IS_SPADMIN')) {
                unset($permission_levels[2]);
            }

            foreach ($permission_levels as $value => $text) {
                $permission_options[] = [
                    'value' => $value,
                    'text' => $text
                ];
            }

            $sql = 'SELECT catid, title, lev FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
            if ($db->query($sql)->fetchColumn() == 0) {
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat');
            }

            $result_cat = $db->query($sql);
            while ($row = $result_cat->fetch()) {
                $category_permissions[] = [
                    'catid' => (int) $row['catid'],
                    'title' => (string) $row['title'],
                    'padding' => (int) $row['lev'] * 24,
                    'is_admin' => (isset($array_cat_admin[$userid][$row['catid']]) and $array_cat_admin[$userid][$row['catid']]['admin'] == 1),
                    'add_content' => (isset($array_cat_admin[$userid][$row['catid']]) and $array_cat_admin[$userid][$row['catid']]['add_content'] == 1),
                    'pub_content' => (isset($array_cat_admin[$userid][$row['catid']]) and $array_cat_admin[$userid][$row['catid']]['pub_content'] == 1),
                    'edit_content' => (isset($array_cat_admin[$userid][$row['catid']]) and $array_cat_admin[$userid][$row['catid']]['edit_content'] == 1),
                    'del_content' => (isset($array_cat_admin[$userid][$row['catid']]) and $array_cat_admin[$userid][$row['catid']]['del_content'] == 1),
                    'app_content' => (isset($array_cat_admin[$userid][$row['catid']]) and $array_cat_admin[$userid][$row['catid']]['app_content'] == 1)
                ];
            }

            $edit_user = [
                'userid' => $userid,
                'username' => $users_list[$userid]['username'],
                'permission_level' => $admin_module
            ];
            $can_edit_user = true;
        }
    }
} elseif ($view_mode === 'readonly') {
    $sql = 'SELECT catid, title, lev FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';

    if ($db->query($sql)->fetchColumn() == 0) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat');
    }

    $result_cat = $db->query($sql);
    while ($row = $result_cat->fetch()) {
        if (isset($array_cat_admin[$admin_id][$row['catid']])) {
            $check_show = false;
            if ($array_cat_admin[$admin_id][$row['catid']]['admin'] == 1) {
                $check_show = true;
            } else {
                if ($array_cat_admin[$admin_id][$row['catid']]['add_content'] == 1) {
                    $check_show = true;
                } elseif ($array_cat_admin[$admin_id][$row['catid']]['pub_content'] == 1) {
                    $check_show = true;
                } elseif ($array_cat_admin[$admin_id][$row['catid']]['edit_content'] == 1) {
                    $check_show = true;
                } elseif ($array_cat_admin[$admin_id][$row['catid']]['app_content'] == 1) {
                    $check_show = true;
                }
            }

            if ($check_show) {
                $readonly_rows[] = [
                    'catid' => (int) $row['catid'],
                    'title' => (string) $row['title'],
                    'padding' => (int) $row['lev'] * 24,
                    'is_admin' => (isset($array_cat_admin[$admin_id][$row['catid']]) and $array_cat_admin[$admin_id][$row['catid']]['admin'] == 1),
                    'add_content' => (isset($array_cat_admin[$admin_id][$row['catid']]) and $array_cat_admin[$admin_id][$row['catid']]['add_content'] == 1),
                    'pub_content' => (isset($array_cat_admin[$admin_id][$row['catid']]) and $array_cat_admin[$admin_id][$row['catid']]['pub_content'] == 1),
                    'edit_content' => (isset($array_cat_admin[$admin_id][$row['catid']]) and $array_cat_admin[$admin_id][$row['catid']]['edit_content'] == 1),
                    'del_content' => (isset($array_cat_admin[$admin_id][$row['catid']]) and $array_cat_admin[$admin_id][$row['catid']]['del_content'] == 1),
                    'app_content' => (isset($array_cat_admin[$admin_id][$row['catid']]) and $array_cat_admin[$admin_id][$row['catid']]['app_content'] == 1)
                ];
            }
        }
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('admins.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('VIEW_MODE', $view_mode);
$tpl->assign('SHOW_NO_USER', empty($module_info['admins']));
$tpl->assign('TABLE_HEADERS', $table_headers);
$tpl->assign('USERS_LIST', $users_list);
$tpl->assign('ORDERBY', $orderby);
$tpl->assign('ORDERTYPE', $ordertype);
$tpl->assign('EDIT_USER', $edit_user);
$tpl->assign('PERMISSION_OPTIONS', $permission_options);
$tpl->assign('CATEGORY_PERMISSIONS', $category_permissions);
$tpl->assign('READONLY_ROWS', $readonly_rows);
$tpl->assign('CAN_EDIT_USER', $can_edit_user);

$contents = $tpl->fetch('admins.tpl');

$page_title = $nv_Lang->getModule('admin');
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
