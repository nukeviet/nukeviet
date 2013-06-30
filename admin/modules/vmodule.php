<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$array_site_cat_module = array();
if( $global_config['idsite'] )
{
	$result = $db->sql_query( "SELECT module FROM `" . $db_config['dbsystem'] . "`.`" . $db_config['prefix'] . "_site_cat` AS t1 INNER JOIN `" . $db_config['dbsystem'] . "`.`" . $db_config['prefix'] . "_site` AS t2 ON t1.`cid`=t2.`cid` WHERE t2.`idsite`=" . $global_config['idsite'] );
	$row = $db->sql_fetch_assoc( $result );
	if( ! empty( $row['module'] ) )
	{
		$array_site_cat_module = explode( ',', $row['module'] );
	}
}

$title = $note = $modfile = $error = '';
$modules_site = nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] );
if( $nv_Request->get_title( 'checkss', 'post' ) == md5( session_id() . "addmodule" ) )
{
	$title = $nv_Request->get_title( 'title', 'post', '', 1 );
	$modfile = $nv_Request->get_title( 'module_file', 'post', '', 1 );
	$note = $nv_Request->get_title( 'note', 'post', '', 1 );
	$title = strtolower( change_alias( $title ) );

	$modules_admin = nv_scandir( NV_ROOTDIR . "/" . NV_ADMINDIR, $global_config['check_module'] );
	$error = $lang_module['vmodule_exit'];

	if( ! empty( $title ) and ! empty( $modfile ) and ! in_array( $title, $modules_site ) and ! in_array( $title, $modules_admin ) and preg_match( $global_config['check_module'], $title ) and preg_match( $global_config['check_module'], $modfile ) )
	{
		$mod_version = '';
		$author = '';
		$note = nv_nl2br( $note, '<br />' );
		$module_data = preg_replace( '/(\W+)/i', '_', $title );
		if( empty( $array_site_cat_module ) OR in_array( $modfile_i, $array_site_cat_module ) )
		{
			$ok = $db->sql_query( "INSERT INTO `" . $db_config['prefix'] . "_setup_modules` (`title`, `is_sysmod`, `virtual`, `module_file`, `module_data`, `mod_version`, `addtime`, `author`, `note`) VALUES (" . $db->dbescape( $title ) . ", '0', '0', " . $db->dbescape( $modfile ) . ", " . $db->dbescape( $module_data ) . ", " . $db->dbescape( $mod_version ) . ", '" . NV_CURRENTTIME . "', " . $db->dbescape( $author ) . ", " . $db->dbescape( $note ) . ")" );
			if( $ok )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['vmodule_add'] . ' "' . $module_data . '"', '', $admin_info['userid'] );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setup&setmodule=" . $title . "&checkss=" . md5( $title . session_id() . $global_config['sitekey'] ) );
				die();
			}
		}
	}
}

$sql = "SELECT `title` FROM `" . $db_config['prefix'] . "_setup_modules` WHERE `virtual`='1' ORDER BY `addtime` ASC";
$result = $db->sql_query( $sql );

$page_title = $lang_module['vmodule_add'];

$xtpl = new XTemplate( "vmodule.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );

if( $error )
{
	$lang_module['vmodule_blockquote'] = $lang_module['vmodule_exit'];
	$xtpl->assign( 'CLASSERROR', 'error' );
}
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

	if( in_array( $modfile_i, $modules_site ) )
	{
		if( ! empty( $array_site_cat_module ) AND ! in_array( $modfile_i, $array_site_cat_module ) )
		{
			continue;
		}
		$xtpl->assign( 'MODFILE', array( 'key' => $modfile_i, 'selected' => ( $modfile_i == $modfile ) ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.modfile' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>