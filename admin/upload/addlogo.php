<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post,get' ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

if( ! isset( $check_allow_upload_dir['delete_file'] ) )
{
	die( 'ERROR#' . $lang_module['notlevel'] );
}

$file = htmlspecialchars( trim( $nv_Request->get_string( 'file', 'post,get' ) ), ENT_QUOTES );
$file = basename( $file );

if( empty( $file ) or ! is_file( NV_ROOTDIR . '/' . $path . '/' . $file ) )
{
	die( 'ERROR#' . $lang_module['errorNotSelectFile'] . NV_ROOTDIR . '/' . $path . '/' . $file );
}

if( $nv_Request->isset_request( 'path', 'post' ) and $nv_Request->isset_request( 'x', 'post' ) and $nv_Request->isset_request( 'y', 'post' ) )
{
	if( file_exists( NV_ROOTDIR . '/' . $global_config['upload_logo'] ) )
	{
		$upload_logo = NV_ROOTDIR . '/' . $global_config['upload_logo'];
	}
	elseif( file_exists( NV_ROOTDIR . '/' . $global_config['site_logo'] ) )
	{
		$upload_logo = NV_ROOTDIR . '/' . $global_config['site_logo'];
	}
	elseif( file_exists( NV_ROOTDIR . '/images/logo.png' ) )
	{
		$upload_logo = NV_ROOTDIR . '/images/logo.png';
	}
	else
	{
		die( 'ERROR#' . $lang_module['notlogo'] );
	}

	$config_logo = array();
	$config_logo['x'] = $nv_Request->get_int( 'x', 'post', 0 );
	$config_logo['y'] = $nv_Request->get_int( 'y', 'post', 0 );
	$config_logo['w'] = $nv_Request->get_int( 'w', 'post', 0 );
	$config_logo['h'] = $nv_Request->get_int( 'h', 'post', 0 );

	if( $config_logo['w'] > 0 and $config_logo['h'] > 0 )
	{
		require_once NV_ROOTDIR . '/includes/class/image.class.php';
		$createImage = new image( NV_ROOTDIR . '/' . $path . '/' . $file, NV_MAX_WIDTH, NV_MAX_HEIGHT );
		$createImage->addlogo( $upload_logo, '', '', $config_logo );
		$createImage->save( NV_ROOTDIR . '/' . $path, $file );
		$createImage->close();

		if( isset( $array_dirname[$path] ) )
		{
			if( preg_match( "/^" . nv_preg_quote( NV_UPLOADS_DIR ) . "\/([a-z0-9\-\_\/]+)$/i", $path, $m ) )
			{
				@nv_deletefile( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1] . '/' . $file );
			}

			$info = nv_getFileInfo( $path, $file );

			$did = $array_dirname[$path];
			$db->query( "UPDATE " . NV_UPLOAD_GLOBALTABLE . "_file SET filesize=" . $info['filesize'] . ", src='" . $info['src'] . "', srcwidth=" . $info['srcwidth'] . ", srcheight=" . $info['srcheight'] . ", sizes='" . $info['size'] . "', userid=" . $admin_info['userid'] . ", mtime=" . $info['mtime'] . " WHERE did = " . $did . " AND title = '" . $file . "'" );
		}

		die( 'OK#' . basename( $file ) );
	}
	else
	{
		die( 'ERROR#' . $lang_module['notlevel'] );
	}
}

if( file_exists( NV_ROOTDIR . '/' . $global_config['upload_logo'] ) )
{
	$upload_logo = $global_config['upload_logo'];
}
elseif( file_exists( NV_ROOTDIR . '/' . $global_config['site_logo'] ) )
{
	$upload_logo = $global_config['site_logo'];
}
elseif( file_exists( NV_ROOTDIR . '/images/logo.png' ) )
{
	$upload_logo = 'images/logo.png';
}
else
{
	trigger_error( $lang_module['notlogo'], 256 );
}

$logo_size = getimagesize( NV_ROOTDIR . '/' . $upload_logo );
$file_size = getimagesize( NV_ROOTDIR . '/' . $path . '/' . $file );

if( $file_size[0] <= 150 )
{
	$w = ceil( $logo_size[0] * $global_config['autologosize1'] / 100 );
}
elseif( $file_size[0] < 350 )
{
	$w = ceil( $logo_size[0] * $global_config['autologosize2'] / 100 );
}
else
{
	if( ceil( $file_size[0] * $global_config['autologosize3'] / 100 ) > $logo_size[0] )
	{
		$w = $logo_size[0];
	}
	else
	{
		$w = ceil( $file_size[0] * $global_config['autologosize3'] / 100 );
	}
}

$h = ceil( $w * $logo_size[1] / $logo_size[0] );
$x = $file_size[0] - $w - 5;
$y = $file_size[1] - $h - 5;

$logosite = array(
	'p' => NV_BASE_SITEURL . $upload_logo,
	'x' => $x,
	'y' => $y,
	'w' => $w,
	'h' => $h
);

$xtpl = new XTemplate( 'addlogo.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_OP_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
$xtpl->assign( 'IMG_PATH', $path );
$xtpl->assign( 'IMG_FILE', $file );
$xtpl->assign( 'IMG_MTIME', filemtime( NV_ROOTDIR . '/' . $path . '/' . $file ) );
$xtpl->assign( 'LOGOSITE', $logosite );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';