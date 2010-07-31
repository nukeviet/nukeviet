<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$path = htmlspecialchars ( trim ( $nv_Request->get_string ( 'path', 'post' ) ), ENT_QUOTES );
if ($admin_info ['allow_modify_files'] && in_array ( NV_UPLOADS_DIR, explode ( '/', $path ) )) {
	$image = htmlspecialchars ( trim ( $nv_Request->get_string ( 'img', 'post' ) ), ENT_QUOTES );
	$newname = htmlspecialchars ( trim ( $nv_Request->get_string ( 'name', 'post' ) ), ENT_QUOTES );
	$ext = pathinfo ( NV_ROOTDIR . '/' . $path . '/' . $image, PATHINFO_EXTENSION );
	if (! empty ( $newname ) && $image != $newname) {
		@rename ( NV_ROOTDIR . '/' . $path . '/' . $image, NV_ROOTDIR . '/' . $path . '/' . change_alias ( $newname ) . '.' . $ext );
		echo change_alias ( $newname ) . '.' . $ext;
	}
}
?>