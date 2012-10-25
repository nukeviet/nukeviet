/**
 * @Project NUKEVIET 3.x
 * @Author  VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-13-2010 15:24
 */

var nv_aryDayName = new Array('Chủ nhật','Thứ Hai','Thứ Ba','Thứ Tư','Thứ Năm','Thứ Sáu','Thứ Bảy');
var nv_aryDayNS = new Array('CN','Hai','Ba','Tư','Năm','Sáu','Bảy');
var nv_aryMonth = new Array('Tháng Một','Tháng Hai','Tháng Ba','Tháng Tư','Tháng Năm','Tháng Sáu','Tháng Bảy','Tháng Tám','Tháng Chín','Tháng Mười','Tháng Mười một','Tháng Mười hai');
var nv_aryMS = new Array('Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12');
var nv_admlogout_confirm = new Array('Bạn thực sự muốn thoát khỏi tài khoản Quản trị?','Toàn bộ thông tin đăng nhập đã được xóa. Bạn đã thoát khỏi tài khoản Quản trị');
var nv_is_del_confirm = new Array('Bạn thực sự muốn xóa? Nếu đồng ý, tất cả dữ liệu liên quan sẽ bị xóa. Bạn sẽ không thể phục hồi lại chúng sau này','Lệnh Xóa đã được thực hiện','Vì một lý do nào đó lệnh Xóa đã không được thực hiện');
var nv_is_change_act_confirm = new Array('Bạn thực sự muốn thực hiện lệnh \'Thay đổi\'?','Lệnh \'Thay đổi\' đã được thực hiện','Vì một lý do nào đó lệnh \'Thay đổi\' đã không được thực hiện');
var nv_is_empty_confirm = new Array('Bạn thực sự muốn thực hiện lệnh \'Làm rỗng\'?','Lệnh \'Làm rỗng\' đã được thực hiện','Vì một lý do nào đó lệnh \'Làm rỗng\' đã không được thực hiện');
var nv_is_recreate_confirm = new Array('Bạn thực sự muốn thực hiện lệnh \'Cài lại\'?','Lệnh \'Cài lại\' đã được thực hiện','Vì một lý do nào đó lệnh \'Cài lại\' đã không được thực hiện');
var nv_is_add_user_confirm = new Array('Bạn thực sự muốn thêm thành viên này vào nhóm?','Lệnh \'Thêm vào nhóm\' đã được thực hiện','Vì một lý do nào đó lệnh \'Thêm vào nhóm\' đã không được thực hiện');
var nv_is_exclude_user_confirm = new Array('Bạn thực sự muốn loại thành viên này ra khỏi nhóm?','Lệnh \'Loại khỏi nhóm\' đã được thực hiện','Vì một lý do nào đó lệnh \'Loại khỏi nhóm\' đã không được thực hiện');

var nv_formatString = "dd.mm.yyyy";
var nv_gotoString = "Chọn tháng hiện tại";
var nv_todayString = "Hôm nay";
var nv_weekShortString = "Tuần";
var nv_weekString = "Tuần";
var nv_scrollLeftMessage = "Click để di chuyển đến tháng trước. Giữ chuột để di chuyển tự động.";
var nv_scrollRightMessage = "Click để di chuyển đến tháng sau. Giữ chuột để di chuyển tự động.";
var nv_selectMonthMessage = "Click để chọn tháng.";
var nv_selectYearMessage = "Click để chọn năm.";
var nv_selectDateMessage = "Chọn ngày [date].";

var nv_loadingText = "Đang tải...";
var nv_loadingTitle = "Click để thôi";
var nv_focusTitle = "Click để xem tiếp";
var nv_fullExpandTitle = "Mở rộng kích thước thực tế (f)";
var nv_restoreTitle = "Click để đóng hình ảnh, Click và kéo để di chuyển. Sử dụng phím mũi tên Tiếp theo và Quay lại.";

