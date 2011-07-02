<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
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

$val_cat_content = array();
$val_cat_content[] = array(
	"value" => 0,
	"selected" => ( $catid == 0 ) ? " selected=\"selected\"" : "",
	"title" => $lang_module['search_cat_all']
);

$array_cat_view = array();
foreach ( $global_array_cat as $catid_i => $array_value )
{
    $lev_i = $array_value['lev'];
    $check_cat = false;
    if ( defined( 'NV_IS_ADMIN_MODULE' ) )
    {
        $check_cat = true;
    }
    elseif ( isset( $array_cat_admin[$admin_id][$catid_i] ) )
    {
        if ( $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 )
        {
            $check_cat = true;
        }
        elseif ( $array_cat_admin[$admin_id][$catid_i]['add_content'] == 1 )
        {
            $check_cat = true;
        }
        elseif ( $array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 )
        {
            $check_cat = true;
        }
        elseif ( $array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1 )
        {
            $check_cat = true;
        }
        elseif ( $array_cat_admin[$admin_id][$catid_i]['del_content'] == 1 )
        {
            $check_cat = true;
        }
    }
	
    if ( $check_cat )
    {
        $xtitle_i = "";
        if ( $lev_i > 0 )
        {
            $xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
            for ( $i = 1; $i <= $lev_i; $i ++ )
            {
                $xtitle_i .= "---";
            }
            $xtitle_i .= ">&nbsp;";
        }
        $xtitle_i .= $array_value['title'];
        
        $sl = "";
        if ( $catid_i == $catid )
        {
            $sl = " selected=\"selected\"";
        }
		
		$val_cat_content[] = array(
			"value" => $catid_i,
			"selected" => $sl,
			"title" => $xtitle_i
		);
		$array_cat_view[] = $catid_i;
    }
}

