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

$global_array_roles = [];
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

$array = [];
while ($row = $result->fetch()) {
    $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
    $row['api_roles'] = array_filter(explode(',', $row['api_roles']));

    $api_roles = [];
    foreach ($row['api_roles'] as $role_id) {
        if (isset($global_array_roles[$role_id])) {
            $api_roles[] = $global_array_roles[$role_id]['role_title'];
        }
    }
    $row['api_roles_show'] = $api_roles;

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

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('LINK_ADD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;add=1');

if ($nv_Request->isset_request('add', 'get') or !empty($credential_ident)) {
    // Lấy tất cả các Admin
    $db->sqlreset()->from(NV_AUTHORS_GLOBALTABLE . ' tb1');
    $db->join('INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb2 ON tb1.admin_id=tb2.userid');
    $db->select('tb1.admin_id, tb1.lev, tb2.username, tb2.first_name, tb2.last_name');
    $result = $db->query($db->sql());
    $array_admins = [];
    while ($row = $result->fetch()) {
        $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
        $array_admins[$row['admin_id']] = $row;
    }

    $error = '';
    if ($credential_ident) {
        $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;credential_ident=' . $credential_ident;
        $array_post = [
            'admin_id' => $array[$credential_ident]['admin_id'],
            'credential_title' => $array[$credential_ident]['credential_title'],
            'api_roles' => $array[$credential_ident]['api_roles']
        ];
    } else {
        $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;add=1';
        $array_post = [
            'admin_id' => 0,
            'credential_title' => '',
            'api_roles' => []
        ];
    }

    if ($nv_Request->isset_request('submit', 'post')) {
        $array_post['credential_title'] = nv_substr($nv_Request->get_title('credential_title', 'post', ''), 0, 255);
        if (empty($credential_ident)) {
            $array_post['admin_id'] = $nv_Request->get_int('admin_id', 'post', 0);
        }
        $array_post['api_roles'] = $nv_Request->get_typed_array('api_roles', 'post', 'int', []);
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

                    $tpl->assign('CREDENTIAL_IDENT', $new_credential_ident);
                    $tpl->assign('CREDENTIAL_SECRET', $new_credential_secret);
                    $tpl->assign('URL_BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

                    $contents = $tpl->fetch('api-credentials-result.tpl');

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

    $tpl->assign('CREDENTIAL_IDENT', $credential_ident);
    $tpl->assign('DATA', $array_post);
    $tpl->assign('FORM_ACTION', $form_action);
    $tpl->assign('ERROR', $error);
    $tpl->assign('ARRAY_ADMINS', $array_admins);
    $tpl->assign('ARRAY_ROLES', $global_array_roles);

    $contents = $tpl->fetch('api-credentials.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if (empty($array)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add=1');
}

$tpl->registerPlugin('modifier', 'implode', 'implode');
$tpl->registerPlugin('modifier', 'date', 'nv_date');

// Thông báo nếu Remote API đang tắt.
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('REMOTE_API_ACCESS', $global_config['remote_api_access']);
$tpl->assign('URL_CONFIG', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=settings&amp;' . NV_OP_VARIABLE . '=system');
$tpl->assign('ARRAY', $array);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$contents = $tpl->fetch('api-credentials-list.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
