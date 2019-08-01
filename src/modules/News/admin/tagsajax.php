<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$q = $nv_Request->get_title('term', 'get', '', 1);
if (empty($q)) {
    return;
}

$db_slave->sqlreset()
    ->select('keywords')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_tags')
    ->where('alias LIKE :alias OR keywords LIKE :keywords')
    ->order('alias ASC')
    ->limit(50);

$sth = $db_slave->prepare($db_slave->sql());
$sth->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':keywords', '%' . $q . '%', PDO::PARAM_STR);
$sth->execute();

$array_data = array();
while (list($keywords) = $sth->fetch(3)) {
    $keywords = explode(',', $keywords);
    foreach ($keywords as $_keyword) {
        $array_data[] = nv_unhtmlspecialchars(str_replace('-', ' ', $_keyword));
    }
}

nv_jsonOutput($array_data);
