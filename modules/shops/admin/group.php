<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['group'];

$table_name = $db_config['prefix'] . "_" . $module_data . "_group";
$error = $admins = "";
$savegroup = 0;
$data = array();
list( $data['groupid'], $data['parentid'], $data['title'], $data['alias'], $data['description'], $data['keywords'], $data['who_view'], $groups_view, $data['cateid'], $data['numpro'] ) = array( 0, 0, "", "", "", "", 0, "", 0, 0 );
$groups_list = nv_groups_list();

$savegroup = $nv_Request->get_int( 'savegroup', 'post', 0 );

if( ! empty( $savegroup ) )
{
	$field_lang = nv_file_table( $table_name );
	
	$data['groupid'] = $nv_Request->get_int( 'groupid', 'post', 0 );
	$data['parentid_old'] = $nv_Request->get_int( 'parentid_old', 'post', 0 );
	$data['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$data['cateid'] = $nv_Request->get_int( 'cateid', 'post', 0 );
	$data['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$data['keywords'] = filter_text_input( 'keywords', 'post', '', 1 );
	$data['alias'] = filter_text_input( 'alias', 'post', '', 1, 255 );
	$data['description'] = $nv_Request->get_string( 'description', 'post', '' );
	$data['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $data['description'] ) ), '<br />' );
	$data['alias'] = ( $data['alias'] == "" ) ? change_alias( $data['title'] ) : change_alias( $data['alias'] );

	$data['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
	$groups_view = "";

	$data['groups'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int', array() );
	$groups = array_intersect( $data['groups'], array_keys( $groups_list ) );
	$groups_view = implode( ",", $data['groups'] );

	if( $data['title'] == "" )
	{
		$error = $lang_module['error_group_name'];
	}
	
	list( $check_alias ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM " . $table_name . " WHERE groupid!=" . $data['groupid'] . " AND `" . NV_LANG_DATA . "_alias`=" . $db->dbescape( $data['alias'] ) ) );
	
	if( $check_alias and $data['parentid'] > 0 )
	{
		list( $parentid_alias ) = $db->sql_fetchrow( $db->sql_query( "SELECT `" . NV_LANG_DATA . "_alias` FROM " . $table_name . " WHERE `groupid`=" . $data['parentid'] ) );
		$data['alias'] = $parentid_alias . "-" . $data['alias'];
	}
	
	if( $data['groupid'] == 0 and $data['title'] != "" and $error == "" )
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
		
		list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(`weight`) FROM " . $table_name . " WHERE `parentid`=" . $db->dbescape( $data['parentid'] ) ) );
		$weight = intval( $weight ) + 1;
		
		$viewgroup = "viewgroup_page_list";
		$subgroupid = "";
		
		$sql = "INSERT INTO " . $table_name . " (`groupid`, `parentid`,`cateid`, `image`, `thumbnail`, `weight`, `order`, `lev`, `viewgroup`, `numsubgroup`, `subgroupid`, `inhome`, `numlinks`, `admins`, `add_time`, `edit_time`, `who_view`, `groups_view`,`numpro` " . $listfield . " ) 
         VALUES (NULL, " . $db->dbescape( $data['parentid'] ) . "," . $db->dbescape( $data['cateid'] ) . ",' ',' '," . $db->dbescape( $weight ) . ", '0', '0', " . $db->dbescape( $viewgroup ) . ", '0', " . $db->dbescape( $subgroupid ) . ", '1', '4'," . $db->dbescape( $admins ) . ", UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), " . $db->dbescape( $data['who_view'] ) . "," . $db->dbescape( $groups_view ) . ',0 ' . $listvalue . " )";

		$newgroupid = intval( $db->sql_query_insert_id( $sql ) );
		if( $newgroupid > 0 )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['add_group'], $data['title'], $admin_info['userid'] );
			$db->sql_freeresult();
			nv_fix_group_order();
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&parentid=" . $data['parentid'] );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	elseif( $data['groupid'] > 0 and $data['title'] != "" and $error == "" )
	{
		$sql = "UPDATE " . $table_name . " SET `parentid`=" . $db->dbescape( $data['parentid'] ) . ", `cateid`=" . $db->dbescape( $data['cateid'] ) . ", `" . NV_LANG_DATA . "_title`=" . $db->dbescape( $data['title'] ) . ", `" . NV_LANG_DATA . "_alias` =  " . $db->dbescape( $data['alias'] ) . ", `" . NV_LANG_DATA . "_description`=" . $db->dbescape( $data['description'] ) . ", `" . NV_LANG_DATA . "_keywords`= " . $db->dbescape( $data['keywords'] ) . ", `who_view`=" . $db->dbescape( $data['who_view'] ) . ", `groups_view`=" . $db->dbescape( $groups_view ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `groupid` =" . $data['groupid'];
		$db->sql_query( $sql );
		
		if( $db->sql_affectedrows() > 0 )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_group'], $data['title'], $admin_info['userid'] );
			$db->sql_freeresult();
			if( $data['parentid'] != $data['parentid_old'] )
			{
				list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM " . $table_name . " WHERE `parentid`=" . $db->dbescape( $data['parentid'] ) ) );
				$weight = intval( $weight ) + 1;
				$sql = "UPDATE " . $table_name . " SET `weight`=" . $weight . " WHERE `groupid`=" . intval( $data['groupid'] );
				$db->sql_query( $sql );
				nv_fix_group_order();
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
$data['groupid'] = $nv_Request->get_int( 'groupid', 'get', 0 );

if( $data['groupid'] > 0 )
{
	list( $data['groupid'], $data['parentid'], $data['cateid'], $data['title'], $data['alias'], $data['description'], $data['keywords'], $data['who_view'], $data['groups_view'] ) = $db->sql_fetchrow( $db->sql_query( "SELECT `groupid`, `parentid`,`cateid`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_description`, `" . NV_LANG_DATA . "_keywords`, `who_view`, `groups_view`  FROM " . $table_name . " where `groupid`=" . $data['groupid'] ) );
	$caption = $lang_module['edit_group'];
}
else
{
	$caption = $lang_module['add_group'];
}
$groups_view = explode( ",", $groups_view );

$sql = "SELECT `groupid`, `" . NV_LANG_DATA . "_title`, `lev` FROM " . $table_name . " WHERE `groupid` !='" . $data['groupid'] . "' ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
$array_group_list = array();
$array_group_list[0] = array( '0', $lang_module['group_sub_sl'] );

while( list( $groupid_i, $title_i, $lev_i ) = $db->sql_fetchrow( $result ) )
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
	$array_group_list[] = array( $groupid_i, $xtitle_i );
}

$xtpl = new XTemplate( "group_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'caption', $caption );
$xtpl->assign( 'who_view', $lang_global['who_view'] );
$xtpl->assign( 'groups_view', $lang_global['groups_view'] );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=getcatalog&pid=" . $data['parentid'] . "&cid=" . $data['cateid'] );
$xtpl->assign( 'GROUP_LIST', nv_show_group_list( $data['parentid'] ) );

if( $error != "" )
{
	$xtpl->assign( 'error', $error );
	$xtpl->parse( 'main.error' );
}

foreach( $array_group_list as $rows_i )
{
	$sl = ( $rows_i[0] == $data['parentid'] ) ? " selected=\"selected\"" : "";
	$xtpl->assign( 'pgroup_i', $rows_i[0] );
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