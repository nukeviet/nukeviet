<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC All rights reserved
 * @Createdate 04/05/2010 
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

//Xoa thanh vien
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	$userid = $nv_Request->get_int( 'userid', 'post', 0 );

	$sql = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`=" . $userid;
	$result = $db->sql_query( $sql );

	if( ! $result )
	{
		die( "NO" );
	}

	die( "OK" );
}

//Kich hoat thanh vien
if( $nv_Request->isset_request( 'act', 'get' ) )
{
	$userid = $nv_Request->get_int( 'userid', 'get', 0 );

	if( ! $userid )
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
		die();
	}

	$sql = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`=" . $userid;
	$result = $db->sql_query( $sql );
	$numrows = $db->sql_numrows( $result );
	if( $numrows != 1 )
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
		die();
	}
	$row = $db->sql_fetchrow( $result );

	$sql = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "` (
    `userid`, `username`, `md5username`, `password`, `email`, `full_name`, `gender`, `photo`, `birthday`, 
    `regdate`, `website`, `location`, `yim`, `telephone`, `fax`, `mobile`, `question`, 
    `answer`, `passlostkey`, `view_mail`, `remember`, `in_groups`, `active`, `checknum`, 
    `last_login`, `last_ip`, `last_agent`, `last_openid`
    ) VALUES (
    NULL, 
    " . $db->dbescape( $row['username'] ) . ", 
    " . $db->dbescape( md5( $row['username'] ) ) . ", 
    " . $db->dbescape( $row['password'] ) . ", 
    " . $db->dbescape( $row['email'] ) . ", 
    " . $db->dbescape( $row['full_name'] ) . ", 
    '', '', 0, " . $row['regdate'] . ", '', '', '', '', '', '', 
    " . $db->dbescape( $row['question'] ) . ", 
    " . $db->dbescape( $row['answer'] ) . ", 
    '', 0, 0, '', 1, '', 0, '', '', '')";

	$userid = $db->sql_query_insert_id( $sql );

	if( $userid )
	{
		$db->sql_query( "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`=" . $row['userid'] );
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['active_users'], 'userid: ' . $userid . ' - username: ' . $row['username'], $admin_info['userid'] );

		$full_name = ( ! empty( $row['full_name'] ) ) ? $row['full_name'] : $row['username'];
		$subject = $lang_module['adduser_register'];
		$message = sprintf( $lang_module['adduser_register_info'], $full_name, $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, $row['username'] );
		$message .= "<br /><br />------------------------------------------------<br /><br />";
		$message .= nv_EncString( $message );
		@nv_sendmail( $global_config['site_email'], $row['email'], $subject, $message );
	}

	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=user_waiting" );
	die();
}

$page_title = $table_caption = $lang_module['member_wating'];

$sql = "FROM `" . NV_USERS_GLOBALTABLE . "_reg`";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting";

$methods = array( //
	'userid' => array(
		'key' => 'userid',
		'value' => $lang_module['search_id'],
		'selected' => ''
	), //
	'username' => array(
		'key' => 'username',
		'value' => $lang_module['search_account'],
		'selected' => ''
	), //
	'full_name' => array(
		'key' => 'full_name',
		'value' => $lang_module['search_name'],
		'selected' => ''
	), //
	'email' => array(
		'key' => 'email',
		'value' => $lang_module['search_mail'],
		'selected' => ''
	) //
);
$method = $nv_Request->isset_request( 'method', 'post' ) ? $nv_Request->get_string( 'method', 'post', '' ) : ( $nv_Request->isset_request( 'method', 'get' ) ? urldecode( $nv_Request->get_string( 'method', 'get', '' ) ) : '' );
$methodvalue = $nv_Request->isset_request( 'value', 'post' ) ? $nv_Request->get_string( 'value', 'post' ) : ( $nv_Request->isset_request( 'value', 'get' ) ? urldecode( $nv_Request->get_string( 'value', 'get', '' ) ) : '' );

