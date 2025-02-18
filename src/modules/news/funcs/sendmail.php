<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

use NukeViet\Module\news\Shared\Emails;

$alias_cat_url = $array_op[1];
$array_page = explode('-', $array_op[2]);
$id = (int) (end($array_page));
$catid = 0;
foreach ($global_array_cat as $catid_i => $array_cat_i) {
    if ($alias_cat_url == $array_cat_i['alias']) {
        $catid = $catid_i;
        break;
    }
}

if ($id > 0 and $catid > 0) {
    $sql = 'SELECT id, title, alias, hometext FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id =' . $id . ' AND status=1';
    $result = $db_slave->query($sql);
    [$id, $title, $alias, $hometext] = $result->fetch(3);
    if ($id > 0) {
        $checkss = $nv_Request->get_string('checkss', 'post', '');
        if ($checkss == md5($id . NV_CHECK_SESSION)) {
            $allowed_send = $db_slave->query('SELECT allowed_send FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id=' . $id)->fetchColumn();
            if ($allowed_send == 1) {
                $your_name = $your_email = '';
                if (defined('NV_IS_ADMIN')) {
                    $your_name = !empty($admin_info['full_name']) ? $admin_info['full_name'] : $admin_info['username'];
                    $your_email = $admin_info['email'];
                } elseif (defined('NV_IS_USER')) {
                    $your_name = !empty($user_info['full_name']) ? $user_info['full_name'] : $user_info['username'];
                    $your_email = $user_info['email'];
                }
                if ($nv_Request->isset_request('send', 'post')) {
                    unset($nv_seccode);
                    if ($module_captcha == 'recaptcha') {
                        // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
                        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
                    } elseif ($module_captcha == 'turnstile') {
                        // Xác định giá trị của captcha nhập vào nếu sử dụng Turnstile
                        $nv_seccode = $nv_Request->get_title('cf-turnstile-response', 'post', '');
                    } elseif ($module_captcha == 'captcha') {
                        // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
                        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
                    }

                    // Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
                    if (isset($nv_seccode) and !nv_capcha_txt($nv_seccode, $module_captcha)) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'input' => '',
                            'mess' => ($module_captcha == 'recaptcha') ? $nv_Lang->getGlobal('securitycodeincorrect1') : (($module_captcha == 'turnstile') ? $nv_Lang->getGlobal('securitycodeincorrect2') : $nv_Lang->getGlobal('securitycodeincorrect'))
                        ]);
                    }

                    $friend_email = $nv_Request->get_title('friend_email', 'post', '');
                    if (($friend_email_error = nv_check_valid_email($friend_email)) != '') {
                        nv_jsonOutput([
                            'status' => 'error',
                            'input' => 'friend_email',
                            'mess' => $friend_email_error
                        ]);
                    }

                    $your_name = $nv_Request->get_title('your_name', 'post', '');
                    $_t = str_replace('&#039;', "'", $your_name);
                    if (!preg_match('/^([\p{L}\p{Mn}\p{Pd}\'][\p{L}\p{Mn}\p{Pd}\',\s]*)*$/u', $_t)) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'input' => 'your_name',
                            'mess' => $nv_Lang->getModule('sendmail_err_name')
                        ]);
                    }

                    $data_permission_confirm = !empty($global_config['data_warning']) ? (int) $nv_Request->get_bool('data_permission_confirm', 'post', false) : -1;
                    $antispam_confirm = !empty($global_config['antispam_warning']) ? (int) $nv_Request->get_bool('antispam_confirm', 'post', false) : -1;
                    if ($data_permission_confirm === 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'input' => 'data_permission_confirm',
                            'mess' => $nv_Lang->getGlobal('data_warning_error')
                        ]);
                    }
                    if ($antispam_confirm === 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'input' => 'antispam_confirm',
                            'mess' => $nv_Lang->getGlobal('antispam_warning_error')
                        ]);
                    }

                    $difftimeout = 3600;
                    $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/news_logs';
                    $log_fileext = preg_match('/^[a-z]+$/i', NV_LOGS_EXT) ? NV_LOGS_EXT : 'log';
                    $pattern = '/^(.*)\.' . $log_fileext . '$/i';
                    $logs = nv_scandir($dir, $pattern);

                    if (!empty($logs)) {
                        foreach ($logs as $file) {
                            $vtime = filemtime($dir . '/' . $file);

                            if (!$vtime or $vtime <= NV_CURRENTTIME - $difftimeout) {
                                @unlink($dir . '/' . $file);
                            }
                        }
                    }

                    $logfile = 'sf' . $id . '_' . md5(NV_LANG_DATA . $global_config['sitekey'] . $friend_email) . '.' . $log_fileext;
                    if (file_exists($dir . '/' . $logfile)) {
                        $timeout = filemtime($dir . '/' . $logfile);
                        $timeout = ceil(($difftimeout - NV_CURRENTTIME + $timeout) / 60);
                        nv_jsonOutput([
                            'status' => 'OK',
                            'mess' => $nv_Lang->getModule('sendmail_limit_sendmail', $friend_email, $timeout)
                        ]);
                    }

                    $your_message = $nv_Request->get_title('your_message', 'post', '');
                    // Disable email engines from automatically hyperlinking a URL
                    !empty($your_message) && $your_message = nv_autoLinkDisable($your_message);

                    if (empty($hometext)) {
                        $hometext = $db_slave->query('SELECT bodyhtml FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id =' . $id)->fetchColumn();
                        $hometext = nv_clean60(strip_tags(str_replace(["\r\n", "\r", "\n"], ' ', $hometext)), 300);
                    }

                    $send_data = [[
                        'from' => !empty($your_email) ? [$your_name, $your_email] : [],
                        'to' => $friend_email,
                        'data' => [
                            'from_name' => $your_name,
                            'post_name' => $title,
                            'site_name' => $global_config['site_name'],
                            'message' => $your_message,
                            'hometext' => $hometext,
                            'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'], NV_MY_DOMAIN)
                        ]
                    ]];
                    $check = nv_sendmail_from_template([$module_file, Emails::SENDMAIL], $send_data, NV_LANG_INTERFACE);
                    if ($check) {
                        file_put_contents($dir . '/' . $logfile, '', LOCK_EX);
                        nv_jsonOutput([
                            'status' => 'OK',
                            'mess' => $nv_Lang->getModule('sendmail_success', $friend_email)
                        ]);
                    } else {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('sendmail_success_err')
                        ]);
                    }
                }

                $sendmail = [
                    'checkss' => md5($id . NV_CHECK_SESSION),
                    'your_name' => $your_name,
                    'your_email' => $your_email,
                    'action' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=sendmail/' . $global_array_cat[$catid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'], true)
                ];

                $contents = sendmail_themme($sendmail);
                nv_htmlOutput($contents);
            }
        }
    }
}
nv_redirect_location($global_config['site_url']);
