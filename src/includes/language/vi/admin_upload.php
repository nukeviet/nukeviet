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
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['upload_manager'] = 'Quản lý Files';
$lang_module['upload_delimg_confirm'] = 'Bạn có chắc muốn xóa file';
$lang_module['upload_delimgs_confirm'] = 'Bạn có chắc muốn xóa %s file này không';
$lang_module['upload_delimg_success'] = 'Xóa file thành công !';
$lang_module['upload_delimg_unsuccess'] = 'Lỗi không thể xóa file!';
$lang_module['upload_create_too_bigimg'] = 'Kích thước của hình quá lớn';
$lang_module['upload_create_invalid_filetype'] = 'Dạng File không hợp lệ';
$lang_module['upload_file_created'] = 'Đã tạo thành công file ';
$lang_module['upload_file_maxsize'] = 'File quá giới hạn cho phép';
$lang_module['upload_file_error_movefile'] = 'Lỗi không có quyền tạo file, hãy kiểm tra lại chmod thư mục';
$lang_module['upload_file_error_invalidurl'] = 'Đường dẫn file không hợp lệ';
$lang_module['upload_error_browser_ie6'] = 'Lỗi: Hệ thống không hỗ trợ chức năng này trên trình duyệt Internet Explorer 6, Bạn cần nâng cấp trình duyệt lên phiên bản mới hơn hoặc chuyển sang sử dụng các trình duyệt khác như: Mozilla Firefox, Safari, Opera, Chrome ... ';
$lang_module['upload_empty_path'] = 'Thông báo: Bạn cần chọn thư mục để duyệt file';
$lang_module['upload_size'] = 'Kích thước';
$lang_module['upload_width'] = 'Rộng';
$lang_module['upload_height'] = 'Cao';
$lang_module['upload_file'] = 'Upload file';
$lang_module['upload_mode'] = 'Chọn kiểu upload';
$lang_module['upload_mode_remote'] = 'Upload file từ internet';
$lang_module['upload_mode_local'] = 'Upload từ máy tính';
$lang_module['upload_otherurl'] = 'hoặc URL';
$lang_module['upload_delfile'] = 'Xóa file';
$lang_module['upload_cancel'] = 'Hủy';
$lang_module['upload_createimage'] = 'Công cụ ảnh';
$lang_module['upload_add_files'] = 'Thêm file';
$lang_module['type_file'] = 'Tất cả';
$lang_module['type_image'] = 'Hình ảnh';
$lang_module['type_flash'] = 'Flash';
$lang_module['rename'] = 'Đổi tên file';
$lang_module['renamefolder'] = 'Đổi tên Thư mục';
$lang_module['deletefolder'] = 'Xóa Thư mục';
$lang_module['createfolder'] = 'Tạo Thư mục';
$lang_module['recreatethumb'] = 'Tạo lại ảnh thumb';
$lang_module['recreatethumb_note'] = 'Chú ý: Chức năng này sẽ Xóa tất cả thumb thuộc thư mục, cả thư mục con và tạo lại ảnh thumb theo cấu hình mới. Quá trình này có thể kéo dài rất lâu, bạn không được đóng trình duyệt';
$lang_module['recreatethumb_result'] = 'Đã tạo lại ảnh thumb cho';
$lang_module['rename_newname'] = 'Tên mới';
$lang_module['rename_noname'] = 'Bạn chưa đặt tên mới cho file';
$lang_module['rename_error_folder'] = 'Lỗi: Hệ thống không đổi tên được thư mục';
$lang_module['rename_nonamefolder'] = 'Bạn chưa đặt tên mới cho thư mục hoặc tên thư mục không đúng quy chuẩn';
$lang_module['preview'] = 'Xem chi tiết';
$lang_module['movefolder'] = 'Chuyển đến thư mục';
$lang_module['select_folder'] = 'Chọn:';
$lang_module['delete_folder'] = 'Bạn có chắc muốn xóa thư mục này không. Nếu xóa thư mục này đồng nghĩa với việc toàn bộ các file trong thư mục này cũng bị xóa ?';
$lang_module['download'] = 'Tải về';
$lang_module['select'] = 'Chọn';
$lang_module['move'] = 'Di chuyển';
$lang_module['move_multiple'] = 'Di chuyển %s file';
$lang_module['origSize'] = 'Kích thước gốc';
$lang_module['sizenotchanged'] = 'Hình mà bạn muốn tạo mới có kích thước giống hình gốc';
$lang_module['name_folder_error'] = 'Bạn chưa đặt tên cho thư mục hoặc tên không đúng quy chuẩn';
$lang_module['foldername'] = 'Tên thư mục';
$lang_module['folder_exists'] = 'Lỗi! Đã có thư mục cùng tên tồn tại';
$lang_module['notlevel'] = 'Bạn không được cấp quyền thực hiện thao tác này';
$lang_module['notupload'] = 'Thư mục không được phép upload';
$lang_module['errorInfo'] = 'Thông báo';
$lang_module['selectfiletype'] = 'Hiển thị loại file';
$lang_module['refresh'] = 'Cập nhật lại';
$lang_module['author'] = 'Người tải file lên';
$lang_module['author0'] = 'của tất cả';
$lang_module['author1'] = 'của tôi';
$lang_module['uploadError1'] = 'Bạn cần chọn file trên PC hoặc chép đường dẫn tới file vào ô URL';
$lang_module['uploadError2'] = 'Lỗi: URL không đúng quy chuẩn';
$lang_module['uploadError3'] = 'Lỗi: Dữ liệu upload nhiều phần không đúng chuẩn';
$lang_module['pubdate'] = 'Cập nhật';
$lang_module['newSize'] = 'Tạo ảnh mới';
$lang_module['prView'] = 'Xem';
$lang_module['prViewExample'] = 'Xem ví dụ';
$lang_module['prViewExampleError'] = 'Hãy chọn thư mục, phương án và nhập đầy đủ kích thước, chất lượng ảnh thumb trước';
$lang_module['prViewExampleError1'] = 'Lỗi dữ liệu';
$lang_module['prViewExampleError2'] = 'Hệ thống không tìm thấy bất kỳ ảnh nào để đưa ra ví dụ. Bạn cần upload lên ít nhất một ảnh ở thư mục upload';
$lang_module['errorMinX'] = 'Lỗi: Chiều rộng nhỏ hơn mức cho phép';
$lang_module['errorMaxX'] = 'Lỗi: Chiều rộng lớn hơn mức cho phép';
$lang_module['errorMinY'] = 'Lỗi: Chiều cao nhỏ hơn mức cho phép';
$lang_module['errorMaxY'] = 'Lỗi: Chiều cao lớn hơn mức cho phép';
$lang_module['errorEmptyX'] = 'Lỗi: Chiều rộng chưa xác định';
$lang_module['errorEmptyY'] = 'Lỗi: Chiều cao chưa xác định';
$lang_module['clickSize'] = 'Nháy kép để lấy kích thước gốc';
$lang_module['goNewPath'] = 'Truy cập vào thư mục mới';
$lang_module['mirrorFile'] = 'Lưu bản sao ở thư mục cũ';
$lang_module['errorNotSelectFile'] = 'Lỗi: File chưa được chọn';
$lang_module['errorNotCopyFile'] = 'Lỗi: Vì một lý do nào đó hệ thống đã không thể chuyển file sang thư mục mới';
$lang_module['errorNotRenameFile'] = 'Lỗi: Vì một lý do nào đó hệ thống đã không thể thay tên mới cho file';
$lang_module['nopreview'] = 'Không hỗ trợ xem trước loại tệp tin này.';
$lang_module['errorNewSize'] = 'Bạn chỉ có thể tạo ảnh mới với chiều rộng: 10 - %d px, chiều cao: 10 - %d px';
$lang_module['maxSizeSize'] = 'Kích cỡ tối đa: %dx%dpx';
$lang_module['enter_url'] = 'Nhập URL file';

