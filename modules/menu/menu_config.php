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

/**
 * nv_block_config_menu()
 *
 * @param string $module
 * @param array  $data_block
 * @param array  $lang_block
 * @return string
 */
function nv_block_config_menu($module, $data_block, $lang_block)
{
    global $nv_Cache;
    $html = '';
    $html .= '<div class="form-group">';
    $html .= '	<label class="control-label col-sm-6">' . $lang_block['menu'] . ':</label>';
    $html .= "	<div class=\"col-sm-9\"><select name=\"menuid\" class=\"form-control\">\n";

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_menu ORDER BY id DESC';
    // Module menu của hệ thống không ảo hóa, do đó chỉ định cache trực tiếp vào module tránh lỗi khi gọi file từ giao diện
    $list = $nv_Cache->db($sql, 'id', 'menu');
    foreach ($list as $l) {
        $sel = ($data_block['menuid'] == $l['id']) ? ' selected' : '';
        $html .= '<option value="' . $l['id'] . '" ' . $sel . '>' . $l['title'] . "</option>\n";
    }

    $html .= "	</select></div>\n";
    $html .= '</div>';
    $html .= '<div class="form-group">';
    $html .= '<label class="control-label col-sm-6">';
    $html .= $lang_block['title_length'];
    $html .= ':</label>';
    $html .= '<div class="col-sm-18">';
    $html .= '<input type="text" class="form-control" name="config_title_length" value="' . $data_block['title_length'] . '"/>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

/**
 * nv_block_config_menu_submit()
 *
 * @param string $module
 * @param array  $lang_block
 * @return array
 */
function nv_block_config_menu_submit($module, $lang_block)
{
    global $nv_Request;
    $return = [];
    $return['error'] = [];
    $return['config'] = [];
    $return['config']['menuid'] = $nv_Request->get_int('menuid', 'post', 0);
    $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 24);

    return $return;
}
