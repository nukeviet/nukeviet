<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/05/2010
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

//Xoa thanh vien
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	$userid = $nv_Request->get_int( 'userid', 'post', 0 );

	$sql = 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE userid=' . $userid;
	if( $db->exec( $sql ) )
	{
		die( 'OK' );
	}
	die( 'NO' );
}

//Kich hoat thanh vien
if( $nv_Request->isset_request( 'act', 'get' ) )
{
	$userid = $nv_Request->get_int( 'userid', 'get', 0 );

	$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE userid=' . $userid;
	$row = $db->query( $sql )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . " (
		username, md5username, password, email, full_name, gender, photo, birthday,
		regdate, question,
		answer, passlostkey, view_mail, remember, in_groups, active, checknum,
		last_login, last_ip, last_agent, last_openid, idsite
		) VALUES (
		:username,
		:md5_username,
		:password,
		:email,
		:full_name,
		'', '', 0, " . $row['regdate'] . ",
		:question,
		:answer,
		'', 0, 0, '', 1, '', 0, '', '', '', ".$global_config['idsite'].")";

	$data_insert = array();
	$data_insert['username'] = $row['username'];
	$data_insert['md5_username'] = nv_md5safe( $row['username'] );
	$data_insert['password'] = $row['password'];
	$data_insert['email'] = $row['email'];
	$data_insert['full_name'] = $row['full_name'];
	$data_insert['question'] = $row['question'];
	$data_insert['answer'] = $row['answer'];
	$userid = $db->insert_id( $sql, 'userid', $data_insert );
	if( $userid )
	{
		$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers+1 WHERE group_id=4' );
		$users_info = unserialize( nv_base64_decode( $row['users_info'] ) );
		$query_field = array();
		$query_field['userid'] = $userid;
		$result_field = $db->query( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_field ORDER BY fid ASC' );
		while( $row_f = $result_field->fetch() )
		{
			$query_field[$row_f['field']] = ( isset( $users_info[$row_f['field']] ) ) ? $users_info[$row_f['field']] : $db->quote( $row_f['default_value'] );
		}
		if( $db->exec( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_info (' . implode( ', ', array_keys( $query_field ) ) . ') VALUES (' . implode( ', ', array_values( $query_field ) ) . ')' ) )
		{
			$db->query( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE userid=' . $row['userid'] );

			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['active_users'], 'userid: ' . $userid . ' - username: ' . $row['username'], $admin_info['userid'] );
			$full_name = ( ! empty( $row['full_name'] ) ) ? $row['full_name'] : $row['username'];
			$subject = $lang_module['adduser_register'];
			$message = sprintf( $lang_module['adduser_register_info'], $full_name, $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, $row['username'] );
			$message .= '<br /><br />------------------------------------------------<br /><br />';
			$message .= nv_EncString( $message );
			@nv_sendmail( $global_config['site_email'], $row['email'], $subject, $message );
		}
		else
		{
			$db->query( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['userid'] );
		}
	}
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=user_waiting' );
	die();
}

$page_title = $table_caption = $lang_module['member_wating'];

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting';

$methods = array(
	'userid' => array(
		'key' => 'userid',
		'value' => $lang_module['search_id'],
		'selected' => ''
	),
	'username' => array(
		'key' => 'username',
		'value' => $lang_module['search_account'],
		'selected' => ''
	),
	'full_name' => array(
		'key' => 'full_name',
		'value' => $lang_module['search_name'],
		'selected' => ''
	),
	'email' => array(
		'key' => 'email',
		'value' => $lang_module['search_mail'],
		'selected' => ''
	)
);
$method = $nv_Request->isset_request( 'method', 'post' ) ? $nv_Request->get_string( 'method', 'post', '' ) : ( $nv_Request->isset_request( 'method', 'get' ) ? urldecode( $nv_Request->get_string( 'method', 'get', '' ) ) : '' );
$methodvalue = $nv_Request->isset_request( 'value', 'post' ) ? $nv_Request->get_string( 'value', 'post' ) : ( $nv_Request->isset_request( 'value', 'get' ) ? urldecode( $nv_Request->get_string( 'value', 'get', '' ) ) : '' );

$orders = array( 'userid', 'username', 'full_name', 'email', 'regdate' );
$orderby = $nv_Request->get_string( 'sortby', 'get', '' );
$ordertype = $nv_Request->get_string( 'sorttype', 'get', '' );
if( $ordertype != 'ASC' ) $ordertype = 'DESC';

$db->sqlreset()
	->select( 'COUNT(*)' )
	->from( NV_USERS_GLOBALTABLE . '_reg' );

if( ! empty( $method ) and isset( $methods[$method] ) and ! empty( $methodvalue ) )
{
	$base_url .= '&amp;method=' . urlencode( $method ) . '&amp;value=' . urlencode( $methodvalue );
	$methods[$method]['selected'] = ' selected="selected"';
	$table_caption = $lang_module['search_page_title'];

	$db->where( $method . " LIKE '%" . $db->dblikeescape( $methodvalue ) . "%'" );
}

$page = $nv_Request->get_int( 'page', 'get', 1 );
$per_page = 30;

$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( '*' )
	->limit( $per_page )
	->offset( ( $page - 1 ) * $per_page );

if( ! empty( $orderby ) and in_array( $orderby, $orders ) )
{
	$db->order( $orderby . ' ' . $ordertype );
	$base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
}

$result = $db->query( $db->sql() );

$users_list = array();
while( $row = $result->fetch() )
{
	$users_list[$row['userid']] = array(
		'userid' => $row['userid'],
		'username' => $row['username'],
		'full_name' => $row['full_name'],
		'email' => $row['email'],
		'regdate' => date( 'd/m/Y H:i', $row['regdate'] )
	);
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$head_tds = array();
$head_tds['userid']['title'] = $lang_module['userid'];
$head_tds['userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=userid&amp;sorttype=ASC';
$head_tds['username']['title'] = $lang_module['account'];
$head_tds['username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=username&amp;sorttype=ASC';
$head_tds['full_name']['title'] = $lang_module['name'];
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=full_name&amp;sorttype=ASC';
$head_tds['email']['title'] = $lang_module['email'];
$head_tds['email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=email&amp;sorttype=ASC';
$head_tds['regdate']['title'] = $lang_module['register_date'];
$head_tds['regdate']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=regdate&amp;sorttype=ASC';

foreach( $orders as $order )
{
	if( $orderby == $order and $ordertype == 'ASC' )
	{
		$head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=' . $order . '&amp;sorttype=DESC';
		$head_tds[$order]['title'] .= ' &darr;';
	}
	elseif( $orderby == $order and $ordertype == 'DESC' )
	{
		$head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=' . $order . '&amp;sorttype=ASC';
		$head_tds[$order]['title'] .= ' &uarr;';
	}
}

$xtpl = new XTemplate( 'user_waitting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting' );
$xtpl->assign( 'SORTURL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
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
	$xtpl->assign( 'ACTIVATE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;act=1&amp;userid=' . $u['userid'] );
	$xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;del&amp;userid=' . $u['userid'] );
	$xtpl->parse( 'main.xusers' );
}

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';