<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_LIBRABY')) {
    exit('Stop!!!');
}

/**
 * nv_theme_detail()
 *
 * @param array $array_data
 * @return string
 */
function nv_theme_detail($array_data)
{
    global $op;
    [$template, $dir] = get_module_tpl_dir('detail.tpl', true);
    $xtpl = new XTemplate('detail.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);

    $xtpl->assign('CTS', $array_data);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_list()
 *
 * @param array $array_data
 * @param mixed $generate_page
 * @return string
 */
function nv_theme_list($array_data, $generate_page)
{
    [$template, $dir] = get_module_tpl_dir('main.tpl', true);
    $xtpl = new XTemplate('main.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);

    if (!empty($array_data)) {
        foreach ($array_data as $value) {
            $xtpl->assign('LOOP', $value);
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
