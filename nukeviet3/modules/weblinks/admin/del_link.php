<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['weblink_del_link_title'];
$id = ( $nv_Request->get_int( 'id', 'get' ) > 0 ) ? $nv_Request->get_int( 'id', 'post,get' ) : 0;
nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_link', "id ".$id, $admin_info['userid'] );
if ( empty( $id ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
}
$submit = $nv_Request->get_string( 'submit', 'post' );
if ( ! empty( $submit ) )
{
    $confirm = $nv_Request->get_int( 'confirm', 'post' );
    if ( $confirm == 1 )
    {
        $query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE id=" . $id . "";
        if ( $db->sql_query( $query ) )
        {
            $db->sql_freeresult();
            $msg = $lang_module['weblink_del_success'];
        }
        else
        {
            $msg = $lang_module['weblink_del_error'];
        }
        if ( $msg != '' )
        {
            $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
            $contents .= "<blockquote class=\"error\"><span>" . $msg . "</span></blockquote>\n";
            $contents .= "</div>\n";
            $contents .= "<div class=\"clear\"></div>\n";
        }
    }
    else
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
    }
}
else
{
    $contents .= "<div id=\"list_mods\">";
    $contents .= "<form name=\"del_link\" action=\"\" method=\"post\">";
    $contents .= "<table summary=\"\" class=\"tab1\">\n";
    $contents .= "<input type=\"hidden\" name=\"id\" value=\"" . $id . "\">";
    $contents .= "<tr>";
    $contents .= "<td width=\"50px\" style=\"padding-left:60px\">" . $lang_module['weblink_del_link_confirm'] . "</td>\n";
    $contents .= "</tr>";
    $contents .= "<tr>";
    $contents .= "<td width=\"50px\" style=\"padding-left:80px\">\n";
    $contents .= "<label><input name=\"confirm\" type=\"radio\" value=\"1\" id=\"confirm_0\">" . $lang_module['weblink_yes'] . "</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input name=\"confirm\" type=\"radio\" value=\"0\" id=\"confirm_1\"/>" . $lang_module['weblink_no'] . "</label></td>\n";
    $contents .= "</tr>";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $contents .= "<td align=\"left\"><input name=\"submit\" style=\"width:80px;margin-left:80px\" type=\"submit\" value=\"" . $lang_module['weblink_submit'] . "\" /></td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "</table>";
    $contents .= "</form>";
    $contents .= "</div>";
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>