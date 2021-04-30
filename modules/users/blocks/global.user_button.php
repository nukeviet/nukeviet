<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

global $site_mods, $client_info, $global_config, $module_file, $module_name, $user_info, $lang_global, $my_head, $admin_info, $blockID;

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
        $xtpl->assign('USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login');
        $xtpl->assign('USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=register');
        $xtpl->assign('USER_LOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass');
        $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
        $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
        $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
        $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
        $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
        $xtpl->assign('NV_HEADER', '');
        $xtpl->assign('NV_REDIRECT', '');

        if (in_array($global_config['gfx_chk'], [
            2,
            4,
            5,
            7
        ])) {
            if ($global_config['captcha_type'] == 3) {
                $xtpl->parse('main.recaptcha3');
            } elseif ($global_config['captcha_type'] == 2) {
                $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
                $xtpl->parse('main.recaptcha.default');
                $xtpl->parse('main.recaptcha');
            } else {
                $xtpl->parse('main.captcha');
            }
        }

        if (defined('NV_OPENID_ALLOWED')) {
            $icons = [
                'single-sign-on' => 'lock',
                'google' => 'google-plus',
                'facebook' => 'facebook'
            ];
            foreach ($global_config['openid_servers'] as $server) {
                $assigns = [];
                $assigns['href'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server . '&amp;nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']);
                $assigns['title'] = ucfirst($server);
                $assigns['server'] = $server;
                $assigns['icon'] = $icons[$server];

                $xtpl->assign('OPENID', $assigns);
                $xtpl->parse('main.openid.server');
            }
            $xtpl->parse('main.openid');
        }

        if ($global_config['allowuserreg']) {
            $xtpl->parse('main.allowuserreg');
        }

        $xtpl->parse('main');
        $content = $xtpl->text('main');
    }
}
