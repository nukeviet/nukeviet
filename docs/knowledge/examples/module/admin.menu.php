<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

// PATTERN 1 — module đơn giản (như page): chỉ khai báo $submenu.
// Chỉ khai báo $submenu (menu hiển thị trên UI)
// $submenu['content'] = $nv_Lang->getModule('menu_content'); // key tương ứng với tên func

// Config menu chỉ hiển thị với super admin
// if (defined('NV_IS_SPADMIN')) {
//     $submenu['config'] = $nv_Lang->getModule('menu_config');
// }

// ==================================================

// PATTERN 2 — module phức tạp (như news): khai báo cả $allow_func và $submenu.

// Module phức tạp có thể khai báo $allow_func và $submenu cùng nhau ở đây
$allow_func = ['main', 'content', 'edit', 'del'];

// Submenu dạng đơn giản
$submenu['content'] = $nv_Lang->getModule('menu_content');

// Submenu dạng lồng nhau (nested)
$submenu['setting'] = [
    'title'   => $nv_Lang->getModule('menu_setting'),
    'submenu' => [
        'voices' => $nv_Lang->getModule('menu_voices'),
        'config' => $nv_Lang->getModule('menu_config'),
    ]
];

// Thêm func theo quyền
if (defined('NV_IS_SPADMIN')) {
    $allow_func[] = 'system';
    $submenu['system'] = $nv_Lang->getModule('menu_system');
}
