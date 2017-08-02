<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:19
 */

if (!defined('NV_IS_MOD_BANNERS')) {
    die('Stop!!!');
}

$contents = array();
$contents['info'] = $lang_module['main_page_info'];
$contents['detail'] = $lang_global['detail'];

$contents['rows'] = array();

foreach ($global_array_plans as $row) {
    $contents['rows'][$row['id']]['title'] = array($row['title']);
    $contents['rows'][$row['id']]['blang'] = array($lang_module['blang'], ((!empty($row['blang'])) ? $language_array[$row['blang']]['name'] : $lang_module['blang_all']));
    $contents['rows'][$row['id']]['size'] = array($lang_module['size'], $row['width'] . ' x ' . $row['height'] . 'px');
    $contents['rows'][$row['id']]['form'] = array($lang_module['form'], $row['form']);
    $contents['rows'][$row['id']]['description'] = array($lang_module['description'], $row['description']);
    $contents['rows'][$row['id']]['allowed'] = isset($global_array_uplans[$row['id']]) ? true : false;
}

$page_title = $module_info['site_title'];
$contents = nv_banner_theme_main($contents, $manament);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
