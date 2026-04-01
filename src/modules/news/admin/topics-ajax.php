<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$q = $nv_Request->get_title('term', 'get', '');
if (empty($q)) {
    return;
}

$sth = $db->prepare('SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE title LIKE :title OR keywords LIKE :keywords ORDER BY weight ASC LIMIT 50');
$sth->bindValue(':title', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':keywords', '%' . $q . '%', PDO::PARAM_STR);
$sth->execute();

$array_data = [];
while ($_row = $sth->fetch()) {
    $array_data[] = $_row['title'];
}
$sth->closeCursor();

nv_jsonOutput($array_data);
