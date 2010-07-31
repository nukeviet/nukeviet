<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */
if (! defined ( 'NV_ADMIN' ) or ! defined ( 'NV_MAINFILE' ) or ! defined ( 'NV_IS_MODADMIN' ))
	die ( 'Stop!!!' );
$fileupload = $nv_Request->get_string ( 'file', 'get' );
$cutpos = strrpos ( $fileupload, '/' );
$filename = substr ( $fileupload, $cutpos + 1 );
if (file_exists ( NV_DOCUMENT_ROOT . $fileupload ))
{
	header ( "Content-disposition: attachment; filename=" . $filename . "" );
	header ( "Content-Length: " . filesize ( NV_DOCUMENT_ROOT . $fileupload ) . "" );
	readfile ( NV_DOCUMENT_ROOT . $fileupload );
	exit ();
}
?>