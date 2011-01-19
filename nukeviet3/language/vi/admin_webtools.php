<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 8/9/2010, 23:6
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) )
{
    die( 'Stop!!!' );
}

$lang_translator['author'] = "VINADES.,JSC (contact@vinades.vn)";
$lang_translator['createdate'] = "04/03/2010, 15:22";
$lang_translator['copyright'] = "@Copyright (C) 2010 VINADES.,JSC. All rights reserved";
$lang_translator['info'] = "";
$lang_translator['langtype'] = "lang_module";

$lang_module['clearsystem'] = "Dọn dẹp hệ thống";
$lang_module['clearcache'] = "Làm sạch cache";
$lang_module['clearsession'] = "Xóa session file";
$lang_module['cleardumpbackup'] = "Xóa các file backup CSDL";
$lang_module['clearfiletemp'] = "Xóa các file tạm";
$lang_module['clearerrorlogs'] = "Xóa các thông báo lỗi";
$lang_module['submit'] = "Thực hiện";
$lang_module['deletedetail'] = "Đã xóa thành công các file sau đây";

$lang_module['sitemapPing'] = "Sitemap Ping";
$lang_module['searchEngine'] = "Máy chủ tìm kiếm";
$lang_module['searchEngineConfig'] = "Quản lý Máy chủ tìm kiếm";
$lang_module['searchEngineName'] = "Tên Máy chủ tìm kiếm";
$lang_module['searchEngineActive'] = "Kích hoạt";
$lang_module['searchEngineSelect'] = "Hãy chọn Máy chủ";
$lang_module['sitemapModule'] = "Hãy chọn Module";
$lang_module['sitemapView'] = "Xem Sitemap";
$lang_module['sitemapSend'] = "Gửi đi";
$lang_module['PingNotSupported'] = "PING không được hỗ trợ";
$lang_module['pleasePingAgain'] = "Bạn vừa mới gửi đi rồi. Hãy đợi một thời gian nữa";
$lang_module['searchEngineValue'] = "Đường dẫn để PING";
$lang_module['searchEngineFailed'] = "Lỗi link để ping";
$lang_module['pingOK'] = "Hệ thống đã gửi file Sitemap thành công. Việc này có thể được thực hiện lại sau 60 phút";

$lang_module['revision_no_support'] = "Lỗi, chức năng này chỉ dùng cho phiên bản lớn hơn 3.0.12";
$lang_module['revision'] = "Cập nhật theo Revision";
$lang_module['revision_nochange'] = "Hiện tại chưa có bản cập nhật nào mới";
$lang_module['revision_error'] = "Lỗi: Hệ thống không kết nối được với máy chủ SVN, Bạn có thể kiểm tra lại vào thời gian khác";

$lang_module['checkupdate'] = "Kiểm tra phiên bản";
$lang_module['checkSystem'] = "Hệ thống";
$lang_module['checkModules'] = "Modules";
$lang_module['checkContent'] = "Nội dung";
$lang_module['checkValue'] = "Giá trị";
$lang_module['userVersion'] = "Phiên bản đang sử dụng";
$lang_module['onlineVersion'] = "Phiên bản mới nhất";
$lang_module['newVersion_detail'] = "Số phiên bản: %s; Tên phiên bản: %s; Cập nhật: %s";
$lang_module['newVersion_info'] = "NukeViet CMS mà bạn đang sử dụng cần được nâng cấp lên phiên bản mới. Hãy click <a href=\"%s\">Vào đây</a> để tải về";
$lang_module['reCheck'] = "Cập nhật lại thông tin";
$lang_module['moduleName'] = "Tên Module";
$lang_module['moduleInfo'] = "Thông tin";
$lang_module['moduleNote'] = "Ghi chú";
$lang_module['moduleNote1'] = "Module chưa được kiểm tra";
$lang_module['moduleNote1_detail'] = "Rất tiếc là module này chưa được kiểm tra và xác nhận tính hợp lệ từ NUKEVIET GROUP. Sử dụng nó, tức là bạn chấp nhận những rủi ro nằm ngoài tầm kiểm soát của hệ thống";
$lang_module['moduleNote2'] = "Module chưa được cài đặt. Hãy click để tải về";
$lang_module['moduleNote2_link'] = "Danh sách các module mới";
$lang_module['moduleNote3'] = "<a title=\"Hãy Click để tải mới\" href=\"%s\">Phiên bản không xác định</a>";
$lang_module['moduleNote4'] = "<a title=\"Hãy Click để tải về\" href=\"%s\">Cần cập nhật phiên bản mới</a>";
$lang_module['moduleNote5'] = "Đã cài phiên bản mới nhất";
$lang_module['moduleAuthor'] = "Tác giả";
$lang_module['moduleLicense'] = "Bản quyền";
$lang_module['moduleMode'] = "Lưu hành";
$lang_module['moduleModeSys'] = "Cùng hệ thống";
$lang_module['moduleModeOther'] = "Độc lập";
$lang_module['moduleLink'] = "Link tải về";
$lang_module['moduleSupport'] = "Website hỗ trợ";
$lang_module['checkDate'] = "Cập nhật vào";

