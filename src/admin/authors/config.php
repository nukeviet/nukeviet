<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('config');

/**
 * nv_save_file_admin_config()
 *
 * @return false|int
 */
function nv_save_file_admin_config()
{
    global $db, $ips;

    $content_config_ip = $content_config_user = '';

    $sql = 'SELECT keyname, mask, begintime, endtime, notice FROM ' . NV_AUTHORS_GLOBALTABLE . '_config';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $endtime = (int) $row['endtime'];
        if ($endtime === 0 || $endtime > NV_CURRENTTIME) {
            if ((int) $row['mask'] === -1) {
                // Cấu hình tài khoản truy cập
                $content_config_user .= "\$adv_admins['" . md5($row['keyname']) . "'] = ['password' => " . var_export(trim($row['notice']), true) . ", 'begintime' => " . $row['begintime'] . ", 'endtime' => " . $endtime . "];\n";
            } elseif ($ips->isIp6($row['keyname'])) {
                $content_config_ip .= "\$array_adminip['" . $row['keyname'] . "'] = ['ip6' => 1, 'mask' => \"" . $row['keyname'] . '/' . $row['mask'] . "\", 'begintime' => " . $row['begintime'] . ", 'endtime' => " . $endtime . "];\n";
            } else {
                switch ((int) $row['mask']) {
                    case 3:
                        $ipMask = '/\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/';
                        break;
                    case 2:
                        $ipMask = '/\.[0-9]{1,3}\.[0-9]{1,3}$/';
                        break;
                    case 1:
                        $ipMask = '/\.[0-9]{1,3}$/';
                        break;
                    default:
                        $ipMask = '//';
                        break;
                }

                $content_config_ip .= "\$array_adminip['" . $row['keyname'] . "'] = ['ip6' => 0, 'mask' => \"" . $ipMask . "\", 'begintime' => " . $row['begintime'] . ", 'endtime' => " . $endtime . "];\n";
            }
        }
    }
    $result->closeCursor();
    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
    $content_config .= "\$array_adminip = [];\n";
    $content_config .= $content_config_ip . "\n";
    $content_config .= "\$adv_admins = [];\n";
    $content_config .= $content_config_user;

    return file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/admin_config.php', $content_config, LOCK_EX);
}


// Xóa tài khoản cấu hình
if ($nv_Request->isset_request('delid', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Error!!!');
    }

    $respon = [
        'error' => 1,
        'message' => 'Error!!!',
    ];

    $delid = $nv_Request->get_int('delid', 'post', 0);
    if (empty($delid)) {
        $respon['message'] = 'No ID';
        nv_jsonOutput($respon);
    }
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key . '_' . $delid)) {
        $respon['message'] = $nv_Lang->getGlobal('error_checkss');
        nv_jsonOutput($respon);
    }

    $stmt = $db->prepare('SELECT keyname FROM ' . NV_AUTHORS_GLOBALTABLE . '_config WHERE id = :id');
    $stmt->bindValue(':id', $delid, PDO::PARAM_INT);
    $stmt->execute();
    $keyname = $stmt->fetchColumn();

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('adminip_delete') . ' ' . $nv_Lang->getModule('config'), ' keyname : ' . $keyname, $admin_info['userid']);
    $stmt = $db->prepare('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . '_config WHERE id = :id');
    $stmt->bindValue(':id', $delid, PDO::PARAM_INT);
    $stmt->execute();
    nv_save_file_admin_config();
    $respon['error'] = 0;
    nv_jsonOutput($respon);
}

$error_user = $error_ip = [];
$array_iptypes = [
    4 => 'IPv4',
    6 => 'IPv6'
];

