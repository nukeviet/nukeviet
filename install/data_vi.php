<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$my_lang['modules'] = array();
$my_lang['modules']['about'] = 'Giới thiệu';
$my_lang['modules']['about_for_acp'] = '';
$my_lang['modules']['news'] = 'Tin Tức';
$my_lang['modules']['news_for_acp'] = '';
$my_lang['modules']['users'] = 'Thành viên';
$my_lang['modules']['users_for_acp'] = 'Tài khoản';
$my_lang['modules']['contact'] = 'Liên hệ';
$my_lang['modules']['contact_for_acp'] = '';
$my_lang['modules']['statistics'] = 'Thống kê';
$my_lang['modules']['statistics_for_acp'] = '';
$my_lang['modules']['voting'] = 'Thăm dò ý kiến';
$my_lang['modules']['voting_for_acp'] = '';
$my_lang['modules']['banners'] = 'Quảng cáo';
$my_lang['modules']['banners_for_acp'] = '';
$my_lang['modules']['seek'] = 'Tìm kiếm';
$my_lang['modules']['seek_for_acp'] = '';
$my_lang['modules']['menu'] = 'Menu Site';
$my_lang['modules']['menu_for_acp'] = '';
$my_lang['modules']['comment'] = 'Bình luận';
$my_lang['modules']['comment_for_acp'] = 'Quản lý bình luận';
$my_lang['modules']['siteterms'] = 'Điều khoản sử dụng';
$my_lang['modules']['siteterms_for_acp'] = '';
$my_lang['modules']['feeds'] = 'RSS-feeds';
$my_lang['modules']['Page'] = 'Page';
$my_lang['modules']['Page_for_acp'] = '';
$my_lang['modules']['freecontent'] = 'Giới thiệu sản phẩm';
$my_lang['modules']['freecontent_for_acp'] = '';

$my_lang['modfuncs'] = array();
$my_lang['modfuncs']['users'] = array();
$my_lang['modfuncs']['users']['login'] = 'Đăng nhập';
$my_lang['modfuncs']['users']['register'] = 'Đăng ký';
$my_lang['modfuncs']['users']['lostpass'] = 'Khôi phục mật khẩu';
$my_lang['modfuncs']['users']['active'] = 'Kích hoạt tài khoản';
$my_lang['modfuncs']['users']['editinfo'] = 'Thiếp lập tài khoản';
$my_lang['modfuncs']['users']['memberlist'] = 'Danh sách thành viên';
$my_lang['modfuncs']['users']['logout'] = 'Thoát';
$my_lang['modfuncs']['users']['groups'] = 'Quản lý nhóm';

$my_lang['modfuncs']['statistics'] = array();
$my_lang['modfuncs']['statistics']['allreferers'] = 'Theo đường dẫn đến site';
$my_lang['modfuncs']['statistics']['allcountries'] = 'Theo quốc gia';
$my_lang['modfuncs']['statistics']['allbrowsers'] = 'Theo trình duyệt';
$my_lang['modfuncs']['statistics']['allos'] = 'Theo hệ điều hành';
$my_lang['modfuncs']['statistics']['allbots'] = 'Theo máy chủ tìm kiếm';
$my_lang['modfuncs']['statistics']['referer'] = 'Đường dẫn đến site theo tháng';

