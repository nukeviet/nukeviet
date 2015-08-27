<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['upload_manager'];
$contents = '';

$path = ( defined( 'NV_IS_SPADMIN' ) ) ? '' : NV_UPLOADS_DIR;
$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'get', $path ) );
$currentpath = nv_check_path_upload( $nv_Request->get_string( 'currentpath', 'get', $path ) );
$type = $nv_Request->get_string( 'type', 'get' );
$popup = $nv_Request->get_int( 'popup', 'get', 0 );
$area = htmlspecialchars( trim( $nv_Request->get_string( 'area', 'get' ) ), ENT_QUOTES );
$alt = htmlspecialchars( trim( $nv_Request->get_string( 'alt', 'get' ) ), ENT_QUOTES );

if( empty( $currentpath ) ) $currentpath = NV_UPLOADS_DIR;
if( $type != 'image' and $type != 'flash' ) $type = 'file';

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );

if( $popup )
{
	$lang_module['browse_file'] = $lang_global['browse_file'];
	$sys_max_size = min( $global_config['nv_max_size'], nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ) );

	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'ADMIN_THEME', $global_config['module_theme'] );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_MAX_SIZE', nv_convertfromBytes( $sys_max_size ) );
	$xtpl->assign( 'NV_MAX_WIDTH', NV_MAX_WIDTH );
	$xtpl->assign( 'NV_MAX_HEIGHT', NV_MAX_HEIGHT );
	$xtpl->assign( 'NV_MIN_WIDTH', 10 );
	$xtpl->assign( 'NV_MIN_HEIGHT', 10 );
	$xtpl->assign( 'CURRENTPATH', $currentpath );
	$xtpl->assign( 'PATH', $path );
	$xtpl->assign( 'TYPE', $type );
	$xtpl->assign( 'AREA', $area );
	$xtpl->assign( 'ALT', $alt );
	$xtpl->assign( 'FUNNUM', $nv_Request->get_int( 'CKEditorFuncNum', 'get', 0 ) );

	$sfile = ( $type == 'file' ) ? ' selected="selected"' : '';
	$simage = ( $type == 'image' ) ? ' selected="selected"' : '';
	$sflash = ( $type == 'flash' ) ? ' selected="selected"' : '';

	$xtpl->assign( 'SFLASH', $sflash );
	$xtpl->assign( 'SIMAGE', $simage );
	$xtpl->assign( 'SFILE', $sfile );

	// Find logo config
    $upload_logo = $upload_logo_config = '';
	if( ! empty( $global_config['upload_logo'] ) and file_exists( NV_ROOTDIR . '/' . $global_config['upload_logo'] ) )
	{
		$upload_logo = NV_BASE_SITEURL . $global_config['upload_logo'];
        $logo_size = getimagesize( NV_ROOTDIR . '/' . $global_config['upload_logo'] );
        $upload_logo_config = $logo_size[0] . '|' . $logo_size[1] . '|' . $global_config['autologosize1'] . '|' . $global_config['autologosize2'] . '|' . $global_config['autologosize3'];
	}

	$xtpl->assign( 'UPLOAD_LOGO', $upload_logo );
	$xtpl->assign( 'UPLOAD_LOGO_CONFIG', $upload_logo_config );

	// Check upload allow file types
	if( $type == 'image' and in_array( 'images', $admin_info['allow_files_type'] ) )
	{
		$allow_files_type = array( 'images' );
	}
	elseif( $type == 'flash' and in_array( 'flash', $admin_info['allow_files_type'] ) )
	{
		$allow_files_type = array( 'flash' );
	}
	else
	{
		$allow_files_type = $admin_info['allow_files_type'];
	}

	$mimes = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/mime.ini', true );

	foreach( $mimes as $mime_type => $file_ext )
	{
		if( ! in_array( $mime_type, $global_config['forbid_mimes'] ) and in_array( $mime_type, $allow_files_type ) )
		{
			$file_ext = array_diff( array_keys( $file_ext ), $global_config['forbid_extensions'] );

			if( ! empty( $file_ext ) )
			{
				$xtpl->assign( 'MIMI_TYPE', ucfirst( $mime_type ) );
				$xtpl->assign( 'MIME_EXTS', implode( ',', $file_ext ) );

				$xtpl->parse( 'main.mime' );
			}
		}
	}

	$xtpl->assign( 'UPLOAD_ALT_REQUIRE', ! empty( $global_config['upload_alt_require'] ) ? 'true' : 'false' );
	$xtpl->assign( 'UPLOAD_AUTO_ALT', ! empty( $global_config['upload_auto_alt'] ) ? 'true' : 'false' );

	if( ! empty( $global_config['upload_alt_require'] ) )
	{
		$xtpl->parse( 'main.alt_remote' );
	}

	if( ! empty( $global_config['upload_auto_alt'] ) )
	{
		$xtpl->parse( 'main.auto_alt' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	$head_site = 0;
}
else
{
	$xtpl->assign( 'IFRAME_SRC', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;popup=1' );
	$xtpl->parse( 'uploadPage' );
	$contents = $xtpl->text( 'uploadPage' );
	$head_site = 1;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents, $head_site );
include NV_ROOTDIR . '/includes/footer.php';