if ($nv_Request->isset_request('savesetting', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('save') . ' ' . $nv_Lang->getModule('config'), 'config error checkss', $admin_info['userid']);
    } else {
        $array_config_global = [];
        $array_config_global['admfirewall'] = $nv_Request->get_int('admfirewall', 'post');
        $array_config_global['block_admin_ip'] = $nv_Request->get_int('block_admin_ip', 'post');

        $array_config_global['spadmin_add_admin'] = $nv_Request->get_int('spadmin_add_admin', 'post');
        $array_config_global['authors_detail_main'] = $nv_Request->get_int('authors_detail_main', 'post');
        $array_config_global['admin_check_pass_time'] = 60 * $nv_Request->get_int('admin_check_pass_time', 'post');
        if ($array_config_global['admin_check_pass_time'] < 120) {
            $array_config_global['admin_check_pass_time'] = 120;
        }
        $array_config_global['admin_user_logout'] = (int) $nv_Request->get_bool('admin_user_logout', 'post', false);
        $array_config_global['admin_login_duration'] = 60 * $nv_Request->get_int('admin_login_duration', 'post', 0);

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
        foreach ($array_config_global as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        nv_save_file_config_global();
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('save') . ' ' . $nv_Lang->getModule('config'), 'config', $admin_info['userid']);
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

if ($nv_Request->isset_request('submituser', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        $error_user[] = $nv_Lang->getGlobal('error_checkss');
    } else {
        $uid = $nv_Request->get_int('uid', 'post', 0);
        $username = $nv_Request->get_title('username', 'post', '');
        $password = $nv_Request->get_title('password', 'post', '');
        $password2 = $nv_Request->get_title('password2', 'post', '');
        $begintime1 = nv_d2u_post($nv_Request->get_title('begintime1', 'post', ''));
        $endtime1 = nv_d2u_post($nv_Request->get_title('endtime1', 'post', ''));

        $errorlogin = nv_check_valid_login($username, $global_config['nv_unickmax'], $global_config['nv_unickmin']);
        if (!empty($errorlogin)) {
            $error_user[] = $errorlogin;
        } elseif (preg_match('/[^a-zA-Z0-9_-]/', $username)) {
            $error_user[] = $nv_Lang->getModule('rule_user');
        } else {
            $stmt = $db->prepare('SELECT id FROM ' . NV_AUTHORS_GLOBALTABLE . '_config WHERE keyname = :keyname AND id != :id');
            $stmt->bindValue(':keyname', $username, PDO::PARAM_STR);
            $stmt->bindValue(':id', $uid, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn()) {
                $error_user[] = $nv_Lang->getModule('adminur_exists');
            }
        }
        if (!empty($password) or empty($uid)) {
            $errorpassword = nv_check_valid_pass($password, $global_config['nv_upassmax'], $global_config['nv_upassmin']);
            if (!empty($errorpassword)) {
                $error_user[] = $errorpassword;
            }
            if ($password != $password2) {
                $error_user[] = $nv_Lang->getModule('passwordsincorrect');
            } elseif (preg_match('/[^a-zA-Z0-9_-]/', $password)) {
                $error_user[] = $nv_Lang->getModule('rule_pass');
            }
        }

        if (empty($error_user)) {
            if ($uid > 0 and $password != '') {
                $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . "_config SET keyname = :username, mask = '-1', begintime = :begintime, endtime = :endtime, notice = :notice WHERE id = :id");
                $sth->bindValue(':username', $username, PDO::PARAM_STR);
                $sth->bindValue(':begintime', $begintime1, PDO::PARAM_INT);
                $sth->bindValue(':endtime', $endtime1, PDO::PARAM_INT);
                $sth->bindValue(':notice', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
                $sth->bindValue(':id', $uid, PDO::PARAM_INT);
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('title_username'), $nv_Lang->getModule('username_edit') . ' username: ' . $username, $admin_info['userid']);
            } elseif ($uid > 0) {
                $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . "_config SET keyname = :username, mask = '-1', begintime = :begintime, endtime = :endtime WHERE id = :id");
                $sth->bindValue(':username', $username, PDO::PARAM_STR);
                $sth->bindValue(':begintime', $begintime1, PDO::PARAM_INT);
                $sth->bindValue(':endtime', $endtime1, PDO::PARAM_INT);
                $sth->bindValue(':id', $uid, PDO::PARAM_INT);
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('title_username'), $nv_Lang->getModule('username_edit') . ' username: ' . $username, $admin_info['userid']);
            } else {
                $sth = $db->prepare('INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . "_config (keyname, mask, begintime, endtime, notice) VALUES (:username, '-1', :begintime, :endtime, :notice)");
                $sth->bindValue(':username', $username, PDO::PARAM_STR);
                $sth->bindValue(':begintime', $begintime1, PDO::PARAM_INT);
                $sth->bindValue(':endtime', $endtime1, PDO::PARAM_INT);
                $sth->bindValue(':notice', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
                $sth->execute();
                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('title_username'), $nv_Lang->getModule('username_add') . ' username: ' . $username, $admin_info['userid']);
            }
            nv_save_file_admin_config();
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
        }
    }
} else {
    $username = $password = $password2 = '';
    $begintime1 = $endtime1 = 0;
}

