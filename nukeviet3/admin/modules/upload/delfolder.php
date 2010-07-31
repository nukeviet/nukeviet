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
if (trim ( $path ) != NV_UPLOADS_DIR && ! empty ( $path ) && $admin_info ['allow_modify_subdirectories'] && in_array ( NV_UPLOADS_DIR, explode ( '/', $path ) ))
	nv_deletefile ( NV_ROOTDIR . '/' . trim ( $path ), true );
?>