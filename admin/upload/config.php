<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$upload_logo = $nv_Request->get_title( 'upload_logo', 'post' );

	if( ! nv_is_url( $upload_logo ) and file_exists( NV_DOCUMENT_ROOT . $upload_logo ) )
	{
		$lu = strlen( NV_BASE_SITEURL );
		$upload_logo = substr( $upload_logo, $lu );
	}
	else
	{
		$upload_logo = 'images/logo.png';
	}

	$autologosize1 = $nv_Request->get_int( 'autologosize1', 'post', 50 );
	$autologosize2 = $nv_Request->get_int( 'autologosize2', 'post', 40 );
	$autologosize3 = $nv_Request->get_int( 'autologosize3', 'post', 30 );

	$autologomod = $nv_Request->get_array( 'autologomod', 'post' );

	if( ( in_array( 'all', $autologomod ) ) )
	{
		$autologomod = 'all';
	}
	else
	{
		$autologomod = array_intersect( $autologomod, array_keys( $site_mods ) );
		$autologomod = implode( ',', $autologomod );
	}

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'upload_logo'" );
	$sth->bindParam( ':config_value', $upload_logo, PDO::PARAM_STR );
	$sth->execute();

	$db->query( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologosize1 . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologosize1'" );
	$db->query( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologosize2 . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologosize2'" );
	$db->query( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologosize3 . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologosize3'" );
	$db->query( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologomod . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologomod'" );

	nv_delete_all_cache();

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	die();
}

$page_title = $lang_module['configlogo'];

if( ! nv_is_url( $global_config['upload_logo'] ) and file_exists( NV_ROOTDIR . '/' . $global_config['upload_logo'] ) )
{
	$upload_logo = NV_BASE_SITEURL . $global_config['upload_logo'];
}
else
{
	$upload_logo = $global_config['site_logo'];
}

$array_autologosize = array(
	'upload_logo' => $upload_logo,
	'autologosize1' => $global_config['autologosize1'],
	'autologosize2' => $global_config['autologosize2'],
	'autologosize3' => $global_config['autologosize3']
);

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'ADMIN_THEME', $global_config['module_theme'] );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'AUTOLOGOSIZE', $array_autologosize );

$a = 0;
$xtpl->assign( 'CLASS', '' );

if( $global_config['autologomod'] == 'all' )
{
	$autologomod = array();
}
else
{
	$autologomod = explode( ',', $global_config['autologomod'] );
}

foreach( $site_mods as $mod => $value )
{
	if( is_dir( NV_UPLOADS_REAL_DIR . '/' . $mod ) )
	{
		++$a;
		$xtpl->assign( 'MOD_VALUE', $mod );
		$xtpl->assign( 'LEV_CHECKED', ( in_array( $mod, $autologomod ) ) ? 'checked="checked"' : '' );
		$xtpl->assign( 'CUSTOM_TITLE', $value['custom_title'] );
		$xtpl->parse( 'main.loop1.loop2' );

		if( $a % 3 == 0 )
		{
			$xtpl->parse( 'main.loop1' );
		}
	}
}

$a++;
$xtpl->assign( 'MOD_VALUE', 'all' );
$xtpl->assign( 'LEV_CHECKED', ( $global_config['autologomod'] == 'all' ) ? 'checked="checked"' : '' );
$xtpl->assign( 'CUSTOM_TITLE', '<strong>' . $lang_module['autologomodall'] . '</strong>' );

$xtpl->parse( 'main.loop1.loop2' );
$xtpl->parse( 'main.loop1' );
$xtpl->parse( 'main' );

$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';