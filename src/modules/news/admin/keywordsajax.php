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

$respon = [
    'results' => [],
    'pagination' => [
        'more' => false
    ]
];

$q = $nv_Request->get_title('q', 'post', '');
$page = $nv_Request->get_page('page', 'post', 1);
$per_page = 20;

if (nv_strlen($q) < 2 or !csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
    nv_jsonOutput($respon);
}

$sql_count = 'SELECT COUNT(tid) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE alias LIKE :alias OR keywords LIKE :keywords';
$sth = $db->prepare($sql_count);
$sth->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':keywords', '%' . $q . '%', PDO::PARAM_STR);
$sth->execute();
$num_items = $sth->fetchColumn();

$sql_data = 'SELECT keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE alias LIKE :alias OR keywords LIKE :keywords ORDER BY alias ASC LIMIT :limit OFFSET :offset';
$sth = $db->prepare($sql_data);
$sth->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':keywords', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':limit', $per_page, PDO::PARAM_INT);
$sth->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
$sth->execute();

$array_data = [];
while ($_row = $sth->fetch()) {
    $keywords = explode(',', $_row['keywords']);
    foreach ($keywords as $_keyword) {
        $array_data[] = nv_unhtmlspecialchars(str_replace('-', ' ', $_keyword));
    }
}
$sth->closeCursor();

if (count($array_data) < $per_page and $page == 1) {
    if (file_exists(NV_ROOTDIR . '/includes/keywords/' . NV_LANG_DATA . '.php')) {
        $contents = file_get_contents(NV_ROOTDIR . '/includes/keywords/' . NV_LANG_DATA . '.php');
        preg_match_all('/\'([^\']*' . nv_preg_quote($q) . '[^\']*)\'/', $contents, $matches);
        $array_data = array_merge($array_data, $matches[1]);
        $array_data = array_unique($array_data);
        $array_data = array_slice($array_data, 0, 50, true);
    }
}

foreach ($array_data as $_keyword) {
    $respon['results'][] = [
        'id' => $_keyword,
        'text' => $_keyword
    ];
}
$respon['pagination']['more'] = ($page * $per_page) < $num_items;
nv_jsonOutput($respon);
