<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$fid = $nv_Request->get_int( 'id', 'get' );
if ( $fid > 0 )
{
    $query = "SELECT `active` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE id =" . $fid;
    $re = $db->sql_query( $query );
    if ( $db->sql_numrows( $re ) )
    {
        list( $active ) = $db->sql_fetchrow( $re );
        echo $active;
        if ( $active == '1' )
        {
            $active = '0';
        }
        else if ( $active == '0' ) $active = '1';
        
        $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET active ='" . $active . "' WHERE id = '" . $fid . "'";
        $db->sql_query( $query );
    }
}

$url_link = "index.php?" . NV_NAME_VARIABLE . "=" . $module_data;
Header( "Location:" . $url_link );
exit();
?>