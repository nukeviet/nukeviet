<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '20/07/2023, 07:15';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['confirm_password'] = 'Nhập mật khẩu để tiếp tục';
$lang_module['confirm_password_info'] = 'Để thực hiện tính năng này, bạn cần xác nhận lại mật khẩu. Vui lòng điền mật khẩu vào ô bên dưới và nhấp Xác nhận';
$lang_module['confirm'] = 'Xác nhận';
$lang_module['secretkey'] = 'Mã bí mật';
$lang_module['wrong_confirm'] = 'Mã bí mật không chính xác. Vui lòng nhập lại!';
$lang_module['cfg_step1'] = 'Bước 1: Quét mã QR';
$lang_module['cfg_step1_manual'] = 'Hãy quét QR-code bằng ứng dụng hỗ trợ xác thực hai bước trên điện thoại của bạn (ví dụ: Google Authenticator). Nếu không thể quét QR-code, hãy';
$lang_module['cfg_step1_manual1'] = 'click vào đây';
$lang_module['cfg_step1_manual2'] = 'để nhập Khóa thiết lập thủ công';
$lang_module['cfg_step1_note'] = 'Chú ý: Bạn nên giữ bí mật khóa này';
$lang_module['cfg_step2_info'] = 'Nếu thao tác trên thành công, ứng dụng sẽ hiển thị một chuỗi gồm 6 chữ số. Hãy nhập chuỗi đó vào ô bên dưới để xác nhận.';
$lang_module['cfg_step2_info2'] = 'Mã 6 chữ số';
$lang_module['cfg_step2'] = 'Bước 2: Nhập mã từ ứng dụng';
$lang_module['title_2step'] = 'Xác thực hai bước';
$lang_module['status_2step'] = 'Xác thực hai bước đang';
$lang_module['active_2step'] = 'Bật';
$lang_module['deactive_2step'] = 'Tắt';
$lang_module['backupcode_2step'] = 'Bạn có <strong>%d</strong> mã dự phòng chưa sử dụng';
$lang_module['backupcode_2step_view'] = 'Xem mã dự phòng';
$lang_module['backupcode_2step_note'] = 'Chú ý: Vui lòng lưu trữ mã dự phòng cẩn thận! Nếu bị mất điện thoại, bạn có thể sử dụng chúng để xác minh quyền truy cập tài khoản. Nếu quên mã và mất điện thoại, bạn sẽ không thể đăng nhập vào tài khoản của mình.';
$lang_module['turnoff2step'] = 'Click để tắt xác thực hai bước';
$lang_module['turnon2step'] = 'Click để bật xác thực hai bước';
$lang_module['creat_other_code'] = 'Tạo lại mã dự phòng';
$lang_module['email_subject'] = 'Thông báo bảo mật';
$lang_module['email_2step_on'] = 'Tài khoản <strong>%4$s</strong> của bạn tại website <a href="%5$s"><strong>%6$s</strong></a> vừa kích hoạt chức năng xác thực hai bước qua ứng dụng. Thông tin:<br /><br />- Thời gian: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Trình duyệt: <strong>%3$s</strong><br /><br />Nếu đây đúng là bạn, hãy bỏ qua email này. Nếu đây không phải là bạn, rất có thể tài khoản của bạn đã bị đánh cắp. Hãy liên hệ với quản trị site để được hỗ trợ';
$lang_module['email_2step_off'] = 'Tài khoản <strong>%5$s</strong> của bạn tại website <a href="%6$s"><strong>%7$s</strong></a> vừa tắt chức năng xác thực hai bước qua ứng dụng. Thông tin:<br /><br />- Thời gian: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Trình duyệt: <strong>%3$s</strong><br /><br />Nếu đây đúng là bạn, hãy bỏ qua email này. Nếu đây không phải là bạn, mời kiểm tra lại thông tin cá nhân tại <a href="%4$s">%4$s</a>';
$lang_module['email_code_renew'] = 'Tài khoản <strong>%5$s</strong> của bạn tại website <a href="%6$s"><strong>%7$s</strong></a> vừa tạo lại mã dự phòng. Thông tin:<br /><br />- Thời gian: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Trình duyệt: <strong>%3$s</strong><br /><br />Nếu đây đúng là bạn, hãy bỏ qua email này. Nếu đây không phải là bạn, mời kiểm tra lại thông tin cá nhân tại <a href="%4$s">%4$s</a>';
$lang_module['change_2step_notvalid'] = 'Tài khoản của bạn chưa có mật khẩu nên không thể thay đổi tính năng Xác thực hai bước. Vui lòng tạo mật khẩu sau đó quay lại trang này.<br />Hãy <a href="%s">click vào đây</a> để tạo mật khẩu';
$lang_module['deactive_mess'] = 'Bạn thực sự muốn tắt xác thực hai bước?';
$lang_module['setup_2step'] = 'Thiết lập xác thực hai bước';
$lang_module['forcedrelogin'] = 'Buộc đăng nhập lại ở mọi nơi';
$lang_module['forcedrelogin_note'] = 'Bạn đã thoát khỏi tài khoản người dùng. Vui lòng đăng nhập lại';
$lang_module['setup_key'] = 'Khóa thiết lập';
