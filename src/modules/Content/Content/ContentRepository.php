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

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use PDO;
use NukeViet\Module\Content\Shared\BaseRepository;

/**
 * ContentRepository — Tầng truy vấn dữ liệu cho bài viết
 * Tập trung mọi SQL vào đây, Controller không viết SQL trực tiếp
 */
class ContentRepository extends BaseRepository
{
    protected function entityClass(): string
    {
        return ContentEntity::class;
    }

    public function saveConfig(array $config): void
    {
        $sth = $this->db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
        $sth->bindValue(':module_name', $this->module_name, PDO::PARAM_STR);
        foreach ($config as $config_name => $config_value) {
            $sth->bindValue(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindValue(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        $this->cache->delMod('settings');
        $this->cache->delMod($this->module_name);
    }

    /**
     * Tìm bài viết theo ID
     */
    public function findById(int $id): ?ContentEntity
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->tables->content . ' WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data ? ContentEntity::fromArray($data) : null;
    }

    /**
     * Tìm bài viết theo alias
     */
    public function findByAlias(string $alias): ?ContentEntity
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->tables->content . ' WHERE alias = :alias');
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data ? ContentEntity::fromArray($data) : null;
    }

    /**
     * Đếm tổng bài viết active
     */
    public function countActive(int $catid = 0): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->tables->content . ' WHERE status = 1';
        if ($catid > 0) {
            $sql .= ' AND catid = :catid';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $this->db->query($sql);
        }
        return (int) $stmt->fetchColumn();
    }

    /**
     * Đếm bài viết (mọi trạng thái) thuộc một catid
     */
    public function countByCatid(int $catid): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->tables->content . ' WHERE catid = :catid');
        $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Lấy danh sách bài viết linh hoạt (dùng cho cả frontend và admin)
     *
     * @param int $catid Lọc theo chủ đề (0 = tất cả)
     * @param int $status Lọc theo trạng thái (-1 = tất cả, 0 = ẩn, 1 = hiển thị)
     * @param int $page Trang hiện tại (bắt đầu từ 1)
     * @param int $per_page Số bản ghi/trang (0 = lấy hết, không phân trang)
     * @return ContentEntity[]
     */
    public function getContentList(int $catid = 0, int $status = 1, int $page = 1, int $per_page = 0): array
    {
        $sql = 'SELECT * FROM ' . $this->tables->content;
        $where = [];

        if ($status >= 0) {
            $where[] = 'status = :status';
        }
        if ($catid > 0) {
            $where[] = 'catid = :catid';
        }
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY weight ASC';

        if ($per_page > 0) {
            $sql .= ' LIMIT :offset, :limit';
        }

        $stmt = $this->db->prepare($sql);
        if ($status >= 0) {
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        }
        if ($catid > 0) {
            $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
        }
        if ($per_page > 0) {
            $stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $this->fetchEntities($stmt);
    }

    /**
     * Kiểm tra alias trùng
     */
    public function isAliasExists(string $alias, int $excludeId = 0): bool
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->tables->content . ' WHERE alias = :alias';
        if ($excludeId > 0) {
            $sql .= ' AND id != :id';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        if ($excludeId > 0) {
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Lưu bài viết (INSERT hoặc UPDATE)
     * @return int ID bài viết
     */
    public function save(array $data, int $id = 0): int
    {
        // Lọc bỏ những trường "ảo" (category, link...) không có trong DB
        $data = array_intersect_key($data, array_flip(ContentEntity::getDbColumns()));

        if ($id > 0) {
            // UPDATE
            $fields = [];
            $params = [':id' => [$id, PDO::PARAM_INT]];
            foreach ($data as $key => $value) {
                $fields[] = $key . ' = :' . $key;
                $params[':' . $key] = [$value, $this->pdoType($key)];
            }
            $stmt = $this->db->prepare('UPDATE ' . $this->tables->content . ' SET ' . implode(', ', $fields) . ' WHERE id = :id');
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v[0], $v[1]);
            }
            $stmt->execute();
            return $id;
        }

        // INSERT
        $columns = array_keys($data);
        $placeholders = array_map(fn($k) => ':' . $k, $columns);
        $stmt = $this->db->prepare(
            'INSERT INTO ' . $this->tables->content . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')'
        );
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value, $this->pdoType($key));
        }
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }

    /**
     * Xóa bài viết + comment liên quan
     */
    public function delete(int $id, string $commentTable = ''): bool
    {
        $stmt = $this->db->prepare('DELETE FROM ' . $this->tables->content . ' WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if ($result && !empty($commentTable)) {
            $stmt = $this->db->prepare('DELETE FROM ' . $commentTable . ' WHERE module = :module AND id = :id');
            $stmt->bindValue(':module', $this->module_name, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        return $result;
    }

    /**
     * Toggle trạng thái bài viết
     * @return int Trạng thái mới, -1 nếu lỗi
     */
    public function toggleStatus(int $id): int
    {
        $row = $this->findById($id);
        if (!$row) {
            return -1;
        }
        $newStatus = $row->status ? 0 : 1;
        $stmt = $this->db->prepare('UPDATE ' . $this->tables->content . ' SET status = :status WHERE id = :id');
        $stmt->bindValue(':status', $newStatus, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $newStatus;
    }

    /**
     * Sắp xếp lại weight sau khi xóa hoặc đổi vị trí.
     * Dùng bulk UPDATE (CASE WHEN) thay vì UPDATE từng dòng trong vòng lặp.
     * Chỉ cập nhật những row có weight thực sự thay đổi.
     */
    public function reorderWeight(int $movedId = 0, int $newWeight = 0): void
    {
        $sql = 'SELECT id, weight FROM ' . $this->tables->content;
        $params = [];
        if ($movedId > 0) {
            $sql .= ' WHERE id != :id';
            $params[':id'] = $movedId;
        }
        $sql .= ' ORDER BY weight ASC';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_INT);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $cases = [];
        $ids = [];
        $calcWeight = 0;

        foreach ($rows as $row) {
            ++$calcWeight;
            if ($movedId > 0 && $calcWeight == $newWeight) {
                ++$calcWeight;
            }
            // Chỉ đưa vào bulk UPDATE nếu weight thực sự thay đổi
            if ($calcWeight !== (int) $row['weight']) {
                $cases[] = 'WHEN ' . (int) $row['id'] . ' THEN ' . $calcWeight;
                $ids[] = (int) $row['id'];
            }
        }

        // Cập nhật weight của bài được di chuyển
        if ($movedId > 0 && $newWeight > 0) {
            $cases[] = 'WHEN ' . $movedId . ' THEN ' . $newWeight;
            $ids[] = $movedId;
        }

        if (empty($ids)) {
            return;
        }

        $this->db->exec(
            'UPDATE ' . $this->tables->content
                . ' SET weight = CASE id ' . implode(' ', $cases) . ' END'
                . ' WHERE id IN (' . implode(',', $ids) . ')'
        );
    }

    /**
     * Lấy bài liên quan (cùng catid hoặc khác id)
     * @return ContentEntity[]
     */
    public function getRelated(int $currentId, int $limit, int $catid = 0): array
    {
        if ($catid > 0) {
            $stmt = $this->db->prepare(
                'SELECT * FROM ' . $this->tables->content . ' WHERE status = 1 AND id != :id AND catid = :catid ORDER BY weight ASC LIMIT :limit'
            );
            $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
        } else {
            $stmt = $this->db->prepare(
                'SELECT * FROM ' . $this->tables->content . ' WHERE status = 1 AND id != :id ORDER BY weight ASC LIMIT :limit'
            );
        }
        $stmt->bindValue(':id', $currentId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $this->fetchEntities($stmt);
    }

    /**
     * Tăng hitstotal (lượt xem)
     */
    public function incrementHits(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE ' . $this->tables->content . ' SET hitstotal = hitstotal + 1 WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Lấy ID lớn nhất
     */
    public function getMaxId(): int
    {
        $stmt = $this->db->query('SELECT MAX(id) FROM ' . $this->tables->content);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Lấy weight lớn nhất
     */
    public function getMaxWeight(): int
    {
        $stmt = $this->db->query('SELECT MAX(weight) FROM ' . $this->tables->content);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Tăng weight của tất cả bài viết lên 1
     */
    public function incrementOthersWeight(): void
    {
        $this->db->prepare('UPDATE ' . $this->tables->content . ' SET weight = weight + 1')->execute();
    }

    /**
     * Tự động sửa lại weight nếu sai lệch cho toàn bộ mảng dữ liệu.
     * Dùng bulk UPDATE (CASE WHEN) thay vì UPDATE từng dòng trong vòng lặp.
     * Chỉ cập nhật những row có weight thực sự thay đổi.
     * Trả về true nếu CÓ update
     */
    public function autoCorrectWeight(array &$entities): bool
    {
        $cases = [];
        $ids = [];
        $iw = 0;

        foreach ($entities as $entity) {
            ++$iw;
            if ($iw != $entity->weight) {
                $entity->weight = $iw;
                $cases[] = 'WHEN ' . (int) $entity->id . ' THEN ' . $iw;
                $ids[] = (int) $entity->id;
            }
        }

        if (empty($ids)) {
            return false;
        }

        $this->db->exec(
            'UPDATE ' . $this->tables->content
                . ' SET weight = CASE id ' . implode(' ', $cases) . ' END'
                . ' WHERE id IN (' . implode(',', $ids) . ')'
        );

        return true;
    }
}
