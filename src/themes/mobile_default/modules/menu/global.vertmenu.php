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

if (!nv_function_exists('nv_vertmenu')) {
    /**
     * nv_vertmenu_getdata()
     *
     * @param array $list
     * @param int   $parentid
     * @param array $block_config
     * @return array
     */
    function nv_vertmenu_getdata($list, $parentid, $block_config)
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
                    $row['strip_title'] = nv_clean60($row['title'], $block_config['title_length']);
                    $row['active'] = is_current_url($row['link'], $row['active_type']);
                    $row['sub'] = nv_vertmenu_getdata($list, $row['id'], $block_config);
                    if (!$row['active'] and !empty($row['sub'])) {
                        foreach ($row['sub'] as $subrow) {
                            if ($subrow['active']) {
                                $row['active'] = true;
                                break;
                            }
                        }
                    }
                    if ($row['active']) {
                        $row['expanded'] = true;
                        $row['collapsed'] = false;
                    } else {
                        $row['expanded'] = false;
                        $row['collapsed'] = true;
                    }
                    $menus[] = $row;
                }
            }
        }

        return $menus;
    }

    function nv_vertmenu_getsub($menulist, $parentid, $is_show, $block_config, $block_theme = '')
    {
        global $global_config;

        if ((int) $parentid == 0) {
            $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/menu/global.vertmenu.tpl');
        }

        $xtpl = new XTemplate('global.vertmenu.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');
        $xtpl->assign('MENUID', $block_config['bid']);
        $xtpl->assign('PARENTID', $parentid);

        if ((int) $parentid == 0) {
            $css_template = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/css/global.vertmenu.css');
            $xtpl->assign('CSS_TEMPLATE', $css_template);

            $xtpl->parse('main.level0');
            $xtpl->parse('main.level0_1');
        } else {
            if ($is_show) {
                $xtpl->parse('main.level1.is_show');
            }
            $xtpl->parse('main.level1');
        }

        foreach ($menulist as $menu) {
            if ((int) $menu['parentid'] == $parentid) {
                $xtpl->assign('ENTRY', $menu);

                if ($menu['active']) {
                    $xtpl->parse('main.loop.is_active');
                }
                if (!empty($menu['sub'])) {
                    $xtpl->assign('SUBMENU', nv_vertmenu_getsub($menu['sub'], (int) $menu['id'], $menu['active'], $block_config, $block_theme));

                    if ($menu['collapsed']) {
                        $xtpl->parse('main.loop.sub.collapsed');
                    }

                    if ($menu['expanded']) {
                        $xtpl->parse('main.loop.sub.expanded');
                    } else {
                        $xtpl->parse('main.loop.sub.no_expanded');
                    }

                    $xtpl->parse('main.loop.sub');
                }
                $xtpl->parse('main.loop');
            }
        }

        $xtpl->parse('main');

        return $xtpl->text('main');
    }

    /**
     * nv_vertmenu()
     *
     * @param array $block_config
     * @return string
     */
    function nv_vertmenu($block_config)
    {
        global $nv_Cache;

        $sql = 'SELECT id, parentid, title, link, icon, note, subitem, groups_view, module_name, op, target, css, active_type FROM ' . NV_PREFIXLANG . '_menu_rows WHERE status=1 AND mid = ' . $block_config['menuid'] . ' ORDER BY parentid, weight ASC';
        $list = $nv_Cache->db($sql, 'id', $block_config['module']);

        $menulist = nv_vertmenu_getdata($list, 0, $block_config);
        if (empty($menulist)) {
            return '';
        }

        return nv_vertmenu_getsub($menulist, 0, false, $block_config);
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_vertmenu($block_config);
}
