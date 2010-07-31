<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language Vietnamese
* @Createdate 13/04/2010, 16:46
*/

 if (!defined( 'NV_MAINFILE' )) {
 die('Stop!!!');
}

$lang_translator['author'] ="VINADES.,JSC (contact@vinades.vn)";
$lang_translator['createdate'] ="04/03/2010, 15:22";
$lang_translator['copyright'] ="@Copyright (C) 2010 VINADES.,JSC. All rights reserved";
$lang_translator['info'] ="";
$lang_translator['langtype'] ="lang_module";
$lang_module['download_config'] ='Cấu hình';
$lang_module['download_filemanager'] ='Quản lý file';
$lang_module['download_addfile'] ='Thêm file mới';
$lang_module['download_editfile'] ='Sửa file';
$lang_module['download_editfilequeue'] ='Kiểm duyệt file tạm';
$lang_module['download_catmanager'] ='Quản lý chủ đề';
$lang_module['download_config'] ='Cấu hình module';
$lang_module['download_comment'] ='Quản lý comment';
$lang_module['download_report'] ='Báo cáo lỗi';
$lang_module['download_filequeue'] ='File chờ kiểm duyệt';
$lang_module['download_yes'] ='Có';
$lang_module['download_no'] ='Không';


# cat.php
$lang_module['category_cat_name'] = 'Tên chủ đề';
$lang_module['category_cat_parent'] = 'Thuộc chủ đề';
$lang_module['category_cat_active'] = 'Hoạt động';
$lang_module['category_cat_feature'] = 'Chức năng';
$lang_module['category_cat_sub'] = 'chủ đề con';
$lang_module['category_cat_maincat'] = 'Chủ đề chính';
$lang_module['category_cat_sort'] = 'Sắp xếp';

# config.php
$lang_module['config_title'] = 'Cấu hình cho module Download';
$lang_module['config_textlimit'] = 'Cho phép tự động duyệt comments';
$lang_module['config_textlimitnum'] = 'Chỉ ra bao nhiêu ký tự sẽ được giữ lại';
$lang_module['config_directdownload'] = 'Chuyển qua chế độ download link trực tiếp';
$lang_module['config_showmessage'] = 'Hiển thị thông báo trên trang chính của module';
$lang_module['config_message'] = 'Nội dung thông báo';
$lang_module['config_folder'] = 'Hiển thị dạng thư mục con thuộc danh mục chính';
$lang_module['config_foldernum'] = 'Số cột thư mục';
$lang_module['config_showcomment'] = 'Cho phép hiển thị email ở mục bình luận';
$lang_module['config_filenum'] = 'Số file trên 1 trang';
$lang_module['config_whodownload'] = 'Ai được tải file';
$lang_module['config_captcha'] = 'Hiển thị mã kiểm tra khi download file';
$lang_module['config_whocomment'] = 'Bình luận cho file';
$lang_module['config_whoreport'] = 'Thông báo liên kết hỏng';
$lang_module['config_whorate'] = 'Bình chọn cho file';
$lang_module['config_whoupload'] = 'Thêm file vào cơ sở dữ liệu';
$lang_module['config_whouploadfile'] = 'Cho phép upload lên server';
$lang_module['config_maxfilesize'] = 'Nếu cho upload thì dung lượng tối đa của file là';
$lang_module['config_maxfilebyte'] = 'bằng byte';
$lang_module['config_maxfilesizesys'] = 'Giới hạn tải lên hệ thống của bạn là';
$lang_module['config_uploadedfolder'] = 'Thư mục chứa những file đã được kiểm duyệt';
$lang_module['config_queuefolder'] = 'Thư mục chứa những file chờ kiểm duyệt';
$lang_module['config_allowfiletype'] = 'Loại file được cho phép tải lên cách nhau bằng dấu phẩy ( <b>,</b> )';
$lang_module['config_confirm'] = 'Chấp nhận';
$lang_module['config_alert_numtextlimit'] = 'Chỉ ra số ký tự sẽ được giữ lại phải là số';
$lang_module['config_alert_numfolder'] = 'Số cột thư mục phải là số';
$lang_module['config_alert_numfile'] = 'Số file trên 1 trang phải là số';
$lang_module['config_alert_filesize'] = 'Nếu cho upload thì dung lượng tối đa của file phải là số';
$lang_module['config_writeable'] = 'Có thể thay đổi !';
$lang_module['config_unwriteable'] = 'Không thể thay đổi, hãy chmod thư mục !';

