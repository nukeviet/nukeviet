<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['categories'];

$error = $admins = "";
$savecat = 0;
list( $catid, $parentid, $title, $titlesite, $alias, $description, $keywords, $who_view, $groups_view ) = array( 0, 0, "", "", "", "", "", 0, "" );

$groups_list = nv_groups_list();
$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if( ! empty( $savecat ) )
{
	$catid = $nv_Request->get_int( 'catid', 'post', 0 );
	$parentid_old = $nv_Request->get_int( 'parentid_old', 'post', 0 );
	$parentid = $nv_Request->get_int( 'parentid', 'post', 0 );
	$title = filter_text_input( 'title', 'post', '', 1 );
	$titlesite = filter_text_input( 'titlesite', 'post', '', 1 );
	$keywords = filter_text_input( 'keywords', 'post', '', 1 );
	$alias = filter_text_input( 'alias', 'post', '' );
	$description = $nv_Request->get_string( 'description', 'post', '' );
	$description = nv_nl2br( nv_htmlspecialchars( strip_tags( $description ) ), '<br />' );
	$alias = ( $alias == "" ) ? change_alias( $title ) : change_alias( $alias );

	$who_view = $nv_Request->get_int( 'who_view', 'post', 0 );
	$groups_view = "";

	$groups = $nv_Request->get_typed_array( 'groups_view', 'post', 'int', array() );
	$groups = array_intersect( $groups, array_keys( $groups_list ) );
	$groups_view = implode( ",", $groups );

	if( ! defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		if( ! ( isset( $array_cat_admin[$admin_id][$parentid] ) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1 ) )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&parentid=" . $parentid );
			die();
		}
	}

	if( $catid == 0 and $title != "" )
	{
		list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $db->dbescape( $parentid ) ) );
		$weight = intval( $weight ) + 1;
		$viewcat = "viewcat_page_new";
		$subcatid = "";

		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_cat` (`catid`, `parentid`, `title`, `titlesite`, `alias`, `description`, `image`, `thumbnail`, `weight`, `order`, `lev`, `viewcat`, `numsubcat`, `subcatid`, `inhome`, `numlinks`, `keywords`, `admins`, `add_time`, `edit_time`, `who_view`, `groups_view`)
         VALUES (NULL, " . $db->dbescape( $parentid ) . ", " . $db->dbescape( $title ) . ", " . $db->dbescape( $titlesite ) . ", " . $db->dbescape( $alias ) . ", " . $db->dbescape( $description ) . ", '', '', " . $db->dbescape( $weight ) . ", '0', '0', " . $db->dbescape( $viewcat ) . ", '0', " . $db->dbescape( $subcatid ) . ", '1', '3', " . $db->dbescape( $keywords ) . ", " . $db->dbescape( $admins ) . ", UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), " . $db->dbescape( $who_view ) . "," . $db->dbescape( $groups_view ) . ")";

		$newcatid = ( int )$db->sql_query_insert_id( $sql );
		if( $newcatid > 0 )
		{
			$db->sql_freeresult();
			nv_create_table_rows( $newcatid );
			nv_fix_cat_order();
			
			if( ! defined( 'NV_IS_ADMIN_MODULE' ) )
			{
				$db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_admins` (`userid`, `catid`, `admin`, `add_content`, `pub_content`, `edit_content`, `del_content`, `comment`) VALUES ('" . $admin_id . "', '" . $newcatid . "', '1', '1', '1', '1', '1', '1')" );
			}
			
			nv_del_moduleCache( $module_name );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['add_cat'], $title, $admin_info['userid'] );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&parentid=" . $parentid );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	elseif( $catid > 0 and $title != "" )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `parentid`=" . $db->dbescape( $parentid ) . ", `title`=" . $db->dbescape( $title ) . ", `titlesite`=" . $db->dbescape( $titlesite ) . ", `alias` =  " . $db->dbescape( $alias ) . ", `description`=" . $db->dbescape( $description ) . ", `keywords`= " . $db->dbescape( $keywords ) . ", `who_view`=" . $db->dbescape( $who_view ) . ", `groups_view`=" . $db->dbescape( $groups_view ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `catid` =" . $catid;
		$db->sql_query( $sql );
		
		if( $db->sql_affectedrows() > 0 )
		{
			$db->sql_freeresult();
			
			if( $parentid != $parentid_old )
			{
				list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $db->dbescape( $parentid ) ) );
				$weight = intval( $weight ) + 1;
				
				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `weight`=" . $weight . " WHERE `catid`=" . intval( $catid );
				$db->sql_query( $sql );
				
				nv_fix_cat_order();
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_cat'], $title, $admin_info['userid'] );
			}
			
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&parentid=" . $parentid );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
		$db->sql_freeresult();
	}
	else
	{
		$error = $lang_module['error_name'];
	}
}

