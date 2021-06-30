<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
     * @param array  $lang_block
     * @return string
     */
    function nv_block_vertica_menu_note($module, $data_block, $lang_block)
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '	<div class="col-sm-18 col-sm-offset-6"><div class="alert alert-info panel-block-content-last">' . $lang_block['menu_note_auto'] . '</div></div>';
        $html .= '</div>';

        return $html;
    }
}

if (defined('NV_SYSTEM')) {
    global $nv_vertical_menu;

    $content = '';

    if (!empty($nv_vertical_menu)) {
        $content .= "<div id=\"ver_menu\">\n";

        foreach ($nv_vertical_menu as $menu) {
            $content .= ($menu[2]) ? '<a href="' . $menu[1] . '" class="current">' . $menu[0] . "</a>\n" : '<a href="' . $menu[1] . '">' . $menu[0] . "</a>\n";
            if (!empty($menu['submenu'])) {
                foreach ($menu['submenu'] as $sub_menu) {
                    $content .= ($sub_menu[2]) ? '<a href="' . $sub_menu[1] . '" class="sub_current">' . $sub_menu[0] . "</a>\n" : '<a href="' . $sub_menu[1] . '" class="sub_normal">' . $sub_menu[0] . "</a>\n";
                }
            }
        }

        $content .= "</div>\n";
    }
}
