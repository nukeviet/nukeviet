<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['cat_title'];

$table_name = $db_config['prefix'] . "_" . $module_data . "_catalogs";
$error = $admins = "";
$savecat = 0;
$data = array();

list( $data['catid'], $data['parentid'], $data['title'], $data['alias'], $data['description'], $data['keywords'], $data['who_view'], $groups_view ) = array( 0, 0, "", "", "", "", 0, "" );
$groups_list = nv_groups_list();

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );

if( ! empty( $savecat ) )
{
	$field_lang = nv_file_table( $table_name );
	
	$data['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$data['parentid_old'] = $nv_Request->get_int( 'parentid_old', 'post', 0 );
	$data['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$data['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$data['keywords'] = filter_text_input( 'keywords', 'post', '', 1, 255 );
	$data['alias'] = filter_text_input( 'alias', 'post', '', 1, 255 );
	$data['description'] = $nv_Request->get_string( 'description', 'post', '' );
	$data['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $data['description'] ) ), '<br />' );
	
	$data['alias'] = ( $data['alias'] == "" ) ? change_alias( $data['title'] ) : change_alias( $data['alias'] );
	
	// Cat mo ta cho chinh xac
	if( strlen( $data['description'] ) > 255 )
	{
		$data['description'] = nv_clean60( $data['description'], 250 );
	}

	$data['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
	$groups_view = "";

	$data['groups'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int', array() );
	$groups = array_intersect( $data['groups'], array_keys( $groups_list ) );
	$groups_view = implode( ",", $data['groups'] );

	if( $data['title'] == "" )
	{
		$error = $lang_module['error_cat_name'];
	}
	
	list( $check_alias ) = $db->sql_fetchrow( $db->sql_query( "SELECT count(*) FROM `" . $table_name . "` WHERE `catid`!=" . $data['catid'] . " AND `" . NV_LANG_DATA . "_alias`=" . $db->dbescape( $data['alias'] ) ) );
	
	if( $check_alias and $data['parentid'] > 0 )
	{
		list( $parentid_alias ) = $db->sql_fetchrow( $db->sql_query( "SELECT `" . NV_LANG_DATA . "_alias` FROM `" . $table_name . "` WHERE `catid`=" . $data['parentid'] ) );
		$data['alias'] = $parentid_alias . "-" . $data['alias'];
	}
	
	if( $data['catid'] == 0 and $data['title'] != "" and $error == "" )
	{
		$listfield = "";
		$listvalue = "";
		foreach( $field_lang as $field_lang_i )
		{
			list( $flang, $fname ) = $field_lang_i;
			$listfield .= ", `" . $flang . "_" . $fname . "`";
			if( $flang == NV_LANG_DATA )
			{
				$listvalue .= ", " . $db->dbescape( $data[$fname] );
			}
			else
			{
				$listvalue .= ", " . $db->dbescape( $data[$fname] );
			}
		}
		list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . $table_name . "` WHERE `parentid`=" . $db->dbescape( $data['parentid'] ) ) );
		
		$weight = intval( $weight ) + 1;
		
		$viewcat = "viewcat_page_list";
		$subcatid = "";
		
		$sql = "INSERT INTO `" . $table_name . "` (`catid`, `parentid`, `image`, `thumbnail`, `weight`, `order`, `lev`, `viewcat`, `numsubcat`, `subcatid`, `inhome`, `numlinks`, `admins`, `add_time`, `edit_time`, `who_view`, `groups_view` " . $listfield . " ) 
         VALUES (NULL, " . $db->dbescape( $data['parentid'] ) . ",' ',' '," . $db->dbescape( $weight ) . ", '0', '0', " . $db->dbescape( $viewcat ) . ", '0', " . $db->dbescape( $subcatid ) . ", '1', '4'," . $db->dbescape( $admins ) . ", UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), " . $db->dbescape( $data['who_view'] ) . "," . $db->dbescape( $groups_view ) . $listvalue . " )";
		
		$newcatid = intval( $db->sql_query_insert_id( $sql ) );
		if( $newcatid > 0 )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_catalog', "id " . $newcatid, $admin_info['userid'] );
			$db->sql_freeresult();
			nv_fix_cat_order();
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&parentid=" . $data['parentid'] );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	elseif( $data['catid'] > 0 and $data['title'] != "" and $error == "" )
	{
		$sql = "UPDATE `" . $table_name . "` SET `parentid`=" . $db->dbescape( $data['parentid'] ) . ", `" . NV_LANG_DATA . "_title`=" . $db->dbescape( $data['title'] ) . ", `" . NV_LANG_DATA . "_alias` =  " . $db->dbescape( $data['alias'] ) . ", `" . NV_LANG_DATA . "_description`=" . $db->dbescape( $data['description'] ) . ", `" . NV_LANG_DATA . "_keywords`= " . $db->dbescape( $data['keywords'] ) . ", `who_view`=" . $db->dbescape( $data['who_view'] ) . ", `groups_view`=" . $db->dbescape( $groups_view ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `catid` =" . $data['catid'];
		$db->sql_query( $sql );
		
		if( $db->sql_affectedrows() > 0 )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_catalog', "id " . $data['catid'], $admin_info['userid'] );
			
			$db->sql_freeresult();
			if( $data['parentid'] != $data['parentid_old'] )
			{
				list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . $table_name . "` WHERE `parentid`=" . $db->dbescape( $data['parentid'] ) . "" ) );
				$weight = intval( $weight ) + 1;
				$sql = "UPDATE `" . $table_name . "` SET `weight`=" . $weight . " WHERE `catid`=" . intval( $data['catid'] );
				$db->sql_query( $sql );
				nv_fix_cat_order();
			}
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&parentid=" . $data['parentid'] );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
		$db->sql_freeresult();
	}
}

$data['parentid'] = $nv_Request->get_int( 'parentid', 'get,post', 0 );

$data['catid'] = $nv_Request->get_int( 'catid', 'get', 0 );
if( $data['catid'] > 0 )
{
	list( $data['catid'], $data['parentid'], $data['title'], $data['alias'], $data['description'], $data['keywords'], $data['who_view'], $data['groups_view'] ) = $db->sql_fetchrow( $db->sql_query( "SELECT `catid`, `parentid`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_description`, `" . NV_LANG_DATA . "_keywords`, `who_view`, `groups_view`  FROM `" . $table_name . "` where `catid`=" . $data['catid'] . "" ) );
	$caption = $lang_module['edit_cat'];
}
else
{
	$caption = $lang_module['add_cat'];
}
$groups_view = explode( ",", $groups_view );

$sql = "SELECT `catid`, `" . NV_LANG_DATA . "_title`, `lev` FROM `" . $table_name . "` WHERE `catid` !='" . $data['catid'] . "' ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
$array_cat_list = array();
$array_cat_list[0] = array( '0', $lang_module['cat_sub_sl'] );

while( list( $catid_i, $title_i, $lev_i ) = $db->sql_fetchrow( $result ) )
{
	$xtitle_i = "";
	if( $lev_i > 0 )
	{
		$xtitle_i .= "&nbsp;";
		for( $i = 1; $i <= $lev_i; $i++ )
		{
			$xtitle_i .= "---";
		}
	}
	$xtitle_i .= $title_i;
	$array_cat_list[] = array( $catid_i, $xtitle_i );
}

$xtpl = new XTemplate( "cat_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'caption', $caption );
$xtpl->assign( 'who_view', $lang_global['who_view'] );
$xtpl->assign( 'groups_view', $lang_global['groups_view'] );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'CAT_LIST', nv_show_cat_list( $data['parentid'] ) );

if( $error != "" )
{
	$xtpl->assign( 'error', $error );
	$xtpl->parse( 'main.error' );
}

foreach( $array_cat_list as $rows_i )
{
	$sl = ( $rows_i[0] == $data['parentid'] ) ? " selected=\"selected\"" : "";
	$xtpl->assign( 'pcatid_i', $rows_i[0] );
	$xtpl->assign( 'ptitle_i', $rows_i[1] );
	$xtpl->assign( 'pselect', $sl );
	$xtpl->parse( 'main.parent_loop' );
}

$contents_html = "";
foreach( $array_who_view as $k => $w )
{
	$sl = ( $data['who_view'] == $k ) ? " selected=\"selected\"" : "";
	$contents_html .= "	<option value=\"" . $k . "\" " . $sl . ">" . $w . "</option>\n";
}
$xtpl->assign( 'who_view_html', $contents_html );

$visibility = ( $data['who_view'] == 3 ) ? "visibility:visible;display:block;" : "visibility:hidden;display:none;";
$xtpl->assign( 'visibility', $visibility );

$contents_html = "";
foreach( $groups_list as $group_id => $grtl )
{
	$contents_html .= "<p><input name=\"groups_view[]\" type=\"checkbox\" value=\"" . $group_id . "\"";
	if( in_array( $group_id, $groups_view ) ) $contents_html .= " checked=\"checked\"";
	$contents_html .= " />&nbsp;" . $grtl . "</p>\n";
}

$xtpl->assign( 'groups_list_html', $contents_html );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>