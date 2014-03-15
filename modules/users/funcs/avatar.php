<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

$array = array();
$array['success'] = false;

if( isset( $_FILES['image_file'] ) and is_uploaded_file( $_FILES['image_file']['tmp_name'] ) )
{
	$max_width = $nv_Request->get_int( 'avatar_width', 'post', $global_config['avatar_width'] );
	$max_height = $nv_Request->get_int( 'avatar_height', 'post', $global_config['avatar_height'] );
	$avatar_width = $nv_Request->get_int( 'w', 'post', $global_config['avatar_width'] );
	$avatar_height = $nv_Request->get_int( 'h', 'post', $global_config['avatar_height'] );
	$x1 = $nv_Request->get_int( 'x1', 'post', 0);
	$y1 = $nv_Request->get_int( 'y1', 'post', 0);
	$old_images = $nv_Request->get_title( 'old_images', 'post', '');

	if( ! empty( $old_images ) and is_file( $old_images ) )
	{
		@nv_deletefile( $old_images );
	}
	
	require_once NV_ROOTDIR . '/includes/class/upload.class.php' ;
	$upload = new upload( array( 'images' ), $global_config['forbid_extensions'], $global_config['forbid_mimes'] );
	$upload_info = $upload->save_file( $_FILES['image_file'], NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_name, false );

	@unlink( $_FILES['image_file']['tmp_name'] );

	if( empty( $upload_info['error'] ) )
	{
		$image = $upload_info['name'];
		$basename = $upload_info['basename'];
		$basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . nv_genpass(8) . "_" . $user_info['userid'] . '\2', $basename );

		require_once NV_ROOTDIR . '/includes/class/image.class.php' ;

		$_image = new image( $image, $avatar_width, $avatar_height );
		$_image->cropFromLeft( $x1, $y1, $avatar_width, $avatar_height );
		$_image->save( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_name, $basename );
		$file_name = NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_name . '/' . $basename;
		if( file_exists( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_name . '/' . $basename ) )
		{
			//resize
			$_image = new image( $file_name, $max_width, $max_height );
			$_image->resizeXY( $max_width, $max_height );
			$_image->save( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_name, $basename );
			
			//@chmod($file_name, 0644);
			$file_name = str_replace( NV_ROOTDIR . '/', '', $file_name );
			@nv_deletefile( $upload_info['name'] );
		}
	}

	$array['success'] = true;
	$array['filename'] = $file_name;
}

$contents = nv_avatar( $array );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';

?>