<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$widget_info = [
    'id' => 'arttotal',
    'name' => $nv_Lang->getModule('siteinfo_totalpost'),
    'note' => '',
    'func' => function () {
        global $global_config, $module_file, $module_name, $module_data, $nv_Lang, $db, $nv_Cache;

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_module_tpl_dir('widget_arttotal.tpl'));
        $tpl->assign('LANG', $nv_Lang);

        $_arr_siteinfo = [];
        $cacheFile = 'widget_arttotal_' . NV_CACHE_PREFIX . '.cache';
        $cacheTTL = 1800;

        if (($cache = $nv_Cache->getItem($module_name, $cacheFile, ttl: $cacheTTL)) != false) {
            $_arr_siteinfo = unserialize($cache);
        } else {
            $_arr_siteinfo['total_posts'] = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows')->fetchColumn();

            $nv_Cache->setItem($module_name, $cacheFile, serialize($_arr_siteinfo), ttl: $cacheTTL);
        }

        $tpl->assign('NUM', nv_number_format($_arr_siteinfo['total_posts']));

        return $tpl->fetch('widget_arttotal.tpl');
    }
];
