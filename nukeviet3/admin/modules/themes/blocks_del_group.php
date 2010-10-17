<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$list_group = $nv_Request->get_string( 'list', 'post,get' );
$array_group = explode( ',', $list_group );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$theme_array = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );
if ( in_array( $selectthemes, $theme_array ) )
{
    foreach ( $array_group as $groupbl )
    {
        $group = intval( $groupbl );
        if ( $group > 0 )
        {
            $db->sql_query( "DELETE FROM " . NV_BLOCKS_TABLE . " WHERE groupbl='" . $group . "' AND theme='" . $selectthemes . "'" );
        }
    }
    nv_del_moduleCache( 'themes' );
}
echo $lang_module['block_delete_success'];
?>