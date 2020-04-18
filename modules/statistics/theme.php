<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_IS_MOD_STATISTICS')) {
    die('Stop!!!');
}

/**
 * nv_theme_statistics_referer()
 *
 * @return
 */
function nv_theme_statistics_referer($cts, $total)
{
    global $module_info, $lang_module;

    $xtpl = new XTemplate('referer.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    if ($total) {
        $xtpl->assign('CTS', $cts);

        foreach ($cts['rows'] as $m) {
            if (!empty($m['count'])) {
                $xtpl->assign('M', number_format($m['count']));
                $xtpl->assign('SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg.gif');
                $xtpl->assign('HEIGHT', ceil(($m['count'] / $cts['max']) * 200));

                $xtpl->parse('main.loop.img');
            }

            $xtpl->parse('main.loop');
        }

        foreach ($cts['rows'] as $key => $m) {
            $xtpl->assign('M', $m);

            if ($key == $cts['current_month']) {
                $xtpl->parse('main.loop_1.m_c');
            } else {
                $xtpl->parse('main.loop_1.m_o');
            }

            $xtpl->parse('main.loop_1');
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allreferers()
 *
 * @return
 */
function nv_theme_statistics_allreferers($num_items, $cts, $host_list)
{
    global $module_info, $lang_module;

    $xtpl = new XTemplate('allreferers.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    if ($num_items) {
        if (!empty($host_list)) {
            $xtpl->assign('CTS', $cts);

            $a = 0;
            foreach ($cts['rows'] as $key => $value) {
                $class = ($a % 2 == 0) ? " class=\"second\"" : "";

                $xtpl->assign('CLASS', $class);
                $xtpl->assign('KEY', $key);

                if ($value[0]) {
                    $proc = ceil(($value[0] / $cts['max']) * 100);

                    $xtpl->assign('SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif');
                    $xtpl->assign('WIDTH', $proc * 3);

                    $xtpl->parse('main.loop.img');
                    $value[0] = number_format($value[0]);
                }
                $xtpl->assign('VALUE', $value);
                ++$a;

                $xtpl->parse('main.loop');
            }

            if (!empty($cts['generate_page'])) {
                $xtpl->parse('main.gp');
            }
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allbots()
 *
 * @return
 */
function nv_theme_statistics_allbots($num_items, $bot_list, $cts)
{
    global $module_info, $lang_module, $lang_global;

    $xtpl = new XTemplate('allbots.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    if ($num_items) {
        if (!empty($bot_list)) {
            $xtpl->assign('CTS', $cts);

            $a = 0;
            foreach ($cts['rows'] as $key => $value) {
                $class = ($a % 2 == 0) ? " class=\"second\"" : "";

                $xtpl->assign('CLASS', $class);
                $xtpl->assign('KEY', $key);

                if ($value[0]) {
                    $proc = ceil(($value[0] / $cts['max']) * 100);

                    $xtpl->assign('SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif');
                    $xtpl->assign('WIDTH', $proc * 3);

                    $xtpl->parse('main.loop.img');
                    $value[0] = number_format($value[0]);
                }
                $xtpl->assign('VALUE', $value);
                ++$a;

                $xtpl->parse('main.loop');
            }

            if (!empty($cts['generate_page'])) {
                $xtpl->parse('main.gp');
            }
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allos()
 *
 * @return
 */
function nv_theme_statistics_allos($num_items, $os_list, $cts)
{
    global $module_info, $lang_module, $lang_global;

    $xtpl = new XTemplate('allos.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    if ($num_items) {
        if (!empty($os_list)) {
            $xtpl->assign('CTS', $cts);

            $a = 0;
            foreach ($cts['rows'] as $key => $value) {
                $const = 'PLATFORM_' . strtoupper($key);
                $key = defined($const) ? constant($const) : $lang_global['unknown'];

                $class = ($a % 2 == 0) ? " class=\"second\"" : "";
                $xtpl->assign('CLASS', $class);
                $xtpl->assign('KEY', $key);

                if ($value[0]) {
                    $proc = ceil(($value[0] / $cts['max']) * 100);

                    $xtpl->assign('SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif');
                    $xtpl->assign('WIDTH', $proc * 3);

                    $xtpl->parse('main.loop.img');
                    $value[0] = number_format($value[0]);
                }
                $xtpl->assign('VALUE', $value);
                ++$a;

                $xtpl->parse('main.loop');
            }

            if (!empty($cts['generate_page'])) {
                $xtpl->parse('main.gp');
            }
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allbrowsers()
 *
 * @return
 */
function nv_theme_statistics_allbrowsers($num_items, $browsers_list, $cts)
{
    global $module_info, $lang_module, $lang_global;

    $xtpl = new XTemplate('allbrowsers.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    if ($num_items) {
        if (!empty($browsers_list)) {
            $xtpl->assign('CTS', $cts);

            $a = 0;
            foreach ($cts['rows'] as $key => $value) {
                $const = 'BROWSER_' . strtoupper($key);
                $key = defined($const) ? constant($const) : $key;

                $class = ($a % 2 == 0) ? " class=\"second\"" : "";
                $xtpl->assign('CLASS', $class);
                $xtpl->assign('KEY', $key);

                if ($value[0]) {
                    $proc = ceil(($value[0] / $cts['max']) * 100);

                    $xtpl->assign('SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif');
                    $xtpl->assign('WIDTH', $proc * 3);

                    $xtpl->parse('main.loop.img');
                    $value[0] = number_format($value[0]);
                }
                $xtpl->assign('VALUE', $value);

                ++$a;

                $xtpl->parse('main.loop');
            }

            if (!empty($cts['generate_page'])) {
                $xtpl->parse('main.gp');
            }
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_allcountries()
 *
 * @return
 */
function nv_theme_statistics_allcountries($num_items, $countries_list, $cts)
{
    global $module_info, $lang_module, $lang_global;

    $xtpl = new XTemplate('allcountries.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    if ($num_items) {
        if (!empty($countries_list)) {
            $xtpl->assign('CTS', $cts);

            $a = 0;
            foreach ($cts['rows'] as $key => $value) {
                if ($key == 'ZZ') {
                    $value[0] = $lang_global['unknown'];
                }
                $class = ($a % 2 == 0) ? " class=\"second\"" : "";

                $xtpl->assign('CLASS', $class);
                $xtpl->assign('KEY', $key);

                if ($value[1]) {
                    $proc = ceil(($value[1] / $cts['max']) * 100);

                    $xtpl->assign('SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif');
                    $xtpl->assign('WIDTH', $proc * 3);

                    $xtpl->parse('main.loop.img');
                    $value[1] = number_format($value[1]);
                }
                $xtpl->assign('VALUE', $value);
                ++$a;

                $xtpl->parse('main.loop');
            }

            if (!empty($cts['generate_page'])) {
                $xtpl->parse('main.gp');
            }
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_statistics_main()
 *
 * @return
 */
function nv_theme_statistics_main($ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh)
{
    global $module_info, $lang_module, $lang_global;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    // Thống kê theo giờ trong ngày
    $xtpl->assign('CTS', $ctsh);
    $xtpl->assign('DATA_LABEL', '"' . implode('", "', array_keys($ctsh['rows'])) . '"');
    $xtpl->assign('DATA_VALUE', implode(', ', $ctsh['rows']));

    $xtpl->parse('main.hour');

    // Thống kê theo ngày trong tuần
    $xtpl->assign('CTS', $ctsdw);

    $data_label = array();
    $data_value = array();
    $data_bgcolor = array(
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)'
    );

    foreach ($ctsdw['rows'] as $key => $m) {
        $data_label[] = $m['fullname'];
        $data_value[] = $m['count'];
    }

    $xtpl->assign('DATA_LABEL', '"' . implode('", "', $data_label) . '"');
    $xtpl->assign('DATA_BGCOLOR', '"' . implode('", "', $data_bgcolor) . '"');
    $xtpl->assign('DATA_VALUE', implode(', ', $data_value));

    $xtpl->parse('main.day_k');

    // Thống kê ngày của tháng
    $xtpl->assign('CTS', $ctsdm);
    $xtpl->assign('DATA_LABEL', '"' . implode('", "', array_keys($ctsdm['rows'])) . '"');
    $xtpl->assign('DATA_VALUE', implode(', ', $ctsdm['rows']));

    $xtpl->parse('main.day_m');

    // Thống kê tháng của năm
    $xtpl->assign('CTS', $ctsm);

    $data_label = array();
    $data_value = array();

    foreach ($ctsm['rows'] as $key => $m) {
        $data_label[] = $m['fullname'];
        $data_value[] = $m['count'];
    }

    $xtpl->assign('DATA_LABEL', '"' . implode('", "', $data_label) . '"');
    $xtpl->assign('DATA_VALUE', implode(', ', $data_value));

    $xtpl->parse('main.month');

    // Thống kê theo năm
    $xtpl->assign('CTS', $ctsy);
    $xtpl->assign('DATA_LABEL', '"' . implode('", "', array_keys($ctsy['rows'])) . '"');
    $xtpl->assign('DATA_VALUE', implode(', ', $ctsy['rows']));

    $xtpl->parse('main.year');

    //Thong ke theo quoc gia
    $xtpl->assign('CTS', $ctsc);

    $a = 0;
    foreach ($ctsc['rows'] as $key => $value) {
        if ($key == 'ZZ') {
            $value[0] = $lang_global['unknown'];
        }
        $class = ($a % 2 == 0) ? " class=\"second\"" : "";
        $xtpl->assign('CLASS', $class);
        $xtpl->assign('KEY', $key);

        if ($value[1]) {
            $proc = ceil(($value[1] / $ctsc['max']) * 100);

            $xtpl->assign('SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif');
            $xtpl->assign('WIDTH', $proc * 3);

            $xtpl->parse('main.ct.loop.img');
            $value[1] = number_format($value[1]);
        }
        $xtpl->assign('VALUE', $value);

        ++$a;
        $xtpl->parse('main.ct.loop');
    }

    if ($ctsc['others'][1]) {
        $class = ($a % 2 == 0) ? " class=\"second\"" : "";
        $xtpl->assign('CLASS', $class);
        $xtpl->assign('URL', NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allcountries']);
        $xtpl->parse('main.ct.ot');
    }

    $xtpl->parse('main.ct');
    //Thong ke theo quoc gia

    //Thong ke theo trinh duyet
    $xtpl->assign('CTS', $ctsb);

    $a = 0;
    foreach ($ctsb['rows'] as $key => $value) {
        $const = 'BROWSER_' . strtoupper($key);
        $key = defined($const) ? constant($const) : $key;

        $class = ($a % 2 == 0) ? " class=\"second\"" : "";
        $xtpl->assign('CLASS', $class);
        $xtpl->assign('KEY', $key);

        if ($value[0]) {
            $proc = ceil(($value[0] / $ctsb['max']) * 100);
            $xtpl->assign('SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif');
            $xtpl->assign('WIDTH', $proc * 3);

            $xtpl->parse('main.br.loop.img');
            $value[0] = number_format($value[0]);
        }
        $xtpl->assign('VALUE', $value);

        $xtpl->parse('main.br.loop');
        ++$a;
    }

    if ($ctsb['others'][1]) {
        $class = ($a % 2 == 0) ? " class=\"second\"" : "";
        $xtpl->assign('CLASS', $class);
        $xtpl->assign('URL', NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allbrowsers']);
        $xtpl->parse('main.br.ot');
    }

    $xtpl->parse('main.br');
    //Thong ke theo trinh duyet

    //Thong ke theo he dieu hanh
    $xtpl->assign('CTS', $ctso);

    $a = 0;
    foreach ($ctso['rows'] as $key => $value) {
        $const = 'PLATFORM_' . strtoupper($key);
        $key = defined($const) ? constant($const) : $lang_global['unknown'];

        $class = ($a % 2 == 0) ? " class=\"second\"" : "";
        $xtpl->assign('CLASS', $class);
        $xtpl->assign('KEY', $key);

        if ($value[0]) {
            $proc = ceil(($value[0] / $ctso['max']) * 100);

            $xtpl->assign('SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif');
            $xtpl->assign('WIDTH', $proc * 3);

            $xtpl->parse('main.os.loop.img');
            $value[0] = number_format($value[0]);
        }
        $xtpl->assign('VALUE', $value);
        $xtpl->parse('main.os.loop');
        ++$a;
    }

    if ($ctso['others'][1]) {
        $class = ($a % 2 == 0) ? " class=\"second\"" : "";

        $xtpl->assign('CLASS', $class);
        $xtpl->assign('URL', NV_BASE_MOD_URL . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allos']);
        $xtpl->parse('main.os.ot');
    }

    $xtpl->parse('main.os');
    //Thong ke theo he dieu hanh

    $xtpl->parse('main');

    return $xtpl->text('main');
}
