<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

use Codeception\Lib\Di;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_news_tags')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_news_tags($module, $data_block)
    {
        global $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('block_tags.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);

        return $tpl->fetch('block_tags.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_block_config_news_tags_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['show_type'] = $nv_Request->get_int('config_show_type', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);

        if ($return['config']['numrow'] < 1 or $return['config']['numrow'] > 100) {
            $return['config']['numrow'] = 10;
        }
        if (!in_array($return['config']['show_type'], [0, 1], true)) {
            $return['config']['show_type'] = 0;
        }

        return $return;
    }

    /**
     * @param array $block_config
     * @return string|void
     */
    function nv_news_tags($block_config)
    {
        global $nv_Lang, $nv_Cache, $site_mods, $db;

        $module = $block_config['module'];
        [$block_theme, $dir] = get_block_tpl_dir('block_tags.tpl', true, $module);

        $ttl = 3600;
        $cache_file = NV_LANG_DATA . '_block_tags_' . $block_config['show_type'] . $block_config['numrow'] . '_' . $block_theme . '_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module, $cache_file, $ttl)) != false) {
            return $cache;
        }

        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_tags";
        if ($block_config['show_type'] == 1) {
            // Phổ biến
            $sql .= " ORDER BY numnews DESC";
        } else {
            // Ngẫu nhiên
            $sql .= " ORDER BY RAND()";
        }
        $sql .= " LIMIT " . $block_config['numrow'];
        $result = $db->query($sql);

        $array = [];
        while ($row = $result->fetch()) {
            $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=tag/' . $row['alias'];
            $array[] = $row;
        }
        $result->closeCursor();
        if (empty($array)) {
            return '';
        }

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $block_config);
        $tpl->assign('ARRAY', $array);

        $content = $tpl->fetch('block_tags.tpl');
        $nv_Cache->setItem($module, $cache_file, $content);
        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $content = nv_news_tags($block_config);
    }
}
