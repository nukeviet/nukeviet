<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    exit('Stop!!!');
}

$page_title = $lang_module['api_cr'];

// Lấy tất cả API Roles
$sql = 'SELECT role_id, role_title FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_role ORDER BY role_id DESC';
$result = $db->query($sql);

$global_array_roles = [];
while ($row = $result->fetch()) {
    $global_array_roles[$row['role_id']] = $row;
}

// Các phương thức xác thực được phép
$credential_auth_methods = ['password_verify', 'none'];

if (empty($global_array_roles)) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=api-roles';
    $contents = nv_theme_alert($lang_global['site_info'], $lang_module['api_cr_error_role_empty'], 'info', $url, $lang_module['api_roles_add']);
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Lấy tất cả các API Credential
$db->sqlreset()->from(NV_AUTHORS_GLOBALTABLE . '_api_credential tb1');
$db->join('INNER JOIN ' . NV_AUTHORS_GLOBALTABLE . ' tb2 ON tb1.admin_id=tb2.admin_id INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb3 ON tb1.admin_id=tb3.userid');
$db->select('tb1.admin_id, tb1.credential_title, tb1.credential_ident, tb1.credential_ips, tb1.auth_method, tb1.api_roles, tb1.addtime, tb1.edittime, tb1.last_access, tb2.lev, tb3.username, tb3.first_name, tb3.last_name');
$db->order('tb1.addtime DESC');

$result = $db->query($db->sql());

