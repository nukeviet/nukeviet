<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$install_lang['modules'] = [];
$install_lang['modules']['about'] = 'Giới thiệu';
$install_lang['modules']['about_for_acp'] = '';
$install_lang['modules']['news'] = 'Tin Tức';
$install_lang['modules']['news_for_acp'] = '';
$install_lang['modules']['users'] = 'Thành viên';
$install_lang['modules']['users_for_acp'] = 'Tài khoản';
$install_lang['modules']['contact'] = 'Liên hệ';
$install_lang['modules']['contact_for_acp'] = '';
$install_lang['modules']['statistics'] = 'Thống kê';
$install_lang['modules']['statistics_for_acp'] = '';
$install_lang['modules']['voting'] = 'Thăm dò ý kiến';
$install_lang['modules']['voting_for_acp'] = '';
$install_lang['modules']['banners'] = 'Quảng cáo';
$install_lang['modules']['banners_for_acp'] = '';
$install_lang['modules']['seek'] = 'Tìm kiếm';
$install_lang['modules']['seek_for_acp'] = '';
$install_lang['modules']['menu'] = 'Menu Site';
$install_lang['modules']['menu_for_acp'] = '';
$install_lang['modules']['comment'] = 'Bình luận';
$install_lang['modules']['comment_for_acp'] = 'Quản lý bình luận';
$install_lang['modules']['siteterms'] = 'Điều khoản sử dụng';
$install_lang['modules']['siteterms_for_acp'] = '';
$install_lang['modules']['feeds'] = 'RSS-feeds';
$install_lang['modules']['Page'] = 'Page';
$install_lang['modules']['Page_for_acp'] = '';
$install_lang['modules']['freecontent'] = 'Giới thiệu sản phẩm';
$install_lang['modules']['freecontent_for_acp'] = '';
$install_lang['modules']['two_step_verification'] = 'Xác thực hai bước';
$install_lang['modules']['two_step_verification_for_acp'] = '';

$install_lang['modfuncs'] = [];
$install_lang['modfuncs']['users'] = [];
$install_lang['modfuncs']['users']['login'] = 'Đăng nhập';
$install_lang['modfuncs']['users']['register'] = 'Đăng ký';
$install_lang['modfuncs']['users']['lostpass'] = 'Khôi phục mật khẩu';
$install_lang['modfuncs']['users']['active'] = 'Kích hoạt tài khoản';
$install_lang['modfuncs']['users']['editinfo'] = 'Thiết lập tài khoản';
$install_lang['modfuncs']['users']['memberlist'] = 'Danh sách thành viên';
$install_lang['modfuncs']['users']['logout'] = 'Thoát';
$install_lang['modfuncs']['users']['groups'] = 'Quản lý nhóm';

$install_lang['modfuncs']['statistics'] = [];
$install_lang['modfuncs']['statistics']['allreferers'] = 'Theo đường dẫn đến site';
$install_lang['modfuncs']['statistics']['allcountries'] = 'Theo quốc gia';
$install_lang['modfuncs']['statistics']['allbrowsers'] = 'Theo trình duyệt';
$install_lang['modfuncs']['statistics']['allos'] = 'Theo hệ điều hành';
$install_lang['modfuncs']['statistics']['allbots'] = 'Theo máy chủ tìm kiếm';
$install_lang['modfuncs']['statistics']['referer'] = 'Đường dẫn đến site theo tháng';