$lang_module['configlogo'] = 'Cấu hình chèn logo';
$lang_module['addlogo'] = 'Thêm Logo';
$lang_module['addlogosave'] = 'Lưu thay đổi';
$lang_module['notlogo'] = 'Lỗi: Hệ thống không tìm thấy file Logo, có thể bạn chưa cấu hình chèn ảnh logo hoặc file ảnh bị xóa, vui lòng cấu hình lại';
$lang_module['upload_logo'] = 'Logo khi thêm vào hình ảnh ';
$lang_module['upload_logo_pos'] = 'Vị trí của logo';
$lang_module['selectimg'] = 'Chọn hình ảnh';
$lang_module['autologo'] = 'Tự động chèn Logo vào ảnh của các module';
$lang_module['autologo_for_upload'] = 'Chèn logo vào tập tin tải lên (nếu là ảnh)';
$lang_module['autologomodall'] = 'Tất cả các module';
$lang_module['logosizecaption'] = 'Kích thước của logo';
$lang_module['imagewith'] = 'Nếu chiều rộng ảnh';
$lang_module['logowith'] = 'Chiều rộng của logo bằng';
$lang_module['logosize3'] = 'Sử dụng nguyên kích thước logo, Kích thước tối đa của logo bằng';
$lang_module['logoposbottomright'] = 'Phía dưới, bên phải';
$lang_module['logoposbottomleft'] = 'Phía dưới, bên trái';
$lang_module['logoposbottomcenter'] = 'Phía dưới, ở giữa';
$lang_module['logoposcenterright'] = 'Ở giữa, bên phải';
$lang_module['logoposcenterleft'] = 'Ở giữa, bên trái';
$lang_module['logoposcentercenter'] = 'Chính giữa ảnh';
$lang_module['logopostopright'] = 'Phía trên, bên phải';
$lang_module['logopostopleft'] = 'Phía trên, bên trái';
$lang_module['logopostopcenter'] = 'Phía trên, ở giữa';
$lang_module['fileimage'] = 'ảnh';
$lang_module['filerelativepath'] = 'Đường dẫn tương đối';
$lang_module['fileabsolutepath'] = 'Đường dẫn tuyệt đối';
$lang_module['altimage'] = 'Chú thích cho hình';
$lang_module['filepathcopied'] = 'Đường dẫn đã được sao chép';

