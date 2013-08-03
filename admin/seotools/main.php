<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:38
 */

if( ! defined( 'NV_IS_FILE_SEOTOOLS' ) )
	die( 'Stop!!!' );

Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=pagetitle' );
exit( );

$sql = "SELECT `id`, `idfile`, `lang_key`, `lang_vi`, `update_vi`, `lang_en`, `update_en`, `lang_fr`, `update_fr`, `lang_cs`, `update_cs`,
 `lang_tr`, `update_tr`, `lang_ja`, `update_ja` FROM `nv3_language` WHERE `lang_vi`='' AND (`idfile`=9 OR `idfile`=13)";
$result = $db->sql_query( $sql );
while( $row = $db->sql_fetch_assoc( $result ) )
{
	$db->sql_query( "UPDATE `nv3_language` SET 
		`lang_en`='" . mysql_real_escape_string( $row['lang_en'] ) . "',
		`lang_fr`='" . mysql_real_escape_string( $row['lang_fr'] ) . "',
		`lang_cs`='" . mysql_real_escape_string( $row['lang_cs'] ) . "',
		`lang_tr`='" . mysql_real_escape_string( $row['lang_tr'] ) . "',
		`lang_ja`='" . mysql_real_escape_string( $row['lang_ja'] ) . "'
		 WHERE `idfile`='8' AND `lang_key`='" . $row['lang_key'] . "'" );
}
?>