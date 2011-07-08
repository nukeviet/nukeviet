<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['content_list'];

$stype = $nv_Request->get_string( 'stype', 'get', '-' );
$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$per_page_old = $nv_Request->get_int( 'per_page', 'cookie', 50 );
$per_page = $nv_Request->get_int( 'per_page', 'get', $per_page_old );
if ( $per_page < 1 and $per_page > 500 )
{
    $per_page = 50;
}
if ( $per_page_old != $per_page )
{
    $nv_Request->set_Cookie( 'per_page', $per_page, NV_LIVE_COOKIE_TIME );
}

$q = filter_text_input( 'q', 'get', '', 1 );
$ordername = $nv_Request->get_string( 'ordername', 'get', 'publtime' );
$order = $nv_Request->get_string( 'order', 'get' ) == "asc" ? 'asc' : 'desc';

$array_search = array( 
    "-" => "---", "title" => $lang_module['search_title'], "bodytext" => $lang_module['search_bodytext'], "author" => $lang_module['search_author'], "admin_id" => $lang_module['search_admin'] 
);
$array_in_rows = array( 
    "" . NV_LANG_DATA . "_title", "" . NV_LANG_DATA . "_bodytext" 
);
$array_in_ordername = array( 
    "" . NV_LANG_DATA . "_title", "publtime", "exptime" 
);
if ( ! in_array( $stype, array_keys( $array_search ) ) )
{
    $stype = "-";
}
if ( ! in_array( $ordername, array_keys( $array_in_ordername ) ) )
{
    $ordername = "id";
}

$from = "`" . $db_config['prefix'] . "_" . $module_data . "_rows`";

