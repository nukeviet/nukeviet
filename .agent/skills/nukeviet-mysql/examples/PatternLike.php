<?php
// Escape ký tự đặc biệt % và _ trong LIKE
$keyword = $db->dblikeescape($nv_Request->get_title('keyword', 'get', ''));
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_items WHERE title LIKE :kw';
$sth = $db->prepare($sql);
$kw  = '%' . $keyword . '%';
$sth->bindParam(':kw', $kw, PDO::PARAM_STR);
$sth->execute();
$rows = $sth->fetchAll();
