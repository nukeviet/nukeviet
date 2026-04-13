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
 * AbstractEntity — Lớp trừu tượng chứa các phương thức chung cho mọi Entity
 *
 * Mỗi class con cần khai báo:
 * - Hằng VIEW_FIELDS: danh sách thuộc tính chỉ dùng cho hiển thị (không có trong DB)
 * - Hằng PRIMARY_KEY: tên khóa chính nếu không nằm trong VIEW_FIELDS (VD: 'catid')
 *   Nếu khóa chính đã nằm trong VIEW_FIELDS thì không cần khai báo PRIMARY_KEY.
 */
abstract class AbstractEntity
{
    /**
     * Danh sách các thuộc tính chỉ dùng cho hiển thị (không có trong DB).
     * Mỗi class con PHẢI override hằng này.
     */
    protected const VIEW_FIELDS = [];

    /**
     * Tên cột khóa chính. Override trong class con nếu khóa chính
     * không nằm trong VIEW_FIELDS.
     * VD: CatEntity có PRIMARY_KEY = 'catid'
     */
    protected const PRIMARY_KEY = '';

    /**
     * Lấy danh sách các cột thực tế trong Database.
     * Tự động lọc bỏ các trường View và Khóa chính.
     */
    public static function getDbColumns(): array
    {
        $allFields = array_keys(get_class_vars(static::class));
        $exclude = static::VIEW_FIELDS;
        if (static::PRIMARY_KEY !== '') {
            $exclude[] = static::PRIMARY_KEY;
        }
        return array_values(array_diff($allFields, $exclude));
    }

    /**
     * Lấy tập hợp tên các cột kiểu int (dùng để bind PDO::PARAM_INT).
     * Kết quả được cache static — chỉ tạo 1 lần/request cho mỗi class con.
     * @return array<string, true>
     */
    public static function getIntColumns(): array
    {
        static $cache = [];
        $class = static::class;
        if (!isset($cache[$class])) {
            $cache[$class] = [];
            foreach (get_class_vars($class) as $field => $default) {
                if ($default !== null && is_int($default)) {
                    $cache[$class][$field] = true;
                }
            }
        }
        return $cache[$class];
    }

    /**
     * Chuyển Entity thành Array tương thích Hooks / Smarty NV5.
     * Mỗi class con tự override nếu cần xử lý đặc biệt (VD: nested Entity).
     */
    abstract public function toArray(): array;

    /**
     * Tạo Entity từ array dữ liệu.
     * Ép kiểu tự động theo giá trị mặc định: int → (int), còn lại → (string).
     * Bỏ qua các thuộc tính nullable (Relationship).
     */
    public static function fromArray(array $data): static
    {
        $entity = new static();
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