$lang_module['siteDiagnostic'] = "Chẩn đoán site";
$lang_module['EngineInfo'] = "Thông tin từ các máy chủ tìm kiếm";
$lang_module['diagnosticDate'] = "Cập nhật";
$lang_module['diagnosticGPR'] = "Google<br />PageRank";
$lang_module['diagnosticATR'] = "Alexa<br />Rank";
$lang_module['diagnosticGBL'] = "Google<br />BackLink";
$lang_module['diagnosticYBL'] = "Yahoo<br />BackLink";
$lang_module['diagnosticABL'] = "Alexa<br />BackLink";
$lang_module['diagnosticGID'] = "Google<br />Indexed";
$lang_module['diagnosticYID'] = "Yahoo<br />Indexed";

$lang_module['keywordRank'] = "Hạng site theo từ khóa";
$lang_module['keywordFormTitle'] = "Kiểm tra thứ hạng của site %s trên Google theo từ khóa";
$lang_module['keyword'] = "Từ khóa";
$lang_module['keywordInfo'] = "Hãy nhập từ hoặc cụm từ có số ký tự tối thiểu là 3, tối đa là 60";
$lang_module['accuracy'] = "Độ chính xác";
$lang_module['byKeyword'] = "tối thiểu 1 từ";
$lang_module['byPhrase'] = "Cả cụm từ";
$lang_module['language'] = "Trong phạm vi ngôn ngữ";
$lang_module['langAll'] = "Tất cả";
$lang_module['languageSelect'] = "Cho tất cả ngôn ngữ";
$lang_module['check'] = "Kiểm tra";
$lang_module['currentDomain'] = "Tên miền";
$lang_module['fromEngine'] = "Dữ liệu khai thác từ";
$lang_module['updDate'] = "Cập nhật vào";
$lang_module['mainResult'] = "Kết quả chung";
$lang_module['myPages'] = "Số trang của bạn";
$lang_module['allPages'] = "Tổng số trang";
$lang_module['rankResult'] = "Hạng site của bạn trong Top 50";
$lang_module['rank0'] = "Site của bạn không nằm trong Top 50";
$lang_module['Top10'] = "Top 10 trang của bạn";
$lang_module['Top50'] = "Top 50 trang có chứa từ khóa";
$lang_module['isLocalhost'] = "Công cụ không hỗ trợ Localhost";

$lang_module['autoupdate_system'] = "Nâng cấp site tự động";
$lang_module['autoupdate_get_error'] = "Thông báo: Hệ thống không kiểm tra được thông tin cập nhật phiên bản NukeViet";
$lang_module['autoupdate_download'] = "Download gói cập nhật NukeViet";
$lang_module['autoupdate_download_complete'] = "Quá trình download file thành công, hãy click vào kiểm tra bản cập nhật để tiếp tục quá trình.";
$lang_module['autoupdate_form_upload'] = "Hệ thống phát hiện có phiên bản nâng cấp hệ thống, hãy click vào kiểm tra bản cập nhật để tiếp tục quá trình.";
$lang_module['autoupdate_check_file'] = "Kiểm tra bản cập nhật";
$lang_module['autoupdate_change'] = "Hệ thống kiểm tra thấy bạn đã thay đổi các file sau đây so với bản gốc";
$lang_module['autoupdate_overwrite'] = "Nếu bạn muốn ghi đè các file này, Hãy click vào nút tiến hành nâng cấp";
$lang_module['autoupdate_click_update'] = "Hãy click vào nút tiến hành nâng cấp";
$lang_module['autoupdate_backupfile'] = "Nếu bạn tiếp tục quá trình nâng cấp, hệ thống sẽ tạo tệp tin sao lưu dự phòng các file thay đổi tại";
$lang_module['autoupdate_backupfile_error'] = "Quá trình nâng cấp bị lỗi: Hệ thống không tạo tệp tin sao lưu dự phòng, bạn kiểm tra lại việc chmode thư mục";
$lang_module['autoupdate'] = "Nâng cấp hệ thống";
$lang_module['autoupdate_confirm'] = "Bạn có chắc chắn nâng cấp hệ thống";
$lang_module['autoupdate_error_create_folder'] = "Quá trình nâng cấp bị lỗi: Hệ thống không tạo được các thư mục sau";
$lang_module['autoupdate_error_move_file'] = "Quá trình nâng cấp bị lỗi: Hệ thống không di chuyển được các file sau";
$lang_module['autoupdate_complete_file'] = "Thông báo: Hệ thống đã thực hiện quá trình di chuyển file thành công";
$lang_module['autoupdate_complete'] = "Thực hiện quá trình nâng cấp thành công.";
$lang_module['autoupdate_error_data'] = "Và gặp các lỗi về việc cập nhật CSDL";

?>