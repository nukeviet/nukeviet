<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$bid = $nv_Request->get_int( 'bid', 'post' );
$func = $nv_Request->get_int( 'func_id', 'post' );
$pos = filter_text_input( 'pos', 'post', '' );
$group = $nv_Request->get_int( 'group', 'post' );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$theme_array = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );
if ( in_array( $selectthemes, $theme_array ) and ! empty( $pos ) and $bid > 0 )
{
    $result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl=" . intval( $group ) . " AND theme='$selectthemes'" );
    while ( list( $bids ) = $db->sql_fetchrow( $result ) )
    {
        list( $maxweight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(weight) FROM `" . NV_BLOCKS_TABLE . "` WHERE func_id=" . intval( $func ) . " AND position=" . $db->dbescape( $pos ) . "" ) );
        $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET position=" . $db->dbescape( $pos ) . ", weight='" . ( $maxweight + 1 ) . "' WHERE `bid`='$bids'" );
    }
    #reupdate 
    $result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE func_id='" . $func . "' AND position='" . $pos . "' AND theme='$selectthemes'" );
    $i = 1;
    while ( list( $bid ) = $db->sql_fetchrow( $result ) )
    {
        $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight='" . ( $i ) . "' WHERE `bid`='$bid'" );
        $i ++;
    }
    nv_del_moduleCache( 'themes' );
}
echo $lang_module['block_update_success'];
?>