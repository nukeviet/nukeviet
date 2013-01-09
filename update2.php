<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

define( 'NV_SYSTEM', true );

require (str_replace( DIRECTORY_SEPARATOR, '/', dirname( __file__ ) ) . '/mainfile.php');

$array_hidefolders = array(
	".",
	"..",
	"index.html",
	".htaccess",
	".tmp"
);

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

//15) Chuyển CSDL của module file vào DataBase
$step = $nv_Request->get_int( 'step', 'post,get', 1 );
if( $step == 1 )
{
	$db->sql_query( "CREATE TABLE `" . NV_UPLOAD_GLOBALTABLE . "_dir` (
		  `did` int(11) NOT NULL AUTO_INCREMENT,
		  `dirname` varchar(255) NOT NULL,
		  `time` int(11) NOT NULL DEFAULT '0',
		  `thumb_type` tinyint(4) NOT NULL DEFAULT '0',
		  `thumb_width` smallint(6) NOT NULL DEFAULT '0',
		  `thumb_height` smallint(6) NOT NULL DEFAULT '0',
		  `thumb_quality` tinyint(4) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`did`),
		  UNIQUE KEY `name` (`dirname`)
		) ENGINE=MyISAM" );
	$db->sql_query( "INSERT INTO `" . NV_UPLOAD_GLOBALTABLE . "_dir` (`did`, `dirname`, `time`, `thumb_type`, `thumb_width`, `thumb_height`, `thumb_quality`) VALUES ('-1', '', 0, 3, 100, 150, 90)" );
	$db->sql_query( "UPDATE `" . NV_UPLOAD_GLOBALTABLE . "_dir` SET `did` = '0' WHERE `did` = '-1'" );

	$db->sql_query( "CREATE TABLE `" . NV_UPLOAD_GLOBALTABLE . "_file` (
		  `name` varchar(255) NOT NULL,
		  `ext` varchar(10) NOT NULL DEFAULT '',
		  `type` varchar(5) NOT NULL DEFAULT '',
		  `filesize` int(11) NOT NULL DEFAULT '0',
		  `src` varchar(255) NOT NULL DEFAULT '',
		  `srcwidth` int(11) NOT NULL DEFAULT '0',
		  `srcheight` int(11) NOT NULL DEFAULT '0',
		  `size` varchar(50) NOT NULL DEFAULT '',
		  `userid` int(11) NOT NULL DEFAULT '0',
		  `mtime` int(11) NOT NULL DEFAULT '0',
		  `did` int(11) NOT NULL DEFAULT '0',
		  `title` varchar(255) NOT NULL DEFAULT '',
		  UNIQUE KEY `did` (`did`,`title`),
		  KEY `userid` (`userid`),
		  KEY `type` (`type`)
		) ENGINE=MyISAM" );

	$contents = "<br><br>";
	$real_dirlist = array( );
	$allow_upload_dir = array(
		'images',
		NV_UPLOADS_DIR
	);

	foreach( $allow_upload_dir as $dir )
	{
		$real_dirlist = nv_listUploadDir( $dir, $real_dirlist );
	}

	foreach( $real_dirlist as $info )
	{
		$db->sql_query( "INSERT INTO `" . NV_UPLOAD_GLOBALTABLE . "_dir` (`did`, `dirname`, `time`, `thumb_type`, `thumb_width`, `thumb_height`, `thumb_quality`) VALUES (NULL, '" . $info . "', '0', '0', '0', '0', '0')" );
		$contents .= "<br>" . $info;
	}
	$contents = "<meta http-equiv=\"refresh\" content=\"1;URL=" . NV_BASE_SITEURL . "update2.php?step=2\" />";
	die( 'Đang thực hiện nâng cấp CSDL cho module Upload' . $contents );
}
elseif( $step == 2 )
{
	list( $did, $pathimg ) = $db->sql_fetchrow( $db->sql_query( "SELECT `did`, `dirname` FROM `" . NV_UPLOAD_GLOBALTABLE . "_dir` WHERE `time`=0" ) );
	if( $did )
	{
		$tempFile = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/" . md5( $pathimg );
		$results = array( );
		if( file_exists( $tempFile ) )
		{
			$results = file_get_contents( $tempFile );
			$results = unserialize( $results );

			foreach( $results as $title => $info )
			{
				$db->sql_query( "INSERT INTO `" . NV_UPLOAD_GLOBALTABLE . "_file` 
				(`name`, `ext`, `type`, `filesize`, `src`, `srcwidth`, `srcheight`, `size`, `userid`, `mtime`, `did`, `title`) 
				VALUES ('" . $info[0] . "', '" . $info[1] . "', '" . $info[2] . "', " . $info[3] . ", '', " . $info[5] . ", " . $info[6] . ", '" . $info[7] . "', " . $info[8] . ", " . $info[9] . ", " . $did . ", '" . $title . "')" );
			}
			$dir_time = 1;
		}
		$db->sql_query( "UPDATE `" . NV_UPLOAD_GLOBALTABLE . "_dir` SET `time` = '" . $dir_time . "' WHERE `did` = " . $did );
		$contents = ": " . $pathimg . "<meta http-equiv=\"refresh\" content=\"1;URL=" . NV_BASE_SITEURL . "update2.php?step=2\" />";
	}
	else
	{
		nv_deletefile( NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache", true );
		nv_deletefile( NV_ROOTDIR . "/" . NV_FILES_DIR . "/images", true );
		$contents = "<meta http-equiv=\"refresh\" content=\"1;URL=" . NV_BASE_SITEURL . "update2.php?step=3\" />";
	}
	die( 'Đang thực hiện nâng cấp CSDL cho module Upload' . $contents );
}
else
{
	$db->sql_query( "UPDATE `" . NV_UPLOAD_GLOBALTABLE . "_dir` SET `time` = '0'" );
	die( 'Thực hiện nâng cấp CSDL thành công, Bạn cần xóa các file update.php, update2.php ở thư mục gốc của site ngay lập tức' );
}
?>