<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$bid = $nv_Request->get_int( 'bid', 'post' );
list( $theme, $position, $func_id, $groupbl ) = $db->sql_fetchrow( $db->sql_query( "SELECT theme, position,func_id, groupbl FROM `" . NV_BLOCKS_TABLE . "` WHERE bid=" . $bid . "" ) );
if ( intval( $groupbl ) > 0 )
{
    $selectthemes = ( empty( $theme ) ) ? $global_config['site_theme'] : $theme;
    
    $db->sql_query( "DELETE FROM " . NV_BLOCKS_TABLE . " WHERE groupbl='" . $groupbl . "'  AND theme='" . $selectthemes . "'" );
    #reupdate
    $result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE position='$position' AND func_id='$func_id'  AND theme='" . $selectthemes . "' ORDER BY weight ASC" );
    $order = 1;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "` SET weight=" . $order . " WHERE bid=" . $row['bid'] . "" );
        $order ++;
    }
    $db->sql_query( "LOCK TABLE `" . NV_BLOCKS_TABLE . "` WRITE" );
    $db->sql_query( "REPAIR TABLE `" . NV_BLOCKS_TABLE . "`" );
    $db->sql_query( "OPTIMIZE TABLE `" . NV_BLOCKS_TABLE . "`" );
    $db->sql_query( "UNLOCK TABLE `" . NV_BLOCKS_TABLE . "`" );
    echo $lang_module['block_delete_success'];
}
else
{
    echo $lang_module['block_front_delete_error'];
}
?>