<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$topicList = nv_listTopics( 0 );

if ( empty( $topicList ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topic&add" );
	exit();
}

$page_title = $lang_module['main'];
$contents = "";

$sql = "SELECT COUNT(*) as count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip`";
$result = $db->sql_query( $sql );
$count = $db->sql_fetchrow( $result );

if ( empty( $count['count'] ) and ! $nv_Request->isset_request( 'add', 'get' ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add" );
	die();
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE );
$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'NV_ADMIN_THEME', $global_config['module_theme'] );
$xtpl->assign( 'module', $module_data );

$groups_list = nv_groups_list();
$array_who = array(
	$lang_global['who_view0'],
	$lang_global['who_view1'],
	$lang_global['who_view2'] );
if ( ! empty( $groups_list ) )
{
	$array_who[] = $lang_global['who_view3'];
}

if ( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
{
	if ( defined( 'NV_EDITOR' ) )
	{
		require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
	}

	$post = array();
	$is_error = false;
	$info = "";

	if ( $nv_Request->isset_request( 'edit, id', 'get' ) )
	{
		$post['id'] = $nv_Request->get_int( 'id', 'get', 0 );

		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` WHERE `id`=" . $post['id'];
		$result = $db->sql_query( $sql );
		$num = $db->sql_numrows( $result );
		if ( $num != 1 )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			die();
		}

		$row = $db->sql_fetch_assoc( $result );
	}

	if ( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$post['tid'] = $nv_Request->get_int( 'tid', 'post', 0 );
		$post['title'] = filter_text_input( 'title', 'post', '', 1 );
		$post['hometext'] = filter_text_input( 'hometext', 'post', '', 1 );
		$post['bodytext'] = nv_editor_filter_textarea( 'bodytext', '', NV_ALLOWED_HTML_TAGS );
		$post['keywords'] = filter_text_input( 'keywords', 'post', '', 1 );
		$post['internalpath'] = filter_text_input( 'internalpath', 'post' );
		$post['externalpath'] = filter_text_input( 'externalpath', 'post' );
		$post['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
		$post['groups_view'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int' );
		$post['comm'] = $nv_Request->get_int( 'comm', 'post', 0 );
		$post['redirect'] = $nv_Request->get_int( 'redirect', 'post', 0 );

		if ( ! empty( $post['internalpath'] ) )
		{
			$post['internalpath'] = preg_replace( "/^" . nv_preg_quote( NV_BASE_SITEURL ) . "(.+)$/", "$1", $post['internalpath'] );
			if ( ! preg_match( "/^([a-z0-9\/\.\-\_]+)\.([a-z0-9]+)$/i", $post['internalpath'] ) or ! file_exists( NV_ROOTDIR . "/" . $post['internalpath'] ) ) $post['internalpath'] = "";
		}

		if ( ! empty( $post['externalpath'] ) and ! nv_is_url( $post['externalpath'] ) ) $post['externalpath'] = "";

		if ( ! isset( $topicList[$post['tid']] ) ) $post['tid'] = 0;
		$post['hometext'] = nv_nl2br( $post['hometext'] );

		$where = isset( $post['id'] ) ? " `id`!=" . $post['id'] . " AND" : "";

		if ( empty( $post['title'] ) )
		{
			$info = $lang_module['error1'];
			$is_error = true;
		}
		elseif ( empty( $post['hometext'] ) )
		{
			$info = $lang_module['error7'];
			$is_error = true;
		}
		elseif ( empty( $post['internalpath'] ) and empty( $post['externalpath'] ) )
		{
			$info = $lang_module['error5'];
			$is_error = true;
		}

		$post['img'] = "";
		$homeimg = filter_text_input( 'img', 'post' );
		if ( ! empty( $homeimg ) )
		{
			$homeimg = preg_replace( "/^" . nv_preg_quote( NV_BASE_SITEURL ) . "(.+)$/", "$1", $homeimg );
			if ( preg_match( "/^([a-z0-9\/\.\-\_]+)\.(jpg|png|gif)$/i", $homeimg ) )
			{
				$image = NV_ROOTDIR . "/" . $homeimg;
				$image = nv_is_image( $image );
				if ( ! empty( $image ) ) $post['img'] = $homeimg;
			}

			if ( empty( $post['img'] ) )
			{
				$info = $lang_module['error6'];
				$is_error = true;
			}
		}

		if ( ! $is_error )
		{
			$test_content = strip_tags( $post['bodytext'] );
			$test_content = trim( $test_content );
			$post['bodytext'] = ! empty( $test_content ) ? nv_editor_nl2br( $post['bodytext'] ) : "";

			if ( ! in_array( $post['who_view'], array_keys( $array_who ) ) ) $post['who_view'] = 0;
			$post['groups_view'] = ( ! empty( $post['groups_view'] ) ) ? implode( ',', $post['groups_view'] ) : '';

			if ( empty( $post['keywords'] ) )
			{
				$post['keywords'] = nv_get_keywords( $post['hometext'] . " " . $post['bodytext'] );
			}
			else
			{
				$post['keywords'] = explode( ",", $post['keywords'] );
				$post['keywords'] = array_map( "trim", $post['keywords'] );
				$post['keywords'] = array_unique( $post['keywords'] );
				$post['keywords'] = implode( ",", $post['keywords'] );
			}

			if ( isset( $post['id'] ) )
			{
				$alias = nv_myAlias( strtolower( change_alias( $post['title'] ) ), 2, $post['id'] );

				$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_clip` SET 
                `tid`=" . $post['tid'] . ", 
                `alias`=" . $db->dbescape( $alias ) . ", 
                `title`=" . $db->dbescape( $post['title'] ) . ", 
                `img`=" . $db->dbescape( $post['img'] ) . ", 
                `hometext`=" . $db->dbescape( $post['hometext'] ) . ", 
                `bodytext`=" . $db->dbescape( $post['bodytext'] ) . ", 
                `keywords`=" . $db->dbescape( $post['keywords'] ) . ", 
                `internalpath`=" . $db->dbescape( $post['internalpath'] ) . ",
                `externalpath`=" . $db->dbescape( $post['externalpath'] ) . ", 
                `who_view`=" . $post['who_view'] . ",
                `groups_view`=" . $db->dbescape( $post['groups_view'] ) . ",
                 `comm`=" . $post['comm'] . " 
                WHERE `id`=" . $post['id'];

				$db->sql_query( $query );

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['editClip'], "Id: " . $post['id'], $admin_info['userid'] );
			}
			else
			{
				$alias = nv_myAlias( strtolower( change_alias( $post['title'] ) ) );

				$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_clip` VALUES 
                (NULL, " . $post['tid'] . ", " . $db->dbescape( $post['title'] ) . ", " . $db->dbescape( $alias ) . ", 
                " . $db->dbescape( $post['hometext'] ) . ", " . $db->dbescape( $post['bodytext'] ) . ", 
                " . $db->dbescape( $post['keywords'] ) . ", " . $db->dbescape( $post['img'] ) . ", 
                " . $db->dbescape( $post['internalpath'] ) . ", " . $db->dbescape( $post['externalpath'] ) . ", 
                " . $post['who_view'] . ", " . $db->dbescape( $post['groups_view'] ) . ", " . $post['comm'] . ", 
                1, " . NV_CURRENTTIME . ");";
				$_id = $db->sql_query_insert_id( $query );

				$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_hit` VALUES (" . $_id . ", 0, 0, 0, 0, 0);";
				$db->sql_query( $query );

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['addClip'], "Id: " . $_id, $admin_info['userid'] );
			}
			nv_del_moduleCache( $module_name );
			if ( $post['redirect'] )
			{
				Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $alias );
				die();
			}
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
			die();
		}
	}
	elseif ( isset( $post['id'] ) )
	{
		$post = $row;
		$post['hometext'] = nv_br2nl( $post['hometext'] );
		$post['bodytext'] = nv_editor_br2nl( $post['bodytext'] );
		$post['keywords'] = preg_replace( "/\,[\s]*/", ", ", $post['keywords'] );
		$post['who_view'] = ( int )$row['who_view'];
		$post['groups_view'] = ! empty( $row['groups_view'] ) ? explode( ",", $row['groups_view'] ) : array();
		$post['redirect'] = $nv_Request->get_int( 'redirect', 'get', 0 );
	}
	else
	{
		$post['title'] = $post['hometext'] = $post['bodytext'] = $post['img'] = $post['keywords'] = $post['internalpath'] = $post['externalpath'] = "";
		$post['tid'] = $post['who_view'] = 0;
		$post['comm'] = 1;
		$post['groups_view'] = array();
		$post['redirect'] = 0;
	}

	if ( ! empty( $post['bodytext'] ) ) $post['bodytext'] = nv_htmlspecialchars( $post['bodytext'] );
	if ( ! empty( $post['img'] ) ) $post['img'] = NV_BASE_SITEURL . $post['img'];
	if ( ! empty( $post['internalpath'] ) ) $post['internalpath'] = NV_BASE_SITEURL . $post['internalpath'];
	$post['comm'] = $post['comm'] ? "  checked=\"checked\"" : "";

	$xtpl->assign( 'ERROR_INFO', $info );

	if ( isset( $post['id'] ) )
	{
		$post['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&edit&id=" . $post['id'];
		$informationtitle = $lang_module['editClip'];
	}
	else
	{
		$post['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&add";
		$informationtitle = $lang_module['addClip'];
	}

	$xtpl->assign( 'INFO_TITLE', $informationtitle );
	$xtpl->assign( 'POST', $post );

	foreach ( $topicList as $_tid => $_value )
	{
		$option = array(
			'value' => $_tid,
			'name' => $_value['title'],
			'selected' => $_tid == $post['tid'] ? " selected=\"selected\"" : "" );
		$xtpl->assign( 'OPTION3', $option );
		$xtpl->parse( 'add.option3' );
	}

	foreach ( $array_who as $key => $who )
	{
		$xtpl->assign( 'WHO_VIEW', array(
			'key' => $key,
			'title' => $who,
			'selected' => $key == $post['who_view'] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'add.who_view' );
	}

	if ( ! empty( $groups_list ) )
	{
		foreach ( $groups_list as $key => $title )
		{
			$xtpl->assign( 'GROUPS_VIEW', array(
				'key' => $key,
				'title' => $title,
				'checked' => in_array( $key, $post['groups_view'] ) ? " checked=\"checked\"" : "" ) );
			$xtpl->parse( 'add.group_view_empty.groups_view' );
		}
		$xtpl->parse( 'add.group_view_empty' );
	}

	if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$_cont = nv_aleditor( 'bodytext', '100%', '300px', $post['bodytext'] );
	}
	else
	{
		$_cont = "<textarea style=\"width:100%;height:300px\" name=\"bodytext\" id=\"bodytext\">" . $post['bodytext'] . "</textarea>";
	}
	$xtpl->assign( 'CONTENT', $_cont );

	$xtpl->parse( 'add' );
	$contents = $xtpl->text( 'add' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}

if ( $nv_Request->isset_request( 'changeStatus', 'post' ) )
{
	$id = $nv_Request->get_int( 'changeStatus', 'post', 0 );
	$sql = "SELECT `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	list( $status ) = $db->sql_fetchrow( $result );

	$newStatus = $status ? 0 : 1;
	$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_clip` SET `status`=" . $newStatus . " WHERE `id`=" . $id;
	$db->sql_query( $query );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['cstatus'], "Id: " . $id, $admin_info['userid'] );

	$alt = $newStatus ? $lang_module['status1'] : $lang_module['status0'];
	$icon = $newStatus ? "enabled" : "disabled";

	die( "<img style=\"vertical-align:middle;margin-right:10px\" alt=\"" . $alt . "\" title=\"" . $alt . "\" src=\"" . NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_data . "/" . $icon . ".png\" width=\"12\" height=\"12\" />" );
}
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
	$id = $nv_Request->get_int( 'del', 'post', 0 );
	$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_hit` WHERE `cid` = " . $id;
	$db->sql_query( $query );
	$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` WHERE `id` = " . $id;
	$db->sql_query( $query );
	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['delClip'], "Id: " . $id, $admin_info['userid'] );
	die( "OK" );
}

foreach ( $topicList as $id => $name )
{
	$option = array( 'id' => $id, 'name' => $name['title'] );
	$xtpl->assign( 'OPTION4', $option );
	$xtpl->parse( 'main.psopt4' );
}

$where = "";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
$ptitle = $lang_module['main'];
if ( $nv_Request->isset_request( 'tid', 'get' ) )
{
	$top = $nv_Request->get_int( 'tid', 'get', 0 );
	if ( isset( $topicList[$top] ) )
	{
		$where .= " WHERE `tid`=" . $top;
		$base_url .= "&tid=" . $top;
		$ptitle = sprintf( $lang_module['listClipByTid'], $topicList[$top]['title'] );
	}
}

$xtpl->assign( 'PTITLE', $ptitle );

$sql = "SELECT COUNT(*) as ccount FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip`" . $where;
$result = $db->sql_query( $sql );
$all_page = $db->sql_fetchrow( $result );
$all_page = $all_page['ccount'];

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 50;

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip`" . $where . " ORDER BY `addtime` DESC LIMIT " . $page . "," . $per_page;
$result = $db->sql_query( $sql );

$a = 0;
while ( $row = $db->sql_fetch_assoc( $result ) )
{
	$xtpl->assign( 'CLASS', $a % 2 ? " class=\"second\"" : "" );

	$row['adddate'] = date( "d-m-Y H:i", $row['addtime'] );
	$row['topicname'] = isset( $topicList[$row['tid']] ) ? $topicList[$row['tid']]['title'] : "";
	$row['icon'] = $row['status'] ? "enabled" : "disabled";
	$row['status'] = $row['status'] ? $lang_module['status1'] : $lang_module['status0'];
	$xtpl->assign( 'DATA', $row );
	$xtpl->parse( 'main.loop' );
	$a++;
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
if ( ! empty( $generate_page ) )
{
	$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
}
elseif ( $page )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	exit();
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>