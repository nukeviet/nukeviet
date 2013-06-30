<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 16-12-2012 15:48
 */

if( ! defined( 'NV_IS_FILE_AUTHORS' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_AJAX' ) )
{
	if( $nv_Request->isset_request( 'changeweight', 'post' ) )
	{
		$mid = $nv_Request->get_int( 'changeweight', 'post', 0 );
		$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

		$query = "SELECT `mid` FROM `" . NV_AUTHORS_GLOBALTABLE . "_module` WHERE `mid`!=" . $mid . " ORDER BY `weight` ASC";
		$result = $db->sql_query( $query );
		$weight = 0;
		while( $row = $db->sql_fetchrow( $result ) )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = "UPDATE `" . NV_AUTHORS_GLOBALTABLE . "_module` SET `weight`=" . $weight . " WHERE `mid`=" . $row['mid'];
			$db->sql_query( $sql );
		}
		$sql = "UPDATE `" . NV_AUTHORS_GLOBALTABLE . "_module` SET `weight`=" . $new_vid . " WHERE `mid`=" . $mid;
		$db->sql_query( $sql );
	}
	elseif( $nv_Request->isset_request( 'changact', 'post' ) )
	{
		$mid = $nv_Request->get_int( 'mid', 'post', 0 );
		$act = $nv_Request->get_int( 'changact', 'post', 1 );
		$query = "SELECT * FROM `" . NV_AUTHORS_GLOBALTABLE . "_module` WHERE `mid`=" . $mid;
		$result = $db->sql_query( $query );
		if( $db->sql_numrows( $result ) )
		{
			$row = $db->sql_fetch_assoc( $result );
			if( ! ( $act == 3 AND ( $row['module'] == 'database' OR $row['module'] == 'settings' OR $row['module'] == 'site' ) ) )
			{
				$act_val = ( $row['act_' . $act] ) ? 0 : 1;
				$checksum = md5( $row['module'] . "#" . $row['act_1'] . "#" . $row['act_2'] . "#" . $row['act_3'] . "#" . $global_config['sitekey'] );
				$db->sql_query( "UPDATE `" . NV_AUTHORS_GLOBALTABLE . "_module` SET `act_" . $act . "` = '" . $act_val . "', `checksum` = '" . $checksum . "' WHERE `mid` = " . $mid );
			}
		}
		die( "OK" );
	}
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
$a = 0;
$result = $db->sql_query( "SELECT * FROM `" . NV_AUTHORS_GLOBALTABLE . "_module` ORDER BY `weight` ASC" );
$numrows = $db->sql_numrows( $result );
while( $row = $db->sql_fetch_assoc( $result ) )
{
	$row['class'] = ( $a++ % 2 == 0 ) ? ' class="second"' : '';
	for( $i = 1; $i <= $numrows; $i++ )
	{
		$xtpl->assign( 'WEIGHT', array( 'key' => $i, 'selected' => ( $i == $row['weight'] ) ? ' selected="selected"' : '' ) );
		$xtpl->parse( 'main.loop.weight' );
	}
	$row['custom_title'] = isset( $lang_global[$row['lang_key']] ) ? $lang_global[$row['lang_key']] : '';
	$chang_act = array();
	for( $i = 1; $i <= 3; $i++ )
	{
		$chang_act[$i] = ( $row['act_' . $i] ) ? ' checked="checked"' : '';
		if( $i == 3 AND ( $row['module'] == 'database' OR $row['module'] == 'settings' OR $row['module'] == 'site' ) )
		{
			$chang_act[$i] .= ' disabled="disabled"';
		}
	}
	$xtpl->assign( 'ROW', $row );
	$xtpl->assign( 'CHANG_ACT', $chang_act );

	$xtpl->parse( 'main.loop' );
}
if( ! defined( 'NV_IS_AJAX' ) )
{
	$xtpl->parse( 'main.script' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

if( ! defined( 'NV_IS_AJAX' ) )
{
	$page_title = $lang_module['module_admin'];
	$contents = nv_admin_theme( $contents );
}

include ( NV_ROOTDIR . '/includes/header.php' );
echo $contents;
include ( NV_ROOTDIR . '/includes/footer.php' );

?>