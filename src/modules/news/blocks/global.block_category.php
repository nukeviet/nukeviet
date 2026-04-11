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

if (!nv_function_exists('nv_news_category')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_news_category($module, $data_block)
    {
        global $nv_Cache, $site_mods, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('block_category.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);
        $tpl->assign('MODULE', $module);
        $tpl->assign('SITE_MODS', $site_mods);

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY sort ASC';
        $list = $nv_Cache->db($sql, '', $module);
        $tpl->assign('CATS', $list);

        return $tpl->fetch('block_category.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_block_config_news_category_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['catid'] = $nv_Request->get_int('config_catid', 'post', 0);
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 0);

        return $return;
    }

    /**
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
                $row['title0'] = nv_clean60($row['title'], $block_config['title_length']);
                $row['active'] = (bool) ($module_name == $block_config['module'] and !empty($catid) and $row['catid'] == $catid);
                $row['sub'] = nv_news_category_getdata($list, $row['catid'], $block_config);
                $row['open'] = false;
                if (!$row['open'] and !empty($row['sub'])) {
                    foreach ($row['sub'] as $subrow) {
                        if ($subrow['active'] or $subrow['open']) {
                            $row['open'] = true;
                            break;
                        }
                    }
                }
                $menus[] = $row;
            }
        }


        return $menus;
    }

    /**
     * @param array $block_config
     * @return string|void
     */
    function nv_news_category($block_config)
    {
        global $module_array_cat, $global_config, $nv_Lang;

        $menulist = nv_news_category_getdata($module_array_cat, $block_config['catid'], $block_config);
        if (empty($menulist)) {
            return '';
        }

        [$block_theme, $dir] = get_block_tpl_dir('block_category.tpl', true, $block_config['module']);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $block_config);
        $tpl->assign('MENULIST', $menulist);

        return $tpl->fetch('block_category.tpl');
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
