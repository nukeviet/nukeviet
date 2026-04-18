<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Shared\Traits;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use PDO;

/**
 * AliasRepositoryTrait — Tập hợp các phương thức xử lý cột 'alias' cho Repository.
 *
 * Dùng cho Repository của Entity implement HasAlias.
 * Yêu cầu class sử dụng trait phải có: $this->db, $this->tableName(), $this->entityClass(), $this->primaryKey()
 */
trait AliasRepositoryTrait
{
    /**
     * Tìm Entity theo alias
     */
    public function findByAlias(string $alias): mixed
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->tableName() . ' WHERE alias = :alias');
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data ? $this->entityClass()::fromArray($data) : null;
    }

    /**
     * Kiểm tra alias đã tồn tại chưa (loại trừ bản ghi đang edit)
     */
    public function isAliasExists(string $alias, int $excludeId = 0): bool
    {
        $pk  = $this->primaryKey();
        $sql = 'SELECT COUNT(*) FROM ' . $this->tableName() . ' WHERE alias = :alias';
        if ($excludeId > 0) {
            $sql .= ' AND ' . $pk . ' != :id';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        if ($excludeId > 0) {
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }
}
