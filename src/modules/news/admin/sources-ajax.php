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

$sth = $db->prepare('SELECT title, link FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE title LIKE :title OR link LIKE :link ORDER BY weight ASC LIMIT 50');
$sth->bindValue(':title', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':link', '%' . $q . '%', PDO::PARAM_STR);
$sth->execute();

$array_data = [];
while ($_row = $sth->fetch()) {
    if (empty($_row['link'])) {
        $array_data[] = ['label' => $_row['title'], 'value' => $_row['title']];
    } else {
        $array_data[] = ['label' => $_row['title'] . ': ' . $_row['link'], 'value' => $_row['link']];
    }
}
$sth->closeCursor();

nv_jsonOutput($array_data);
