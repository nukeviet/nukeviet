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
	$avatar_width = $nv_Request->get_int( 'avatar_width', 'post', 120);
	$avatar_height = $nv_Request->get_int( 'avatar_height', 'post', 120);
	$x1 = $nv_Request->get_int( 'x1', 'post', 0);
	$y1 = $nv_Request->get_int( 'y1', 'post', 0);
	
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