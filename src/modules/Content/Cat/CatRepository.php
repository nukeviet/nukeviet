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

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use PDO;
use NukeViet\Module\Content\Shared\BaseRepository;

/**
 * CatRepository — Tầng truy vấn dữ liệu cho Chủ đề
 * Tập trung mọi SQL vào đây, Controller không viết SQL trực tiếp
 */
class CatRepository extends BaseRepository
{
    protected function entityClass(): string
    {
        return CatEntity::class;
    }

    /**
     * Lấy tất cả chủ đề
     * @return CatEntity[]
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM ' . $this->tables->cat . ' ORDER BY weight ASC');
        return $this->fetchEntities($stmt);
    }

    /**
     * Lấy tất cả chủ đề active
     * @return CatEntity[]
     */
    public function getAllActive(): array
    {
        $stmt = $this->db->query('SELECT * FROM ' . $this->tables->cat . ' WHERE status = 1 ORDER BY weight ASC');
        return $this->fetchEntities($stmt);
    }

    /**
     * Tìm chủ đề theo ID
     */
    public function findById(int $catid): ?CatEntity
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->tables->cat . ' WHERE catid = :catid');
        $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data ? CatEntity::fromArray($data) : null;
    }

    /**
     * Tìm chủ đề theo alias
     */
    public function findByAlias(string $alias): ?CatEntity
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->tables->cat . ' WHERE alias = :alias');
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data ? CatEntity::fromArray($data) : null;
    }

    /**
     * Đếm tổng chủ đề
     */
    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM ' . $this->tables->cat);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Kiểm tra alias trùng
     */
    public function isAliasExists(string $alias, int $excludeId = 0): bool
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->tables->cat . ' WHERE alias = :alias';
        if ($excludeId > 0) {
            $sql .= ' AND catid != :catid';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        if ($excludeId > 0) {
            $stmt->bindValue(':catid', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Lưu chủ đề (INSERT hoặc UPDATE)
     * @return int catid của chủ đề
     */
    public function save(array $data, int $catid = 0): int
    {
        // Lọc bỏ những trường "ảo" (link, url_edit...) không có trong DB
        $data = array_intersect_key($data, array_flip(CatEntity::getDbColumns()));

        if ($catid > 0) {
            // UPDATE
            $fields = [];
            $params = [':catid' => [$catid, PDO::PARAM_INT]];
            foreach ($data as $key => $value) {
                $fields[] = $key . ' = :' . $key;
                $params[':' . $key] = [$value, $this->pdoType($key)];
            }
            $stmt = $this->db->prepare('UPDATE ' . $this->tables->cat . ' SET ' . implode(', ', $fields) . ' WHERE catid = :catid');
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v[0], $v[1]);
            }
            $stmt->execute();
            return $catid;
        }

        // INSERT
        $columns = array_keys($data);
        $placeholders = array_map(fn($k) => ':' . $k, $columns);
        $stmt = $this->db->prepare(
            'INSERT INTO ' . $this->tables->cat . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')'
        );
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value, $this->pdoType($key));
        }
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }

    /**
     * Xóa chủ đề
     */
    public function delete(int $catid): bool
    {
        $stmt = $this->db->prepare('DELETE FROM ' . $this->tables->cat . ' WHERE catid = :catid');
        $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Toggle trạng thái chủ đề
     */
    public function toggleStatus(int $catid): int
    {
        $row = $this->findById($catid);
        if (!$row) {
            return -1;
        }
        $newStatus = $row->status ? 0 : 1;
        $stmt = $this->db->prepare('UPDATE ' . $this->tables->cat . ' SET status = :status WHERE catid = :catid');
        $stmt->bindValue(':status', $newStatus, PDO::PARAM_INT);
        $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt->execute();
        return $newStatus;
    }

    /**
     * Sắp xếp lại weight sau khi xóa hoặc đổi vị trí
     */
    public function reorderWeight(int $movedId = 0, int $newWeight = 0): void
    {
        $sql = 'SELECT catid FROM ' . $this->tables->cat;
        $params = [];
        if ($movedId > 0) {
            $sql .= ' WHERE catid != :catid';
            $params[':catid'] = $movedId;
        }
        $sql .= ' ORDER BY weight ASC';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_INT);
        }
        $stmt->execute();

        $weight = 0;
        $stmtUpdate = $this->db->prepare('UPDATE ' . $this->tables->cat . ' SET weight = :weight WHERE catid = :catid');
        while ($row = $stmt->fetch()) {
            ++$weight;
            if ($movedId > 0 && $weight == $newWeight) {
                ++$weight;
            }
            $stmtUpdate->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmtUpdate->bindValue(':catid', $row['catid'], PDO::PARAM_INT);
            $stmtUpdate->execute();
        }
        $stmt->closeCursor();

        if ($movedId > 0 && $newWeight > 0) {
            $stmtUpdate->bindValue(':weight', $newWeight, PDO::PARAM_INT);
            $stmtUpdate->bindValue(':catid', $movedId, PDO::PARAM_INT);
            $stmtUpdate->execute();
        }
    }

    /**
     * Tự động sửa lại weight cho mảng object nếu có sai lệch
     * Trả về true nếu CÓ thay đổi
     */
    public function autoCorrectWeight(array &$cats): bool
    {
        $iw = 0;
        $is_updated = false;
        $stmt = $this->db->prepare('UPDATE ' . $this->tables->cat . ' SET weight = :weight WHERE catid = :catid');
        foreach ($cats as $cat) {
            ++$iw;
            if ($iw != $cat->weight) {
                $cat->weight = $iw;
                $stmt->bindValue(':weight', $iw, PDO::PARAM_INT);
                $stmt->bindValue(':catid', $cat->catid, PDO::PARAM_INT);
                $stmt->execute();
                $is_updated = true;
            }
        }
        return $is_updated;
    }

    /**
     * Lấy weight lớn nhất hiện có
     */
    public function getMaxWeight(): int
    {
        $stmt = $this->db->query('SELECT MAX(weight) FROM ' . $this->tables->cat);
        return (int) $stmt->fetchColumn();
    }

}
