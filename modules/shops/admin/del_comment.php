<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['comment_delete_title'];

$listid = $nv_Request->get_string( 'list', 'post,get' );
$del_array = explode( ',', $listid );
$del_array = array_map( "intval", $del_array );

foreach( $del_array as $cid )
{
	$sql = "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_comments_" . NV_LANG_DATA . "` WHERE cid='$cid'";
	$result = $db->query( $sql );
}

nv_del_moduleCache( $module_name );

include NV_ROOTDIR . '/includes/header.php';
echo $lang_module['comment_delete_success'];
include NV_ROOTDIR . '/includes/footer.php';

?>