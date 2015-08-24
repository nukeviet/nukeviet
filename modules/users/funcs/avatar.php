<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( ! defined( "NV_IS_ADMIN" ) )
{
	if( ! defined( 'NV_IS_USER' ) or ! $global_config['allowuserlogin'] )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
		die();
	}

	if( ( int )$user_info['safemode'] > 0 )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo', true ) );
		die();
	}
}

function updateAvatar( $file )
{
	global $db, $user_info, $module_upload;

	$tmp_photo = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file;
	$new_photo_path = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/';
	$new_photo_name = $file;
	$i = 1;
	while( file_exists( $new_photo_path . $new_photo_name ) )
	{
		$new_photo_name = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $file );
		++$i;
	}

	if( nv_copyfile( $tmp_photo, $new_photo_path . $new_photo_name ) )
	{
		$sql = 'SELECT photo FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $user_info['userid'];
		$result = $db->query( $sql );
		$oldAvatar = $result->fetchColumn();
		$result->closeCursor();

		if( ! empty( $oldAvatar ) and file_exists( NV_ROOTDIR . '/' . $oldAvatar ) )
		{
			nv_deletefile( NV_ROOTDIR . '/' . $oldAvatar );
		}

		$photo = NV_UPLOADS_DIR . '/' . $module_upload . '/' . $new_photo_name;
		$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET photo=:photo WHERE userid=' . $user_info['userid'] );
		$stmt->bindParam( ':photo', $photo, PDO::PARAM_STR );
		$stmt->execute();
	}

	nv_deletefile( $tmp_photo );
}

function deleteAvatar()
{
	global $db, $user_info;

	$sql = 'SELECT photo FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $user_info['userid'];
	$result = $db->query( $sql );
	$oldAvatar = $result->fetchColumn();
	$result->closeCursor();

	if( ! empty( $oldAvatar ) )
	{
		if( file_exists( NV_ROOTDIR . '/' . $oldAvatar ) ) nv_deletefile( NV_ROOTDIR . '/' . $oldAvatar );

		$stmt = $db->prepare( "UPDATE " . NV_USERS_GLOBALTABLE . " SET photo='' WHERE userid=" . $user_info['userid'] );
		$stmt->execute();
	}
}

$page_title = $lang_module['avata_pagetitle'];

$array = array();
$array['success'] = 0;
$array['error'] = '';
$array['u'] = ( isset( $array_op[1] ) and ( $array_op[1] == "upd" or $array_op[1] == "opener" or $array_op[1] == "src" ) ) ? $array_op[1] : "";
$array['checkss'] = md5( $client_info['session_id'] . $global_config['sitekey'] );
$checkss = $nv_Request->get_title( 'checkss', 'post', '' );

//Xoa avatar
if( $checkss == $array['checkss'] && $nv_Request->isset_request( "del", "post" ) )
{
	deleteAvatar();
	die( json_encode( array(
		'status' => 'ok',
		'input' => 'ok',
		'mess' => $lang_module['editinfo_ok'] ) ) );
}

//global config
$sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='avatar_width'";
$result = $db->query( $sql );
$global_config['avatar_width'] = $result->fetchColumn();
$result->closeCursor();

$sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='avatar_height'";
$result = $db->query( $sql );
$global_config['avatar_height'] = $result->fetchColumn();
$result->closeCursor();

if( isset( $_FILES['image_file'] ) and is_uploaded_file( $_FILES['image_file']['tmp_name'] ) and ! empty( $array['u'] ) )
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

	if( sizeof( array_filter( array(
		$array['x1'],
		$array['y1'],
		$array['x2'],
		$array['y2'],
		$array['w'],
		$array['h'] ) ) ) < 4 or $array['avatar_width'] < $global_config['avatar_width'] or $array['avatar_height'] < $global_config['avatar_height'] )
	{
		$array['error'] = $lang_module['avata_error_data'];
	}
	else
	{
		$upload = new upload( array( 'images' ), $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );

		// Storage in temp dir
		$upload_info = $upload->save_file( $_FILES['image_file'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false );

		// Delete upload tmp
		@unlink( $_FILES['image_file']['tmp_name'] );

		if( empty( $upload_info['error'] ) )
		{
			$basename = $upload_info['basename'];
			$basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . nv_genpass( 8 ) . "_" . $user_info['userid'] . '\2', $basename );

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
				$array['filename'] = str_replace( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/', '', $image->create_Image_info['src'] );

				if( $array['u'] == "upd" )
				{
					updateAvatar( $array['filename'] );
					$array['success'] = 2;
				}
				elseif( $array['u'] == "src" )
				{
					updateAvatar( $array['filename'] );
					$array['filename'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['filename'];
					$array['success'] = 3;
				}
				else
				{
					$array['success'] = 1;
				}
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
