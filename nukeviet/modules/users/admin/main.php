<?php

/**
 * @Project NUKEVIET CMS 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright(C) 2010 VINADES. All rights reserved
 * @Createdate 04/05/2010 
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $table_caption = $lang_module['list_module_title'];

$sql = "FROM `" . NV_USERS_GLOBALTABLE . "`";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;

$methods = array(  //
    'userid' => array( 
    'key' => 'userid', 'value' => $lang_module['search_id'], 'selected' => '' 
), //
'username' => array( 
    'key' => 'username', 'value' => $lang_module['search_account'], 'selected' => '' 
), //
'full_name' => array( 
    'key' => 'full_name', 'value' => $lang_module['search_name'], 'selected' => '' 
), //
'email' => array( 
    'key' => 'email', 'value' => $lang_module['search_mail'], 'selected' => '' 
)  //
);
$method = $nv_Request->isset_request( 'method', 'post' ) ? $nv_Request->get_string( 'method', 'post', '' ) : ( $nv_Request->isset_request( 'method', 'get' ) ? urldecode( $nv_Request->get_string( 'method', 'get', '' ) ) : '' );
$methodvalue = $nv_Request->isset_request( 'value', 'post' ) ? $nv_Request->get_string( 'value', 'post' ) : ( $nv_Request->isset_request( 'value', 'get' ) ? urldecode( $nv_Request->get_string( 'value', 'get', '' ) ) : '' );

$orders = array( 
    'userid', 'username', 'full_name', 'email', 'regdate' 
);
$orderby = $nv_Request->get_string( 'sortby', 'get', 'userid' );
$ordertype = $nv_Request->get_string( 'sorttype', 'get', 'DESC' );
if ( $ordertype != "ASC" ) $ordertype = "DESC";
$method  = ( ! empty( $method ) and isset( $methods[$method] ) ) ? $method : '';

if ( ! empty( $methodvalue ) )
{
    if ( empty( $method ) )
    {
        $key_methods = array_keys($methods);
        $array_like = array();
        foreach ($key_methods as $method_i) {
            $array_like[] = "`" . $method_i . "` LIKE '%" . $db->dblikeescape( $methodvalue ) . "%'";
        }
        $sql .= " WHERE ".implode(" OR ", $array_like);        
    }
    else
    {
        $sql .= " WHERE `" . $method . "` LIKE '%" . $db->dblikeescape( $methodvalue ) . "%'";
        $methods[$method]['selected'] = " selected=\"selected\"";
    }
    $base_url .= "&amp;method=" . urlencode( $method ) . "&amp;value=" . urlencode( $methodvalue );
    $table_caption = $lang_module['search_page_title'];
}

if ( ! empty( $orderby ) and in_array( $orderby, $orders ) )
{
    $sql .= " ORDER BY `" . $orderby . "` " . $ordertype;
    $base_url .= "&amp;sortby=" . $orderby . "&amp;sorttype=" . $ordertype;
}

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;

$sql2 = "SELECT SQL_CALC_FOUND_ROWS * " . $sql . " LIMIT " . $page . ", " . $per_page;
$query2 = $db->sql_query( $sql2 );

$result = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result );

$users_list = array();
$admin_in = array();
while ( $row = $db->sql_fetchrow( $query2 ) )
{
    $users_list[$row['userid']] = array(  //
        'userid' => ( int )$row['userid'], //
        'username' => ( string )$row['username'], //
        'full_name' => ( string )$row['full_name'], //
        'email' => ( string )$row['email'], //
        'regdate' => date( "d/m/Y H:i", $row['regdate'] ), //
        'checked' => ( int )$row['active'] ? " checked=\"checked\"" : "", //
        'disabled' => " onclick=\"nv_chang_status(" . $row['userid'] . ");\"", //
        'is_edit' => true, //
        'is_delete' => true, //
        'level' => $lang_module['level0'], //
        'is_admin' => false  //
    );
    $admin_in[] = $row['userid'];
}

if ( $admin_in )
{
    $admin_in = implode( ",", $admin_in );
    $sql = "SELECT `admin_id`, `lev` FROM `" . NV_AUTHORS_GLOBALTABLE . "` WHERE `admin_id` IN (" . $admin_in . ")";
    $query = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $query ) )
    {
        $is_my = ( $admin_info['admin_id'] == $row['admin_id'] ) ? true : false;
        $superadmin = ( $row['lev'] == 1 or $row['lev'] == 2 ) ? true : false;

        $users_list[$row['admin_id']]['is_edit'] = false;
        $users_list[$row['admin_id']]['is_delete'] = false;
        if ( $row['lev'] == 1 )
        {
            $users_list[$row['admin_id']]['level'] = $lang_module['level1'];
            $users_list[$row['admin_id']]['img'] = 'admin1';
        }
        elseif ( $row['lev'] == 2 )
        {
            $users_list[$row['admin_id']]['level'] = $lang_module['level2'];
            $users_list[$row['admin_id']]['img'] = 'admin2';
        }
        else
        {
            $users_list[$row['admin_id']]['level'] = $lang_module['level3'];
            $users_list[$row['admin_id']]['img'] = 'admin3';
        }
        
        $users_list[$row['admin_id']]['is_admin'] = true;
        
        if ( defined( 'NV_IS_GODADMIN' ) )
        {
            $users_list[$row['admin_id']]['is_edit'] = true;
        }
        elseif ( defined( 'NV_IS_SPADMIN' ) and ( $is_my or ! $superadmin ) )
        {
            $users_list[$row['admin_id']]['is_edit'] = true;
        }
        elseif ( $is_my )
        {
            $users_list[$row['admin_id']]['is_edit'] = true;
        }
        if(!($users_list[$row['admin_id']]['is_edit'] AND !$is_my))
        {
            $users_list[$row['admin_id']]['disabled'] = " disabled=\"disabled\"";
        }
    }
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$head_tds = array();
$head_tds['userid']['title'] = $lang_module['userid'];
$head_tds['userid']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;sortby=userid&amp;sorttype=ASC";
$head_tds['username']['title'] = $lang_module['account'];
$head_tds['username']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;sortby=username&amp;sorttype=ASC";
$head_tds['full_name']['title'] = $lang_module['name'];
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;sortby=full_name&amp;sorttype=ASC";
$head_tds['email']['title'] = $lang_module['email'];
$head_tds['email']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;sortby=email&amp;sorttype=ASC";
$head_tds['regdate']['title'] = $lang_module['register_date'];
$head_tds['regdate']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;sortby=regdate&amp;sorttype=ASC";

foreach ( $orders as $order )
{
    if ( $orderby == $order and $ordertype == 'ASC' )
    {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;sortby=" . $order . "&amp;sorttype=DESC";
        $head_tds[$order]['title'] .= " &darr;";
    }
    elseif ( $orderby == $order and $ordertype == 'DESC' )
    {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;sortby=" . $order . "&amp;sorttype=ASC";
        $head_tds[$order]['title'] .= " &uarr;";
    }
}

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
$xtpl->assign( 'SORTURL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
$xtpl->assign( 'SEARCH_VALUE', $methodvalue );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );

if ( defined( 'NV_IS_USER_FORUM' ) )
{
    $xtpl->parse( 'main.is_forum' );
}

foreach ( $methods as $m )
{
    $xtpl->assign( 'METHODS', $m );
    $xtpl->parse( 'main.method' );
}

foreach ( $head_tds as $head_td )
{
    $xtpl->assign( 'HEAD_TD', $head_td );
    $xtpl->parse( 'main.head_td' );
}

foreach ( $users_list as $u )
{
    $xtpl->assign( 'CONTENT_TD', $u );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'NV_ADMIN_THEME', $global_config['admin_theme'] );
    if ( $u['is_admin'] )
    {
        $xtpl->parse( 'main.xusers.is_admin' );
    }
    
	if ( ! defined( 'NV_IS_USER_FORUM' ) )
    {
        if ( $u['is_edit'] )
        {
            $xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=" . $u['userid'] );
            $xtpl->parse( 'main.xusers.edit' );
        }
        if ( $u['is_delete'] )
        {
            $xtpl->parse( 'main.xusers.del' );
        }
    }
    
    $xtpl->parse( 'main.xusers' );
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