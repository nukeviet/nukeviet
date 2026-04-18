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
 * WeightRepositoryTrait — Tập hợp các phương thức xử lý cột 'weight' cho Repository.
 *
 * Dùng cho Repository của Entity implement HasWeight.
 * Yêu cầu class sử dụng trait phải có: $this->db, $this->tableName(), $this->primaryKey()
 */
trait WeightRepositoryTrait
{
    /**
     * Lấy weight lớn nhất hiện có
     */
    public function getMaxWeight(): int
    {
        $stmt = $this->db->query('SELECT MAX(weight) FROM ' . $this->tableName());
        return (int) $stmt->fetchColumn();
    }

    /**
     * Sắp xếp lại weight sau khi xóa hoặc đổi vị trí.
     * Dùng bulk UPDATE (CASE WHEN) thay vì UPDATE từng dòng trong vòng lặp.
     * Chỉ cập nhật những row có weight thực sự thay đổi.
     *
     * @param int $movedId  ID bản ghi vừa được di chuyển (0 = chỉ reorder sau xóa)
     * @param int $newWeight Weight mới của bản ghi di chuyển
     */
    public function reorderWeight(int $movedId = 0, int $newWeight = 0): void
    {
        $pk  = $this->primaryKey();
        $sql = 'SELECT ' . $pk . ', weight FROM ' . $this->tableName();
        $params = [];
        if ($movedId > 0) {
            $sql .= ' WHERE ' . $pk . ' != :id';
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

        $cases      = [];
        $ids        = [];
        $calcWeight = 0;

        foreach ($rows as $row) {
            ++$calcWeight;
            if ($movedId > 0 && $calcWeight == $newWeight) {
                ++$calcWeight;
            }
            // Chỉ đưa vào bulk UPDATE nếu weight thực sự thay đổi
            if ($calcWeight !== (int) $row['weight']) {
                $cases[] = 'WHEN ' . (int) $row[$pk] . ' THEN ' . $calcWeight;
                $ids[]   = (int) $row[$pk];
            }
        }

        // Cập nhật weight của bản ghi di chuyển
        if ($movedId > 0 && $newWeight > 0) {
            $cases[] = 'WHEN ' . $movedId . ' THEN ' . $newWeight;
            $ids[]   = $movedId;
        }

        if (empty($ids)) {
            return;
        }

        $this->db->exec(
            'UPDATE ' . $this->tableName()
                . ' SET weight = CASE ' . $pk . ' ' . implode(' ', $cases) . ' END'
                . ' WHERE ' . $pk . ' IN (' . implode(',', $ids) . ')'
        );
    }

    /**
     * Tự động sửa lại weight nếu sai lệch cho toàn bộ mảng Entity.
     * Dùng bulk UPDATE (CASE WHEN) thay vì UPDATE từng dòng trong vòng lặp.
     * Trả về true nếu CÓ update.
     *
     * @param array $entities Mảng Entity đã được sắp xếp theo weight ASC
     */
    public function autoCorrectWeight(array &$entities): bool
    {
        $pk    = $this->primaryKey();
        $cases = [];
        $ids   = [];
        $iw    = 0;

        foreach ($entities as $entity) {
            ++$iw;
            if ($iw != $entity->weight) {
                $entity->weight = $iw;
                $cases[] = 'WHEN ' . (int) $entity->$pk . ' THEN ' . $iw;
                $ids[]   = (int) $entity->$pk;
            }
        }

        if (empty($ids)) {
            return false;
        }

        $this->db->exec(
            'UPDATE ' . $this->tableName()
                . ' SET weight = CASE ' . $pk . ' ' . implode(' ', $cases) . ' END'
                . ' WHERE ' . $pk . ' IN (' . implode(',', $ids) . ')'
        );

        return true;
    }
}
