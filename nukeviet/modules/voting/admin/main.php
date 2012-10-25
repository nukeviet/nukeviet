<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['voting_list'];

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` ORDER BY `vid` ASC";
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) )
{
	$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );

	$a = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$sql1 = "SELECT SUM(hitstotal) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `vid`='" . $row['vid'] . "'";
		$result1 = $db->sql_query( $sql1 );
		$totalvote = $db->sql_fetchrow( $result1 );
		
		$xtpl->assign( 'ROW', array(
			"class" => $a % 2 ? " class=\"second\"" : "",
			"status" => $row['act'] == 1 ? $lang_module['voting_yes'] : $lang_module['voting_no'],
			"vid" => $row['vid'],
			"question" => $row['question'],
			"totalvote" => $totalvote[0],
			"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;vid=" . $row['vid'],
			"checksess" => md5( $row['vid'] . session_id() ),
		) );
		
		$xtpl->parse( 'main.loop' );
		++ $a;
	}
	
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content" );
	die();
}

?>