$orders = array( 'userid', 'username', 'full_name', 'email', 'regdate' );
$orderby = $nv_Request->get_string( 'sortby', 'get', '' );
$ordertype = $nv_Request->get_string( 'sorttype', 'get', '' );
if( $ordertype != "ASC" ) $ordertype = "DESC";

if( ! empty( $method ) and isset( $methods[$method] ) and ! empty( $methodvalue ) )
{
	$sql .= " WHERE `" . $method . "` LIKE '%" . $db->dblikeescape( $methodvalue ) . "%'";
	$base_url .= "&amp;method=" . urlencode( $method ) . "&amp;value=" . urlencode( $methodvalue );
	$methods[$method]['selected'] = " selected=\"selected\"";
	$table_caption = $lang_module['search_page_title'];
}

if( ! empty( $orderby ) and in_array( $orderby, $orders ) )
{
	$sql .= " ORDER BY `" . $orderby . "` " . $ordertype;
	$base_url .= "&amp;sortby=" . $orderby . "&amp;sorttype=" . $ordertype;
}

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;

$sql2 = "SELECT SQL_CALC_FOUND_ROWS * " . $sql . " LIMIT " . $page . ", " . $per_page;
$query2 = $db->sql_query( $sql2 );

$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $numf ) = $db->sql_fetchrow( $result_all );
$all_page = ( $numf ) ? $numf : 1;

$users_list = array();
while( $row = $db->sql_fetchrow( $query2 ) )
{
	$users_list[$row['userid']] = array( //
		'userid' => ( int )$row['userid'], //
		'username' => ( string )$row['username'], //
		'full_name' => ( string )$row['full_name'], //
		'email' => ( string )$row['email'], //
		'regdate' => date( "d/m/Y H:i", $row['regdate'] ) //
	);
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$head_tds = array();
$head_tds['userid']['title'] = $lang_module['userid'];
$head_tds['userid']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting&amp;sortby=userid&amp;sorttype=ASC";
$head_tds['username']['title'] = $lang_module['account'];
$head_tds['username']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting&amp;sortby=username&amp;sorttype=ASC";
$head_tds['full_name']['title'] = $lang_module['name'];
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting&amp;sortby=full_name&amp;sorttype=ASC";
$head_tds['email']['title'] = $lang_module['email'];
$head_tds['email']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting&amp;sortby=email&amp;sorttype=ASC";
$head_tds['regdate']['title'] = $lang_module['register_date'];
$head_tds['regdate']['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting&amp;sortby=regdate&amp;sorttype=ASC";

foreach( $orders as $order )
{
	if( $orderby == $order and $ordertype == 'ASC' )
	{
		$head_tds[$order]['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting&amp;sortby=" . $order . "&amp;sorttype=DESC";
		$head_tds[$order]['title'] .= " &darr;";
	}
	elseif( $orderby == $order and $ordertype == 'DESC' )
	{
		$head_tds[$order]['href'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting&amp;sortby=" . $order . "&amp;sorttype=ASC";
		$head_tds[$order]['title'] .= " &uarr;";
	}
}

$xtpl = new XTemplate( "user_waitting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting" );
$xtpl->assign( 'SORTURL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
$xtpl->assign( 'SEARCH_VALUE', $methodvalue );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );

if( defined( 'NV_IS_USER_FORUM' ) )
{
	$xtpl->parse( 'main.is_forum' );
}

foreach( $methods as $m )
{
	$xtpl->assign( 'METHODS', $m );
	$xtpl->parse( 'main.method' );
}

foreach( $head_tds as $head_td )
{
	$xtpl->assign( 'HEAD_TD', $head_td );
	$xtpl->parse( 'main.head_td' );
}

foreach( $users_list as $u )
{
	$xtpl->assign( 'CONTENT_TD', $u );
	$xtpl->assign( 'ACTIVATE_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting&amp;act=1&amp;userid=" . $u['userid'] );
	$xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=user_waiting&amp;del&amp;userid=" . $u['userid'] );
	$xtpl->parse( 'main.xusers' );
}

if( ! empty( $generate_page ) )
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