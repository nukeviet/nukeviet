<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_login')) {
    /**
     * nv_block_login()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_login($block_config)
    {
        global $client_info, $global_config, $module_name, $module_file, $user_info, $lang_global, $admin_info, $blockID, $db, $module_info, $site_mods, $db_config, $my_head, $page_url, $nv_redirect;

        $content = '';
        $module = $block_config['module'];
        $mod_file = $site_mods[$module]['module_file'];

        if (empty($global_config['allowuserlogin'])) {
            return '';
        }

        if ($module == $module_name) {
            return '';
        }

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/users/block.login.tpl');

        if ($mod_file != $module_file) {
            if (file_exists(NV_ROOTDIR . '/modules/users/language/' . NV_LANG_INTERFACE . '.php')) {
                include NV_ROOTDIR . '/modules/users/language/' . NV_LANG_INTERFACE . '.php';
            } else {
                include NV_ROOTDIR . '/modules/users/language/vi.php';
            }
        } else {
            global $lang_module;
        }

        $xtpl = new XTemplate('block.login.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/users');

        $xtpl->assign('GLANG', $lang_global);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('BLOCKID', $blockID);

        if (defined('NV_IS_USER')) {
            if (!empty($user_info['avata'])) {
                $avata = $user_info['avata'];
            } else {
                $avata = NV_STATIC_URL . 'themes/' . $block_theme . '/images/users/no_avatar.png';
            }

            $user_info['current_login_txt'] = nv_date('d/m, H:i', $user_info['current_login']);
            $xtpl->assign('URL_LOGOUT', defined('NV_IS_ADMIN') ? 'nv_admin_logout' : 'bt_logout');
            $xtpl->assign('MODULENAME', $module_info['custom_title']);
            $xtpl->assign('AVATA', $avata);
            $xtpl->assign('USER', $user_info);
            $xtpl->assign('WELCOME', defined('NV_IS_ADMIN') ? $lang_global['admin_account'] : $lang_global['your_account']);
            $xtpl->assign('LEVEL', defined('NV_IS_ADMIN') ? $admin_info['level'] : 'user');
            $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '');
            $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=');

            if (defined('NV_OPENID_ALLOWED')) {
                $xtpl->parse('signed.allowopenid');
            }

            if (!empty($site_mods['myapi'])) {
                $xtpl->parse('signed.myapis');
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
            if ($global_config['allowuserreg']) {
                $xtpl->assign('USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=register');
                $xtpl->parse('main.allowuserreg');
            }

            $xtpl->parse('main');
            $content = $xtpl->text('main');
        }

        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_login($block_config);
}
