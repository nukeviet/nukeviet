<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['comment_delete_title'];
$idlist = $nv_Request->get_string( 'list', 'post,get' );
$array_id = explode( ',', $idlist );
$array_id = array_map( "intval", $array_id );
foreach ( $array_id as $tid )
{
    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE tid='$tid'";
    $result = $db->sql_query( $sql );
}
echo $lang_module['comment_delete_success'];
?>