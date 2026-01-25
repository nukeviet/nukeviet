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

if (nv_strlen($q) < 2 or $nv_Request->get_title('checkss', 'post', '') != NV_CHECK_SESSION) {
    nv_jsonOutput($respon);
}

$db_slave->sqlreset()
    ->select('COUNT(tid)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_tags')
    ->where('alias LIKE :alias OR keywords LIKE :keywords');

$sth = $db_slave->prepare($db_slave->sql());
$sth->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':keywords', '%' . $q . '%', PDO::PARAM_STR);
$sth->execute();
$num_items = $sth->fetchColumn();
$sth->closeCursor();

$db_slave->select('keywords')->order('alias ASC')->limit($per_page)->offset(($page - 1) * $per_page);

$sth = $db_slave->prepare($db_slave->sql());
$sth->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':keywords', '%' . $q . '%', PDO::PARAM_STR);
$sth->execute();

$array_data = [];
while ([$keywords] = $sth->fetch(3)) {
    $keywords = explode(',', $keywords);
    foreach ($keywords as $_keyword) {
        $_keyword = nv_unhtmlspecialchars(str_replace('-', ' ', $_keyword));
        $respon['results'][] = [
            'id' => $_keyword,
            'text' => $_keyword
        ];
    }
}

$respon['pagination']['more'] = ($page * $per_page) < $num_items;
nv_jsonOutput($respon);
