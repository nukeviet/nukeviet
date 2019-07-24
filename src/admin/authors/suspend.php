<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:21
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    die('Stop!!!');
}

if (!defined('NV_IS_SPADMIN')) {
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

$array = [];
$new_suspend = 0;
if ($allow_change) {
    $new_suspend = ($old_suspend) ? 0 : 1;

    $error = '';
    if ($nv_Request->get_int('save', 'post', 0)) {
        $new_reason = (!empty($new_suspend)) ? $nv_Request->get_title('new_reason', 'post', '', 1) : '';
        $sendmail = $nv_Request->get_int('sendmail', 'post', 0);
        $clean_history = defined('NV_IS_GODADMIN') ? $nv_Request->get_int('clean_history', 'post', 0) : 0;

        if (!empty($new_suspend) and empty($new_reason)) {
            $error = sprintf($nv_Lang->getModule('susp_reason_empty'), $row_user['username']);
        } else {
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
                    $send_data = [[
                        'to' => [$row_user['email']],
                        'data' => [
                            $new_suspend,
                            $admin_info,
                            $global_config,
                            $new_reason,
                            $last_reason
                        ]
                    ]];
                    $send = nv_sendmail_from_template(NukeViet\Template\Email\Tpl::E_AUTHOR_SUSPEND, $send_data);
                    if (!$send) {
                        $page_title = $nv_Lang->getGlobal('error_info_caption');
                        $url_back = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
                        $contents = nv_theme_alert($page_title, $nv_Lang->getGlobal('error_sendmail_admin'), 'danger', $url_back, '', 10);

                        include NV_ROOTDIR . '/includes/header.php';
                        echo nv_admin_theme($contents);
                        include NV_ROOTDIR . '/includes/footer.php';
                    }
                }
            }
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=suspend&admin_id=' . $admin_id);
        }
    } else {
        $adminpass = $new_reason = '';
        $clean_history = $sendmail = 0;
    }

    $array['error'] = $error;
    $array['new_suspend_action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=suspend&amp;admin_id=' . $admin_id;
    $array['sendmail'] = $sendmail;

    if (!empty($new_suspend)) {
        $array['new_reason'] = $new_reason;
    }
    if (defined('NV_IS_GODADMIN')) {
        if (($new_suspend and !empty($susp_reason)) or (empty($new_suspend) and sizeof($susp_reason) >= 1)) {
            $array['clean_history'] = $clean_history;
        }
    }
}

$array_suspended = [];
if (!empty($susp_reason)) {
    $inf = [];
    $ads = [];

    foreach ($susp_reason as $vals) {
        $ads[] = $vals['start_admin'];
        if (!empty($vals['end_admin'])) {
            $ads[] = $vals['end_admin'];
        }
    }

    $ads = array_unique($ads);
    $ads = "'" . implode("','", $ads) . "'";

    $result2 = $db->query('SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . $ads . ')');

    $ads = [];
    while ($row2 = $result2->fetch()) {
        $ads[$row2['userid']] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;admin_id=" . $row2['userid'] . "\">" . $row2['first_name'] . "</a>";
    }
    $result2->closeCursor();

    foreach ($susp_reason as $vals) {
        $start = sprintf($nv_Lang->getModule('suspend_info'), nv_date('d/m/Y H:i', $vals['starttime']), $ads[$vals['start_admin']]);
        $end = '';
        if (!empty($vals['endtime'])) {
            $end = sprintf($nv_Lang->getModule('suspend_info'), nv_date('d/m/Y H:i', $vals['endtime']), $ads[$vals['end_admin']]);
        }
        $inf[] = [$start, $end, $vals['info']];
    }

    $array_suspended = $inf;
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('ARRAY_SUSPENDED', $array_suspended);
$tpl->assign('ADMIN', $row_user);
$tpl->assign('ALLOW_CHANGE', $allow_change);
$tpl->assign('IS_NEW_SUSPEND', $new_suspend);
$tpl->assign('DATA', $array);

$page_title = sprintf($nv_Lang->getModule('nv_admin_chg_suspend'), $row_user['username']);

$contents = $tpl->fetch('suspend.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