$my_lang['blocks_groups'] = array();
$my_lang['blocks_groups']['news'] = array();
$my_lang['blocks_groups']['news']['module.block_newscenter'] = 'Tin mới nhất';
$my_lang['blocks_groups']['news']['global.block_category'] = 'Chủ đề';
$my_lang['blocks_groups']['news']['global.block_tophits'] = 'Tin xem nhiều';
$my_lang['blocks_groups']['banners'] = array();
$my_lang['blocks_groups']['banners']['global.banners1'] = 'Quảng cáo giữa trang';
$my_lang['blocks_groups']['banners']['global.banners2'] = 'Quảng cáo cột trái';
$my_lang['blocks_groups']['banners']['global.banners3'] = 'Quảng cáo cột phải';
$my_lang['blocks_groups']['statistics'] = array();
$my_lang['blocks_groups']['statistics']['global.counter'] = 'Thống kê';
$my_lang['blocks_groups']['about'] = array();
$my_lang['blocks_groups']['about']['global.about'] = 'Giới thiệu';
$my_lang['blocks_groups']['voting'] = array();
$my_lang['blocks_groups']['voting']['global.voting_random'] = 'Thăm dò ý kiến';
$my_lang['blocks_groups']['users'] = array();
$my_lang['blocks_groups']['users']['global.user_button'] = 'Đăng nhập thành viên';
$my_lang['blocks_groups']['theme'] = array();
$my_lang['blocks_groups']['theme']['global.company_info'] = 'Công ty chủ quản';
$my_lang['blocks_groups']['theme']['global.menu_footer'] = 'Các chuyên mục chính';
$my_lang['blocks_groups']['freecontent'] = array();
$my_lang['blocks_groups']['freecontent']['global.free_content'] = 'Sản phẩm';

$my_lang['cron'] = array();
$my_lang['cron']['cron_online_expired_del'] = 'Xóa các dòng ghi trạng thái online đã cũ trong CSDL';
$my_lang['cron']['cron_dump_autobackup'] = 'Tự động lưu CSDL';
$my_lang['cron']['cron_auto_del_temp_download'] = 'Xóa các file tạm trong thư mục tmp';
$my_lang['cron']['cron_del_ip_logs'] = 'Xóa IP log files, Xóa các file nhật ký truy cập';
$my_lang['cron']['cron_auto_del_error_log'] = 'Xóa các file error_log quá hạn';
$my_lang['cron']['cron_auto_sendmail_error_log'] = 'Gửi email các thông báo lỗi cho admin';
$my_lang['cron']['cron_ref_expired_del'] = 'Xóa các referer quá hạn';
$my_lang['cron']['cron_siteDiagnostic_update'] = 'Cập nhật đánh giá site từ các máy chủ tìm kiếm';
$my_lang['cron']['cron_auto_check_version'] = 'Kiểm tra phiên bản NukeViet';
$my_lang['cron']['cron_notification_autodel'] = 'Xóa thông báo cũ';

$my_lang['groups']['NukeViet-Fans'] = 'Nhóm những người hâm mộ hệ thống NukeViet';
$my_lang['groups']['NukeViet-Admins'] = 'Nhóm những người quản lý website xây dựng bằng hệ thống NukeViet';
$my_lang['groups']['NukeViet-Programmers'] = 'Nhóm Lập trình viên hệ thống NukeViet';

$my_lang['vinades_fullname'] = "Công ty cổ phần phát triển nguồn mở Việt Nam";
$my_lang['vinades_address'] = "Phòng 2004 - Tòa nhà CT2 Nàng Hương, 583 Nguyễn Trãi, Hà Nội";
$my_lang['nukeviet_description'] = 'Chia sẻ thành công, kết nối đam mê';
$my_lang['disable_site_content'] = 'Vì lý do kỹ thuật website tạm ngưng hoạt động. Thành thật xin lỗi các bạn vì sự bất tiện này!';

