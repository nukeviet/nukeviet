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
use NukeViet\Module\Content\Shared\Traits\AliasRepositoryTrait;
use NukeViet\Module\Content\Shared\Traits\StatusRepositoryTrait;
use NukeViet\Module\Content\Shared\Traits\WeightRepositoryTrait;

/**
 * CatRepository — Tầng truy vấn dữ liệu cho Chủ đề
 * Tập trung mọi SQL vào đây, Controller không viết SQL trực tiếp
 *
 * Traits được nạp tự động theo interface Entity implement:
 * - AliasRepositoryTrait  → CatEntity implements HasAlias  → findByAlias(), isAliasExists()
 * - WeightRepositoryTrait → CatEntity implements HasWeight → getMaxWeight(), reorderWeight(), autoCorrectWeight()
 * - StatusRepositoryTrait → CatEntity implements HasStatus → toggleStatus()
 */
class CatRepository extends BaseRepository
{
    use AliasRepositoryTrait;
    use WeightRepositoryTrait;
    use StatusRepositoryTrait;

    protected function entityClass(): string
    {
        return CatEntity::class;
    }

    protected function tableName(): string
    {
        return $this->tables->cat;
    }

    protected function primaryKey(): string
    {
        return 'catid';
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
     * Đếm tổng chủ đề
     */
    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM ' . $this->tables->cat);
        return (int) $stmt->fetchColumn();
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
        $columns      = array_keys($data);
        $placeholders = array_map(fn($k) => ':' . $k, $columns);
        $stmt         = $this->db->prepare(
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
}
