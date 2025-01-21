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
$lang_module['status_on'] = 'Đang bật';
$lang_module['status_off'] = 'Đang tắt';
$lang_module['active_2step'] = 'Bật';
$lang_module['deactive_2step'] = 'Tắt';
$lang_module['backupcode_2step'] = 'Bạn có <strong>%d</strong> mã dự phòng chưa sử dụng';
$lang_module['backupcode_2step_view'] = 'Xem mã dự phòng';
$lang_module['backupcode_2step_note'] = 'Chú ý: Vui lòng lưu trữ mã dự phòng cẩn thận! Nếu bị mất điện thoại, bạn có thể sử dụng chúng để xác minh quyền truy cập tài khoản. Nếu quên mã và mất điện thoại, bạn sẽ không thể đăng nhập vào tài khoản của mình.';
$lang_module['creat_other_code'] = 'Tạo lại mã dự phòng';
$lang_module['change_2step_notvalid'] = 'Tài khoản của bạn chưa có mật khẩu nên không thể thay đổi tính năng Xác thực hai bước. Vui lòng tạo mật khẩu sau đó quay lại trang này.<br />Hãy <a href="%s">click vào đây</a> để tạo mật khẩu';
$lang_module['deactive_mess'] = 'Bạn thực sự muốn tắt xác thực hai bước?';
$lang_module['setup_2step'] = 'Thiết lập xác thực hai bước';
$lang_module['setup_key'] = 'Khóa thiết lập';
$lang_module['recovery_codes'] = 'Mã dự phòng';
$lang_module['recovery_codes_note'] = 'Giúp bạn truy cập vào tài khoản trong trường hợp bạn mất quyền truy cập vào thiết bị và không thể nhận được mã xác thực hai bước. Xin vui làm đảm bảo giữ chúng an toàn và có thể nhớ';
$lang_module['active_2tep_success'] = 'Kích hoạt xác thực hai bước từ mã ứng dụng thành công';
$lang_module['active_2tep_success1'] = 'Bạn vừa kích hoạt xác thực hai bước từ mã ứng dụng thành công. Giờ đây, sau khi đăng nhập tài khoản bằng mật khẩu, bạn cần nhập mã xác nhận hai bước như thao tác vừa rồi';
$lang_module['active_2tep_success2'] = 'Trong trường hợp bạn bị mất, bị hỏng thiết bị dẫn đến không thể truy cập vào ứng dụng nhận mã xác thực hai bước thì các mã dự phòng bên dưới sẽ giúp bạn có thể hoàn tất đăng nhập';
$lang_module['active_2tep_success3'] = 'Hãy giữ mã dự phòng của bạn an toàn như mật khẩu. Chúng tôi khuyên bạn nên lưu trữ chúng bằng một trình quản lý mật khẩu như <a href="https://1password.com/" target="_blank">1Password</a>, <a href="https://1password.com/" target="_blank">Authy</a> hoặc <a href="https://1password.com/" target="_blank">Keeper</a>';
$lang_module['active_2tep_success'] = 'Kích hoạt xác thực hai bước từ mã ứng dụng thành công';
$lang_module['tstep_app'] = 'Ứng dụng xác thực';
$lang_module['tstep_app_note'] = 'Sử dụng ứng dụng hoặc tiện ích của trình duyệt để tạo mã xác thực gồm 6 chữ số';
$lang_module['backup_methods'] = 'Phương án dự phòng';
$lang_module['rcode_note'] = 'Bạn đã tạo khóa đăng nhập, bạn có thể sử dụng nó làm phương án xác thực hai bước. Bạn cũng có thể thêm các khóa bảo mật khác ở đây';
$lang_module['security_keys'] = 'Khóa bảo mật';
$lang_module['security_keys_note'] = 'Sử dụng vân tay, PIN, khuôn mặt, khóa màn hình hoặc khóa bảo mật phần cứng để xác thực hai bước';
$lang_module['recovery_codes_creat'] = 'Tạo mã';
$lang_module['passkey_not_supported'] = 'Trình duyệt/thiết bị này không hỗ trợ WebAuthn nên chưa thể tạo khóa đăng nhập. Xin vui lòng sử dụng một trình duyệt/thiết bị khác hoặc thử lại sau';