$lang_module['uploadconfig'] = 'Cấu hình upload';
$lang_module['uploadconfig_ban_ext'] = 'Phần mở rộng bị cấm';
$lang_module['uploadconfig_ban_mime'] = 'Loại mime bị cấm';
$lang_module['uploadconfig_types'] = 'Loại files cho phép';
$lang_module['sys_max_size'] = 'Server của bạn chỉ cho phép tải file có dung lượng tối đa';
$lang_module['nv_max_size'] = 'Dung lượng tối đa của file tải lên';
$lang_module['nv_max_width_height'] = 'Kích thước tối đa của hình tải lên';
$lang_module['nv_auto_resize'] = 'Tự động resize ảnh nếu kích thước lớn hơn kích thước tối đa';
$lang_module['upload_checking_mode'] = 'Kiểu kiểm tra file tải lên';
$lang_module['strong_mode'] = 'Mạnh';
$lang_module['mild_mode'] = 'Vừa phải';
$lang_module['lite_mode'] = 'Yếu';
$lang_module['none_mode'] = 'Không';
$lang_module['upload_checking_note'] = 'Máy chủ của bạn không hỗ trợ một số hàm xác định loại file. Nếu chọn "Mạnh", bạn sẽ không thể upload file lên host';

$lang_module['thumbconfig'] = 'Cấu hình ảnh thumbnail';
$lang_module['thumb_width_height'] = 'Kích thước thumbnail của hình ảnh';
$lang_module['thumb_note'] = 'Hình ảnh thumbnail sẽ được dùng tại các vị trí dùng ảnh nhỏ, kích thước này sẽ không thay đổi các file đã có';
$lang_module['thumb_type'] = 'resize theo phương án';
$lang_module['thumb_type_1'] = 'resize ảnh theo chiều rộng';
$lang_module['thumb_type_2'] = 'resize ảnh theo chiều cao';
$lang_module['thumb_type_3'] = 'resize ảnh theo hai chiều';
$lang_module['thumb_type_4'] = 'resize và crop ảnh theo kích thước';
$lang_module['thumb_type_5'] = 'resize và crop top ảnh theo kích thước';
$lang_module['thumb_quality'] = 'Chất lượng hình ảnh resize';
$lang_module['thumb_dir'] = 'Thư mục';
$lang_module['thumb_dir_default'] = 'Cấu hình mặc định';

