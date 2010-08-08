<?php

/**
 * @Project NUKEVIET CMS 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (@) 2010 VINADES. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

$page_title = $module_info['custom_title'];

$id = $nv_Request->get_int( 'id', 'get', 0 );

if ( ! $id )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_send` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if ( $numrows != 1 )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$row = $db->sql_fetchrow( $result );

$contact_allowed = nv_getAllowed();

if ( ! isset( $contact_allowed['view'][$row['cid']] ) or ! isset( $contact_allowed['reply'][$row['cid']] ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$is_read = intval( $row['is_read'] );
if ( ! $is_read )
{
    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_send` SET `is_read`=1 WHERE `id`=" . $id;
    $result = $db->sql_query( $sql );
    $is_read = 1;
}

$admin_name = $admin_info['full_name'];
if ( empty( $admin_name ) ) $admin_name = $admin_info['username'];
$mess_content = "";
$contents = "";
if ( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
    $mess_content = nv_editor_filter_textarea( 'mess_content', '', NV_ALLOWED_HTML_TAGS, true );
    
    if ( strip_tags( $mess_content ) != "" )
    {
        list( $from ) = $db->sql_fetchrow( $db->sql_query( "SELECT `email` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $row['cid'] ) );
        if ( nv_check_valid_email( $from ) != "" )
        {
            $from = $admin_info['email'];
        }
        
        $from = array( 
            $admin_name, $from 
        );
        
        $subject = "Re: " . $row['title'];
        
        if ( nv_sendmail( $from, $row['sender_email'], $subject, $mess_content ) )
        {
            $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_send` SET `is_reply`=1, 
            `reply_content`=" . $db->dbescape( $mess_content ) . ", 
            `reply_time`=" . NV_CURRENTTIME . ", `reply_aid`=" . $admin_info['admin_id'] . " WHERE `id`=" . $id;
            $result = $db->sql_query( $sql );
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=view&id=" . $id );
            die();
        }
        else
        {
            $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
            $contents .= "<blockquote class=\"error\"><span>" . $lang_global['error_sendmail_admin'] . "</span></blockquote>\n";
            $contents .= "</div>\n";
            $contents .= "<div class=\"clear\"></div>\n";
        }
    }

}
else
{
    $mess_content .= "<br /><br />--<br />Best regards,<br /><br />" . $admin_name . "<br />";
    if ( ! empty( $admin_info['position'] ) )
    {
        $mess_content .= $admin_info['position'] . "<br />";
    }
    $mess_content .= "<br />";
    $mess_content .= "E-mail: " . $admin_info['email'] . "<br />";
    $mess_content .= "Website: " . $global_config['site_name'] . "<br />" . $global_config['site_url'] . "<br /><br />";
    
    $mess_content .= "--------------------------------------------------------------------------------<br />";
    $mess_content .= "<strong>From:</strong> " . $row['sender_name'] . " [mailto:" . $row['sender_email'] . "]<br />";
    $mess_content .= "<strong>Sent:</strong> " . date( "r", $row['send_time'] ) . "<br />";
    $mess_content .= "<strong>To:</strong> " . $contact_allowed['view'][$row['cid']] . "<br />";
    $mess_content .= "<strong>Subject:</strong> " . $row['title'] . "<br /><br />";
    $mess_content .= $row['content'];
}

$mess_content = nv_htmlspecialchars( $mess_content );

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id . "\" method=\"post\">";
$contents .= "<input name=\"save\" type=\"hidden\" value=\"1\" />\n";
$contents .= "<div style=\"margin-top:8px;margin-bottom:8px;\">\n";
if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
    $contents .= nv_aleditor( "mess_content", '720px', '300px', $mess_content );
}
else
{
    $contents .= "<textarea style=\"width: 720px\" name=\"mess_content\" id=\"mess_content\" cols=\"20\" rows=\"8\">" . $mess_content . "</textarea>";
}
$contents .= "</div>";
$contents .= "<br />\n";
$contents .= "<div><input name=\"submit1\" type=\"submit\" value=\"" . $lang_module['send_title'] . "\" /></div>\n";
$contents .= "</form>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>