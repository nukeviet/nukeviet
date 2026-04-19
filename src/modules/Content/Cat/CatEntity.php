<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Cat;

use NukeViet\Module\Content\Shared\AbstractEntity;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * CatEntity — Đại diện cho 1 bản ghi chủ đề (category)
 */
class CatEntity extends AbstractEntity
{
    /**
     * Danh sách các thuộc tính chỉ dùng cho hiển thị (không có trong DB).
     * Mọi thuộc tính public khác mặc định được coi là cột Database.
     */
    protected const VIEW_FIELDS = ['link', 'url_edit', 'checkss', 'url_copy'];

    /**
     * Tên cột khóa chính (không nằm trong VIEW_FIELDS).
     */
    protected const PRIMARY_KEY = 'catid';

    public int $catid = 0;
    public string $title = '';
    public string $alias = '';
    public string $description = '';
    public string $image = '';
    public int $weight = 0;
    public string $keywords = '';
    public int $add_time = 0;
    public int $edit_time = 0;
    public int $status = 1;

    // Thuộc tính bổ sung cho View
    public string $link = '';
    public string $url_edit = '';
    public string $url_copy = '';
    public string $checkss = '';

}
