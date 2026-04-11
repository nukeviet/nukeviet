<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\content\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * CatEntity — Đại diện cho 1 bản ghi chủ đề (category)
 */
class CatEntity
{
    /**
     * Danh sách các thuộc tính chỉ dùng cho hiển thị (không có trong DB).
     * Mọi thuộc tính public khác mặc định được coi là cột Database.
     */
    private const VIEW_FIELDS = ['link', 'url_edit', 'checkss', 'url_copy'];

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

    /**
     * Lấy danh sách các cột thực tế trong Database.
     * Tự động lọc bỏ các trường View và Khóa chính (catid).
     */
    public static function getDbColumns(): array
    {
        $allFields = array_keys(get_class_vars(self::class));
        return array_values(array_diff($allFields, self::VIEW_FIELDS, ['catid']));
    }

    /**
     * Lấy tập hợp tên các cột kiểu int (dùng để bind PDO::PARAM_INT).
     * @return array<string, true>
     */
    public static function getIntColumns(): array
    {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            foreach (get_class_vars(self::class) as $field => $default) {
                if ($default !== null && is_int($default)) {
                    $cache[$field] = true;
                }
            }
        }
        return $cache;
    }

    /**
     * Chuyển Entity thành Array tương thích Hooks / Smarty NV5
     */
    public function toArray(): array
    {
        // Loại bỏ giá trị Null (nếu có) thành chuỗi/số rỗng tương ứng nếu cần
        // nhưng thông thường Smarty vẫn hiển thị Null bình thường.
        return get_object_vars($this);
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
                continue;
            }
            // Ép kiểu theo giá trị mặc định: int → (int), còn lại → (string)
            $entity->$key = is_int($default) ? (int) $value : (string) $value;
        }
        return $entity;
    }
}
