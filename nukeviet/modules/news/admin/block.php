<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['block'];
$set_active_op = "blockcat";

$sql = "SELECT bid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );
if ( $num > 0 )
{
    $array_block = array();
    while ( list( $bid_i, $title_i ) = $db->sql_fetchrow( $result ) )
    {
        $array_block[$bid_i] = $title_i;
    }
}
else
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=blockcat" );
}

$cookie_bid = $nv_Request->get_int( 'int_bid', 'cookie', 0 );
if ( empty( $cookie_bid ) or ! isset( $array_block[$cookie_bid] ) )
{
    $cookie_bid = 0;
}

$bid = $nv_Request->get_int( 'bid', 'get,post', $cookie_bid );
if ( ! in_array( $bid, array_keys( $array_block ) ) )
{
    $bid_array_id = array_keys( $array_block );
    $bid = $bid_array_id[0];
}

if ( $cookie_bid != $bid )
{
    $nv_Request->set_Cookie( 'int_bid', $bid, NV_LIVE_COOKIE_TIME );
}
$page_title = $array_block[$bid];

if ( $nv_Request->isset_request( 'checkss,idcheck', 'post' ) and $nv_Request->get_string( 'checkss', 'post' ) == md5( session_id() . $bid ) )
{
    $id_array = array_map( "intval", $nv_Request->get_array( 'idcheck', 'post' ) );
    foreach ( $id_array as $id )
    {
        $db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_block` (`bid`, `id`, `weight`) VALUES ('" . $bid . "', '" . $id . "', '0')" );
    }
    nv_news_fix_block( $bid );
    nv_del_moduleCache( $module_name );
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&bid=" . $bid);
    die();
}

$select_options = array();
foreach ( $array_block as $xbid => $blockname )
{
    $select_options[NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;bid=" . $xbid] = $blockname;
}
$contents = "<div id=\"module_show_list\">";
$contents .= nv_show_block_list( $bid );
$contents .= "</div><br />\n";

$contents .= "<div id=\"add\">";
$id_array = array();
$listid = $nv_Request->get_string( 'listid', 'get', '' );
if ( $listid == "" )
{
    $sql = "SELECT id, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `status`=1 AND `id` NOT IN(SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` WHERE `bid`=" . $bid . ") ORDER BY `publtime` DESC LIMIT 0,20";
}
else
{
    $id_array = array_map( "intval", explode( ",", $listid ) );
    $sql = "SELECT id, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `status`=1 AND `id` IN (" . implode( ",", $id_array ) . ") ORDER BY `publtime` DESC";
}

$result = $db->sql_query( $sql );
if ( $db->sql_numrows( $result ) )
{
    $contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
    $contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
    $contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
    $contents .= "<table class=\"tab1\">\n";
    $contents .= " <caption>" . $lang_module['addtoblock'] . "</caption>\n";
    $contents .= "<thead>\n";
    $contents .= "<tr>\n";
    $contents .= "<td align=\"center\" width=\"60\"><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);\" /></td>\n";
    $contents .= "<td>" . $lang_module['name'] . "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</thead>\n";
    $a = 0;
    while ( list( $id, $title ) = $db->sql_fetchrow( $result ) )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $contents .= "<tbody" . $class . ">\n";
        $contents .= "<tr>\n";
        $contents .= "<td align=\"center\"><input type=\"checkbox\" onclick=\"nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);\" value=\"" . $id . "\" name=\"idcheck[]\" " . ( in_array( $id, $id_array ) ? "checked" : "" ) . "></td>\n";
        $contents .= "<td>" . $title . "</td>\n";
        $contents .= "</tr>\n";
        $contents .= "</tbody>\n";
        ++$a;
    }
    $contents .= "<tfoot>\n";
    $contents .= "<tr align=\"left\">\n";
    $contents .= "<td align=\"center\"><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);\" /></td>\n";
    $contents .= "<td>";
    $contents .= "<select name=\"bid\">\n";
    foreach ( $array_block as $xbid => $blockname )
    {
        $sl = ( $xbid == $bid ) ? " selected" : "";
        $contents .= "<option value=\"" . $xbid . "\" " . $sl . ">" . $blockname . "</option>\n";
    }
    $contents .= "</select><input type=\"hidden\" name =\"checkss\"value=\"" . md5( session_id() . $bid ) . "\" />";
    $contents .= "<input name=\"submit1\" type=\"submit\" value=\"" . $lang_module['save'] . "\" />\n";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tfoot>\n";
    $contents .= "</table>\n";
    $contents .= "</form>\n";
}
$contents .= "</div>";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>