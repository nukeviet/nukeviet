<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

//thay đổi weight
$id = $nv_Request->get_int('id', 'post', 0);
$new_weight = $nv_Request->get_int('new_weight', 'post', 0);
if(!empty($new_weight)) {
	$sql = 'SELECT vid FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE vid=' . $id;
	$vid = $db->query($sql)->fetchColumn();
	if (empty($vid)) {
	    die('NO_' . $vid);
	}
	$sql = 'SELECT vid FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE vid!=' . $id . ' ORDER BY weight ASC';
	$result = $db->query($sql);

	$weight = 0;
	while ($row = $result->fetch()) {
	    ++$weight;
	    if ($weight == $new_weight) {
	        ++$weight;
	    }

	    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $weight . ' WHERE vid=' . $row['vid'];
	    $db->query($sql);
	}

	$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $new_weight . ' WHERE vid=' . $id;
	$db->query($sql);

	$nv_Cache->delMod($module_name);
	die('OK_' . $id);
}



$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ASC';
$result = $db->query($sql);
$num = sizeof(($db->query($sql)->fetchAll()));
$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$i = 0;
while ($row = $result->fetch()) {
    $sql1 = 'SELECT SUM(hitstotal) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid=' . $row['vid'];
    $totalvote = $db->query($sql1)->fetchColumn();
    ++$i;
    $xtpl->assign('ROW', array(
        'status' => $row['act'] == 1 ? $lang_module['voting_yes'] : $lang_module['voting_no'],
        'vid' => $row['vid'],
        'question' => $row['question'],
        'weight' => $row['weight'],
        'totalvote' => $totalvote,
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;vid=' . $row['vid'],
        'checksess' => md5($row['vid'] . NV_CHECK_SESSION)
    ));
	for ($i = 1; $i <= $num; ++$i) {
        $xtpl->assign('WEIGHT', array(
            'w' => $i,
            'selected' => ($i == $row['weight']) ? ' selected="selected"' : ''
        ));

        $xtpl->parse('main.loop.weight');
    }
    $xtpl->parse('main.loop');
}
if (empty($i)) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content');
    die();
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['voting_list'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
