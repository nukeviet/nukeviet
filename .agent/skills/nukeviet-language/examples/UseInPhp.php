<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Truy cập đơn giản
echo $nv_Lang->getModule('menu_content');

// Chuỗi có tham số (sprintf)
$msg = $nv_Lang->getModule('error_msg', 'Tên lỗi'); // Nếu có nhiều tham số hơn: $nv_Lang->getModule('error_msg', 'Tên lỗi', 'Chi tiết lỗi', ...);
$msg = $nv_Lang->getModule('item_count', $total);

// Chuỗi hệ thống ($lang_global)
echo $nv_Lang->getGlobal('save');      // 'Lưu thay đổi'
echo $nv_Lang->getGlobal('cancel');    // 'Hủy bỏ'
echo $nv_Lang->getGlobal('edit');      // 'Sửa'
echo $nv_Lang->getGlobal('delete');    // 'Xóa'
