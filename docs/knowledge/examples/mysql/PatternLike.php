<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Escape ký tự đặc biệt % và _ trong LIKE
$keyword = $db->dblikeescape($nv_Request->get_title('keyword', 'get', ''));
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_items WHERE title LIKE :kw';
$sth = $db->prepare($sql);
$kw  = '%' . $keyword . '%';
$sth->bindParam(':kw', $kw, PDO::PARAM_STR);
$sth->execute();
$rows = $sth->fetchAll();
