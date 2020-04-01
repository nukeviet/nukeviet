<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

/**
 * nv_save_file_admin_config()
 *
 * @return
 */
function nv_save_file_admin_config()
{
    global $db;
    $content_config_ip = $content_config_user = '';

    $sql = 'SELECT keyname, mask, begintime, endtime, notice FROM ' . NV_AUTHORS_GLOBALTABLE . '_config';
    $result = $db->query($sql);
    while (list($keyname, $dbmask, $dbbegintime, $dbendtime, $dbnotice) = $result->fetch(3)) {
        $dbendtime = intval($dbendtime);
        if ($dbendtime == 0 or $dbendtime > NV_CURRENTTIME) {
            if ($dbmask == - 1) {
                $content_config_user .= "\$adv_admins['" . md5($keyname) . "'] = array( 'password' => \"" . trim($dbnotice) . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
            } else {
                switch ($dbmask) {
                    case 3:
                        $ip_mask = '/\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/';
                        break;
                    case 2:
                        $ip_mask = '/\.[0-9]{1,3}.[0-9]{1,3}$/';
                        break;
                    case 1:
                        $ip_mask = '/\.[0-9]{1,3}$/';
                        break;
                    default:
                        $ip_mask = '//';
                }
                $content_config_ip .= "\$array_adminip['" . $keyname . "'] = array( 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
            }
        }
    }
    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );\n\n";
    $content_config .= "\$array_adminip = array();\n";
    $content_config .= $content_config_ip . "\n";
    $content_config .= "\$adv_admins = array();\n";
    $content_config .= $content_config_user;

    return file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/admin_config.php', $content_config, LOCK_EX);
}

$delid = $nv_Request->get_int('delid', 'get');
if (! empty($delid)) {
    $sql = 'SELECT keyname FROM ' . NV_AUTHORS_GLOBALTABLE . '_config WHERE id=' . $delid;
    $keyname = $db->query($sql)->fetchColumn();

    $db->query('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . '_config WHERE id=' . $delid);
    nv_save_file_admin_config();
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['adminip_delete'] . ' ' . $lang_module['config'], ' keyname : ' . $keyname, $admin_info['userid']);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$error = array();
$contents = '';

if ($nv_Request->isset_request('savesetting', 'post')) {
    $array_config_global = array();
    $array_config_global['admfirewall'] = $nv_Request->get_int('admfirewall', 'post');
    $array_config_global['block_admin_ip'] = $nv_Request->get_int('block_admin_ip', 'post');

    $array_config_global['spadmin_add_admin'] = $nv_Request->get_int('spadmin_add_admin', 'post');
    $array_config_global['authors_detail_main'] = $nv_Request->get_int('authors_detail_main', 'post');
    $array_config_global['admin_check_pass_time'] = 60 * $nv_Request->get_int('admin_check_pass_time', 'post');
    if ($array_config_global['admin_check_pass_time'] < 120) {
        $array_config_global['admin_check_pass_time'] = 120;
    }

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['save'] . ' ' . $lang_module['config'], 'config', $admin_info['userid']);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

if ($nv_Request->isset_request('submituser', 'post')) {
    $uid = $nv_Request->get_int('uid', 'post', 0);
    $username = $nv_Request->get_title('username', 'post', '', 1);
    $password = $nv_Request->get_title('password', 'post', '', 1);
    $password2 = $nv_Request->get_title('password2', 'post', '', 1);
    $begintime1 = $nv_Request->get_title('begintime1', 'post', 0, 1);
    $endtime1 = $nv_Request->get_title('endtime1', 'post', 0, 1);

    $errorlogin = nv_check_valid_login($username, $global_config['nv_unickmax'], $global_config['nv_unickmin']);
    if (! empty($errorlogin)) {
        $error[] = $errorlogin;
    } elseif (preg_match('/[^a-zA-Z0-9_-]/', $username)) {
        $error[] = $lang_module['rule_user'];
    }
    if (! empty($password) or empty($uid)) {
        $errorpassword = nv_check_valid_pass($password, $global_config['nv_upassmax'], $global_config['nv_upassmin']);
        if (! empty($errorpassword)) {
            $error[] = $errorpassword;
        }
        if ($password != $password2) {
            $error[] = $lang_module['passwordsincorrect'];
        } elseif (preg_match('/[^a-zA-Z0-9_-]/', $password)) {
            $error[] = $lang_module['rule_pass'];
        }
    }

    if (! empty($begintime1) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\.([0-9]{4})$/', $begintime1, $m)) {
        $begintime1 = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $begintime1 = NV_CURRENTTIME;
    }
    if (! empty($endtime1) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $endtime1, $m)) {
        $endtime1 = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $endtime1 = 0;
    }
    if (empty($error)) {
        if ($uid > 0 and $password != '') {
            $sth = $db->prepare("UPDATE " . NV_AUTHORS_GLOBALTABLE . "_config SET keyname= :username, mask='-1', begintime=" . $begintime1 . ", endtime=" . $endtime1 . ", notice='" . md5($password) . "' WHERE id=" . $uid);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->execute();

            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['title_username'], $lang_module['username_edit'] . ' username: ' . $username, $admin_info['userid']);
        } elseif ($uid > 0) {
            $sth = $db->prepare("UPDATE " . NV_AUTHORS_GLOBALTABLE . "_config SET keyname=:username, mask='-1', begintime=" . $begintime1 . ", endtime=" . $endtime1 . " WHERE id=" . $uid);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->execute();

            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['title_username'], $lang_module['username_edit'] . ' username: ' . $username, $admin_info['userid']);
        } else {
            $sth = $db->prepare("INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_config (keyname, mask, begintime, endtime, notice) VALUES (:username, '-1', " . $begintime1 . ", " . $endtime1 . ", '" . md5($password) . "' )");
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->execute();
            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['title_username'], $lang_module['username_add'] . ' username: ' . $username, $admin_info['userid']);
        }
        nv_save_file_admin_config();
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
} else {
    $username = $password = $password2 = $begintime1 = $endtime1 = '';
}

