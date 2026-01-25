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
$lang_module['cfg_step1'] = 'Quét mã QR';
$lang_module['cfg_step1_manual'] = 'Hãy quét QR-code bằng ứng dụng hỗ trợ xác thực hai bước trên điện thoại của bạn (ví dụ: Google Authenticator). Nếu không thể quét QR-code, hãy';
$lang_module['cfg_step1_manual1'] = 'click vào đây';
$lang_module['cfg_step1_manual2'] = 'để nhập Khóa thiết lập thủ công';
$lang_module['cfg_step1_note'] = 'Chú ý: Bạn nên giữ bí mật khóa này';
$lang_module['cfg_step2_info'] = 'Nếu thao tác trên thành công, ứng dụng sẽ hiển thị một chuỗi gồm 6 chữ số. Hãy nhập chuỗi đó vào ô bên dưới để xác nhận.';
$lang_module['cfg_step2_info2'] = 'Mã 6 chữ số';
$lang_module['cfg_step2'] = 'Nhập mã từ ứng dụng';
$lang_module['title_2step'] = 'Xác thực hai bước';
$lang_module['title_2step_off'] = 'Xác thực hai bước đang tắt';
$lang_module['title_2step_off_note'] = 'Xác thực hai bước giúp bảo vệ tài khoản của bạn tốt hơn bằng cách yêu cầu một bước xác minh bổ sung ngoài mật khẩu. Hãy bật xác thực hai bước ngay hôm nay để tăng cường an toàn cho thông tin cá nhân của bạn!';
$lang_module['title_2step_off_note2'] = 'Xác thực hai bước giúp bảo vệ tài khoản của bạn tốt hơn bằng cách yêu cầu một bước xác minh bổ sung ngoài mật khẩu';
$lang_module['title_2step_off_note3'] = 'Chúng tôi khuyến cáo bạn nên bật xác thực hai bước trên tài khoản của mình. Nếu bạn cần thay đổi cấu hình hoặc tạo mã dự phòng mới, bạn có thể thực hiện trong phần cài đặt bên dưới thay vì tắt tính năng này';
$lang_module['title_2step_turnon'] = 'Kích hoạt xác thực hai bước (2FA)';
$lang_module['status_on'] = 'Đang bật';
$lang_module['status_off'] = 'Đang tắt';
$lang_module['active_2step'] = 'Bật';
$lang_module['deactive_2step'] = 'Tắt';
$lang_module['backupcode_2step'] = 'Bạn có <strong>%d</strong> mã dự phòng chưa sử dụng';
$lang_module['backupcode_2step_view'] = 'Xem mã dự phòng';
$lang_module['backupcode_2step_note'] = 'Chú ý: Vui lòng lưu trữ mã dự phòng cẩn thận! Nếu bị mất điện thoại, bạn có thể sử dụng chúng để xác minh quyền truy cập tài khoản. Nếu quên mã và mất điện thoại, bạn sẽ không thể đăng nhập vào tài khoản của mình.';
$lang_module['creat_other_code'] = 'Tạo lại mã dự phòng';
$lang_module['creat_other_note'] = 'Khi tạo lại mã dự phòng, vui lòng tải nó xuống hoặc in ra và lưu trữ nó ở một nơi an toàn. Mã dự phòng cũ nếu chưa dùng sẽ không còn khả dụng nữa';
$lang_module['change_2step_notvalid'] = 'Tài khoản của bạn chưa có mật khẩu nên không thể thay đổi tính năng Xác thực hai bước. Vui lòng tạo mật khẩu sau đó quay lại trang này.<br />Hãy <a href="%s">click vào đây</a> để tạo mật khẩu';
$lang_module['deactive_mess'] = 'Bạn thực sự muốn tắt xác thực hai bước?';
$lang_module['turnoff_2step'] = 'Tắt xác thực hai bước';
$lang_module['setup_2step'] = 'Thiết lập xác thực hai bước';
$lang_module['setup_key'] = 'Khóa thiết lập';
$lang_module['recovery_codes'] = 'Mã dự phòng';
$lang_module['recovery_codes_note'] = 'Giúp bạn truy cập vào tài khoản trong trường hợp bạn mất quyền truy cập vào thiết bị và không thể nhận được mã xác thực hai bước. Xin vui làm đảm bảo giữ chúng an toàn và có thể nhớ';
$lang_module['active_2tep_success'] = 'Kích hoạt xác thực hai bước từ mã ứng dụng thành công';
$lang_module['active_2tep_success1'] = 'Bạn vừa kích hoạt xác thực hai bước từ mã ứng dụng thành công. Giờ đây, sau khi đăng nhập tài khoản bằng mật khẩu, bạn cần nhập mã xác nhận hai bước như thao tác vừa rồi';
$lang_module['active_2tep_success2'] = 'Trong trường hợp bạn bị mất, bị hỏng thiết bị dẫn đến không thể truy cập vào ứng dụng nhận mã xác thực hai bước thì các mã dự phòng bên dưới sẽ giúp bạn có thể hoàn tất đăng nhập';
$lang_module['active_2tep_success3'] = 'Hãy giữ mã dự phòng của bạn an toàn như mật khẩu. Chúng tôi khuyên bạn nên lưu trữ chúng bằng một trình quản lý mật khẩu như <a href="https://1password.com/" target="_blank">1Password</a>, <a href="https://1password.com/" target="_blank">Authy</a> hoặc <a href="https://1password.com/" target="_blank">Keeper</a>';
$lang_module['active_2tep_success'] = 'Kích hoạt xác thực hai bước từ mã ứng dụng thành công';
$lang_module['active_2tep_success4'] = 'Vui lòng tải xuống, in ra hoặc sao chép và đảm bảo đã lưu trữ cẩn thận chúng trước khi tiếp tục';
$lang_module['active_2tep_review1'] = 'Tài khoản của bạn đã được bảo mật, xác thực hai bước (2FA) đã được kích hoạt';
$lang_module['active_2tep_review2'] = 'Phương án xác thực bổ sung';
$lang_module['active_2tep_review3'] = 'Các phương thức xác thực bổ sung sẽ giúp bạn truy cập vào tài khoản của mình trong trường hợp bạn làm mất thiết bị và quên cả mã dự phòng. Chú ý: Nếu tất cả mã từ ứng dụng, mã dự phòng và phương án xác thực bổ sung đều không thể sử dụng, bạn sẽ mất quyền truy cập vào tài khoản của mình';
$lang_module['tstep_key'] = 'Khóa truy cập';
$lang_module['tstep_app'] = 'Ứng dụng xác thực';
$lang_module['tstep_app_note'] = 'Sử dụng ứng dụng hoặc tiện ích của trình duyệt để tạo mã xác thực gồm 6 chữ số';
$lang_module['backup_methods'] = 'Phương án dự phòng';
$lang_module['rcode_note'] = 'Bạn đã tạo <strong>%s</strong> khóa đăng nhập, bạn có thể sử dụng nó làm phương án xác thực hai bước. Bạn cũng có thể thêm các khóa bảo mật khác ở đây';
$lang_module['passkey'] = 'Khóa đăng nhập';
$lang_module['passkey_help'] = 'Khóa đăng nhập là phương thức an toàn và hiện đại giúp bạn có thể đăng nhập vào tài khoản của mình mà chỉ cần sử dụng vân tay, khuôn mặt, khóa màn hình, PIN hoặc khóa bảo mật phần cứng. Bạn cũng có thể sử dụng khóa đăng nhập làm phương thức xác thực hai bước sau khi đăng nhập bằng mật khẩu';
$lang_module['security_keys'] = 'Khóa bảo mật';
$lang_module['security_keys_add'] = 'Thêm khóa bảo mật';
$lang_module['security_keys_note'] = 'Sử dụng vân tay, PIN, khuôn mặt, khóa màn hình hoặc khóa bảo mật phần cứng để xác thực hai bước';
$lang_module['recovery_codes_creat'] = 'Tạo mã';
$lang_module['passkey_not_supported'] = 'Trình duyệt/thiết bị này không hỗ trợ WebAuthn nên chưa thể tạo khóa đăng nhập. Xin vui lòng sử dụng một trình duyệt/thiết bị khác hoặc thử lại sau';
$lang_module['passkey_created_at'] = 'Tạo lúc';
$lang_module['passkey_last_used_at'] = 'Dùng lần cuối';
$lang_module['passkey_seenthis'] = 'Tạo từ trình duyệt này';
$lang_module['passkey_nickname'] = 'Tên gợi nhớ';
$lang_module['passkey_nickname_edit'] = 'Thay đổi tên gợi nhớ';
$lang_module['configured'] = 'Đã thiết lập';
$lang_module['go_config'] = 'Đi đến';
$lang_module['number_keys'] = '%s khóa';
$lang_module['remain_code'] = 'Còn %s mã';
$lang_module['lack_code'] = 'Bạn đã dùng gần hết mã dự phòng, vui lòng tạo mới mã dự phòng để đảm bảo an toàn';
$lang_module['usedup_code'] = 'Bạn đã dùng hết mã dự phòng, vui lòng tạo mới mã dự phòng ngay!';
$lang_module['code_is_available'] = 'Mã này khả dụng';
$lang_module['code_is_used'] = 'Mã đã được sử dụng';
$lang_module['qr_expried'] = 'Hết thời gian chờ, tải lại trang để tạo mã mới';
$lang_module['preferred_2fa_method'] = 'Phương pháp xác thực hai bước ưa thích';
$lang_module['preferred_2fa_method_help'] = 'Chọn phương pháp xác thực hai bước mà bạn muốn sử dụng đầu tiên sau khi đăng nhập bằng mật khẩu';
