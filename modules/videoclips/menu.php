<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Thinhweb Blog (thinhwebhp@gmail.com)
 * @Copyright (C) 2019 Thinhweb Blog. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 02/28/2019 14:35
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$sql = 'SELECT id, title, alias FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_topic WHERE status=1 ORDER BY weight ASC';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_item[$row['id']] = array(
        'key' => $row['id'],
        'title' => $row['title'],
        'alias' => $row['alias']
    );
}
