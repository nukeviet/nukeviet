<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/**
 * 1. TRONG GIAO DIỆN ADMIN (VD: funcs/edit.php)
 * Lấy chi tiết bản ghi bằng API đã có
 */
$params = [
    'limit' => 10,
    'page'  => 1,
    'catid' => $nv_Request->get_int('catid', 'post,get', 0)
];

// Gọi Local API
$json_result = nv_local_api('GetList', $params, $admin_info['username'], $module_name);

// Xử lý kết quả
$result = json_decode($json_result, true);

if ($result['code'] == '0000') {
    // Thành công - dùng $result['data']
    $items = $result['data']['items'];
    $total = $result['data']['total'];
} else {
    // Lỗi
    $error = $result['message'];
}

/**
 * 2. MODULE NÀY GỌI API MODULE KHÁC
 * Ví dụ module "order" gọi API của module "products" để lấy sản phẩm
 */
$product = nv_local_api('GetProduct', ['product_id' => $pid], $admin_info['username'], 'products');
$product = json_decode($product, true);

/**
 * 3. GỌI API HỆ THỐNG (Không thuộc module)
 * $module rỗng → resolve class NukeViet\Api\{$cmd}
 */
$sys_result = nv_local_api('SystemInfo', [], $admin_info['username']);
