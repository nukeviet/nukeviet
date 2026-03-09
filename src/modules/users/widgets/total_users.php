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
    'id' => 'usrtotal',
    'name' => $nv_Lang->getModule('siteinfo_user'),
    'note' => '',
    'func' => function () {
        global $global_config, $module_file, $module_name, $module_data, $nv_Lang, $db, $nv_Cache, $db_config;

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_module_tpl_dir('widget_usrtotal.tpl'));
        $tpl->assign('LANG', $nv_Lang);

        $_mod_table = ($module_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $module_data;
        $_arr_siteinfo = [];
        $cacheFile = 'widget_usrtotal_' . NV_CACHE_PREFIX . '.cache';
        $cacheTTL = 1800;

        if (($cache = $nv_Cache->getItem($module_name, $cacheFile, ttl: $cacheTTL)) != false) {
            $_arr_siteinfo = unserialize($cache);
        } else {
            if ($global_config['idsite'] > 0) {
                $site_condition = ' WHERE idsite=' . $global_config['idsite'];
            } else {
                $site_condition = '';
            }
            $_arr_siteinfo['number_user'] = $db->query('SELECT COUNT(*) FROM ' . $_mod_table . $site_condition)->fetchColumn();

            $nv_Cache->setItem($module_name, $cacheFile, serialize($_arr_siteinfo), ttl: $cacheTTL);
        }

        $tpl->assign('NUM', nv_number_format($_arr_siteinfo['number_user']));

        return $tpl->fetch('widget_usrtotal.tpl');
    }
];
