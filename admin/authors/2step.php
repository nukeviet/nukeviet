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

$admin_id = $nv_Request->get_absint('admin_id', 'get', $admin_info['admin_id']);

$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $admin_id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$allowed = false;
if (defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) {
    $allowed = true;
} elseif (defined('NV_IS_SPADMIN')) {
    if ($row['admin_id'] == $admin_info['admin_id']) {
        $allowed = true;
    } elseif ($row['lev'] == 3 and $global_config['spadmin_add_admin'] == 1) {
        $allowed = true;
    }
} else {
    if ($row['admin_id'] == $admin_info['admin_id']) {
        $allowed = true;
    }
}

if (empty($allowed)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id;
$row_user = $db->query($sql)->fetch();
if (empty($row_user)) {
    trigger_error('Data error: No user for admin account!', 256);
}
$error = '';

// Xác định quyền sửa tài khoản thành viên
$sql = 'SELECT content FROM ' . NV_USERS_GLOBALTABLE . "_config WHERE config='access_admin'";
$config_user = $db->query($sql)->fetchColumn();
$config_user = empty($config_user) ? [] : unserialize($config_user);
$manager_user_2step = false;
if (
    isset($site_mods['users']) and isset($config_user['access_editus']) and !empty($config_user['access_editus'][$admin_info['level']])
    and ($admin_info['admin_id'] == $row['admin_id'] or $admin_info['level'] < $row['lev'])
    and (empty($global_config['idsite']) or $global_config['idsite'] == $row_user['idsite'])
) {
    $manager_user_2step = true;
}

$page_title = $lang_module['2step_manager'] . ': ' . $row_user['username'];

$xtpl = new XTemplate('2step.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors');
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('USERID', $row['admin_id']);
$xtpl->assign('TOKEND', NV_CHECK_SESSION);

if (empty($row_user['active2step'])) {
    // Xác thực 2 bước bằng ứng dụng đang tắt
    $xtpl->parse('main.code_off');
} else {
    // Xác thực 2 bước bằng ứng dụng đang bật
    $xtpl->parse('main.code_on');
}

if ($row['admin_id'] == $admin_info['admin_id']) {
    // Xác định các cổng Oauth hỗ trợ
    $server_allowed = [];
    if (!empty($global_config['facebook_client_id']) and !empty($global_config['facebook_client_secret'])) {
        $server_allowed['facebook'] = 1;
    }
    if (!empty($global_config['google_client_id']) and !empty($global_config['google_client_secret'])) {
        $server_allowed['google'] = 1;
    }
    if (!empty($global_config['zaloOfficialAccountID']) and !empty($global_config['zaloAppID']) and !empty($global_config['zaloAppSecretKey'])) {
        $server_allowed['zalo'] = 1;
    }

    // Thêm mới tài khoản Oauth
    if (isset($server_allowed[($opt = $nv_Request->get_title('auth', 'get', ''))])) {
        define('NV_ADMIN_2STEP_OAUTH', true);
        require NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_file . '/2step_' . $opt . '.php';

        if (!empty($_GET['code']) and empty($error)) {
            if (empty($attribs)) {
                $error = $lang_global['admin_oauth_error_getdata'];
            } else {
                // Kiểm tra trùng
                $sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_oauth WHERE oauth_uid=' . $db->quote($attribs['full_identity']) . '
                AND admin_id=' . $row['admin_id'] . ' AND oauth_server=' . $db->quote($opt);
                if ($db->query($sql)->fetch()) {
                    $error = $lang_module['2step_error_oauth_exists'];
                }
            }

            if (empty($error)) {
                // Thêm mới vào CSDL
                $sql = 'INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . '_oauth (
                    admin_id, oauth_server, oauth_uid, oauth_email, oauth_id, addtime
                ) VALUES (
                    ' . $row['admin_id'] . ', ' . $db->quote($opt) . ', ' . $db->quote($attribs['full_identity']) . ',
                    ' . $db->quote($attribs['email']) . ', ' . $db->quote($attribs['identity']) . ', ' . NV_CURRENTTIME . '
                )';
                if (!$db->insert_id($sql, 'id')) {
                    $error = $lang_global['admin_oauth_error_savenew'];
                } else {
                    $oauthid = !empty($attribs['email']) ? $attribs['email'] : $attribs['identity'];
                    $message = sprintf($lang_module['2step_oauth_add_mail_content'], $row_user['first_name'], $global_config['site_name'], $oauthid, ucfirst($opt));
                    $checkSend = nv_sendmail([$global_config['site_name'], $global_config['site_email']], $row_user['email'], $lang_module['2step_oauth_add_mail_subject'], $message);

                    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_OAUTH', $opt . ': ' . $oauthid, $admin_info['userid']);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                }
            }
        }
    }

    // Nếu là bản thân thì hiển thị link quản lý
    $xtpl->assign('CODE_SELF_MANAGER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification');
    $xtpl->parse('main.code_self_manager');

    // Thêm Oauth từ các server
    if (isset($server_allowed['facebook'])) {
        $xtpl->assign('LINK_FACEBOOK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=2step&amp;auth=facebook');
        $xtpl->parse('main.add_facebook');
    }
    if (isset($server_allowed['google'])) {
        $xtpl->assign('LINK_GOOGLE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=2step&amp;auth=google');
        $xtpl->parse('main.add_google');
    }
    if (isset($server_allowed['zalo'])) {
        $xtpl->assign('LINK_ZALO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=2step&amp;auth=zalo');
        $xtpl->parse('main.add_zalo');
    }
} elseif ($manager_user_2step and !empty($row_user['active2step'])) {
    // Quản lý 2 bước của tài khoản khác đang bật xác thực
    $xtpl->assign('CODE_MANAGER', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=edit_2step&amp;userid=' . $row_user['userid']);
    $xtpl->parse('main.code_manager');
}

// Danh sách các cổng xác thực
$array_oauth = [];
$list_for_mail = [];
$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_oauth WHERE admin_id=' . $row['admin_id'] . ' ORDER BY addtime DESC';
$result = $db->query($sql);
while ($_row = $result->fetch()) {
    $array_oauth[$_row['id']] = $_row;
    $oauthid = !empty($_row['oauth_email']) ? $_row['oauth_email'] : $_row['oauth_id'];
    $list_for_mail[] = $oauthid . '(' . ucfirst($_row['oauth_server']) . ')';
}

// Xóa tất cả
if ($nv_Request->get_title('delall', 'post', '') === NV_CHECK_SESSION) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    $sql = 'DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . '_oauth WHERE admin_id=' . $row['admin_id'];
    $db->query($sql);

    $list_for_mail = implode(', ', $list_for_mail);
    $message = sprintf($lang_module['2step_oauth_dels_mail_content'], $row_user['first_name'], $global_config['site_name'], $list_for_mail);
    $checkSend = nv_sendmail([$global_config['site_name'], $global_config['site_email']], $row_user['email'], $lang_module['2step_oauth_del_mail_subject'], $message);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_TRUNCATE_OAUTH', 'AID ' . $row['admin_id'], $admin_info['userid']);
    nv_htmlOutput('OK');
}

// Xóa một tài khoản
if ($nv_Request->get_title('del', 'post', '') === NV_CHECK_SESSION) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    $id = $nv_Request->get_absint('id', 'post', 0);
    if (!isset($array_oauth[$id])) {
        nv_htmlOutput('NO');
    }

    $sql = 'DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . '_oauth WHERE admin_id=' . $row['admin_id'] . ' AND id=' . $id;
    $db->query($sql);

    $oauthid = !empty($array_oauth[$id]['oauth_email']) ? $array_oauth[$id]['oauth_email'] : $array_oauth[$id]['oauth_id'];
    $message = sprintf($lang_module['2step_oauth_del_mail_content'], $row_user['first_name'], $global_config['site_name'], $oauthid, ucfirst($array_oauth[$id]['oauth_server']));
    $checkSend = nv_sendmail([$global_config['site_name'], $global_config['site_email']], $row_user['email'], $lang_module['2step_oauth_del_mail_subject'], $message);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_OAUTH', 'AID ' . $row['admin_id'] . ': ' . $array_oauth[$id]['oauth_server'] . '|' . $array_oauth[$id]['oauth_email'], $admin_info['userid']);
    nv_htmlOutput('OK');
}

if (empty($array_oauth)) {
    $xtpl->parse('main.oauth_empty');
} else {
    foreach ($array_oauth as $oauth) {
        $oauth['email_or_id'] = !empty($oauth['oauth_email']) ? $oauth['oauth_email'] : $oauth['oauth_id'];
        $oauth['addtime'] = nv_date('H:i d/m/Y', $oauth['addtime']);
        $xtpl->assign('OAUTH', $oauth);
        $xtpl->parse('main.oauth_data.oauth');
    }

    $xtpl->parse('main.oauth_data');
    $xtpl->parse('main.delete_btn');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
