<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if (! defined ( 'NV_IS_MOD_NEWS' ))
	die ( 'Stop!!!' );
global $module_data, $module_name, $module_file, $global_array_cat, $global_config, $lang_module;
$xtpl = new XTemplate ( "block_newscenter.tpl", NV_ROOTDIR . "/themes/" . $module_info ['template'] . "/modules/" . $module_file );
$xtpl->assign ( 'lang', $lang_module );
$sql = "SELECT id, listcatid, publtime, exptime, title, alias, hometext, homeimgthumb FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 0 , 4";
$result = $db->sql_query ( $sql );
$i = 1;
while ( $row = $db->sql_fetchrow ( $result ) ) {
	$catid = explode ( ',', $row ['listcatid'] );
	$row ['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat [$catid [0]] ['alias'] . "/" . $row ['alias'] . "-" . $row ['id'];
	if (! empty ( $row ['homeimgthumb'] )) {
		$imgthumb = explode ( '|', $row ['homeimgthumb'] );
		$row ['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $imgthumb [1];
	} else {
		$row ['imgsource'] = '' . NV_BASE_SITEURL . 'themes/' . $global_config ['site_theme'] . '/images/no_image.gif';
	}
	$row ['hometext'] = nv_clean60 ( strip_tags ( $row ['hometext'] ), 360 );
	if ($i == 1) {
		$xtpl->assign ( 'main', $row );
		$i ++;
	} else {
		$xtpl->assign ( 'othernews', $row );
		$xtpl->parse ( 'main.othernews' );
	}
}
$xtpl->parse ( 'main' );
$content = $xtpl->text ( 'main' );
?>