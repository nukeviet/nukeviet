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

$mid = $nv_Request->get_int('mid', 'post', 0);
$parentid = $nv_Request->get_int('parentid', 'post', 0);

$arr_item = [];
$arr_item[0] = [
    'key' => 0,
    'title' => $lang_module['cat0'],
    'selected' => ($parentid == 0) ? ' selected="selected"' : ''
];

$sp = '&nbsp;&nbsp;&nbsp;';
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . $mid . ' ORDER BY sort';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $sp_title = '';
    if ($row['lev'] > 0) {
        for ($i = 1; $i <= $row['lev']; ++$i) {
            $sp_title .= $sp;
        }
    }
    $arr_item[$row['id']] = [
        'key' => $row['id'],
        'title' => $sp_title . $row['title'],
        'selected' => ($parentid == $row['id']) ? ' selected="selected"' : ''
    ];
}
$xtpl = new XTemplate('rows.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
foreach ($arr_item as $arr_items) {
    $xtpl->assign('cat', $arr_items);
    $xtpl->parse('main.cat');
}
$contents = $xtpl->text('main.cat');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
