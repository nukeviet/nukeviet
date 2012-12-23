<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) )
	die( 'Stop!!!' );

if( defined( 'NV_IS_SPADMIN' ) )
{
	$submenu['config'] = $lang_module['configlogo'];
	if( defined( 'NV_IS_GODADMIN' ) )
	{
		$submenu['uploadconfig'] = $lang_module['uploadconfig'];
	}
}

if( $module_name != "upload" )
	return;

$menu_top = array(
	"title" => $module_name,
	"module_file" => "",
	"custom_title" => $lang_global['mod_upload']
);

define( 'NV_IS_FILE_ADMIN', true );

$allow_func = array(
	'main',
	'imglist',
	'delimg',
	'createimg',
	'dlimg',
	'renameimg',
	'moveimg',
	'folderlist',
	'delfolder',
	'renamefolder',
	'createfolder',
	'quickupload',
	'upload',
	'addlogo'
);

if( defined( 'NV_IS_SPADMIN' ) )
{
	$allow_func[] = 'config';
	if( defined( 'NV_IS_GODADMIN' ) )
	{
		$allow_func[] = 'uploadconfig';
	}
}

/**
 * nv_check_allow_upload_dir()
 *
 * @param mixed $dir
 * @return
 */
function nv_check_allow_upload_dir( $dir )
{
	global $site_mods, $allow_upload_dir, $admin_info;

	$dir = trim( $dir );
	if( empty( $dir ) )
		return array( );

	$dir = str_replace( "\\", "/", $dir );
	$dir = rtrim( $dir, "/" );
	$arr_dir = explode( "/", $dir );
	$level = array( );

	if( in_array( $arr_dir[0], $allow_upload_dir ) )
	{
		if( defined( 'NV_IS_SPADMIN' ) )
		{
			$level['view_dir'] = true;

			if( $admin_info['allow_create_subdirectories'] )
			{
				$level['create_dir'] = true;
			}

			if( $admin_info['allow_modify_subdirectories'] and ! in_array( $dir, $allow_upload_dir ) )
			{
				$level['rename_dir'] = true;
				$level['delete_dir'] = true;

				if( isset( $arr_dir[1] ) and ! empty( $arr_dir[1] ) and isset( $site_mods[$arr_dir[1]] ) and ! isset( $arr_dir[2] ) )
				{
					unset( $level['rename_dir'], $level['delete_dir'] );
				}
			}

			if( ! empty( $admin_info['allow_files_type'] ) )
			{
				$level['upload_file'] = true;
			}

			if( $admin_info['allow_modify_files'] )
			{
				$level['create_file'] = true;
				$level['rename_file'] = true;
				$level['delete_file'] = true;
				$level['move_file'] = true;
			}
		}
		elseif( isset( $arr_dir[1] ) and ! empty( $arr_dir[1] ) and isset( $site_mods[$arr_dir[1]] ) )
		{
			$level['view_dir'] = true;

			if( $admin_info['allow_create_subdirectories'] )
			{
				$level['create_dir'] = true;
			}

			if( isset( $arr_dir[2] ) and ! empty( $arr_dir[2] ) and $admin_info['allow_modify_subdirectories'] )
			{
				$level['rename_dir'] = true;
				$level['delete_dir'] = true;
			}

			if( ! empty( $admin_info['allow_files_type'] ) )
			{
				$level['upload_file'] = true;
			}

			if( $admin_info['allow_modify_files'] )
			{
				$level['create_file'] = true;
				$level['rename_file'] = true;
				$level['delete_file'] = true;
				$level['move_file'] = true;
			}
		}

		if( preg_match( "/^([\d]{4})\_([\d]{1,2})$/", $arr_dir[sizeof( $arr_dir ) - 1] ) )
		{
			unset( $level['rename_dir'], $level['delete_dir'] );
		}
	}

	return $level;
}

/**
 * nv_check_path_upload()
 *
 * @param mixed $path
 * @return
 */
