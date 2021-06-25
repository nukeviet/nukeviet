<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$q = $nv_Request->get_title('term', 'get', '', 1);
if (empty($q)) {
    return;
}

$db->sqlreset()
    ->select('title, link')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_sources')
    ->where('title LIKE :title OR link LIKE :link')
    ->order('weight ASC')
    ->limit(50);

$sth = $db->prepare($db->sql());
$sth->bindValue(':title', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':link', '%' . $q . '%', PDO::PARAM_STR);
$sth->execute();

$array_data = [];
while (list($title, $link) = $sth->fetch(3)) {
    $array_data[] = ['label' => $title . ': ' . $link, 'value' => $link];
}

nv_jsonOutput($array_data);
