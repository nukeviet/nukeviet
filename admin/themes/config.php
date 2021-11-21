<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$select_options = [];
$theme_array = nv_scandir(NV_ROOTDIR . '/themes', [$global_config['check_theme'], $global_config['check_theme_mobile']]);
if ($global_config['idsite']) {
    $theme = $db->query('SELECT t1.theme FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'])->fetchColumn();
    if (!empty($theme)) {
        $array_site_cat_theme = explode(',', $theme);
        $result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0');
        while (list($theme) = $result->fetch(3)) {
            $array_site_cat_theme[] = $theme;
        }
        $theme_array = array_intersect($theme_array, $array_site_cat_theme);
    }
}

foreach ($theme_array as $themes_i) {
    if (file_exists(NV_ROOTDIR . '/themes/' . $themes_i . '/config.ini')) {
        $select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;selectthemes=' . $themes_i] = $themes_i;
    }
}

$selectthemes_old = $nv_Request->get_string('selectthemes', 'cookie', $global_config['site_theme']);
$selectthemes = $nv_Request->get_string('selectthemes', 'get', $selectthemes_old);

if (!in_array($selectthemes, $theme_array, true)) {
    $selectthemes = $global_config['site_theme'];
}
if ($selectthemes_old != $selectthemes) {
    $nv_Request->set_Cookie('selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME);
}

if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.php')) {
    // Connect with file language interface configuration
    if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_' . NV_LANG_INTERFACE . '.php')) {
        require NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_' . NV_LANG_INTERFACE . '.php';
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_' . NV_LANG_DATA . '.php')) {
        require NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_' . NV_LANG_DATA . '.php';
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_en.php')) {
        require NV_ROOTDIR . '/themes/' . $selectthemes . '/language/admin_en.php';
    }

    // Connect with file theme configuration
    require NV_ROOTDIR . '/themes/' . $selectthemes . '/config.php';
} else {
    $contents = '<h2 class="center vcenter" style="margin: 50px;">' . sprintf($lang_module['config_not_exit'], $selectthemes) . '</h2>';
}

$page_title = $lang_module['config'] . ':' . $selectthemes;

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
