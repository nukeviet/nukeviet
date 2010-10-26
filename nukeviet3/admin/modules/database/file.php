<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:51
 */

if ( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

$log_dir = NV_ROOTDIR . "/" . NV_LOGS_DIR . "/dump_backup";

$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<thead>";
$contents .= "<tr align=\"center\">\n";
$contents .= "<td>" . $lang_module['file_nb'] . "</td>\n";
$contents .= "<td>" . $lang_module['file_name'] . "</td>\n";
$contents .= "<td>" . $lang_module['file_site'] . "</td>\n";
$contents .= "<td>" . $lang_module['file_time'] . "</td>\n";
$contents .= "<td></td>\n";
$contents .= "</tr>";
$contents .= "</thead>";
$array_content = $array_time = array();
$files = scandir( $log_dir );
foreach ( $files as $file )
{
    unset( $mc );
    $global_config['check_op_layout'] = "/^layout\.([a-zA-Z0-9\-\_]+)\.tpl$/";
    if ( preg_match( "/^([a-zA-Z0-9]+)\_([a-zA-Z0-9\-\_]+)\.(sql|sql\.gz)+$/", $file, $mc ) )
    {
        $filesize = filesize( $log_dir . '/' . $file );
        $filetime = intval( filemtime( $log_dir . '/' . $file ) );
        $array_time[] = $filetime;
        $array_content[$filetime] = array( 
            "file" => $file, 'mc' => $mc, "filesize" => $filesize 
        );
    
    }
}
sort( $array_time );

$a = 1;
for ( $index = count( $array_time ) - 1; $index >= 0; $index -- )
{
    $filetime = $array_time[$index];
    $value = $array_content[$filetime];
    $file = $value['file'];
    $mc = $value['mc'];
    
    $link_getfile = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=getfile&amp;filename=" . $file . "&amp;checkss=" . md5( $file . $client_info['session_id'] . $global_config['sitekey'] );
    $link_delete = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=delfile&amp;filename=" . $file . "&amp;checkss=" . md5( $file . $client_info['session_id'] . $global_config['sitekey'] );
    
    $class = ( $a % 2 == 0 ) ? "" : " class=\"second\"";
    $contents .= "<tbody" . $class . ">";
    $contents .= "<tr>\n";
    $contents .= "<td align=\"center\">" . $a ++ . "</td>\n";
    $contents .= "<td>" . $mc[2] . "." . $mc[3] . "</td>\n";
    $contents .= "<td align=\"right\">" . nv_convertfromBytes( $value['filesize'] ) . "</td>\n";
    $contents .= "<td align=\"right\">" . nv_date( "l d-m-Y h:i:s A", $filetime ) . "</td>\n";
    $contents .= "<td align=\"center\">
        				<span class=\"default_icon\"><a href=\"" . $link_getfile . "\">" . $lang_module['download'] . "</a></span>
        				 - 
        				<span class=\"delete_icon\"><a onclick=\"return confirm(nv_is_del_confirm[0]);\" href=\"" . $link_delete . "\">" . $lang_global['delete'] . "</a></span>
        				</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
}

$contents .= "</table>\n";
$page_title = $lang_module['file_backup'];
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>