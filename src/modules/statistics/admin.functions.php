<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$module_info['alias'] = [];
$module_info['alias']['allbots'] = 'allbots';
$module_info['alias']['allbrowsers'] = 'allbrowsers';
$module_info['alias']['allcountries'] = 'allcountries';
$module_info['alias']['allos'] = 'allos';
$module_info['alias']['allreferers'] = 'allreferers';

define('NV_IS_MOD_STATISTICS', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:statistics';
$array_url_instruction['allbots'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:statistics:allbots';
$array_url_instruction['allbrowsers'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:statistics:allbrowsers';
$array_url_instruction['allcountries'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:statistics:allcountries';
$array_url_instruction['allos'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:statistics:allos';
$array_url_instruction['allreferers'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:statistics:allreferers';

define('NV_BASE_MOD_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
$global_config['current_theme_type'] = 'r';

if ($op != 'cleardata') {
    // FIXME Xóa 3 dòng này sau khi đã hoàn thiện.
    $module_info['module_theme'] = 'future';
    $global_config['module_theme'] = 'future';
    $global_config['site_theme'] = 'future';

    // Xử lý đoạn này để ép load js, css ngoài site
    $_module_name = $module_name;
    $module_name = 'admin_statistics';
    addition_module_assets($_module_name, 'both');
    $module_name = $_module_name;

    require NV_ROOTDIR . '/modules/' . $module_file . '/theme.php';

    /**
     * nv_site_theme()
     *
     * @param mixed $contents
     */
    function nv_site_theme($contents)
    {
        return nv_admin_theme($contents);
    }
}
