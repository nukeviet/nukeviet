<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$status = $nv_Request->get_int( 'active', 'post' );
$listcid = $nv_Request->get_string( 'list', 'post' );
if ( ! empty( $listcid ) )
{
    $status = ( $status == 1 ) ? 1 : 0;
    $cid_array = explode( ',', $listcid );
    $cid_array = array_map( "intval", $cid_array );
    // Xac dinh ID cac bai viet
    $sql = "SELECT cid, id FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE cid in (" . implode( ",", $cid_array ) . ")";
    $query = $db->sql_query( $sql );
    $array_id = array();
    while ( list( $cid, $id ) = $db->sql_fetchrow( $query ) )
    {
        $array_id[$cid] = $id;
    }
    // Het Xac dinh ID cac bai viet
    

    // Xac dinh cac chu de bai viet
    $array_listcatid = array();
    $query = $db->sql_query( "SELECT id, listcatid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` in (" . implode( ",", array_unique( $array_id ) ) . ")" );
    while ( list( $id, $listcatid ) = $db->sql_fetchrow( $query ) )
    {
        $array_listcatid[$id] = explode( ",", $listcatid );
    }
    
    // Xac dinh cac chu de bai viet
    foreach ( $cid_array as $cid )
    {
        if ( isset( $array_id[$cid] ) )
        {
            $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comments` SET status='" . $status . "' WHERE cid=" . $cid . "";
            $db->sql_query( $query );
            
            $id = $array_id[$cid];
            list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `id`= '" . $id . "' AND `status`=1" ) );
            
            $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `hitslm`=" . $numf . " WHERE `id`=" . $id;
            $db->sql_query( $query );
            $array_catid = $array_listcatid[$id];
            foreach ( $array_catid as $catid_i )
            {
                $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `hitslm`=" . $numf . " WHERE `id`=" . $id;
                $db->sql_query( $query );
            }
        }
    }
    echo $lang_module['comment_update_success'];
}
?>