function nv_check_path_upload( $path )
{
	global $allow_upload_dir;

	$path = htmlspecialchars( trim( $path ), ENT_QUOTES );
	$path = rtrim( $path, "/" );
	if( empty( $path ) )
		return "";

	$path = NV_ROOTDIR . "/" . $path;
	if( ($path = realpath( $path )) === false )
		return "";

	$path = str_replace( "\\", "/", $path );
	$path = str_replace( NV_ROOTDIR . "/", "", $path );

	$result = false;
	foreach( $allow_upload_dir as $dir )
	{
		$dir = nv_preg_quote( $dir );
		if( preg_match( "/^" . $dir . "/", $path ) )
		{
			$result = true;
			break;
		}
	}

	if( $result === false )
		return "";
	return $path;
}

/**
 * nv_get_viewImage()
 *
 * @param mixed $fileName
 * @param integer $w
 * @param integer $h
 * @return
 */
function nv_get_viewImage( $fileName, $w = 80, $h = 80 )
{
	$ext = nv_getextension( $fileName );
	$md5_view_image = md5( $fileName );
	$viewDir = NV_FILES_DIR . '/images';
	$viewFile = $viewDir . '/' . $md5_view_image . '.' . $ext;

	if( file_exists( NV_ROOTDIR . '/' . $viewFile ) )
	{
		$size = @getimagesize( NV_ROOTDIR . '/' . $viewFile );
		return array(
			$viewFile,
			$size[0],
			$size[1]
		);
	}

	include_once (NV_ROOTDIR . "/includes/class/image.class.php");
	$image = new image( NV_ROOTDIR . '/' . $fileName, NV_MAX_WIDTH, NV_MAX_HEIGHT );
	$image->resizeXY( $w, $h );
	$image->save( NV_ROOTDIR . '/' . $viewDir, $md5_view_image, 75 );
	$create_Image_info = $image->create_Image_info;
	$error = $image->error;
	$image->close( );

	if( empty( $error ) )
	{
		return array(
			$viewDir . '/' . basename( $create_Image_info['src'] ),
			$create_Image_info['width'],
			$create_Image_info['height']
		);
	}

	return false;
}

/**
 * nv_getFileInfo()
 *
 * @param mixed $pathimg
 * @param mixed $file
 * @return
 */
