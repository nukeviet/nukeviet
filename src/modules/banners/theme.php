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
 * Giao diện chính của module Banner
 * @param array $contents
 * @return string
 */
function nv_banner_theme_main(array $contents): string
{
    global $nv_Lang, $manament, $language_array;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('home.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MANAGEMENT', $manament);
    $tpl->assign('CONTENT', $contents);
    $tpl->assign('LANGUAGE_ARRAY', $language_array);

    return $tpl->fetch('home.tpl');
}

/**
 * Giao diện trang thêm banners
 * @param array $global_array_uplans
 * @param string $page_url
 * @return string
 */
function nv_banner_theme_addads(array $global_array_uplans, string $page_url): string
{
    global $nv_Lang, $lang_array, $manament;

    $plans = [];
    foreach ($global_array_uplans as $row) {
        $row['title'] .= ' (' . (empty($row['blang']) ? $nv_Lang->getModule('addads_block_lang_all') : $lang_array[$row['blang']]) . ')';
        $row['typeimage'] = $row['require_image'] ? 1 : 0;
        $row['uploadtype'] = str_replace(',', ', ', $row['uploadtype']);
        $plans[] = $row;
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('addads.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MANAGEMENT', $manament);
    $tpl->assign('FORM_ACTION', $page_url);
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('captcha'));
    $tpl->assign('PLANS', $plans);
    $current_plan = !empty($plans) ? reset($plans) : null;
    $tpl->assign('CURRENT_PLAN', $current_plan);

    return $tpl->fetch('addads.tpl');
}

/**
 * Giao diện trang xem thống kê
 * @param array $ads
 * @return string
 */
function nv_banner_theme_stats($ads)
{
    global $module_info, $manament;

    $xtpl = new XTemplate('stats.tpl', get_module_tpl_dir('stats.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('MANAGEMENT', $manament);
    $xtpl->parse('main.management');

    if (!empty($ads)) {
        foreach ($ads as $row) {
            $xtpl->assign('ads', $row);
            $xtpl->parse('main.ads');
        }
    }

    for ($i = 1; $i <= 12; ++$i) {
        $xtpl->assign('month', $i);
        $xtpl->parse('main.month');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}
