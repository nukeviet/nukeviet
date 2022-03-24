<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$userid = $nv_Request->get_int('userid', 'get', 0);

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $lang_module['user_2step_of'] . ' ' . $row['username'];

$allow = false;

$sql = 'SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
$rowlev = $db->query($sql)->fetch();
if (empty($rowlev)) {
    $allow = true;
} else {
    if ($admin_info['admin_id'] == $userid or $admin_info['level'] < $rowlev['lev']) {
        $allow = true;
    }
}

if ($global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite'] and $admin_info['admin_id'] != $userid) {
    $allow = false;
}

if (!$allow) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if ($admin_info['admin_id'] == $userid and $admin_info['safemode'] == 1) {
    $xtpl = new XTemplate('user_safemode.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('SAFEMODE_DEACT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/safeshow');
    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

// Thêm vào menutop
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $row['userid']] = $lang_module['edit_title'];
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_oauth&amp;userid=' . $row['userid']] = $lang_module['user_openid_mamager'];

$xtpl = new XTemplate('user_2step.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;userid=' . $row['userid']);

if (empty($row['active2step'])) {
    $xtpl->parse('turnoff');
    $contents = $xtpl->text('turnoff');
} else {
    if (!empty($global_config['two_step_verification'])) {
        $xtpl->parse('main.turnoff_info');
    }

    // Tắt xác thực hai bước
    if ($nv_Request->isset_request('turnoff2step', 'post')) {
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_backupcodes WHERE userid=' . $row['userid']);
        $db->query('UPDATE ' . NV_MOD_TABLE . " SET active2step=0, secretkey='' WHERE userid=" . $row['userid']);

        // Gửi email thông báo
        if (!empty($global_users_config['admin_email'])) {
            $url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . NV_2STEP_VERIFICATION_MODULE, true);
            $message = sprintf($lang_module['security_alert_2stepoff'], $row['username'], $url);
            nv_sendmail([
                $global_config['site_name'],
                $global_config['site_email']
            ], $row['email'], $lang_module['security_alert'], $message);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_turnoff_user2step', 'userid ' . $row['userid'], $admin_info['userid']);
        $nv_Cache->delMod($module_name);

        header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&userid=' . $row['userid']);
        exit();
    }

    // Tạo lại mã dự phòng
    if ($nv_Request->isset_request('resetbackupcodes', 'post')) {
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_backupcodes WHERE userid=' . $row['userid']);

        $new_code = [];
        while (sizeof($new_code) < 10) {
            $code = nv_strtolower(nv_genpass(8, 0));
            if (!in_array($code, $new_code, true)) {
                $new_code[] = $code;
            }
        }

        foreach ($new_code as $code) {
            $db->query('INSERT INTO ' . NV_MOD_TABLE . '_backupcodes (userid, code, is_used, time_used, time_creat) VALUES (
            ' . $row['userid'] . ', ' . $db->quote($code) . ', 0, 0, ' . NV_CURRENTTIME . ')');
        }

        if ($nv_Request->get_int('sendmail', 'post', 0) == 1) {
            $full_name = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
            $subject = $lang_module['user_2step_newcodes'];
            $message = sprintf($lang_module['user_2step_bodymail'], $full_name, $global_config['site_name'], implode('<br />', $new_code));
            @nv_sendmail([$global_config['site_name'], $global_config['site_email']], $row['email'], $subject, $message);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_reset_user2step_codes', 'userid ' . $row['userid'], $admin_info['userid']);
        $nv_Cache->delMod($module_name);

        header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&userid=' . $row['userid']);
        exit();
    }

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_backupcodes WHERE userid=' . $row['userid'];
    $result = $db->query($sql);
    while ($code = $result->fetch()) {
        $code['status'] = $lang_module['user_2step_codes_s' . $code['is_used']];
        $code['time_creat'] = $code['time_creat'] ? nv_date('H:i:s d/m/Y', $code['time_creat']) : '';
        $code['time_used'] = $code['time_used'] ? nv_date('H:i:s d/m/Y', $code['time_used']) : '';
        $xtpl->assign('CODE', $code);
        $xtpl->parse('main.code');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
