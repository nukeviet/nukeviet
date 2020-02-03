<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE') or !defined('NV_ADMIN')) {
    die('Stop!!!');
}

/**
 * @param array $array
 * @param array $cfg_2step
 * @param array $admin_pre_data
 * @param string $error
 * @return string
 */
function nv_admin_login_theme($array, $cfg_2step, $admin_pre_data, $error = '')
{
    global $global_config, $nv_Lang, $language_array;

    if (!file_exists(NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/login.tpl')) {
        trigger_error('Login template error!!!', 256);
    }

    $xtpl = new XTemplate('login.tpl', NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system');
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('CHARSET', $global_config['site_charset']);
    $xtpl->assign('SITE_NAME', $global_config['site_name']);
    $xtpl->assign('ADMIN_THEME', $global_config['admin_theme']);
    $xtpl->assign('SITELANG', NV_LANG_INTERFACE);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
    $xtpl->assign('CHECK_SC', ($global_config['gfx_chk'] == 1) ? 1 : 0);
    $xtpl->assign('SITEURL', $global_config['site_url']);
    $xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
    $xtpl->assign('NV_TITLEBAR_DEFIS', NV_TITLEBAR_DEFIS);
    $xtpl->assign('LOGIN_ERROR_SECURITY', addslashes($nv_Lang->getGlobal('login_error_security', NV_GFX_NUM)));
    $xtpl->assign('LANGINTERFACE', $nv_Lang->getGlobal('langinterface'));

    // Logo của site
    if (!empty($global_config['site_logo'])) {
        $xtpl->assign('LOGO', NV_BASE_SITEURL . $global_config['site_logo']);
        $xtpl->parse('main.logo');
    }

    if (empty($admin_pre_data)) {
        // Form đăng nhập bằng tài khoản (bước 1)
        $xtpl->assign('ADMIN_LOGIN_TITLE', $nv_Lang->getGlobal('adminlogin'));
        $xtpl->assign('LANGLOSTPASS', $nv_Lang->getGlobal('lostpass'));
        $xtpl->assign('LINKLOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass');
        $xtpl->assign('V_LOGIN', $array['username']);
        $xtpl->assign('V_PASSWORD', $array['password']);

        // Đa ngôn ngữ giao diện admin
        if ($global_config['lang_multi'] == 1) {
            $_language_array = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/');
            foreach ($_language_array as $lang_i) {
                if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php') and file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/admin_global.php')) {
                    $xtpl->assign('LANGOP', NV_BASE_ADMINURL . 'index.php?langinterface=' . $lang_i);
                    $xtpl->assign('LANGTITLE', $nv_Lang->getGlobal('langinterface'));
                    $xtpl->assign('SELECTED', ($lang_i == NV_LANG_INTERFACE) ? "selected='selected'" : "");
                    $xtpl->assign('LANGVALUE', $language_array[$lang_i]['name']);
                    $xtpl->parse('main.pre_form.lang_multi.option');
                }
            }
            $xtpl->parse('main.pre_form.lang_multi');
        }

        // Kích hoạt mã xác nhận
        if ($global_config['gfx_chk']) {
            if ($global_config['captcha_type'] == 2) {
                $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode1'));
                $xtpl->assign('RECAPTCHA_SITEKEY', $global_config['recaptcha_sitekey']);
                $xtpl->assign('RECAPTCHA_TYPE', $global_config['recaptcha_type']);
                $xtpl->parse('main.pre_form.recaptcha');
            } else {
                $xtpl->assign('CAPTCHA_REFRESH', $nv_Lang->getGlobal('captcharefresh'));
                $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/refresh.png');
                $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode'));
                $xtpl->assign('GFX_NUM', NV_GFX_NUM);
                $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
                $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
                $xtpl->parse('main.pre_form.captcha');
            }
        }

        $xtpl->parse('main.pre_form');
    } else {
        // Form xác thực hai bước
        $xtpl->assign('ADMIN_PRE_LOGOUT', NV_BASE_ADMINURL . 'index.php?pre_logout=1&amp;checkss=' . NV_CHECK_SESSION);
        $xtpl->assign('ADMIN_LOGIN_TITLE', $nv_Lang->getGlobal('2teplogin'));
        $xtpl->assign('ADMIN_2STEP_HELLO', $nv_Lang->getGlobal('admin_hello_2step', $admin_pre_data['full_name']));

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
                        $xtpl->parse('main.2step_form.must_activate.loop.popup');
                    }
                    $xtpl->parse('main.2step_form.must_activate.loop');
                }
            }
            $xtpl->parse('main.2step_form.must_activate');
        } else {
            // Xuất các phương thức để xác thực
            $html = [];
            foreach ($cfg_2step['opts'] as $opt) {
                if ($cfg_2step['active_' . $opt]) {
                    if ($opt == 'code') {
                        if (!empty($array['backupcodepin'])) {
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
                $xtpl->parse('main.2step_form.choose_method.others');
            }
            $xtpl->parse('main.2step_form.choose_method');
        }

        $xtpl->parse('main.2step_form');
    }

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    } elseif (empty($admin_pre_data)) {
        $xtpl->parse('main.info');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
