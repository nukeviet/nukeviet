<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$bid = $nv_Request->get_int( 'bid', 'post', 0 );

$contents = "NO_" . $bid;
list( $bid ) = $db->sql_fetchrow( $db->sql_query( "SELECT `bid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` WHERE `bid`=" . intval( $bid ) . "" ) );
if ( $bid > 0 )
{
    nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_blockcat', "block_catid ".$bid, $admin_info['userid'] );
	$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` WHERE `bid`=" .$bid . "";
    if ( $db->sql_query( $query ) )
    {
        $db->sql_freeresult();
        $query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` WHERE `bid`=" .$bid . "";
        $db->sql_query( $query );
        nv_fix_block_cat();
        nv_del_moduleCache($module_name);
        $contents = "OK_" . $bid;
    }
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>