<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$listfile = $nv_Request->get_string( 'listfile', 'post,get' );
$array_id = explode( ',', $listfile );
$array_id = array_map( "intval", $array_id );
foreach ( $array_id as $id )
{
    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` WHERE id='$id'";
    $result = $db->sql_query( $sql );
}
echo $lang_module['delfilequeuelist_success'];
?>