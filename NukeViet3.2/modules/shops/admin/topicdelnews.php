<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$listid = $nv_Request->get_string( 'list', 'post,get' );

$del_array = explode( ',', $listid );
$del_array = array_map( "intval", $del_array );
foreach ( $del_array as $id )
{
    $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_rows` SET topic_id=0 WHERE id='$id'";
    $result = $db->sql_query( $sql );
}
nv_del_moduleCache( $module_name );

echo $lang_module['topic_delete_success'];

?>