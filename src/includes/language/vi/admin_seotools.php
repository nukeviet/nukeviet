<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Language Tiếng Việt
 * @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
 * @Createdate Mar 04, 2010, 03:22:00 PM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['pagetitle'] = 'Cấu hình tag "title"';
$lang_module['metaTagsConfig'] = 'Cấu hình Meta-Tags';

$lang_module['sitemapPing'] = 'Sitemap Ping';
$lang_module['searchEngine'] = 'Máy chủ tìm kiếm';
$lang_module['searchEngineConfig'] = 'Quản lý Máy chủ tìm kiếm';
$lang_module['searchEngineName'] = 'Tên Máy chủ tìm kiếm';
$lang_module['searchEngineActive'] = 'Kích hoạt';
$lang_module['searchEngineSelect'] = 'Hãy chọn Máy chủ';
$lang_module['sitemapModule'] = 'Hãy chọn Module';
$lang_module['sitemapView'] = 'Xem Sitemap';
$lang_module['sitemapSend'] = 'Gửi đi';
$lang_module['PingNotSupported'] = 'PING không được hỗ trợ';
$lang_module['pleasePingAgain'] = 'Bạn vừa mới gửi đi rồi. Hãy đợi một thời gian nữa';
$lang_module['searchEngineValue'] = 'Đường dẫn để PING';
$lang_module['searchEngineFailed'] = 'Lỗi link để ping';
$lang_module['pingOK'] = 'Hệ thống đã gửi file Sitemap thành công. Việc này có thể được thực hiện lại sau 60 phút';
$lang_module['submit'] = 'Thực hiện';
$lang_module['weight'] = 'Stt';

$lang_module['robots'] = 'Cấu hình file robots.txt';
$lang_module['robots_number'] = 'Số thứ tự';
$lang_module['robots_filename'] = 'Tên file';
$lang_module['robots_type'] = 'Chế độ';
$lang_module['robots_type_0'] = 'Cấm truy cập';
$lang_module['robots_type_1'] = 'Không hiển thị trong file robots.txt';
$lang_module['robots_type_2'] = 'Cho phép truy cập';
$lang_module['robots_error_writable'] = 'Lỗi: Hệ thống không ghi được file robots.txt, hãy tạo file robots.txt với nội dung bên dưới và đặt vào thư mục gốc của website';

$lang_module['pagetitle2'] = 'Phương án hiển thị tag "title"';
$lang_module['pagetitleNote'] = '<strong>Chấp nhận các biến:</strong><br /><br />- <strong>pagetitle</strong>: Tiêu đề trang được xác định trong từng trường hợp cụ thể,<br />- <strong>funcname</strong>: Tên function,<br />- <strong>modulename</strong>: Tên module,<br />- <strong>sitename</strong>: Tên của site';
$lang_module['metaTagsGroupName'] = 'Kiểu Nhóm';
$lang_module['metaTagsGroupValue'] = 'Tên Nhóm';
$lang_module['metaTagsNote'] = 'Các Meta-Tags: "%s" được xác định tự động';
$lang_module['metaTagsVar'] = 'Chấp nhận các biến';
$lang_module['metaTagsContent'] = 'Nội dung';
$lang_module['metaTagsOgp'] = 'Kích hoạt meta-Tag Open Graph protocol';
$lang_module['metaTagsOgpNote'] = 'Open Graph protocol: là 1 chuẩn dữ liệu để chia sẻ dữ liệu lên facebook, xem chi tiết tại <a href="http://ogp.me" target="_blank">http://ogp.me</a>';
$lang_module['description_length'] = 'Số ký tự của thẻ meta tag description';
$lang_module['description_note'] = ' = 0 không giới hạn số ký tự';

$lang_module['module'] = 'Module';
$lang_module['custom_title'] = 'Tên gọi ngoài site';

$lang_module['googleplus'] = 'Xác thực Google+';
$lang_module['googleplus_page_title'] = 'Cấu hình xác thực Google+ (quyền tác giả)';
$lang_module['googleplus_list'] = 'Danh sách tài khoản Google+';
$lang_module['googleplus_module'] = 'Cấu hình tài khoản Google+ mặc định cho module';

$lang_module['googleplus_title'] = 'Tên tài khoản profile Google+';
$lang_module['googleplus_idprofile'] = 'ID profile Google+';
$lang_module['googleplus_add'] = 'Thêm mới';
$lang_module['googleplusNote1'] = 'Việc thiết lập xác minh quyền tác giả sẽ giúp bảo vệ bài viết gốc, tránh tình trạng bài copy TOP cao hơn bài gốc. Việc thiết lập này, sẽ được thiết lập cho toàn module. <br />Nếu trong module có chức năng Cấu hình xác thực Google+, thì chức năng đó được ưu tiên hơn';
$lang_module['googleplusNote2'] = 'Nếu link Google+ là https://plus.google.com/105065032561530186108 thì ID profile Google+ chỉ cần nhập 105065032561530186108';

$lang_module['rpc'] = "Dịch vụ PING";
$lang_module['rpc_setting'] = "Cấu hình dịch vụ PING";
$lang_module['rpc_error_timeout'] = "Vui lòng đợi %s nữa để tiếp tục Ping";
$lang_module['rpc_error_titleEmpty'] = "Vui lòng khai báo tên của URL cần Ping";
$lang_module['rpc_error_urlEmpty'] = "Vui lòng khai báo đúng URL cần Ping";
$lang_module['rpc_error_rsschannelEmpty'] = "Vui lòng khai báo đúng kênh RSS của URL này";
$lang_module['rpc_error_serviceEmpty'] = "Dịch vụ chưa khả dụng. Vui lòng thông báo đến Ban quản trị website";
$lang_module['rpc_error_unknown'] = "Lỗi không xác định";
$lang_module['rpc_flerror0'] = "PING thành công";
$lang_module['rpc_flerror1'] = "Lỗi";
$lang_module['rpc_ftitle'] = "PING là một tiện ích miễn phí giúp bạn nhanh chóng tạo chỉ mục cho các trang tin của mình trên các máy chủ tìm kiếm lớn.";
$lang_module['rpc_webtitle'] = "Tiêu đề trang tin";
$lang_module['rpc_weblink'] = "URL của trang tin";
$lang_module['rpc_rsslink'] = "Kênh RSS của trang tin";
$lang_module['rpc_submit'] = "PING !";
$lang_module['rpc_linkname'] = "Máy chủ";
$lang_module['rpc_reruslt'] = "Kết quả";
$lang_module['rpc_message'] = "Thông tin";
$lang_module['rpc_ping'] = "PING khi cập nhật dữ liệu";
$lang_module['rpc_ping_page'] = "PING bài viết";
$lang_module['rpc_finish'] = "Hoàn thành quá trình PING, bạn có muốn chuyển về trang quản lý bài viết ?";
