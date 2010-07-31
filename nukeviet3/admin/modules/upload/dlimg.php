<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$path = htmlspecialchars ( trim ( $nv_Request->get_string ( 'path', 'get' ) ), ENT_QUOTES );
$image = htmlspecialchars ( trim ( $nv_Request->get_string ( 'img', 'get' ) ), ENT_QUOTES );
$path_filename = NV_ROOTDIR . '/' . $path . "/" . $image;
if (file_exists ( $path_filename ) && is_file ( $path_filename ) && in_array ( NV_UPLOADS_DIR, explode ( '/', $path ) )) {
	$file_extension = strtolower ( substr ( strrchr ( $image, "." ), 1 ) );
	$ctype = "";
	switch ($file_extension) {
		case "pdf" :
			$ctype = "application/pdf";
			break;
		case "exe" :
			$ctype = "application/octet-stream";
			break;
		case "zip" :
			$ctype = "application/zip";
			break;
		case "zar" :
			$ctype = "application/zar";
			break;
		case "doc" :
			$ctype = "application/msword";
			break;
		case "xls" :
			$ctype = "application/vnd.ms-excel";
			break;
		case "ppt" :
			$ctype = "application/vnd.ms-powerpoint";
			break;
		case "gif" :
			$ctype = "image/gif";
			break;
		case "png" :
			$ctype = "image/png";
			break;
		case "jpeg" :
		case "jpg" :
			$ctype = "image/jpg";
			break;
		default :
			$ctype = "application/force-download";
	}
	header ( "Pragma: public" );
	header ( "Expires: 0" );
	header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
	header ( "Cache-Control: private", false );
	header ( "Content-Type: $ctype" );
	
	header ( "Content-Disposition: attachment; filename=\"" . $image . "\";" );
	header ( "Content-Transfer-Encoding: binary" );
	header ( "Content-Length: " . filesize ( $path_filename ) );
	readfile ( $path_filename );
	exit ();
} else {
	echo 'file not exist !';
}
?>