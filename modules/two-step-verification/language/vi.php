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

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['confirm_password'] = 'Nhập mật khẩu xác nhận để tiếp tục';
$lang_module['confirm_password_info'] = 'Để thực hiện tính năng này, bạn cần xác nhận lại mật khẩu, điền mật khẩu của bạn vào ô bên dưới và nhấp Xác nhận';
$lang_module['confirm'] = 'Xác nhận';
$lang_module['secretkey'] = 'Mã bí mật';
$lang_module['wrong_confirm'] = 'Mã xác nhận không chính xác, vui lòng nhập lại';

$lang_module['cfg_step1'] = 'Bước 1: Quét mã QR';
$lang_module['cfg_step1_manual'] = 'Quét mã QR-code bên trên bằng phần mềm Two-Factor Authentication (Ví dụ Google Authenticator) trên điện thoại của bạn. Nếu bạn không thể sử dụng Camera hãy';
$lang_module['cfg_step1_manual1'] = 'nhập đoạn mã này';
$lang_module['cfg_step1_manual2'] = 'thủ công';
$lang_module['cfg_step1_note'] = 'Chú ý giữ bí mật mã này';
$lang_module['cfg_step2_info'] = 'Sau khi quét mã hoặc nhập thủ công thành công, ứng dụng sẽ hiển thị một chuỗi 6 chữ số, nhập chuỗi đó vào ô bên dưới để xác nhận';
$lang_module['cfg_step2_info2'] = 'Mã xác nhận gồm 6 chữ số hiển thị trên màn hình của ứng dụng trên điện thoại của bạn';
$lang_module['cfg_step2'] = 'Bước 2: Nhập mã từ ứng dụng';

$lang_module['title_2step'] = 'Xác thực hai bước';
$lang_module['status_2step'] = 'Xác thực hai bước đang';
$lang_module['active_2step'] = 'BẬT';
$lang_module['deactive_2step'] = 'TẮT';
$lang_module['backupcode_2step'] = 'Bạn có <strong>%d</strong> mã dự phòng chưa sử dụng';
$lang_module['backupcode_2step_view'] = 'Xem mã dự phòng';
$lang_module['backupcode_2step_note'] = 'Chú ý: Lưu giữ mã dự phòng cẩn thận để đề phòng khi bị mất điện thoại bạn có thể sử dụng mã này để truy cập tài khoản. Nếu quên mã và mất điện thoại bạn sẽ không thể đăng nhập vào tài khoản của mình';
$lang_module['turnoff2step'] = 'Tắt xác thực hai bước';
$lang_module['turnon2step'] = 'Thiết lập xác thực hai bước';
$lang_module['creat_other_code'] = 'Tạo mã dự phòng mới';

$lang_module['email_subject'] = 'Thông báo bảo mật';
$lang_module['email_2step_on'] = 'Tài khoản <strong>%4$s</strong> của bạn tại website <a href="%5$s"><strong>%6$s</strong></a> vừa kích hoạt chức năng xác thực hai bước qua ứng dụng. Thông tin:<br /><br />- Thời gian: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Trình duyệt: <strong>%3$s</strong><br /><br />Nếu đây đúng là bạn, hãy bỏ qua email này. Nếu đây không phải là bạn, rất có thể tài khoản của bạn đã bị đánh cắp. Hãy liên hệ với quản trị site để được hỗ trợ';
$lang_module['email_2step_off'] = 'Tài khoản <strong>%5$s</strong> của bạn tại website <a href="%6$s"><strong>%7$s</strong></a> vừa tắt chức năng xác thực hai bước qua ứng dụng. Thông tin:<br /><br />- Thời gian: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Trình duyệt: <strong>%3$s</strong><br /><br />Nếu đây đúng là bạn, hãy bỏ qua email này. Nếu đây không phải là bạn, mời kiểm tra lại thông tin cá nhân tại <a href="%4$s">%4$s</a>';
$lang_module['email_code_renew'] = 'Tài khoản <strong>%5$s</strong> của bạn tại website <a href="%6$s"><strong>%7$s</strong></a> vừa tạo lại mã dự phòng. Thông tin:<br /><br />- Thời gian: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Trình duyệt: <strong>%3$s</strong><br /><br />Nếu đây đúng là bạn, hãy bỏ qua email này. Nếu đây không phải là bạn, mời kiểm tra lại thông tin cá nhân tại <a href="%4$s">%4$s</a>';

$lang_module['change_2step_notvalid'] = 'Tài khoản của bạn chưa có mật khẩu nên không thể thay đổi tính năng Xác thực hai bước. Vui lòng tạo mật khẩu sau đó quay lại trang này. Hãy <a class="btn btn-primary btn-xs" href="%s">click vào đây</a> để tạo mật khẩu';