$lang_module['search'] = 'Tìm kiếm';
$lang_module['order0'] = 'Sắp xếp theo ngày mới';
$lang_module['order1'] = 'Sắp xếp theo ngày cũ';
$lang_module['order2'] = 'Sắp xếp theo tên file';

$lang_module['searchdir'] = 'Tìm kiếm trong thư mục';
$lang_module['searchkey'] = 'Từ khóa tìm kiếm';
$lang_module['original_image'] = 'Ảnh gốc';
$lang_module['thumb_image'] = 'Ảnh thumb';

$lang_module['crop'] = 'Cắt ảnh';
$lang_module['crop_error_small'] = 'Ảnh này kích thước quá nhỏ, không nên cắt';
$lang_module['crop_keep_original'] = 'Giữ lại ảnh gốc';
$lang_module['rotate'] = 'Xoay ảnh';
$lang_module['waiting'] = 'Đang tải dữ liệu, vui lòng đợi';
$lang_module['file_no_exists'] = 'File không tồn tại';
$lang_module['file_name'] = 'Tên file';
$lang_module['upload_status'] = 'Trạng thái';
$lang_module['upload_info'] = 'Đã tải lên %s/%s tệp. Tốc độ %s/s';
$lang_module['upload_stop'] = 'Dừng';
$lang_module['upload_finish'] = 'Hoàn tất';
$lang_module['upload_continue'] = 'Tiếp tục';
$lang_module['addlogo_error_small'] = 'Ảnh này kích thước quá nhỏ, không thể chèn logo vào';
$lang_module['upload_alt_require'] = 'Bắt buộc nhập chú thích cho file khi upload';
$lang_module['upload_auto_alt'] = 'Tự xác định mô tả từ tên ảnh';
$lang_module['upload_alt_note'] = 'Hãy nhập chú thích cho file trước';
$lang_module['upload_view_thumbnail'] = 'Chuyển sang xem theo dạng lưới';
$lang_module['upload_view_detail'] = 'Chuyển sang xem theo dạng danh sách';
$lang_module['upload_chunk'] = 'Chunk Upload';
$lang_module['upload_chunk_help'] = 'Chức năng này hỗ trợ chia nhỏ tập tin upload thành nhiều gói nhỏ, hỗ trợ upload tập tin lớn đến rất lớn lên máy chủ. Nếu bạn không có ý định cho phép upload các tập tin lớn hãy để trống giá bị bên trên';
$lang_module['upload_overflow'] = 'Upload vượt giới hạn';
$lang_module['upload_overflow_help'] = 'Cho phép tải lên các tập tin có kích thước lớn hơn dung lượng tối đa được tải lên. Lưu ý: Chỉ có tác dụng khi tải lên trực tiếp tập tin từ máy tính tại phần quản lý file, phần Chunk Upload bên trên được thiết lập. Giá trị cấu hình tại đây không được nhỏ hơn dung lượng tối đa của file tải lên được thiết lập bên trên';
$lang_module['nv_mobile_mode_img'] = 'Tạo hình ảnh cho chế độ di động';
$lang_module['nv_mobile_mode_img_note'] = 'Chiều rộng hình ảnh (0 = không tạo)';
