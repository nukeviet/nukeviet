<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\news\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * @author VINADES.,JSC <contact@vinades.vn>
 */
class Emails
{
    /**
     * @var integer Gửi email giới thiệu bài viết cho bạn bè tại module news
     */
    public const SENDMAIL = 1;

    /**
     * @var integer Email cảm ơn người báo lỗi tại module news
     */
    public const REPORT_THANKS = 2;
}
