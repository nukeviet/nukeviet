<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Dùng Query Builder (chuẩn)
$db_slave->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_items')
    ->where('status = 1');
$total = (int) $db_slave->query($db_slave->sql())->fetchColumn();

if ($total > 0) {
    $offset = ($page - 1) * $perPage;
    $db_slave->select('id, title, alias, created_at')
        ->order('created_at DESC')
        ->limit($perPage)
        ->offset($offset);
    $rows = $db_slave->query($db_slave->sql())->fetchAll();
}

// Hoặc dùng SQL thuần (cũng được)
$where = ' WHERE status = 1';
$total = (int) $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_items' . $where)->fetchColumn();

if ($total > 0) {
    $offset = ($page - 1) * $perPage;
    $sql    = 'SELECT id, title, alias, created_at'
            . ' FROM '  . NV_PREFIXLANG . '_items' . $where
            . ' ORDER BY created_at DESC'
            . ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
    $rows = $db_slave->query($sql)->fetchAll();
}
