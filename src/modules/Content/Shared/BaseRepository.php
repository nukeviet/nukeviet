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

use PDO;

/**
 * BaseRepository — Lớp cha trừu tượng cho mọi Repository của module Content.
 *
 * Tập trung các thành phần dùng chung:
 * - Constructor với $db, $tables, $cache, $module_name
 * - invalidateCache()
 * - pdoType() — xác định PDO bind type theo khai báo Entity
 * - fetchEntities() — hydrate PDOStatement → mảng Entity
 *
 * Mỗi class con PHẢI khai báo entityClass() để trả về tên Entity class của mình.
 */
abstract class BaseRepository
{
    protected PDO $db;
    protected Tables $tables;
    protected $cache;
    protected string $module_name;

    public function __construct(PDO $db, Tables $tables, $cache, string $module_name)
    {
        $this->db          = $db;
        $this->tables      = $tables;
        $this->cache       = $cache;
        $this->module_name = $module_name;
    }

    /**
     * Mỗi Repo con khai báo Entity class của mình.
     * VD: return CatEntity::class;
     */
    abstract protected function entityClass(): string;

    /**
     * Xác định PDO bind type cho 1 cột dựa theo khai báo Entity.
     * Tránh dùng is_int($value) vì giá trị từ Request/string-cast có thể không đáng tin.
     */
    protected function pdoType(string $field): int
    {
        static $cache = [];
        $class = $this->entityClass();
        if (!isset($cache[$class])) {
            $cache[$class] = $class::getIntColumns();
        }
        return isset($cache[$class][$field]) ? PDO::PARAM_INT : PDO::PARAM_STR;
    }

    /**
     * fetchAll + map thành mảng Entity của class con.
     */
    protected function fetchEntities(\PDOStatement $stmt): array
    {
        return array_map([$this->entityClass(), 'fromArray'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Xóa cache module.
     */
    public function invalidateCache(): void
    {
        $this->cache->delMod($this->module_name);
    }
}