#delcat.php
$lang_module['delcat_success'] = 'Xóa chủ đề thành công !';
$lang_module['delcat_confirm'] = 'Xác nhận xóa chủ đề';
$lang_module['delcat_movecat'] = 'Toàn bộ bài biết sẽ được chuyển sang chủ đề :';
$lang_module['delcat_catmain'] = 'Chủ đề chính';
$lang_module['delcat_nextstep'] = 'Tiếp tục';
$lang_module['delcat_confirmdel'] = 'Bạn có chắc muốn xóa chủ đề ?';
$lang_module['delcat_nodel'] = 'Không thể xóa chủ đề có chứa các chủ đề con bên trong !';

#delfile.php
$lang_module['delfile_success'] = 'Xóa file thành công !';
$lang_module['delfile_error'] = 'Có lỗi trong quá trình xóa file !';

#delfilelist.php
$lang_module['delfilelist_success'] = 'Xóa file thành công !';

#delfilequeue.php
$lang_module['delfilequeue_success'] = 'Xóa file thành công !';
$lang_module['delfilequeue_error'] = 'Có lỗi trong quá trình xóa file !';

#delfilequeuelist.php
$lang_module['delfilequeuelist_success'] = 'Xóa file thành công !';

#delreport.php
$lang_module['delreport_success'] = 'Report đã được xóa !';

#editcat.php
$lang_module['editcat_success'] = 'Cập nhật thành công !';
$lang_module['editcat_error'] = 'Có lỗi trong quá trình cập nhật dữ liệu !';
$lang_module['editcat_cat'] = 'Sửa chủ đề';
$lang_module['editcat_title'] = 'Tên chủ đề';
$lang_module['editcat_parent'] = 'Thuộc chủ đề';
$lang_module['editcat_maincat'] = 'Chủ đề chính';
$lang_module['editcat_description'] = 'Mô tả chủ đề';
$lang_module['editcat_active'] = 'Kích hoạt';
$lang_module['editcat_save'] = 'Lưu lại';
$lang_module['editcat_error_title'] = 'Tiêu đề không được để trống !';

#editfile.php
$lang_module['editfile_success'] = 'Lưu thông tin thành công !';
$lang_module['editfile_unsuccess'] = 'Có lỗi lưu dữ liệu !';
$lang_module['editfile_titlebox'] = 'Sửa file';
$lang_module['editfile_title'] = 'Tên file';
$lang_module['editfile_cat'] = 'Chủ đề mẹ';
$lang_module['editfile_description'] = 'Mô tả file';
$lang_module['editfile_author'] = 'Người đăng';
$lang_module['editfile_email'] = 'Email người đăng';
$lang_module['editfile_homepage'] = 'Trang chủ';
$lang_module['editfile_selectfile'] = 'Chọn file';
$lang_module['editfile_linkfile'] = 'Hoặc link đến file';
$lang_module['editfile_version'] = 'Phiên bản';
$lang_module['editfile_size'] = 'Dung lượng';
$lang_module['editfile_sizeblank'] = 'Để trống nếu dùng file upload';
$lang_module['editfile_tag'] = 'Tags';
$lang_module['editfile_image'] = 'Hình ảnh';
$lang_module['editfile_active'] = 'Kích hoạt';
$lang_module['editfile_save'] = 'Lưu lại';
$lang_module['editfile_error_title'] = 'Tiêu đề không được để trống !';
$lang_module['editfile_error_email'] = 'Lỗi email không hợp lệ !';
$lang_module['editfile_error_fileupload'] = 'Hãy chọn file để upload hoặc điền vào link trực tiếp!';
$lang_module['editfile_error_filesize'] = 'Dung lượng của file phải là số';
$lang_module['editfile_downloadfile'] = 'Tải file về';