var nv_error_login = "Lỗi: Bạn chưa khai báo tài khoản hoặc khai báo không đúng. Tài khoản phải bao gồm những ký tự có trong bảng chữ cái latin, số và dấu gạch dưới. Số ký tự tối đa là [max], tối thiểu là [min]";
var nv_error_password = "Lỗi: Bạn chưa khai báo mật khẩu hoặc khai báo không đúng. Mật khẩu phải bao gồm những ký tự có trong bảng chữ cái latin, số và dấu gạch dưới. Số ký tự tối đa là [max], tối thiểu là [min]";
var nv_error_email = "Lỗi: Bạn chưa khai báo địa chỉ hộp thư điện tử hoặc khai báo không đúng quy định";
var nv_error_seccode = "Lỗi: Bạn chưa khai báo mã chống spam hoặc khai báo không đúng. Mã chống spam phải là một dãy số có chiều dài là [num] ký tự được thể hiện trong hình bên";
var nv_login_failed = "Lỗi: Vì một lý do nào đó, hệ thống không tiếp nhận tài khoản của bạn. Hãy thử khai báo lại lần nữa";
var nv_content_failed = "Lỗi: Vì một lý do nào đó, hệ thống không tiếp nhận thông tin của bạn. Hãy thử khai báo lại lần nữa";

var nv_required = "Trường này là bắt buộc.";
var nv_remote = "Xin vui lòng sửa chữa trường này.";
var nv_email = "Xin vui lòng nhập địa chỉ email hợp lệ.";
var nv_url = "Xin vui lòng nhập URL hợp lệ.";
var nv_date = "Xin vui lòng nhập ngày hợp lệ.";
var nv_dateISO = "Xin vui lòng nhập ngày hợp lệ (ISO).";
var nv_dateDE = "Bitte geben Sie ein gültiges Datum ein.";
var nv_number = "Xin vui lòng nhập số hợp lệ.";
var nv_numberDE = "Bitte geben Sie eine Nummer ein.";
var nv_digits = "Xin vui lòng nhập chỉ chữ số";
var nv_creditcard = "Xin vui lòng nhập số thẻ tín dụng hợp lệ.";
var nv_equalTo = "Xin vui lòng nhập cùng một giá trị một lần nữa.";
var nv_accept = "Xin vui lòng nhập giá trị có phần mở rộng hợp lệ.";
var nv_maxlength = "Xin vui lòng nhập không quá {0} ký tự.";
var nv_minlength = "Xin vui lòng nhập ít nhất {0} ký tự.";
var nv_rangelength = "Xin vui lòng nhập một giá trị giữa {0} và {1} ký tự.";
var nv_range = "Xin vui lòng nhập một giá trị giữa {0} và {1}.";
var nv_max = "Xin vui lòng nhập một giá trị nhỏ hơn hoặc bằng {0}.";
var nv_min = "Xin vui lòng nhập một giá trị lớn hơn hoặc bằng {0}.";

//contact
var nv_fullname = "Họ tên nhập không hợp lệ.";
var nv_title = "Bạn chưa nhập tiêu đề.";
var nv_content = "Nội dung không được để trống.";
var nv_code = "Mã chống spam không đúng.";

//ErrorMessage
var NVJL = [];
NVJL.errorRequest = "Đã có lỗi xảy ra trong quá trình truy vấn.";
NVJL.errortimeout = "Thời gian chờ thực thi yêu cầu đã quá thời lượng cho phép.";
NVJL.errornotmodified = "Trình duyệt nhận được thông báo về nội dung tập tin không thay đổi, nhưng không tìm thấy nội dung lưu trữ từ bộ nhớ đệm.";
NVJL.errorparseerror = "Định dạng XML/Json không hợp lệ.";
NVJL.error304 = "Nội dung không đổi. Tài liệu có nội dung giống trong bộ nhớ đệm.";
NVJL.error400 = "Yêu cầu truy vấn không hợp lệ.";
NVJL.error401 = "Truy vấn bị từ chối.";
NVJL.error403 = "Yêu cầu bị đình chỉ.";
NVJL.error404 = "Không tìm thấy tập tin yêu cầu. Có thể do URL không hợp lệ hoặc tập tin không tồn tại trên máy chủ.";
NVJL.error406 = "Không được chấp nhận. Trình duyệt không chấp nhận kiểu MIME của tập tin được yêu cầu.";
NVJL.error500 = "Lỗi từ phía máy chủ nội bộ.";
NVJL.error502 = "Web server nhận được phản hồi không hợp lệ trong khi hoạt động như một gateway hoặc proxy. Bạn nhận được thông báo lỗi khi cố gắng chạy một kịch bản CGI.";
NVJL.error503 = "Dịch vụ không khả dụng.";