$install_lang['blocks_groups'] = [];
$install_lang['blocks_groups']['news'] = [];
$install_lang['blocks_groups']['news']['module.block_newscenter'] = 'Tin mới nhất';
$install_lang['blocks_groups']['news']['global.block_category'] = 'Chủ đề';
$install_lang['blocks_groups']['news']['global.block_tophits'] = 'Tin xem nhiều';
$install_lang['blocks_groups']['banners'] = [];
$install_lang['blocks_groups']['banners']['global.banners1'] = 'Quảng cáo giữa trang';
$install_lang['blocks_groups']['banners']['global.banners2'] = 'Quảng cáo cột trái';
$install_lang['blocks_groups']['banners']['global.banners3'] = 'Quảng cáo cột phải';
$install_lang['blocks_groups']['statistics'] = [];
$install_lang['blocks_groups']['statistics']['global.counter'] = 'Thống kê';
$install_lang['blocks_groups']['about'] = [];
$install_lang['blocks_groups']['about']['global.about'] = 'Giới thiệu';
$install_lang['blocks_groups']['voting'] = [];
$install_lang['blocks_groups']['voting']['global.voting_random'] = 'Thăm dò ý kiến';
$install_lang['blocks_groups']['users'] = [];
$install_lang['blocks_groups']['users']['global.user_button'] = 'Đăng nhập thành viên';
$install_lang['blocks_groups']['theme'] = [];
$install_lang['blocks_groups']['theme']['global.company_info'] = 'Công ty chủ quản';
$install_lang['blocks_groups']['theme']['global.menu_footer'] = 'Các chuyên mục chính';
$install_lang['blocks_groups']['freecontent'] = [];
$install_lang['blocks_groups']['freecontent']['global.free_content'] = 'Sản phẩm';

$install_lang['cron'] = [];
$install_lang['cron']['cron_online_expired_del'] = 'Xóa các dòng ghi trạng thái online đã cũ trong CSDL';
$install_lang['cron']['cron_dump_autobackup'] = 'Tự động lưu CSDL';
$install_lang['cron']['cron_auto_del_temp_download'] = 'Xóa các file tạm trong thư mục tmp';
$install_lang['cron']['cron_del_ip_logs'] = 'Xóa IP log files, Xóa các file nhật ký truy cập';
$install_lang['cron']['cron_auto_del_error_log'] = 'Xóa các file error_log quá hạn';
$install_lang['cron']['cron_auto_sendmail_error_log'] = 'Gửi email các thông báo lỗi cho admin';
$install_lang['cron']['cron_ref_expired_del'] = 'Xóa các referer quá hạn';
$install_lang['cron']['cron_auto_check_version'] = 'Kiểm tra phiên bản NukeViet';
$install_lang['cron']['cron_notification_autodel'] = 'Xóa thông báo cũ';

$install_lang['groups']['NukeViet-Fans'] = 'Người hâm mộ';
$install_lang['groups']['NukeViet-Admins'] = 'Người quản lý';
$install_lang['groups']['NukeViet-Programmers'] = 'Lập trình viên';

$install_lang['groups']['NukeViet-Fans-desc'] = 'Nhóm những người hâm mộ hệ thống NukeViet';
$install_lang['groups']['NukeViet-Admins-desc'] = 'Nhóm những người quản lý website xây dựng bằng hệ thống NukeViet';
$install_lang['groups']['NukeViet-Programmers-desc'] = 'Nhóm Lập trình viên hệ thống NukeViet';

$install_lang['vinades_fullname'] = 'Công ty cổ phần phát triển nguồn mở Việt Nam';
$install_lang['vinades_address'] = 'Phòng 1706 - Tòa nhà CT2 Nàng Hương, 583 Nguyễn Trãi, Hà Nội';
$install_lang['nukeviet_description'] = 'Chia sẻ thành công, kết nối đam mê';
$install_lang['disable_site_content'] = 'Vì lý do kỹ thuật website tạm ngưng hoạt động. Thành thật xin lỗi các bạn vì sự bất tiện này!';

