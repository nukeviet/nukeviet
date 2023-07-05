<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_module_menu')) {
    /**
     * nv_module_menu()
     *
     * @return string
     */
    function nv_module_menu()
    {
        global $global_config, $module_info, $nv_Lang, $module_name, $op, $user_info, $db, $site_mods;

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/blocks/global.module_menu.tpl');
        $xtpl = new XTemplate('global.module_menu.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
        $xtpl->assign('TEMPLATE', $block_theme);

        $_lis = $module_info['funcs'];
        $_alias = $module_info['alias'];
        $mod = ['href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, 'active' => 'active', 'title' => $module_info['custom_title']];

        foreach ($_lis as $_li) {
            if ($_li['show_func'] and $_li['in_submenu'] and $_li['func_name'] != 'main') {
                if ($module_name == 'users') {
                    if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
                        continue;
                    }
                    if ($_li['func_name'] == 'groups') {
                        if (!isset($user_info['group_manage'])) {
                            $user_info['group_manage'] = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $user_info['userid'] . ' AND is_leader=1')->fetchColumn();
                        }
                        if (!$user_info['group_manage']) {
                            continue;
                        }
                    }

                    if (!empty($site_mods['myapi']) and $_li['func_name'] == 'logout') {
                        $xtpl->assign('LOOP', [
                            'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=myapi',
                            'active' => '',
                            'title' => $nv_Lang->getGlobal('myapis')
                        ]);
                        $xtpl->parse('main.loop');
                    }
                }

                if ($_li['func_name'] == $op) {
                    $active = 'active';
                    $mod['active'] = '';
                } else {
                    $active = '';
                }

                $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
                $li = ['href' => $href, 'active' => $active, 'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']];
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
