<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_function_exists('nv_module_menu')) {
    /**
     * nv_module_menu()
     *
     * @return
     *
     */
    function nv_module_menu()
    {
        global $global_config, $module_info, $lang_global, $module_name, $op;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.module_menu.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.module_menu.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.module_menu.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('TEMPLATE', $block_theme);
        
        $_lis = $module_info['funcs'];
        $_alias = $module_info['alias'];
        $mod = array('href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' .$module_name, 'active' => 'active', 'title' => $module_info['custom_title'] );

        foreach ($_lis as $_li) {
            if ($_li['show_func'] and $_li['in_submenu'] and $_li['func_name'] != 'main') {
                if ($module_name == "users") {
                    if ($_li['func_name'] == 'register' and ! $global_config['allowuserreg']) {
                        continue;
                    }
                }

                if ($_li['func_name'] == $op) {
                    $active = "active";
                    $mod['active'] = "";
                } else {
                    $active = "";
                }
                
                $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' .$module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
                $li = array( 'href' => $href, 'active' => $active, 'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name'] );
                $xtpl->assign('LOOP', $li);
                $xtpl->parse('main.loop');
            }
        }
        
        $xtpl->assign('MOD', $mod);
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_module_menu();
}
