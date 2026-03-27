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
$sql = "INSERT INTO " . NV_PREFIXLANG . "_items (title, content, status, created_at)
        VALUES (:title, :content, :status, :created_at)";
$sth = $db->prepare($sql);
$sth->bindValue(':title', $row['title'], PDO::PARAM_STR);
$sth->bindValue(':content', $row['content'], PDO::PARAM_STR);
$sth->bindValue(':status', $row['status'], PDO::PARAM_INT);
$sth->bindValue(':created_at', NV_CURRENTTIME, PDO::PARAM_INT);
$sth->execute();
$new_id = $db->lastInsertId();

// UPDATE
$sql = "UPDATE " . NV_PREFIXLANG . "_items SET
        title = :title,
        content = :content,
        updated_at = :updated_at
        WHERE id = :id";
$sth = $db->prepare($sql);
$sth->bindValue(':title', $row['title'], PDO::PARAM_STR);
$sth->bindValue(':content', $row['content'], PDO::PARAM_STR);
$sth->bindValue(':updated_at', NV_CURRENTTIME, PDO::PARAM_INT);
$sth->bindValue(':id', $id, PDO::PARAM_INT);
$sth->execute();
if ($sth->rowCount()) {
    // cập nhật thành công
}

// DELETE
$sth = $db->prepare("DELETE FROM " . NV_PREFIXLANG . "_items WHERE id = :id");
$sth->bindValue(':id', $id, PDO::PARAM_INT);
$sth->execute();
if ($sth->rowCount()) {
    // xóa thành công
}
