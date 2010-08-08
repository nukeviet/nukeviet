<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_ABOUT' ) ) die( 'Stop!!!' );

$contents = "";

if ( $id )
{
    $sql = "SELECT `id`,`title`,`alias`,`bodytext`,`add_time`,`edit_time` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
    $query = $db->sql_query( $sql );
    $row = $db->sql_fetchrow( $query );
    
    $row['add_time'] = nv_date( "H:i T l, d/m/Y", $row['add_time'] );
    $row['edit_time'] = nv_date( "H:i T l, d/m/Y", $row['edit_time'] );
    $contents = nv_about_main( $row, $ab_links );
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>