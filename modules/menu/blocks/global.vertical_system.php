<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!function_exists('nv_block_vertica_menu_note')) {
    /**
     * nv_block_vertica_menu_note()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_vertica_menu_note($module, $data_block)
    {
        global $nv_Lang;

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '	<div class="col-sm-18 col-sm-offset-6"><div class="alert alert-info panel-block-content-last">' . $nv_Lang->getModule('menu_note_auto') . '</div></div>';
        $html .= '</div>';

        return $html;
    }
}

if (defined('NV_SYSTEM')) {
    global $nv_vertical_menu;

    $content = '';

    foreach ($module_info['funcs'] as $key => $values) {
        if (!empty($values['in_submenu'])) {
            $func_custom_name = trim(!empty($values['func_custom_name']) ? $values['func_custom_name'] : $key);
            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . ($key != 'main' ? '&amp;' . NV_OP_VARIABLE . '=' . $key : '');
            $act = $key == $op ? ' class="current"' : '';
            $content .= '<a href="' . $link . '"' . $act . '>' . $func_custom_name . "</a>\n";
        }
    }

    if (!empty($nv_vertical_menu)) {
        foreach ($nv_vertical_menu as $menu) {
            $act = $menu[2] ? ' class="current"' : '';
            $content .= '<a href="' . $menu[1] . '"' . $act . '>' . $menu[0] . "</a>\n";
            if (!empty($menu['submenu'])) {
                foreach ($menu['submenu'] as $sub_menu) {
                    $act = $sub_menu[2] ? ' class="sub_current"' : ' class="sub_normal"';
                    $content .= '<a href="' . $sub_menu[1] . '"' . $act . '>' . $sub_menu[0] . "</a>\n";
                }
            }
        }
    }

    if (!empty($content)) {
        $content = "<div id=\"ver_menu\">\n" . $content . "</div>\n";
    }
}
