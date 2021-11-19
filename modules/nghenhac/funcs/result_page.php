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

$xtpl = new XTemplate('result_page.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $query = "SELECT * FROM nv4_vi_nghenhac_game_max_results as tbl1 where diem = (select max(diem) from nv4_vi_nghenhac_game_max_results as tbl2 where tbl1.username = tbl2.username)";
$result = $db->query($query);
$stt = 1;
while ($row = $result->fetch()) {
  
    $xtpl->assign('result', array(
        'stt' => $stt,
        'id' => $row['id'],
        'username' => $row['username'],
        'diem' => $row['diem'],
        'timeupdate' =>  date('H:i d/m/Y', $row['timeupdate'])
    ));
    $xtpl->parse('main.loop_results');
    $stt++;
}
$xtpl->parse('main');
$contents = $xtpl->text('main');
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
