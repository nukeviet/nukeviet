/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var nv_aryDayName = "Chủ nhật;Thứ Hai;Thứ Ba;Thứ Tư;Thứ Năm;Thứ Sáu;Thứ Bảy".split(";"),
    nv_aryDayNS = "CN Hai Ba Tư Năm Sáu Bảy".split(" "),
    nv_aryMonth = "Tháng Một;Tháng Hai;Tháng Ba;Tháng Tư;Tháng Năm;Tháng Sáu;Tháng Bảy;Tháng Tám;Tháng Chín;Tháng Mười;Tháng Mười một;Tháng Mười hai".split(";"),
    nv_aryMS = "Tháng 1;Tháng 2;Tháng 3;Tháng 4;Tháng 5;Tháng 6;Tháng 7;Tháng 8;Tháng 9;Tháng 10;Tháng 11;Tháng 12".split(";"),
    nv_admlogout_confirm = ["Bạn thực sự muốn thoát khỏi tài khoản Quản trị?", "Toàn bộ thông tin đăng nhập đã được xóa. Bạn đã thoát khỏi tài khoản Quản trị"],
    nv_is_del_confirm = ["Bạn thực sự muốn xóa? Nếu đồng ý, tất cả dữ liệu liên quan sẽ bị xóa. Bạn sẽ không thể phục hồi lại chúng sau này", "Lệnh Xóa đã được thực hiện", "Vì một lý do nào đó lệnh Xóa đã không được thực hiện"],
    nv_is_change_act_confirm = ["Bạn thực sự muốn thực hiện lệnh 'Thay đổi'?", "Lệnh 'Thay đổi' đã được thực hiện", "Vì một lý do nào đó lệnh 'Thay đổi' đã không được thực hiện"],
    nv_is_empty_confirm = ["Bạn thực sự muốn thực hiện lệnh 'Làm rỗng'?", "Lệnh 'Làm rỗng' đã được thực hiện", "Vì một lý do nào đó lệnh 'Làm rỗng' đã không được thực hiện"],
    nv_is_recreate_confirm = ["Bạn thực sự muốn thực hiện lệnh 'Cài lại'?", "Lệnh 'Cài lại' đã được thực hiện", "Vì một lý do nào đó lệnh 'Cài lại' đã không được thực hiện"],
    nv_is_add_user_confirm = ["Bạn thực sự muốn thêm người dùng này vào nhóm?", "Lệnh 'Thêm vào nhóm' đã được thực hiện", "Vì một lý do nào đó lệnh 'Thêm vào nhóm' đã không được thực hiện"],
    nv_is_exclude_user_confirm = ["Bạn thực sự muốn loại thành viên này ra khỏi nhóm?", "Lệnh 'Loại khỏi nhóm' đã được thực hiện", "Vì một lý do nào đó lệnh 'Loại khỏi nhóm' đã không được thực hiện"],
    nv_gotoString = "Chọn tháng hiện tại",
    nv_todayString = "Hôm nay",
    nv_weekShortString = "Tuần",
    nv_weekString = "Tuần",
    nv_scrollLeftMessage = "Click để di chuyển đến tháng trước. Giữ chuột để di chuyển tự động.",
    nv_scrollRightMessage = "Click để di chuyển đến tháng sau. Giữ chuột để di chuyển tự động.",
    nv_selectMonthMessage = "Click để chọn tháng.",
    nv_selectYearMessage = "Click để chọn năm.",
    nv_selectDateMessage = "Chọn ngày [date].",
    nv_loadingText = "Đang tải...",
    nv_loadingTitle = "Click để thôi",
    nv_focusTitle = "Click để xem tiếp",
    nv_fullExpandTitle = "Mở rộng kích thước thực tế (f)",
    nv_restoreTitle = "Click để đóng hình ảnh, Click và kéo để di chuyển. Sử dụng phím mũi tên Tiếp theo và Quay lại.",
    nv_error_login = "Lỗi: Bạn chưa khai báo tên đăng nhập hoặc khai báo không đúng. Tên đăng nhập phải bao gồm những ký tự có trong bảng chữ cái latin, số và dấu gạch dưới. Số ký tự tối đa là [max], tối thiểu là [min]",
    nv_error_password = "Lỗi: Bạn chưa khai báo mật khẩu hoặc khai báo không đúng. Mật khẩu phải bao gồm những ký tự có trong bảng chữ cái latin, số và dấu gạch dưới. Số ký tự tối đa là [max], tối thiểu là [min]",
    nv_error_email = "Lỗi: Bạn chưa khai báo địa chỉ hộp thư điện tử hoặc khai báo không đúng quy định",
    nv_error_seccode = "Lỗi: Bạn chưa khai báo Mã bảo mật hoặc khai báo không đúng. Mã bảo mật phải là một dãy số có chiều dài là [num] ký tự được thể hiện trong hình bên",
    nv_login_failed = "Lỗi: Vì một lý do nào đó, hệ thống không tiếp nhận tài khoản của bạn. Hãy thử khai báo lại lần nữa",
    nv_content_failed = "Lỗi: Vì một lý do nào đó, hệ thống không tiếp nhận thông tin của bạn. Hãy thử khai báo lại lần nữa",
    nv_required = "Trường này là bắt buộc.",
    nv_remote = "Xin vui lòng sửa chữa trường này.",
    nv_email = "Xin vui lòng nhập địa chỉ email hợp lệ.",
    nv_url = "Xin vui lòng nhập URL hợp lệ.",
    nv_date = "Xin vui lòng nhập ngày hợp lệ.",
    nv_dateISO = "Xin vui lòng nhập ngày hợp lệ (ISO).",
    nv_number = "Xin vui lòng nhập số hợp lệ.",
    nv_digits = "Xin vui lòng nhập chỉ chữ số",
    nv_creditcard = "Xin vui lòng nhập số thẻ tín dụng hợp lệ.",
    nv_equalTo = "Xin vui lòng nhập cùng một giá trị một lần nữa.",
    nv_accept = "Xin vui lòng nhập giá trị có phần mở rộng hợp lệ.",
    nv_maxlength = "Xin vui lòng nhập không quá {0} ký tự.",
    nv_minlength = "Xin vui lòng nhập ít nhất {0} ký tự.",
    nv_rangelength = "Xin vui lòng nhập một giá trị giữa {0} và {1} ký tự.",
    nv_range = "Xin vui lòng nhập một giá trị giữa {0} và {1}.",
    nv_max = "Xin vui lòng nhập một giá trị nhỏ hơn hoặc bằng {0}.",
    nv_min = "Xin vui lòng nhập một giá trị lớn hơn hoặc bằng {0}.",
    // Contact
    nv_fullname = "Họ tên nhập không hợp lệ.",
    nv_title = "Bạn chưa nhập tiêu đề.",
    nv_content = "Nội dung không được để trống.",
    nv_code = "Mã bảo mật không đúng.",
    nv_close = "Đóng",
    nv_confirm = "Xác nhận",
    nv_please_check = "Xin vui lòng chọn ít nhất một dòng để thao tác",
    // Message before unload
    nv_msgbeforeunload = "Dữ liệu chưa được lưu. Bạn sẽ bị mất dữ liệu nếu thoát khỏi trang này. Bạn có đồng ý thoát không?",
    // ErrorMessage
    verify_not_robot = "Xác minh bạn không phải là robot",
    NVJL = [];
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

