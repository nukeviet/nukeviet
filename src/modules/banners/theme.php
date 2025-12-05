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
 * nv_banner_theme_main()
 *
 * @param array $contents
 * @param mixed $manament
 * @return string
 */
function nv_banner_theme_main($contents)
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
 * nv_banner_theme_addads()
 *
 * @param array  $global_array_uplans
 * @param string $page_url
 * @return string
 */
function nv_banner_theme_addads($global_array_uplans, $page_url)
{
    global $global_config, $module_info, $module_captcha, $nv_Lang, $lang_array, $manament;

    $captcha = '';
    if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
        $captcha = 'recaptcha3';
    } elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
        $captcha = 'recaptcha';
    } elseif ($module_captcha == 'turnstile') {
        $captcha = 'turnstile';
    } elseif ($module_captcha == 'captcha') {
        $captcha = 'captcha';
    }

    $plans = [];
    foreach ($global_array_uplans as $row) {
        $row['title'] .= ' (' . (empty($row['blang']) ? $nv_Lang->getModule('addads_block_lang_all') : $lang_array[$row['blang']]) . ')';
        $row['typeimage'] = $row['require_image'] ? 'true' : 'false';
        $row['uploadtype'] = str_replace(',', ', ', $row['uploadtype']);
        $plans[] = $row;
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('addads.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MANAGEMENT', $manament);
    $tpl->assign('FORM_ACTION', $page_url);
    $tpl->assign('CAPTCHA', $captcha);
    $tpl->assign('PLANS', $plans);

    return $tpl->fetch('addads.tpl');
}

/**
 * nv_banner_theme_stats()
 *
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
