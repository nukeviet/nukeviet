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

    $xtpl = new XTemplate('addads.tpl', get_module_tpl_dir('addads.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('FORM_ACTION', $page_url);

    $xtpl->assign('MANAGEMENT', $manament);
    $xtpl->parse('main.management');

    foreach ($global_array_uplans as $row) {
        $row['title'] .= ' (' . (empty($row['blang']) ? $nv_Lang->getModule('addads_block_lang_all') : $lang_array[$row['blang']]) . ')';
        $row['typeimage'] = $row['require_image'] ? 'true' : 'false';
        $row['uploadtype'] = str_replace(',', ', ', $row['uploadtype']);
        $xtpl->assign('blockitem', $row);
        $xtpl->parse('main.blockitem');
    }

    // Nếu dùng reCaptcha v3
    if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
        $xtpl->parse('main.recaptcha3');
    }
    // Nếu dùng reCaptcha v2
    elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
        $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode1'));
        $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
        $xtpl->parse('main.recaptcha');
    } elseif ($module_captcha == 'turnstile') {
        $xtpl->parse('main.turnstile');
    } elseif ($module_captcha == 'captcha') {
        $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode'));
        $xtpl->parse('main.captcha');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
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