$array = [];
while ($row = $result->fetch()) {
    $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
    $row['api_roles'] = array_filter(array_map('intval', explode(',', $row['api_roles'])));

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
        exit('Wrong URL!!!');
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

$xtpl = new XTemplate('api-credentials.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_ADMIN_THEME', $global_config['module_theme']);
$xtpl->assign('LINK_ADD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;add=1');

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
        $credential_ips = empty($array[$credential_ident]['credential_ips']) ? [] : ((array) json_decode($array[$credential_ident]['credential_ips'], true));

        $array_post = [
            'admin_id' => $array[$credential_ident]['admin_id'],
            'credential_title' => $array[$credential_ident]['credential_title'],
            'credential_ips' => implode("\n", $credential_ips),
            'auth_method' => $array[$credential_ident]['auth_method'],
            'api_roles' => $array[$credential_ident]['api_roles']
        ];
        $caption = $lang_module['api_cr_edit'];
    } else {
        $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;add=1';
        $array_post = [
            'admin_id' => 0,
            'credential_title' => '',
            'credential_ips' => '',
            'auth_method' => $credential_auth_methods[0],
            'api_roles' => []
        ];
        $caption = $lang_module['api_cr_add'];
    }

    if ($nv_Request->isset_request('save', 'post')) {
        $array_post['credential_title'] = nv_substr($nv_Request->get_title('credential_title', 'post', ''), 0, 255);
        $array_post['auth_method'] = $nv_Request->get_title('auth_method', 'post', '');

        if (!in_array($array_post['auth_method'], $credential_auth_methods)) {
            $array_post['auth_method'] = $credential_auth_methods[0];
        }

        $str_ips = $nv_Request->get_textarea('credential_ips', '', NV_ALLOWED_HTML_TAGS, true);
        $str_ips = explode('<br />', strip_tags($str_ips, '<br>'));

        $array_credential_ips = [];
        foreach ($str_ips as $str_ip) {
            if ($ips->isIp4($str_ip) or $ips->isIp6($str_ip)) {
                $array_credential_ips[] = $str_ip;
            }
        }
        $array_post['credential_ips'] = empty($array_credential_ips) ? '' : json_encode(array_unique($array_credential_ips));

        if (empty($credential_ident)) {
            $array_post['admin_id'] = $nv_Request->get_int('admin_id', 'post', 0);
        }
        $array_post['api_roles'] = $nv_Request->get_typed_array('api_roles', 'post', 'int', []);
        $array_post['api_roles'] = array_intersect($array_post['api_roles'], array_keys($global_array_roles));
        if (empty($array_post['credential_title'])) {
            $error = $lang_module['api_cr_error_title'];
        } elseif (!isset($array_admins[$array_post['admin_id']])) {
            $error = $lang_module['api_cr_error_admin'];
        } elseif (empty($array_post['api_roles'])) {
            $error = $lang_module['api_cr_error_roles'];
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
                    admin_id, credential_title, credential_ident, credential_secret, credential_ips, auth_method, api_roles, addtime
                ) VALUES (
                    ' . $array_post['admin_id'] . ', :credential_title, :credential_ident, :credential_secret, :credential_ips, :auth_method, :api_roles, ' . NV_CURRENTTIME . '
                )';
                $sth = $db->prepare($sql);

                $new_credential_secret_db = $crypt->encrypt($new_credential_secret);
                $api_roles = implode(',', $array_post['api_roles']);

                $sth->bindParam(':credential_title', $array_post['credential_title'], PDO::PARAM_STR);
                $sth->bindParam(':credential_ident', $new_credential_ident, PDO::PARAM_STR);
                $sth->bindParam(':credential_secret', $new_credential_secret_db, PDO::PARAM_STR);
                $sth->bindParam(':credential_ips', $array_post['credential_ips'], PDO::PARAM_STR);
                $sth->bindParam(':auth_method', $array_post['auth_method'], PDO::PARAM_STR);
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
                    credential_ips=:credential_ips,
                    auth_method=:auth_method,
                    api_roles=:api_roles,
                    edittime=' . NV_CURRENTTIME . '
                WHERE credential_ident=' . $db->quote($credential_ident);
                $sth = $db->prepare($sql);
                $api_roles = implode(',', $array_post['api_roles']);
                $sth->bindParam(':credential_title', $array_post['credential_title'], PDO::PARAM_STR);
                $sth->bindParam(':credential_ips', $array_post['credential_ips'], PDO::PARAM_STR);
                $sth->bindParam(':auth_method', $array_post['auth_method'], PDO::PARAM_STR);
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

    $xtpl->assign('FORM_ACTION', $form_action);
    $xtpl->assign('CAPTION', $caption);
    $xtpl->assign('DATA', $array_post);

    if (empty($credential_ident)) {
        // Xuất quản trị
        foreach ($array_admins as $admin) {
            $admin['selected'] = $admin['admin_id'] == $array_post['admin_id'] ? ' selected="selected"' : '';

            $xtpl->assign('ADMIN', $admin);

            if (!empty($admin['full_name'])) {
                $xtpl->parse('content.for_admin.admin.full_name');
            }

            $xtpl->parse('content.for_admin.admin');
        }

        $xtpl->parse('content.for_admin');
    }

    // Xuất các phương thức xác thực
    foreach ($credential_auth_methods as $auth_method) {
        $xtpl->assign('AUTH_METHOD', [
            'key' => $auth_method,
            'title' => $lang_module['api_cr_auth_method_' . $auth_method],
            'checked' => $auth_method == $array_post['auth_method'] ? ' checked="checked"' : ''
        ]);
        $xtpl->parse('content.auth_method');
    }

    // Xuất lỗi
    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('content.error');
    }

    // Xuất các role
    foreach ($global_array_roles as $role) {
        $role['checked'] = in_array((int) $role['role_id'], array_map('intval', $array_post['api_roles']), true) ? ' checked="checked"' : '';

        $xtpl->assign('ROLE', $role);
        $xtpl->parse('content.role');
    }

    $xtpl->parse('content');
    $contents = $xtpl->text('content');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if (empty($array)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add=1');
}

// Thông báo nếu Remote API đang tắt.
if (empty($global_config['remote_api_access'])) {
    $url_config = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=settings&amp;' . NV_OP_VARIABLE . '=system';
    $xtpl->assign('REMOTE_OFF', sprintf($lang_module['api_remote_off'], $url_config));
    $xtpl->parse('main.remote_off');
}

// Xuất quyền truy cập API
foreach ($array as $row) {
    $row['api_roles_show'] = implode(', ', $row['api_roles_show']);
    $row['last_access'] = empty($row['last_access']) ? $lang_module['api_cr_last_access_none'] : nv_date('H:i d/m/Y', $row['last_access']);
    $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;credential_ident=' . $row['credential_ident'];

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
