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

/*
 * Tệp này không bắt buộc trong giao diện nếu không có hệ thống lấy từ giao diện admin_default
 * Giữ tệp này, chỉnh sửa nó nếu muốn phát triển giao diện đăng nhập riêng
 */

use NukeViet\Client\Browser;

$dir_tpl = get_tpl_dir($global_config['admin_theme'], NV_DEFAULT_ADMIN_THEME, '/system/login.tpl');
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $dir_tpl . '/system');

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('PAGE_TITLE', $nv_Lang->getGlobal('admin_page'));
$tpl->assign('IS_IE', $browser->isBrowser(Browser::BROWSER_IE));
$tpl->assign('ADMIN_THEME', $dir_tpl);
$tpl->assign('PASSKEY_ALLOWED', $passkey_allowed);

// Icon site
$site_favicon = NV_BASE_SITEURL . 'favicon.ico';
if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
    $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
}
$tpl->assign('FAVICON', $site_favicon);

$whitelisted_attr = ['target'];
if (!empty($global_config['allowed_html_tags']) and in_array('iframe', $global_config['allowed_html_tags'])) {
    $whitelisted_attr[] = 'frameborder';
    $whitelisted_attr[] = 'allowfullscreen';
}
$tpl->assign('WHITELISTED_ATTR', "['" . implode("', '", $whitelisted_attr). "']");
$tpl->assign('JSDATE_GET', nv_region_config('jsdate_get'));
$tpl->assign('JSDATE_POST', nv_region_config('jsdate_post'));

$tpl->assign('PRE_DATA', $admin_pre_data);

if (!empty($global_config['lang_multi'])) {
    $_language_array = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/');
    $langs = [];
    foreach ($_language_array as $lang_i) {
        if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php') and file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/admin_global.php')) {
            $langs[] = [
                'lang' => $lang_i,
                'name' => $language_array[$lang_i]['name']
            ];
        }
    }
    $tpl->assign('LANGS', $langs);
}

// Đăng nhập bước 1
if (empty($admin_pre_data)) {
    $tpl->assign('V_LOGIN', $nv_username);
    $tpl->assign('V_PASSWORD', $nv_password);
    $tpl->assign('GFX_CHK', $gfx_chk);
    $tpl->assign('CAPTCHA_TYPE', $captcha_type);
    $tpl->assign('LOGIN_ERROR_SECURITY', addslashes($nv_Lang->getGlobal('login_error_security', NV_GFX_NUM)));
    $tpl->assign('SV', $nv_Server);
} else {
    $tpl->assign('CFG_2STEP', $cfg_2step);

    if (empty($cfg_2step['opts'])) {
        // Lỗi khi không có phương thức xác thực 2 bước nào
        $error = $nv_Lang->getGlobal('admin_noopts_2step');
    }
    $tpl->assign('ERROR', $error);

    // Lấy HTML các phương thức xác thực và sắp xếp nó
    if (!empty($cfg_2step['opts']) and $cfg_2step['count_active'] > 0) {
        $tpl->assign('BACKUP_CODE', $nv_backupcodepin);

        $html = [];
        foreach ($cfg_2step['opts'] as $opt) {
            if ($cfg_2step['active_' . $opt]) {
                $tpl->assign('OPT', $opt);
                $html[$opt] = $tpl->fetch('login_methods.tpl');
            }
        }

        $key_default = isset($html[$cfg_2step['default']]) ? $cfg_2step['default'] : key($html);
        $tpl->assign('HTML_DEFAULT', $html[$key_default]);
        unset($html[$key_default]);
        $tpl->assign('HTML_OTHER', empty($html) ? '' : implode(PHP_EOL, $html));
    }
}

$sitecontent = str_replace('[THEME_ERROR_INFO]', nv_error_info(), $tpl->fetch('login.tpl'));

if (!empty($my_head)) {
    $sitecontent = preg_replace('/(<\/head>)/i', $my_head . '\\1', $sitecontent, 1);
}
if (!empty($my_footer)) {
    $sitecontent = preg_replace('/(<\/body>)/i', $my_footer . '\\1', $sitecontent, 1);
}

return $sitecontent;
