<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$bid = $nv_Request->get_int( 'bid', 'post' );
$func_id = $nv_Request->get_int( 'func_id', 'post' );

$row = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `bid`=" . $bid . "" ) );
if ( $func_id > 0 and isset( $row['bid'] ) )
{
    if ( ! empty( $row['all_func'] ) )
    {
        $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET `all_func`='0' WHERE `bid`=" . $bid );
    }
    list( $maxweight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(weight) FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE theme =" . $db->dbescape( $row['theme'] ) ) );
    $row['weight'] = intval( $maxweight ) + 1;
    $new_bid = $db->sql_query_insert_id( "INSERT INTO `" . NV_BLOCKS_TABLE . "_groups` (`bid`, `theme`, `module`, `file_name`, `title`, `link`, `template`, `position`, `exp_time`, `active`, `groups_view`, `all_func`, `weight`, `config`) VALUES ( NULL, " . $db->dbescape( $row['theme'] ) . ", " . $db->dbescape( $row['module'] ) . ", " . $db->dbescape( $row['file_name'] ) . ", " . $db->dbescape( $row['title'] ) . ", " . $db->dbescape( $row['link'] ) . ", " . $db->dbescape( $row['template'] ) . ", " . $db->dbescape( $row['position'] ) . ", '" . $row['exp_time'] . "', '" . $row['active'] . "', " . $db->dbescape( $row['groups_view'] ) . ", '" . $row['all_func'] . "', '" . $row['weight'] . "', " . $db->dbescape( $row['config'] ) . " )" );
    if ( $new_bid > 0 )
    {
        $db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid`=" . $new_bid . " AND `func_id`=" . $func_id );
        
        $sql = "SELECT MAX(t1.weight) FROM `" . NV_BLOCKS_TABLE . "_weight` AS t1 INNER JOIN `" . NV_BLOCKS_TABLE . "_groups` AS t2 ON t1.bid = t2.bid WHERE t1.func_id=" . $func_id . " AND t2.theme=" . $db->dbescape( $row['theme'] ) . " AND t2.position=" . $db->dbescape( $row['position'] ) . "";
        list( $weight ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
        $weight = intval( $weight ) + 1;
        
        $db->sql_query( "INSERT INTO `" . NV_BLOCKS_TABLE . "_weight` (`bid`, `func_id`, `weight`) VALUES ('" . $new_bid . "', '" . $func_id . "', '" . $weight . "')" );
        
        echo $lang_module['block_front_outgroup_success'] . ( $new_bid + 1 );
        nv_del_moduleCache( 'themes' );
    }
    else
    {
        echo $lang_module['block_front_outgroup_error_update'];
    }
}
else
{
    echo $lang_module['block_front_outgroup_cancel'];
}

?>