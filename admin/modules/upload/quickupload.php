<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$CKEditorFuncNum = $nv_Request->get_string( 'CKEditorFuncNum', 'post,get', 0 );
$imgfolder = nv_check_path_upload( $nv_Request->get_string( 'currentpath', 'post,get' ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $imgfolder );

/**
 * SendUploadResults()
 * 
 * @param mixed $errorNumber
 * @param string $fileUrl
 * @param string $fileName
 * @param string $customMsg
 * @return void
 */
function SendUploadResults( $errorNumber, $fileUrl = "", $fileName = "", $customMsg = "" )
{
	global $CKEditorFuncNum;
	
	if( $CKEditorFuncNum )
	{
		echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ", '" . $fileUrl . "', '" . $customMsg . "');</script>";
	}
	else
	{
		echo "<script type=\"text/javascript\">(function(){var d=document.domain;while (true){try{var A=window.parent.document.domain;break;}catch(e) {};d=d.replace(/.*?(?:\.|$)/,'');if (d.length==0) break;try{document.domain=d;}catch (e){break;}}})();window.parent.OnUploadCompleted($errorNumber,\"" . $fileUrl . "\",\"" . $fileName . "\", \"" . $customMsg . "\");</script>";
	}
	exit();
}

if( ! isset( $check_allow_upload_dir['upload_file'] ) )
{
	SendUploadResults( 1, "", "", $lang_module['notlevel'] );
}

if( ! isset( $_FILES, $_FILES['upload'], $_FILES['upload']['tmp_name'] ) )
{
	SendUploadResults( 1, "", "", $lang_module['errorNotSelectFile'] );
}

$type = $nv_Request->get_string( 'type', 'post,get' );
$allow_files_type = array();

if( $type == "image" and in_array( 'images', $admin_info['allow_files_type'] ) )
{
	$allow_files_type = array( 'images' );
}
elseif( $type == "flash" and in_array( 'flash', $admin_info['allow_files_type'] ) )
{
	$allow_files_type = array( 'flash' );
}
elseif( empty( $type ) )
{
	$allow_files_type = $admin_info['allow_files_type'];
}

if( empty( $allow_files_type ) )
{
	SendUploadResults( 1, "", "", $lang_module['notlevel'] );
}

require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
$upload = new upload( $allow_files_type, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
$upload_info = $upload->save_file( $_FILES['upload'], NV_ROOTDIR . '/' . $imgfolder, false );

if( ! empty( $upload_info['error'] ) )
{
	SendUploadResults( 1, "", "", $upload_info['error'] );
}

nv_filesList( $imgfolder, false, $upload_info['basename'] );
nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_file'], $imgfolder . "/" . $upload_info['basename'], $admin_info['userid'] );

SendUploadResults( 0, NV_BASE_SITEURL . $imgfolder . "/" . $upload_info['basename'], $upload_info['basename'], "" );

?>