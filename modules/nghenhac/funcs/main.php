<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}
$xtpl = new XTemplate('music.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('rowcontent', $rowcontent);
$xtpl->assign('ISCOPY', $copy);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_DATA', $module_data);
$xtpl->assign('OP', $op);
$query = "SELECT * FROM nv4_vi_music_cats ORDER BY add_time";

$result = $db->query($query);

while ($row = $result->fetch()) {
  
    $xtpl->assign('categories', array(
        'id' => $row['id'],
        'cat_name' => $row['cat_name'],
        'add_time' => date('d/m/Y', $row['add_time']),
        'update_time' =>  date('d/m/Y', $row['update_time'])
    ));
    $xtpl->parse('main.loop_categories');
}
$value = $nv_Request->get_title('delete', 'post');
if($value)
{
    die('ab');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
