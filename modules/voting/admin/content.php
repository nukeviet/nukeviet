<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['voting_edit'];

$error = '';
$vid = $nv_Request->get_int( 'vid', 'post,get' );
$submit = $nv_Request->get_string( 'submit', 'post' );

if( ! empty( $submit ) )
{
	$question = filter_text_input( 'question', 'post', '', 1 );
	$link = filter_text_input( 'link', 'post', '', 1 );
	$who_view = $nv_Request->get_int( 'who_view', 'post', 0 );
	$groups_view = $nv_Request->get_array( 'groups_view', 'post' );
	$groups_view = implode( ',', $groups_view );

	$publ_date = filter_text_input( 'publ_date', 'post', '' );
	$exp_date = filter_text_input( 'exp_date', 'post', '' );
	$maxoption = $nv_Request->get_int( 'maxoption', 'post', 1 );

	$array_answervote = $nv_Request->get_array( 'answervote', 'post' );
	$array_urlvote = $nv_Request->get_array( 'urlvote', 'post' );

	$answervotenews = $nv_Request->get_array( 'answervotenews', 'post' );
	$urlvotenews = $nv_Request->get_array( 'urlvotenews', 'post' );
	if( $maxoption > ( $sizeof = sizeof( $answervotenews ) + sizeof( $array_answervote ) ) || $maxoption <= 0 ) $maxoption = $sizeof;

	if( preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $publ_date, $m ) )
	{
		$phour = $nv_Request->get_int( 'phour', 'post', 0 );
		$pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
		$begindate = mktime( $phour, $pmin, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$begindate = NV_CURRENTTIME;
	}
	if( preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $exp_date, $m ) )
	{
		$ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
		$emin = $nv_Request->get_int( 'emin', 'post', 0 );
		$enddate = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$enddate = 0;
	}

	$number_answer = 0;
	foreach( $array_answervote as $title )
	{
		$title = trim( strip_tags( $title ) );
		if( $title != "" )
		{
			++$number_answer;
		}
	}
	foreach( $answervotenews as $title )
	{
		$title = trim( strip_tags( $title ) );
		if( $title != "" )
		{
			++$number_answer;
		}
	}
	$rowvote = array(
		"who_view" => 0,
		"groups_view" => "",
		"publ_time" => $begindate,
		"exp_time" => $enddate,
		"acceptcm" => $maxoption,
		"question" => $question,
		"link" => $link
	);

	if( ! empty( $question ) and $number_answer > 1 )
	{
		$error = $lang_module['voting_error'];
		
		if( empty( $vid ) )
		{
			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` (`vid`, `question`, `link`, `acceptcm`, `admin_id`, `who_view`, `groups_view`, `publ_time`, `exp_time`, `act`) VALUES (NULL, " . $db->dbescape( $question ) . ", " . $db->dbescape( $link ) . ", " . $maxoption . "," . $admin_info['admin_id'] . ", " . $who_view . ", " . $db->dbescape( $groups_view ) . ", 0,0,1)";
			$vid = $db->sql_query_insert_id( $sql );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['voting_add'], $question, $admin_info['userid'] );
		}
		if( $vid > 0 )
		{
			$maxoption_data = 0;
			foreach( $array_answervote as $id => $title )
			{
				$title = nv_htmlspecialchars( strip_tags( $title ) );
				if( $title != "" )
				{
					$url = nv_unhtmlspecialchars( strip_tags( $array_urlvote[$id] ) );
					$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `title` = " . $db->dbescape( $title ) . ", `url` = " . $db->dbescape( $url ) . " WHERE `id` ='" . intval( $id ) . "' AND `vid` =" . $vid . "" );
					++$maxoption_data;
				}
				else
				{
					$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` ='" . intval( $id ) . "' AND `vid` =" . $vid . "" );
				}
			}

			foreach( $answervotenews as $key => $title )
			{
				$title = nv_htmlspecialchars( strip_tags( $title ) );
				if( $title != "" )
				{
					$url = nv_unhtmlspecialchars( strip_tags( $urlvotenews[$key] ) );

					$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_rows` (`id`, `vid`, `title`, `url`, `hitstotal`) VALUES (NULL, " . $db->dbescape( $vid ) . ", " . $db->dbescape( $title ) . ", " . $db->dbescape( $url ) . ", '0')";
					if( $db->sql_query_insert_id( $sql ) )
					{
						++$maxoption_data;
					}
				}
			}

			if( $maxoption > $maxoption_data )
			{
				$maxoption = $maxoption_data;
			}

			if( $begindate > NV_CURRENTTIME or ( $enddate > 0 and $enddate < NV_CURRENTTIME ) )
			{
				$act = 0;
			}
			else
			{
				$act = 1;
			}
			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `question`=" . $db->dbescape( $question ) . ", `link`=" . $db->dbescape( $link ) . ", `acceptcm` =  " . $maxoption . ", `admin_id` =  " . $admin_info['admin_id'] . ", `who_view`=" . $who_view . ", `groups_view` = " . $db->dbescape( $groups_view ) . ", `publ_time`=" . $begindate . ", `exp_time`=" . $enddate . ", `act`=" . $act . " WHERE `vid` =" . $vid . "";
			if( $db->sql_query( $sql ) )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['voting_edit'], $question, $admin_info['userid'] );
				nv_del_moduleCache( $module_name );
				$error = "";
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
				die();
			}
		}
	}
	else
	{
		$error = $lang_module['voting_error_content'];
	}

	foreach( $answervotenews as $key => $title )
	{
		$title = trim( strip_tags( $title ) );
		if( $title != "" )
		{
			$array_answervote[] = $title;
			$array_urlvote[] = $urlvotenews[$key];
		}
	}
}
else
{
	$maxoption = 1;
	$array_answervote = array();
	$array_urlvote = array();
	if( $vid > 0 )
	{
		$queryvote = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE vid=" . $vid . "";
		$rowvote = $db->sql_fetchrow( $db->sql_query( $queryvote ) );

		$sql = "SELECT `id`, `title`, `url` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `vid`='" . $vid . "' ORDER BY `id` ASC";
		$result = $db->sql_query( $sql );
		$maxoption = $db->sql_numrows( $result );
		$maxoption = ( $maxoption > 0 ) ? $maxoption : 1;

		while( list( $id, $title, $url ) = $db->sql_fetchrow( $result ) )
		{
			$array_answervote[$id] = $title;
			$array_urlvote[$id] = $url;
		}
	}
	else
	{
		$rowvote = array(
			"who_view" => 0,
			"groups_view" => "",
			"publ_time" => NV_CURRENTTIME,
			"exp_time" => "",
			"acceptcm" => 1,
			"question" => "",
			"link" => ""
		);
	}
}

$my_head = "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.css\" rel=\"stylesheet\" />\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\">\$(document).ready(function(){\$(\"#votingcontent\").validate();});</script>";

$xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;vid=" . $vid );

$rowvote['link'] = nv_htmlspecialchars( $rowvote['link'] );
$xtpl->assign( 'DATA', $rowvote );

if( $error != "" )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$array_who_view = array( $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] );
$array_allowed_comm = array( $lang_global['no'], $lang_global['who_view0'], $lang_global['who_view1'] );

$groups_list = nv_groups_list();
$tdate = date( "d|m|Y|H|i" );
list( $pday, $pmonth, $pyear, $phour, $pmin ) = explode( "|", $tdate );
$emonth = $eday = $eyear = $emin = $ehour = 0;

$who_view = $rowvote['who_view'];
foreach( $array_who_view as $k => $w )
{
	$xtpl->assign( 'WHO_VIEW', array( "key" => $k, "title" => $w, "selected" => $who_view == $k ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.who_view' );
}

$xtpl->assign( 'SHOW_GROUPS_LIST', $who_view == 3 ? "visibility:visible;display:block;" : "visibility:hidden;display:none;" );

$groups_view = explode( ',', $rowvote['groups_view'] );
foreach( $groups_list as $group_id => $grtl )
{
	$xtpl->assign( 'GROUPS_VIEW', array( "key" => $group_id, "title" => $grtl, "checked" => in_array( $group_id, $groups_view ) ? " checked=\"checked\"" : "" ) );
	$xtpl->parse( 'main.groups_view' );
}

$tdate = date( "H|i", $rowvote['publ_time'] );
$publ_date = date( "d/m/Y", $rowvote['publ_time'] );
list( $phour, $pmin ) = explode( "|", $tdate );

// Thoi gian dang
$xtpl->assign( 'PUBL_DATE', $publ_date );
for( $i = 0; $i <= 23; ++ $i )
{
	$xtpl->assign( 'PHOUR', array( "key" => $i, "title" => str_pad( $i, 2, "0", STR_PAD_LEFT ), "selected" => $i == $phour ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.phour' );
}
for( $i = 0; $i < 60; ++$i )
{
	$xtpl->assign( 'PMIN', array( "key" => $i, "title" => str_pad( $i, 2, "0", STR_PAD_LEFT ), "selected" => $i == $pmin ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.pmin' );
}

// Thoi gian ket thuc
if( $rowvote['exp_time'] > 0 )
{
	$tdate = date( "H|i", $rowvote['exp_time'] );
	$exp_date = date( "d/m/Y", $rowvote['exp_time'] );
	list( $ehour, $emin ) = explode( "|", $tdate );
}
else
{
	$emin = $ehour = 0;
	$exp_date = "";
}
$xtpl->assign( 'EXP_DATE', $exp_date );
for( $i = 0; $i <= 23; ++ $i )
{
	$xtpl->assign( 'EHOUR', array( "key" => $i, "title" => str_pad( $i, 2, "0", STR_PAD_LEFT ), "selected" => $i == $ehour ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.ehour' );
}
for( $i = 0; $i < 60; ++ $i )
{
	$xtpl->assign( 'EMIN', array( "key" => $i, "title" => str_pad( $i, 2, "0", STR_PAD_LEFT ), "selected" => $i == $emin ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.emin' );
}

$items = 0;
$j = 0;
foreach( $array_answervote as $id => $title )
{
	++ $j;
	
	$xtpl->assign( 'ITEM', array(
		"class" => ( $j % 2 == 0 ) ? " class=\"second\"" : "",
		"stt" => ++ $items,
		"id" => $id,
		"title" => $title,
		"link" => nv_htmlspecialchars( $array_urlvote[$id] ),
	) );
	
	$xtpl->parse( 'main.item' );
}

++ $j;
$class = ( $j % 2 == 0 ) ? " class=\"second additem\"" : " class=\"additem\"";
$xtpl->assign( 'NEW_CLASS', $class );
$xtpl->assign( 'NEW_ITEM', ++ $items );
$xtpl->assign( 'NEW_ITEM_NUM', $items );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
if( $vid ) $op = ''; 

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>