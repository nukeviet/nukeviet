<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_SPADMIN')) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$admin_id = $nv_Request->get_int('admin_id', 'get', 0);
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_id);

if (empty($admin_id) or $admin_id == $admin_info['admin_id']) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $admin_id;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if ($row['lev'] == 1 or (!defined('NV_IS_GODADMIN') and $row['lev'] == 2)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$row_user = $db->query('SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id)->fetch();
$susp_reason = [];
$last_reason = [];

if (!empty($row['susp_reason'])) {
    $susp_reason = unserialize($row['susp_reason']);
    $last_reason = (!empty($susp_reason)) ? $susp_reason[0] : '';
}

$old_suspend = ($row['is_suspend'] or empty($row_user['active'])) ? 1 : 0;

if (empty($old_suspend)) {
    $allow_change = true;
} else {
    $allow_change = (defined('NV_IS_GODADMIN')) ? true : ((defined('NV_IS_SPADMIN') and $global_config['spadmin_add_admin'] == 1) ? true : false);
}

$new_suspend = ($old_suspend) ? 0 : 1;

if ($allow_change and $nv_Request->get_int('save', 'post', 0)) {
    if ($checkss != $nv_Request->get_string('checkss', 'post')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'SESSION error'
        ]);
    }

    $new_reason = (!empty($new_suspend)) ? $nv_Request->get_title('new_reason', 'post', '', 1) : '';
    if (!empty($new_suspend) and empty($new_reason)) {
        nv_jsonOutput([
            'input' => 'new_reason',
            'status' => 'error',
            'mess' => $nv_Lang->getModule('susp_reason_empty')
        ]);
    }

    $sendmail = (int) $nv_Request->get_bool('sendmail', 'post', false);
    $clean_history = defined('NV_IS_GODADMIN') ? (int) $nv_Request->get_bool('clean_history', 'post', false) : 0;

    if ($new_suspend) {
        if ($clean_history) {
            $susp_reason = [];
            $susp_reason[] = [
                'starttime' => NV_CURRENTTIME,
                'endtime' => 0,
                'start_admin' => $admin_info['admin_id'],
                'end_admin' => '',
                'info' => $new_reason
            ];
        } else {
            array_unshift($susp_reason, [
                'starttime' => NV_CURRENTTIME,
                'endtime' => 0,
                'start_admin' => $admin_info['admin_id'],
                'end_admin' => '',
                'info' => $new_reason
            ]);
        }
    } else {
        if ($clean_history) {
            $susp_reason = [];
        } else {
            $susp_reason[0] = [
                'starttime' => $last_reason['starttime'],
                'endtime' => NV_CURRENTTIME,
                'start_admin' => $last_reason['start_admin'],
                'end_admin' => $admin_info['admin_id'],
                'info' => $last_reason['info']
            ];
        }
    }
    $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . ' SET edittime=' . NV_CURRENTTIME . ', is_suspend=' . $new_suspend . ', susp_reason= :susp_reason WHERE admin_id=' . $admin_id);
    $sth->bindValue(':susp_reason', serialize($susp_reason), PDO::PARAM_STR);
    if ($sth->execute()) {
        if (empty($row_user['active'])) {
            $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET active= 1 WHERE userid=' . $admin_id);
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('suspend' . $new_suspend) . ' ', ' Username : ' . $row_user['username'], $admin_info['userid']);
        if (!empty($sendmail)) {
            $maillang = NV_LANG_INTERFACE;
            if (!empty($row_user['language']) and in_array($row_user['language'], $global_config['setup_langs'], true)) {
                if ($row_user['language'] != NV_LANG_INTERFACE) {
                    $maillang = $row_user['language'];
                }
            } elseif (NV_LANG_DATA != NV_LANG_INTERFACE) {
                $maillang = NV_LANG_DATA;
            }

            $send_data = [[
                'to' => $row_user['email'],
                'data' => [
                    'lang' => $maillang,
                    'time' => NV_CURRENTTIME,
                    'note' => $new_suspend ? $new_reason : $last_reason['info'],
                    'email' => $admin_info['view_mail'] ? $admin_info['email'] : $global_config['site_email'],
                    'sig' => (!empty($admin_info['sig']) ? $admin_info['sig'] : 'All the best'),
                    'position' => $admin_info['position'],
                    'username' => $admin_info['username']
                ]
            ]];
            nv_sendmail_template_async($new_suspend ? NukeViet\Template\Email\Tpl::E_AUTHOR_SUSPEND : NukeViet\Template\Email\Tpl::E_AUTHOR_REACTIVE, $send_data, $maillang);
        }
        nv_jsonOutput([
            'status' => 'OK',
            'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name
        ]);
    }

    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'DB error'
    ]);
}

if (!empty($susp_reason)) {
    $ads = [];
    foreach ($susp_reason as $vals) {
        $ads[] = (int) $vals['start_admin'];
        if (!empty($vals['end_admin'])) {
            $ads[] = (int) $vals['end_admin'];
        }
    }

    $ads = array_unique($ads);
    $ads = implode(',', $ads);
    $result2 = $db->query('SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . $ads . ')');
    $ads = [];
    $ads[0] = $nv_Lang->getGlobal('system');
    while ($row2 = $result2->fetch()) {
        $ads[$row2['userid']] = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;id=' . $row2['userid'] . '">' . $row2['first_name'] . '</a>';
    }
    $result2->closeCursor();
}

$page_title = $nv_Lang->getModule('nv_admin_chg_suspend', $row_user['username']);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('suspend.tpl'));
$tpl->registerPlugin('modifier', 'datetime_format', 'nv_datetime_format');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('OP', $op);
$tpl->assign('MODULE_NAME', $module_name);

$tpl->assign('OLD_SUSPEND', $old_suspend);
$tpl->assign('NEW_SUSPEND', $new_suspend);
$tpl->assign('CHECKSS', $checkss);
$tpl->assign('SUSP_REASON', $susp_reason);
$tpl->assign('USER', $row_user);
$tpl->assign('ALLOW_CHANGE', $allow_change);
$tpl->assign('ADMINS', $ads);

$contents = $tpl->fetch('suspend.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
