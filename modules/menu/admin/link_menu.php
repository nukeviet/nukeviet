<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$mid = $nv_Request->get_int('mid', 'post', 0);
$parentid = $nv_Request->get_int('parentid', 'post', 0);

$arr_item = array();
$arr_item[0] = array(
    'key' => 0,
    'title' => $lang_module['cat0'],
    'selected' => ($parentid == 0) ? ' selected="selected"' : ''
);

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
    $arr_item[$row['id']] = array(
        'key' => $row['id'],
        'title' => $sp_title . $row['title'],
        'selected' => ($parentid == $row['id']) ? ' selected="selected"' : ''
    );
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
