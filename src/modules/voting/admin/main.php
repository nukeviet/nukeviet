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

$page_title = $nv_Lang->getModule('voting_list');

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY vid ASC';
$result = $db->query($sql);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);

$array_row = [];

while ($row = $result->fetch()) {
    $sql_sum = 'SELECT SUM(hitstotal) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid=' . $row['vid'];
    $totalvote = $db->query($sql_sum)->fetchColumn();
    $array_row[] = [
        'status' => $row['act'],
        'vid' => $row['vid'],
        'question' => $row['question'],
        'totalvote' => $totalvote,
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;vid=' . $row['vid'],
        'checksess' => md5($row['vid'] . NV_CHECK_SESSION)
    ];
}
if (empty($array_row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content');
}

$tpl->assign('DATA', $array_row);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

