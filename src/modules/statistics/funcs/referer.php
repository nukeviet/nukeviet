<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_STATISTICS')) {
    exit('Stop!!!');
}

$host = $nv_Request->get_string('host', 'get', '');

if (!isset($host) or !preg_match('/^[0-9a-z]([-.]?[0-9a-z])*.[a-z]{2,4}$/', $host)) {
    nv_redirect_location(NV_BASE_MOD_URL);
}

$sth = $db->prepare('SELECT * FROM ' . NV_REFSTAT_TABLE . ' WHERE host= :host');
$sth->bindParam(':host', $host, PDO::PARAM_STR);
$sth->execute();

$row = $sth->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_MOD_URL);
}

$contents = '';
$page_title = $nv_Lang->getModule('refererbysite', $host);
$key_words = $module_info['keywords'];
$page_url = NV_BASE_MOD_URL . '&' . NV_OP_VARIABLE . '=' . $op . '&host=' . $host;
$canonicalUrl = getCanonicalUrl($page_url);

$cts = [];
$cts['caption'] = $page_title;
$cts['rows'] = [];
$cts['rows']['Jan'] = ['fullname' => $nv_Lang->getGlobal('january'), 'count' => $row['month01']];
$cts['rows']['Feb'] = ['fullname' => $nv_Lang->getGlobal('february'), 'count' => $row['month02']];
$cts['rows']['Mar'] = ['fullname' => $nv_Lang->getGlobal('march'), 'count' => $row['month03']];
$cts['rows']['Apr'] = ['fullname' => $nv_Lang->getGlobal('april'), 'count' => $row['month04']];
$cts['rows']['May'] = ['fullname' => $nv_Lang->getGlobal('may'), 'count' => $row['month05']];
$cts['rows']['Jun'] = ['fullname' => $nv_Lang->getGlobal('june'), 'count' => $row['month06']];
$cts['rows']['Jul'] = ['fullname' => $nv_Lang->getGlobal('july'), 'count' => $row['month07']];
$cts['rows']['Aug'] = ['fullname' => $nv_Lang->getGlobal('august'), 'count' => $row['month08']];
$cts['rows']['Sep'] = ['fullname' => $nv_Lang->getGlobal('september'), 'count' => $row['month09']];
$cts['rows']['Oct'] = ['fullname' => $nv_Lang->getGlobal('october'), 'count' => $row['month10']];
$cts['rows']['Nov'] = ['fullname' => $nv_Lang->getGlobal('november'), 'count' => $row['month11']];
$cts['rows']['Dec'] = ['fullname' => $nv_Lang->getGlobal('december'), 'count' => $row['month12']];

$total = 0;
$data_label = [];
$data_value = [];
foreach ($cts['rows'] as $key => $month) {
    $data_label[] = $month['fullname'];
    $data_value[] = $month['count'];

    $total += $month['count'];
}

$cts['total'] = $total ? nv_number_format($total) : 0;
$cts['dataLabel'] = implode('_', $data_label);
$cts['dataValue'] = implode('_', $data_value);

$contents = nv_theme_statistics_referer($cts);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
