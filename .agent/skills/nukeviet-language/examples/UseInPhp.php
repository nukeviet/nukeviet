<?php
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