var nukeviet = nukeviet || {};
nukeviet.i18n = nukeviet.i18n || {};
nukeviet.i18n.WebAuthnErrors = {
    creat: {
        NotAllowedError: 'Bạn đã từ chối yêu cầu',
        ConstraintError: 'Một ràng buộc nào đó trong cấu hình của yêu cầu không được đáp ứng',
        InvalidStateError: 'Khóa này đã được đăng ký trước đó, xin vui lòng tạo khóa khác',
        TypeError: 'Một tham số trong yêu cầu không hợp lệ, xin vui lòng tải lại trang và thử lại',
        SecurityError: 'Một vấn đề bảo mật xảy ra trong quá trình đăng ký, xin vui lòng tải lại trang và thử lại',
        AbortError: 'Bạn đã hủy bỏ yêu cầu',
        NotSupportedError: 'Trình duyệt hoặc thiết bị không hỗ trợ WebAuthn hoặc không hỗ trợ tham số cụ thể được yêu cầu',
        NotReadableError: 'Không thể đọc dữ liệu từ thiết bị xác thực',
        NotFoundError: 'Không tìm thấy thiết bị xác thực phù hợp hoặc không có thiết bị nào sẵn sàng cho yêu cầu',
        DataError: 'Có lỗi xảy ra trong dữ liệu cung cấp',
        OperationError: 'Lỗi nội bộ trong quá trình xác thực, kiểm tra lại trình duyệt hoặc thiết bị',
        TimeoutError: 'Quá trình xác thực quá lâu, xin vui lòng tải lại trang và thử lại',
        InvalidAccessError: 'Truy cập không hợp pháp và bị chặn'
    },
    get: {
        NotAllowedError: 'Bạn đã từ chối yêu cầu xác thực',
        SecurityError: 'Yêu cầu không an toàn nên không thể thực hiện, xin vui lòng tải lại trang và thử lại',
        TimeoutError: 'Quá trình xác thực hoặc đăng ký đã hết thời gian chờ',
        AbortError: 'Bạn đã hủy bỏ yêu cầu',
        NotSupportedError: 'Trình duyệt hoặc thiết bị không hỗ trợ WebAuthn',
        ConstraintError: 'Các ràng buộc (constraints) không được đáp ứng (ví dụ: userVerification), xin vui lòng tải lại trang và thử lại',
        InvalidStateError: 'Trạng thái không hợp lệ (ví dụ: PublicKeyCredential đã bị hủy), xin vui lòng tải lại trang và thử lại',
        EncodingError: 'Lỗi liên quan đến mã hóa dữ liệu (ví dụ: Base64 URL-encoded không hợp lệ), xin vui lòng tải lại trang và thử lại'
    },
    unknow: 'Lỗi không xác định, xin vui lòng tải lại trang và thử lại'
};
nukeviet.i18n.errorSessExp = 'Phiên đã hết hạn hoặc có lỗi khác, vui lòng tải lại trang và thử lại';
nukeviet.i18n.close = 'Đóng';
nukeviet.i18n.seccode1 = 'Xác minh &quot;Tôi không phải người máy&quot;';
nukeviet.i18n.seccode = 'Mã bảo mật';
nukeviet.i18n.captcharefresh = 'Thay mới';
nukeviet.i18n.confirmAction = 'Bạn có xác nhận thực hiện không?';
