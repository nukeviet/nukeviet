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

$sth_count = $db->prepare('SELECT COUNT(tid) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE alias LIKE :alias OR keywords LIKE :keywords');
$sth_count->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
$sth_count->bindValue(':keywords', '%' . $q . '%', PDO::PARAM_STR);
$sth_count->execute();
$num_items = $sth_count->fetchColumn();

$sth = $db->prepare('SELECT keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE alias LIKE :alias OR keywords LIKE :keywords ORDER BY alias ASC LIMIT :limit OFFSET :offset');
$sth->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':keywords', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':limit', $per_page, PDO::PARAM_INT);
$sth->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
$sth->execute();

$array_data = [];
while ($_row = $sth->fetch()) {
    $keywords = explode(',', $_row['keywords']);
    foreach ($keywords as $_keyword) {
        $_keyword = nv_unhtmlspecialchars(str_replace('-', ' ', $_keyword));
        $respon['results'][] = [
            'id' => $_keyword,
            'text' => $_keyword
        ];
    }
}
$sth->closeCursor();

$respon['pagination']['more'] = ($page * $per_page) < $num_items;
nv_jsonOutput($respon);
