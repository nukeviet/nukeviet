<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_THEMES' ))
	die ( 'Stop!!!' );
$list_group = $nv_Request->get_string ( 'list', 'post,get' );
$array_group = explode ( ',', $list_group );
foreach ( $array_group as $groupbl ) {
	$group = intval ( $groupbl );
	if ($group > 0) {
		$db->sql_query ( "DELETE FROM " . NV_BLOCKS_TABLE . " WHERE groupbl='" . $group . "'" );
	}
}
echo $lang_module ['block_delete_success'];
?>