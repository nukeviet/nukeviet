<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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

$lang_module['pagetitle'] = 'Cấu hình tag "title"';
$lang_module['metaTagsConfig'] = 'Cấu hình meta-tags';
$lang_module['linkTagsConfig'] = 'Cấu hình link-tags';
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
$lang_module['robots_error_writable'] = 'Lỗi: Hệ thống không ghi được file robots.txt. Hãy tạo file robots.txt với nội dung bên dưới và đặt vào thư mục gốc của website';
$lang_module['pagetitle2'] = 'Phương án hiển thị tag "title"';
$lang_module['pagetitleNote'] = '<strong>Chấp nhận các biến:</strong><br /><br />- <strong>pagetitle</strong>: Tiêu đề trang được xác định trong từng trường hợp cụ thể,<br />- <strong>funcname</strong>: Tên function,<br />- <strong>modulename</strong>: Tên module,<br />- <strong>sitename</strong>: Tên của site';
$lang_module['metaTagsGroupName'] = 'Kiểu Nhóm';
$lang_module['metaTagsGroupValue'] = 'Tên Nhóm';
$lang_module['metaTagsNote'] = 'Các meta-tags: "%s" được xác định tự động';
$lang_module['metaTagsVar'] = 'Chấp nhận các biến';
$lang_module['metaTagsContent'] = 'Nội dung';
$lang_module['metaTagsOgp'] = 'Kích hoạt meta-tag Open Graph protocol';
$lang_module['metaTagsOgpNote'] = 'Open Graph protocol là chuẩn dữ liệu được chia sẻ lên facebook. Xem chi tiết tại <a href="http://ogp.me" target="_blank">http://ogp.me</a>';
$lang_module['description_length'] = 'Số ký tự tối đa cho meta-tag Description';
$lang_module['description_note'] = 'Không hơn 1024 ký tự';
$lang_module['metaTagsSettings'] = 'Cấu hình';
$lang_module['module'] = 'Module';
$lang_module['custom_title'] = 'Tên gọi ngoài site';
$lang_module['rpc'] = 'Dịch vụ PING';
$lang_module['rpc_setting'] = 'Cấu hình dịch vụ PING';
$lang_module['rpc_error_timeout'] = 'Vui lòng đợi %s nữa để tiếp tục Ping';
$lang_module['rpc_error_titleEmpty'] = 'Vui lòng khai báo tên của URL cần Ping';
$lang_module['rpc_error_urlEmpty'] = 'Vui lòng khai báo đúng URL cần Ping';
$lang_module['rpc_error_rsschannelEmpty'] = 'Vui lòng khai báo đúng kênh RSS của URL này';
$lang_module['rpc_error_serviceEmpty'] = 'Dịch vụ chưa khả dụng. Vui lòng thông báo đến Ban quản trị website';
$lang_module['rpc_error_unknown'] = 'Lỗi không xác định';
$lang_module['rpc_flerror0'] = 'PING thành công';
$lang_module['rpc_flerror1'] = 'Lỗi';
$lang_module['rpc_ftitle'] = 'PING là một tiện ích miễn phí giúp bạn nhanh chóng tạo chỉ mục cho các trang tin của mình trên các máy chủ tìm kiếm lớn.';
$lang_module['rpc_webtitle'] = 'Tiêu đề trang tin';
$lang_module['rpc_weblink'] = 'URL của trang tin';
$lang_module['rpc_rsslink'] = 'Kênh RSS của trang tin';
$lang_module['rpc_submit'] = 'PING !';
$lang_module['rpc_linkname'] = 'Máy chủ';
$lang_module['rpc_reruslt'] = 'Kết quả';
$lang_module['rpc_message'] = 'Thông tin';
$lang_module['rpc_ping'] = 'PING khi cập nhật dữ liệu';
$lang_module['rpc_ping_page'] = 'PING bài viết';
$lang_module['rpc_finish'] = 'Hoàn thành quá trình PING. Bạn có muốn chuyển về trang quản lý bài viết?';
$lang_module['private_site'] = 'Chặn các máy chủ tìm kiếm đánh chỉ mục website';
$lang_module['ogp_image'] = 'Hình ảnh mặc định cho thẻ Open Graph<br />(kích thước tốt nhất: 1080px x 1080px)';
$lang_module['linkTags_attribute'] = 'Thuộc tính';
$lang_module['linkTags_value'] = 'Giá trị';
$lang_module['linkTags_add_attribute'] = 'Thêm thuộc tính';
$lang_module['linkTags_rel_val_required'] = 'Bạn cần khai báo giá trị của thuộc tính rel';
$lang_module['linkTags_add'] = 'Thêm thẻ link mới';
$lang_module['linkTags_acceptVars'] = 'Các biến được chấp nhận trong giá trị thuộc tính';
$lang_module['linkTags_del_confirm'] = 'Bạn thục sự muốn xoá?';
$lang_module['add_opensearch_link'] = 'Kích hoạt thẻ link OpenSearch cho plugin tìm kiếm tại các khu vực';
$lang_module['add_opensearch_link_all'] = 'Trên toàn site';
$lang_module['ShortName'] = 'Tên ngắn gọn';
$lang_module['Description'] = 'Mô tả';
$lang_module['ShortName_note'] = 'Không hơn 16 ký tự';
$lang_module['other_seo_tools'] = 'Các công cụ SEO khác';
$lang_module['add_sitelinks_search_box_schema'] = 'Thêm Hộp tìm kiếm liên kết trang web lên Google tìm kiếm';
$lang_module['more_information'] = 'Tìm hiểu thêm';
$lang_module['strdata'] = 'Dữ liệu có cấu trúc';
$lang_module['strdata_organization'] = 'Biểu trưng của tổ chức hiển thị tại Google tìm kiếm';
$lang_module['strdata_organization_logo_guidelines'] = 'Định dạng ảnh: *.jpg, *.png, *.webp; Kích thước tối thiểu: 112x112px; Nền trắng';
$lang_module['strdata_breadcrumblist'] = 'Hiển thị đường dẫn breadcrumb trên Google tìm kiếm';
$lang_module['strdata_localbusiness'] = 'Hiển thị thông tin doanh nghiệp trên Google tìm kiếm';
$lang_module['localbusiness_file_error'] = 'Vui lòng khai báo thông tin doanh nghiệp trước khi kích hoạt lựa chọn này';
$lang_module['localbusiness_information'] = 'Khai báo thông tin doanh nghiệp';
$lang_module['logo_select_img'] = 'Nhấn vào đây để chọn hình';
$lang_module['change_logo'] = 'Thay đổi biểu trưng';
$lang_module['change_logo_guide'] = 'Để thay đổi biểu trưng hãy thực hiện theo các bước';
$lang_module['change_logo_chosen'] = 'Chọn hình bằng cách click vào ô bên';
$lang_module['change_logo_upload'] = 'Chọn vùng hiển thị sau đó nhấn Cắt hình';
$lang_module['filesize'] = 'Dung lượng';
$lang_module['filetype'] = 'Kiểu';
$lang_module['filedimension'] = 'Kích thước thực tế';
$lang_module['displaydimension'] = 'Kích thước hiển thị';
$lang_module['crop'] = 'Cắt hình';
$lang_module['chosen_other'] = 'Chọn hình khác';
$lang_module['bigfile'] = 'Dung lượng file cho phép: <= %1$s';
$lang_module['bigsize'] = 'Kích thước tối đa của hình là: %1$s x %2$s px';
$lang_module['smallsize'] = 'Kích thước tối thiểu của hình là: %1$s x %2$s px';
$lang_module['allowed_type'] = 'Chấp nhận các định dạng: *.jpg, *.png, *.webp';
$lang_module['logo_error_data'] = 'Lỗi: Dữ liệu không đầy đủ. Hãy thử lần nữa!';
$lang_module['localbusiness_name'] = 'Tên doanh nghiệp của tôi';
$lang_module['localbusiness_name_error'] = 'Vui lòng khai báo tên doanh nghiệp của bạn';
$lang_module['localbusiness_address_error'] = 'Vui lòng khai báo địa chỉ doanh nghiệp của bạn';
$lang_module['localbusiness_data_empty'] = 'Vui lòng khai báo thông tin doanh nghiệp theo đúng chuẩn JSON-LD';
$lang_module['localbusiness_reset'] = 'Dữ liệu mẫu';
$lang_module['localbusiness_del_confirm'] = 'Nếu xóa thông tin doanh nghiệp, việc hiển thị thông tin doanh nghiệp trên Google tìm kiếm cũng sẽ bị tắt. Bạn đồng ý xóa?';
