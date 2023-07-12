<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_BANNERS')) {
    exit('Stop!!!');
}

$contents = [];
$contents['info'] = $nv_Lang->getModule('main_page_info');
$contents['detail'] = $nv_Lang->getGlobal('detail');

$contents['rows'] = [];

foreach ($global_array_plans as $row) {
    $contents['rows'][$row['id']]['title'] = [$row['title']];
    $contents['rows'][$row['id']]['blang'] = [$nv_Lang->getModule('blang'), ((!empty($row['blang'])) ? $language_array[$row['blang']]['name'] : $nv_Lang->getModule('blang_all'))];
    $contents['rows'][$row['id']]['size'] = [$nv_Lang->getModule('size'), $row['width'] . ' x ' . $row['height'] . 'px'];
    $contents['rows'][$row['id']]['form'] = [$nv_Lang->getModule('form'), ($nv_Lang->existsModule('form_' . $row['form']) ? $nv_Lang->getModule('form_' . $row['form']) : $row['form'])];
    $contents['rows'][$row['id']]['description'] = [$nv_Lang->getModule('description'), $row['description']];
    $contents['rows'][$row['id']]['allowed'] = isset($global_array_uplans[$row['id']]) ? true : false;
}

$page_title = $module_info['site_title'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$canonicalUrl = getCanonicalUrl($page_url, true, true);

$contents = nv_banner_theme_main($contents);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