#editfilequeue.php
$lang_module['editfilequeue_success'] = 'Lưu thông tin thành công !';
$lang_module['editfilequeue_unsuccess'] = 'Có lỗi lưu dữ liệu !';
$lang_module['editfilequeue_titlebox'] = 'Sửa file';
$lang_module['editfilequeue_title'] = 'Tên file';
$lang_module['editfilequeue_cat'] = 'Chủ đề mẹ';
$lang_module['editfilequeue_description'] = 'Mô tả file';
$lang_module['editfilequeue_author'] = 'Tên tác giả';
$lang_module['editfilequeue_email'] = 'Email tác giả';
$lang_module['editfilequeue_homepage'] = 'Trang chủ';
$lang_module['editfilequeue_selectfile'] = 'Chọn file';
$lang_module['editfilequeue_linkfile'] = 'Hoặc link đến file';
$lang_module['editfilequeue_version'] = 'Phiên bản';
$lang_module['editfilequeue_size'] = 'Dung lượng';
$lang_module['editfilequeue_sizeblank'] = 'Để trống nếu dùng file upload';
$lang_module['editfilequeue_tag'] = 'Tags';
$lang_module['editfilequeue_image'] = 'Hình ảnh';
$lang_module['editfilequeue_active'] = 'Kích hoạt';
$lang_module['editfilequeue_save'] = 'Lưu lại';
$lang_module['editfilequeue_error_title'] = 'Tiêu đề không được để trống !';
$lang_module['editfilequeue_error_email'] = 'Lỗi email không hợp lệ !';
$lang_module['editfilequeue_error_fileupload'] = 'Hãy chọn file để upload hoặc điền vào link trực tiếp!';
$lang_module['editfilequeue_error_filesize'] = 'Dung lượng của file phải là số';

#file.php
$lang_module['file_id']='ID';
$lang_module['file_name']='Tên file';
$lang_module['file_cat']='Chủ đề mẹ';
$lang_module['file_active']='Hoạt động';
$lang_module['file_feature']='Chức năng';
$lang_module['file_maincat']='Chủ đề chính';
$lang_module['file_checkall']='Chọn tất cả';
$lang_module['file_uncheckall']='Bỏ chọn tất cả';
$lang_module['file_error_file']='Hãy chọn ít nhất 1 file để xóa';
$lang_module['file_del_confirm']='Bạn có chắc muốn xóa không ?';

#filequeue.php
$lang_module['filequeue_id']='ID';
$lang_module['filequeue_name']='Tên file';
$lang_module['filequeue_cat']='Chủ đề mẹ';
$lang_module['filequeue_active']='Hoạt động';
$lang_module['filequeue_feature']='Chức năng';
$lang_module['filequeue_maincat']='Chủ đề chính';
$lang_module['filequeue_checkall']='Chọn tất cả';
$lang_module['filequeue_uncheckall']='Bỏ chọn tất cả';
$lang_module['filequeue_error_filequeue']='Hãy chọn ít nhất 1 file để xóa';

#report.php
$lang_module['report_id']='ID FILE';
$lang_module['report_name']='Tên file';
$lang_module['report_cat']='Ngày gửi';
$lang_module['report_feature']='Chức năng';
$lang_module['report_title']='Tiêu đề:';
$lang_module['report_content']='Nội dung:';
$lang_module['report_maincat']='Chủ đề chính';
$lang_module['report_checkall']='Chọn tất cả';
$lang_module['report_uncheckall']='Bỏ chọn tất cả';
$lang_module['report_error_report']='Hãy chọn ít nhất 1 report để xóa';

#updateconfig.php
$lang_module['updateconfig_error_dirdown']='Lỗi không thể đổi tên thư mục chứa file đã kiểm duyệt';
$lang_module['updateconfig_error_dirqueue']='Lỗi không thể đổi tên thư mục chứa file chưa kiểm duyệt';
$lang_module['updateconfig_error_save']='Cấu hình đã được lưu';
$lang_module['updateconfig_error_error']='Lỗi trong quá trình lưu cấu hình';

