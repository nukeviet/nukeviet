<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_STATISTICS')) {
    exit('Stop!!!');
}

/**
 * nv_theme_statistics_referer()
 *
 * @param array $cts
 * @return string
 */
function nv_theme_statistics_referer($cts)
{
    list($template, $dir) = get_module_tpl_dir('referer.tpl', true);
    $xtpl = new XTemplate('referer.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);

    // Thống kê ngày của tháng
    $xtpl->assign('CTS', $cts);

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allreferers()
 *
 * @param int   $num_items
 * @param array $cts
 * @param mixed $host_list
 * @return string
 */
function nv_theme_statistics_allreferers($host_list, $generate_page)
{
    list($template, $dir) = get_module_tpl_dir('allreferers.tpl', true);
    $xtpl = new XTemplate('allreferers.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);

    if (!empty($host_list)) {
        foreach ($host_list as $value) {
            $xtpl->assign('LOOP', $value);

            if (!empty($value['count'])) {
                $xtpl->parse('main.loop.progress');
            }
            $xtpl->parse('main.loop');
        }

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.gp');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allbots()
 *
 * @param array  $bot_list
 * @param string $generate_page
 * @return string
 */
function nv_theme_statistics_allbots($bot_list, $generate_page)
{
    list($template, $dir) = get_module_tpl_dir('allbots.tpl', true);
    $xtpl = new XTemplate('allbots.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);

    if (!empty($bot_list)) {
        foreach ($bot_list as $value) {
            $xtpl->assign('LOOP', $value);

            if (!empty($value['count'])) {
                $xtpl->parse('main.loop.progress');
            }
            $xtpl->parse('main.loop');
        }

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.gp');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allos()
 *
 * @param array  $os_list
 * @param string $generate_page
 * @return string
 */
function nv_theme_statistics_allos($os_list, $generate_page)
{
    list($template, $dir) = get_module_tpl_dir('allos.tpl', true);
    $xtpl = new XTemplate('allos.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);

    if (!empty($os_list)) {
        foreach ($os_list as $value) {
            $xtpl->assign('LOOP', $value);

            if (!empty($value['count'])) {
                $xtpl->parse('main.loop.progress');
            }
            $xtpl->parse('main.loop');
        }

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.gp');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allbrowsers()
 *
 * @param array  $browsers_list
 * @param string $generate_page
 * @return string
 */
function nv_theme_statistics_allbrowsers($browsers_list, $generate_page)
{
    list($template, $dir) = get_module_tpl_dir('allbrowsers.tpl', true);
    $xtpl = new XTemplate('allbrowsers.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);

    if (!empty($browsers_list)) {
        foreach ($browsers_list as $value) {
            $xtpl->assign('LOOP', $value);

            if (!empty($value['count'])) {
                $xtpl->parse('main.loop.progress');
            }
            $xtpl->parse('main.loop');
        }

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.gp');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allcountries()
 *
 * @param array  $countries_list
 * @param string $generate_page
 * @return string
 */
function nv_theme_statistics_allcountries($countries_list, $generate_page)
{
    list($template, $dir) = get_module_tpl_dir('allcountries.tpl', true);
    $xtpl = new XTemplate('allcountries.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);

    if (!empty($countries_list)) {
        foreach ($countries_list as $value) {
            $xtpl->assign('LOOP', $value);

            if (!empty($value['count'])) {
                $xtpl->parse('main.loop.progress');
            }
            $xtpl->parse('main.loop');
        }

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.gp');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_main()
 *
 * @param array $ctsy
 * @param array $ctsm
 * @param array $ctsdm
 * @param array $ctsdw
 * @param array $ctsc
 * @param array $ctsb
 * @param array $ctso
 * @param array $ctsh
 * @return string
 */
function nv_theme_statistics_main($ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh)
{
    list($template, $dir) = get_module_tpl_dir('main.tpl', true);
    $xtpl = new XTemplate('main.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);

    // Thống kê theo giờ trong ngày
    $xtpl->assign('CTSH', $ctsh);

    // Thống kê theo ngày trong tuần
    $xtpl->assign('CTSDW', $ctsdw);

    // Thống kê ngày của tháng
    $xtpl->assign('CTSDM', $ctsdm);

    // Thống kê tháng của năm
    $xtpl->assign('CTSM', $ctsm);

    // Thống kê theo năm
    $xtpl->assign('CTSY', $ctsy);

    //Thong ke theo quoc gia
    $xtpl->assign('CTSC', $ctsc);

    foreach ($ctsc['rows'] as $value) {
        $value['proc'] = !empty($value['count']) ? ceil(($value['count'] / $ctsc['max']) * 100) : 0;
        $xtpl->assign('CTLOOP', $value);

        if (!empty($value['count'])) {
            $xtpl->parse('main.ctloop.progress');
        }
        $xtpl->parse('main.ctloop');
    }

    if (!empty($ctsc['others'])) {
        $xtpl->parse('main.ctot');
    }

    //Thong ke theo trinh duyet
    $xtpl->assign('CTSB', $ctsb);

    foreach ($ctsb['rows'] as $value) {
        $value['proc'] = !empty($value['count']) ? ceil(($value['count'] / $ctsc['max']) * 100) : 0;
        $xtpl->assign('BRLOOP', $value);

        if (!empty($value['count'])) {
            $xtpl->parse('main.brloop.progress');
        }
        $xtpl->parse('main.brloop');
    }

    if (!empty($ctsb['others'])) {
        $xtpl->parse('main.brot');
    }

    //Thong ke theo he dieu hanh
    $xtpl->assign('CTSO', $ctso);

    foreach ($ctso['rows'] as $value) {
        $value['proc'] = !empty($value['count']) ? ceil(($value['count'] / $ctsc['max']) * 100) : 0;
        $xtpl->assign('OSLOOP', $value);

        if (!empty($value['count'])) {
            $xtpl->parse('main.osloop.progress');
        }
        $xtpl->parse('main.osloop');
    }

    if ($ctso['others']) {
        $xtpl->parse('main.osot');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}
