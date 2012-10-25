<?php

/**
 * @Project NukeViet 3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 26/5/2011, 23:28
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$area = filter_text_input( 'area', 'get', '' );
if( empty( $area ) )
{
	nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
}

$page_title = $lang_module['pagetitle'];

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'AREA', $area );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&area=" . $area );

$array = array();

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&amp;area=" . $area . "&amp;submit=1";

if( $nv_Request->isset_request( 'submit', 'get' ) )
{
	$array_user = array();
	$generate_page = "";

	$orderid = filter_text_input( 'orderid', 'get', '' );
	$orderusername = filter_text_input( 'orderusername', 'get', '' );
	$orderemail = filter_text_input( 'orderemail', 'get', '' );
	$orderregdate = filter_text_input( 'orderregdate', 'get', '' );

	if( $orderid != "DESC" and $orderid != "" ) $orderid = "ASC";
	if( $orderusername != "DESC" and $orderusername != "" ) $orderusername = "ASC";
	if( $orderemail != "DESC" and $orderemail != "" ) $orderemail = "ASC";
	if( $orderregdate != "DESC" and $orderregdate != "" ) $orderregdate = "ASC";

	$array['username'] = filter_text_input( 'username', 'get', '' );
	$array['full_name'] = filter_text_input( 'full_name', 'get', '' );
	$array['email'] = filter_text_input( 'email', 'get', '' );
	$array['website'] = filter_text_input( 'website', 'get', '' );
	$array['address'] = filter_text_input( 'address', 'get', '' );
	$array['phone'] = filter_text_input( 'phone', 'get', '' );
	$array['mobile'] = filter_text_input( 'mobile', 'get', '' );

	$array['regdatefrom'] = filter_text_input( 'regdatefrom', 'get', '' );
	$array['regdateto'] = filter_text_input( 'regdateto', 'get', '' );

	$array['last_loginfrom'] = filter_text_input( 'last_loginfrom', 'get', '' );
	$array['last_loginto'] = filter_text_input( 'last_loginto', 'get', '' );

	$array['last_ip'] = filter_text_input( 'last_ip', 'get', '' );

	$array['gender'] = filter_text_input( 'gender', 'get', '' );
	$array['yim'] = filter_text_input( 'yim', 'get', '' );

	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array['regdatefrom'], $m ) )
	{
		$array['regdatefrom1'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array['regdatefrom1'] = "";
	}

	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array['regdateto'], $m ) )
	{
		$array['regdateto1'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array['regdateto1'] = "";
	}

	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array['last_loginfrom'], $m ) )
	{
		$array['last_loginfrom1'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array['last_loginfrom1'] = "";
	}

	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array['last_loginto'], $m ) )
	{
		$array['last_loginto1'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array['last_loginto1'] = "";
	}

	$sql = "FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`!=0";

	$is_null = true;
	foreach( $array as $check )
	{
		if( ! empty( $check ) )
		{
			$is_null = false;
			break;
		}
	}

	if( ! $is_null )
	{
		if( ! empty( $array['username'] ) )
		{
			$base_url .= "&amp;username=" . rawurlencode( $array['username'] );
			$sql .= " AND ( username LIKE '%" . $db->dblikeescape( $array['username'] ) . "%' )";
		}

		if( ! empty( $array['full_name'] ) )
		{
			$base_url .= "&amp;full_name=" . rawurlencode( $array['full_name'] );
			$sql .= " AND ( full_name LIKE '%" . $db->dblikeescape( $array['full_name'] ) . "%' )";
		}

		if( ! empty( $array['email'] ) )
		{
			$base_url .= "&amp;email=" . rawurlencode( $array['email'] );
			$sql .= " AND ( email LIKE '%" . $db->dblikeescape( $array['email'] ) . "%' )";
		}

		if( ! empty( $array['website'] ) )
		{
			$base_url .= "&amp;website=" . rawurlencode( $array['website'] );
			$sql .= " AND ( website LIKE '%" . $db->dblikeescape( $array['website'] ) . "%' )";
		}

		if( ! empty( $array['address'] ) )
		{
			$base_url .= "&amp;address=" . rawurlencode( $array['address'] );
			$sql .= " AND ( address LIKE '%" . $db->dblikeescape( $array['address'] ) . "%' )";
		}

		if( ! empty( $array['phone'] ) )
		{
			$base_url .= "&amp;phone=" . rawurlencode( $array['phone'] );
			$sql .= " AND ( phone LIKE '%" . $db->dblikeescape( $array['phone'] ) . "%' )";
		}

		if( ! empty( $array['mobile'] ) )
		{
			$base_url .= "&amp;mobile=" . rawurlencode( $array['mobile'] );
			$sql .= " AND ( mobile LIKE '%" . $db->dblikeescape( $array['mobile'] ) . "%' )";
		}

		if( ! empty( $array['last_ip'] ) )
		{
			$base_url .= "&amp;last_ip=" . rawurlencode( $array['last_ip'] );
			$sql .= " AND ( last_ip LIKE '%" . $db->dblikeescape( $array['last_ip'] ) . "%' )";
		}

		if( ! empty( $array['yim'] ) )
		{
			$base_url .= "&amp;yim=" . rawurlencode( $array['yim'] );
			$sql .= " AND ( yim LIKE '%" . $db->dblikeescape( $array['yim'] ) . "%' )";
		}

		if( ! empty( $array['gender'] ) )
		{
			$base_url .= "&amp;gender=" . rawurlencode( $array['gender'] );
			$sql .= " AND ( `gender` =" . $db->dbescape( $array['gender'] ) . " )";
		}

		if( ! empty( $array['regdatefrom1'] ) )
		{
			$base_url .= "&amp;regdatefrom=" . rawurlencode( nv_date( "d.m.Y", $array['regdatefrom1'] ) );
			$sql .= " AND ( regdate >= " . $array['regdatefrom1'] . " )";
		}

		if( ! empty( $array['regdateto1'] ) )
		{
			$base_url .= "&amp;regdateto=" . rawurlencode( nv_date( "d.m.Y", $array['regdateto1'] ) );
			$sql .= " AND ( regdate <= " . $array['regdateto1'] . " )";
		}

		if( ! empty( $array['last_loginfrom1'] ) )
		{
			$base_url .= "&amp;last_loginfrom=" . rawurlencode( nv_date( "d.m.Y", $array['last_loginfrom1'] ) );
			$sql .= " AND ( last_login >= " . $array['last_loginfrom1'] . " )";
		}

		if( ! empty( $array['last_loginto1'] ) )
		{
			$base_url .= "&amp;last_loginto=" . rawurlencode( nv_date( "d.m.Y", $array['last_loginto1'] ) );
			$sql .= " AND ( last_login <= " . $array['last_loginto1'] . " )";
		}

		$sql1 = "SELECT COUNT(*) " . $sql;
		$result1 = $db->sql_query( $sql1 );
		list( $all_page ) = $db->sql_fetchrow( $result1 );

		// Order data
		$orderida = array( "url" => ( $orderid == "ASC" ) ? $base_url . "&amp;orderid=DESC" : $base_url . "&amp;orderid=ASC", //
				"class" => ( $orderid == "" ) ? "nooder" : strtolower( $orderid ) //
				);

		$orderusernamea = array( "url" => ( $orderusername == "ASC" ) ? $base_url . "&amp;orderusername=DESC" : $base_url . "&amp;orderusername=ASC", "class" => ( $orderusername == "" ) ? "nooder" : strtolower( $orderusername ) //
				);

		$orderemaila = array( "url" => ( $orderemail == "ASC" ) ? $base_url . "&amp;orderemail=DESC" : $base_url . "&amp;orderemail=ASC", //
				"class" => ( $orderemail == "" ) ? "nooder" : strtolower( $orderemail ) //
				);

		$orderregdatea = array( "url" => ( $orderregdate == "ASC" ) ? $base_url . "&amp;orderregdate=DESC" : $base_url . "&amp;orderregdate=ASC", //
				"class" => ( $orderregdate == "" ) ? "nooder" : strtolower( $orderregdate ) //
				);

		// SQL data
		if( ! empty( $orderid ) )
		{
			$base_url .= "&amp;orderid=" . $orderid;
			$sql .= " ORDER BY `userid` " . $orderid . "";
		}
		elseif( ! empty( $orderusername ) )
		{
			$base_url .= "&amp;orderusername=" . $orderusername;
			$sql .= " ORDER BY `username` " . $orderusername . "";
		}
		elseif( ! empty( $orderemail ) )
		{
			$base_url .= "&amp;orderemail=" . $orderemail;
			$sql .= " ORDER BY `email` " . $orderemail . "";
		}
		elseif( ! empty( $orderregdate ) )
		{
			$base_url .= "&amp;orderregdate=" . $orderregdate;
			$sql .= " ORDER BY `regdate` " . $orderregdate . "";
		}

		$page = $nv_Request->get_int( 'page', 'get', 0 );
		$per_page = 10;

		$sql2 = "SELECT `userid`, `username`, `email`, `regdate` " . $sql . " LIMIT " . $page . ", " . $per_page;
		$query2 = $db->sql_query( $sql2 );

		while( $row = $db->sql_fetchrow( $query2 ) )
		{
			$array_user[$row['userid']] = array(
				"userid" => $row['userid'], //
				"username" => $row['username'], //
				"email" => $row['email'], //
				"regdate" => nv_date( "d/m/Y H:i", $row['regdate'] ) //
					);
		}

		$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
	}

	if( ! empty( $array_user ) )
	{
		$xtpl->assign( 'ODER_ID', $orderida );
		$xtpl->assign( 'ODER_USERNAME', $orderusernamea );
		$xtpl->assign( 'ODER_EMAIL', $orderemaila );
		$xtpl->assign( 'ODER_REGDATE', $orderregdatea );

		$a = 0;
		foreach( $array_user as $row )
		{
			$xtpl->assign( 'CLASS', ( $a % 2 == 1 ) ? " class=\"second\"" : "" );
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'resultdata.data.row' );
			++$a;
		}

		if( ! empty( $generate_page ) )
		{
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'resultdata.data.generate_page' );
		}

		$xtpl->parse( 'resultdata.data' );
	}
	elseif( $nv_Request->isset_request( 'submit', 'get' ) )
	{
		$xtpl->parse( 'resultdata.nodata' );
	}

	$xtpl->parse( 'resultdata' );
	$contents = $xtpl->text( 'resultdata' );
}
else
{
	$gender = isset( $array['gender'] ) ? $array['gender'] : "";
	$array['gender'] = array();
	$array['gender'][] = array(
		"key" => "", //
		"title" => $lang_module['select_gender'], //
		"selected" => ( "" == $gender ) ? " selected=\"selected\"" : "" //
	);
	$array['gender'][] = array(
		"key" => "M", //
		"title" => $lang_module['select_gender_male'], //
		"selected" => ( "M" == $gender ) ? " selected=\"selected\"" : "" //
	);
	$array['gender'][] = array(
		"key" => "F", //
		"title" => $lang_module['select_gender_female'], //
		"selected" => ( "F" == $gender ) ? " selected=\"selected\"" : "" //
	);

	foreach( $array['gender'] as $gender )
	{
		$xtpl->assign( 'GENDER', $gender );
		$xtpl->parse( 'main.gender' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );
exit();

?>