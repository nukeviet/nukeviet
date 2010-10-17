<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$selectthemes = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$theme_array = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );
if ( ! in_array( $selectthemes, $theme_array ) )
{
    $selectthemes = $global_config['site_theme'];
}

$list_bid = $nv_Request->get_string( 'list', 'post,get' );
$array_bid = explode( ',', $list_bid );
foreach ( $array_bid as $bid )
{
    $bid = intval( $bid );
    if ( $bid > 0 )
    {
        nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_block', "blockid  " . $bid, $admin_info['userid'] );
        list( $position, $func_id ) = $db->sql_fetchrow( $db->sql_query( "SELECT position,func_id FROM `" . NV_BLOCKS_TABLE . "` WHERE bid=" . $bid . "" ) );
        $db->sql_query( "DELETE FROM " . NV_BLOCKS_TABLE . " WHERE bid='" . $bid . "'" );
        #reupdate
        $result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE position='$position' AND func_id='$func_id'  AND theme='" . $selectthemes . "' ORDER BY weight ASC" );
        $order = 1;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight=" . $order . " WHERE bid=" . $row['bid'] . "" );
            $order ++;
        }
        $db->sql_query( "LOCK TABLE " . NV_BLOCKS_TABLE . " WRITE" );
        $db->sql_query( "REPAIR TABLE " . NV_BLOCKS_TABLE );
        $db->sql_query( "OPTIMIZE TABLE " . NV_BLOCKS_TABLE );
        $db->sql_query( "UNLOCK TABLE " . NV_BLOCKS_TABLE );
        nv_del_moduleCache( 'themes' );
    }
}
echo $lang_module['block_delete_success'];
?>