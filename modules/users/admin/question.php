<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['question'];

// Sua cau hoi
if( $nv_Request->isset_request( 'edit', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$qid = $nv_Request->get_int( 'qid', 'post', 0 );
	$title = filter_text_input( 'title', 'post', '', 1 );

	if( empty( $title ) )
	{
		die( "NO" );
	}
	$sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_question` SET `title`=" . $db->dbescape( $title ) . ", `edit_time`=" . NV_CURRENTTIME . " 
    WHERE `qid`=" . $qid . " AND `lang`='" . NV_LANG_DATA . "'";
	$db->sql_query( $sql );
	if( ! $db->sql_affectedrows() )
	{
		die( "NO" );
	}
	die( "OK" );
}

// Them cau hoi
if( $nv_Request->isset_request( 'add', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$title = filter_text_input( 'title', 'post', '', 1 );
	if( empty( $title ) )
	{
		die( "NO" );
	}

	$sql = "SELECT MAX(`weight`) FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `lang`='" . NV_LANG_DATA . "'";
	list( $weight ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
	$weight = intval( $weight ) + 1;
	$query = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_question` (`qid`, `title`, `lang`, `weight`, `add_time`, `edit_time`) VALUES (
    NULL, " . $db->dbescape( $title ) . ", " . $db->dbescape( NV_LANG_DATA ) . ", " . $weight . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
	if( ! $db->sql_query_insert_id( $query ) )
	{
		die( "NO" );
	}
	die( "OK" );
}

// Chinh thu tu
if( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$qid = $nv_Request->get_int( 'qid', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

	if( empty( $qid ) ) die( "NO" );

	$query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `qid`=" . $qid . " AND `lang`='" . NV_LANG_DATA . "'";
	$result = $db->sql_query( $query );
	$numrows = $db->sql_numrows( $result );
	if( $numrows != 1 ) die( 'NO' );

	$query = "SELECT `qid` FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `qid`!=" . $qid . " AND `lang`='" . NV_LANG_DATA . "' ORDER BY `weight` ASC";
	$result = $db->sql_query( $query );
	$weight = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		++$weight;
		if( $weight == $new_vid ) ++$weight;
		$sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_question` SET `weight`=" . $weight . " WHERE `qid`=" . $row['qid'];
		$db->sql_query( $sql );
	}
	$sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_question` SET `weight`=" . $new_vid . " WHERE `qid`=" . $qid;
	$db->sql_query( $sql );
	die( "OK" );
}

// Xoa cau hoi
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$qid = $nv_Request->get_int( 'qid', 'post', 0 );

	list( $qid ) = $db->sql_fetchrow( $db->sql_query( "SELECT `qid` FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `qid`=" . $qid ) );

	if( $qid )
	{
		$query = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `qid`=" . $qid;
		if( $db->sql_query( $query ) )
		{
			$db->sql_freeresult();
			nv_fix_question();
			die( "OK" );
		}
	}
	die( "NO" );
}

$xtpl = new XTemplate( "question.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

// Danh sach cau hoi
if( $nv_Request->isset_request( 'qlist', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$sql = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "_question`  WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	$num = $db->sql_numrows( $result );

	if( $num )
	{
		$a = 0;
		while( $row = $db->sql_fetchrow( $result ) )
		{
			$xtpl->assign( 'ROW', array(
				"class" => ( $a % 2 ) ? " class=\"second\"" : "",
				"qid" => $row['qid'],
				"title" => $row['title'],
			) );

			for( $i = 1; $i <= $num; ++$i )
			{
				$xtpl->assign( 'WEIGHT', array(
					"key" => $i,
					"title" => $i,
					"selected" => $i == $row['weight'] ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.data.loop.weight' );
			}

			$xtpl->parse( 'main.data.loop' );
			++$a;
		}

		$xtpl->parse( 'main.data' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit;
}

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->parse( 'load' );
$contents = $xtpl->text( 'load' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>