if ($nv_Request->isset_request('submitip', 'post')) {
    $cid = $nv_Request->get_int('cid', 'post', 0);
    $keyname = $nv_Request->get_title('keyname', 'post', '', 1);
    $mask = $nv_Request->get_int('mask', 'post', 0);
    $begintime = $nv_Request->get_title('begintime', 'post', 0, 1);
    $endtime = $nv_Request->get_title('endtime', 'post', 0, 1);

    if (empty($keyname) or ! $ips->nv_validip($keyname)) {
        $error[] = $lang_module['adminip_error_validip'];
    }
    if (! empty($begintime) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $begintime, $m)) {
        $begintime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $begintime = NV_CURRENTTIME;
    }
    if (! empty($endtime) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $endtime, $m)) {
        $endtime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $endtime = 0;
    }
    $notice = $nv_Request->get_title('notice', 'post', '', 1);
    if (empty($error)) {
        if ($cid > 0) {
            $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_config SET keyname= :keyname, mask= :mask, begintime=' . $begintime . ', endtime=' . $endtime . ', notice= :notice WHERE id=' . $cid);
            $sth->bindParam(':keyname', $keyname, PDO::PARAM_STR);
            $sth->bindParam(':mask', $mask, PDO::PARAM_STR);
            $sth->bindParam(':notice', $notice, PDO::PARAM_STR);
            $sth->execute();

            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['adminip'], $lang_module['adminip_edit'] . ' ID ' . $cid . ' -> ' . $keyname, $admin_info['userid']);
        } else {
        	$result = $db->query('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . '_config WHERE keyname=' . $db->quote($keyname));
			if ($result) {
	            $sth = $db->prepare('INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . '_config (keyname, mask, begintime, endtime, notice) VALUES ( :keyname, :mask, ' . $begintime . ', ' . $endtime . ', :notice )');
	            $sth->bindParam(':keyname', $keyname, PDO::PARAM_STR);
	            $sth->bindParam(':mask', $mask, PDO::PARAM_STR);
	            $sth->bindParam(':notice', $notice, PDO::PARAM_STR);
	            $sth->execute();

	            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['adminip'], $lang_module['adminip_add'] . ' ' . $keyname, $admin_info['userid']);
			}
        }
        nv_save_file_admin_config();
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
} else {
    $id = $keyname = $mask = $begintime = $endtime = $notice = '';
}

$cid = $nv_Request->get_int('id', 'get,post');
$uid = $nv_Request->get_int('uid', 'get,post');

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
$xtpl->assign('ADMIN_CHECK_PASS_TIME', round($global_config['admin_check_pass_time'] / 60));
$xtpl->assign('OP', $op);