$menu_rows_lev0['about'] = array(
    'title' => $my_lang['modules']['about'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=about",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['news'] = array(
    'title' => $my_lang['modules']['news'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['users'] = array(
    'title' => $my_lang['modules']['users'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['statistics'] = array(
    'title' => $my_lang['modules']['statistics'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=statistics",
    'groups_view' => '2',
    'op' => ''
);
$menu_rows_lev0['voting'] = array(
    'title' => $my_lang['modules']['voting'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=voting",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['seek'] = array(
    'title' => $my_lang['modules']['seek'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=seek",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['contact'] = array(
    'title' => $my_lang['modules']['contact'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=contact",
    'groups_view' => '6',
    'op' => ''
);

$menu_rows_lev1['about'] = array();
$menu_rows_lev1['about'][] = array(
    'title' => 'Giới thiệu về NukeViet',
    'link' => NV_BASE_SITEURL . "index.php?language=vi&nv=about&op=gioi-thieu-ve-nukeviet" . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'gioi-thieu-ve-nukeviet'
);
$menu_rows_lev1['about'][] = array(
    'title' => 'Giới thiệu về NukeViet CMS',
    'link' => NV_BASE_SITEURL . "index.php?language=vi&nv=about&op=gioi-thieu-ve-nukeviet-cms" . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'gioi-thieu-ve-nukeviet-cms'
);
$menu_rows_lev1['about'][] = array(
    'title' => 'Logo và tên gọi NukeViet',
    'link' => NV_BASE_SITEURL . "index.php?language=vi&nv=about&op=logo-va-ten-goi-nukeviet" . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'logo-va-ten-goi-nukeviet'
);
$menu_rows_lev1['about'][] = array(
    'title' => 'Giấy phép sử dụng NukeViet',
    'link' => NV_BASE_SITEURL . "index.php?language=vi&nv=about&op=giay-phep-su-dung-nukeviet" . $global_config['rewrite_exturl'],
    'groups_view' => '67',
    'op' => 'giay-phep-su-dung-nukeviet'
);
$menu_rows_lev1['about'][] = array(
    'title' => 'Những tính năng của NukeViet CMS 4.0',
    'link' => NV_BASE_SITEURL . "index.php?language=vi&nv=about&op=nhung-tinh-nang-cua-nukeviet-cms-4-0" . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'nhung-tinh-nang-cua-nukeviet-cms-4-0'
);
$menu_rows_lev1['about'][] = array(
    'title' => 'Yêu cầu sử dụng NukeViet 4',
    'link' => NV_BASE_SITEURL . "index.php?language=vi&nv=about&op=Yeu-cau-su-dung-NukeViet-4" . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'Yeu-cau-su-dung-NukeViet-4'
);
$menu_rows_lev1['about'][] = array(
    'title' => 'Giới thiệu về Công ty cổ phần phát triển nguồn mở Việt Nam',
    'link' => NV_BASE_SITEURL . "index.php?language=vi&nv=about&op=gioi-thieu-ve-cong-ty-co-phan-phat-trien-nguon-mo-viet-nam" . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'gioi-thieu-ve-cong-ty-co-phan-phat-trien-nguon-mo-viet-nam'
);
$menu_rows_lev1['about'][] = array(
    'title' => 'Ủng hộ, hỗ trợ và tham gia phát triển NukeViet',
    'link' => NV_BASE_SITEURL . "index.php?language=vi&nv=about&op=ung-ho-ho-tro-va-tham-gia-phat-trien-nukeviet" . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'ung-ho-ho-tro-va-tham-gia-phat-trien-nukeviet'
);

$menu_rows_lev1['news'] = array();
$menu_rows_lev1['news'][] = array(
    'title' => 'Đối tác',
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=Doi-tac",
    'groups_view' => '6',
    'op' => 'Doi-tac'
);
$menu_rows_lev1['news'][] = array(
    'title' => 'Tuyển dụng',
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=Tuyen-dung",
    'groups_view' => '6',
    'op' => 'Tuyen-dung'
);
$menu_rows_lev1['news'][] = array(
    'title' => 'Rss',
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=rss",
    'groups_view' => '6',
    'op' => 'rss'
);
$menu_rows_lev1['news'][] = array(
    'title' => 'Đăng bài viết',
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=content",
    'groups_view' => '6',
    'op' => 'content'
);
$menu_rows_lev1['news'][] = array(
    'title' => 'Tìm kiếm',
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=search",
    'groups_view' => '6',
    'op' => 'search'
);
$menu_rows_lev1['news'][] = array(
    'title' => 'Tin tức',
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=Tin-tuc",
    'groups_view' => '6',
    'op' => 'Tin-tuc'
);
$menu_rows_lev1['news'][] = array(
    'title' => 'Sản phẩm',
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=San-pham",
    'groups_view' => '6',
    'op' => 'San-pham'
);
