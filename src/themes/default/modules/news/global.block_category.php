<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_news_category')) {
    /**
     * nv_news_category_getdata()
     *
     * @param array $list
     * @param int   $parentid
     * @param array $block_config
     * @return array
     */
    function nv_news_category_getdata($list, $parentid, $block_config)
    {
        global $module_name, $catid;

        $menus = [];
        foreach ($list as $row) {
            if (in_array((int) $row['status'], [1, 2], true) and $row['parentid'] == $parentid) {
                $row['active'] = (bool) ($module_name == $block_config['module'] and !empty($catid) and $row['catid'] == $catid);
                $row['sub'] = nv_news_category_getdata($list, $row['catid'], $block_config);
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

        return $menus;
    }

    /**
     * nv_news_category()
     *
     * @param array $block_config
     * @return string|void
     */
    function nv_news_category($block_config)
    {
        global $module_array_cat, $nv_Lang;

        $menulist = nv_news_category_getdata($module_array_cat, $block_config['catid'], $block_config);
        $block_theme = get_tpl_dir([$block_config['real_theme']], 'default', '/css/jquery.metisMenu.css');

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('CONFIGS', $block_config);
        $stpl->assign('TEMPLATE', $block_theme);
        $stpl->assign('MENUID', $block_config['bid']);
        $stpl->assign('MENU', $menulist);

        return $stpl->fetch('block_category.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods, $module_name, $global_array_cat, $module_array_cat, $nv_Cache, $catid;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        if ($module == $module_name) {
            $module_array_cat = $global_array_cat;
            unset($module_array_cat[0]);
        } else {
            $module_array_cat = [];
            $sql = 'SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, keywords, groups_view, status FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY sort ASC';
            $list = $nv_Cache->db($sql, 'catid', $module);
            if (!empty($list)) {
                foreach ($list as $l) {
                    $module_array_cat[$l['catid']] = $l;
                    $module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
                }
            }
        }

        $content = nv_news_category($block_config);
    }
}
