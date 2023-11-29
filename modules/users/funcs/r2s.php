<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

if (defined('NV_IS_USER')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if (!$nv_Request->isset_request('cant_do_2step', 'session')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$uinfo = $nv_Request->get_title('cant_do_2step', 'session', '');
unset($matches);
if (!preg_match('/^([1-9][0-9]*)\.([1-9][0-9]{9,10})\.(\d)\.([a-zA-Z0-9]*)$/', $uinfo, $matches)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$uid = (int) $matches[1];
$createtime = (int) $matches[2];
$count = (int) $matches[3];
$vkey = $matches[4];

// Session đến trang xóa xác thực 2 bước chỉ giới hạn trong vòng 6 tiếng
if (NV_CURRENTTIME - $createtime > 21600) {
    $nv_Request->unset_request('cant_do_2step', 'session');
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Số lần khai báo sai không quá 5
if ($count > 5) {
    $nv_Request->unset_request('cant_do_2step', 'session');
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$row = $db->query('SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
if (empty($row['userid'])) {
    $nv_Request->unset_request('cant_do_2step', 'session');
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();

    if (!empty($nv_redirect)) {
        $page_url .= '&nv_redirect=' . $nv_redirect;
    }
}

$array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
$gfx_chk = (!empty($array_gfx_chk) and in_array('s', $array_gfx_chk, true)) ? 1 : 0;
$data = [];
$data['checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op);
$data['question'] = !empty($row['question']) ? $row['question'] : '';

if ($nv_Request->isset_request('checkss', 'post')) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if ($checkss != $data['checkss']) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $useremail = $nv_Request->get_title('useremail', 'post', '');
    $useranswer = $nv_Request->get_title('useranswer', 'post', '');
    $email_sent = $nv_Request->get_bool('email_sent', 'post', false);
    $verifykey = $nv_Request->get_title('verifykey', 'post', '');

    $check_email = nv_check_valid_email($useremail, true);
    if (!empty($check_email[0])) {
        ++$count;
        $return = [
            'status' => 'error',
            'input' => 'useremail',
            'mess' => $check_email[0]
        ];
        if ($count >= 5) {
            $nv_Request->unset_request('cant_do_2step', 'session');
            $return['mess'] = $nv_Lang->getModule('remove_2step_error5');
            $return['redirect'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . (!empty($nv_redirect) ? '&nv_redirect=' . $nv_redirect : ''), true);
        } else {
            $nv_Request->set_Session('cant_do_2step', $uid . '.' . $createtime . '.' . $count . '.');
        }

        nv_jsonOutput($return);
    }

    $useremail = $check_email[1];
    if ($useremail != $row['email']) {
        ++$count;
        $return = [
            'status' => 'error',
            'input' => 'useremail',
            'mess' => $nv_Lang->getModule('remove_2step_email_error')
        ];
        if ($count >= 5) {
            $nv_Request->unset_request('cant_do_2step', 'session');
            $return['mess'] = $nv_Lang->getModule('remove_2step_error5');
            $return['redirect'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . (!empty($nv_redirect) ? '&nv_redirect=' . $nv_redirect : ''), true);
        } else {
            $nv_Request->set_Session('cant_do_2step', $uid . '.' . $createtime . '.' . $count . '.');
        }

        nv_jsonOutput($return);
    }

    if (!empty($row['question'])) {
        if ($useranswer != $row['answer']) {
            ++$count;
            $return = [
                'status' => 'error',
                'input' => 'useranswer',
                'mess' => $nv_Lang->getModule('answer_failed')
            ];
            if ($count >= 5) {
                $nv_Request->unset_request('cant_do_2step', 'session');
                $return['mess'] = $nv_Lang->getModule('remove_2step_error5');
                $return['redirect'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . (!empty($nv_redirect) ? '&nv_redirect=' . $nv_redirect : ''), true);
            } else {
                $nv_Request->set_Session('cant_do_2step', $uid . '.' . $createtime . '.' . $count . '.');
            }

            nv_jsonOutput($return);
        }
    }

    if (!$email_sent) {
        $key = strtoupper(nv_genpass(10));
        $nv_Request->set_Session('cant_do_2step', $uid . '.' . $createtime . '.' . $count . '.' . $key);
        $message = $nv_Lang->getModule('remove_2step_verifykey_content', $row['username'], $global_config['site_name'], $key);
        @nv_sendmail_async([
            $global_config['site_name'],
            $global_config['site_email']
        ], $row['email'], $nv_Lang->getModule('remove_2step_verifykey_subject'), $message);
        nv_jsonOutput([
            'status' => 'step2',
            'mess' => $nv_Lang->getModule('verifykey_info')
        ]);
    }

    if (empty($verifykey) or $verifykey != $vkey) {
        $nv_Request->unset_request('cant_do_2step', 'session');
        nv_jsonOutput([
            'status' => 'failed',
            'mess' => $nv_Lang->getModule('verifykey_error'),
            'redirect' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . (!empty($nv_redirect) ? '&nv_redirect=' . $nv_redirect : ''), true)
        ]);
    }

    $nv_Request->unset_request('cant_do_2step', 'session');

    if (empty($global_config['remove_2step_method']) or empty($row['question'])) {
        // Thêm thông báo vào hệ thống
        $access_admin = unserialize($global_users_config['access_admin']);
        if (isset($access_admin['access_editus'])) {
            for ($i = 1; $i <= 3; ++$i) {
                if (!empty($access_admin['access_editus'][$i])) {
                    $admin_view_allowed = $i == 3 ? 0 : $i;
                    nv_insert_notification($module_name, 'remove_2step_request', [
                        'title' => $row['username'],
                        'uid' => $row['userid']
                    ], $uid, 0, 0, 1, $admin_view_allowed, 1);
                }
            }
        }

        $info = $nv_Lang->getModule('remove_2step_send');
    } else {
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_backupcodes WHERE userid=' . $uid);
        $db->query('UPDATE ' . NV_MOD_TABLE . " SET active2step=0, secretkey='', last_update=" . NV_CURRENTTIME . ' WHERE userid=' . $uid);

        $message = $nv_Lang->getModule('remove_2step_content', $row['username'], $global_config['site_name']);
        @nv_sendmail_async([
            $global_config['site_name'],
            $global_config['site_email']
        ], $row['email'], $nv_Lang->getModule('remove_2step_subject'), $message);

        $info = $nv_Lang->getModule('remove_2step_success');
    }

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $info,
        'redirect' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . (!empty($nv_redirect) ? '&nv_redirect=' . $nv_redirect : ''), true)
    ]);
}

$page_title = $nv_Lang->getModule('remove_2step_method_title');
$key_words = $module_info['keywords'];

$canonicalUrl = getCanonicalUrl($page_url);

$contents = user_r2s($data, $page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
