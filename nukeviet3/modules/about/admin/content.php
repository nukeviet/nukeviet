<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );

if ( $id )
{
    $query = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( empty( $numrows ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        die();
    }
    $row = $db->sql_fetchrow( $result );
    define( 'IS_EDIT', true );
    $page_title = $lang_module['aabout12'];
    $action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
}
else
{
    $page_title = $lang_module['aabout1'];
    $action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
}

$error = "";

if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

if ( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
    $title = filter_text_input( 'title', 'post', '', 1 );
    $alias = filter_text_input( 'alias', 'post', '', 1 );
    $bodytext = nv_editor_filter_textarea( 'bodytext', '', NV_ALLOWED_HTML_TAGS );

    if ( empty( $title ) )
    {
        $error = $lang_module['aabout9'];
    } elseif ( strip_tags( $bodytext ) == "" )
    {
        $error = $lang_module['aabout10'];
    }
    else
    {
        $bodytext = nv_editor_nl2br( $bodytext );
        $alias = empty( $alias ) ? change_alias( $title ) : change_alias( $alias );

        if ( defined( 'IS_EDIT' ) )
        {
            nv_delete_cache( array( //
                "/" . nv_preg_quote( NV_LANG_DATA . "_" . $module_name . "_" . $id . "_" . NV_CACHE_PREFIX . ".cache" ) . "/", //
                "/" . nv_preg_quote( NV_LANG_DATA . "_" . $module_name . "_" . NV_CACHE_PREFIX . ".cache" ) . "/" //
                ) );
            
            $query = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "` SET 
            `title`=" . $db->dbescape( $title ) . ", `alias` =  " . $db->dbescape( $alias ) . ", 
            `bodytext`=" . $db->dbescape( $bodytext ) . ", `keywords`='', `edit_time`=" . NV_CURRENTTIME . " WHERE `id` =" . $id;
        }
        else
        {
            list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "`" ) );
            $weight = intval( $weight ) + 1;

            $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` VALUES (
            NULL, " . $db->dbescape( $title ) . ", " . $db->dbescape( $alias ) . ", " . $db->dbescape( $bodytext ) . ", '', 
            " . $weight . ", " . $admin_info['admin_id'] . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 1);";
        }
        
        $db->sql_query( $query );

        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main" );
        die();
    }
}
else
{
    if ( defined( 'IS_EDIT' ) )
    {
        $title = $row['title'];
        $alias = $row['alias'];
        $bodytext = nv_editor_br2nl( $row['bodytext'] );
    }
    else
    {
        $title = $alias = $bodytext = "";
    }
}

if ( ! empty( $bodytext ) ) $bodytext = nv_htmlspecialchars( $bodytext );

if ( ! empty( $error ) )
{
    $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $contents .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n";
    $contents .= "</div>\n";
    $contents .= "<div class=\"clear\"></div>\n";
}

$contents .= "<form action=\"" . $action . "\" method=\"post\">\n";
$contents .= "<input name=\"save\" type=\"hidden\" value=\"1\" />\n";
$contents .= "<table summary=\"\" style=\"margin-top:8px;margin-bottom:8px;\">\n";
$contents .= "<col valign=\"top\" width=\"150px\" />\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['aabout2'] . ":</td>\n";
$contents .= "<td><input style=\"width:400px\" name=\"title\" id=\"title\" type=\"text\" value=\"" . $title . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['alias'] . ":</td>\n";
$contents .= "<td><input style=\"width:400px\" name=\"alias\" id=\"alias\" type=\"text\" value=\"" . $alias . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan=\"2\">" . $lang_module['aabout11'] . ":</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan=\"2\">\n";
if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
    $contents .= nv_aleditor( "bodytext", '750px', '300px', $bodytext );
}
else
{
    $contents .= "<textarea style=\"width:750px;height:300px\" name=\"bodytext\" id=\"bodytext\">" . $bodytext . "</textarea>";
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</table>\n";

$contents .= "<br>\n";
$contents .= "<div style=\"text-align:center\"><input name=\"submit1\" type=\"submit\" value=\"" . $lang_module['save'] . "\" /></div>\n";
$contents .= "</form>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>