<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['link_list'];

$contents = "<div id=\"list_mods\">";
list( $all_page ) = $db->sql_fetchrow( $db->sql_query( "SELECT count(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows`" ) );
if ( empty( $all_page ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
    exit();
}
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
$per_page = 10;
$page = $nv_Request->get_int( 'page', 'get', 0 );

$sqlcat = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` ORDER BY id DESC LIMIT $page,$per_page";
$resultcat = $db->sql_query( $sqlcat );

$contents .= "<form name=\"listlink\" method=\"post\" action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&op=multidel\"><table class=\"tab1\">\n";
$contents .= "<caption>" . $lang_module['weblink_link_recent'] . "</caption>\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);\" /></td>\n";
$contents .= "<td>" . $lang_module['weblink_add_title'] . "</td>\n";
$contents .= "<td>" . $lang_module['weblink_parent'] . "</td>\n";
$contents .= "<td>" . $lang_module['weblink_add_url'] . "</td>\n";
$contents .= "<td>" . $lang_module['weblink_link_time'] . "</td>\n";
$contents .= "<td>" . $lang_module['weblink_add_click'] . "</td>\n";
$contents .= "<td>" . $lang_module['weblink_inhome'] . "</td>\n";
$contents .= "<td>" . $lang_module['weblink_method'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$a = 0;
while ( $rowcat = $db->sql_fetchrow( $resultcat ) )
{
    $sqlparent = "SELECT title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE catid='" . intval( $rowcat['catid'] ) . "'";
    $resultparent = $db->sql_query( $sqlparent );
    $rowparent = $db->sql_fetchrow( $resultparent );
    
    $class = ( $a % 2 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td><input type=\"checkbox\" onclick=\"nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);\" value=\"" . $rowcat['id'] . "\" name=\"idcheck[]\"></td>\n";
    $contents .= "<td>" . $rowcat['title'] . "</td>\n";
    $contents .= "<td>" . ( ( $rowcat['catid'] != 0 ) ? $rowparent['title'] : $lang_module['weblink_parent'] ) . "</td>\n";
    $contents .= "<td>" . $rowcat['url'] . "</td>\n";
    $contents .= "<td>" . date( 'd-m-Y', $rowcat['add_time'] ) . "</td>\n";
    $contents .= "<td>" . $rowcat['hits_total'] . "</td>\n";
    $contents .= "<td>" . ( ( $rowcat['status'] == 1 ) ? $lang_module['weblink_yes'] : $lang_module['weblink_no'] ) . "</td>\n";
    $contents .= "<td><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $rowcat['id'] . "\">" . $lang_module['weblink_method_edit'] . "</a></span>\n";
    $contents .= "&nbsp;-&nbsp;<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del_link&amp;id=" . $rowcat['id'] . "\">" . $lang_module['weblink_method_del'] . "</a></span></td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $a ++;
}
$contents .= "<tr><td colspan='8'>";
$contents .= "<input type=\"submit\" value=\"" . $lang_module['weblink_method_del'] . "\"></td></tr>";
$contents .= "</table></form>\n";
$contents .= "</div>\n";
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
if ( $generate_page != "" ) $contents .= "<br><p align=\"center\">" . $generate_page . "</p>\n";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>