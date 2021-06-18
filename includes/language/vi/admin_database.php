<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Language Tiếng Việt
 * @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
 * @Createdate Mar 04, 2010, 03:22:00 PM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Thông tin chung';
$lang_module['database_info'] = 'Thông tin chung về CSDL &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['tables_info'] = 'Các Table thuộc CSDL &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['table_caption'] = 'Thông tin chung về table &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['table_row_caption'] = 'Thông tin về các trường của table &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['db_host_info'] = 'Máy chủ';
$lang_module['db_sql_version'] = 'Phiên bản máy chủ';
$lang_module['db_proto_info'] = 'Phiên bản giao thức';
$lang_module['server'] = 'Tên máy chủ';
$lang_module['db_dbname'] = 'Tên CSDL';
$lang_module['db_uname'] = 'Tài khoản truy cập CSDL';
$lang_module['db_charset'] = 'Bảng mã CSDL';
$lang_module['db_collation'] = 'Mã so sánh CSDL';
$lang_module['db_time_zone'] = 'Múi giờ của CSDL';
$lang_module['table_name'] = 'Tên Table';
$lang_module['table_size'] = 'Dung lượng';
$lang_module['table_max_size'] = 'DL tối đa';
$lang_module['table_datafree'] = 'DL Thừa';
$lang_module['table_numrow'] = 'Dòng';
$lang_module['table_charset'] = 'Mã';
$lang_module['table_type'] = 'Loại';
$lang_module['row_format'] = 'Kiểu dữ liệu';
$lang_module['table_auto_increment'] = 'Số tự động';
$lang_module['table_create_time'] = 'Khởi tạo';
$lang_module['table_update_time'] = 'Cập nhật';
$lang_module['table_check_time'] = 'Kiểm tra';
$lang_module['optimize'] = 'Tối ưu';
$lang_module['savefile'] = 'Lưu trên máy chủ';
$lang_module['download'] = 'Tải về';
$lang_module['download_now'] = 'Tải về dữ liệu hiện tại';
$lang_module['download_all'] = 'Cấu trúc và dữ liệu';
$lang_module['download_str'] = 'Cấu trúc';
$lang_module['ext_sql'] = 'Dạng file SQL';
$lang_module['ext_gz'] = 'Dạng file nén Gzip';
$lang_module['submit'] = 'Thực thi';
$lang_module['third'] = 'Tổng số bảng: %1$d; Tổng dung lượng: %2$s; Tổng dữ liệu thừa: %3$s';
$lang_module['optimize_result'] = 'Hệ thống đã tối ưu các tables:%1$sGiải phóng %2$s dữ liệu thừa';
$lang_module['nv_show_tab'] = 'Thông tin về table &ldquo;%s&rdquo;';
$lang_module['field_name'] = 'Tên trường';
$lang_module['field_type'] = 'Loại';
$lang_module['field_null'] = 'Bắt buộc';
$lang_module['field_key'] = 'Từ khóa';
$lang_module['field_default'] = 'Mặc định';
$lang_module['field_extra'] = 'Bổ sung';
$lang_module['php_code'] = 'Mã PHP';
$lang_module['sql_code'] = 'Mã SQL';
$lang_module['save_data'] = 'Lưu cơ sở dữ liệu';
$lang_module['save_error'] = 'Lỗi: Hệ thống không ghi được file<br /><br />Bạn cần kiểm tra lại thư mục: %1$s và cấp quyền được ghi đối với thư mục đó.';
$lang_module['save_ok'] = 'Lưu dữ liệu thành công.';
$lang_module['save_download'] = 'Click vào đây để download file.';
$lang_module['dump_autobackup'] = 'Kích hoạt tiện ích lưu CSDL';
$lang_module['dump_backup_ext'] = 'Định dạng lưu CSDL';
$lang_module['dump_interval'] = 'Lặp lại công việc sau';
$lang_module['dump_backup_day'] = 'Thời gian lưu file backup CSDL';
$lang_module['file_backup'] = 'Sao lưu dữ liệu';
$lang_module['file_nb'] = 'STT';
$lang_module['file_name'] = 'Tên file';
$lang_module['file_time'] = 'Thời gian backup';
$lang_module['file_size'] = 'Dung lượng';
$lang_module['sampledata'] = 'Xuất dữ liệu mẫu';
$lang_module['sampledata_note'] = 'Đây là cách xuất toàn bộ CSDL của website hiện tại ra file mẫu nhằm mục đích đóng gói chia sẻ toàn bộ website. Khi cài đặt mới hệ thống sẽ phục hồi lại dữ liệu cũ được đóng gói thay vì cài đặt dữ liệu mẫu ở bộ cài. Hãy hoàn thành các mục yêu cầu bên dưới sau đó nhấp nút thực hiện để bắt đầu quá trình';
$lang_module['sampledata_creat'] = 'Tạo mới gói dữ liệu mẫu';
$lang_module['sampledata_list'] = 'Danh sách các gói dữ liệu mẫu đã tạo';
$lang_module['sampledata_empty'] = 'Chưa có gói dữ liệu mẫu nào';
$lang_module['sampledata_start'] = 'Bắt đầu tạo';
$lang_module['sampledata_dat_init'] = 'Tiến trình bắt đầu chạy, vui lòng không tắt trình duyệt cho đến khi có thông báo hoàn thành hoặc thông báo lỗi. Hệ thống đang kiểm tra thông tin';
$lang_module['sampledata_name'] = 'Tên gói dữ liệu mẫu';
$lang_module['sampledata_name_rule'] = 'Chỉ nhập các ký tự từ a-z và 0-9';
$lang_module['sampledata_error_sys'] = 'Lỗi máy chủ, vui lòng tải lại trang và thực hiện lại';
$lang_module['sampledata_error_name'] = 'Vui lòng nhập tên gói dữ liệu mẫu';
$lang_module['sampledata_error_namerule'] = 'Vui lòng chỉ nhập các ký tự từ a-z và 0-9';
$lang_module['sampledata_error_exists'] = 'Gói dữ liệu mẫu này đã tồn tại, bằng cách nhấp vào nút <strong>Bắt đầu tạo</strong> lần nữa hệ thống sẽ ghi đè gói dữ liệu mẫu đã có. Nếu không muốn ghi đè, bạn hãy nhập tên khác';
$lang_module['sampledata_error_writetmp'] = 'Lỗi: Hệ thống không ghi được dữ liệu, hãy cấp quyền ghi cho thư mục %s sau đó thực hiện lại';
$lang_module['sampledata_success_1'] = 'Xuất dữ liệu thành công! Hệ thống đã ghi dữ liệu ra file. Bây giờ bạn có thể dọn dẹp hệ thống để xóa các file thừa sau đó xóa file config và đóng gói bộ code để chia sẻ.';
$lang_module['sampledata_success_2'] = 'Xuất dữ liệu thành công tuy nhiên hệ thống không ghi được ra file. Bạn có thể tải gói dữ liệu thủ công <a href="%s"><strong>tại đây</strong>!</a>';