$parentid = $nv_Request->get_int( 'parentid', 'get,post', 0 );

$catid = $nv_Request->get_int( 'catid', 'get', 0 );
if( $catid > 0 and isset( $global_array_cat[$catid] ) )
{
	$parentid = $global_array_cat[$catid]['parentid'];
	$title = $global_array_cat[$catid]['title'];
	$titlesite = $global_array_cat[$catid]['titlesite'];
	$alias = $global_array_cat[$catid]['alias'];
	$description = $global_array_cat[$catid]['description'];
	$keywords = $global_array_cat[$catid]['keywords'];
	$who_view = $global_array_cat[$catid]['who_view'];
	$groups_view = $global_array_cat[$catid]['groups_view'];

	if( ! defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		if( ! ( isset( $array_cat_admin[$admin_id][$parentid] ) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1 ) )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&parentid=" . $parentid );
			die();
		}
	}

	$caption = $lang_module['edit_cat'];
	$array_in_cat = GetCatidInParent( $catid );
}
else
{
	$caption = $lang_module['add_cat'];
	$array_in_cat = array();
}
$groups_view = explode( ",", $groups_view );

$array_cat_list = array();
if( defined( 'NV_IS_ADMIN_MODULE' ) )
{
	$array_cat_list[0] = $lang_module['cat_sub_sl'];
}
foreach( $global_array_cat as $catid_i => $array_value )
{
	$lev_i = $array_value['lev'];
	if( defined( 'NV_IS_ADMIN_MODULE' ) or ( isset( $array_cat_admin[$admin_id][$catid_i] ) and $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 ) )
	{
		$xtitle_i = "";
		if( $lev_i > 0 )
		{
			$xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
			for( $i = 1; $i <= $lev_i; ++$i )
			{
				$xtitle_i .= "---";
			}
			$xtitle_i .= ">&nbsp;";
		}
		$xtitle_i .= $array_value['title'];
		$array_cat_list[$catid_i] = $xtitle_i;
	}
}

if( ! empty( $array_cat_list ) )
{
	$cat_listsub = array();
	while( list( $catid_i, $title_i ) = each( $array_cat_list ) )
	{
		if( ! in_array( $catid_i, $array_in_cat ) )
		{
			$cat_listsub[] = array(
				"value" => $catid_i,
				"selected" => ( $catid_i == $parentid ) ? " selected=\"selected\"" : "",
				"title" => $title_i
			);
		}
	}

	$who_views = array();
	foreach( $array_who_view as $k => $w )
	{
		$who_views[] = array(
			"value" => $k,
			"selected" => ( $who_view == $k ) ? " selected=\"selected\"" : "",
			"title" => $w
		);
	}

	$groups_views = array();
	foreach( $groups_list as $group_id => $grtl )
	{
		$groups_views[] = array(
			"value" => $group_id,
			"checked" => in_array( $group_id, $groups_view ) ? " checked=\"checked\"" : "",
			"title" => $grtl
		);
	}
}

$xtpl = new XTemplate( "cat.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'caption', $caption );
$xtpl->assign( 'catid', $catid );
$xtpl->assign( 'title', $title );
$xtpl->assign( 'titlesite', $titlesite );
$xtpl->assign( 'alias', $alias );
$xtpl->assign( 'parentid', $parentid );
$xtpl->assign( 'keywords', $keywords );
$xtpl->assign( 'description', $description );

$xtpl->assign( 'CAT_LIST', nv_show_cat_list( $parentid ) );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( ! empty( $array_cat_list ) )
{
	if( empty( $alias ) )
	{
		$xtpl->parse( 'main.content.getalias' );
	}

	foreach( $cat_listsub as $data )
	{
		$xtpl->assign( 'cat_listsub', $data );
		$xtpl->parse( 'main.content.cat_listsub' );
	}

	foreach( $who_views as $data )
	{
		$xtpl->assign( 'who_views', $data );
		$xtpl->parse( 'main.content.who_views' );
	}

	foreach( $groups_views as $data )
	{
		$xtpl->assign( 'groups_views', $data );
		$xtpl->parse( 'main.content.groups_views' );
	}

	$xtpl->assign( 'hidediv', $who_view == 3 ? "visibility:visible;display:block;" : "visibility:hidden;display:none;" );

	$xtpl->parse( 'main.content' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>