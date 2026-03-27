<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Pattern phân trang chuẩn dùng PDO Prepared Statement (Khuyên dùng)
$where = ' WHERE status = 1';
$total = (int) $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_items' . $where)->fetchColumn();

if ($total > 0) {
    $stmt = $db_slave->prepare('SELECT id, title, alias, created_at FROM ' . NV_PREFIXLANG . '_items' . $where . ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
}
