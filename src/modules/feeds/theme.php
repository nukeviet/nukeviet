<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_RSS')) {
    exit('Stop!!!');
}

/**
 * nv_build_rss_subtree()
 * Xây dựng mảng cây đệ quy từ dữ liệu subcategory của rssdata.php
 */
function nv_build_rss_subtree($rss_contents, $id = 0)
{
    $nodes = [];
    foreach ($rss_contents as $value) {
        if (($value['parentid'] ?? 0) == $id) {
            $catid = $value['catid'] ?? 0;
            $nodes[] = [
                'title' => $value['title'],
                'rss_url' => $value['link'],
                'atom_url' => $value['link'] . '&amp;type=atom',
                'children' => $catid > 0 ? nv_build_rss_subtree($rss_contents, $catid) : []
            ];
        }
    }
    return $nodes;
}

/**
 * nv_rss_main_theme()
 *
 * @param string $rsscontents
 * @return string
 */
function nv_rss_main_theme($rsscontents)
{
    global $site_mods, $module_name, $nv_Lang;

    // Không xóa biến global này vì dùng ở rssdata.php
    global $db, $nv_Cache, $module_data, $global_config;

    $rss_array = nv_apply_hook($module_name, 'before_generate_rss', [$rsscontents], []);
    if (empty($rss_array)) {
        foreach ($site_mods as $mod_name => $mod_info) {
            if ($mod_info['rss'] == 1 && isset($mod_info['alias']['rss']) && module_file_exists($mod_info['module_file'] . '/funcs/rss.php')) {
                $rss_array[$mod_name] = $mod_info;
            }
        }
    }

    $rss_tree = [];
    foreach ($rss_array as $mod_name => $mod_info) {
        $mod_file = $mod_info['module_file'];
        $mod_data = $mod_info['module_data'];

        $children = [];
        if (module_file_exists($mod_file . '/rssdata.php')) {
            $rssarray = [];
            include NV_ROOTDIR . '/modules/' . $mod_file . '/rssdata.php';
            if (!empty($rssarray)) {
                $children = nv_build_rss_subtree($rssarray, 0);
            }
        }

        $rss_tree[] = [
            'title' => $mod_info['custom_title'],
            'rss_url' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $mod_info['alias']['rss'],
            'atom_url' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $mod_info['alias']['rss'] . '&amp;type=atom',
            'children' => $children
        ];
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('INTRO', $rsscontents);
    $tpl->assign('RSS_TREE', $rss_tree);

    return $tpl->fetch('main.tpl');
}
