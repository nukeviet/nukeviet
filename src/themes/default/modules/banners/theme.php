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
    global $nv_Lang, $manament;

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('MANAGEMENT', $manament);
    $stpl->assign('PLANS', $contents);

    return $stpl->fetch('home.tpl');
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
    global $global_config, $module_captcha, $nv_Lang, $lang_array, $manament;

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

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('MANAGEMENT', $manament);
    $stpl->assign('CAPTCHA', $captcha);
    $stpl->assign('PLANS', $plans);
    $stpl->assign('FORM_ACTION', $page_url);

    return $stpl->fetch('addads.tpl');
}

/**
 * nv_banner_theme_stats()
 *
 * @param array $ads
 * @return string
 */
function nv_banner_theme_stats($ads)
{
    global $manament, $nv_Lang;

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('MANAGEMENT', $manament);
    $stpl->assign('ADS', $ads);

    return $stpl->fetch('stats.tpl');
}
