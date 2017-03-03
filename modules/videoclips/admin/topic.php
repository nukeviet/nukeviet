<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_FixWeightTopic()
 * 
 * @param integer $parentid
 * @return
 */
function nv_FixWeightTopic( $parentid = 0 )
{
	global $db, $module_data;

	$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `parentid`=" . $parentid . " ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	$weight = 0;
	while ( $row = $db->sql_fetchrow( $result ) )
	{
		++$weight;
		$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topic` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'] );
	}
}

/**
 * nv_del_topic()
 * 
 * @param mixed $tid
 * @return
 */
function nv_del_topic( $tid )
{
	global $db, $module_data;

	$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` WHERE `tid`=" . $tid;
	$result = $db->sql_query( $sql );
	$in = array();
	while ( $row = $db->sql_fetchrow( $result ) ) $in[] = $row['id'];
	$in = implode( ",", $in );

	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_hit` WHERE `tid` IN (" . $in . ")";
	$db->sql_query( $sql );
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` WHERE `id` IN (" . $in . ")";
	$db->sql_query( $sql );

	$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `parentid`=" . $tid;
	$result = $db->sql_query( $sql );
	while ( list( $id ) = $db->sql_fetchrow( $result ) ) nv_del_topic( $id );

	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`=" . $tid;
	$db->sql_query( $sql );
}

$array = array();
$error = "";

//them the loai
if ( $nv_Request->isset_request( 'add', 'get' ) )
{
	$page_title = $lang_module['addtopic_titlebox'];

	$is_error = false;

	if ( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$array['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
		$array['title'] = filter_text_input( 'title', 'post', '', 1 );
		$array['description'] = filter_text_input( 'description', 'post', '' );
		$array['keywords'] = filter_text_input( 'keywords', 'post', '', 1 );

		if ( empty( $array['title'] ) )
		{
			$error = $lang_module['error1'];
			$is_error = true;
		}
		elseif ( ! empty( $array['parentid'] ) )
		{
			$sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`=" . $array['parentid'];
			$result = $db->sql_query( $sql );
			list( $count ) = $db->sql_fetchrow( $result );

			if ( ! $count )
			{
				$error = $lang_module['error2'];
				$is_error = true;
			}
		}

		if ( ! $is_error )
		{
			$alias = nv_myAlias( strtolower( change_alias( $array['title'] ) ) );

			$array['img'] = "";
			$homeimg = filter_text_input( 'img', 'post' );
			if ( ! empty( $homeimg ) )
			{
				$homeimg = preg_replace( "/^" . nv_preg_quote( NV_BASE_SITEURL ) . "(.+)$/", "$1", $homeimg );
				if ( preg_match( "/^([a-z0-9\/\.\-\_]+)\.(jpg|png|gif)$/i", $homeimg ) )
				{
					$image = NV_ROOTDIR . "/" . $homeimg;
					$image = nv_is_image( $image );
					if ( ! empty( $image ) ) $array['img'] = $homeimg;
				}
			}

			if ( empty( $array['keywords'] ) )
			{
				$array['keywords'] = nv_get_keywords( $array['description'] );
			}
			else
			{
				$array['keywords'] = explode( ",", $array['keywords'] );
				$array['keywords'] = array_map( "trim", $array['keywords'] );
				$array['keywords'] = array_unique( $array['keywords'] );
				$array['keywords'] = implode( ",", $array['keywords'] );
			}

			$sql = "SELECT MAX(weight) AS new_weight FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `parentid`=" . $array['parentid'];
			$result = $db->sql_query( $sql );
			list( $new_weight ) = $db->sql_fetchrow( $result );
			$new_weight = ( int )$new_weight;
			++$new_weight;

			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_topic` VALUES (
            NULL, 
            " . $array['parentid'] . ", 
            " . $db->dbescape( $array['title'] ) . ", 
            " . $db->dbescape( $alias ) . ", 
            " . $db->dbescape( $array['description'] ) . ", 
            " . $new_weight . ", 
            " . $db->dbescape( $array['img'] ) . ", 
            1, 
            " . $db->dbescape( $array['keywords'] ) . ")";

			$tid = $db->sql_query_insert_id( $sql );

			if ( ! $tid )
			{
				$error = $lang_module['error4'];
				$is_error = true;
			}
			else
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['addtopic_titlebox'], "ID " . $tid, $admin_info['userid'] );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
				exit();
			}
		}
	}
	else
	{
		$array['parentid'] = 0;
		$array['title'] = "";
		$array['description'] = "";
		$array['keywords'] = "";
		$array['img'] = "";
	}

	if ( ! empty( $array['img'] ) ) $array['img'] = NV_BASE_SITEURL . $array['img'];

	$listTopics = array( array(
			'id' => 0,
			'name' => $lang_module['is_maintopic'],
			'selected' => "" ) );
	$listTopics = $listTopics + nv_listTopics( $array['parentid'] );

	$xtpl = new XTemplate( "topic_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;add=1" );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );
	$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );
	$xtpl->assign( 'DATA', $array );

	if ( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}

	foreach ( $listTopics as $cat )
	{
		$xtpl->assign( 'LISTCATS', $cat );
		$xtpl->parse( 'main.parentid' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );

	exit;
}

//Sua the loai
if ( $nv_Request->isset_request( 'edit', 'get' ) )
{
	$page_title = $lang_module['edittopic_titlebox'];

	$tid = $nv_Request->get_int( 'tid', 'get', 0 );

	if ( empty( $tid ) )
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		exit();
	}

	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`=" . $tid;
	$result = $db->sql_query( $sql );
	$numcat = $db->sql_numrows( $result );

	if ( $numcat != 1 )
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		exit();
	}

	$row = $db->sql_fetchrow( $result );

	$is_error = false;

	if ( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$array['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
		$array['title'] = filter_text_input( 'title', 'post', '', 1 );
		$array['description'] = filter_text_input( 'description', 'post', '' );
		$array['keywords'] = filter_text_input( 'keywords', 'post', '', 1 );

		if ( empty( $array['title'] ) )
		{
			$error = $lang_module['error1'];
			$is_error = true;
		}
		else
		{
			if ( ! empty( $array['parentid'] ) )
			{
				$sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`=" . $array['parentid'];
				$result = $db->sql_query( $sql );
				list( $count ) = $db->sql_fetchrow( $result );

				if ( ! $count )
				{
					$error = $lang_module['error2'];
					$is_error = true;
				}
			}

			if ( ! $is_error )
			{
				$sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`!=" . $tid . " AND `alias`=" . $db->dbescape( $alias ) . " AND `parentid`=" . $array['parentid'];
				$result = $db->sql_query( $sql );
				list( $count ) = $db->sql_fetchrow( $result );

				if ( $count )
				{
					$error = $lang_module['error3'];
					$is_error = true;
				}
			}
		}

		if ( ! $is_error )
		{
			$alias = nv_myAlias( strtolower( change_alias( $array['title'] ) ), 1, $tid );

			$array['img'] = "";
			$homeimg = filter_text_input( 'img', 'post' );
			if ( ! empty( $homeimg ) )
			{
				$homeimg = preg_replace( "/^" . nv_preg_quote( NV_BASE_SITEURL ) . "(.+)$/", "$1", $homeimg );
				if ( preg_match( "/^([a-z0-9\/\.\-\_]+)\.(jpg|png|gif)$/i", $homeimg ) )
				{
					$image = NV_ROOTDIR . "/" . $homeimg;
					$image = nv_is_image( $image );
					if ( ! empty( $image ) ) $array['img'] = $homeimg;
				}
			}
			if ( empty( $array['keywords'] ) )
			{
				$array['keywords'] = nv_get_keywords( $array['description'] );
			}
			else
			{
				$array['keywords'] = explode( ",", $array['keywords'] );
				$array['keywords'] = array_map( "trim", $array['keywords'] );
				$array['keywords'] = array_unique( $array['keywords'] );
				$array['keywords'] = implode( ",", $array['keywords'] );
			}

			if ( $array['parentid'] != $row['parentid'] )
			{
				$sql = "SELECT MAX(weight) AS new_weight FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `parentid`=" . $array['parentid'];
				$result = $db->sql_query( $sql );
				list( $new_weight ) = $db->sql_fetchrow( $result );
				$new_weight = ( int )$new_weight;
				++$new_weight;
			}
			else
			{
				$new_weight = $row['weight'];
			}

			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topic` SET 
            `parentid`=" . $array['parentid'] . ", 
            `title`=" . $db->dbescape( $array['title'] ) . ", 
            `alias`=" . $db->dbescape( $alias ) . ", 
            `description`=" . $db->dbescape( $array['description'] ) . ", 
            `keywords`=" . $db->dbescape( $array['keywords'] ) . ", 
            `img`=" . $db->dbescape( $array['img'] ) . ", 
            `weight`=" . $new_weight . " 
            WHERE `id`=" . $tid;
			$result = $db->sql_query( $sql );

			if ( ! $result )
			{
				$error = $lang_module['error4'];
				$is_error = true;
			}
			else
			{
				if ( $array['parentid'] != $row['parentid'] )
				{
					nv_FixWeightTopic( $row['parentid'] );
				}

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edittopic_titlebox'], "ID " . $tid, $admin_info['userid'] );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
				exit();
			}
		}
	}
	else
	{
		$array['parentid'] = ( int )$row['parentid'];
		$array['title'] = $row['title'];
		$array['description'] = $row['description'];
		$array['keywords'] = $row['keywords'];
		$array['img'] = $row['img'];
	}

	if ( ! empty( $array['img'] ) ) $array['img'] = NV_BASE_SITEURL . $array['img'];

	$listTopics = array( array(
			'id' => 0,
			'name' => $lang_module['is_maintopic'],
			'selected' => "" ) );
	$listTopics = $listTopics + nv_listTopics( $array['parentid'], $tid );

	$xtpl = new XTemplate( "topic_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;tid=" . $tid );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );
	$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );
	$xtpl->assign( 'DATA', $array );

	if ( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}

	foreach ( $listTopics as $cat )
	{
		$xtpl->assign( 'LISTCATS', $cat );
		$xtpl->parse( 'main.parentid' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );

	exit;
}

//Xoa chu de
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$tid = $nv_Request->get_int( 'tid', 'post', 0 );

	if ( empty( $tid ) )
	{
		die( "NO" );
	}

	$sql = "SELECT COUNT(*) AS count, `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`=" . $tid;
	$result = $db->sql_query( $sql );
	list( $count, $parentid ) = $db->sql_fetchrow( $result );

	if ( $count != 1 )
	{
		die( "NO" );
	}

	nv_del_topic( $tid );
	nv_FixWeightTopic( $parentid );

	die( "OK" );
}

//Chinh thu tu chu de
if ( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$tid = $nv_Request->get_int( 'tid', 'post', 0 );
	$new = $nv_Request->get_int( 'new', 'post', 0 );

	if ( empty( $tid ) ) die( "NO" );

	$query = "SELECT `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`=" . $tid;
	$result = $db->sql_query( $query );
	$numrows = $db->sql_numrows( $result );
	if ( $numrows != 1 ) die( 'NO' );
	list( $parentid ) = $db->sql_fetchrow( $result );

	$query = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`!=" . $tid . " AND `parentid`=" . $parentid . " ORDER BY `weight` ASC";
	$result = $db->sql_query( $query );
	$weight = 0;
	while ( $row = $db->sql_fetchrow( $result ) )
	{
		++$weight;
		if ( $weight == $new ) ++$weight;
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topic` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'];
		$db->sql_query( $sql );
	}
	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topic` SET `weight`=" . $new . " WHERE `id`=" . $tid;
	$db->sql_query( $sql );
	die( "OK" );
}

//Kich hoat - dinh chi
if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$tid = $nv_Request->get_int( 'tid', 'post', 0 );

	if ( empty( $tid ) ) die( "NO" );

	$query = "SELECT `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`=" . $tid;
	$result = $db->sql_query( $query );
	$numrows = $db->sql_numrows( $result );
	if ( $numrows != 1 ) die( 'NO' );

	list( $status ) = $db->sql_fetchrow( $result );
	$status = $status ? 0 : 1;

	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topic` SET `status`=" . $status . " WHERE `id`=" . $tid;
	$db->sql_query( $sql );
	die( "OK" );
}

//Danh sach chu de
$page_title = $lang_module['topic_management'];

$pid = $nv_Request->get_int( 'pid', 'get', 0 );

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `parentid`=" . $pid . " ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

if ( ! $num )
{
	if ( $pid )
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	}
	else
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add=1" );
	}
	exit();
}

if ( $pid )
{
	$sql2 = "SELECT `title`,`parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `id`=" . $pid;
	$result2 = $db->sql_query( $sql2 );
	list( $parentid, $parentid2 ) = $db->sql_fetchrow( $result2 );
	$caption = sprintf( $lang_module['listSubTopic'], $parentid );
	$parentid = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;pid=" . $parentid2 . "\">" . $parentid . "</a>";
}
else
{
	$caption = $lang_module['listMainTopic'];
	$parentid = $lang_module['is_maintopic'];
}

$list = array();
$a = 0;

while ( $row = $db->sql_fetchrow( $result ) )
{
	$numsub = $db->sql_numrows( $db->sql_query( "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE parentid=" . $row['id'] ) );
	if ( $numsub )
	{
		$numsub = " (<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;pid=" . $row['id'] . "\">" . $numsub . " " . $lang_module['is_subtopic'] . "</a>)";
	}
	else
	{
		$numsub = "";
	}

	$weight = array();
	for ( $i = 1; $i <= $num; ++$i )
	{
		$weight[$i]['title'] = $i;
		$weight[$i]['pos'] = $i;
		$weight[$i]['selected'] = ( $i == $row['weight'] ) ? " selected=\"selected\"" : "";
	}

	$class = ( $a % 2 ) ? " class=\"second\"" : "";

	$list[$row['id']] = array( //
		'id' => ( int )$row['id'], //
		'title' => $row['title'], //
		'titlelink' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;tid=" . $row['id'], //
		'numsub' => $numsub, //
		'parentid' => $parentid, //
		'weight' => $weight, //
		'status' => $row['status'] ? " checked=\"checked\"" : "", //
		'class' => $class //
			);

	++$a;
}

$xtpl = new XTemplate( "topic_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'ADD_NEW_TOPIC', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;add=1" );
$xtpl->assign( 'TABLE_CAPTION', $caption );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'LANG', $lang_module );

foreach ( $list as $row )
{
	$xtpl->assign( 'ROW', $row );

	foreach ( $row['weight'] as $weight )
	{
		$xtpl->assign( 'WEIGHT', $weight );
		$xtpl->parse( 'main.row.weight' );
	}

	$xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;tid=" . $row['id'] );
	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>