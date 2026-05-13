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
!defined('NV_ADMIN') && $canonicalUrl = getCanonicalUrl($page_url);

$cts = [
    'caption' => $page_title,
    'keys'    => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    'values'  => [
        (int) $row['month01'], (int) $row['month02'], (int) $row['month03'],
        (int) $row['month04'], (int) $row['month05'], (int) $row['month06'],
        (int) $row['month07'], (int) $row['month08'], (int) $row['month09'],
        (int) $row['month10'], (int) $row['month11'], (int) $row['month12'],
    ],
];

$contents = nv_theme_statistics_referer($cts);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
