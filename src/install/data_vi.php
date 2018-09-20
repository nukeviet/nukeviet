<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
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

$install_lang['groups']['NukeViet-Fans'] = 'Nhóm những người hâm mộ hệ thống NukeViet';
$install_lang['groups']['NukeViet-Admins'] = 'Nhóm những người quản lý website xây dựng bằng hệ thống NukeViet';
$install_lang['groups']['NukeViet-Programmers'] = 'Nhóm Lập trình viên hệ thống NukeViet';

$install_lang['vinades_fullname'] = 'Công ty cổ phần phát triển nguồn mở Việt Nam';
$install_lang['vinades_address'] = 'Phòng 1706 - Tòa nhà CT2 Nàng Hương, 583 Nguyễn Trãi, Hà Nội';
$install_lang['nukeviet_description'] = 'Chia sẻ thành công, kết nối đam mê';
$install_lang['disable_site_content'] = 'Vì lý do kỹ thuật website tạm ngưng hoạt động. Thành thật xin lỗi các bạn vì sự bất tiện này!';

// Ngôn ngữ dữ liệu cho phần mẫu email
use NukeViet\Template\Email\Cat as EmailCat;
use NukeViet\Template\Email\Tpl as EmailTpl;

$install_lang['emailtemplates'] = [];
$install_lang['emailtemplates']['cats'] = [];
$install_lang['emailtemplates']['cats'][EmailCat::CAT_SYSTEM] = 'Email của hệ thống';
$install_lang['emailtemplates']['cats'][EmailCat::CAT_AUTHOR] = 'Email về quản trị';
$install_lang['emailtemplates']['cats'][EmailCat::CAT_USER] = 'Email về tài khoản';
$install_lang['emailtemplates']['cats'][EmailCat::CAT_MODULE] = 'Email của các module';

