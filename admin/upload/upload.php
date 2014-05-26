<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24/1/2011, 1:33
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post,get', NV_UPLOADS_DIR ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

$error = '';
if( ! isset( $check_allow_upload_dir['upload_file'] ) )
{
	$error = $lang_module['notlevel'];
}
elseif( ! isset( $_FILES, $_FILES['upload'], $_FILES['upload']['tmp_name'] ) and ! $nv_Request->isset_request( 'fileurl', 'post' ) )
{
	$error = $lang_module['uploadError1'];
}
elseif( ! isset( $_FILES ) and ! nv_is_url( $nv_Request->get_string( 'fileurl', 'post,get' ) ) )
{
	$error = $lang_module['uploadError2'];
}
else
{
	$type = $nv_Request->get_string( 'type', 'post,get' );

	if( $type == 'image' and in_array( 'images', $admin_info['allow_files_type'] ) )
	{
		$allow_files_type = array( 'images' );
	}
	elseif( $type == 'flash' and in_array( 'flash', $admin_info['allow_files_type'] ) )
	{
		$allow_files_type = array( 'flash' );
	}
	elseif( empty( $type ) )
	{
		$allow_files_type = $admin_info['allow_files_type'];
	}
	else
	{
		$allow_files_type = array();
	}

	require_once NV_ROOTDIR . '/includes/class/upload.class.php';

	$upload = new upload( $allow_files_type, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );

	if( isset( $_FILES['upload']['tmp_name'] ) and is_uploaded_file( $_FILES['upload']['tmp_name'] ) )
	{
		$upload_info = $upload->save_file( $_FILES['upload'], NV_ROOTDIR . '/' . $path, false, $global_config['nv_auto_resize'] );
	}
	else
	{
		$urlfile = trim( $nv_Request->get_string( 'fileurl', 'post' ) );
		$upload_info = $upload->save_urlfile( $urlfile, NV_ROOTDIR . '/' . $path, false, $global_config['nv_auto_resize'] );
	}

	if( ! empty( $upload_info['error'] ) )
	{
		$error = $upload_info['error'];
	}
	elseif( preg_match( '#image\/[x\-]*([a-z]+)#', $upload_info['mime'] ) )
	{
		if( $global_config['nv_auto_resize'] and ( $upload_info['img_info'][0] > NV_MAX_WIDTH or $upload_info['img_info'][0] > NV_MAX_HEIGHT ) )
		{
			require_once NV_ROOTDIR . '/includes/class/image.class.php';
			$createImage = new image( NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename'], $upload_info['img_info'][0], $upload_info['img_info'][1] );
			$createImage->resizeXY( NV_MAX_WIDTH, NV_MAX_HEIGHT );
			$createImage->save( NV_ROOTDIR . '/' . $path, $upload_info['basename'], 90 );
			$createImage->close();
			$info = $createImage->create_Image_info;
			$upload_info['img_info'][0] = $info['width'];
			$upload_info['img_info'][1] = $info['height'];
			$upload_info['size'] = filesize( NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename'] );
		}

		if( $upload_info['size'] > NV_UPLOAD_MAX_FILESIZE )
		{
			nv_deletefile( NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename'] );
			$error = sprintf( $lang_global['error_upload_max_user_size'], NV_UPLOAD_MAX_FILESIZE );
		}
		else
		{
			if( $upload_info['img_info'][0] > NV_MAX_WIDTH or $upload_info['img_info'][0] > NV_MAX_HEIGHT )
			{
				nv_deletefile( NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename'] );
				if( $upload_info['img_info'][0] > NV_MAX_WIDTH )
				{
					$error = sprintf( $lang_global['error_upload_image_width'], NV_MAX_WIDTH );
				}
				else
				{
					$error = sprintf( $lang_global['error_upload_image_height'], NV_MAX_HEIGHT );
				}
			}
			else
			{
				$autologomod = explode( ',', $global_config['autologomod'] );
				$dir = str_replace( "\\", '/', $path );
				$dir = rtrim( $dir, '/' );
				$arr_dir = explode( '/', $dir );

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

						require_once NV_ROOTDIR . '/includes/class/image.class.php';
						$createImage = new image( NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename'], NV_MAX_WIDTH, NV_MAX_HEIGHT );
						$createImage->addlogo( NV_ROOTDIR . '/' . $upload_logo, '', '', $config_logo );
						$createImage->save( NV_ROOTDIR . '/' . $path, $upload_info['basename'] );
					}
				}
			}
		}
	}
}

$editor = $nv_Request->get_string( 'editor', 'post,get' );
$CKEditorFuncNum = $nv_Request->get_string( 'CKEditorFuncNum', 'post,get', 0 );

if( empty( $error ) )
{
	if( isset( $array_dirname[$path] ) )
	{
		$did = $array_dirname[$path];
		$info = nv_getFileInfo( $path, $upload_info['basename'] );
		$info['userid'] = $admin_info['userid'];

		$newalt = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1', $upload_info['basename'] );
		$newalt = str_replace( '-', ' ', change_alias( $newalt ) );

		$sth = $db->prepare( "INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_file
		(name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title, alt) VALUES
		('" . $info['name'] . "', '" . $info['ext'] . "', '" . $info['type'] . "', " . $info['filesize'] . ", '" . $info['src'] . "', " . $info['srcwidth'] . ", " . $info['srcheight'] . ", '" . $info['size'] . "', " . $info['userid'] . ", " . $info['mtime'] . ", " . $did . ", '" . $upload_info['basename'] . "', :newalt)" );

		$sth->bindParam( ':newalt', $newalt, PDO::PARAM_STR );
		$sth->execute();
	}
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_file'], $path . '/' . $upload_info['basename'], $admin_info['userid'] );
	if( $editor == 'ckeditor' )
	{
		echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ", '" . NV_BASE_SITEURL . $path . "/" . $upload_info['basename'] . "', '');</script>";
	}
	else
	{
		echo $upload_info['basename'];
	}
}
else
{
	if( $editor == 'ckeditor' )
	{
		echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ", '', '" . $error . "');</script>";
	}
	else
	{
		echo 'ERROR_' . $error;
	}
}

exit();