$xtpl->assign('DATA', array(
    'admfirewall' => $global_config['admfirewall'] ? ' checked="checked"' : '',
    'block_admin_ip' => $global_config['block_admin_ip'] ? ' checked="checked"' : '',
    'authors_detail_main' => $global_config['authors_detail_main'] ? ' checked="checked"' : '',
    'spadmin_add_admin' => $global_config['spadmin_add_admin'] ? ' checked="checked"' : ''
));

if (! empty($error)) {
    $xtpl->assign('ERROR', implode('<br/>', $error));
    $xtpl->parse('main.error');
}

$sql = "SELECT id, keyname, begintime, endtime FROM " . NV_AUTHORS_GLOBALTABLE . "_config WHERE mask = '-1' ORDER BY keyname DESC";
$result = $db->query($sql);
$i = 0;
while (list($dbid, $keyname, $dbbegintime, $dbendtime) = $result->fetch(3)) {
    ++$i;
    $xtpl->assign('ROW', array(
        'keyname' => $keyname,
        'dbbegintime' => ! empty($dbbegintime) ? date('d/m/Y', $dbbegintime) : '',
        'dbendtime' => ! empty($dbendtime) ? date('d/m/Y', $dbendtime) : $lang_module['adminip_nolimit'],
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;uid=' . $dbid . '#iduser',
        'url_delete' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delid=' . $dbid
    ));

    $xtpl->parse('main.list_firewall.loop');
}
if ($i) {
    $xtpl->parse('main.list_firewall');
}

if (! empty($uid)) {
    list($username, $begintime1, $endtime1) = $db->query("SELECT keyname, begintime, endtime FROM " . NV_AUTHORS_GLOBALTABLE . "_config WHERE mask = '-1' AND id=" . $uid)->fetch(3);

    $lang_module['username_add'] = $lang_module['username_edit'];
    $password2 = $password = '';
}

$xtpl->assign('FIREWALLDATA', array(
    'uid' => $uid,
    'username' => $username,
    'password' => $password,
    'password2' => $password2,
    'begintime1' => ! empty($begintime1) ? date('d/m/Y', $begintime1) : '',
    'endtime1' => ! empty($endtime1) ? date('d/m/Y', $endtime1) : ''
));

if (! empty($uid)) {
    $xtpl->parse('main.nochangepass');
}

$mask_text_array = array();
$mask_text_array[0] = '255.255.255.255';
$mask_text_array[3] = '255.255.255.xxx';
$mask_text_array[2] = '255.255.xxx.xxx';
$mask_text_array[1] = '255.xxx.xxx.xxx';

$sql = "SELECT id, keyname, mask, begintime, endtime FROM " . NV_AUTHORS_GLOBALTABLE . "_config WHERE mask!='-1' ORDER BY keyname DESC";
$result = $db->query($sql);

$i = 0;
while (list($dbid, $keyname, $dbmask, $dbbegintime, $dbendtime) = $result->fetch(3)) {
    ++$i;
    $xtpl->assign('ROW', array(
        'keyname' => $keyname,
        'mask_text_array' => $mask_text_array[$dbmask],
        'dbbegintime' => ! empty($dbbegintime) ? date('d/m/Y', $dbbegintime) : '',
        'dbendtime' => ! empty($dbendtime) ? date('d/m/Y', $dbendtime) : '',
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $dbid . '#idip',
        'url_delete' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delid=' . $dbid
    ));

    $xtpl->parse('main.ipaccess.loop');
}
if ($i) {
    $xtpl->parse('main.ipaccess');
}

if (! empty($cid)) {
    list($id, $keyname, $mask, $begintime, $endtime, $notice) = $db->query("SELECT id, keyname, mask, begintime, endtime, notice FROM " . NV_AUTHORS_GLOBALTABLE . "_config WHERE mask != '-1' AND id=" . $cid)->fetch(3);
    $lang_module['adminip_add'] = $lang_module['adminip_edit'];
}

$xtpl->assign('IPDATA', array(
    'cid' => $cid,
    'keyname' => $keyname,
    'selected3' => ($mask == 3) ? ' selected="selected"' : '',
    'selected2' => ($mask == 2) ? ' selected="selected"' : '',
    'selected1' => ($mask == 1) ? ' selected="selected"' : '',
    'begintime' => ! empty($begintime) ? date('d/m/Y', $begintime) : '',
    'endtime' => ! empty($endtime) ? date('d/m/Y', $endtime) : '',
    'notice' => $notice
));

$xtpl->assign('MASK_TEXT_ARRAY', $mask_text_array);

$page_title = $lang_module['config'];

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
