<?php

/**
 * @Project NUKEVIET 5.0
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 */

declare(strict_types=1);

namespace NukeViet\Module\Devtool\Schema;

use PDO;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * SchemaRepository — Tầng I/O duy nhất của module Devtool.
 * Tập trung mọi truy vấn DB (introspection) và thao tác file JSON vào đây.
 * Controller và Service không được viết SQL hay file I/O trực tiếp.
 */
class SchemaRepository
{
    /** Cache danh sách bảng để tránh SHOW TABLES nhiều lần/request */
    private ?array $tablesCache = null;

    public function __construct(
        private PDO $db,
        private string $dataDir
    ) {}

    /**
     * Lấy toàn bộ danh sách tên bảng trong database hiện tại.
     * Kết quả được cache trong request.
     */
    public function getTablesList(): array
    {
        if ($this->tablesCache === null) {
            $sth = $this->db->prepare('SHOW TABLES');
            $sth->execute();
            $this->tablesCache = $sth->fetchAll(PDO::FETCH_COLUMN);
            $sth->closeCursor();
        }
        return $this->tablesCache;
    }

    /**
     * Kiểm tra bảng có tồn tại trong DB không.
     */
    public function tableExists(string $table): bool
    {
        return in_array($table, $this->getTablesList(), true);
    }

    /**
     * Lấy thông tin đầy đủ các cột của bảng (SHOW FULL COLUMNS).
     * Tên bảng đã được validate trước khi truyền vào.
     *
     * @return array<string, array{field: string, sql_type_full: string, base_type: string, comment: string}>
     */
    public function getTableColumns(string $table): array
    {
        $sth = $this->db->prepare('SHOW FULL COLUMNS FROM `' . $table . '`');
        $sth->execute();
        $columns = [];

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $row = array_change_key_case($row, CASE_LOWER);
            $field = $row['field'];
            $typeFull = strtolower($row['type']);
            preg_match('/^([a-z]+)/', $typeFull, $matches);
            $baseType = $matches[1] ?? 'varchar';

            $columns[$field] = [
                'field' => $field,
                'sql_type_full' => $typeFull,
                'base_type' => $baseType,
                'default' => $row['default'],
                'comment' => $row['comment'] ?? '',
            ];
        }
        $sth->closeCursor();

        return $columns;
    }

    /**
     * Lấy câu lệnh CREATE TABLE từ DB.
     * Tên bảng đã được validate trước khi truyền vào.
     */
    public function getCreateTableSql(string $table): string
    {
        $sth = $this->db->prepare('SHOW CREATE TABLE `' . $table . '`');
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_NUM);
        $sth->closeCursor();
        return (string) ($row[1] ?? '');
    }

    /**
     * Load cấu hình schema từ file JSON.
     * Trả về null nếu chưa có file.
     */
    public function loadSchema(string $table): ?SchemaEntity
    {
        $path = $this->getSchemaPath($table);
        if (!file_exists($path)) {
            return null;
        }
        $data = json_decode(file_get_contents($path), true);
        if (!is_array($data)) {
            return null;
        }
        return SchemaEntity::fromArray($data);
    }

    /**
     * Lưu SchemaEntity ra file JSON.
     */
    public function saveSchema(string $table, SchemaEntity $entity): void
    {
        if (!is_dir($this->dataDir)) {
            nv_mkdir(dirname($this->dataDir), basename($this->dataDir));
        }
        $content = json_encode($entity->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->getSchemaPath($table), $content);
    }

    /**
     * Trả đường dẫn tuyệt đối đến file JSON của bảng.
     */
    public function getSchemaPath(string $table): string
    {
        return $this->dataDir . '/' . $table . '.json';
    }

    // -------------------------------------------------------------------------
    // Module & Table filtering
    // -------------------------------------------------------------------------

    /**
     * Quét thư mục modules và trả danh sách tên module (folder có version.php).
     * Loại bỏ chính module Devtool khỏi danh sách.
     *
     * @param string $modulesDir  Đường dẫn tuyệt đối đến thư mục modules (NV_ROOTDIR . '/modules')
     * @return string[]           Danh sách tên module đã sắp xếp
     */
    public function getModulesList(string $modulesDir): array
    {
        if (!is_dir($modulesDir)) {
            return [];
        }
        $modules = [];
        foreach (scandir($modulesDir) as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            if (!is_dir($modulesDir . '/' . $entry)) {
                continue;
            }
            if (!file_exists($modulesDir . '/' . $entry . '/version.php')) {
                continue;
            }
            // Bỏ qua chính module Devtool
            if (strtolower($entry) === 'devtool') {
                continue;
            }
            $modules[] = $entry;
        }
        sort($modules);
        return $modules;
    }

    /**
     * Lọc danh sách bảng DB theo tên module.
     *
     * Khớp các pattern:
     *   {prefix}_{module}
     *   {prefix}_{module}_{suffix}
     *   {prefix}_{lang}_{module}
     *   {prefix}_{lang}_{module}_{suffix}
     *
     * Trong đó lang là 2–5 ký tự chữ thường (vi, en, zh_cn…).
     *
     * @param string $moduleName  Tên module (PascalCase hoặc lowercase đều được)
     * @param string $prefix      Tiền tố DB từ $db_config['prefix']
     * @return string[]
     */
    public function getFilteredTablesList(string $moduleName, string $prefix): array
    {
        $all = $this->getTablesList();
        if (empty($moduleName)) {
            return $all;
        }

        $moduleKey = preg_quote(strtolower($moduleName), '/');
        $prefixKey = preg_quote($prefix . '_', '/');
        // Khớp: prefix_ (lang_)? module (_suffix)?
        $pattern = '/^' . $prefixKey . '([a-z]{2,5}_)?' . $moduleKey . '(_|$)/i';

        return array_values(array_filter($all, static fn(string $t) => preg_match($pattern, $t) === 1));
    }
}
