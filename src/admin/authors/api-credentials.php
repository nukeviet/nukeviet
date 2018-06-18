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

$page_title = $nv_Lang->getModule('api_cr');

// Lấy tất cả API Roles
$sql = 'SELECT role_id, role_title FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_role ORDER BY role_id DESC';
$result = $db->query($sql);

$global_array_roles = array();
while ($row = $result->fetch()) {
    $global_array_roles[$row['role_id']] = $row;
}

if (empty($global_array_roles)) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=api-roles';
    $contents = nv_theme_alert($nv_Lang->getGlobal('site_info'), $nv_Lang->getModule('api_cr_error_role_empty'), 'info', $url, $nv_Lang->getModule('api_roles_add'));
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Lấy tất cả các API Credential
$db->sqlreset()->from(NV_AUTHORS_GLOBALTABLE . '_api_credential tb1');
$db->join('INNER JOIN ' . NV_AUTHORS_GLOBALTABLE . ' tb2 ON tb1.admin_id=tb2.admin_id INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb3 ON tb1.admin_id=tb3.userid');
$db->select('tb1.admin_id, tb1.credential_title, tb1.credential_ident, tb1.api_roles, tb1.addtime, tb1.edittime, tb1.last_access, tb2.lev, tb3.username, tb3.first_name, tb3.last_name');
$db->order('tb1.addtime DESC');

$result = $db->query($db->sql());

$array = array();
while ($row = $result->fetch()) {
    $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
    $row['api_roles'] = array_filter(explode(',', $row['api_roles']));
    $array[$row['credential_ident']] = $row;
}


// Xóa API Credential
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL!!!');
    }

    $credential_ident = $nv_Request->get_title('credential_ident', 'post', '');
    if (!isset($array[$credential_ident])) {
        nv_htmlOutput('NO');
    }

    $db->query('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_credential WHERE credential_ident=' . $db->quote($credential_ident));
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete API Credential', $credential_ident, $admin_info['userid']);
    nv_htmlOutput('OK');
}

