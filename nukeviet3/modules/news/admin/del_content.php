<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'post', '' );
$listid = $nv_Request->get_string( 'listid', 'post', '' );
$contents = "NO_" . $id;
if ( $listid != "" and md5( $global_config['sitekey'] . session_id() ) == $checkss )
{
    $del_array = array_map( "intval", explode( ",", $listid ) );
    foreach ( $del_array as $id )
    {
        if ( $id > 0 )
        {
            $contents = nv_del_content_module( $id );
        }
    }

}
elseif ( md5( $id . session_id() ) == $checkss )
{
    $contents = nv_del_content_module( $id );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>