$page = $nv_Request->get_int( 'page', 'get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
if ( $checkss == md5( session_id() ) )
{
    if ( in_array( $stype, $array_in_rows ) and ! empty( $q ) )
    {
        $from .= " WHERE `" . $stype . "` LIKE '%" . $db->dblikeescape( $q ) . "%' ";
    }
    elseif ( $stype == "admin_id" and ! empty( $q ) )
    {
        $sql = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " where userid in (SELECT admin_id FROM " . NV_AUTHORS_GLOBALTABLE . ") AND `username` LIKE '%" . $db->dblikeescape( $q ) . "%' OR `full_name` LIKE '%" . $db->dblikeescape( $q ) . "%'";
        $result = $db->sql_query( $sql );
        $array_admin_id = array();
        while ( list( $admin_id ) = $db->sql_fetchrow( $result ) )
        {
            $array_admin_id[] = $admin_id;
        }
        $from .= " WHERE `admin_id` IN (0," . implode( ",", $array_admin_id ) . ",0)";
    }
    elseif ( ! empty( $q ) )
    {
        $sql = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " where userid in (SELECT admin_id FROM " . NV_AUTHORS_GLOBALTABLE . ") AND `username` LIKE '%" . $db->dblikeescape( $q ) . "%' OR `full_name` LIKE '%" . $db->dblikeescape( $q ) . "%'";
        $result = $db->sql_query( $sql );
        $array_admin_id = array();
        while ( list( $admin_id ) = $db->sql_fetchrow( $result ) )
        {
            $array_admin_id[] = $admin_id;
        }
        $arr_from = array();
        foreach ( $array_in_rows as $key => $val )
        {
            $arr_from[] = "(`" . $val . "` LIKE '%" . $db->dblikeescape( $q ) . "%')";
        }
        $from .= " WHERE " . implode( " OR ", $arr_from ) . "";
        if ( ! empty( $array_admin_id ) )
        {
            $from .= " OR (`admin_id` IN (0," . implode( ",", $array_admin_id ) . ",0))";
        }
    }
}

list( $all_page ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM " . $from ) );

$sql = "SELECT userid, username  FROM " . NV_USERS_GLOBALTABLE . " ";
$result = $db->sql_query( $sql );
$array_admin = array();
while ( list( $admin_id, $admin_login ) = $db->sql_fetchrow( $result ) )
{
    $array_admin[$admin_id] = $admin_login;
}

$reval = "";
$reval .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"GET\">";
$reval .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$reval .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$reval .= "<label>" . $lang_module['search_cat'] . ": </label>\n";
$reval .= "<select name=\"catid\">\n";
$sl = "";
if ( $catid == 0 )
{
    $sl = " selected=\"selected\"";
}
$reval .= "<option value=\"0\" " . $sl . ">" . $lang_module['search_cat_all'] . "</option>\n";

$global_array_cat = array();
$link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=Other";
$global_array_cat[0] = array( 
    "catid" => 0, "parentid" => 0, "title" => "Other", "alias" => "Other", "link" => $link_i, "viewcat" => "viewcat_page_new", "subcatid" => 0, "numlinks" => 3, "description" => "", "keywords" => "" 
);

$sql = "SELECT catid, parentid, " . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewcat, subcatid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, " . NV_LANG_DATA . "_keywords, lev FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
while ( list( $catid_i, $parentid_i, $title_i, $alias_i, $viewcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $keywords_i, $lev_i ) = $db->sql_fetchrow( $result ) )
{
    $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
    $global_array_cat[$catid_i] = array( 
        "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "keywords" => $keywords_i 
    );
    $xtitle_i = "";
    if ( $lev_i > 0 )
    {
        $xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
        for ( $i = 1; $i <= $lev_i; $i ++ )
        {
            $xtitle_i .= "---";
        }
        $xtitle_i .= "&nbsp;";
    }
    $xtitle_i .= $title_i;
    $sl = "";
    if ( $catid_i == $catid )
    {
        $sl = " selected=\"selected\"";
    }
    $reval .= "<option value=\"" . $catid_i . "\" " . $sl . ">" . $xtitle_i . "</option>\n";
}

$reval .= "</select> \n";
$reval .= " <label>" . $lang_module['search_type'] . ": </label>\n";
$reval .= "<select name=\"stype\">\n";
foreach ( $array_search as $key => $val )
{
    $reval .= "<option value=\"" . $key . "\"" . ( ( $key == $stype ) ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
}
$reval .= "</select>";

$i = 5;
$reval .= " <label>" . $lang_module['search_per_page'] . ": </label>\n";
$reval .= "<select name=\"per_page\">\n";
while ( $i <= 1000 )
{
    $reval .= "<option value=\"" . $i . "\"" . ( ( $i == $per_page ) ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
    $i = $i + 5;
}
$reval .= "</select>";
$reval .= "<br>\n";

$reval .= "" . $lang_module['search_key'] . ": <input type=\"text\" value=\"" . $q . "\" maxlength=\"64\" name=\"q\" style=\"width: 265px\">\n";
$reval .= "<input type=\"submit\" value=\"" . $lang_module['search'] . "\"><br>\n";
$reval .= "<input type=\"hidden\" name =\"checkss\"value=\"" . md5( session_id() ) . "\" />";
$reval .= "<label><em>" . $lang_module['search_note'] . "</em></label>\n";
$reval .= "</form>\n";

$a = 0;
$order2 = ( $order == "asc" ) ? "desc" : "asc";
$base_url_id = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&per_page=" . $per_page . "&catid=" . $catid . "&stype=" . $stype . "&q=" . $q . "&checkss=" . $checkss . "&ordername=id&order=" . $order2 . "&page=" . $page;
$base_url_name = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&per_page=" . $per_page . "&catid=" . $catid . "&stype=" . $stype . "&q=" . $q . "&checkss=" . $checkss . "&ordername=title&order=" . $order2 . "&page=" . $page;
$base_url_publtime = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&per_page=" . $per_page . "&catid=" . $catid . "&stype=" . $stype . "&q=" . $q . "&checkss=" . $checkss . "&ordername=publtime&order=" . $order2 . "&page=" . $page;
$base_url_exptime = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&per_page=" . $per_page . "&catid=" . $catid . "&stype=" . $stype . "&q=" . $q . "&checkss=" . $checkss . "&ordername=exptime&order=" . $order2 . "&page=" . $page;

$contents = "<form name=\"block_list\">";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<thead>";
$contents .= "<tr>\n";
$contents .= "<td  align=\"center\"><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);\" /></td>\n";
$contents .= "<td><a href=\"" . $base_url_name . "\">" . $lang_module['name'] . "</a></td>\n";
$contents .= "<td  align=\"center\"><a href=\"" . $base_url_publtime . "\">" . $lang_module['content_publ_date'] . "</a></td>\n";
$contents .= "<td  align=\"center\">" . $lang_module['status'] . "</td>\n";
$contents .= "<td  align=\"center\">" . $lang_module['content_admin'] . "</td>\n";
$contents .= "<td align=\"center\">" . $lang_module['content_product_number1'] . "</td>\n";
$contents .= "<td></td>\n";
$contents .= "</thead>";

$base_url = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&per_page=" . $per_page . "&catid=" . $catid . "&stype=" . $stype . "&q=" . $q . "&checkss=" . $checkss . "&ordername=" . $ordername . "&order=" . $order;
$ord_sql = "ORDER BY `" . $ordername . "` " . $order . "";
$sql = "SELECT id, listcatid, user_id, " . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, status , publtime, exptime,product_number FROM " . $from . " " . $ord_sql . " LIMIT " . $page . "," . $per_page;
//die($sql);
$result = $db->sql_query( $sql );
while ( list( $id, $listcatid, $admin_id, $title, $alias, $status, $publtime, $exptime,$product_number ) = $db->sql_fetchrow( $result ) )
{
    if ( $status == 0 )
    {
        $status = $lang_module['status_0'];
    }
    elseif ( $publtime < NV_CURRENTTIME and ( $exptime == 0 or $exptime > NV_CURRENTTIME ) )
    {
        $status = $lang_module['status_1'];
    }
    elseif ( $publtime > NV_CURRENTTIME )
    {
        $status = $lang_module['status_2'];
    }
    else
    {
        $status = $lang_module['status_3'];
    }
    $publtime = nv_date( "H:i d/m/y", $publtime );
    $title = nv_clean60( $title );
    $class = ( $a % 2 == 0 ) ? "" : " class=\"second\"";
    $catid_i = 0;
    if ( $catid > 0 )
    {
        $catid_i = $catid;
    }
    else
    {
        $listcatid_arr = explode( ",", $listcatid );
        $catid_i = $listcatid_arr[0];
    }
    $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id;
    $contents .= "<tbody" . $class . ">";
    $contents .= "<tr>\n";
    $contents .= "<td align=\"center\"><input type=\"checkbox\" onclick=\"nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);\" value=\"" . $id . "\" name=\"idcheck[]\"></td>\n";
    $contents .= "<td><a target=\"_blank\" href=\"" . $link . "\">" . $title . "</a></td>\n";
    $contents .= "<td align=\"center\">" . $publtime . "</td>\n";
    $contents .= "<td align=\"center\">" . $status . "</td>\n";
    $contents .= "<td>" . ( isset( $array_admin[$admin_id] ) ? $array_admin[$admin_id] : "" ) . "</td>\n";
    $contents .= "<td align=\"center\">".$product_number."</td>\n";
    $contents .= "<td>";
    $contents .= "     " . nv_link_edit_page( $id ) . "\n";
    $contents .= "     &nbsp;-&nbsp;";
    $contents .= "     " . nv_link_delete_page( $id ) . "\n";
    $contents .= "     </td>\n";
    $contents .= "</tbody>";
    $a ++;
}
$contents .= "<tfoot>\n";
$contents .= "<tr align=\"left\">\n";
$contents .= "<td colspan=\"7\">\n";
$contents .= "<select name=\"action\" id=\"action\">\n";
$array_list_action = array( 
    'delete' => $lang_global['delete'], 'publtime' => $lang_module['publtime'], 'exptime' => $lang_module['exptime'], 'addtoblock' => $lang_module['addtoblock'], 'addtotopics' => $lang_module['addtotopics'] 
);

while ( list( $catid_i, $title_i ) = each( $array_list_action ) )
{
    $contents .= "<option value=\"" . $catid_i . "\">" . $title_i . "</option>\n";
}
$contents .= "</select>\n";
$contents .= "<input type=\"button\" onclick=\"nv_main_action(this.form,'" . md5( $global_config['sitekey'] . session_id() ) . "','" . $lang_module['msgnocheck'] . "')\" value=\"" . $lang_module['action'] . "\">\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tfoot>\n";
$contents .= "</table>\n";
$contents .= "</form>\n";

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
if ( $generate_page != "" ) $contents .= "<br><p align=\"center\">" . $generate_page . "</p>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $reval . $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>