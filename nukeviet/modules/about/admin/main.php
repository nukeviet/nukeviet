<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$page_title = $lang_module['aabout0'];
$array = array();

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

if( $num < 1 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content" );
	die();
}

$array_status = array( $lang_module['aabout6'], $lang_module['aabout5'] );

$i = 0;
while( $row = $db->sql_fetchrow( $result ) )
{
	$row['class'] = ( ++$i % 2 ) ? " class=\"second\"" : "";
	$row['url_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $row['id'];

	for( $i = 1; $i <= $num; ++$i )
	{
		$xtpl->assign( 'WEIGHT', array(
			'w' => $i, //
			'selected' => ( $i == $row['weight'] ) ? " selected=\"selected\"" : "", //
		) );

		$xtpl->parse( 'main.row.weight' );
	}

	foreach( $array_status as $key => $val )
	{
		$xtpl->assign( 'STATUS', array(
			'key' => $key, //
			'val' => $val, //
			'selected' => ( $key == $row['status'] ) ? " selected=\"selected\"" : "", //
		) );

		$xtpl->parse( 'main.row.status' );
	}

	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>