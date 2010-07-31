<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:49
 */

if ( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

$tables = $nv_Request->get_array( 'tables', 'post', array() );
$type = filter_text_input( 'type', 'post', '' );
$ext = filter_text_input( 'ext', 'post', '' );

if ( empty( $tables ) )
{
    $tables = array();
}
elseif ( ! is_array( $tables ) )
{
    $tables = array( 
        $tables 
    );
}

$tab_list = array();
$result = $db->sql_query( "SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'" );
while ( $item = $db->sql_fetchrow( $result ) )
{
    $tab_list[] = $item[0];
}
$db->sql_freeresult( $result );

$contents = array();
$contents['tables'] = ( empty( $tables ) ) ? $tab_list : array_values( array_intersect( $tab_list, $tables ) );
$contents['type'] = ( $type != "str" ) ? "all" : "str";
$contents['savetype'] = ( $ext != "sql" ) ? "gz" : "sql";
$file_name = "backupdata_" . date( "Y-m-d-H-i", time() ) . "." . $contents['savetype'];
$contents['filename'] = NV_ROOTDIR . "/" . NV_LOGS_DIR . "/dump_backup/" . $file_name;
include ( NV_ROOTDIR . "/includes/core/dump.php" );
$result = nv_dump_save( $contents );
if ( empty( $result ) )
{
    $content = sprintf( $lang_module['save_error'], NV_LOGS_DIR . "/dump_backup" );
}
else
{
    $content = $lang_module['save_ok'];
    $content .= "<br><br><a href=\"" . NV_BASE_SITEURL . "" . str_replace( NV_ROOTDIR . "/", "", $contents['filename'] ) . "\">" . $lang_module['save_download'] . "</a>";
}
$page_title = $lang_module['save_data'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( "<br><br><br><center><b>" . $content . "</b></center>" );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>