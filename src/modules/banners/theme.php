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

/**
 * Giao diện chính của module
 *
 * @param array $array
 * @return string
 */
function nv_banner_theme_main($array)
{
    global $module_name, $manament, $nv_Lang, $language_array;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('MANAGEMENT', $manament);
    $tpl->assign('LANGUAGE_ARRAY', $language_array);
    $tpl->assign('ARRAY', $array);

    return $tpl->fetch('main.tpl');
}

/**
 * Giao diện thêm quảng cáo
 *
 * @param array  $global_array_uplans
 * @param string $page_url
 * @return string
 */
function nv_banner_theme_addads($global_array_uplans, $page_url)
{
    global $module_name, $nv_Lang, $lang_array, $manament;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('addads.tpl'));

    $plans = [];
    foreach ($global_array_uplans as $row) {
        $row['title'] .= ' (' . (empty($row['blang']) ? $nv_Lang->getModule('addads_block_lang_all') : $lang_array[$row['blang']]) . ')';
        $row['typeimage'] = (bool) $row['require_image'];
        $row['uploadtype'] = str_replace(',', ', ', $row['uploadtype']);
        $plans[] = $row;
    }

    $current_plan = !empty($plans) ? $plans[0] : ['id' => 0, 'typeimage' => false];

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('FORM_ACTION', $page_url);
    $tpl->assign('MANAGEMENT', $manament);
    $tpl->assign('PLANS', $plans);
    $tpl->assign('CURRENT_PLAN', $current_plan);
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('captcha'));

    return $tpl->fetch('addads.tpl');
}

/**
 * Giao diện thống kê quảng cáo
 *
 * @param array $ads
 * @return string
 */
function nv_banner_theme_stats($ads)
{
    global $module_name, $nv_Lang, $manament;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('stats.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('MANAGEMENT', $manament);
    $tpl->assign('ADS', $ads);
    $tpl->assign('MONTHS', range(1, 12));

    return $tpl->fetch('stats.tpl');
}
