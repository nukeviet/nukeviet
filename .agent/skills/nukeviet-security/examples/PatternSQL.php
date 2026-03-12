<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// ❌ Sai — Nối chuỗi mảng ID gây SQLi
// $sql = "WHERE id IN (" . implode(',', $_POST['ids']) . ")";

// ✅ Đúng — Ép kiểu nguyên cho toàn bộ mảng trước khi implode
$ids = array_map('intval', $nv_Request->get_typed_array('ids', 'post', 'int', []));
$sql = "WHERE id IN (" . implode(',', $ids) . ")";

// ❌ Sai — nối chuỗi input trực tiếp
// $sql = "WHERE title = '" . $_POST['title'] . "'";

// ✅ Đúng — số nguyên dùng (int)
$sql = '... WHERE id = ' . (int) $id;

// ✅ Đúng — chuỗi từ user dùng prepared statement
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_items WHERE title = :title';
$sth = $db->prepare($sql);
$sth->bindParam(':title', $title, PDO::PARAM_STR);
$sth->execute();
$row = $sth->fetch();
