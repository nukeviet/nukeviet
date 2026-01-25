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

if (defined('NV_IS_FILE_THEMES')) {
    // include config theme
    require NV_ROOTDIR . '/modules/menu/menu_config.php';
}

if (!nv_function_exists('nv_menu_bootstrap')) {
    /**
     * nv_menu_bootstrap_getdata()
     *
     * @param array $list
     * @param int   $parentid
     * @param array $block_config
     * @return array
     */
    function nv_menu_bootstrap_getdata($list, $parentid, $block_config)
    {
        global $site_mods;

        $search = ['&amp;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;'];
        $replace = ['&', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~'];

        $menus = [];
        foreach ($list as $row) {
            if ($row['parentid'] == $parentid) {
                if ((empty($row['module_name']) or (!empty($row['module_name']) and !empty($site_mods[$row['module_name']]))) and nv_user_in_groups($row['groups_view'])) {
                    $row['link'] = nv_url_rewrite(str_replace($search, $replace, $row['link']), true);
                    switch ($row['target']) {
                        case 1:
                            $row['target'] = '';
                            break;
                        case 3:
                            $row['target'] = ' data-toggle="winCMD" data-cmd="open" data-url="' . $row['link'] . '" data-win-name="targetWindow" data-win-opts="toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes"';
                            break;
                        default:
                            $row['target'] = ' data-target="_blank"';
                    }
                    $row['title_trim'] = nv_clean60($row['title'], $block_config['title_length']);
                    $row['current'] = empty($parentid) ? $row['css'] : '';
                    $row['liclass'] = $row['css'];
                    $row['aclass'] = '';
                    $row['is_active'] = is_current_url($row['link'], $row['active_type']);
                    $row['sub'] = nv_menu_bootstrap_getdata($list, $row['id'], $block_config);
                    empty($row['note']) && $row['note'] = $row['title'];
                    !empty($row['icon']) && $row['icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/menu/' . $row['icon'];
                    if (!empty($row['sub'])) {
                        empty($parentid) && ($row['current'] = 'dropdown' . (!empty($row['current']) ? ' ' . $row['current'] : ''));
                        $row['liclass'] = 'dropdown' . ($parentid ? ' dropend' : '') . (!empty($row['liclass']) ? ' ' . $row['liclass'] : '');
                        $row['aclass'] = 'dropdown-toggle';
                    }
                    if (!$row['is_active'] and !empty($row['sub'])) {
                        foreach ($row['sub'] as $subrow) {
                            if ($subrow['is_active']) {
                                $row['is_active'] = true;
                                break;
                            }
                        }
                    }
                    if ($row['is_active']) {
                        $row['aclass'] .= (!empty($row['aclass']) ? ' ' : '') . 'active';
                        empty($parentid) && ($row['current'] .= (!empty($row['current']) ? ' ' : '') . 'active');
                    }
                    $menus[] = $row;
                }
            }
        }

        return $menus;
    }

    /**
     * nv_menu_bootstrap_getsub()
     *
     * @param array  $smenus
     * @param string $block_theme
     * @return string
     */
    function nv_menu_bootstrap_getsub($smenus, $block_theme)
    {
        $xtpl = new XTemplate('global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');

        foreach ($smenus as $smenu) {
            !empty($smenu['liclass']) && $smenu['liclass'] = ' class="' . $smenu['liclass'] . '"';
            !empty($smenu['aclass']) && $smenu['aclass'] = ' ' . $smenu['aclass'];
            $xtpl->assign('SUBMENU', $smenu);
            if (!empty($smenu['icon'])) {
                $xtpl->parse('submenu.loop.icon');
            }
            if (!empty($smenu['sub'])) {
                $submenu = nv_menu_bootstrap_getsub($smenu['sub'], $block_theme);
                $xtpl->assign('SUB', $submenu);
                $xtpl->parse('submenu.loop.submenu');
                $xtpl->parse('submenu.loop.item');
                $xtpl->parse('submenu.loop.has_sub');
                $xtpl->parse('submenu.loop.sub');
            }
            $xtpl->parse('submenu.loop');
        }

        $xtpl->parse('submenu');

        return $xtpl->text('submenu');
    }

    /**
     * nv_menu_bootstrap()
     *
     * @param array $block_config
     * @return string
     */
    function nv_menu_bootstrap($block_config)
    {
        global $nv_Cache, $global_config, $nv_Lang;

        $sql = 'SELECT id, parentid, title, link, icon, note, subitem, groups_view, module_name, op, target, css, active_type FROM ' . NV_PREFIXLANG . '_menu_rows WHERE status=1 AND mid = ' . $block_config['menuid'] . ' ORDER BY parentid, weight ASC';
        $list = $nv_Cache->db($sql, 'id', $block_config['module']);

        $menulist = nv_menu_bootstrap_getdata($list, 0, $block_config);

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/menu/global.bootstrap.tpl');
        $xtpl = new XTemplate('global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
        $xtpl->assign('BLOCK_THEME', $block_theme);
        $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);

        if (!empty($menulist)) {
            foreach ($menulist as $menu) {
                !empty($menu['liclass']) && $menu['liclass'] = ' ' . $menu['liclass'];
                !empty($menu['aclass']) && $menu['aclass'] = ' ' . $menu['aclass'];
                !empty($menu['current']) && $menu['current'] = ' class="' . $menu['current'] . '"';
                $xtpl->assign('TOP_MENU', $menu);
                if (!empty($menu['icon'])) {
                    $xtpl->parse('main.top_menu.icon');
                }
                if (!empty($menu['sub'])) {
                    $submenu = nv_menu_bootstrap_getsub($menu['sub'], $block_theme);
                    $xtpl->assign('SUB', $submenu);
                    $xtpl->parse('main.top_menu.sub');
                    $xtpl->parse('main.top_menu.has_sub');
                }
                $xtpl->parse('main.top_menu');
            }
        }

        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_bootstrap($block_config);
}
