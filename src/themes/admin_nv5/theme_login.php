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

    $global_config['site_name'] = empty($global_config['site_name']) ? NV_SERVER_NAME : $global_config['site_name'];

    $site_favicon = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }

    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('DATA', $array);
    $tpl->assign('ERROR', $error);

    $tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $tpl->assign('NV_TITLEBAR_DEFIS', NV_TITLEBAR_DEFIS);
    $tpl->assign('SITE_CHARSET', $global_config['site_charset']);
    $tpl->assign('NV_SITE_COPYRIGHT', $global_config['site_name'] . ' [' . $global_config['site_email'] . '] ');
    $tpl->assign('NV_SITE_NAME', $global_config['site_name']);
    $tpl->assign('SITE_DESCRIPTION', empty($global_config['site_description']) ? $global_config['site_name'] : $global_config['site_description']);
    $tpl->assign('SITE_FAVICON', $site_favicon);
    $tpl->assign('NV_ADMIN_THEME', $global_config['admin_theme']);

    $tpl->assign('NV_SITE_TIMEZONE_OFFSET', round(NV_SITE_TIMEZONE_OFFSET / 3600));
    $tpl->assign('NV_CURRENTTIME', NV_CURRENTTIME);
    $tpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
    $tpl->assign('NV_CHECK_PASS_MSTIME', (intval($global_config['admin_check_pass_time']) - 62) * 1000);
    $tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);
    $tpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);

    $tpl->assign('LINKLOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass');
    $tpl->assign('GFX_NUM', NV_GFX_NUM);
    $tpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
    $tpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
    $tpl->assign('RECAPTCHA_SITEKEY', $global_config['recaptcha_sitekey']);
    $tpl->assign('RECAPTCHA_TYPE', $global_config['recaptcha_type']);

    // Xử lý logo site
    $site_logo = empty($global_config['site_logo']) ? ('themes/' . $global_config['admin_theme'] . '/images/logo.png') : $global_config['site_logo'];
    $tpl->assign('NV_SITE_LOGO', $site_logo);

    // Chọn ngôn ngữ giao diện
    $lang_multi = [];

    if ($global_config['lang_multi'] == 1) {
        $_language_array = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/');
        foreach ($_language_array as $lang_i) {
            if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php') and file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/admin_global.php') and $lang_i != NV_LANG_INTERFACE) {
                $lang_multi[] = [
                    'link' => NV_BASE_ADMINURL . 'index.php?langinterface=' . $lang_i,
                    'title' => $language_array[$lang_i]['name']
                ];
            }
        }
    }

    $tpl->assign('LANG_MULTI', $lang_multi);
    $tpl->assign('LANG_CURRENT_NAME', $language_array[NV_LANG_INTERFACE]['name']);

    return $tpl->fetch('login.tpl');
}
