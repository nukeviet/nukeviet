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
class Posts
{
    /**
     * Ngưng hiệu lực
     */
    const STATUS_DEACTIVE = 0;

    /**
     * Xuất bản
     */
    const STATUS_PUBLISH = 1;

    /**
     * Hẹn giờ đăng
     */
    const STATUS_WAITING = 2;

    /**
     * Hết hạn
     */
    const STATUS_EXPIRED = 3;

    /**
     * Lưu nháp
     */
    const STATUS_DRAFT = 4;

    /**
     * Chuyển duyệt bài
     */
    const STATUS_REVIEW_TRANSFER = 5;

    /**
     * Từ chối duyệt bài
     */
    const STATUS_REVIEW_REJECT = 6;

    /**
     * Đang duyệt bài
     */
    const STATUS_REVIEWING = 7;

    /**
     * Chuyển đăng bài
     */
    const STATUS_PUBLISH_TRANSFER = 8;

    /**
     * Từ chối đăng bài
     */
    const STATUS_PUBLISH_REJECT = 9;

    /**
     * Đang kiểm tra để đăng
     */
    const STATUS_PUBLISH_CHECKING = 10;

    /**
     * Đang khóa bởi chuyên mục
     */
    const STATUS_LOCKING = 21;
}
