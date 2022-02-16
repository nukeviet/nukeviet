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

$array_data = [];
while (list($keywords) = $sth->fetch(3)) {
    $keywords = explode(',', $keywords);
    foreach ($keywords as $_keyword) {
        $array_data[] = nv_unhtmlspecialchars(str_replace('-', ' ', $_keyword));
    }
}

if (sizeof($array_data) < 50) {
    if (file_exists(NV_ROOTDIR . '/includes/keywords/' . NV_LANG_DATA . '.php')) {
        $contents = file_get_contents(NV_ROOTDIR . '/includes/keywords/' . NV_LANG_DATA . '.php');
        preg_match_all('/\'([^\']*' . nv_preg_quote($q). '[^\']*)\'/', $contents, $matches);
        $array_data = array_merge($array_data, $matches[1]);
        $array_data = array_unique($array_data);
        $array_data = array_slice($array_data, 0, 50, true);
    }
}

nv_jsonOutput($array_data);
