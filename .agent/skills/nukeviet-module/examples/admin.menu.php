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
// $submenu['content'] = $lang_module['menu_content']; // key tương ứng với tên func

// Config menu chỉ hiển thị với super admin
// if (defined('NV_IS_SPADMIN')) {
//     $submenu['config'] = $lang_module['menu_config'];
// }

// ==================================================

// PATTERN 2 — module phức tạp (như news): khai báo cả $allow_func và $submenu.

// Module phức tạp có thể khai báo $allow_func và $submenu cùng nhau ở đây
$allow_func = ['main', 'content', 'edit', 'del'];

// Submenu dạng đơn giản
$submenu['content'] = $lang_module['menu_content'];

// Submenu dạng lồng nhau (nested)
$submenu['setting'] = [
    'title'   => $lang_module['menu_setting'],
    'submenu' => [
        'voices' => $lang_module['menu_voices'],
        'config' => $lang_module['menu_config'],
    ]
];

// Thêm func theo quyền
if (defined('NV_IS_SPADMIN')) {
    $allow_func[] = 'system';
    $submenu['system'] = $lang_module['menu_system'];
}