$install_lang['emailtemplates']['emails'] = [];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_EMAIL_ACTIVE] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Kích hoạt tài khoản qua email',
    's' => 'Thông tin kích hoạt tài khoản',
    'c' => 'Xin chào {$user_full_name},<br /><br />Tài khoản của bạn tại website {$site_name} đang chờ kích hoạt. Để kích hoạt, bạn hãy click vào link dưới đây:<br /><br />URL: <a href="{$active_link}">{$active_link}</a><br /><br />Các thông tin cần thiết:<br /><br />Tài khoản: {$user_username}<br />Email: {$user_email}<br /><br />Việc kích hoạt tài khoản chỉ có hiệu lực đến {$active_deadline}<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.<br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_DELETE] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Thư thông báo xóa tài khoản',
    's' => 'Thư thông báo xóa tài khoản',
    'c' => 'Xin chào {$user_full_name} ({$user_username}),<br /><br />Chúng tôi rất lấy làm tiếc thông báo về việc tài khoản của bạn đã bị xóa khỏi website {$site_name}.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_NEW_2STEP_CODE] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Gửi mã dự phòng mới',
    's' => 'Mã dự phòng mới',
    'c' => 'Xin chào {$user_full_name},<br /><br />Mã dự phòng cho tài khoản của bạn tại website {$site_name} đã được thay đổi. Dưới đây là mã dự phòng mới:<br /><br />{foreach from=$new_code item=code}{$code}<br />{/foreach}<br /><br />Bạn chú ý giữ mã dự phòng an toàn. Nếu mất điện thoại và mất cả mã dự phòng bạn sẽ không thể truy cập vào tài khoản của mình được nữa.<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.<br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_NEW_INFO] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_USER,
    't' => 'Thông báo tài khoản được tạo/kích hoạt',
    's' => 'Tài khoản của bạn đã được tạo',
    'c' => 'Xin chào {$user_full_name},<br /><br />Tài khoản của bạn tại website {$site_name} đã được kích hoạt. Dưới đây là thông tin đăng nhập:<br /><br />URL: <a href="{$login_link}">{$login_link}</a><br />Tên tài khoản: {$user_username}<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.<br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_ADMIN_ADDED] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Thông báo tài khoản được quản trị khởi tạo',
    's' => 'Tài khoản của bạn đã được tạo',
    'c' => 'Xin chào {$user_full_name},<br /><br />Tài khoản của bạn tại website {$site_name} đã được khởi tạo. Dưới đây là thông tin đăng nhập:<br /><br />URL: <a href="{$login_link}">{$login_link}</a><br />Tên tài khoản: {$user_username}<br />Mật khẩu: {$user_password}<br /><br />Chúng tôi khuyến cáo bạn nên đổi mật khẩu trước khi sử dụng tài khoản.<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.<br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_SAFE_KEY] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Thư xác minh bật chế độ an toàn',
    's' => 'Mã xác minh chế độ an toàn',
    'c' => 'Xin chào {$user_full_name},<br /><br />Bạn đã gửi yêu cầu sử dụng chế độ an toàn tại website {$site_name}. Dưới đây là mã xác minh dùng cho việc kích hoạt hoặc tắt chế độ an toàn:<br /><br /><strong>{$code}</strong><br /><br />Mã xác minh này chỉ có tác dụng bật-tắt chế độ an toàn một lần duy nhất. Sau khi bạn tắt chế độ an toàn, mã xác minh này sẽ vô giá trị.<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}.<br /><br /><br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_SELF_EDIT] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Thư thông báo đã cập nhật tài khoản',
    's' => 'Cập nhật thông tin tài khoản thành công',
    'c' => 'Xin chào {$user_full_name},<br /><br />Tài khoản của bạn tại website {$site_name} đã được cập nhật với {$edit_label} mới là <strong>{$new_value}</strong>.<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}.<br /><br /><br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_ADMIN_EDIT] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Thư thông báo quản trị sửa tài khoản thành viên',
    's' => 'Tài khoản của bạn đã được cập nhật',
    'c' => 'Xin chào {$user_full_name},<br /><br />Tài khoản của bạn tại website {$site_name} đã được cập nhật. Dưới đây là thông tin đăng nhập mới:<br /><br />URL: <a href="{$login_url}">{$login_url}</a><br />Tên tài khoản: {$user_username}{if $send_password}<br />Mật khẩu: {$user_password}{/if}<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.<br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_VERIFY_EMAIL] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Thư xác nhận thay đổi email tài khoản',
    's' => 'Thông tin kích hoạt thay đổi email',
    'c' => 'Xin chào {$user_full_name},<br /><br />Bạn đã gửi đề nghị thay đổi địa chỉ email của Tài khoản cá nhân trên website {$site_name}. Để hoàn tất thay đổi này, bạn cần xác nhận địa chị email mới bằng cách nhập Mã xác minh dưới đây vào ô tương ứng tại khu vực Sửa thông tin tài khoản:<br /><br />Mã xác minh: <strong>{$code}</strong><br /><br />Mã này hết hạn vào {$timeout}.<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.<br /><br /><br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_GROUP_JOIN] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Thông báo có yêu cầu tham gia nhóm',
    's' => 'Yêu cầu tham gia nhóm',
    'c' => 'Xin chào trưởng nhóm <strong>{$leader_name}</strong>,<br /><br /><strong>{$user_full_name}</strong> đã gửi yêu cầu tham gia nhóm <strong>{$group_name}</strong> do bạn đang quản lý. Bạn cần xét duyệt yêu cầu này!<br /><br />Vui lòng truy cập <a href="{$link}">liên kết này</a> để xét duyệt thành viên.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_LOST_ACTIVE] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Lấy lại link kích hoạt tài khoản',
    's' => 'Thông tin kích hoạt tài khoản',
    'c' => 'Xin chào {$user_full_name},<br /><br />Tài khoản của bạn tại website {$site_name} đang chờ kích hoạt. Để kích hoạt, bạn hãy click vào link dưới đây:<br /><br />URL: <a href="{$active_link}">{$active_link}</a><br />Các thông tin cần thiết:<br />Tài khoản: {$user_username}<br />Email: {$user_email}<br />Mật khẩu: {$user_password}<br /><br />Việc kích hoạt tài khoản chỉ có hiệu lực đến {$timeout}<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.<br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_LOST_PASS] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Khôi phục mật khẩu thành viên',
    's' => 'Hướng dẫn khôi phục mật khẩu',
    'c' => 'Xin chào {$user_full_name},<br /><br />Bạn vừa gửi đề nghị thay đổi mật khẩu đăng nhập tài khoản cá nhân tại website {$site_name}. Để thay đổi mật khẩu, bạn cần nhập mã xác minh dưới đây vào ô tương ứng tại khu vực thay đổi mật khẩu.<br /><br />Mã xác minh: <strong>{$code}</strong></a><br /><br />Mã này chỉ được sử dụng một lần và trước thời hạn: {$timeout}.<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.<br /><br />Quản trị site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_DELETE] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Xóa tài khoản quản trị',
    's' => 'Thông báo từ website {$site_name}',
    'c' => 'Ban quản trị website {$site_name} xin thông báo:<br />Tài khoản quản trị của bạn tại website {$site_name} đã bị xóa vào {$delete_time}{if not empty($delete_reason)} vì lý do: {$delete_reason}{/if}.<br />Mọi đề nghị, thắc mắc... xin gửi đến địa chỉ {$contact_link}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_SUSPEND] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Đình chỉ/Kích hoạt lại quản trị site',
    's' => 'Thông báo từ website {$site_name}',
    'c' => '{if $is_suspend}Ban quản trị website {$site_name} xin thông báo:<br />Tài khoản quản trị của bạn tại website {$site_name} đã bị đình chỉ hoạt động vào {$suspend_time} vì lý do: {$suspend_reason}.<br />Mọi đề nghị, thắc mắc... xin gửi đến địa chỉ {$contact_link}{else}Ban quản trị website {$site_name} xin thông báo:<br />Tài khoản quản trị của bạn tại website {$site_name} đã hoạt động trở lại vào {$unsuspend_time}.<br />Trước đó tài khoản này đã bị đình chỉ hoạt động vì lý do: {$suspend_reason}{/if}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTO_ERROR_REPORT] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_SYSTEM,
    't' => 'Email tự động thông báo lỗi',
    's' => 'Cảnh báo từ website {$site_name}',
    'c' => 'Hệ thống đã nhận được một số thông báo. Bạn hãy mở file đính kèm để xem chi tiết'
];

$menu_rows_lev0['about'] = array(
    'title' => $install_lang['modules']['about'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=about",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['news'] = array(
    'title' => $install_lang['modules']['news'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['users'] = array(
    'title' => $install_lang['modules']['users'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['statistics'] = array(
    'title' => $install_lang['modules']['statistics'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=statistics",
    'groups_view' => '2',
    'op' => ''
);
$menu_rows_lev0['voting'] = array(
    'title' => $install_lang['modules']['voting'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=voting",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['seek'] = array(
    'title' => $install_lang['modules']['seek'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=seek",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['contact'] = array(
    'title' => $install_lang['modules']['contact'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=contact",
    'groups_view' => '6',
    'op' => ''
);

$menu_rows_lev1['about'] = [];
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
    'groups_view' => '6,7',
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

$menu_rows_lev1['news'] = [];
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