// Thêm, sửa API Credential
$credential_ident = $nv_Request->get_title('credential_ident', 'get', '');
if (!empty($credential_ident) and !isset($array[$credential_ident])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('NV_ADMIN_THEME', $global_config['admin_theme']);
$xtpl->assign('LINK_ADD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;add=1');

if ($nv_Request->isset_request('add', 'get') or !empty($credential_ident)) {
    // Lấy tất cả các Admin
    $db->sqlreset()->from(NV_AUTHORS_GLOBALTABLE . ' tb1');
    $db->join('INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb2 ON tb1.admin_id=tb2.userid');
    $db->select('tb1.admin_id, tb1.lev, tb2.username, tb2.first_name, tb2.last_name');
    $result = $db->query($db->sql());
    $array_admins = array();
    while ($row = $result->fetch()) {
        $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
        $array_admins[$row['admin_id']] = $row;
    }

    $error = '';
    if ($credential_ident) {
        $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;credential_ident=' . $credential_ident;
        $table_caption = $nv_Lang->getModule('api_cr_edit');
        $array_post = array(
            'admin_id' => $array[$credential_ident]['admin_id'],
            'credential_title' => $array[$credential_ident]['credential_title'],
            'api_roles' => $array[$credential_ident]['api_roles']
        );
    } else {
        $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;add=1';
        $table_caption = $nv_Lang->getModule('api_cr_add');
        $array_post = array(
            'admin_id' => 0,
            'credential_title' => '',
            'api_roles' => array()
        );
    }

    if ($nv_Request->isset_request('submit', 'post')) {
        $array_post['credential_title'] = nv_substr($nv_Request->get_title('credential_title', 'post', ''), 0, 255);
        if (empty($credential_ident)) {
            $array_post['admin_id'] = $nv_Request->get_int('admin_id', 'post', 0);
        }
        $array_post['api_roles'] = $nv_Request->get_typed_array('api_roles', 'post', 'int', array());
        $array_post['api_roles'] = array_intersect($array_post['api_roles'], array_keys($global_array_roles));
        if (empty($array_post['credential_title'])) {
            $error = $nv_Lang->getModule('api_cr_error_title');
        } elseif (!isset($array_admins[$array_post['admin_id']])) {
            $error = $nv_Lang->getModule('api_cr_error_admin');
        } elseif (empty($array_post['api_roles'])) {
            $error = $nv_Lang->getModule('api_cr_error_roles');
        } else {
            if (empty($credential_ident)) {
                // Tạo mới
                $new_credential_ident = '';
                $new_credential_secret = '';
                while (empty($new_credential_ident) or $db->query('SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_credential WHERE credential_ident=' . $db->quote($new_credential_ident))->fetchColumn()) {
                    $new_credential_ident = nv_genpass(32, 3);
                }
                while (empty($new_credential_secret) or $db->query('SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_credential WHERE credential_ident=' . $db->quote($new_credential_secret))->fetchColumn()) {
                    $new_credential_secret = nv_genpass(32, 3);
                }

                $sql = 'INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . '_api_credential (
                    admin_id, credential_title, credential_ident, credential_secret, api_roles, addtime
                ) VALUES (
                    ' . $array_post['admin_id'] . ', :credential_title, :credential_ident, :credential_secret, :api_roles, ' . NV_CURRENTTIME . '
                )';
                $sth = $db->prepare($sql);

                $new_credential_secret_db = $crypt->encrypt($new_credential_secret);
                $api_roles = implode(',', $array_post['api_roles']);

                $sth->bindParam(':credential_title', $array_post['credential_title'], PDO::PARAM_STR);
                $sth->bindParam(':credential_ident', $new_credential_ident, PDO::PARAM_STR);
                $sth->bindParam(':credential_secret', $new_credential_secret_db, PDO::PARAM_STR);
                $sth->bindParam(':api_roles', $api_roles, PDO::PARAM_STR);

                if ($sth->execute()) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add API Credential', $new_credential_ident, $admin_info['userid']);

                    $xtpl->assign('CREDENTIAL_IDENT', $new_credential_ident);
                    $xtpl->assign('CREDENTIAL_SECRET', $new_credential_secret);
                    $xtpl->assign('URL_BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

                    $xtpl->parse('result');
                    $contents = $xtpl->text('result');

                    include NV_ROOTDIR . '/includes/header.php';
                    echo nv_admin_theme($contents);
                    include NV_ROOTDIR . '/includes/footer.php';
                } else {
                    $error = 'Unknow Error!!!';
                }
            } else {
                // Cập nhật
                $sql = 'UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_api_credential SET
                    credential_title=:credential_title,
                    api_roles=:api_roles,
                    edittime=' . NV_CURRENTTIME . '
                WHERE credential_ident=' . $db->quote($credential_ident);
                $sth = $db->prepare($sql);
                $api_roles = implode(',', $array_post['api_roles']);
                $sth->bindParam(':credential_title', $array_post['credential_title'], PDO::PARAM_STR);
                $sth->bindParam(':api_roles', $api_roles, PDO::PARAM_STR);
                if ($sth->execute()) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit API Credential', $credential_ident, $admin_info['userid']);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                } else {
                    $error = 'Unknow error!!!';
                }
            }
        }
    }

    $xtpl->assign('DATA', $array_post);
    $xtpl->assign('FORM_ACTION', $form_action);
    $xtpl->assign('TABLE_CAPTION', $table_caption);

    if (empty($credential_ident)) {
        foreach ($array_admins as $admin) {
            if (!empty($admin['full_name'])) {
                $admin['username'] .= ' (' . $admin['full_name'] . ')';
            }
            $admin['selected'] = $admin['admin_id'] == $array_post['admin_id'] ? ' selected="selected"' : '';
            $xtpl->assign('ADMIN', $admin);
            $xtpl->parse('contents.admin.loop');
        }
        $xtpl->parse('contents.admin');
    }

    foreach ($global_array_roles as $api_role) {
        $api_role['checked'] = in_array($api_role['role_id'], $array_post['api_roles']) ? ' checked="checked"' : '';
        $xtpl->assign('API_ROLE', $api_role);
        $xtpl->parse('contents.api_role');
    }

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('contents.error');
    }

    $xtpl->parse('contents');
    $contents = $xtpl->text('contents');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if (empty($array)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add=1');
}

// Thông báo nếu Remote API đang tắt.
if (empty($global_config['remote_api_access'])) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=settings&amp;' . NV_OP_VARIABLE . '=system';
    $xtpl->assign('MESSAGE', $nv_Lang->getModule('api_remote_off', $url));
    $xtpl->parse('main.remote_api_off');
}

foreach ($array as $row) {
    $row['addtime'] = nv_date('H:i d/m/Y', $row['addtime']);
    $row['edittime'] = $row['edittime'] ? nv_date('H:i d/m/Y', $row['edittime']) : '';
    $row['last_access'] = $row['last_access'] ? nv_date('H:i d/m/Y', $row['last_access']) : $nv_Lang->getModule('api_cr_last_access_none');
    $row['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;credential_ident=' . $row['credential_ident'];

    $api_roles = array();
    foreach ($row['api_roles'] as $role_id) {
        if (isset($global_array_roles[$role_id])) {
            $api_roles[] = $global_array_roles[$role_id]['role_title'];
        }
    }

    $xtpl->assign('API_ROLES', implode(', ', $api_roles));
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
