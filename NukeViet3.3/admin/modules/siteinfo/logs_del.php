<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 11-10-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );
$contents = "NO_" . $lang_module['log_del_error'];
$number_del = 0;
if ( $id > 0 )
{
    $query = "DELETE FROM `" . $db_config['prefix'] . "_logs` WHERE `id`=" . $id . "";
    if ( $db->sql_query( $query ) )
    {
        $db->sql_freeresult();
        $contents = "OK_" . $lang_module['log_del_ok'];
        ++$number_del;
    }
}
else
{
    
    $listall = $nv_Request->get_string( 'listall', 'post,get' );
    $array_id = explode( ',', $listall );
    $array_id = array_map( "intval", $array_id );
    foreach ( $array_id as $id )
    {
        if ( $id > 0 )
        {
            $sql = "DELETE FROM `" . $db_config['prefix'] . "_logs` WHERE `id`=" . $id . "";
            $result = $db->sql_query( $sql );
            ++$number_del;
        }
    }
    $contents = "OK_" . $lang_module['log_del_ok'];
}
nv_insert_logs( NV_LANG_DATA, $module_name, $lang_global['delete'] . ' ' . $lang_module['logs_title'], $number_del, $admin_info['userid'] );
echo $contents;
?>