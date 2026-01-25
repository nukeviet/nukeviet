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
class Tpl2Step
{
    /**
     * @var integer Thông báo bật xác thực hai bước cho tài khoản thành viên
     */
    public const ACTIVE_2STEP = 1;

    /**
     * @var integer Thông báo tắt xác thực hai bước cho tài khoản thành viên
     */
    public const DEACTIVATE_2STEP = 2;

    /**
     * @var integer Thông báo tạo lại mã dự phòng xác thực hai bước cho tài khoản thành viên
     */
    public const RENEW_BACKUPCODE = 3;
}
