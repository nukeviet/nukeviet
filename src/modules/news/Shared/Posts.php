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

    /**
     * Được xem bài viết
     */
    const VIEW_OK = 0;

    /**
     * Không có quyền xem bài viết
     */
    const VIEW_NO_PERMISSION = 1;

    /**
     * Bài viết chưa xuất bản hoặc đã hết hạn
     */
    const VIEW_NOT_FOUND = 2;

    /**
     * Kiểm tra người dùng hiện tại có được xem bài viết hay không.
     *
     * @param array  $row           Bài viết, cần các khóa status, publtime, exptime, group_view
     * @param string $catGroupsView groups_view của chuyên mục chính
     * @param bool   $isModadmin    Người dùng là quản trị module
     * @return int Một trong các hằng VIEW_*
     */
    public static function checkView(array $row, string $catGroupsView, bool $isModadmin): int
    {
        if (!nv_user_in_groups($catGroupsView)) {
            return self::VIEW_NO_PERMISSION;
        }
        if (!empty($row['group_view']) and !nv_user_in_groups($row['group_view'])) {
            return self::VIEW_NO_PERMISSION;
        }
        if ($isModadmin) {
            return self::VIEW_OK;
        }
        if ($row['status'] == self::STATUS_PUBLISH and $row['publtime'] < NV_CURRENTTIME and ($row['exptime'] == 0 or $row['exptime'] > NV_CURRENTTIME)) {
            return self::VIEW_OK;
        }

        return self::VIEW_NOT_FOUND;
    }
}
