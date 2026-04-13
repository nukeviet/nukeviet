<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * ContentEntity — Đại diện cho 1 bản ghi bài viết
 */
class ContentEntity
{
    /**
     * Danh sách các thuộc tính chỉ dùng cho hiển thị (không có trong DB).
     */
    private const VIEW_FIELDS = ['id', 'category', 'link', 'url_view', 'url_edit', 'url_copy', 'checkss', 'url_copy_edit'];

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

    /**
     * Lấy danh sách các cột thực tế trong Database.
     */
    public static function getDbColumns(): array
    {
        $allFields = array_keys(get_class_vars(self::class));
        return array_values(array_diff($allFields, self::VIEW_FIELDS));
    }

    /**
     * Lấy tập hợp tên các cột kiểu int (dùng để bind PDO::PARAM_INT).
     * Kết quả được cache static — chỉ tạo Entity prototype 1 lần/request.
     * @return array<string, true>
     */
    public static function getIntColumns(): array
    {
        static $cache = null;
        if ($cache === null) {
            $proto = new self();
            $cache = [];
            foreach (get_class_vars(self::class) as $field => $default) {
                if ($default !== null && is_int($default)) {
                    $cache[$field] = true;
                }
            }
        }
        return $cache;
    }

    public function toArray(): array
    {
        $arr = get_object_vars($this);
        if ($this->category instanceof CatEntity) {
            $arr['category'] = $this->category->toArray();
        }
        return $arr;
    }

    /**
     * Tạo Entity từ array dữ liệu
     */
    public static function fromArray(array $data): self
    {
        $entity = new self();
        foreach ($data as $key => $value) {
            if (!property_exists($entity, $key) || $value === null) {
                continue;
            }
            $default = $entity->$key;
            if ($default === null) {
                // Bỏ qua Relationship (nullable, VD: ?CatEntity $category = null)
                continue;
            }
            // Ép kiểu theo giá trị mặc định: int → (int), còn lại → (string)
            $entity->$key = is_int($default) ? (int) $value : (string) $value;
        }
        return $entity;
    }
}
