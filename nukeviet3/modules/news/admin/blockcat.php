<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['block'];

$error = "";
$savecat = 0;
list( $bid, $title, $alias, $description, $keywords ) = array( 
    0, "", "", "", "" 
);

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if ( ! empty( $savecat ) )
{
    $bid = $nv_Request->get_int( 'bid', 'post', 0 );
    $title = filter_text_input( 'title', 'post', '', 1 );
    $keywords = filter_text_input( 'keywords', 'post', '', 1 );
    $alias = filter_text_input( 'alias', 'post', '' );
    $description = $nv_Request->get_string( 'description', 'post', '' );
    $description = nv_nl2br( nv_htmlspecialchars( strip_tags( $description ) ), '<br />' );
    $alias = ( $alias == "" ) ? change_alias( $title ) : change_alias( $alias );
    if ( $bid == 0 )
    {
        list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat`" ) );
        $weight = intval( $weight ) + 1;
        $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` (`bid`, `adddefault`, `number`, `title`, `alias`, `description`, `image`, `thumbnail`, `weight`, `keywords`, `add_time`, `edit_time`) VALUES (NULL, 0, 4, " . $db->dbescape( $title ) . ", " . $db->dbescape( $alias ) . ", " . $db->dbescape( $description ) . ", '', '', " . $db->dbescape( $weight ) . ", " . $db->dbescape( $keywords ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ))";
        if ( $db->sql_query_insert_id( $query ) )
        {
            nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_blockcat', " ", $admin_info['userid'] );
            $db->sql_freeresult();
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "" );
            die();
        }
        else
        {
            $error = $lang_module['errorsave'];
        }
    }
    else
    {
        $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` SET `title`=" . $db->dbescape( $title ) . ", `alias` =  " . $db->dbescape( $alias ) . ", `description`=" . $db->dbescape( $description ) . ", `keywords`= " . $db->dbescape( $keywords ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `bid` =" . $bid . "";
        $db->sql_query( $query );
        if ( $db->sql_affectedrows() > 0 )
        {
            nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_blockcat', "blockid " . $bid, $admin_info['userid'] );
            $db->sql_freeresult();
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "" );
            die();
        }
        else
        {
            $error = $lang_module['errorsave'];
        }
        $db->sql_freeresult();
    }
}
$contents = "<div id=\"module_show_list\">";
$contents .= nv_show_block_cat_list();
$contents .= "</div><br />\n";
$bid = $nv_Request->get_int( 'bid', 'get', 0 );
if ( $bid > 0 )
{
    list( $bid, $title, $alias, $description, $keywords ) = $db->sql_fetchrow( $db->sql_query( "SELECT `bid`, `title`, `alias`, `description`, `keywords`  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` where `bid`=" . $bid . "" ) );
    $lang_module['add_block_cat'] = $lang_module['edit_block_cat'];
}
$contents .= "<a id=\"edit\"></a>";
if ( $error != "" )
{
    $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $contents .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n";
    $contents .= "</div>\n";
    $contents .= "<div class=\"clear\"></div>\n";
}

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"bid\" value=\"" . $bid . "\" />";
$contents .= "<input name=\"savecat\" type=\"hidden\" value=\"1\" />\n";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<caption>" . $lang_module['add_block_cat'] . "</caption>\n";
$contents .= "<tbody>";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['name'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"title\" id=\"idtitle\" type=\"text\" value=\"" . $title . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";

$contents .= "<tr>";
$contents .= "<td valign=\"top\" align=\"right\"  width=\"100\"><strong>" . $lang_module['alias'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 600px\" name=\"alias\" id=\"idalias\" type=\"text\" value=\"" . $alias . "\" maxlength=\"255\" />\n";
$contents .= "		<img src=\"" . NV_BASE_SITEURL . "images/refresh.png\" width=\"16\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"get_alias('blockcat', " . $bid . ");\" alt=\"\" height=\"16\" />\n";
$contents .= "</td>\n";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "<tbody class=\"second\">";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['keywords'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"keywords\" type=\"text\" value=\"" . $keywords . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "<tbody>";
$contents .= "<tr>";
$contents .= "<td valign=\"top\" align=\"right\"  width=\"100\"><br /><strong>" . $lang_module['description'] . "</strong></td>\n";
$contents .= "<td>";
$contents .= "<textarea style=\"width: 650px\" name=\"description\" cols=\"100\" rows=\"5\">" . $description . "</textarea>";
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "</table>";
$contents .= "<br /><center><input name=\"submit1\" type=\"submit\" value=\"" . $lang_module['save'] . "\" /></center>\n";
$contents .= "</form>\n";
if ( empty( $alias ) )
{
    $contents .= "<script type=\"text/javascript\">\n";
    $contents .= '$("#idtitle").change(function () {
                    get_alias("blockcat", ' . $bid . ');
                });';
    $contents .= "</script>\n";
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>