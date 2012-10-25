<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$title = $note = $modfile = "";

if( filter_text_input( 'checkss', 'post' ) == md5( session_id() . "addmodule" ) )
{
	$title = filter_text_input( 'title', 'post', '', 1 );
	$modfile = filter_text_input( 'module_file', 'post', '', 1 );
	$note = filter_text_input( 'note', 'post', '', 1 );
	$title = strtolower( change_alias( $title ) );

	$modules_site = nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] );
	$modules_admin = nv_scandir( NV_ROOTDIR . "/" . NV_ADMINDIR . "/modules", $global_config['check_module'] );

	if( ! empty( $title ) and ! empty( $modfile ) and ! in_array( $title, $modules_site ) and ! in_array( $title, $modules_admin ) and preg_match( $global_config['check_module'], $title ) and preg_match( $global_config['check_module'], $modfile ) )
	{
		$mod_version = "";
		$author = "";
		$note = nv_nl2br( $note, '<br />' );
		$module_data = preg_replace( '/(\W+)/i', '_', $title );
	
		$ok = $db->sql_query( "INSERT INTO `" . $db_config['prefix'] . "_setup_modules` (`title`, `is_sysmod`, `virtual`, `module_file`, `module_data`, `mod_version`, `addtime`, `author`, `note`) VALUES (" . $db->dbescape( $title ) . ", '0', '0', " . $db->dbescape( $modfile ) . ", " . $db->dbescape( $module_data ) . ", " . $db->dbescape( $mod_version ) . ", '" . NV_CURRENTTIME . "', " . $db->dbescape( $author ) . ", " . $db->dbescape( $note ) . ")" );
	
		if( $ok )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['vmodule_add'] . ' "' . $module_data . '"', '', $admin_info['userid'] );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setup&setmodule=" . $title . "&checkss=" . md5( $title . session_id() . $global_config['sitekey'] ) );
		}
		else
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setup" );
		}
		
		die();
	}
}

$modules_exit = array_flip( nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] ) );
$modules_data = array();

$sql = "SELECT `title` FROM `" . $db_config['prefix'] . "_setup_modules` WHERE `virtual`='1' ORDER BY `addtime` ASC";
$result = $db->sql_query( $sql );

$page_title = $lang_module['vmodule_add'];

$xtpl = new XTemplate( "vmodule.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'CHECKSS', md5( session_id() . "addmodule" ) );

$xtpl->assign( 'TITLE', $title );
$xtpl->assign( 'NOTE', $note );

while( list( $modfile_i ) = $db->sql_fetchrow( $result ) )
{
	$modfile_i = $db->unfixdb( $modfile_i );

	if( in_array( $modfile_i, $modules_exit ) )
	{
		$xtpl->assign( 'MODFILE', array( 'key' => $modfile_i, 'selected' => ( $modfile_i == $modfile ) ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.modfile' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>