if ( ! defined( 'NV_IS_ADMIN_MODULE' ) and $catid > 0 and ! in_array( $catid, $array_cat_view ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main" );
    die();
}

$array_search = array( 
    "-" => "---", 
	"title" => $lang_module['search_title'], 
	"bodytext" => $lang_module['search_bodytext'], 
	"author" => $lang_module['search_author'], 
	"admin_id" => $lang_module['search_admin'] 
);

$array_in_rows = array( 
    "title", 
	"bodytext", 
	"author" 
);

$array_in_ordername = array( 
    "title", 
	"publtime", 
	"exptime" 
);

if ( ! in_array( $stype, array_keys( $array_search ) ) )
{
    $stype = "-";
}

if ( ! in_array( $ordername, array_keys( $array_in_ordername ) ) )
{
    $ordername = "id";
}

if ( $catid == 0 )
{
    $from = "`" . NV_PREFIXLANG . "_" . $module_data . "_rows` as r LEFT JOIN " . NV_USERS_GLOBALTABLE . " as u ON r.admin_id=u.userid";
}
else
{
    $from = "`" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` as r LEFT JOIN " . NV_USERS_GLOBALTABLE . " as u ON r.admin_id=u.userid";
}

$where = "";
$page = $nv_Request->get_int( 'page', 'get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );

if ( $checkss == md5( session_id() ) )
{
    if ( in_array( $stype, $array_in_rows ) and ! empty( $q ) )
    {
        $where = " WHERE (r." . $stype . " LIKE '%" . $db->dblikeescape( $q ) . "%')";
    }
    elseif ( $stype == "admin_id" and ! empty( $q ) )
    {
        $where = " WHERE (u.username LIKE '%" . $db->dblikeescape( $q ) . "%' OR  u.full_name LIKE '%" . $db->dblikeescape( $q ) . "%')";
    }
    elseif ( ! empty( $q ) )
    {
        $arr_from = array();
        foreach ( $array_in_rows as $key => $val )
        {
            $arr_from[] = "(r." . $val . " LIKE '%" . $db->dblikeescape( $q ) . "%')";
        }
        $where = " WHERE (" . implode( " OR ", $arr_from ) . " OR u.username LIKE '%" . $db->dblikeescape( $q ) . "%' OR  u.full_name LIKE '%" . $db->dblikeescape( $q ) . "%')";
    }
}

if ( ! defined( 'NV_IS_ADMIN_MODULE' ) )
{
    $from_catid = array();
    foreach ( $array_cat_view as $catid_i )
    {
        $from_catid[] = "r.listcatid = '" . $catid_i . "'";
        $from_catid[] = "r.listcatid like '" . $catid_i . ",%'";
        $from_catid[] = "r.listcatid like '%," . $catid_i . ",%'";
        $from_catid[] = "r.listcatid like '%," . $catid_i . "'";
    }
    $where .= ( empty( $where ) ) ? " WHERE (" . implode( " OR ", $from_catid ) . ")" : " AND (" . implode( " OR ", $from_catid ) . ")";
}

$link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=Other";

$global_array_cat[0] = array( 
    "catid" => 0, 
	"parentid" => 0, 
	"title" => "Other", 
	"alias" => "Other", 
	"link" => $link_i, 
	"viewcat" => "viewcat_page_new", 
	"subcatid" => 0, "numlinks" => 3, 
	"description" => "", 
	"keywords" => "" 
);

$search_type = array();
foreach ( $array_search as $key => $val )
{
	$search_type[] = array(
		"key" => $key,
		"value" => $val,
		"selected" => ( $key == $stype ) ? " selected=\"selected\"" : ""
	);
}

$i = 5;
$search_per_page = array();
while ( $i <= 1000 )
{
	$search_per_page[] = array (
		"page" => $i,
		"selected" => ( $i == $per_page ) ? " selected=\"selected\"" : ""
	);
	$i = $i + 5;
}

$a = 0;
$order2 = ( $order == "asc" ) ? "desc" : "asc";
$base_url_id = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;per_page=" . $per_page . "&amp;catid=" . $catid . "&amp;stype=" . $stype . "&amp;q=" . $q . "&amp;checkss=" . $checkss . "&amp;ordername=id&amp;order=" . $order2 . "&amp;page=" . $page;
$base_url_name = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;per_page=" . $per_page . "&amp;catid=" . $catid . "&amp;stype=" . $stype . "&amp;q=" . $q . "&amp;checkss=" . $checkss . "&amp;ordername=title&amp;order=" . $order2 . "&amp;page=" . $page;
$base_url_publtime = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;per_page=" . $per_page . "&amp;catid=" . $catid . "&amp;stype=" . $stype . "&amp;q=" . $q . "&amp;checkss=" . $checkss . "&amp;ordername=publtime&amp;order=" . $order2 . "&amp;page=" . $page;
$base_url_exptime = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;per_page=" . $per_page . "&amp;catid=" . $catid . "&amp;stype=" . $stype . "&amp;q=" . $q . "&amp;checkss=" . $checkss . "&amp;ordername=exptime&amp;order=" . $order2 . "&amp;page=" . $page;

$base_url = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;per_page=" . $per_page . "&amp;catid=" . $catid . "&amp;stype=" . $stype . "&amp;q=" . $q . "&amp;checkss=" . $checkss . "&amp;ordername=" . $ordername . "&amp;order=" . $order;

$ord_sql = "ORDER BY r." . $ordername . " " . $order . "";

$sql = "SELECT SQL_CALC_FOUND_ROWS r.id, r.listcatid, r.admin_id, r.title, r.alias, r.status , r.publtime, r.exptime, u.username  FROM " . $from . " " . $where . " " . $ord_sql . " LIMIT " . $page . "," . $per_page;
$result = $db->sql_query( $sql );

$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $numf ) = $db->sql_fetchrow( $result_all );
$all_page = ( $numf ) ? $numf : 1;

$data = array();
while ( list( $id, $listcatid, $post_id, $title, $alias, $status, $publtime, $exptime, $username ) = $db->sql_fetchrow( $result ) )
{
    if ( $status == 0 )
    {
        $status = $lang_module['status_0'];
        $edit_status = 0;
    }
    elseif ( $publtime < NV_CURRENTTIME and ( $exptime == 0 or $exptime > NV_CURRENTTIME ) )
    {
        $status = $lang_module['status_1'];
        $edit_status = 1;
    }
    elseif ( $publtime > NV_CURRENTTIME )
    {
        $status = $lang_module['status_2'];
        $edit_status = 2;
    }
    else
    {
        $status = $lang_module['status_3'];
        $edit_status = 3;
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
    $check_permission_edit = $check_permission_delete = false;
    if ( defined( 'NV_IS_ADMIN_MODULE' ) )
    {
        $check_permission_edit = $check_permission_delete = true;
    }
    else
    {
        $array_temp = explode( ",", $listcatid );
        $check_edit = $check_del = 0;
        foreach ( $array_temp as $catid_i )
        {
            if ( isset( $array_cat_admin[$admin_id][$catid_i] ) )
            {
                if ( $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 )
                {
                    $check_edit ++;
                    $check_del ++;
                }
                else
                {
                    if ( $array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1 )
                    {
                        $check_edit ++;
                    }
                    elseif ( $array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and $edit_status == 0 )
                    {
                        $check_edit ++;
                    }
                    elseif ( $edit_status == 0 and $post_id == $admin_id )
                    {
                        $check_edit ++;
                    }
                    
                    if ( $array_cat_admin[$admin_id][$catid_i]['del_content'] == 1 )
                    {
                        $check_del ++;
                    }
                    elseif ( $edit_status == 0 and $post_id == $admin_id )
                    {
                        $check_del ++;
                    }
                }
            }
        }
        if ( $check_edit == count( $array_temp ) )
        {
            $check_permission_edit = true;
        }
        if ( $check_del == count( $array_temp ) )
        {
            $check_permission_delete = true;
        }
    }
	
    $admin_funcs = array();
    if ( $check_permission_edit ) $admin_funcs[] = nv_link_edit_page( $id );
    if ( $check_permission_delete ) $admin_funcs[] = nv_link_delete_page( $id );
    
    $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id;
	
	$data[] = array(
		"class" => $class,
		"id" => $id,
		"link" => $link,
		"title" => $title,
		"publtime" => $publtime,
		"status" => $status,
		"username" => $username,
		"feature" => implode( "&nbsp;-&nbsp;", $admin_funcs )
	);
		
    $a ++;
}

$array_list_action = array( 
    'delete' => $lang_global['delete'], 
	'publtime' => $lang_module['publtime'], 
	'exptime' => $lang_module['exptime'] 
);
if ( defined( 'NV_IS_ADMIN_MODULE' ) )
{
    $array_list_action['addtoblock'] = $lang_module['addtoblock'];
    $array_list_action['addtotopics'] = $lang_module['addtotopics'];
}

$action = array();
while ( list( $catid_i, $title_i ) = each( $array_list_action ) )
{
	$action[] = array(
		"value" => $catid_i,
		"title" => $title_i
	);
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'Q', $q );
$xtpl->assign( 'CHECKSS', md5( session_id() ) );
$xtpl->assign( 'SITEKEY', md5( $global_config['sitekey'] . session_id() ) );

$xtpl->assign( 'base_url_id', $base_url_id );
$xtpl->assign( 'base_url_name', $base_url_name );
$xtpl->assign( 'base_url_publtime', $base_url_publtime );
$xtpl->assign( 'base_url_exptime', $base_url_exptime );

foreach ( $val_cat_content as $cat_content )
{
    $xtpl->assign( 'CAT_CONTENT', $cat_content );
    $xtpl->parse( 'main.cat_content' );
}

foreach ( $search_type as $search_t )
{
    $xtpl->assign( 'SEARCH_TYPE', $search_t );
    $xtpl->parse( 'main.search_type' );
}

foreach ( $search_per_page as $s_per_page )
{
    $xtpl->assign( 'SEARCH_PER_PAGE', $s_per_page );
    $xtpl->parse( 'main.s_per_page' );
}

foreach ( $data as $row )
{
    $xtpl->assign( 'ROW', $row );
    $xtpl->parse( 'main.loop' );
}

foreach ( $action as $action1 )
{
    $xtpl->assign( 'ACTION', $action1 );
    $xtpl->parse( 'main.action' );
}

if ( ! empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>