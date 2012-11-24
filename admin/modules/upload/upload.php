<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 24/1/2011, 1:33
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post', NV_UPLOADS_DIR ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

if( ! isset( $check_allow_upload_dir['upload_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

if( ! isset( $_FILES, $_FILES['fileupload'], $_FILES['fileupload']['tmp_name'] ) and ! $nv_Request->isset_request( 'fileurl', 'post' ) ) die( "ERROR_" . $lang_module['uploadError1'] );

if( ! isset( $_FILES ) and ! nv_is_url( $nv_Request->get_string( 'fileurl', 'post' ) ) ) die( "ERROR_" . $lang_module['uploadError2'] );

require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
$upload = new upload( $admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );

if( isset( $_FILES['fileupload']['tmp_name'] ) and is_uploaded_file( $_FILES['fileupload']['tmp_name'] ) )
{
	$upload_info = $upload->save_file( $_FILES['fileupload'], NV_ROOTDIR . '/' . $path, false );
}
else
{
	$urlfile = trim( $nv_Request->get_string( 'fileurl', 'post' ) );
	$upload_info = $upload->save_urlfile( $urlfile, NV_ROOTDIR . '/' . $path, false );
}

if( ! empty( $upload_info['error'] ) )
{
	die( "ERROR_" . $upload_info['error'] );
}

if( $upload_info['is_img'] )
{
	$autologomod = explode( ',', $global_config['autologomod'] );
	$dir = str_replace( "\\", "/", $path );
	$dir = rtrim( $dir, "/" );
	$arr_dir = explode( "/", $dir );
	
	if( $global_config['autologomod'] == 'all' or ( $arr_dir[0] == NV_UPLOADS_DIR and isset( $arr_dir[1] ) and in_array( $arr_dir[1], $autologomod ) ) )
	{
		$upload_logo = '';
		
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
		
		if( ! empty( $upload_logo ) )
		{
			$logo_size = getimagesize( NV_ROOTDIR . '/' . $upload_logo );
			$file_size = $upload_info['img_info'];

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

			$config_logo = array();
			$config_logo['x'] = $file_size[0] - $w - 5;
			$config_logo['y'] = $file_size[1] - $h - 5;
			$config_logo['w'] = $w;
			$config_logo['h'] = $h;

			require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
			$createImage = new image( NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename'], NV_MAX_WIDTH, NV_MAX_HEIGHT );
			$createImage->addlogo( NV_ROOTDIR . '/' . $upload_logo, '', '', $config_logo );
			$createImage->save( NV_ROOTDIR . '/' . $path, $upload_info['basename'] );
		}
	}
}

nv_filesList( $path, false, $upload_info['basename'] );
nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_file'], $path . "/" . $upload_info['basename'], $admin_info['userid'] );

echo $upload_info['basename'];
exit;

?>