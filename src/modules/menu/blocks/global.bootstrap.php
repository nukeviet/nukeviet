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
     * @param array $list
     * @param int   $parentid
     * @param array $block_config
     * @return array
     */
    function nv_menu_bootstrap_getdata($list, $parentid, $block_config)
    {
        global $site_mods, $global_config, $home;

        $search = ['&amp;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;'];
        $replace = ['&', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~'];

        $menus = [];
        foreach ($list as $row) {
            if ($row['parentid'] != $parentid or !(
                (empty($row['module_name']) or (!empty($row['module_name']) and !empty($site_mods[$row['module_name']]))) and nv_user_in_groups($row['groups_view'])
            )) {
                continue;
            }

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
            $row['is_active'] = is_current_url($row['link'], $row['active_type']);
            $row['sub'] = nv_menu_bootstrap_getdata($list, $row['id'], $block_config);

            empty($row['note']) && $row['note'] = $row['title'];
            !empty($row['icon']) && $row['icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/menu/' . $row['icon'];

            // Menu con active thì active ngược ra menu cha
            if (!$row['is_active'] and !empty($row['sub'])) {
                foreach ($row['sub'] as $subrow) {
                    if ($subrow['is_active']) {
                        $row['is_active'] = true;
                        break;
                    }
                }
            }
            // Lược bỏ tên module khỏi url thì không active menu có link main của module ở trang home
            if (
                !empty($block_config['show_home']) and !empty($home) and
                !empty($row['module_name']) and $global_config['rewrite_op_mod'] == $row['module_name'] and
                $row['link'] == nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $row['module_name'], true)
            ) {
                $row['is_active'] = false;
            }
            $menus[] = $row;
        }

        return $menus;
    }

    /**
     * @param array $block_config
     * @return string
     */
    function nv_menu_bootstrap($block_config)
    {
        global $nv_Cache, $nv_Lang, $home;

        if (defined('NV_ADDED_MENU_BOOTSTRAP')) {
            // Chỉ thêm block này 1 lần
            return '';
        }
        define('NV_ADDED_MENU_BOOTSTRAP', true);

        $sql = 'SELECT id, parentid, title, link, icon, note, subitem, groups_view, module_name, op, target, css, active_type
        FROM ' . NV_PREFIXLANG . '_menu_rows WHERE status=1 AND mid = ' . $block_config['menuid'] . ' ORDER BY parentid, weight ASC';
        $list = $nv_Cache->db($sql, 'id', 'menu');
        $menulist = nv_menu_bootstrap_getdata($list, 0, $block_config);

        [$block_theme, $dir] = get_block_tpl_dir('global.bootstrap.tpl', true, 'menu');
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('BLOCK_THEME', $block_theme);
        $tpl->assign('CONFIG', $block_config);
        $tpl->assign('MENUS', $menulist);
        $tpl->assign('HOME', $home);
        $tpl->setTemplateDir($dir);

        return $tpl->fetch('global.bootstrap.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_bootstrap($block_config);
}
