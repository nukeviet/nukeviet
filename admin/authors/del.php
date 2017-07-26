<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:23
 */

if (! defined('NV_IS_FILE_AUTHORS')) {
    die('Stop!!!');
}

if (! defined('NV_IS_SPADMIN')) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$admin_id = $nv_Request->get_int('admin_id', 'get', 0);

if (empty($admin_id) or $admin_id == $admin_info['admin_id']) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $admin_id;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}


if ($row['lev'] == 1 or (! defined('NV_IS_GODADMIN') and $row['lev'] == 2)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

function nv_checkAdmpass($adminpass)
{
    global $db, $admin_info, $crypt;

    $sql = 'SELECT password FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_info['userid'];
    $pass = $db->query($sql)->fetchColumn();
    return $crypt->validate_password($adminpass, $pass);
}

$access_admin = $db->query("SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='access_admin'")->fetchColumn();
$access_admin = unserialize($access_admin);
$level = $admin_info['level'];

$array_action_account = array();
$array_action_account[0] = $lang_module['action_account_nochange'];
if (isset($access_admin['access_waiting'][$level]) and $access_admin['access_waiting'][$level] == 1) {
    $array_action_account[1] = $lang_module['action_account_suspend'];
}
if (isset($access_admin['access_delus'][$level]) and $access_admin['access_delus'][$level] == 1) {
    $array_action_account[2] = $lang_module['action_account_del'];
}

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id;
$row_user = $db->query($sql)->fetch();

$action_account = $nv_Request->get_int('action_account', 'post', 0);
$action_account = (isset($array_action_account[$action_account])) ? $action_account : 0;
$error = '';
$checkss = md5($admin_id . NV_CHECK_SESSION);
if ($nv_Request->get_title('ok', 'post', 0) == $checkss) {
    $sendmail = $nv_Request->get_int('sendmail', 'post', 0);
    $reason = $nv_Request->get_title('reason', 'post', '', 1);
    $adminpass = $nv_Request->get_title('adminpass_iavim', 'post');

    if (empty($adminpass)) {
        $error = $lang_global['admin_password_empty'];
    } elseif (! nv_checkAdmpass($adminpass)) {
        $error = sprintf($lang_global['adminpassincorrect'], $adminpass);
        $adminpass = '';
    } else {
        if ($row['lev'] == 3) {
            $is_delCache = false;
            $array_keys = array_keys($site_mods);
            foreach ($array_keys as $mod) {
                if (! empty($mod)) {
                    if (! empty($site_mods[$mod]['admins'])) {
                        $admins = explode(',', $site_mods[$mod]['admins']);
                        if (in_array($admin_id, $admins)) {
                            $admins = array_diff($admins, array( $admin_id ));
                            $admins = implode(',', $admins);

                            $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET admins= :admins WHERE title= :mod');
                            $sth->bindParam(':admins', $admins, PDO::PARAM_STR);
                            $sth->bindParam(':mod', $mod, PDO::PARAM_STR);
                            $sth->execute();

                            $is_delCache = true;
                        }
                    }
                }
            }
            if ($is_delCache) {
                $nv_Cache->delMod('modules');
            }
        }
        $db->query('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = ' . $admin_id);
        if ($action_account == 1) {
            $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET active=0 WHERE userid=' . $admin_id);
        } elseif ($action_account == 2) {
            $db->query('UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers-1 WHERE group_id IN (SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $admin_id . ' AND approved = 1)');
            $db->query('DELETE FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $admin_id);
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE userid=' . $admin_id);
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '_info WHERE userid=' . $admin_id);
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id);
            if (! empty($row_user['photo']) and is_file(NV_ROOTDIR . '/' . $row_user['photo'])) {
                @nv_deletefile(NV_ROOTDIR . '/' . $row_user['photo']);
            }
        }

        if ($action_account != 2) {
            nv_groups_del_user($row['lev'], $admin_id);
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['nv_admin_del'], 'Username: ' . $row_user['username'] . ', ' . $array_action_account[$action_account], $admin_info['userid']);

        $db->query('OPTIMIZE TABLE ' . NV_AUTHORS_GLOBALTABLE);

        if ($sendmail) {
            $title = sprintf($lang_module['delete_sendmail_title'], $global_config['site_name']);
            $my_sig = (! empty($admin_info['sig'])) ? $admin_info['sig'] : 'All the best';
            $my_mail = $admin_info['view_mail'] ? $admin_info['email'] : $global_config['site_email'];
            if (empty($reason)) {
                $message = sprintf($lang_module['delete_sendmail_mess0'], $global_config['site_name'], nv_date('d/m/Y H:i', NV_CURRENTTIME), $my_mail);
            } else {
                $message = sprintf($lang_module['delete_sendmail_mess1'], $global_config['site_name'], nv_date('d/m/Y H:i', NV_CURRENTTIME), $reason, $my_mail);
            }

            $message = trim($message);

            $mess = $message;
            $mess = nv_nl2br($mess, '<br />');

            $xtpl = new XTemplate('message.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/system');

            $xtpl->assign('SITE_NAME', $global_config['site_name']);
            $xtpl->assign('SITE_SLOGAN', $global_config['site_description']);
            $xtpl->assign('SITE_EMAIL', $global_config['site_email']);
            $xtpl->assign('SITE_FONE', $global_config['site_phone']);
            $xtpl->assign('SITE_URL', $global_config['site_url']);
            $xtpl->assign('TITLE', $title);
            $xtpl->assign('CONTENT', $mess);
            $xtpl->assign('AUTHOR_SIG', $my_sig);
            $xtpl->assign('AUTHOR_NAME', $admin_info['username']);
            $xtpl->assign('AUTHOR_POS', $admin_info['position']);
            $xtpl->assign('AUTHOR_EMAIL', $my_mail);

            $xtpl->parse('main');
            $content = $xtpl->text('main');

            $from = array( $admin_info['username'], $my_mail );
            $to = $row_user['email'];
            $send = nv_sendmail($from, $to, $title, $content);
            if (! $send) {
                $page_title = $lang_global['error_info_caption'];
                $contents = $lang_global['error_sendmail_admin'] . '<meta http-equiv="refresh" content="10;URL=' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '" />';

                include NV_ROOTDIR . '/includes/header.php';
                echo nv_admin_theme($contents);
                include NV_ROOTDIR . '/includes/footer.php';
            }
        }
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
} else {
    $sendmail = 1;
    $reason = $adminpass = '';
}

$contents = array();
$contents['is_error'] = (! empty($error)) ? 1 : 0;
$contents['title'] = (! empty($error)) ? $error : sprintf($lang_module['delete_sendmail_info'], $row_user['username']);
$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del&amp;admin_id=' . $admin_id;
$contents['sendmail'] = $sendmail;

$contents['reason'] = array( $reason, 255 );

$contents['admin_password'] = array( $lang_global['admin_password'], $adminpass, $global_config['nv_upassmax'] );

$page_title = $lang_module['nv_admin_del'];

// Parse content
$xtpl = new XTemplate('del.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$class = $contents['is_error'] ? 'class="alert alert-danger"' : 'class="alert alert-info"';

$xtpl->assign('LANG', $lang_module);
$xtpl->assign('CHECKSS', $checkss);

$xtpl->assign('CLASS', $contents['is_error'] ? 'class="alert alert-danger"' : 'class="alert alert-info"');
$xtpl->assign('TITLE', $contents['title']);
$xtpl->assign('ACTION', $contents['action']);
$xtpl->assign('CHECKED', $contents['sendmail'] ? ' checked="checked"' : '');

$xtpl->assign('REASON1', $contents['reason'][0]);
$xtpl->assign('REASON2', $contents['reason'][1]);

$xtpl->assign('ADMIN_PASSWORD0', $contents['admin_password'][0]);
$xtpl->assign('ADMIN_PASSWORD1', $contents['admin_password'][1]);
$xtpl->assign('ADMIN_PASSWORD2', $contents['admin_password'][2]);
foreach ($array_action_account as $key => $value) {
    $xtpl->assign('ACTION_ACCOUNT_KEY', $key);
    $xtpl->assign('ACTION_ACCOUNT_CHECK', ($key == $action_account) ? ' checked="checked"' : '');
    $xtpl->assign('ACTION_ACCOUNT_TITLE', $value);
    $xtpl->parse('del.action_account');
}

$xtpl->parse('del');
$contents = $xtpl->text('del');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
