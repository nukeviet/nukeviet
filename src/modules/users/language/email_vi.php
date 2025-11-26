<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Module\users\Shared\Emails;
use NukeViet\Template\Email\Cat;

if ($module_name == 'users') {
    $catid = Cat::CAT_USER;
    $pids = '3';
    $is_system = 1;
    $pfile = '';
} else {
    $catid = Cat::CAT_MODULE;
    $pids = '';
    $is_system = 0;
    $pfile = 'emf_code_user.php';
}

$module_emails[Emails::REGISTER_ACTIVE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Kích hoạt tài khoản qua email',
    's' => 'Thông tin kích hoạt tài khoản',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website {$site_name} đang chờ kích hoạt. Để kích hoạt, bạn hãy click vào link dưới đây:<br /><br />URL: <a href="{$link}">{$link}</a><br /><br />Các thông tin cần thiết:<br /><br />Tài khoản: {$username}<br />Email: {$email}<br /><br />Việc kích hoạt tài khoản chỉ có hiệu lực đến {$active_deadline}<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::USER_DELETE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thư thông báo xóa tài khoản',
    's' => 'Thư thông báo xóa tài khoản',
    'c' => '{$greeting_user}<br /><br />Chúng tôi rất lấy làm tiếc thông báo về việc tài khoản của bạn đã bị xóa khỏi website {$site_name}.'
];
$module_emails[Emails::NEW_2STEP_CODE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Gửi mã dự phòng mới',
    's' => 'Mã dự phòng mới',
    'c' => '{$greeting_user}<br /><br />Mã dự phòng cho tài khoản của bạn tại website {$site_name} đã được thay đổi. Dưới đây là mã dự phòng mới:<br /><br />{foreach from=$new_code item=code}{$code}<br />{/foreach}<br />Bạn chú ý giữ mã dự phòng an toàn. Nếu mất điện thoại và mất cả mã dự phòng bạn sẽ không thể truy cập vào tài khoản của mình được nữa.<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::NEW_INFO] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo tài khoản đã được tạo khi thành viên đăng kí thành công tại form',
    's' => 'Tài khoản của bạn đã được tạo',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website {$site_name} đã được kích hoạt. Dưới đây là thông tin tài khoản:<br /><br />Tên đăng nhập: {$username}<br />Email: {$email}<br /><br />Vui lòng bấm vào đường dẫn dưới đây để đăng nhập:<br />URL: <a href="{$link}">{$link}</a><br /><br />Đây là thư tự động được gửi đến email của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::NEW_INFO_OAUTH] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo tài khoản đã được tạo khi thành viên đăng kí thành công qua Oauth',
    's' => 'Tài khoản của bạn đã được tạo',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website {$site_name} đã được kích hoạt. Để đăng nhập vui lòng truy cập vào trang: <a href="{$link}">{$link}</a> và click vào nút: Đăng nhập bằng {$oauth_name}.<br /><br />Đây là thư tự động được gửi đến email của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::ADDED_BY_LEADER] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo tài khoản được trưởng nhóm khởi tạo',
    's' => 'Tài khoản của bạn đã được tạo',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website {$site_name} đã được kích hoạt. Dưới đây là thông tin đăng nhập:<br /><br />URL: <a href="{$link}">{$link}</a><br />Tên đăng nhập: {$username}<br />Email: {$email}<br /><br />Đây là thư tự động được gửi đến email của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::ADDED_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo tài khoản được quản trị khởi tạo',
    's' => 'Tài khoản của bạn đã được tạo',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website {$site_name} đã được khởi tạo. Dưới đây là thông tin đăng nhập:<br /><br />URL: <a href="{$link}">{$link}</a><br />Tên đăng nhập: {$username}<br />Mật khẩu: {$password}<br />{if $pass_reset gt 0 or $email_reset gt 0}<br />Chú ý:<br />{if $pass_reset eq 2}- Chúng tôi khuyến cáo bạn nên thay đổi mật khẩu trước khi sử dụng tài khoản.<br />{elseif $pass_reset eq 1}- Bạn cần đổi mật khẩu trước khi sử dụng tài khoản.<br />{/if}{if $email_reset eq 2}- Chúng tôi khuyến cáo bạn nên thay đổi email trước khi sử dụng tài khoản.<br />{elseif $email_reset eq 1}- Bạn cần đổi email trước khi sử dụng tài khoản.<br />{/if}{/if}<br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::SAFE_KEY] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Gửi mã xác minh khi người dùng bật/ tắt chế độ an toàn',
    's' => 'Mã xác minh chế độ an toàn',
    'c' => '{$greeting_user}<br /><br />Bạn đã gửi yêu cầu sử dụng chế độ an toàn tại website {$site_name}. Dưới đây là mã xác minh dùng cho việc kích hoạt hoặc tắt chế độ an toàn:<br /><br /><strong>{$code}</strong><br /><br />Mã xác minh này chỉ có tác dụng bật-tắt chế độ an toàn một lần duy nhất. Sau khi bạn tắt chế độ an toàn, mã xác minh này sẽ vô giá trị.<br /><br />Đây là thư tự động được gửi đến email của bạn từ website {$site_name}.'
];
$module_emails[Emails::SELF_EDIT] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo các thay đổi về tài khoản vừa được người dùng thực hiện',
    's' => 'Hồ sơ của bạn đã được cập nhật',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website {$site_name} đã được cập nhật {if $send_newvalue}với {$label} mới là <strong>{$newvalue}</strong>{else}{$label} mới{/if}.<br /><br />Đây là thư tự động được gửi đến email của bạn từ website {$site_name}.'
];
$module_emails[Emails::EDIT_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo các thay đổi về tài khoản vừa được quản trị thực hiện',
    's' => 'Tài khoản của bạn đã được cập nhật',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website {$site_name} đã được cập nhật. Dưới đây là thông tin đăng nhập:<br /><br />URL: <a href="{$link}">{$link}</a><br />Tên đăng nhập: {$username}<br />Email: {$email}{if not empty($password)}<br />Mật khẩu: {$password}{/if}<br />{if $pass_reset gt 0 or $email_reset gt 0}<br />Chú ý:<br />{if $pass_reset eq 2}- Chúng tôi khuyến cáo bạn nên thay đổi mật khẩu trước khi sử dụng tài khoản.<br />{elseif $pass_reset eq 1}- Bạn cần đổi mật khẩu trước khi sử dụng tài khoản.<br />{/if}{if $email_reset eq 2}- Chúng tôi khuyến cáo bạn nên thay đổi email trước khi sử dụng tài khoản.<br />{elseif $email_reset eq 1}- Bạn cần đổi email trước khi sử dụng tài khoản.<br />{/if}{/if}<br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::VERIFY_EMAIL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thư xác nhận thay đổi email tài khoản',
    's' => 'Thông tin kích hoạt thay đổi email',
    'c' => '{$greeting_user}<br /><br />Bạn đã gửi đề nghị thay đổi email của tài khoản người dùng trên website {$site_name}. Để hoàn tất thay đổi này, bạn cần xác nhận email mới bằng cách nhập Mã xác minh dưới đây vào ô tương ứng tại khu vực Sửa thông tin tài khoản:<br /><br />Mã xác minh: <strong>{$code}</strong><br /><br />Mã này hết hạn vào {$deadline}.<br /><br />Đây là thư tự động được gửi đến email của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::GROUP_JOIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo có yêu cầu tham gia nhóm',
    's' => 'Yêu cầu tham gia nhóm',
    'c' => 'Xin chào trưởng nhóm <strong>{$group_name}</strong>,<br /><br /><strong>{$full_name}</strong> đã gửi yêu cầu tham gia nhóm <strong>{$group_name}</strong> do bạn đang quản lý. Vui lòng xét duyệt yêu cầu bằng cách nhấn vào <a href="{$link}">liên kết này</a>.'
];
$module_emails[Emails::LOST_ACTIVE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Gửi lại thông tin kích hoạt tài khoản',
    's' => 'Thông tin kích hoạt tài khoản',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website {$site_name} đang chờ kích hoạt. Để kích hoạt, bạn hãy click vào link dưới đây:<br /><br />URL: <a href="{$link}">{$link}</a><br />Các thông tin cần thiết:<br />Tên đăng nhập: {$username}<br />Email: {$email}<br />Mật khẩu: {$password}<br /><br />Việc kích hoạt tài khoản chỉ có hiệu lực đến {$active_deadline}<br /><br />Đây là thư tự động được gửi đến email của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::LOST_PASS] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Hướng dẫn lấy lại mật khẩu thành viên',
    's' => 'Hướng dẫn tạo lại mật khẩu',
    'c' => '{$greeting_user}<br /><br />Bạn vừa gửi đề nghị thay đổi mật khẩu tài khoản người dùng tại website {$site_name}. Để thay đổi mật khẩu, bạn cần nhập mã xác minh dưới đây vào ô tương ứng tại khu vực thay đổi mật khẩu.<br /><br />Mã xác minh: <strong>{$code}</strong><br /><br />Mã này chỉ được sử dụng một lần và trước thời hạn: {$deadline}.<br />Yêu cầu này xuất phát từ:<br />- IP: <strong>{$ip}</strong><br />- Trình duyệt: <strong>{$user_agent}</strong><br />- Thời gian gửi yêu cầu: <strong>{$request_time}</strong><br /><br />Đây là thư tự động được gửi đến email của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::R2S] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo xác thực hai bước đã gỡ thành công',
    's' => 'Xác thực 2 bước đã tắt',
    'c' => '{$greeting_user}<br /><br />Theo yêu cầu của bạn, chúng tôi đã tắt tính năng Xác thực 2 bước cho tài khoản của bạn tại website {$site_name}.<br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::R2S_REQUEST] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Hướng dẫn tắt xác thực hai bước khi quên mã',
    's' => 'Thông tin tắt xác thực 2 bước',
    'c' => '{$greeting_user}<br /><br />Chúng tôi vừa nhận được yêu cầu tắt xác thực 2 bước cho tài khoản của bạn tại website {$site_name}. Nếu bạn là người gửi yêu cầu này, hãy sử dụng Mã xác minh dưới đây để tiến hành tắt:<br /><br />Mã xác minh: <strong>{$code}</strong><br /><br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::OAUTH_LEADER_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo oauth được thêm vào tài khoản bởi trưởng nhóm',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Chúng tôi thông tin đến bạn là tài khoản bên thứ ba <strong>{$oauth_name}</strong> vừa được kết nối với tài khoản <strong>{$username}</strong> của bạn bởi trưởng nhóm.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Quản lý tài khoản bên thứ ba</a>'
];
$module_emails[Emails::OAUTH_SELF_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo oauth được thêm vào tài khoản bởi chính người dùng',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Tài khoản bên thứ ba <strong>{$oauth_name}</strong> vừa được kết nối với tài khoản <strong>{$username}</strong> của bạn. Nếu đây không phải là chủ ý của bạn, vui lòng nhanh chóng xóa nó khỏi tài khoản của mình bằng cách truy cập vào khu vực quản lý tài khoản bên thứ ba.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Quản lý tài khoản bên thứ ba</a>'
];
$module_emails[Emails::OAUTH_LEADER_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo oauth được xóa khỏi tài khoản bởi trưởng nhóm',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Chúng tôi thông tin đến bạn là tài khoản bên thứ ba <strong>{$oauth_name}</strong> vừa được ngắt kết nối khỏi tài khoản <strong>{$username}</strong> của bạn bởi trưởng nhóm.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Quản lý tài khoản bên thứ ba</a>'
];
$module_emails[Emails::OAUTH_SELF_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo oauth được xóa khỏi tài khoản bởi chính người dùng',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Tài khoản bên thứ ba <strong>{$oauth_name}</strong> vừa được ngắt kết nối khỏi tài khoản <strong>{$username}</strong> của bạn. Nếu đây không phải là chủ ý của bạn, vui lòng nhanh chóng liên hệ với quản trị site để được giúp đỡ.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Quản lý tài khoản bên thứ ba</a>'
];
$module_emails[Emails::OAUTH_VERIFY_EMAIL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Gửi mã xác minh email khi đăng nhập qua Oauth mà email trùng với tài khoản đã có',
    's' => 'Thư xác minh email mới',
    'c' => 'Xin chào!<br /><br />Bạn đã gửi yêu cầu xác minh email: {$email}. Hãy chép mã dưới đây vào ô Mã xác minh trên site.<br /><br />Mã xác minh: <strong>{$code}</strong><br /><br />Đây là thư tự động được gửi đến email của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::ACTIVE_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email thông báo cho người dùng khi quản trị kích hoạt tài khoản',
    's' => 'Tài khoản của bạn đã được tạo',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website {$site_name} đã được kích hoạt. {if empty($oauth_name)}Dưới đây là thông tin đăng nhập:<br /><br />URL: <a href="{$link}">{$link}</a><br />Tên đăng nhập: {$username}<br />{if not empty($password)}Mật khẩu: {$password}{/if}{else}Để đăng nhập vui lòng truy cập vào trang: <a href="{$link}">{$link}</a> và click vào nút: <strong>Đăng nhập bằng {$oauth_name}</strong>.{if not empty($password)}<br /><br />Bạn cũng có thể đăng nhập theo cách thông thường với thông tin:<br />Tên đăng nhập: {$username}<br />Mật khẩu: {$password}{/if}{/if}{if $pass_reset gt 0 or $email_reset gt 0}<br />Chú ý:<br />{if $pass_reset eq 2}- Chúng tôi khuyến cáo bạn nên thay đổi mật khẩu trước khi sử dụng tài khoản.<br />{elseif $pass_reset eq 1}- Bạn cần đổi mật khẩu trước khi sử dụng tài khoản.<br />{/if}{if $email_reset eq 2}- Chúng tôi khuyến cáo bạn nên thay đổi email trước khi sử dụng tài khoản.<br />{elseif $email_reset eq 1}- Bạn cần đổi email trước khi sử dụng tài khoản.<br />{/if}{/if}<br />Đây là thư tự động được gửi đến hòm thư điện tử của bạn từ website {$site_name}. Nếu bạn không hiểu gì về nội dung bức thư này, đơn giản hãy xóa nó đi.'
];
$module_emails[Emails::REQUEST_RESET_PASS] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email yêu cầu người dùng thay đổi mật khẩu',
    's' => '{if $pass_reset eq 2}Khuyến cáo thay đổi mật khẩu truy cập{else}Cần thay đổi mật khẩu truy cập{/if}',
    'c' => '{$greeting_user}<br /><br />Ban quản trị website {$site_name} xin thông báo: Vì lý do bảo mật chúng tôi {if $pass_reset eq 2}khuyến cáo bạn nên{else}đề nghị bạn nhanh chóng{/if} thay đổi mật khẩu truy cập tài khoản của mình. Để thay đổi mật khẩu, trước hết bạn cần truy cập vào trang <a href="{$link}">Quản lý tài khoản cá nhân</a>, chọn nút Thiết lập tài khoản, sau đó chọn nút Mật khẩu và làm theo hướng dẫn.'
];
$module_emails[Emails::OFF2S_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo đến người dùng xác thực hai bước đã được quản trị tắt',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn vừa được tắt xác thực hai bước bởi quản trị viên. Chúng tôi gửi cho bạn email này để thông tin đến bạn.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Quản lý xác thực hai bước</a>'
];
$module_emails[Emails::OAUTH_ADMIN_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo oauth được xóa khỏi tài khoản bởi quản trị',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Chúng tôi thông tin đến bạn là tài khoản bên thứ ba <strong>{$oauth_name}</strong> vừa được ngắt kết nối khỏi tài khoản của bạn bởi quản trị viên.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Quản lý tài khoản bên thứ ba</a>'
];
$module_emails[Emails::OAUTH_TRUNCATE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Thông báo đến người dùng khi quản trị xóa tất cả Oauth của họ',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Chúng tôi thông tin đến bạn là tất cả các tài khoản bên thứ ba vừa được ngắt kết nối khỏi tài khoản của bạn bởi quản trị viên.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Quản lý tài khoản bên thứ ba</a>'
];
$module_emails[Emails::REQUEST_RESET_EMAIL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email yêu cầu người dùng thay đổi email',
    's' => '{if $email_reset eq 2}Khuyến cáo thay đổi email{else}Cần thay đổi email{/if}',
    'c' => '{$greeting_user}<br /><br />Ban quản trị website {$site_name} xin thông báo: Vì lý do bảo mật chúng tôi {if $email_reset eq 2}khuyến cáo bạn nên{else}đề nghị bạn nhanh chóng{/if} thay đổi email tài khoản của mình. Để thay đổi email, trước hết bạn cần truy cập vào trang <a href="{$link}">Quản lý tài khoản cá nhân</a>, chọn nút Thiết lập tài khoản, sau đó chọn nút Email và làm theo hướng dẫn.'
];
$module_emails[Emails::SECURITY_KEY_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email thêm khóa bảo mật',
    's' => 'Khóa bảo mật đã được thêm vào tài khoản của bạn',
    'c' => '{$greeting_user}<br /><br />Một khóa bảo mật có tên &quot;{$security_key}&quot; vừa được thêm vào tài khoản của bạn tại website {$site_name}. Hành động này xuất phát từ:
<ul>
    <li>Trình duyệt: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Thời gian thao tác: <strong>{$action_time}</strong></li>
</ul>
<p>Chúng tôi gửi thông báo bắt buộc này đến email của bạn để đảm bảo chính bạn là người thực hiện. Trong trường hợp không phải là bạn, vui lòng khẩn trương truy cập <a href="{$tstep_link}">trang quản lí xác thực hai bước</a> để xem lại các khóa bảo mật. Đồng thời thực hiện <a href="{$pass_link}">đổi mật khẩu ngay</a> để đảm bảo an toàn.</p>
Nhắc nhở: Bạn đã lưu trữ mã dự phòng của mình chưa? Nếu chưa xin vui lòng dành chút thời gian tải xuống và lưu trữ cẩn thận, vì đây là phương án cuối cùng đảm bảo bạn có thể truy cập vào tài khoản trong trường hợp mất các thiết bị để truy cập các phương án xác thực hai bước. Bạn có thể <a href="{$code_link}">tải chúng xuống tại đây</a>.'
];
$module_emails[Emails::PASSKEY_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email thêm khóa đăng nhập',
    's' => 'Khóa đăng nhập đã được thêm vào tài khoản của bạn',
    'c' => '{$greeting_user}<br /><br />Một khóa đăng nhập có tên &quot;{$passkey}&quot; vừa được thêm vào tài khoản của bạn tại website {$site_name}. Hành động này xuất phát từ:
<ul>
    <li>Trình duyệt: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Thời gian thao tác: <strong>{$action_time}</strong></li>
</ul>
Chúng tôi gửi thông báo bắt buộc này đến email của bạn để đảm bảo chính bạn là người thực hiện. Trong trường hợp không phải là bạn, vui lòng khẩn trương truy cập <a href="{$passkey_link}">trang quản lí khóa đăng nhập</a> để xem lại các khóa đăng nhập. Đồng thời thực hiện <a href="{$pass_link}">đổi mật khẩu ngay</a> để đảm bảo an toàn.'
];
$module_emails[Emails::SECURITY_KEY_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email xóa khóa bảo mật',
    's' => 'Khóa bảo mật đã bị xóa khỏi tài khoản của bạn',
    'c' => '{$greeting_user}<br /><br />Khóa bảo mật &quot;{$security_key}&quot; vừa bị xóa khỏi tài khoản của bạn tại website {$site_name}. Hành động này xuất phát từ:
<ul>
    <li>Trình duyệt: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Thời gian thao tác: <strong>{$action_time}</strong></li>
</ul>
<p>Chúng tôi gửi thông báo bắt buộc này đến email của bạn để đảm bảo chính bạn là người thực hiện. Trong trường hợp không phải là bạn, vui lòng khẩn trương truy cập <a href="{$tstep_link}">trang quản lí xác thực hai bước</a> để xem lại các khóa bảo mật. Đồng thời thực hiện <a href="{$pass_link}">đổi mật khẩu ngay</a> để đảm bảo an toàn.</p>
Nhắc nhở: Bạn đã lưu trữ mã dự phòng của mình chưa? Nếu chưa xin vui lòng dành chút thời gian tải xuống và lưu trữ cẩn thận, vì đây là phương án cuối cùng đảm bảo bạn có thể truy cập vào tài khoản trong trường hợp mất các thiết bị để truy cập các phương án xác thực hai bước. Bạn có thể <a href="{$code_link}">tải chúng xuống tại đây</a>.'
];
$module_emails[Emails::PASSKEY_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email xóa khóa đăng nhập',
    's' => 'Khóa đăng nhập đã bị xóa khỏi tài khoản của bạn',
    'c' => '{$greeting_user}<br /><br />Khóa đăng nhập có tên &quot;{$passkey}&quot; vừa bị xóa khỏi tài khoản của bạn tại website {$site_name}. Hành động này xuất phát từ:
<ul>
    <li>Trình duyệt: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Thời gian thao tác: <strong>{$action_time}</strong></li>
</ul>
Chúng tôi gửi thông báo bắt buộc này đến email của bạn để đảm bảo chính bạn là người thực hiện. Trong trường hợp không phải là bạn, vui lòng khẩn trương truy cập <a href="{$passkey_link}">trang quản lí khóa đăng nhập</a> để xem lại các khóa đăng nhập. Đồng thời thực hiện <a href="{$pass_link}">đổi mật khẩu ngay</a> để đảm bảo an toàn.'
];
$module_emails[Emails::DELETE_ACCOUNT_SECCODE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email mã xác nhận xóa tài khoản',
    's' => 'Mã xác nhận yêu cầu xóa tài khoản của bạn',
    'c' => '<p>{$greeting_user}</p> <p>Chúng tôi đã nhận được yêu cầu xóa tài khoản của bạn. Để tiếp tục, vui lòng sử dụng mã xác nhận dưới đây.</p> <div style="text-align: center; margin: 32px 0;"> <p style="font-size: 14px; color: #4b5563; margin-bottom: 8px;">Mã xác nhận của bạn là:</p> <div style="display: inline-block; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; padding: 12px 24px;"> <span style="font-size: 24px; font-weight: bold; letter-spacing: 0.2em; color: #111827; font-family: monospace;">{$code}</span> </div> </div> <p>Mã này có hiệu lực trong 10 phút. Vui lòng không chia sẻ mã này với bất kỳ ai.</p> <p>Nếu bạn không yêu cầu hành động này, vui lòng bỏ qua email này và liên hệ với bộ phận hỗ trợ của chúng tôi ngay lập tức để bảo vệ tài khoản của bạn.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> Đây là thông báo bắt buộc liên quan đến hoạt động trên tài khoản của bạn. Vui lòng không trả lời và hãy bỏ qua email nếu bạn không hiểu nội dung. </p>'
];
$module_emails[Emails::DELETE_ACCOUNT_PENDING] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email thông báo đã lên lịch xóa tài khoản',
    's' => 'Tài khoản của bạn đã được lên lịch để xóa',
    'c' => '<p>{$greeting_user}</p> <p>Yêu cầu xóa tài khoản của bạn đã được xác nhận. Tài khoản của bạn sẽ bị xóa vĩnh viễn vào khoảng <strong style="color: #111827;">{$action_time}</strong>.</p> <p>Sau khi xóa, toàn bộ dữ liệu và dịch vụ liên quan sẽ không thể khôi phục.</p> <div style="text-align: center; margin: 32px 0;"> <p style="color: #4b5563; margin-bottom: 16px;">Nếu bạn đổi ý, bạn có thể hủy yêu cầu này bất kỳ lúc nào trước thời hạn trên bằng cách nhấp vào nút bên dưới và đăng nhập lại.</p> <a href="{$link}" style="display: inline-block; padding: 12px 32px; font-weight: bold; color: white; background-color: #2563eb; text-decoration: none; border-radius: 6px;">Hủy yêu cầu xóa tài khoản</a> </div> <p>Nếu bạn không thực hiện yêu cầu này, vui lòng nhấp vào liên kết bên trên để hủy yêu cầu hoặc liên hệ với bộ phận hỗ trợ của chúng tôi ngay lập tức.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> Đây là thông báo bắt buộc liên quan đến hoạt động trên tài khoản của bạn. Vui lòng không trả lời và hãy bỏ qua email nếu bạn không hiểu nội dung. </p>'
];
$module_emails[Emails::DELETE_ACCOUNT_CANCEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email hủy yêu cầu xóa tài khoản',
    's' => 'Yêu cầu xóa tài khoản của bạn đã được hủy',
    'c' => '<p>{$greeting_user}</p> <p>Chúng tôi xác nhận rằng yêu cầu xóa tài khoản của bạn đã được hủy thành công theo yêu cầu của bạn.</p> <p>Tài khoản của bạn vẫn <strong>an toàn</strong> và hoạt động bình thường. Bạn có thể tiếp tục sử dụng tất cả các dịch vụ của chúng tôi mà không bị gián đoạn.</p> <p>Nếu bạn không phải là người thực hiện hành động này, vui lòng <a href="{$pass_link}" style="color: #2563eb; font-weight: 600; text-decoration: none;">thay đổi mật khẩu</a> ngay lập tức và xem lại <a href="{$link}" style="color: #2563eb; font-weight: 600; text-decoration: none;">các phiên đăng nhập gần đây</a> để bảo vệ tài khoản của bạn.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> Đây là thông báo bắt buộc liên quan đến hoạt động trên tài khoản của bạn. Vui lòng không trả lời và hãy bỏ qua email nếu bạn không hiểu nội dung. </p>'
];
$module_emails[Emails::DELETE_ACCOUNT_COMPLETED] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email đã xóa tài khoản thành công',
    's' => 'Tài khoản của bạn đã được xóa vĩnh viễn',
    'c' => '<p>{$greeting_user}</p> <p>Như đã yêu cầu, tài khoản của bạn đã được <strong>xóa vĩnh viễn</strong> khỏi hệ thống của chúng tôi. Hành động này không thể được hoàn tác.</p> <p>Toàn bộ dữ liệu cá nhân, lịch sử hoạt động và các dịch vụ liên quan đến tài khoản này đã bị xóa không thể khôi phục.</p> <p>{$newvalue}.</p> <p>Chúng tôi rất tiếc khi phải chia tay bạn và hy vọng sẽ có cơ hội phục vụ bạn trong tương lai.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> Đây là thông báo bắt buộc liên quan đến hoạt động trên tài khoản của bạn. Vui lòng không trả lời và hãy bỏ qua email nếu bạn không hiểu nội dung. </p>'
];
