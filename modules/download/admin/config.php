<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['download_config'];

$array_exts = get_allow_exts();
$groups_list = nv_groups_list();
$readme_file = NV_ROOTDIR . '/' . NV_DATADIR . '/README.txt';

$array_config = array();

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config['is_addfile'] = $nv_Request->get_int( 'is_addfile', 'post', 0 );
	$array_config['is_upload'] = $nv_Request->get_int( 'is_upload', 'post', 0 );
	$array_config['maxfilesize'] = $nv_Request->get_float( 'maxfilesize', 'post', 0 );
	$array_config['upload_filetype'] = $nv_Request->get_typed_array( 'upload_filetype', 'post', 'string' );
	$array_config['upload_dir'] = $nv_Request->get_title( 'upload_dir', 'post', '' );
	$array_config['temp_dir'] = $nv_Request->get_title( 'temp_dir', 'post', '' );
	$array_config['is_zip'] = $nv_Request->get_int( 'is_zip', 'post', 0 );
	$array_config['readme'] = $nv_Request->get_textarea( 'readme', '' );
	$array_config['readme'] = strip_tags( $array_config['readme'] );
	$array_config['is_resume'] = $nv_Request->get_int( 'is_resume', 'post', 0 );
	$array_config['max_speed'] = $nv_Request->get_int( 'max_speed', 'post', 0 );

	$_groups_post = $nv_Request->get_array( 'groups_upload', 'post', array() );
	$array_config['groups_addfile'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	$_groups_post = $nv_Request->get_array( 'groups_upload', 'post', array() );
	$array_config['groups_upload'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	if( $array_config['maxfilesize'] <= 0 or $array_config['maxfilesize'] > NV_UPLOAD_MAX_FILESIZE )
	{
		$array_config['maxfilesize'] = NV_UPLOAD_MAX_FILESIZE;
	}
    else
    {
        $array_config['maxfilesize'] = intval( $array_config['maxfilesize'] * 1048576 );
    }

	$array_config['upload_filetype'] = ( ! empty( $array_config['upload_filetype'] ) ) ? implode( ',', $array_config['upload_filetype'] ) : '';

	if( ! preg_match( '/^[a-zA-Z][a-zA-Z0-9\_]*$/', $array_config['upload_dir'] ) )
	{
		$array_config['upload_dir'] = 'files';
	}
	else
	{
		if( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_config['upload_dir'] ) )
		{
			$mkdir = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name, $array_config['upload_dir'] );
			if( $mkdir[0] == 0 )
			{
				$array_config['upload_dir'] = 'files';
			}
			else
			{
				$db->query( "INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $array_config['upload_dir'] . "', 0)" );
			}
		}
	}

	if( ! preg_match( '/^[a-zA-Z][a-zA-Z0-9\_]*$/', $array_config['temp_dir'] ) )
	{
		$array_config['temp_dir'] = 'temp';
	}
	else
	{
		if( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_config['temp_dir'] ) )
		{
			$mkdir = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name, $array_config['temp_dir'] );
			if( $mkdir[0] == 0 )
			{
				$array_config['temp_dir'] = 'temp';
			}
			else
			{
				$db->query( "INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $array_config['upload_dir'] . "', 0)" );
			}
		}
	}

	$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_config SET config_value = :config_value WHERE config_name = :config_name');
	foreach( $array_config as $config_name => $config_value )
	{
		if( $config_name != 'readme' )
		{
			$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR );
			$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
			$sth->execute();
		}
	}

	if( ! empty( $array_config['readme'] ) )
	{
		file_put_contents( $readme_file, $array_config['readme'] );
	}
	else
	{
		if( file_exists( $readme_file ) )
		{
			@nv_deletefile( $readme_file );
		}
	}

	nv_del_moduleCache( $module_name );

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	die();
}

$array_config['is_addfile'] = 0;
$array_config['groups_addfile'] = '';
$array_config['is_upload'] = 0;
$array_config['groups_upload'] = '';
$array_config['maxfilesize'] = NV_UPLOAD_MAX_FILESIZE;
$array_config['upload_filetype'] = '';
$array_config['upload_dir'] = 'files';
$array_config['temp_dir'] = 'temp';
$array_config['is_zip'] = 0;
$array_config['readme'] = '';
$array_config['is_resume'] = 0;
$array_config['max_speed'] = 0;

if( file_exists( $readme_file ) )
{
	$array_config['readme'] = file_get_contents( $readme_file );
	$array_config['readme'] = nv_htmlspecialchars( $array_config['readme'] );
}

$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$result = $db->query( $sql );
while( list( $c_config_name, $c_config_value ) = $result->fetch( 3 ) )
{
	$array_config[$c_config_name] = $c_config_value;
}

$array_config['is_addfile'] = ! empty( $array_config['is_addfile'] ) ? ' checked="checked"' : '';
$array_config['is_upload'] = ! empty( $array_config['is_upload'] ) ? ' checked="checked"' : '';
$array_config['is_zip'] = ! empty( $array_config['is_zip'] ) ? ' checked="checked"' : '';
$array_config['is_resume'] = ! empty( $array_config['is_resume'] ) ? ' checked="checked"' : '';

$upload_filetype = ! empty( $array_config['upload_filetype'] ) ? explode( ',', $array_config['upload_filetype'] ) : array();
$array_config['upload_filetype'] = array();
if( ! empty( $array_exts ) )
{
	foreach( $array_exts as $ext => $mime )
	{
		$array_config['upload_filetype'][$ext] = array(
			'ext' => $ext,
			'title' => $ext . ' (mime: ' . $mime . ')',
			'checked' => ( in_array( $ext, $upload_filetype ) ) ? ' checked="checked"' : ''
		);
	}
}

$groups_addfile = explode( ',', $array_config['groups_addfile'] );
$array_config['groups_addfile'] = array();
if( ! empty( $groups_list ) )
{
	foreach( $groups_list as $key => $title )
	{
		$array_config['groups_addfile'][$key] = array(
			'key' => $key,
			'title' => $title,
			'checked' => in_array( $key, $groups_addfile ) ? ' checked="checked"' : ''
		);
	}
}

$groups_upload = explode( ',', $array_config['groups_upload'] );
$array_config['groups_upload'] = array();
if( ! empty( $groups_list ) )
{
	foreach( $groups_list as $key => $title )
	{
		$array_config['groups_upload'][$key] = array(
			'key' => $key,
			'title' => $title,
			'checked' => in_array( $key, $groups_upload ) ? ' checked="checked"' : ''
		);
	}
}
$array_config['maxfilesize'] = number_format( $array_config['maxfilesize']/1048576, 2);
$xtpl = new XTemplate( 'config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config );
$xtpl->assign( 'NV_UPLOAD_MAX_FILESIZE', nv_convertfromBytes( NV_UPLOAD_MAX_FILESIZE ) );

foreach( $array_config['upload_filetype'] as $filetype )
{
	$xtpl->assign( 'UPLOAD_FILETYPE', $filetype );
	$xtpl->parse( 'main.upload_filetype' );
}

foreach( $array_config['groups_addfile'] as $group )
{
	$xtpl->assign( 'GROUPS_ADDFILE', $group );
	$xtpl->parse( 'main.groups_addfile' );
}

foreach( $array_config['groups_upload'] as $group )
{
	$xtpl->assign( 'GROUPS_UPLOAD', $group );
	$xtpl->parse( 'main.groups_upload' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';