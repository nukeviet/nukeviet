<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// INSERT
$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_items (title, content, status, created_at)
        VALUES (:title, :content, :status, ' . NV_CURRENTTIME . ')';
$sth = $db->prepare($sql);
$sth->bindParam(':title',   $row['title'],   PDO::PARAM_STR);
$sth->bindParam(':content', $row['content'], PDO::PARAM_STR, strlen($row['content']));
$sth->bindParam(':status',  $row['status'],  PDO::PARAM_INT);
$sth->execute();
$new_id = $db->lastInsertId();

// UPDATE
$sql = 'UPDATE ' . NV_PREFIXLANG . '_items
        SET title = :title, content = :content, updated_at = ' . NV_CURRENTTIME . '
        WHERE id = ' . $id;
$sth = $db->prepare($sql);
$sth->bindParam(':title',   $row['title'],   PDO::PARAM_STR);
$sth->bindParam(':content', $row['content'], PDO::PARAM_STR, strlen($row['content']));
$sth->execute();
if ($sth->rowCount()) {
    // cập nhật thành công
}

// DELETE
$db->query('DELETE FROM ' . NV_PREFIXLANG . '_items WHERE id = ' . (int) $id);

// INSERT với helper — gọn hơn prepare+bindParam+execute+lastInsertId
$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_items (title, status, created_at)
        VALUES (:title, :status, ' . NV_CURRENTTIME . ')';
$new_id = $db->insert_id($sql, '', [
    'title'  => $row['title'],
    'status' => (string) $row['status']
]);

// UPDATE/DELETE với helper — trả về số dòng bị ảnh hưởng
$sql = 'UPDATE ' . NV_PREFIXLANG . '_items SET status = :status WHERE id = ' . (int) $id;
$affected = $db->affected_rows_count($sql, ['status' => '1']);
if ($affected) {
    // cập nhật thành công
}
