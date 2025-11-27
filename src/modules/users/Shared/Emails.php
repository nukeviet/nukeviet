<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\users\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * @author VINADES.,JSC <contact@vinades.vn>
 */
class Emails
{
    /**
     * @var integer Gửi thông tin kích hoạt khi đăng kí hoặc quản trị gửi lại
     */
    public const REGISTER_ACTIVE = 1;

    /**
     * @var integer Trưởng nhóm xóa tài khoản, quản trị xóa tài khoản
     */
    public const USER_DELETE = 2;

    /**
     * @var integer Gửi mã xác thực mới cho user khi quản trị tạo lại
     */
    public const NEW_2STEP_CODE = 3;

    /**
     * @var integer Đăng kí thủ công qua form thành công
     */
    public const NEW_INFO = 4;

    /**
     * @var integer Đăng kí thành công qua Oauth
     */
    public const NEW_INFO_OAUTH = 5;

    /**
     * @var integer Trưởng nhóm kích hoạt tài khoản thành viên
     */
    public const ADDED_BY_LEADER = 6;

    /**
     * @var integer Quản trị tạo tài khoản thành viên
     */
    public const ADDED_BY_ADMIN = 7;

    /**
     * @var integer Gửi mã xác nhận khi bật tắt chế độ an toàn
     */
    public const SAFE_KEY = 8;

    /**
     * @var integer Thành viên tự chỉnh sửa thông tin như username, email, mật khẩu
     */
    public const SELF_EDIT = 9;

    /**
     * @var integer Gửi lại thông tin cho thành viên khi quản trị sửa
     */
    public const EDIT_BY_ADMIN = 10;

    /**
     * @var integer Gửi mã xác minh khi người dùng đổi email
     */
    public const VERIFY_EMAIL = 11;

    /**
     * @var integer Gửi thông báo cho trưởng nhóm khi người dùng yêu cầu tham gia nhóm
     */
    public const GROUP_JOIN = 12;

    /**
     * @var integer Gửi mã xác minh khi người dùng lấy lại link kích hoạt
     */
    public const LOST_ACTIVE = 13;

    /**
     * @var integer Gửi mã xác minh khi người dùng quên mật khẩu
     */
    public const LOST_PASS = 14;

    /**
     * @var integer Thông báo gỡ xác thực hai bước khi quên ứng dụng và mã dự phòng
     */
    public const R2S = 15;

    /**
     * @var integer Gửi mã xác minh gỡ xác thực hai bước khi quên ứng dụng và mã dự phòng
     */
    public const R2S_REQUEST = 16;

    /**
     * @var integer Thông báo oauth được thêm vào tài khoản bởi trưởng nhóm
     */
    public const OAUTH_LEADER_ADD = 17;

    /**
     * @var integer Thông báo oauth được thêm vào tài khoản bởi chính người dùng
     */
    public const OAUTH_SELF_ADD = 18;

    /**
     * @var integer Thông báo oauth được xóa khỏi tài khoản bởi trưởng nhóm
     */
    public const OAUTH_LEADER_DEL = 19;

    /**
     * @var integer Thông báo oauth được xóa khỏi tài khoản bởi quản trị
     */
    public const OAUTH_ADMIN_DEL = 20;

    /**
     * @var integer Thông báo oauth được xóa khỏi tài khoản bởi chính người dùng
     */
    public const OAUTH_SELF_DEL = 21;

    /**
     * @var integer Gửi mã xác minh email khi đăng nhập qua Oauth mà email trùng với tài khoản đã có
     */
    public const OAUTH_VERIFY_EMAIL = 22;

    /**
     * @var integer Thông báo đến người dùng khi quản trị xóa tất cả Oauth của họ
     */
    public const OAUTH_TRUNCATE = 23;

    /**
     * @var integer Email thông báo cho người dùng khi quản trị kích hoạt tài khoản
     */
    public const ACTIVE_BY_ADMIN = 24;

    /**
     * @var integer Email yêu cầu người dùng thay đổi mật khẩu
     */
    public const REQUEST_RESET_PASS = 25;

    /**
     * @var integer Thông báo đến người dùng xác thực hai bước đã được quản trị tắt
     */
    public const OFF2S_BY_ADMIN = 26;

    /**
     * @var integer Email yêu cầu người dùng thay đổi email
     */
    public const REQUEST_RESET_EMAIL = 27;

    /**
     * @var integer Thông báo khóa bảo mật được thêm vào tài khoản
     */
    public const SECURITY_KEY_ADD = 28;

    /**
     * @var integer Thông báo khóa đăng nhập được thêm vào tài khoản
     */
    public const PASSKEY_ADD = 29;

    /**
     * @var integer Thông báo khóa bảo mật bị xóa
     */
    public const SECURITY_KEY_DEL = 30;

    /**
     * @var integer Thông báo khóa đăng nhập bị xóa
     */
    public const PASSKEY_DEL = 31;

    /**
     * @var integer Gửi mã xác minh khi người dùng yêu cầu xóa tài khoản
     */
    public const DELETE_ACCOUNT_SECCODE = 32;

    /**
     * @var integer Gửi thông báo khi yêu cầu xóa tài khoản đang chờ xử lý
     */
    public const DELETE_ACCOUNT_PENDING = 33;

    /**
     * @var integer Gửi thông báo khi yêu cầu xóa tài khoản bị hủy
     */
    public const DELETE_ACCOUNT_CANCEL = 34;

    /**
     * @var integer Gửi thông báo khi yêu cầu xóa tài khoản đã hoàn thành
     */
    public const DELETE_ACCOUNT_COMPLETED = 35;
}
