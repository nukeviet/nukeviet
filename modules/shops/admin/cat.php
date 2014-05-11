<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['cat_title'];

$table_name = $db_config['prefix'] . '_' . $module_data . '_catalogs';
$error = $admins = '';
$savecat = 0;
$data = array();
$groups_list = nv_groups_list();

list( $data['catid'], $data['parentid'], $data['title'], $data['alias'], $data['description'], $data['keywords'], $data['groups_view'] ) = array( 0, 0, '', '', '', '', '6' );

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );

if( ! empty( $savecat ) )
{
	$field_lang = nv_file_table( $table_name );

	$data['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$data['parentid_old'] = $nv_Request->get_int( 'parentid_old', 'post', 0 );
	$data['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$data['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$data['keywords'] = nv_substr( $nv_Request->get_title( 'keywords', 'post', '', 1 ), 0, 255 );
	$data['alias'] = nv_substr( $nv_Request->get_title( 'alias', 'post', '', 1 ), 0, 255 );
	$data['description'] = $nv_Request->get_string( 'description', 'post', '' );
	$data['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $data['description'] ) ), '<br />' );

	$data['alias'] = ( $data['alias'] == '' ) ? change_alias( $data['title'] ) : change_alias( $data['alias'] );

	// Cat mo ta cho chinh xac
	if( strlen( $data['description'] ) > 255 )
	{
		$data['description'] = nv_clean60( $data['description'], 250 );
	}

	$_groups_post = $nv_Request->get_array( 'groups_view', 'post', array() );
	$data['groups_view'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	if( $data['title'] == '' )
	{
		$error = $lang_module['error_cat_name'];
	}

	$stmt = $db->prepare( 'SELECT count(*) FROM ' . $table_name . ' WHERE catid!=' . $data['catid'] . ' AND ' . NV_LANG_DATA . '_alias= :alias' );
	$stmt->bindParam( ':alias', $data['alias'], PDO::PARAM_STR );
	$stmt->execute();
	$check_alias = $stmt->fetchColumn();

	if( $check_alias and $data['parentid'] > 0 )
	{
		$parentid_alias = $db->query( 'SELECT ' . NV_LANG_DATA . '_alias FROM ' . $table_name . ' WHERE catid=' . $data['parentid'] )->fetchColumn();
		$data['alias'] = $parentid_alias . '-' . $data['alias'];
	}

	if( $data['catid'] == 0 and $data['title'] != '' and $error == '' )
	{
		$listfield = '';
		$listvalue = '';
		foreach( $field_lang as $field_lang_i )
		{
			list( $flang, $fname ) = $field_lang_i;
			$listfield .= ', ' . $flang . '_' . $fname;
			$listvalue .= ', :' . $flang . '_' . $fname;
		}
		$stmt = $db->prepare( 'SELECT max(weight) FROM ' . $table_name . ' WHERE parentid= :parentid' );
		$stmt->bindParam( ':parentid', $data['parentid'], PDO::PARAM_INT );
		$stmt->execute();
		$weight = $stmt->fetchColumn();

		$weight = intval( $weight ) + 1;

		$viewcat = 'viewcat_page_list';
		$subcatid = '';

		$sql = "INSERT INTO " . $table_name . " (catid, parentid, image, thumbnail, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, admins, add_time, edit_time, groups_view " . $listfield . " )
 			VALUES (NULL, :parentid, ' ', ' '," . $weight . ", '0', '0', :viewcat, '0', :subcatid, '1', '4', :admins, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", :groups_view" . $listvalue . ")";
		$data_insert = array();
		$data_insert['parentid'] = $data['parentid'];
		$data_insert['subcatid'] = $subcatid;
		$data_insert['viewcat'] = $viewcat;
		$data_insert['admins'] = $admins;
		$data_insert['groups_view'] = $data['groups_view'];
		foreach( $field_lang as $field_lang_i )
		{
			list( $flang, $fname ) = $field_lang_i;
			$data_insert[$flang . '_' . $fname] = $data[$fname];
		}

		$newcatid = intval( $db->insert_id( $sql, 'catid', $data_insert ) );
		if( $newcatid > 0 )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_catalog', 'id ' . $newcatid, $admin_info['userid'] );
			nv_fix_cat_order();
			nv_del_moduleCache( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $data['parentid'] );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	elseif( $data['catid'] > 0 and $data['title'] != '' and $error == '' )
	{
		try
		{
			$stmt = $db->prepare( "UPDATE " . $table_name . " SET parentid= :parentid, " . NV_LANG_DATA . "_title= :title, " . NV_LANG_DATA . "_alias = :alias, " . NV_LANG_DATA . "_description= :description, " . NV_LANG_DATA . "_keywords= :keywords, groups_view= :groups_view, edit_time=" . NV_CURRENTTIME . " WHERE catid =" . $data['catid'] );
			$stmt->bindParam( ':parentid', $data['parentid'], PDO::PARAM_INT );
			$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $data['alias'], PDO::PARAM_STR );
			$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );
			$stmt->bindParam( ':keywords', $data['keywords'], PDO::PARAM_STR );
			$stmt->bindParam( ':groups_view', $data['groups_view'], PDO::PARAM_STR );
			if( $stmt->execute() )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_catalog', 'id ' . $data['catid'], $admin_info['userid'] );

				if( $data['parentid'] != $data['parentid_old'] )
				{
					$stmt = $db->prepare( 'SELECT max(weight) FROM ' . $table_name . ' WHERE parentid= :parentid ' );
					$stmt->bindParam( ':parentid', $data['parentid'], PDO::PARAM_INT );
					$stmt->execute();
					$weight->fetchColumn();
					$weight = intval( $weight ) + 1;
					$sql = 'UPDATE ' . $table_name . ' SET weight=' . $weight . ' WHERE catid=' . intval( $data['catid'] );
					$db->query( $sql );
					nv_fix_cat_order();
				}
				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $data['parentid'] );
				die();
			}
		}
		catch( PDOException $e )
		{
			$error = $lang_module['errorsave'];
		}
	}
}

$data['parentid'] = $nv_Request->get_int( 'parentid', 'get,post', 0 );

$data['catid'] = $nv_Request->get_int( 'catid', 'get', 0 );
if( $data['catid'] > 0 )
{
	list( $data['catid'], $data['parentid'], $data['title'], $data['alias'], $data['description'], $data['keywords'], $data['groups_view'] ) = $db->query( 'SELECT catid, parentid, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_description, ' . NV_LANG_DATA . '_keywords, groups_view FROM ' . $table_name . ' where catid=' . $data['catid'] )->fetch( 3 );
	$caption = $lang_module['edit_cat'];
}
else
{
	$caption = $lang_module['add_cat'];
}

$sql = 'SELECT catid, ' . NV_LANG_DATA . '_title, lev FROM ' . $table_name . ' WHERE catid !=' . $data['catid'] . ' ORDER BY sort ASC';
$result = $db->query( $sql );
$array_cat_list = array();
$array_cat_list[0] = array( '0', $lang_module['cat_sub_sl'] );

while( list( $catid_i, $title_i, $lev_i ) = $result->fetch( 3 ) )
{
	$xtitle_i = '';
	if( $lev_i > 0 )
	{
		$xtitle_i .= '&nbsp;';
		for( $i = 1; $i <= $lev_i; $i++ )
		{
			$xtitle_i .= '---';
		}
	}
	$xtitle_i .= $title_i;
	$array_cat_list[] = array( $catid_i, $xtitle_i );
}

$xtpl = new XTemplate( 'cat_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'caption', $caption );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'CAT_LIST', nv_show_cat_list( $data['parentid'] ) );

if( $error != '' )
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

$groups_view = explode( ',', $data['groups_view'] );
foreach( $groups_list as $_group_id => $_title )
{
	$xtpl->assign( 'GROUPS_VIEW', array(
		'value' => $_group_id,
		'checked' => in_array( $_group_id, $groups_view ) ? ' checked="checked"' : '',
		'title' => $_title
	) );
	$xtpl->parse( 'main.groups_view' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';