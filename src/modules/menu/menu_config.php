<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * @param string $module
 * @param array  $data_block
 * @return string
 */
function nv_block_config_menu($module, $data_block)
{
    global $nv_Cache, $nv_Lang;

    $nv_Lang->loadModule('menu', false, true);

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_block_tpl_dir('block.menu_config.tpl', module: $module));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('DATA', $data_block);

    // Module menu của hệ thống không ảo hóa, do đó chỉ định cache trực tiếp vào module tránh lỗi khi gọi file từ giao diện
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_menu ORDER BY id DESC';
    $list = $nv_Cache->db($sql, 'id', 'menu');

    $tpl->assign('MENUS', $list);

    $content = $tpl->fetch('block.menu_config.tpl');
    $nv_Lang->changeLang();

    return $content;
}

/**
 * @param string $module
 * @return array
 */
function nv_block_config_menu_submit($module)
{
    global $nv_Request;
    $return = [];
    $return['error'] = [];
    $return['config'] = [];
    $return['config']['menuid'] = $nv_Request->get_int('menuid', 'post', 0);
    $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 24);
    $return['config']['show_home'] = (int) $nv_Request->get_bool('config_show_home', 'post', false);
    $return['config']['show_icon'] = (int) $nv_Request->get_bool('config_show_icon', 'post', false);

    return $return;
}