function nv_getFileInfo( $pathimg, $file )
{
	global $array_images, $array_flash, $array_archives, $array_documents;

	clearstatcache( );

	unset( $matches );
	preg_match( "/([a-zA-Z0-9\.\-\_\\s\(\)]+)\.([a-zA-Z0-9]+)$/", $file, $matches );

	$info = array( );
	$info['name'] = $file;
	if( isset( $file{17} ) )
	{
		$info['name'] = substr( $matches[1], 0, (13 - strlen( $matches[2] )) ) . "..." . $matches[2];
	}

	$info['ext'] = $matches[2];
	$info['type'] = "file";

	$stat = @stat( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
	$info['filesize'] = $stat['size'];

	$info['src'] = 'images/file.gif';
	$info['srcwidth'] = 32;
	$info['srcheight'] = 32;
	$info['size'] = "|";
	$ext = strtolower( $matches[2] );

	if( in_array( $ext, $array_images ) )
	{
		$size = @getimagesize( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
		$info['type'] = "image";
		$info['src'] = $pathimg . '/' . $file;
		$info['srcwidth'] = $size[0];
		$info['srcheight'] = $size[1];
		$info['size'] = $size[0] . "|" . $size[1];

		if( $size[0] > 80 or $size[1] > 80 )
		{
			if( ($_src = nv_get_viewImage( $pathimg . '/' . $file, 80, 80 )) !== false )
			{
				$info['src'] = $_src[0];
				$info['srcwidth'] = $_src[1];
				$info['srcheight'] = $_src[2];
			}
			else
			{
				if( $info['srcwidth'] > 80 )
				{
					$info['srcheight'] = round( 80 / $info['srcwidth'] * $info['srcheight'] );
					$info['srcwidth'] = 80;
				}

				if( $info['srcheight'] > 80 )
				{
					$info['srcwidth'] = round( 80 / $info['srcheight'] * $info['srcwidth'] );
					$info['srcheight'] = 80;
				}
			}
		}
	}
	elseif( in_array( $ext, $array_flash ) )
	{
		$info['type'] = "flash";
		$info['src'] = 'images/flash.gif';

		if( $matches[2] == "swf" )
		{
			$size = @getimagesize( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
			if( isset( $size, $size[0], $size[1] ) )
			{
				$info['size'] = $size[0] . "|" . $size[1];
			}
		}
	}
	elseif( in_array( $ext, $array_archives ) )
	{
		$info['src'] = 'images/zip.gif';
	}
	elseif( in_array( $ext, $array_documents ) )
	{
		$info['src'] = 'images/doc.gif';
	}

	$info['userid'] = 0;
	$info['mtime'] = $stat['mtime'];

	return $info;
}

/**
 * nv_filesListRefresh()
 *
 * @param mixed $pathimg
 * @return
 */
function nv_filesListRefresh( $pathimg )
{
	global $array_hidefolders, $admin_info, $db_config, $module_data, $db, $array_dirname;
	$results = array( );
	$did = $array_dirname[$pathimg];
	if( is_dir( NV_ROOTDIR . "/" . $pathimg ) )
	{
		$result = $db->sql_query( "SELECT * FROM `" . NV_UPLOAD_GLOBALTABLE . "_file` WHERE `did` = " . $did );
		while( $row = $db->sql_fetch_assoc( $result ) )
		{
			$results[$row['title']] = $row;
		}

		if( $dh = opendir( NV_ROOTDIR . "/" . $pathimg ) )
		{
			while( ($title = readdir( $dh )) !== false )
			{
				if( in_array( $title, $array_hidefolders ) )
					continue;

				if( preg_match( "/([a-zA-Z0-9\.\-\_\\s\(\)]+)\.([a-zA-Z0-9]+)$/", $title, $m ) )
				{
					$info = nv_getFileInfo( $pathimg, $title );
					$info['did'] = $did;
					$info['title'] = $title;
					if( isset( $results[$title] ) )
					{
						$info['userid'] = $results[$title]['userid'];
						$dif = array_diff_assoc( $info, $results[$title] );
						if( ! empty( $dif ) )
						{
							//Cập nhật CSDL file thay đổi
							$db->sql_query( "REPLACE INTO `" . NV_UPLOAD_GLOBALTABLE . "_file` 
										(`name`, `ext`, `type`, `filesize`, `src`, `srcwidth`, `srcheight`, `size`, `userid`, `mtime`, `did`, `title`) 
										VALUES ('" . $info['name'] . "', '" . $info['ext'] . "', '" . $info['type'] . "', " . $info['filesize'] . ", '" . $info['src'] . "', " . $info['srcwidth'] . ", " . $info['srcheight'] . ", '" . $info['size'] . "', " . $info['userid'] . ", " . $info['mtime'] . ", " . $did . ", '" . $title . "')" );
						}
						unset( $results[$title] );
					}
					else
					{
						$info['userid'] = $admin_info['userid'];
						// Thêm file mới
						$db->sql_query( "INSERT INTO `" . NV_UPLOAD_GLOBALTABLE . "_file` 
										(`name`, `ext`, `type`, `filesize`, `src`, `srcwidth`, `srcheight`, `size`, `userid`, `mtime`, `did`, `title`) 
										VALUES ('" . $info['name'] . "', '" . $info['ext'] . "', '" . $info['type'] . "', " . $info['filesize'] . ", '" . $info['src'] . "', " . $info['srcwidth'] . ", " . $info['srcheight'] . ", '" . $info['size'] . "', " . $info['userid'] . ", " . $info['mtime'] . ", " . $did . ", '" . $title . "')" );
					}
				}
			}
			closedir( $dh );

			if( ! empty( $results ) )
			{
				// Xóa CSDL file không còn tồn tại
				foreach( $results as $title => $value )
				{
					$db->sql_query( "DELETE FROM `" . NV_UPLOAD_GLOBALTABLE . "_file` WHERE `did` = " . $did . " AND `title`='" . $title . "'" );
				}
			}
			$db->sql_query( "UPDATE `" . NV_UPLOAD_GLOBALTABLE . "_dir` SET `time` = '" . NV_CURRENTTIME . "' WHERE `did` = " . $did );
		}
	}
	else
	{
		// Xóa CSDL thư mục không còn tồn tại
		$db->sql_query( "DELETE FROM `" . NV_UPLOAD_GLOBALTABLE . "_file` WHERE `did` = " . $did );
		$db->sql_query( "DELETE FROM `" . NV_UPLOAD_GLOBALTABLE . "_dir` WHERE `did` = " . $did );
	}
}

/**
 * nv_listUploadDir()
 *
 * @param mixed $dir
 * @param mixed $real_dirlist
 * @return
 */
function nv_listUploadDir( $dir, $real_dirlist = array() )
{
	$real_dirlist[] = $dir;

	if( ($dh = @opendir( NV_ROOTDIR . '/' . $dir )) !== false )
	{
		while( false !== ($subdir = readdir( $dh )) )
		{
			if( preg_match( "/^[a-zA-Z0-9\-\_]+$/", $subdir ) )
			{
				if( is_dir( NV_ROOTDIR . '/' . $dir . '/' . $subdir ) )
					$real_dirlist = nv_listUploadDir( $dir . '/' . $subdir, $real_dirlist );
			}
		}

		closedir( $dh );
	}

	return $real_dirlist;
}

$allow_upload_dir = array(
	'images',
	NV_UPLOADS_DIR
);
$array_hidefolders = array(
	".",
	"..",
	"index.html",
	".htaccess",
	".tmp"
);

$array_images = array(
	"gif",
	"jpg",
	"jpeg",
	"pjpeg",
	"png"
);
$array_flash = array(
	'swf',
	'swc',
	'flv'
);
$array_archives = array(
	'rar',
	'zip',
	'tar'
);
$array_documents = array(
	'doc',
	'xls',
	'chm',
	'pdf',
	'docx',
	'xlsx'
);

$sql = "SELECT `did`, `dirname` FROM `" . NV_UPLOAD_GLOBALTABLE . "_dir` ORDER BY `dirname` ASC";
$result = $db->sql_query( $sql );
$array_dirname = array( );
while( $row = $db->sql_fetch_assoc( $result ) )
{
	$array_dirname[$row['dirname']] = $row['did'];
}
if( $nv_Request->isset_request( 'dirListRefresh', 'get' ) )
{
	$real_dirlist = array( );
	foreach( $allow_upload_dir as $dir )
	{
		$real_dirlist = nv_listUploadDir( $dir, $real_dirlist );
	}
	$dirlist = array_keys( $array_dirname );
	$result_no_exit = array_diff( $dirlist, $real_dirlist );
	foreach( $result_no_exit as $dirname )
	{
		// Xóa CSDL thư mục không còn tồn tại
		$did = $array_dirname[$dirname];
		$db->sql_query( "DELETE FROM `" . NV_UPLOAD_GLOBALTABLE . "_file` WHERE `did` = " . $did );
		$db->sql_query( "DELETE FROM `" . NV_UPLOAD_GLOBALTABLE . "_dir` WHERE `did` = " . $did );
		unset( $array_dirname[$dirname] );
	}
	$result_new = array_diff( $real_dirlist, $dirlist );
	foreach( $result_new as $dirname )
	{
		$array_dirname[$dirname] = $db->sql_query_insert_id( "INSERT INTO `" . NV_UPLOAD_GLOBALTABLE . "_dir` (`did`, `dirname`, `time`) VALUES (NULL, '" . $dirname . "', 0)" );
	}
}

$global_config['upload_logo'] = $db->unfixdb( nv_unhtmlspecialchars( $global_config['upload_logo'] ) );
?>