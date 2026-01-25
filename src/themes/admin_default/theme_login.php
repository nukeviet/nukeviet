<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$dir_tpl = get_tpl_dir($global_config['admin_theme'], NV_DEFAULT_ADMIN_THEME, '/system/login.tpl');

$xtpl = new XTemplate('login.tpl', NV_ROOTDIR . '/themes/' . $dir_tpl . '/system');
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('CHARSET', $global_config['site_charset']);
$xtpl->assign('SITE_NAME', $global_config['site_name']);
$xtpl->assign('ADMIN_THEME', $dir_tpl);
$xtpl->assign('SITELANG', NV_LANG_INTERFACE);
$xtpl->assign('CHECK_SC', $gfx_chk ? 1 : 0);
$xtpl->assign('SITEURL', $global_config['site_url']);
$xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
$xtpl->assign('LOGIN_ERROR_SECURITY', addslashes($nv_Lang->getGlobal('login_error_security', NV_GFX_NUM)));
$xtpl->assign('LANGINTERFACE', $nv_Lang->getGlobal('langinterface'));
$xtpl->assign('ADMIN_LOGIN_TITLE', empty($admin_pre_data) ? $nv_Lang->getGlobal('adminlogin') : $nv_Lang->getGlobal('2teplogin'));

if (empty($admin_pre_data)) {
    // Form đăng nhập bằng tài khoản (bước 1)
    $xtpl->assign('LANGLOSTPASS', $nv_Lang->getGlobal('lostpass'));
    $xtpl->assign('LINKLOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass');
    $xtpl->assign('V_LOGIN', $nv_username);
    $xtpl->assign('V_PASSWORD', $nv_password);

    // Kích hoạt mã xác nhận
    if ($gfx_chk) {
        if ($captcha_type == 'recaptcha') {
            $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode1'));
            $xtpl->assign('RECAPTCHA_SITEKEY', $global_config['recaptcha_sitekey']);
            $xtpl->assign('RECAPTCHA_TYPE', $global_config['recaptcha_type']);

            if ($global_config['recaptcha_ver'] == 2) {
                $xtpl->parse('pre_form.recaptcha.recaptcha2');
            } elseif ($global_config['recaptcha_ver'] == 3) {
                $xtpl->parse('pre_form.recaptcha.recaptcha3');
            }
            $xtpl->parse('pre_form.recaptcha');
        } elseif ($captcha_type == 'captcha') {
            $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode'));
            $xtpl->parse('pre_form.captcha');
        }
    }

    // Kiểm tra site có dùng SSL không
    if ($nv_Server->getOriginalProtocol() !== 'https') {
        $xtpl->parse('pre_form.warning_ssl');
    }

    $xtpl->parse('pre_form');
    $login_content = $xtpl->text('pre_form');
} else {
    // Form xác thực hai bước
    $xtpl->assign('ADMIN_PRE_LOGOUT', NV_BASE_ADMINURL . 'index.php?pre_logout=1&amp;checkss=' . NV_CHECK_SESSION);

    if (empty($cfg_2step['opts'])) {
        // Lỗi khi không có phương thức xác thực 2 bước nào
        $error = $nv_Lang->getGlobal('admin_noopts_2step');
    } elseif ($cfg_2step['count_active'] < 1) {
        // Yêu cầu kích hoạt tối thiểu 1 phương thức để xác thực
        $xtpl->assign('LANG_CHOOSE', $cfg_2step['count_opts'] > 1 ? $nv_Lang->getGlobal('admin_mactive_2step_choose1') : $nv_Lang->getGlobal('admin_mactive_2step_choose0'));

        foreach ($cfg_2step['opts'] as $opt) {
            if (!$cfg_2step['active_' . $opt]) {
                $xtpl->assign('BTN', [
                    'key' => $opt,
                    'title' => $nv_Lang->getGlobal('admin_2step_opt_' . $opt),
                    'link' => NV_BASE_ADMINURL . 'index.php?auth=' . $opt
                ]);
                if ($opt != 'code') {
                    $xtpl->parse('2step_form.must_activate.loop.popup');
                }
                $xtpl->parse('2step_form.must_activate.loop');
            }
        }
        $xtpl->parse('2step_form.must_activate');
    } else {
        // Xuất các phương thức để xác thực
        $html = [];
        foreach ($cfg_2step['opts'] as $opt) {
            if ($cfg_2step['active_' . $opt]) {
                if ($opt == 'code') {
                    if (!empty($nv_backupcodepin)) {
                        $xtpl->assign('SHOW_TOTPPIN', ' hidden');
                        $xtpl->assign('SHOW_BACKUPCODEPIN', '');
                    } else {
                        $xtpl->assign('SHOW_TOTPPIN', '');
                        $xtpl->assign('SHOW_BACKUPCODEPIN', ' hidden');
                    }
                } else {
                    $xtpl->assign('URL', NV_BASE_ADMINURL . 'index.php?auth=' . $opt);
                }

                $xtpl->parse($opt);
                $html[$opt] = $xtpl->text($opt);
            }
        }

        $key_default = isset($html[$cfg_2step['default']]) ? $cfg_2step['default'] : key($html);
        $xtpl->assign('HTML_DEFAULT', $html[$key_default]);
        unset($html[$key_default]);
        if (!empty($html)) {
            $xtpl->assign('HTML_OTHER', implode(PHP_EOL, $html));
            $xtpl->parse('2step_form.choose_method.others');
        }
        $xtpl->parse('2step_form.choose_method');
    }

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('2step_form.error');
    } else {
        $xtpl->assign('ADMIN_2STEP_HELLO', $nv_Lang->getGlobal('admin_hello_2step', $admin_pre_data['full_name']));
        $xtpl->parse('2step_form.hello');
    }

    $xtpl->parse('2step_form');
    $login_content = $xtpl->text('2step_form');
}

if ($global_config['passshow_button'] === 1) {
    $xtpl->parse('main.passshow_button');
}

// Logo của site
if (!empty($global_config['site_logo'])) {
    $xtpl->assign('LOGO', NV_BASE_SITEURL . $global_config['site_logo']);
    $xtpl->parse('main.logo');
}

// Đa ngôn ngữ giao diện admin
if ($global_config['lang_multi'] == 1) {
    $_language_array = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/');
    foreach ($_language_array as $lang_i) {
        if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php') and file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/admin_global.php')) {
            $xtpl->assign('LANGOP', NV_BASE_ADMINURL . 'index.php?langinterface=' . $lang_i);
            $xtpl->assign('LANGTITLE', $nv_Lang->getGlobal('langinterface'));
            $xtpl->assign('SELECTED', ($lang_i == NV_LANG_INTERFACE) ? "selected='selected'" : '');
            $xtpl->assign('LANGVALUE', $language_array[$lang_i]['name']);
            $xtpl->parse('main.lang_multi.option');
        }
    }
    $xtpl->parse('main.lang_multi');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');
return str_replace('[-CONTENT-]', $login_content, $contents);
