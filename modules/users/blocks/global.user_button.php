<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

global $site_mods, $client_info, $global_config, $module_file, $module_name, $user_info, $lang_global, $my_head, $admin_info, $blockID, $page_url;

$content = '';

if ($global_config['allowuserlogin']) {
    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/users/block.user_button.tpl')) {
        $block_theme = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/users/block.user_button.tpl')) {
        $block_theme = $global_config['site_theme'];
    } else {
        $block_theme = 'default';
    }
    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/css/users.css')) {
        $block_css = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/css/users.css')) {
        $block_css = $global_config['site_theme'];
    } else {
        $block_css = '';
    }
    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/js/users.js')) {
        $block_js = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/js/users.js')) {
        $block_js = $global_config['site_theme'];
    } else {
        $block_js = 'default';
    }

    $xtpl = new XTemplate('block.user_button.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/users');

    if ($site_mods[$block_config['module']]['module_file'] != $module_file) {
        if (file_exists(NV_ROOTDIR . '/modules/users/language/' . NV_LANG_INTERFACE . '.php')) {
            include NV_ROOTDIR . '/modules/users/language/' . NV_LANG_INTERFACE . '.php';
        } else {
            include NV_ROOTDIR . '/modules/users/language/vi.php';
        }
        if (!empty($block_css)) {
            $my_head .= '<link rel="StyleSheet" href="' . NV_STATIC_URL . 'themes/' . $block_css . '/css/users.css">';
        }
    } else {
        global $lang_module;
    }

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('BLOCKID', $blockID);
    $xtpl->assign('BLOCK_THEME', $block_theme);
    $xtpl->assign('BLOCK_CSS', $block_css);
    $xtpl->assign('BLOCK_JS', $block_js);

    if (defined('NV_IS_USER')) {
        if (!empty($user_info['avata'])) {
            $avata = $user_info['avata'];
        } else {
            $avata = NV_STATIC_URL . 'themes/' . $block_theme . '/images/users/no_avatar.png';
        }

        $user_info['current_login_txt'] = nv_date('d/m, H:i', $user_info['current_login']);

        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
        $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
        $xtpl->assign('URL_LOGOUT', defined('NV_IS_ADMIN') ? 'nv_admin_logout' : 'bt_logout');
        $xtpl->assign('MODULENAME', $module_info['custom_title']);
        $xtpl->assign('AVATA', $avata);
        $xtpl->assign('USER', $user_info);
        $xtpl->assign('WELCOME', defined('NV_IS_ADMIN') ? $lang_global['admin_account'] : $lang_global['your_account']);
        $xtpl->assign('LEVEL', defined('NV_IS_ADMIN') ? $admin_info['level'] : 'user');
        $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users');
        $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=avatar/upd', true));
        $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=');

        if (defined('NV_OPENID_ALLOWED')) {
            $xtpl->parse('signed.allowopenid');
        }
        if (defined('SSO_REGISTER_DOMAIN')) {
            $xtpl->assign('SSO_REGISTER_ORIGIN', SSO_REGISTER_DOMAIN);
            $xtpl->parse('signed.crossdomain_listener');
        }

        if (defined('NV_IS_ADMIN')) {
            $new_drag_block = (defined('NV_IS_DRAG_BLOCK')) ? 0 : 1;
            $lang_drag_block = ($new_drag_block) ? $lang_global['drag_block'] : $lang_global['no_drag_block'];

            $xtpl->assign('NV_ADMINDIR', NV_ADMINDIR);
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
    } else {
        $xtpl->assign('NV_REDIRECT', nv_redirect_encrypt(NV_MY_DOMAIN . (empty($page_url) ? '' : nv_url_rewrite(str_replace('&amp;', '&', $page_url), true))));
        $xtpl->parse('main');
        $content = $xtpl->text('main');
    }
}
