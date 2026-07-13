<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use NukeViet\Module\users\Shared\Emails;

$userid = $nv_Request->get_int('userid', 'get', 0);

$stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $nv_Lang->getModule('user_2step_of') . ' ' . $row['username'];

$allow = false;

$stmt = $db->prepare('SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = :userid');
$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$rowlev = $stmt->fetch();
$stmt->closeCursor();

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
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('user_safemode.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $contents = $tpl->fetch('user_safemode.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Thêm vào menutop
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $row['userid']] = $nv_Lang->getModule('edit_title');
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_oauth&amp;userid=' . $row['userid']] = $nv_Lang->getModule('user_openid_mamager');

$codes = [];

if (!empty($row['active2step'])) {
    // Tắt xác thực hai bước
    if ($nv_Request->isset_request('turnoff2step', 'post')) {
        if (!defined('NV_IS_AJAX')) {
            exit('Wrong URL');
        }

        if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }


        $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_backupcodes WHERE userid = :userid');
        $stmt->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_passkey WHERE userid = :userid AND enable_login = 0');
        $stmt->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . " SET active2step = 0, secretkey = '', last_update = :last_update WHERE userid = :userid");
        $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
        $stmt->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
        $stmt->execute();

        nv_delete_notification(NV_LANG_DATA, $module_name, 'remove_2step_request', $row['userid']);

        // Gửi email thông báo
        if (!empty($global_users_config['admin_email'])) {
            $maillang = NV_LANG_INTERFACE;
            if (!empty($row['language']) and in_array($row['language'], $global_config['setup_langs'], true)) {
                if ($row['language'] != NV_LANG_INTERFACE) {
                    $maillang = $row['language'];
                }
            } elseif (NV_LANG_DATA != NV_LANG_INTERFACE) {
                $maillang = NV_LANG_DATA;
            }

            $send_data = [[
                'to' => $row['email'],
                'data' => [
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'gender' => $row['gender'],
                    'lang' => $maillang,
                    'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . NV_2STEP_VERIFICATION_MODULE, NV_MY_DOMAIN)
                ]
            ]];
            nv_sendmail_template_async([$module_name, Emails::OFF2S_BY_ADMIN], $send_data, $maillang);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_turnoff_user2step', 'userid ' . $row['userid'], $admin_info['userid']);
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getModule('user_2step_turnoff')
        ]);
    }

    // Tạo lại mã dự phòng
    if ($nv_Request->isset_request('resetbackupcodes', 'post')) {
        if (!defined('NV_IS_AJAX')) {
            exit('Wrong URL');
        }

        if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }


        $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_backupcodes WHERE userid = :userid');
        $stmt->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();

        $new_code = [];
        while (count($new_code) < 10) {
            $code = nv_strtolower(nv_genpass(8, 0));
            if (!in_array($code, $new_code, true)) {
                $new_code[] = $code;
            }
        }

        $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_backupcodes (userid, code, is_used, time_used, time_creat) VALUES (:userid, :code, 0, 0, :time_creat)');
        foreach ($new_code as $code) {
            $stmt->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt->bindValue(':code', $crypt->encryptDeterministic($code), PDO::PARAM_STR);
            $stmt->bindValue(':time_creat', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
        }

        if ($nv_Request->get_int('sendmail', 'post', 0) == 1) {
            $maillang = NV_LANG_INTERFACE;
            if (!empty($row['language']) and in_array($row['language'], $global_config['setup_langs'], true)) {
                if ($row['language'] != NV_LANG_INTERFACE) {
                    $maillang = $row['language'];
                }
            } elseif (NV_LANG_DATA != NV_LANG_INTERFACE) {
                $maillang = NV_LANG_DATA;
            }

            $send_data = [[
                'to' => $row['email'],
                'data' => [
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'gender' => $row['gender'],
                    'new_code' => $new_code,
                    'lang' => $maillang
                ]
            ]];
            nv_sendmail_template_async([$module_name, Emails::NEW_2STEP_CODE], $send_data, $maillang);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_reset_user2step_codes', 'userid ' . $row['userid'], $admin_info['userid']);
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getModule('user_2step_reset')
        ]);
    }

    $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_backupcodes WHERE userid = :userid');
    $stmt->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
    $stmt->execute();
    while ($code = $stmt->fetch()) {
        $code['code'] = $crypt->decryptDeterministic($code['code']);
        $code['status_label'] = $nv_Lang->getModule('user_2step_codes_s' . $code['is_used']);
        $codes[] = $code;
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('user_2step.tpl'));
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ROW', $row);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('CODES', $codes);
$contents = $tpl->fetch('user_2step.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
