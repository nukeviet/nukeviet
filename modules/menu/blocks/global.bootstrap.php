<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
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
     * nv_menu_bootstrap()
     *
     * @param array $block_config
     * @return string
     */
    function nv_menu_bootstrap($block_config)
    {
        global $nv_Cache, $global_config, $site_mods, $module_info, $module_name, $module_file, $module_data, $lang_global, $catid, $home;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/menu/global.bootstrap.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/menu/global.bootstrap.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $array_menu = [];
        $sql = 'SELECT id, parentid, title, link, icon, note, subitem, groups_view, module_name, op, target, css, active_type FROM ' . NV_PREFIXLANG . '_menu_rows WHERE status=1 AND mid = ' . $block_config['menuid'] . ' ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, '', $block_config['module']);

        $search = ['&amp;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;'];
        $replace = ['&', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~'];
        foreach ($list as $row) {
            if ((empty($row['module_name']) or (!empty($row['module_name']) and !empty($site_mods[$row['module_name']]))) and nv_user_in_groups($row['groups_view'])) {
                $link = str_replace($search, $replace, $row['link']);
                $link = nv_url_rewrite($link, true);
                switch ($row['target']) {
                    case 1:
                        $row['target'] = '';
                        break;
                    case 3:
                        $row['target'] = ' data-toggle="winCMD" data-cmd="open" data-url="' . $link . '" data-win-name="targetWindow" data-win-opts="toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes"';
                        break;
                    default:
                        $row['target'] = ' data-target="_blank"';
                }

                $array_menu[$row['parentid']][$row['id']] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'title_trim' => nv_clean60($row['title'], $block_config['title_length']),
                    'target' => $row['target'],
                    'note' => empty($row['note']) ? $row['title'] : $row['note'],
                    'link' => $link,
                    'icon' => (empty($row['icon'])) ? '' : NV_BASE_SITEURL . NV_UPLOADS_DIR . '/menu/' . $row['icon'],
                    'css' => $row['css'],
                    'active_type' => $row['active_type']
                ];
            }
        }

        $xtpl = new XTemplate('global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('BLOCK_THEME', $block_theme);
        $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);

        if (!empty($array_menu)) {
            foreach ($array_menu[0] as $id => $item) {
                $classcurrent = []; // For bootstrap 3
                $liclass = []; // For bootstrap 4/5
                $aclass = []; // For bootstrap 4/5
                $submenu_active = [];
                if (isset($array_menu[$id])) {
                    $classcurrent[] = 'dropdown';
                    $liclass[] = 'dropdown';
                    $aclass[] = 'dropdown-toggle';
                    $submenu = nv_get_bootstrap_submenu($id, $array_menu, $submenu_active, $block_theme);
                    $xtpl->assign('SUB', $submenu);
                    $xtpl->parse('main.top_menu.sub');
                    $xtpl->parse('main.top_menu.has_sub');
                }
                if (is_current_url($item['link'], $item['active_type'])) {
                    $classcurrent[] = 'active';
                    $aclass[] = 'active';
                } elseif (!empty($submenu_active)) {
                    $classcurrent[] = 'active';
                    $aclass[] = 'active';
                }
                if (!empty($item['css'])) {
                    $classcurrent[] = $item['css'];
                    $liclass[] = $item['css'];
                }
                $item['current'] = empty($classcurrent) ? '' : ' class="' . (implode(' ', $classcurrent)) . '"';
                $item['liclass'] = empty($liclass) ? '' : ' ' . implode(' ', $liclass);
                $item['aclass'] = empty($aclass) ? '' : ' ' . implode(' ', $aclass);

                $xtpl->assign('TOP_MENU', $item);
                if (!empty($item['icon'])) {
                    $xtpl->parse('main.top_menu.icon');
                }
                $xtpl->parse('main.top_menu');
            }
        }

        $xtpl->parse('main');

        return $xtpl->text('main');
    }

    /**
     * nv_get_bootstrap_submenu()
     *
     * @param int    $id
     * @param int    $array_menu
     * @param array  $submenu_active
     * @param string $block_theme
     * @return string
     */
    function nv_get_bootstrap_submenu($id, $array_menu, &$submenu_active, $block_theme)
    {
        $xtpl = new XTemplate('global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');

        if (!empty($array_menu[$id])) {
            foreach ($array_menu[$id] as $sid => $smenu) {
                $liclass = []; // For bootstrap 4/5
                $aclass = []; // For bootstrap 4/5
                if (is_current_url($smenu['link'], $smenu['active_type'])) {
                    $aclass[] = 'active';
                    $submenu_active[] = $id;
                }
                $submenu = '';
                if (isset($array_menu[$sid])) {
                    $submenu = nv_get_bootstrap_submenu($sid, $array_menu, $submenu_active, $block_theme);
                    // For bootstrap 4/5
                    if (!empty($submenu_active) and in_array($sid, $submenu_active, true) and !in_array('active', $aclass, true)) {
                        $aclass[] = 'active';
                    }
                    $liclass[] = 'dropdown dropend';
                    $aclass[] = 'dropdown-toggle';
                    // End for bootstrap 4/5

                    $xtpl->assign('SUB', $submenu);
                    $xtpl->parse('submenu.loop.item');
                    $xtpl->parse('submenu.loop.has_sub'); // For bootstrap 4/5
                    $xtpl->parse('submenu.loop.sub'); // For bootstrap 4/5
                }

                // For bootstrap 4/5
                if (!empty($smenu['css'])) {
                    $liclass[] = $smenu['css'];
                }

                $smenu['liclass'] = empty($liclass) ? '' : ' class="' . implode(' ', $liclass) . '"';
                $smenu['aclass'] = empty($aclass) ? '' : ' ' . implode(' ', $aclass);
                // End for bootstrap 4/5

                $xtpl->assign('SUBMENU', $smenu);
                // For bootstrap 3
                if (!empty($submenu)) {
                    $xtpl->parse('submenu.loop.submenu');
                }
                // End for bootstrap 3
                if (!empty($smenu['icon'])) {
                    $xtpl->parse('submenu.loop.icon');
                }
                $xtpl->parse('submenu.loop');
            }
            $xtpl->parse('submenu');
        }

        return $xtpl->text('submenu');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_bootstrap($block_config);
}
