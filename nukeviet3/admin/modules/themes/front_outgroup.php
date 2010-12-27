<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$bid = $nv_Request->get_int( 'bid', 'post' );
list( $groupbl ) = $db->sql_fetchrow( $db->sql_query( "SELECT groupbl FROM `" . NV_BLOCKS_TABLE . "` WHERE bid=" . $bid . "" ) );
$numbl = $db->sql_numrows( $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl=" . $groupbl . "" ) );
if ( $numbl > 1 )
{
    $selectthemes = $global_config['site_theme'];
    
    list( $maxgroupbl ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(groupbl) FROM `" . NV_BLOCKS_TABLE . "`" ) );
    
    $sql = "UPDATE `" . NV_BLOCKS_TABLE . "` SET all_func='0' WHERE groupbl=" . $groupbl . " AND theme='" . $selectthemes . "' ";
    $result = $db->sql_query( $sql );
    
    $sql = "UPDATE `" . NV_BLOCKS_TABLE . "` SET groupbl='" . ( $maxgroupbl + 1 ) . "',all_func='0' WHERE bid=" . $bid . "";
    $result = $db->sql_query( $sql );
    if ( $result )
    {
        echo $lang_module['block_front_outgroup_success'] . ( $maxgroupbl + 1 );
    }
    else
    {
        echo $lang_module['block_front_outgroup_error_update'];
    }
    nv_del_moduleCache( 'themes' );
}
else
{
    echo $lang_module['block_front_outgroup_cancel'];
}

?>