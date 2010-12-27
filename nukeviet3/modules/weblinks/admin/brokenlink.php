<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['weblink_link_broken'];

$contents = "<div id=\"list_mods\">";
$numcat = $db->sql_numrows( $db->sql_query( "SELECT a.id FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_report` b ON a.id=b.id" ) );

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
$all_page = ( $numcat > 1 ) ? $numcat : 1;
$per_page = 10;
$page = $nv_Request->get_int( 'page', 'get', 0 );

$sqlcat = "SELECT a.url,a.title,b.type,a.id FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_report` b ON a.id=b.id LIMIT $page,$per_page"; //GROUP BY a.url 
$resultcat = $db->sql_query( $sqlcat );
if ( $numcat > 0 )
{
    $contents .= "<form name='delbroken' method='post' action='" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&op=delbroken'><table class=\"tab1\">\n";
    $contents .= "<caption>" . $lang_module['weblink_link_recent'] . "</caption>\n";
    $contents .= "<thead>\n";
    $contents .= "<tr>\n";
    $contents .= "<td><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);\" /></td>\n";
    $contents .= "<td>" . $lang_module['weblink_add_title'] . "</td>\n";
    $contents .= "<td>" . $lang_module['weblink_add_url'] . "</td>\n";
    $contents .= "<td>" . $lang_module['weblink_link_broken_status'] . "</td>\n";
    $contents .= "<td>" . $lang_module['weblink_inhome'] . "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</thead>\n";
    $a = 0;
    while ( $rowcat = $db->sql_fetchrow( $resultcat ) )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $contents .= "<tbody" . $class . ">\n";
        $contents .= "<tr>\n";
        $contents .= "<td><input type='checkbox' name='idcheck[]' value='" . $rowcat['id'] . "'></td>\n";
        $contents .= "<td>" . $rowcat['title'] . "</td>\n";
        $contents .= "<td>" . $rowcat['url'] . "</td>\n";
        $contents .= "<td>" . ( ( $rowcat['type'] == 1 ) ? $lang_module['weblink_link_broken_die'] : $lang_module['weblink_link_broken_bad'] ) . "</td>\n";
        $contents .= "<td><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $rowcat['id'] . "\">" . $lang_module['weblink_method_edit'] . "</a></span></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</tbody>\n";
        $a ++;
    }
    $contents .= "<tr><td colspan='8'>";
    $contents .= '<input type="submit" value="' . $lang_module['link_broken_out'] . '"></td></tr>';
    $contents .= "</table></form>\n";
}
$contents .= "</div>\n";
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
if ( $generate_page != "" ) $contents .= "<br><p align=\"center\">" . $generate_page . "</p>\n";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>