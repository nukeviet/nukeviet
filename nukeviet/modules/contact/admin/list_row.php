<?php

/**
 * @Project NUKEVIET CMS 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright 2010 VINADES. All rights reserved
 * @Createdate Apr 22, 2010 3:00:20 PM
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` ORDER BY `full_name`";
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if ( ! $numrows )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=row" );
    die();
}

$page_title = $lang_module['list_row_title'];

$contents = "";

$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['part_row_title'] . "</td>\n";
$contents .= "<td>" . $lang_global['email'] . "</td>\n";
$contents .= "<td>" . $lang_global['phonenumber'] . "</td>\n";
$contents .= "<td>Fax</td>\n";
$contents .= "<td>" . $lang_global['status'] . "</td>\n";
$contents .= "<td>" . $lang_global['actions'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";

$a = 0;

while ( $row = $db->sql_fetchrow( $result ) )
{
    $class = ( $a % 2 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $row['full_name'] . "</td>\n";
    $contents .= "<td>" . $row['email'] . "</td>\n";
    $contents .= "<td>" . $row['phone'] . "</td>\n";
    $contents .= "<td>" . $row['fax'] . "</td>\n";
    $contents .= "<td><select id=\"change_status_" . $row['id'] . "\" onchange=\"nv_chang_status('" . $row['id'] . "');\">\n";
    $array = array( $lang_global['disable'], $lang_global['active'] );
    foreach ( $array as $key => $val )
    {
        $contents .= "<option value=\"" . $key . "\"" . ( $key == $row['act'] ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
    }
    $contents .= "</select></td>\n";

    $contents .= "<td><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=row&amp;id=" . $row['id'] . "\">" . $lang_global['edit'] .
        "</a></span>\n";
    $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_row_del(" . $row['id'] . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    ++$a;
}

$contents .= "</table>\n";
$contents .= "<div style=\"margin-top:8px;float:right\">\n";
$contents .= "<a class=\"button1\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=row\">\n";
$contents .= "<span><span>" . $lang_module['add_row_title'] . "</span></span></a></div>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>