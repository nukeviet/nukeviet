<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @License GNU/GPL version 2 or any later version
 * @Createdate 16-03-2015 12:55
 */

$path = nv_check_path_upload( NV_UPLOADS_DIR . '/' . $mod_name );
$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

$data = $nv_Request->get_string( 'data' , 'post', '' );

if( isset( $check_allow_upload_dir['upload_file'] ) and in_array( 'images', $admin_info['allow_files_type'] ) and preg_match_all( '/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'\s>]*)/i', $data, $matches ) )
{
	$imageMatch = array_unique( $matches[1] );

	$mod_name = $nv_Request->get_title( 'module_name' , 'post', '' );
    $pathsave = $nv_Request->get_title( 'pathsave' , 'post', '' );
	$upload_real_dir_page = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $mod_name;
    if( !empty( $pathsave ))
	{
		if( ! preg_match( '/^[a-z0-9\-\_]+$/i', $module_name ) )
		{
			$pathsave = change_alias( $pathsave );
		}
       $pathsave = $mod_name . '/' . $pathsave;
		$e = explode( '/', $pathsave );
		if( ! empty( $e ) )
		{
			$cp = '';
			foreach( $e as $p )
			{
				if( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $cp . $p ) )
				{
					$mk = nv_mkdir( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $cp, $p );
					if( $mk[0] > 0 )
					{
						$upload_real_dir_page = $mk[2];
					}
				}
				elseif( ! empty( $p ) )
				{
					$upload_real_dir_page = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $cp . $p;
				}
				$cp .= $p . '/';
			}
		}
	}

    $currentpath = str_replace( NV_ROOTDIR . '/', '', $upload_real_dir_page );

	foreach( $imageMatch as $imageSrc )
	{
		if( nv_check_url( $imageSrc ) )
		{
			$_image = new image( $imageSrc );
			if( $_image->fileinfo['width'] > 50 )
			{
				if( $_image->fileinfo['width'] > NV_MAX_WIDTH )
				{
					$_image->resizeXY( NV_MAX_WIDTH, NV_MAX_HEIGHT );
				}

				$basename = explode( ".", basename( $imageSrc ) );
				array_pop( $basename );
				$basename = implode( "-", $basename );
				$basename = preg_replace( '/^\W+|\W+$/', '', $basename );
				$basename = preg_replace( '/[ ]+/', '_', $basename );
				$basename = strtolower( preg_replace( '/\W-/', '', $basename ) );
				$basename .= '.' . $_image->fileinfo['ext'];

				$thumb_basename = $basename;
				$i = 1;
				while( file_exists( NV_ROOTDIR . '/' . $currentpath . '/' . $thumb_basename ) )
				{
					$thumb_basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename );
					$i++;
				}

				$_image->save( NV_ROOTDIR . '/' . $currentpath, $thumb_basename );
				$image_path = $_image->create_Image_info['src'];
				if( ! empty( $image_path ) and file_exists( $image_path ) )
				{
					$new_imageSrc = str_replace( NV_ROOTDIR . '/' . $currentpath . '/', NV_BASE_SITEURL . $currentpath . '/', $image_path );
					$data = str_replace( "src=\"" . $imageSrc . "\"", "src=\"" . $new_imageSrc . "\"", $data );
				}
			}
		}
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $data;
include NV_ROOTDIR . '/includes/footer.php';