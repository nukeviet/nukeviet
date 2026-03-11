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
echo $lang_module['menu_content'];

// Chuỗi có tham số (sprintf)
$msg = sprintf($lang_module['error_msg'], 'Tên lỗi');
$msg = sprintf($lang_module['item_count'], $total);

// Chuỗi hệ thống ($lang_global)
echo $lang_global['save'];      // 'Lưu thay đổi'
echo $lang_global['cancel'];    // 'Hủy bỏ'
echo $lang_global['edit'];      // 'Sửa'
echo $lang_global['delete'];    // 'Xóa'
