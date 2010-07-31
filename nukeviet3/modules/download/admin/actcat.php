<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$cat = $nv_Request->get_int( 'cat', 'get' );
$cid = $nv_Request->get_int( 'cid', 'get' );
if ( $cid > 0 )
{
    $query = "SELECT `active` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid =" . $cid;
    $re = $db->sql_query( $query );
    list( $active ) = $db->sql_fetchrow( $re );
    echo $active;
    if ( $active == '1' )
    {
        $active = '0';
    }
    else if ( $active == '0' ) $active = '1';
    
    $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET active ='" . $active . "' WHERE cid = '" . $cid . "'";
    $db->sql_query( $query );
}
$url_link = "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=cat&cid=" . $cat;
Header( "Location:" . $url_link );
exit();
?>
