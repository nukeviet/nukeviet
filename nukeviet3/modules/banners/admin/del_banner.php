<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$id = $nv_Request->get_int ( 'id', 'post,get' );
$sql = "DELETE FROM " . NV_BANNERS_ROWS_GLOBALTABLE . " WHERE id='$id'";
$result = $db->sql_query ( $sql );
if ($result)
	echo $lang_module['delfile_success'];
else
	echo $lang_module['delfile_error'];
?>