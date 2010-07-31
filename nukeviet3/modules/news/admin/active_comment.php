<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$active = $nv_Request->get_int ( 'active', 'post' );
$cid = $nv_Request->get_string ( 'list', 'post' );
$cid = explode ( ',', $cid );
if ($active)
{
	foreach ( $cid as $value )
	{
		$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comments` SET status='1' WHERE status!=1 AND cid=" . $value . "";
		$db->sql_query ( $query );
	}
} else
{
	foreach ( $cid as $value )
	{
		$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comments` SET status='0' WHERE status=1 AND cid=" . $value . "";
		$db->sql_query ( $query );
	}
}
echo $lang_module['comment_update_success'];
?>