#addcat.php
$lang_module['addcat_success']='Thêm vào thành công !';
$lang_module['addcat_error']='Có lỗi thêm dữ liệu !';
$lang_module['addcat_titlebox']='Thêm chủ đề';
$lang_module['addcat_title']='Tên chủ đề';
$lang_module['addcat_maincat']='Chủ đề chính';
$lang_module['addcat_description']='Mô tả chủ đề';
$lang_module['addcat_active']='Kích hoạt';
$lang_module['addcat_active_yes']='có';
$lang_module['addcat_save']='Lưu lại';
$lang_module['addcat_error_title']='Tiêu đề không được để trống !';
$lang_module['addcat_error_cat']='Lỗi: Chủ đề này đã có !';
$lang_module ['addcat_par'] = 'Thuộc chủ đề';

#addfile.php
$lang_module['addfile_success'] = 'Thêm vào thành công !';
$lang_module['addfile_unsuccess'] = 'Có lỗi lưu dữ liệu !';
$lang_module['addfile_titlebox'] = 'Thêm file';
$lang_module['addfile_title'] = 'Tên file';
$lang_module['addfile_cat'] = 'Chủ đề';
$lang_module['addfile_description'] = 'Mô tả file';
$lang_module['addfile_author'] = 'Tên tác giả';
$lang_module['addfile_email'] = 'Email tác giả';
$lang_module['addfile_homepage'] = 'Trang chủ';
$lang_module['addfile_selectfile'] = 'Chọn file';
$lang_module['addfile_linkfile'] = 'Hoặc link đến file';
$lang_module['addfile_version'] = 'Phiên bản';
$lang_module['addfile_size'] = 'Dung lượng';
$lang_module['addfile_sizeblank'] = 'Để trống nếu dùng Browse Server';
$lang_module['addfile_tag'] = 'Tags';
$lang_module['addfile_image'] = 'Hình ảnh';
$lang_module['addfile_active'] = 'Kích hoạt';
$lang_module['addfile_save'] = 'Lưu lại';
$lang_module['addfile_error_title'] = 'Tiêu đề quá ngắn !';
$lang_module['addfile_error_parentid'] = 'Chưa chọn chủ đề cho file!';
$lang_module['addfile_error_email'] = 'Lỗi email không hợp lệ !';
$lang_module['addfile_error_fileupload'] = 'Hãy chọn file để upload hoặc điền vào link trực tiếp!';
$lang_module['addfile_error_filesize'] = 'Dung lượng của file phải là số';
$lang_module['intro_title'] = 'Tóm tắt';
$lang_module['addfile_not_file'] = 'File hoặc link file không được trống';
$lang_module['addfile_copyright'] = 'Bản quyền';
#comment.php
$lang_module['comment'] = "Quản lý bình luận";
$lang_module['weight'] = "ID";
$lang_module['activecomm'] = "Kích hoạt chức năng bình luận của module";
$lang_module['emailcomm'] = "Hiển thị email của người đăng bình luận";

$lang_module['comment_delete'] = "Xóa";
$lang_module['comment_funcs'] = "Chức năng";
$lang_module['comment_email'] = "Người gửi";
$lang_module['comment_topic'] = "Bài viết";
$lang_module['comment_content'] = "Nội dung";
$lang_module['comment_status'] = "Trạng thái";
$lang_module['comment_delete_title'] = "Xóa bình luận";
$lang_module['comment_delete_confirm'] = "Bạn có chắc muốn xóa bình luận ?";
$lang_module['comment_delete_yes'] = "Có";
$lang_module['comment_delete_no'] = "Không";
$lang_module['comment_delete_accept'] = "Chấp nhận";
$lang_module['comment_delete_unsuccess'] = "Có lỗi trong quá trình xóa dữ liệu";
$lang_module['comment_delete_success'] = "Xóa dữ liệu thành công";
$lang_module['comment_enable'] = "Bật";
$lang_module['comment_disable'] = "Tắt";
$lang_module['comment_checkall'] = "Chọn tất cả";
$lang_module['comment_uncheckall'] = "Bỏ chọn tất cả";
$lang_module['comment_nocheck'] = "Hãy chọn ít nhất 1 bình luận để có thể thực hiện";
$lang_module['comment_update_success'] = "Cập nhật thành công !";
?>