<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
    global $module_info, $lang_module, $lang_global, $manament;

    $xtpl = new XTemplate('home.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if (!empty($contents['rows'])) {
        $xtpl->assign('MAIN_PAGE_INFO', $contents['info']);
        $xtpl->parse('main.if_banner_plan.info');

        foreach ($contents['rows'] as $row) {
            $xtpl->clear_autoreset();
            $xtpl->assign('PLAN_TITLE', $row['title'][0]);
            $xtpl->assign('PLAN_LANG_TITLE', $row['blang'][0]);
            $xtpl->assign('PLAN_LANG_NAME', $row['blang'][1]);
            $xtpl->assign('PLAN_SIZE_TITLE', $row['size'][0]);
            $xtpl->assign('PLAN_SIZE_NAME', $row['size'][1]);
            $xtpl->assign('PLAN_FORM_TITLE', $row['form'][0]);
            $xtpl->assign('PLAN_FORM_NAME', $row['form'][1]);
            $xtpl->assign('PLAN_DESCRIPTION_TITLE', $row['description'][0]);
            $xtpl->assign('PLAN_DESCRIPTION_NAME', $row['description'][1]);
            $xtpl->assign('PLAN_DETAIL', $contents['detail']);
            $xtpl->set_autoreset();
            if ($row['allowed']) {
                $xtpl->parse('main.if_banner_plan.banner_plan.allowed');
            } else {
                $xtpl->parse('main.if_banner_plan.banner_plan.notallowed');
            }
            if (!empty($row['description'][1])) {
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
    global $global_config, $module_info, $module_captcha, $lang_global, $lang_module, $lang_array, $manament;

    $xtpl = new XTemplate('addads.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('FORM_ACTION', $page_url);

    $xtpl->assign('MANAGEMENT', $manament);
    $xtpl->parse('main.management');

    foreach ($global_array_uplans as $row) {
        $row['title'] .= ' (' . (empty($row['blang']) ? $lang_module['addads_block_lang_all'] : $lang_array[$row['blang']]) . ')';
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
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
        $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
        $xtpl->parse('main.recaptcha');
    } elseif ($module_captcha == 'captcha') {
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
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
    global $module_info, $lang_module, $lang_global, $manament;

    $xtpl = new XTemplate('stats.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
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