$menu_rows_lev0['about'] = [
    'title' => $install_lang['modules']['about'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=about',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['news'] = [
    'title' => $install_lang['modules']['news'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['users'] = [
    'title' => $install_lang['modules']['users'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['statistics'] = [
    'title' => $install_lang['modules']['statistics'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=statistics',
    'groups_view' => '2',
    'op' => ''
];
$menu_rows_lev0['voting'] = [
    'title' => $install_lang['modules']['voting'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=voting',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['seek'] = [
    'title' => $install_lang['modules']['seek'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=seek',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['contact'] = [
    'title' => $install_lang['modules']['contact'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=contact',
    'groups_view' => '6',
    'op' => ''
];

$menu_rows_lev1['about'] = [];
$menu_rows_lev1['about'][] = [
    'title' => 'Giới thiệu về NukeViet',
    'link' => NV_BASE_SITEURL . 'index.php?language=vi&nv=about&op=gioi-thieu-ve-nukeviet' . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'gioi-thieu-ve-nukeviet'
];
$menu_rows_lev1['about'][] = [
    'title' => 'Giới thiệu về NukeViet CMS',
    'link' => NV_BASE_SITEURL . 'index.php?language=vi&nv=about&op=gioi-thieu-ve-nukeviet-cms' . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'gioi-thieu-ve-nukeviet-cms'
];
$menu_rows_lev1['about'][] = [
    'title' => 'Logo và tên gọi NukeViet',
    'link' => NV_BASE_SITEURL . 'index.php?language=vi&nv=about&op=logo-va-ten-goi-nukeviet' . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'logo-va-ten-goi-nukeviet'
];
$menu_rows_lev1['about'][] = [
    'title' => 'Giấy phép sử dụng NukeViet',
    'link' => NV_BASE_SITEURL . 'index.php?language=vi&nv=about&op=giay-phep-su-dung-nukeviet' . $global_config['rewrite_exturl'],
    'groups_view' => '6,7',
    'op' => 'giay-phep-su-dung-nukeviet'
];
$menu_rows_lev1['about'][] = [
    'title' => 'Những tính năng của NukeViet CMS 4.0',
    'link' => NV_BASE_SITEURL . 'index.php?language=vi&nv=about&op=nhung-tinh-nang-cua-nukeviet-cms-4-0' . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'nhung-tinh-nang-cua-nukeviet-cms-4-0'
];
$menu_rows_lev1['about'][] = [
    'title' => 'Yêu cầu sử dụng NukeViet 4',
    'link' => NV_BASE_SITEURL . 'index.php?language=vi&nv=about&op=Yeu-cau-su-dung-NukeViet-4' . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'Yeu-cau-su-dung-NukeViet-4'
];
$menu_rows_lev1['about'][] = [
    'title' => 'Giới thiệu về Công ty cổ phần phát triển nguồn mở Việt Nam',
    'link' => NV_BASE_SITEURL . 'index.php?language=vi&nv=about&op=gioi-thieu-ve-cong-ty-co-phan-phat-trien-nguon-mo-viet-nam' . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'gioi-thieu-ve-cong-ty-co-phan-phat-trien-nguon-mo-viet-nam'
];
$menu_rows_lev1['about'][] = [
    'title' => 'Ủng hộ, hỗ trợ và tham gia phát triển NukeViet',
    'link' => NV_BASE_SITEURL . 'index.php?language=vi&nv=about&op=ung-ho-ho-tro-va-tham-gia-phat-trien-nukeviet' . $global_config['rewrite_exturl'],
    'groups_view' => '6',
    'op' => 'ung-ho-ho-tro-va-tham-gia-phat-trien-nukeviet'
];

$menu_rows_lev1['news'] = [];
$menu_rows_lev1['news'][] = [
    'title' => 'Đối tác',
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news&op=Doi-tac',
    'groups_view' => '6',
    'op' => 'Doi-tac'
];
$menu_rows_lev1['news'][] = [
    'title' => 'Tuyển dụng',
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news&op=Tuyen-dung',
    'groups_view' => '6',
    'op' => 'Tuyen-dung'
];
$menu_rows_lev1['news'][] = [
    'title' => 'Rss',
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news&op=rss',
    'groups_view' => '6',
    'op' => 'rss'
];
$menu_rows_lev1['news'][] = [
    'title' => 'Quản lý bài viết',
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news&op=content',
    'groups_view' => '6',
    'op' => 'content'
];
$menu_rows_lev1['news'][] = [
    'title' => 'Tìm kiếm',
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news&op=search',
    'groups_view' => '6',
    'op' => 'search'
];
$menu_rows_lev1['news'][] = [
    'title' => 'Tin tức',
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news&op=Tin-tuc',
    'groups_view' => '6',
    'op' => 'Tin-tuc'
];
$menu_rows_lev1['news'][] = [
    'title' => 'Sản phẩm',
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news&op=San-pham',
    'groups_view' => '6',
    'op' => 'San-pham'
];
