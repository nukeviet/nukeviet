<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$theme1 = filter_text_input( 'theme1', 'post' );
$theme2 = filter_text_input( 'theme2', 'post' );
$position = filter_text_input( 'position', 'post' );
$position = explode( ',', $position );

if ( ! empty( $theme1 ) and ! empty( $theme2 ) and $theme1 != $theme2 and file_exists( NV_ROOTDIR . '/themes/' . $theme1 . '/config.ini' ) and file_exists( NV_ROOTDIR . '/themes/' . $theme2 . '/config.ini' ) and ! empty( $position ) )
{
    foreach ( $position as $pos )
    {
        #begin drop all exist blocks behavior with theme 2 and position relative
        $db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "` WHERE `theme` = " . $db->dbescape( $theme2 ) . " AND `position`=" . $db->dbescape( $pos ) . "" );
        #get and insert block from theme 1
        $sql = "SELECT * FROM `" . NV_BLOCKS_TABLE . "` WHERE `theme` = " . $db->dbescape( $theme1 ) . " AND `position`=" . $db->dbescape( $pos ) . "";
        $result = $db->sql_query( $sql );
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $db->sql_query( "INSERT INTO `" . NV_BLOCKS_TABLE . "` VALUES (NULL, " . $row['groupbl'] . "," . $db->dbescape( $row['title'] ) . "," . $db->dbescape( $row['link'] ) . "," . $db->dbescape( $row['type'] ) . "," . $db->dbescape( $row['file_path'] ) . "," . $db->dbescape( $theme2 ) . "," . $db->dbescape( $row['template'] ) . "," . $db->dbescape( $row['position'] ) . "," . $row['exp_time'] . "," . $row['active'] . "," . $db->dbescape( $row['groups_view'] ) . "," . $db->dbescape( $row['module'] ) . "," . $row['all_func'] . "," . $row['func_id'] . "," . $row['weight'] . ")" );
        }
    }
    nv_del_moduleCache( 'themes' );
    echo $lang_module['xcopyblock_success'];
}
else
{
    die( 'error request !' );
}
?>