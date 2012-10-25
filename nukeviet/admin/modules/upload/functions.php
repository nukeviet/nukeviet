<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_SPADMIN' ) )
{
	$submenu['config'] = $lang_global['mod_settings'];
}

if( $module_name != "upload" ) return;

$menu_top = array( "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_upload'] );

define( 'NV_IS_FILE_ADMIN', true );

$allow_func = array( 'main', 'imglist', 'delimg', 'createimg', 'dlimg', 'renameimg', 'moveimg', 'folderlist', 'delfolder', 'renamefolder', 'createfolder', 'quickupload', 'upload', 'addlogo' );

if( defined( 'NV_IS_SPADMIN' ) )
{
	$allow_func[] = 'config';
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
	if( empty( $dir ) ) return array();

	$dir = str_replace( "\\", "/", $dir );
	$dir = rtrim( $dir, "/" );
	$arr_dir = explode( "/", $dir );
	$level = array();

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
	if( empty( $path ) ) return "";

	$path = NV_ROOTDIR . "/" . $path;
	if( ( $path = realpath( $path ) ) === false ) return "";

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

	if( $result === false ) return "";
	return $path;
}

/**
 * nv_delete_cache_upload()
 *
 * @param mixed $realpath
 * @return
 */
function nv_delete_cache_upload( $path )
{
	$tempFile = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/" . md5( $path );
	
	if( file_exists( $tempFile ) )
	{
		@nv_deletefile( $tempFile );
	}

	$files = scandir( NV_ROOTDIR . '/' . $path );
	$files = array_diff( $files, array( ".", ".." ) );
	
	if( sizeof( $files ) )
	{
		foreach( $files as $file )
		{
			if( is_dir( NV_ROOTDIR . '/' . $path . '/' . $file ) )
			{
				nv_delete_cache_upload( $path . '/' . $file );
			}
			else
			{
				$md5_view_image = NV_ROOTDIR . "/" . NV_FILES_DIR . "/images/" . md5( $path . '/' . $file ) . "." . nv_getextension( $file );
				if( file_exists( $md5_view_image ) )
				{
					@nv_deletefile( $md5_view_image );
				}
			}
		}
	}
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
			$size[1] );
	}

	include_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
	$image = new image( NV_ROOTDIR . '/' . $fileName, NV_MAX_WIDTH, NV_MAX_HEIGHT );
	$image->resizeXY( $w, $h );
	$image->save( NV_ROOTDIR . '/' . $viewDir, $md5_view_image, 75 );
	$create_Image_info = $image->create_Image_info;
	$error = $image->error;
	$image->close();
	
	if( empty( $error ) )
	{
		return array( $viewDir . '/' . basename( $create_Image_info['src'] ), $create_Image_info['width'], $create_Image_info['height'] );
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

	clearstatcache();

	unset( $matches );
	preg_match( "/([a-zA-Z0-9\.\-\_\\s\(\)]+)\.([a-zA-Z0-9]+)$/", $file, $matches );

	$info = array();
	$info[0] = $file;
	if( isset( $file{17} ) )
	{
		$info[0] = substr( $matches[1], 0, ( 13 - strlen( $matches[2] ) ) ) . "..." . $matches[2];
	}

	$info[1] = $matches[2];
	$info[2] = "file";

	$stat = @stat( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
	$info[3] = $stat['size'];

	$info[4] = 'images/file.gif';
	$info[5] = 32;
	$info[6] = 32;
	$info[7] = "|";
	$ext = strtolower( $matches[2] );
	
	if( in_array( $ext, $array_images ) )
	{
		$size = @getimagesize( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
		$info[2] = "image";
		$info[4] = $pathimg . '/' . $file;
		$info[5] = $size[0];
		$info[6] = $size[1];
		$info[7] = $size[0] . "|" . $size[1];

		if( $size[0] > 80 or $size[1] > 80 )
		{
			if( ( $_src = nv_get_viewImage( $pathimg . '/' . $file, 80, 80 ) ) !== false )
			{
				$info[4] = $_src[0];
				$info[5] = $_src[1];
				$info[6] = $_src[2];
			}
			else
			{
				if( $info[5] > 80 )
				{
					$info[6] = round( 80 / $info[5] * $info[6] );
					$info[5] = 80;
				}

				if( $info[6] > 80 )
				{
					$info[5] = round( 80 / $info[6] * $info[5] );
					$info[6] = 80;
				}
			}
		}
	}
	elseif( in_array( $ext, $array_flash ) )
	{
		$info[2] = "flash";
		$info[4] = 'images/flash.gif';

		if( $matches[2] == "swf" )
		{
			$size = @getimagesize( NV_ROOTDIR . '/' . $pathimg . '/' . $file );
			if( isset( $size, $size[0], $size[1] ) )
			{
				$info[7] = $size[0] . "|" . $size[1];
			}
		}
	}
	elseif( in_array( $ext, $array_archives ) )
	{
		$info[4] = 'images/zip.gif';
	}
	elseif( in_array( $ext, $array_documents ) )
	{
		$info[4] = 'images/doc.gif';
	}

	$info[8] = 0;
	$info[9] = $stat['mtime'];

	return $info;
}

/**
 * nv_filesList()
 *
 * @param mixed $pathimg
 * @param bool $refresh
 * @param string $newFile
 * @return
 */
function nv_filesList( $pathimg, $refresh, $newFile = "", $delFile = "" )
{
	global $array_hidefolders, $admin_info;

	$md5 = md5( $pathimg );
	$tempFile = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/" . $md5;
	$file_exists = file_exists( $tempFile );
	$results = array();

	if( $file_exists )
	{
		$results = file_get_contents( $tempFile );
		$results = unserialize( $results );
	}
	else
	{
		$refresh = true;
	}

	if( is_dir( NV_ROOTDIR . "/" . $pathimg ) )
	{
		if( $refresh )
		{
			if( $dh = opendir( NV_ROOTDIR . "/" . $pathimg ) )
			{
				$files = array();
				while( ( $file = readdir( $dh ) ) !== false )
				{
					if( in_array( $file, $array_hidefolders ) ) continue;

					if( preg_match( "/([a-zA-Z0-9\.\-\_\\s\(\)]+)\.([a-zA-Z0-9]+)$/", $file ) )
					{
						$files[] = $file;

						$info = nv_getFileInfo( $pathimg, $file );
						if( ! empty( $newFile ) and $file == $newFile )
						{
							$info[8] = $admin_info['userid'];
							$info[9] = NV_CURRENTTIME;
						}
						else
						{
							if( isset( $results[$file][8] ) ) $info[8] = $results[$file][8];
							if( isset( $results[$file][9] ) ) $info[9] = $results[$file][9];
						}

						$results[$file] = $info;
					}
				}
				closedir( $dh );

				$files = array_flip( $files );
				$results = array_intersect_key( $results, $files );
			}
			ksort( $results );
			file_put_contents( $tempFile, serialize( $results ) );
		}
		else
		{
			if( ! empty( $newFile ) )
			{
				$info = nv_getFileInfo( $pathimg, $newFile );
				$info[8] = $admin_info['userid'];
				$info[9] = NV_CURRENTTIME;

				$results[$newFile] = $info;
				ksort( $results );
				file_put_contents( $tempFile, serialize( $results ) );
			}

			if( ! empty( $delFile ) )
			{
				unset( $results[$delFile] );
				file_put_contents( $tempFile, serialize( $results ) );
			}
		}
	}

	return $results;
}

$allow_upload_dir = array( 'images', NV_UPLOADS_DIR );
$array_hidefolders = array( ".svn", "CVS", ".", "..", "index.html", ".htaccess", ".tmp" );

$array_images = array( "gif", "jpg", "jpeg", "pjpeg", "png" );
$array_flash = array( 'swf', 'swc', 'flv' );
$array_archives = array( 'rar', 'zip', 'tar' );
$array_documents = array( 'doc', 'xls', 'chm', 'pdf', 'docx', 'xlsx' );

$dirlistCache = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/dirlist-" . md5( implode( $allow_upload_dir ) );

if( ! file_exists( $dirlistCache ) or ( $nv_Request->isset_request( 'dirListRefresh', 'get' ) and filemtime( $dirlistCache ) < ( NV_CURRENTTIME - 30 ) ) )
{
	$dirlist = nv_loadUploadDirList();
}
else
{
	$dirlist = file_get_contents( $dirlistCache );
	$dirlist = unserialize( $dirlist );
}

?>