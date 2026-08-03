<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

global $site_mods, $global_config, $module_file, $module_name, $user_info, $nv_Lang, $admin_info, $page_url;

if (!$global_config['allowuserlogin']) {
    $content = '';
    return;
}

if ($site_mods[$block_config['module']]['module_file'] != $module_file) {
    $nv_Lang->loadModule('users', false, true);
}

addition_module_assets($block_config['module'], 'both');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_block_tpl_dir('block.user_button.tpl', module: $block_config['module']));
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE', $block_config['module']);

/** @disregard P1011 */
if (defined('NV_IS_USER')) {
    // Dữ liệu dựng ảnh đại diện dạng chữ, tính lại phòng trường hợp tài khoản diễn đàn hoặc SSO
    $user_info['avatar_letters'] = $user_info['avatar_letters'] ?? nv_user_avatar_letters($user_info['first_name'] ?? '', $user_info['last_name'] ?? '', $user_info['username'] ?? '');
    $user_info['avatar_color'] = $user_info['avatar_color'] ?? nv_user_avatar_color($user_info['username'] ?? '');

    $tpl->assign('USER', $user_info);
    $tpl->assign('ADMIN', $admin_info);
    $tpl->assign('SITE_MODS', $site_mods);
    $tpl->assign('MODULE_INFO', $module_info);
    $tpl->assign('MODULE_NAME', $module_name);
} elseif (defined('SSO_SERVER') and (defined('NV_IS_USER_FORUM') or NV_MY_DOMAIN != SSO_REGISTER_DOMAIN)) { // phpcs:ignore
    $url = NukeViet\Client\Sso::getLoginUrl(empty($page_url) ? urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN) : urlRewriteWithDomain($page_url, NV_MY_DOMAIN));
    $url = nv_apply_hook('', 'modify_sso_login_url', [$url], $url);
    $tpl->assign('LINK_LOGIN', $url);
} else {
    $tpl->assign('NV_REDIRECT', nv_redirect_encrypt(empty($page_url) ? urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN) : urlRewriteWithDomain($page_url, NV_MY_DOMAIN)));
}

$content = $tpl->fetch('block.user_button.tpl');

if ($site_mods[$block_config['module']]['module_file'] != $module_file) {
    $nv_Lang->changeLang();
}
