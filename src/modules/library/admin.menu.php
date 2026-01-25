<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$menu_sub = [];
$menu_sub['add_new'] = $nv_Lang->getModule('add_new');
$menu_sub['config'] = $nv_Lang->getModule('config');
$submenu['type'] = $nv_Lang->getModule('theloai');
$submenu['main'] = [
    'title' => $nv_Lang->getModule('library_manager'),
    'submenu' => $menu_sub
];

$menu_sub_2 = [];
$menu_sub_2['add_new_2'] = $nv_Lang->getModule('add_new');
$menu_sub_2['config_2'] = $nv_Lang->getModule('config');
$submenu['sach'] = [
    'title' => $nv_Lang->getModule('library_manager_2'),
    'submenu' => $menu_sub_2
];
