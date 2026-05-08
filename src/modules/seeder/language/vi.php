<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '02/05/2026, 12:00';
$lang_translator['copyright'] = '@Copyright (C) 2026 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

// Submenu
$lang_module['menu_dashboard'] = 'Dashboard';
$lang_module['menu_run'] = 'Chạy seed';
$lang_module['menu_reset'] = 'Reset dữ liệu';

// Common
$lang_module['module_title'] = 'Seeder — Generator dữ liệu demo';
$lang_module['back_to_dashboard'] = 'Quay về Dashboard';

// Dashboard
$lang_module['dashboard_title'] = 'Tổng quan tình trạng seed';
$lang_module['dashboard_desc'] = 'Trang này hiển thị tóm tắt số lượng item đã được seeder tạo/cập nhật theo từng bước.';
$lang_module['dashboard_step'] = 'Bước';
$lang_module['dashboard_count_created'] = 'Tạo mới';
$lang_module['dashboard_count_updated'] = 'Cập nhật';
$lang_module['dashboard_count_skipped'] = 'Bỏ qua';
$lang_module['dashboard_count_failed'] = 'Lỗi';
$lang_module['dashboard_last_run'] = 'Lần chạy gần nhất';
$lang_module['dashboard_no_data'] = 'Chưa có dữ liệu seed nào được ghi nhận. Vào "Chạy seed" → tick các bước → bấm "Chạy tất cả".';

// Run
$lang_module['run_title'] = 'Chạy seed dữ liệu mẫu';
$lang_module['run_desc'] = 'Tick các bước cần chạy rồi bấm "Chạy các bước đã chọn", hoặc bấm "Chạy tất cả" để seed toàn bộ theo thứ tự đề xuất.';
$lang_module['run_step'] = 'Các bước có sẵn';
$lang_module['run_all'] = 'Chạy tất cả (theo thứ tự)';
$lang_module['run_btn'] = 'Chạy các bước đã chọn';
$lang_module['run_output'] = 'Kết quả';
$lang_module['run_error_no_step'] = 'Hãy chọn ít nhất 1 bước để chạy.';

// Reset
$lang_module['reset_title'] = 'Reset dữ liệu đã seed';
$lang_module['reset_desc'] = 'Reset chỉ xóa các item do seeder đã tạo (theo bảng log). Item do người dùng tạo tay sẽ KHÔNG bị xóa.';
$lang_module['reset_warning'] = 'CẢNH BÁO: Hành động này không thể hoàn tác. Hãy backup DB trước.';
$lang_module['reset_confirm_label'] = 'Gõ chính xác YES để xác nhận';
$lang_module['reset_btn'] = 'Reset toàn bộ';
$lang_module['reset_error_confirm'] = 'Bạn phải gõ YES (chữ in hoa) để xác nhận reset.';

// Steps (cho dashboard)
$lang_module['step_categories'] = 'Chuyên mục (Tin tức)';
$lang_module['step_topics'] = 'Chủ đề (Tin tức)';
$lang_module['step_departments'] = 'Phòng ban (Liên hệ)';
$lang_module['step_menus'] = 'Menu';
$lang_module['step_banner-positions'] = 'Vị trí banner';
$lang_module['step_banners'] = 'Banner mẫu';
$lang_module['step_articles'] = 'Bài viết mẫu';
$lang_module['step_users'] = 'User mẫu';
$lang_module['step_theme-config'] = 'Theme config';
