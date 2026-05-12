<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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
    [$template, $dir] = get_module_tpl_dir('referer.tpl', true);
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
 * @param mixed $host_list
 * @param mixed $generate_page
 * @return string
 */
function nv_theme_statistics_allreferers($host_list, $generate_page)
{
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('allreferers.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('HOST_LIST', $host_list ?? []);
    $tpl->assign('PAGINATION', $generate_page ?? '');

    return $tpl->fetch('allreferers.tpl');
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
    [$template, $dir] = get_module_tpl_dir('allbots.tpl', true);
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
    [$template, $dir] = get_module_tpl_dir('allos.tpl', true);
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
    [$template, $dir] = get_module_tpl_dir('allbrowsers.tpl', true);
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
    [$template, $dir] = get_module_tpl_dir('allcountries.tpl', true);
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
 * Giao diện main module thống kê
 *
 * @param array $ctsy Theo các năm
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
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));

    $monthLabels = [
        'Jan' => $nv_Lang->getGlobal('jan'),   'Feb' => $nv_Lang->getGlobal('feb'),
        'Mar' => $nv_Lang->getGlobal('mar'),     'Apr' => $nv_Lang->getGlobal('apr'),
        'May' => $nv_Lang->getGlobal('may2'),       'Jun' => $nv_Lang->getGlobal('jun'),
        'Jul' => $nv_Lang->getGlobal('jul'),      'Aug' => $nv_Lang->getGlobal('aug'),
        'Sep' => $nv_Lang->getGlobal('sep'), 'Oct' => $nv_Lang->getGlobal('oct'),
        'Nov' => $nv_Lang->getGlobal('nov'),  'Dec' => $nv_Lang->getGlobal('dec'),
    ];
    $dowLabels = [
        'Sunday'    => $nv_Lang->getGlobal('sunday'),    'Monday'    => $nv_Lang->getGlobal('monday'),
        'Tuesday'   => $nv_Lang->getGlobal('tuesday'),   'Wednesday' => $nv_Lang->getGlobal('wednesday'),
        'Thursday'  => $nv_Lang->getGlobal('thursday'),  'Friday'    => $nv_Lang->getGlobal('friday'),
        'Saturday'  => $nv_Lang->getGlobal('saturday'),
    ];

    $resolveLabels = function (array $cts) use ($monthLabels, $dowLabels): array {
        if (isset($cts['keys'])) {
            $map = count($cts['keys']) === 12 ? $monthLabels : $dowLabels;
            return array_map(fn ($k) => $map[$k] ?? $k, $cts['keys']);
        }
        return $cts['labels'] ?? [];
    };

    $parseChartData = fn (array $cts): array => [
        'caption'          => $cts['caption'] ?? '',
        'total'            => $cts['total'] ?? 0,
        'labels'           => $resolveLabels($cts),
        'values'           => $cts['values'] ?? [],
        'values_formatted' => array_map(
            fn ($v) => $v !== null ? nv_number_format((int) $v) : null,
            $cts['values'] ?? []
        ),
    ];

    // Tính phần trăm progress bar cho mỗi nhóm
    $buildRows = fn (array $rows, int $max): array => array_map(
        fn ($row) => [...$row, 'proc' => ($max > 0 && !empty($row['count'])) ? (int) ceil(($row['count'] / $max) * 100) : 0],
        $rows
    );

    $tpl->assign('LANG', $nv_Lang);

    // Dữ liệu biểu đồ (theo thứ tự thực-tế → lịch sử)
    $tpl->assign('CTSH', $parseChartData($ctsh));
    $tpl->assign('CTSDW', $parseChartData($ctsdw));
    $tpl->assign('CTSDM', $parseChartData($ctsdm));
    $tpl->assign('CTSM', $parseChartData($ctsm));
    $tpl->assign('CTSY', $parseChartData($ctsy));

    // Dữ liệu danh sách
    $tpl->assign('CTSC', [
        'rows' => $buildRows($ctsc['rows'] ?? [], (int) ($ctsc['max'] ?? 0)),
        'others' => $ctsc['others'] ?? '',
        'others_url' => $ctsc['others_url'] ?? '',
    ]);

    $tpl->assign('CTSB', [
        'rows' => $buildRows($ctsb['rows'] ?? [], (int) ($ctsb['max'] ?? 0)),
        'others' => $ctsb['others'] ?? '',
        'others_url' => $ctsb['others_url'] ?? '',
    ]);

    $tpl->assign('CTSO', [
        'rows' => $buildRows($ctso['rows'] ?? [], (int) ($ctso['max'] ?? 0)),
        'others' => $ctso['others'] ?? '',
        'others_url' => $ctso['others_url'] ?? '',
    ]);

    return $tpl->fetch('main.tpl');
}
