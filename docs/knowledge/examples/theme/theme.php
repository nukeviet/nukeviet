<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Cấu hình phân trang — điều chỉnh theo Bootstrap version
$theme_config = [
    'pagination' => [
        // Bootstrap 3: 'pagination' / ''  / ''
        // Bootstrap 4/5: 'pagination justify-content-center' / 'page-item' / 'page-link'
        'ul_class' => 'pagination',
        'li_class' => '',
        'a_class'  => ''
    ]
];

/**
 * Hàm bắt buộc — render email HTML của hệ thống
 */
function nv_mailHTML($title, $content, $footer = '')
{
    // Xây dựng HTML email ra return string
    return '<html>...</html>';
}

/**
 * Hàm bắt buộc — render trang site đầy đủ (bọc MODULE_CONTENT + block positions)
 * $full = false → dùng simple.tpl (không có block positions)
 */
function nv_site_theme($contents, $full = true)
{
    // Load layout file, assign block positions, return HTML
    return '';
}

/**
 * Hàm bắt buộc — xử lý lỗi theme
 */
function nv_error_theme($title, $content, $code)
{
    // Render trang lỗi
    return '';
}
