<?php

/**
 * @Project NUKEVIET CMS 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$contents = "";

$contact_allowed = nv_getAllowed();

if ( ! empty( $contact_allowed['view'] ) )
{
    $in = implode( ",", array_keys( $contact_allowed['view'] ) );
    $sql = "`" . NV_PREFIXLANG . "_" . $module_data . "_send` WHERE `cid` IN (" . $in . ")";
    
    $page = $nv_Request->get_int( 'page', 'get', 0 );
    $per_page = 30;
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
    $query = "SELECT SQL_CALC_FOUND_ROWS * FROM " . $sql . " ORDER BY `id` DESC LIMIT " . $page . "," . $per_page;
    $result = $db->sql_query( $query );
    
    $result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
    list( $all_page ) = $db->sql_fetchrow( $result_all );
    
    if ( $all_page )
    {
        $contents .= "<form name=\"myform\" id=\"myform\" method=\"post\" action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=del&amp;t=2\">\n";
        $contents .= "<table summary=\"\" class=\"tab1\">\n";
        $contents .= "<col valign=\"middle\" width=\"10px\" />\n";
        $contents .= "<col valign=\"middle\" width=\"13px\" />\n";
        $contents .= "<col style=\"white-space:nowrap;width:50px\" />\n";
        $contents .= "<col style=\"white-space:nowrap;width:50px\" />\n";
        $contents .= "<col style=\"white-space:nowrap;\" />\n";
        $contents .= "<col width=\"45px\" />\n";
        $contents .= "<thead>\n";
        $contents .= "<td><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'sends[]', 'check_all[]',this.checked);\" /></td>\n";
        $contents .= "<td colspan=\"2\">" . $lang_module['name_user_send_title'] . "</td>\n";
        $contents .= "<td>" . $lang_module['part_row_title'] . "</td>\n";
        $contents .= "<td>" . $lang_module['title_send_title'] . "</td>\n";
        $contents .= "<td></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        
        $contents .= "<tfoot>\n";
        $contents .= "<tr>\n";
        $contents .= "<td><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'sends[]', 'check_all[]',this.checked);\" /></td>\n";
        $contents .= "<td colspan=\"5\">\n";
        $contents .= "<a href=\"javascript:void(0);\" onclick=\"nv_del_submit(document.myform, 'sends[]');\">" . $lang_module['bt_del_row_title'] . "</a>, \n";
        $contents .= "<a href=\"javascript:void(0)\" onclick=\"nv_delall_submit();\">" . $lang_module['delall'] . "</a></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</tfoot>\n";
        
        $a = 0;
        
        $currday = mktime( 0, 0, 0, date( "n" ), date( "j" ), date( "Y" ) );
        
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $image = array( 
                NV_BASE_SITEURL . 'images/mail_new.gif', 12, 9 
            );
            $status = "New";
            $style = " style=\"font-weight:bold;cursor:pointer;white-space:nowrap;\"";
            if ( $row['is_read'] == 1 )
            {
                $image = array( 
                    NV_BASE_SITEURL . 'images/mail_old.gif', 12, 11 
                );
                $status = $lang_module['tt1_row_title'];
                $style = " style=\"cursor:pointer;white-space:nowrap;\"";
            }
            if ( $row['is_reply'] )
            {
                $image = array( 
                    NV_BASE_SITEURL . 'images/mail_reply.gif', 13, 14 
                );
                $status = $lang_module['tt2_row_title'];
                $style = " style=\"cursor:pointer;white-space:nowrap;\"";
            }
            $onclick = "onclick=\"location.href='" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=view&amp;id=" . $row['id'] . "'\"";
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td><input name=\"sends[]\" type=\"checkbox\" value=\"" . $row['id'] . "\" onclick=\"nv_UncheckAll(this.form, 'sends[]', 'check_all[]', this.checked);\" /></td>\n";
            $contents .= "<td" . $style . " " . $onclick . "><img alt=\"" . $status . "\" title=\"" . $status . "\" src=\"" . $image[0] . "\" width=\"" . $image[1] . "\" height=\"" . $image[2] . "\" /></td>\n";
            $contents .= "<td" . $style . " " . $onclick . ">" . $row['sender_name'] . "</td>\n";
            $contents .= "<td" . $style . " " . $onclick . ">" . $contact_allowed['view'][$row['cid']] . "</td>\n";
            $contents .= "<td" . $style . " " . $onclick . ">" . nv_clean60( $row['title'], 60 ) . "</td>\n";
            $contents .= "<td" . $style . " " . $onclick . " style=\"text-align:right\">" . ( $row['send_time'] >= $currday ? nv_date( "H:i", $row['send_time'] ) : nv_date( "d/m", $row['send_time'] ) ) . "</td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        
        $contents .= "</table>\n";
        $contents .= "</form>\n";
        
        $generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
        if ( ! empty( $generate_page ) )
        {
            $contents .= "<br />\n";
            $contents .= "<div>" . $generate_page . "</div>\n";
        }
    }
}
if ( empty( $contents ) )
{
    $contents .= "<br />\n";
	$contents .= "<br /><center><b>\n";
    $contents .= $lang_module['no_row_contact'];
    $contents .= "</b></center><br />\n";
    $contents .= "<br />\n";
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>