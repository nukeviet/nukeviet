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
 * StatusRepositoryTrait — Tập hợp các phương thức xử lý cột 'status' cho Repository.
 *
 * Dùng cho Repository của Entity implement HasStatus.
 * Yêu cầu class sử dụng trait phải có: $this->db, $this->tableName(), $this->primaryKey(), $this->findById()
 */
trait StatusRepositoryTrait
{
    /**
     * Toggle trạng thái (0 ↔ 1) cho bản ghi theo khóa chính.
     * @return int Trạng thái mới, -1 nếu không tìm thấy bản ghi
     */
    public function toggleStatus(int $id): int
    {
        $pk  = $this->primaryKey();
        $row = $this->findById($id);
        if (!$row) {
            return -1;
        }
        $newStatus = $row->status ? 0 : 1;
        $stmt      = $this->db->prepare('UPDATE ' . $this->tableName() . ' SET status = :status WHERE ' . $pk . ' = :id');
        $stmt->bindValue(':status', $newStatus, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $newStatus;
    }
}
