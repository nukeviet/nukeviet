<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '15/04/2011, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['menu_manager'] = 'Quản lý menu';
$lang_module['type_menu_manager'] = 'Quản lý loại menu';
$lang_module['add_menu'] = 'Thêm khối menu';
$lang_module['edit_menu'] = 'Sửa menu';
$lang_module['menu_name'] = 'Tên menu';
$lang_module['menu_description'] = 'Mô tả';
$lang_module['error_menu_name'] = 'Lỗi: Bạn chưa nhập tên menu';
$lang_module['menu_number'] = 'Số phần tử';
$lang_module['save'] = 'Lưu';
$lang_module['edit'] = 'Sửa';
$lang_module['delete'] = 'Xóa';
$lang_module['errorsave'] = 'Lỗi: Hệ thống không ghi được dữ liệu. Có thể tên menu bị trùng. Vui lòng thử tên khác.';
$lang_module['number'] = 'Số TT';
$lang_module['type_header'] = 'Menu top';
$lang_module['type_along'] = 'Menu dọc';
$lang_module['type_footer'] = 'Menu bottom';
$lang_module['menu'] = 'Menu trực thuộc';
$lang_module['type_tree'] = 'Menu cây thư mục';
$lang_module['m_list'] = 'Danh sách menu';
$lang_module['add_type_menu'] = 'Chọn loại Menu';
$lang_module['module_name'] = 'Chọn module';
$lang_module['cho_module'] = 'Chọn module';
$lang_module['error_menu_block'] = 'Lỗi: Chưa nhập khối menu';
$lang_module['action'] = 'Hoạt động';
$lang_module['display'] = 'Hiển thị';
$lang_module['data_no'] = 'Hệ thống chưa có dữ liệu';
$lang_module['back'] = 'Trở về mục trước ';
$lang_module['add_item'] = 'Thêm mục cho menu';
$lang_module['title'] = 'Tên mục';
$lang_module['item_menu'] = 'Các mục thuộc module';
$lang_module['chomodule'] = 'Liên kết đến module';
$lang_module['select'] = 'Chọn';
$lang_module['note'] = 'Ghi chú';
$lang_module['link'] = 'Đường dẫn liên kết';
$lang_module['module'] = 'Module';
$lang_module['op'] = 'op';
$lang_module['path'] = 'path';
$lang_module['target'] = 'Mở trang liên kết';
$lang_module['type_target1'] = 'Trang hiện tại';
$lang_module['type_target2'] = 'Mở tab mới';
$lang_module['type_target3'] = 'Mở cửa sổ mới';
$lang_module['cat'] = 'Menu này có ';
$lang_module['cat0'] = 'Là mục chính';
$lang_module['cho_item'] = 'Chọn loại của module';
$lang_module['cats'] = 'Thuộc mục';
$lang_module['caton'] = ' menu con nếu xóa các menu con cũng sẽ bị xóa?';
$lang_module['name_block'] = 'Khối menu';
$lang_module['here'] = 'đây';
$lang_module['groups'] = 'Nếu chọn "Nhóm thành viên" hãy đánh dấu vào các nhóm bên dưới';
$lang_module['sub_menu'] = 'menu con';
$lang_module['main_note'] = 'Nhấp vào tên mỗi khối menu bên dưới để thêm các mục menu vào đây cũng như chỉnh sửa các mục menu. Gợi ý: Bạn có thể tạo nhiều khối menu để sử dụng cho các giao diện và vị trí khác nhau.';
$lang_module['add_type_active'] = 'Kiểu active menu.';
$lang_module['add_type_active_note'] = 'Cách thức xác định một menu có đang được kích hoạt hay không bằng cách so sánh liên kết của menu chỉ đến với liên kết hiện tại của trang theo các tiêu chí bên.';
$lang_module['add_type_active_0'] = 'Chính xác với liên kết của menu';
$lang_module['add_type_active_1'] = 'Bắt đầu với liên kết của menu';
$lang_module['add_type_active_2'] = 'Có chứa liên kết của menu';
$lang_module['add_type_css'] = 'Tên lớp CSS.';
$lang_module['add_type_css_info'] = 'Tên class (CSS) để xác định giao diện menu này.';
$lang_module['add_error_module'] = 'Lỗi: Không có module nào được chỉ định';
$lang_module['add_error_module_exist'] = 'Lỗi: Module không tồn tại';
$lang_module['id'] = 'ID';

$lang_module['action_menu_reload'] = 'Nạp lại';
$lang_module['action_menu_reload_confirm'] = 'Hành động này sẽ nạp lại các thành phần là chủ đề của module hiện tại. Bạn có muốn tiếp tục?';
$lang_module['action_menu_reload_success'] = 'Đã nạp lại menu thành công!';
$lang_module['action_menu_reload_none_success'] = 'Nạp menu không thành công. Vui lòng kiểm tra lại thao tác.';
$lang_module['action_menu_reload_note'] = 'Nạp lại các thành phần con là chủ đề của module';
$lang_module['action_menu'] = 'Nạp menu từ';
$lang_module['action_menu_sys_1'] = 'Các menu là các module';
$lang_module['action_menu_sys_2'] = 'Các menu là các module, submenu là các chủ đề hoặc chức năng';
$lang_module['action_menu_sys_3'] = 'Các menu là chủ đề hoặc chức năng module';
$lang_module['icon'] = 'Icon menu';
$lang_module['image'] = 'Hình ảnh';
$lang_module['action_form'] = 'Thực hiện';
$lang_module['msgnocheck'] = 'Bạn cần chọn ít nhất 1 bài viết để thực hiện';
