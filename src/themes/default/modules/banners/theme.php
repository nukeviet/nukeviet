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
    global $module_info, $manament, $nv_Lang, $global_array_uplans, $language_array;

    $xtpl = new XTemplate('home.tpl', get_module_tpl_dir('home.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    if (!empty($contents)) {
        $xtpl->assign('MAIN_PAGE_INFO', $nv_Lang->getModule('main_page_info'));
        $xtpl->parse('main.if_banner_plan.info');

        foreach ($contents as $row) {
            $xtpl->clear_autoreset();
            $xtpl->assign('PLAN_TITLE', $row['title']);
            $xtpl->assign('PLAN_LANG_NAME', ((!empty($row['blang'])) ? $language_array[$row['blang']]['name'] : $nv_Lang->getModule('blang_all')));
            $xtpl->assign('PLAN_SIZE_NAME', $row['width'] . ' x ' . $row['height'] . 'px');
            $xtpl->assign('PLAN_FORM_NAME', ($nv_Lang->existsModule('form_' . $row['form']) ? $nv_Lang->getModule('form_' . $row['form']) : $row['form']));
            $xtpl->assign('PLAN_DESCRIPTION_NAME', $row['description']);
            $xtpl->assign('PLAN_DETAIL', $nv_Lang->getGlobal('detail'));
            $xtpl->set_autoreset();
            if (isset($global_array_uplans[$row['id']])) {
                $xtpl->parse('main.if_banner_plan.banner_plan.allowed');
            } else {
                $xtpl->parse('main.if_banner_plan.banner_plan.notallowed');
            }
            if (!empty($row['description'])) {
                $xtpl->parse('main.if_banner_plan.banner_plan.desc');
            }
            $xtpl->parse('main.if_banner_plan.banner_plan');
        }

        $xtpl->parse('main.if_banner_plan');
    }

    if (defined('NV_IS_BANNER_CLIENT')) {
        $xtpl->assign('MANAGEMENT', $manament);
        $xtpl->parse('main.management');
    } elseif (!defined('NV_IS_USER')) {
        $xtpl->parse('main.login_check');
    } else {
        $xtpl->parse('main.no_permission');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
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
