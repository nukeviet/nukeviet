<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Template\Email;

/**
 * @author VINADES.,JSC <contact@vinades.vn>
 */
class Tpl
{
    /**
     * @var integer Thông báo xóa tài khoản quản trị
     */
    public const E_AUTHOR_DELETE = 1;

    /**
     * @var integer Thông báo đình chỉ tài khoản quản trị
     */
    public const E_AUTHOR_SUSPEND = 2;

    /**
     * @var integer Thông báo kích hoạt lại tài khoản quản trị
     */
    public const E_AUTHOR_REACTIVE = 3;

    /**
     * @var integer Thông báo kích hoạt lại tài khoản quản trị
     */
    public const E_AUTHOR_2STEP_ADD = 4;

    /**
     * @var integer Thông báo kích hoạt lại tài khoản quản trị
     */
    public const E_AUTHOR_2STEP_TRUNCATE = 5;

    /**
     * @var integer Thông báo kích hoạt lại tài khoản quản trị
     */
    public const E_AUTHOR_2STEP_DEL = 6;

    /**
     * @var integer Gửi email thông báo lỗi tự động cho webmaster
     */
    public const E_AUTO_ERROR_REPORT = 7;

    /**
     * @var integer Email gửi thử nghiệm để kiểm tra cấu hình gửi mail
     */
    public const E_EMAIL_CONFIG_TEST = 8;

    /**
     * @var integer ID mẫu email lớn nhất của hệ thống
     */
    public const MAX_SYS_TPL = 8;
}
