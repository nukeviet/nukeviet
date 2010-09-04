<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_RSS_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['content'];

if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . 'Content.txt';

if ( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
    $bodytext = nv_editor_filter_textarea( 'bodytext', '', NV_ALLOWED_HTML_TAGS, true );
    file_put_contents( $content_file, $bodytext );

    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
    die();
}

$bodytext = "";
if ( file_exists( $content_file ) )
{
    $bodytext = file_get_contents( $content_file );
    $bodytext = nv_editor_br2nl( $bodytext );
}

$is_edit = $nv_Request->get_int( 'is_edit', 'get', 0 );
if ( empty( $bodytext ) ) $is_edit = 1;

if ( $is_edit )
{
    if ( ! empty( $bodytext ) ) $bodytext = nv_htmlspecialchars( $bodytext );
    $contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "\" method=\"post\">";
    $contents .= "<input name=\"save\" type=\"hidden\" value=\"1\" />\n";
    $contents .= "<div style=\"margin-top:8px;margin-bottom:8px;\">\n";
    if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
    {
        $contents .= nv_aleditor( "bodytext", '720px', '300px', $bodytext );
    }
    else
    {
        $contents .= "<textarea style=\"width: 720px\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"8\">" . $bodytext . "</textarea>";
    }
    $contents .= "</div>";
    $contents .= "<br />\n";
    $contents .= "<div style=\"text-align:center\"><input name=\"submit1\" type=\"submit\" value=\"" . $lang_module['save'] . "\" /></div>\n";
    $contents .= "</form>\n";
}
else
{
    $contents .= "<div style=\"margin-top:8px;position:absolute;right:10px;\">\n";
    $contents .= "<a class=\"button1\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;is_edit=1\">\n";
    $contents .= "<span><span>" . $lang_global['edit'] . "</span></span></a></div>\n";
    $contents .= "<div style=\"margin-bottom:20px;\">\n";
    $contents .= $bodytext . "</div>";
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>