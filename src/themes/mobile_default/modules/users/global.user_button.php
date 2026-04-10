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

global $site_mods, $client_info, $global_config, $module_file, $module_name, $user_info, $nv_Lang, $my_head, $admin_info, $blockID, $page_url;

$content = '';

if ($global_config['allowuserlogin']) {
    $block_file_name = 'block.user_button.tpl';
    $template_dir = 'themes/' . get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/users/' . $block_file_name);

    if ($site_mods[$block_config['module']]['module_file'] != $module_file) {
        $css_dir = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], '', '/css/users.css');
        if (!empty($css_dir)) {
            $my_head .= '<link rel="StyleSheet" href="' . NV_STATIC_URL . 'themes/' . $css_dir . '/css/users.css">';
        }
    }

    if ($site_mods[$block_config['module']]['module_file'] != $module_file) {
        $nv_Lang->loadModule('users', false, true);
    }

    if (defined('NV_IS_USER')) {
        empty($user_info['avata']) && $user_info['avata'] = NV_STATIC_URL . $template_dir . '/images/users/no_avatar.png';
        $user_info['current_login_txt'] = nv_datetime_format($user_info['current_login']);
    }
    if (defined('NV_IS_ADMIN')) {
        $new_drag_block = (defined('NV_IS_DRAG_BLOCK')) ? 0 : 1;
        $lang_drag_block = ($new_drag_block) ? $nv_Lang->getGlobal('drag_block') : $nv_Lang->getGlobal('no_drag_block');
    }

    $xtpl = new XTemplate($block_file_name, NV_ROOTDIR . '/' . $template_dir . '/modules/users');

    if ($site_mods[$block_config['module']]['module_file'] != $module_file) {
        $xtpl->assign('LANG', \NukeViet\Core\Language::$tmplang_module);
    } else {
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    }

    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    if (defined('NV_IS_USER')) {
        $js_dir = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/js/users.js');
        $xtpl->assign('BLOCK_JS', $js_dir);
        $xtpl->assign('URL_LOGOUT', defined('NV_IS_ADMIN') ? 'nv_admin_logout' : 'bt_logout');
        $xtpl->assign('MODULENAME', $module_info['custom_title']);
        $xtpl->assign('AVATA', $user_info['avata']);
        $xtpl->assign('USER', $user_info);
        $xtpl->assign('WELCOME', defined('NV_IS_ADMIN') ? $nv_Lang->getGlobal('admin_account') : $nv_Lang->getGlobal('your_account'));
        $xtpl->assign('LEVEL', defined('NV_IS_ADMIN') ? $admin_info['level'] : 'user');
        $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users');
        $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=avatar/upd', true));
        $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=');

        if (defined('NV_OPENID_ALLOWED')) {
            $xtpl->parse('signed.allowopenid');
        }

        if (!empty($site_mods['myapi'])) {
            $xtpl->parse('signed.myapis');
        }

        if (defined('NV_IS_ADMIN')) {
            $xtpl->assign('URL_DBLOCK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;drag_block=' . $new_drag_block);
            $xtpl->assign('LANG_DBLOCK', $lang_drag_block);
            $xtpl->assign('URL_ADMINMODULE', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
            $xtpl->assign('URL_AUTHOR', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . $admin_info['admin_id']);

            if (defined('NV_IS_SPADMIN')) {
                $xtpl->parse('signed.admintoolbar.is_spadadmin');
            }
            if (defined('NV_IS_MODADMIN') and !empty($module_info['admin_file'])) {
                $xtpl->parse('signed.admintoolbar.is_modadmin');
            }
            $xtpl->parse('signed.admintoolbar');
        }

        $xtpl->parse('signed');
        $content = $xtpl->text('signed');
    } elseif (defined('SSO_SERVER') and (defined('NV_IS_USER_FORUM') or NV_MY_DOMAIN != SSO_REGISTER_DOMAIN)) {
        $url = NukeViet\Client\Sso::getLoginUrl(empty($page_url) ? urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN) : urlRewriteWithDomain($page_url, NV_MY_DOMAIN));
        $url = nv_apply_hook('', 'modify_sso_login_url', [$url], $url);
        $xtpl->assign('LINK_LOGIN', $url);
        $xtpl->parse('sso');
        $content = $xtpl->text('sso');
    } else {
        $xtpl->assign('LOAD_FORM_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login');
        $xtpl->assign('NV_REDIRECT', nv_redirect_encrypt(empty($page_url) ? urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN) : urlRewriteWithDomain($page_url, NV_MY_DOMAIN)));
        $xtpl->parse('main');
        $content = $xtpl->text('main');
    }

    if ($site_mods[$block_config['module']]['module_file'] != $module_file) {
        $nv_Lang->changeLang();
    }
}
