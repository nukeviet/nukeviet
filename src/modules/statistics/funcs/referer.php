<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
$mod_title = $page_title = sprintf($lang_module['refererbysite'], $host);
$key_words = $module_info['keywords'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&host=' . $host;
$canonicalUrl = getCanonicalUrl($page_url, true, true);

$cts = [];
$cts['caption'] = $page_title;
$cts['rows'] = [];
$cts['rows']['Jan'] = ['fullname' => $lang_global['january'], 'count' => $row['month01']];
$cts['rows']['Feb'] = ['fullname' => $lang_global['february'], 'count' => $row['month02']];
$cts['rows']['Mar'] = ['fullname' => $lang_global['march'], 'count' => $row['month03']];
$cts['rows']['Apr'] = ['fullname' => $lang_global['april'], 'count' => $row['month04']];
$cts['rows']['May'] = ['fullname' => $lang_global['may'], 'count' => $row['month05']];
$cts['rows']['Jun'] = ['fullname' => $lang_global['june'], 'count' => $row['month06']];
$cts['rows']['Jul'] = ['fullname' => $lang_global['july'], 'count' => $row['month07']];
$cts['rows']['Aug'] = ['fullname' => $lang_global['august'], 'count' => $row['month08']];
$cts['rows']['Sep'] = ['fullname' => $lang_global['september'], 'count' => $row['month09']];
$cts['rows']['Oct'] = ['fullname' => $lang_global['october'], 'count' => $row['month10']];
$cts['rows']['Nov'] = ['fullname' => $lang_global['november'], 'count' => $row['month11']];
$cts['rows']['Dec'] = ['fullname' => $lang_global['december'], 'count' => $row['month12']];

$total = 0;
$max = 0;
foreach ($cts['rows'] as $key => $month) {
    $total = $total + $month['count'];
    if ($month['count'] > $max) {
        $max = $month['count'];
    }
}

if ($total) {
    $cts['current_month'] = date('M', NV_CURRENTTIME);
    $cts['max'] = $max;
    $cts['total'] = [$lang_global['total'], number_format($total)];
}

$contents = nv_theme_statistics_referer($cts, $total);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