$cid = $nv_Request->get_int('id', 'get,post');
$uid = $nv_Request->get_int('uid', 'get,post');

if ($nv_Request->isset_request('submitip', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        $error_ip[] = $nv_Lang->getGlobal('error_checkss');
    } else {
        $ip_version = $nv_Request->get_int('ip_version', 'post', 4);
        $cid = $nv_Request->get_int('cid', 'post', 0);
        $keyname = $nv_Request->get_title('keyname', 'post', '');
        $mask = $nv_Request->get_int('mask', 'post', 0);
        $mask6 = $nv_Request->get_int('mask6', 'post', 1);
        $begintime = $nv_Request->get_title('begintime', 'post', 0);
        $endtime = $nv_Request->get_title('endtime', 'post', 0);

        if ($ip_version != 4 and $ip_version != 6) {
            $ip_version = 4;
        }
        if ($mask6 < 1 or $mask6 > 128) {
            $mask6 = 128;
        }
        if ($mask < 0 or $mask > 3) {
            $mask = 0;
        }

        if (empty($keyname) or ($ip_version == 4 and !$ips->isIp4($keyname)) or ($ip_version == 6 and !$ips->isIp6($keyname))) {
            $error_ip[] = $nv_Lang->getModule('adminip_error_validip');
        }
        if (!empty($begintime) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $begintime, $m)) {
            $begintime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        } else {
            $begintime = NV_CURRENTTIME;
        }
        if (!empty($endtime) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $endtime, $m)) {
            $endtime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        } else {
            $endtime = 0;
        }

        $notice = $nv_Request->get_title('notice', 'post', '');
        $ipmask = $ip_version == 4 ? $mask : $mask6;

        if (empty($error_ip)) {
            if ($cid > 0) {
                $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_config SET keyname = :keyname, mask = :mask, begintime = :begintime, endtime = :endtime, notice = :notice WHERE id = :id');
                $sth->bindValue(':keyname', $keyname, PDO::PARAM_STR);
                $sth->bindValue(':mask', $ipmask, PDO::PARAM_STR);
                $sth->bindValue(':begintime', $begintime, PDO::PARAM_INT);
                $sth->bindValue(':endtime', $endtime, PDO::PARAM_INT);
                $sth->bindValue(':notice', $notice, PDO::PARAM_STR);
                $sth->bindValue(':id', $cid, PDO::PARAM_INT);
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('adminip'), $nv_Lang->getModule('adminip_edit') . ' ID ' . $cid . ' -> ' . $keyname, $admin_info['userid']);
            } else {
                $stmt = $db->prepare('DELETE FROM ' . NV_AUTHORS_GLOBALTABLE . '_config WHERE keyname = :keyname');
                $stmt->bindValue(':keyname', $keyname, PDO::PARAM_STR);
                $result = $stmt->execute();
                if ($result) {
                    $sth = $db->prepare('INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . '_config (keyname, mask, begintime, endtime, notice) VALUES (:keyname, :mask, :begintime, :endtime, :notice)');
                    $sth->bindValue(':keyname', $keyname, PDO::PARAM_STR);
                    $sth->bindValue(':mask', $ipmask, PDO::PARAM_STR);
                    $sth->bindValue(':begintime', $begintime, PDO::PARAM_INT);
                    $sth->bindValue(':endtime', $endtime, PDO::PARAM_INT);
                    $sth->bindValue(':notice', $notice, PDO::PARAM_STR);
                    $sth->execute();

                    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('adminip'), $nv_Lang->getModule('adminip_add') . ' ' . $keyname, $admin_info['userid']);
                }
            }
            nv_save_file_admin_config();
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
        }
    }
} else {
    if (!empty($cid)) {
        $stmt = $db->prepare("SELECT id, keyname, mask, begintime, endtime, notice FROM " . NV_AUTHORS_GLOBALTABLE . "_config WHERE mask != '-1' AND id = :id");
        $stmt->bindValue(':id', $cid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            $id = $row['id'];
            $keyname = $row['keyname'];
            $mask = $row['mask'];
            $begintime = $row['begintime'];
            $endtime = $row['endtime'];
            $notice = $row['notice'];
        }
        $stmt->closeCursor();
        $nv_Lang->setModule('adminip_add', $nv_Lang->getModule('adminip_edit'));
        if ($ips->isIp4($keyname)) {
            $ip_version = 4;
            $mask6 = 128;
        } else {
            $ip_version = 6;
            $mask6 = $mask;
            $mask = '';
        }
    } else {
        $id = $keyname = $mask = $notice = '';
        $ip_version = 4;
        $mask6 = 128;
        $begintime = $endtime = 0;
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config.tpl'));
$tpl->assign('LANG', $nv_Lang);

$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('ERROR_USER', implode('<br/>', $error_user));
$tpl->assign('ERROR_IP', implode('<br/>', $error_ip));

// Lấy danh sách tài khoản tường lửa
$firewalls = [];
$sql = 'SELECT id, keyname, begintime, endtime FROM ' . NV_AUTHORS_GLOBALTABLE . "_config WHERE mask = '-1' ORDER BY keyname DESC";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $firewalls[] = [
        'uid' => $row['id'],
        'keyname' => $row['keyname'],
        'dbbegintime' => !empty($row['begintime']) ? nv_date_format(1, $row['begintime']) : '',
        'dbendtime' => !empty($row['endtime']) ? nv_date_format(1, $row['endtime']) : $nv_Lang->getModule('adminip_nolimit'),
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;uid=' . $row['id'],
        'checkss' => csrf_create($csrf_key . '_' . $row['id'])
    ];
}
$result->closeCursor();
$tpl->assign('FIREWALLS', $firewalls);

if (!empty($uid)) {
    $stmt = $db->prepare("SELECT keyname, begintime, endtime FROM " . NV_AUTHORS_GLOBALTABLE . "_config WHERE mask = '-1' AND id = :id");
    $stmt->bindValue(':id', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        $username = $row['keyname'];
        $begintime1 = $row['begintime'];
        $endtime1 = $row['endtime'];
    }
    $stmt->closeCursor();

    $nv_Lang->setModule('username_add', $nv_Lang->getModule('username_edit'));
    $password2 = $password = '';
}

$tpl->assign('FIREWALLDATA', [
    'uid' => $uid,
    'username' => $username,
    'password' => $password,
    'password2' => $password2,
    'begintime1' => nv_u2d_post($begintime1),
    'endtime1' => nv_u2d_post($endtime1)
]);

// Danh sách IP truy cập khu vực quản trị
$mask_text_array = [];
$mask_text_array[0] = '255.255.255.255';
$mask_text_array[3] = '255.255.255.xxx';
$mask_text_array[2] = '255.255.xxx.xxx';
$mask_text_array[1] = '255.xxx.xxx.xxx';

$sql = 'SELECT id, keyname, mask, begintime, endtime FROM ' . NV_AUTHORS_GLOBALTABLE . "_config WHERE mask!='-1' ORDER BY keyname DESC";
$result = $db->query($sql);

$ipaccess = [];
while ($row = $result->fetch()) {
    $ipaccess[] = [
        'id' => $row['id'],
        'keyname' => $row['keyname'],
        'mask_text_array' => $ips->isIp4($row['keyname']) ? $mask_text_array[$row['mask']] : ('/' . $row['mask']),
        'dbbegintime' => nv_date_format(1, $row['begintime']),
        'dbendtime' => nv_date_format(1, $row['endtime']),
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $row['id'],
        'checkss' => csrf_create($csrf_key . '_' . $row['id'])
    ];
}
$result->closeCursor();
$tpl->assign('IPACCESS', $ipaccess);

$tpl->assign('IPDATA', [
    'cid' => $cid,
    'keyname' => $keyname,
    'mask' => $mask,
    'mask6' => $mask6,
    'begintime' => nv_u2d_post($begintime),
    'endtime' => nv_u2d_post($endtime),
    'notice' => $notice,
    'ip_version' => $ip_version,
]);
$tpl->assign('MASK_TEXT_ARRAY', $mask_text_array);
$tpl->assign('IPTYPES', $array_iptypes);

$contents = $tpl->fetch('config.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
