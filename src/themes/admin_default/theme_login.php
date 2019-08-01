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
 * @param string $error
 * @return string
 */
function nv_admin_login_theme($array, $error = '')
{
    global $global_config, $nv_Lang, $language_array;

    if (!file_exists(NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/login.tpl')) {
        trigger_error('Login template error!!!', 256);
    }

    $info = (!empty($error)) ? '<div class="error">' . $error . '</div>' : '<div class="normal">' . $nv_Lang->get('adminlogininfo') . '</div>';
    $size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);

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
    $xtpl->assign('LOGIN_INFO', $info);
    $xtpl->assign('SITEURL', $global_config['site_url']);
    $xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
    $xtpl->assign('NV_TITLEBAR_DEFIS', NV_TITLEBAR_DEFIS);

    $xtpl->assign('LOGIN_ERROR_SECURITY', addslashes($nv_Lang->get('login_error_security', NV_GFX_NUM)));

    $xtpl->assign('V_LOGIN', $array['username']);
    $xtpl->assign('V_PASSWORD', $array['password']);
    $xtpl->assign('LANGINTERFACE', $nv_Lang->get('langinterface'));

    if ($array['login_step'] == 1) {
        $xtpl->assign('SHOW_STEP1', '');
        $xtpl->assign('SHOW_STEP2', ' hidden');
        $xtpl->assign('SHOW_LANG', '');
    } elseif ($array['login_step'] == 3) {
        $xtpl->assign('SHOW_STEP1', ' hidden');
        $xtpl->assign('SHOW_STEP2', ' hidden');
        $xtpl->assign('SHOW_LANG', ' hidden');
        $xtpl->assign('SHOW_SUBMIT', ' hidden');
        $xtpl->assign('SHOW_LOSTPASS', ' hidden');
    } else {
        $xtpl->assign('SHOW_STEP1', ' hidden');
        $xtpl->assign('SHOW_STEP2', '');
        $xtpl->assign('SHOW_LANG', '');
    }

    if (!empty($array['totppin']) or empty($array['backupcodepin'])) {
        $xtpl->assign('SHOW_OPT', '');
        $xtpl->assign('SHOW_CODE', ' class="hidden"');
    } else {
        $xtpl->assign('SHOW_OPT', ' class="hidden"');
        $xtpl->assign('SHOW_CODE', '');
    }

    if (isset($size[1])) {
        if ($size[0] > 490) {
            $size[1] = ceil(490 * $size[1] / $size[0]);
            $size[0] = 490;
        }
        $xtpl->assign('LOGO', NV_BASE_SITEURL . $global_config['site_logo']);
        $xtpl->assign('WIDTH', $size[0]);
        $xtpl->assign('HEIGHT', $size[1]);

        if (isset($size['mime']) and $size['mime'] == 'application/x-shockwave-flash') {
            $xtpl->parse('main.swf');
        } else {
            $xtpl->parse('main.image');
        }
    }

    $xtpl->assign('LANGLOSTPASS', $nv_Lang->get('lostpass'));
    $xtpl->assign('LINKLOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass');

    if ($array['captcha_require']) {
        if ($global_config['captcha_type'] == 2) {
            $xtpl->assign('N_CAPTCHA', $nv_Lang->get('securitycode1'));
            $xtpl->assign('RECAPTCHA_SITEKEY', $global_config['recaptcha_sitekey']);
            $xtpl->assign('RECAPTCHA_TYPE', $global_config['recaptcha_type']);
            $xtpl->parse('main.recaptcha');
        } else {
            $xtpl->assign('CAPTCHA_REFRESH', $nv_Lang->get('captcharefresh'));
            $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/refresh.png');
            $xtpl->assign('N_CAPTCHA', $nv_Lang->get('securitycode'));
            $xtpl->assign('GFX_NUM', NV_GFX_NUM);
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->parse('main.captcha');
        }
    }

    if ($global_config['lang_multi'] == 1) {
        $_language_array = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/');
        foreach ($_language_array as $lang_i) {
            if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php') and file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/admin_global.php')) {
                $xtpl->assign('LANGOP', NV_BASE_ADMINURL . 'index.php?langinterface=' . $lang_i);
                $xtpl->assign('LANGTITLE', $nv_Lang->get('langinterface'));
                $xtpl->assign('SELECTED', ($lang_i == NV_LANG_INTERFACE) ? "selected='selected'" : "");
                $xtpl->assign('LANGVALUE', $language_array[$lang_i]['name']);
                $xtpl->parse('main.lang_multi.option');
            }
        }
        $xtpl->parse('main.lang_multi');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}
