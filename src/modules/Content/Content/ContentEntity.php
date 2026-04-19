<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Content;

use NukeViet\Module\Content\Cat\CatEntity;
use NukeViet\Module\Content\Shared\AbstractEntity;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * ContentEntity — Đại diện cho 1 bản ghi bài viết
 */
class ContentEntity extends AbstractEntity
{
    /**
     * Danh sách các thuộc tính chỉ dùng cho hiển thị (không có trong DB).
     */
    protected const VIEW_FIELDS = ['id', 'category', 'link', 'url_view', 'url_edit', 'url_copy', 'checkss', 'url_copy_edit'];

    /**
     * Khai báo nested Entity — toArray() lớp cha tự động expand theo danh sách này.
     */
    protected const RELATIONS = ['category' => CatEntity::class];

    public int $id = 0;
    public int $catid = 0;
    public ?CatEntity $category = null;
    public string $title = '';
    public string $alias = '';
    public string $image = '';
    public string $imagealt = '';
    public int $imageposition = 0;
    public string $description = '';
    public string $bodytext = '';
    public string $keywords = '';
    public int $socialbutton = 0;
    public string $activecomm = '';
    public string $layout_func = '';
    public int $weight = 0;
    public int $admin_id = 0;
    public int $add_time = 0;
    public int $edit_time = 0;
    public int $status = 0;
    public int $hitstotal = 0;
    public int $hot_post = 0;
    public string $schema_type = 'article';
    public string $schema_about = 'Organization';

    // Thuộc tính bổ sung cho View
    public string $link = '';
    public string $url_view = '';
    public string $url_edit = '';
    public string $url_copy = '';
    public string $url_copy_edit = '';
    public string $checkss = '';

}
