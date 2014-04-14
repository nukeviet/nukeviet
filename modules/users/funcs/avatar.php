<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

$page_title = $lang_module['avata_pagetitle'];

$array = array();
$array['success'] = false;
$array['error'] = '';

if( isset( $_FILES['image_file'] ) and is_uploaded_file( $_FILES['image_file']['tmp_name'] ) )
{
	// Get post data
	$array['x1'] = $nv_Request->get_int( 'x1', 'post', 0 );
	$array['y1'] = $nv_Request->get_int( 'y1', 'post', 0 );
	$array['x2'] = $nv_Request->get_int( 'x2', 'post', 0 );
	$array['y2'] = $nv_Request->get_int( 'y2', 'post', 0 );
	$array['w'] = $nv_Request->get_int( 'w', 'post', 0 );
	$array['h'] = $nv_Request->get_int( 'h', 'post', 0 );
	
	// Caculate crop size
	$array['avatar_width'] = intval( $array['x2'] - $array['x1'] );
	$array['avatar_height'] = intval( $array['y2'] - $array['y1'] );
	
	if( sizeof( array_filter( array( $array['x1'], $array['y1'], $array['x2'], $array['y2'], $array['w'], $array['h'] ) ) ) < 4 or $array['avatar_width'] < $global_config['avatar_width'] or $array['avatar_height'] < $global_config['avatar_height'] )
	{
		$array['error'] = $lang_module['avata_error_data'];
	}
	else
	{
		require_once ( NV_ROOTDIR . '/includes/class/upload.class.php' );
		
		$upload = new upload( array( 'images' ), $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
		
		// Storage in temp dir
		$upload_info = $upload->save_file( $_FILES['image_file'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false );
	
		// Delete upload tmp
		@unlink( $_FILES['image_file']['tmp_name'] );
	
		if( empty( $upload_info['error'] ) )
		{
			$basename = $upload_info['basename'];
			$basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . nv_genpass(8) . "_" . $user_info['userid'] . '\2', $basename );
			
			require_once ( NV_ROOTDIR . '/includes/class/image.class.php' );
			$image = new image( $upload_info['name'], NV_MAX_WIDTH, NV_MAX_HEIGHT );
			
			// Resize image, crop image
			$image->resizeXY( $array['w'], $array['h'] );
			$image->cropFromLeft( $array['x1'], $array['y1'], $array['avatar_width'], $array['avatar_height'] );
			$image->resizeXY( $global_config['avatar_width'], $global_config['avatar_height'] );

			// Save new image
			$image->save( NV_ROOTDIR . '/' . NV_TEMP_DIR, $basename );
			$image->close();
			
			if( file_exists( $image->create_Image_info['src'] ) )
			{
				$array['success'] = true;
				$array['filename'] = str_replace( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/', '', $image->create_Image_info['src'] );
			}
			else
			{
				$array['error'] = $lang_module['avata_error_save'];
			}
			@nv_deletefile( $upload_info['name'] );
		}
		else
		{
			$array['error'] = $upload_info['error'];
		}
	}
}

$contents = nv_avatar( $array );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents, false );
include NV_ROOTDIR . '/includes/footer.php';