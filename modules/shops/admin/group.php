<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['group'];

$table_name = $db_config['prefix'] . '_' . $module_data . '_group';
$error = $admins = '';
$savegroup = 0;
$data = array();
$data['cateid_old'] = 0;
list( $data['groupid'], $data['parentid'], $data['title'], $data['alias'], $data['description'], $data['keywords'], $data['cateid'], $data['numpro'], $data['image'] ) = array( 0, 0, '', '', '', '', array(), 0, '' );

$data['parentid'] = $nv_Request->get_int( 'parentid', 'get,post', 0 );
$data['groupid'] = $nv_Request->get_int( 'groupid', 'get', 0 );
if( $data['groupid'] > 0 )
{
	list( $data['groupid'], $data['parentid'], $data['title'], $data['alias'], $data['description'], $data['keywords'], $data['image'], $data['require'] ) = $db->query( 'SELECT groupid, parentid, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_description, ' . NV_LANG_DATA . '_keywords, image, is_require FROM ' . $table_name . ' where groupid=' . $data['groupid'] )->fetch( 3 );
	$result = $db->query( 'SELECT cateid FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_cateid WHERE groupid = ' . $data['groupid'] . ' ORDER BY cateid' );
	if( $result )
	{
		while( $row = $result->fetchColumn() )
		{
			$data['cateid'][] = $row;
		}
	}

	$data['cateid_old'] = $data['cateid'];

	$data['require_ck'] = $data['require'] ? 'checked="checked"' : '';

	$caption = $lang_module['edit_group'];
}
else
{
	$caption = $lang_module['add_group'];
}

$savegroup = $nv_Request->get_int( 'savegroup', 'post', 0 );
if( ! empty( $savegroup ) )
{
	$field_lang = nv_file_table( $table_name );

	$data['groupid'] = $nv_Request->get_int( 'groupid', 'post', 0 );
	$data['parentid_old'] = $nv_Request->get_int( 'parentid_old', 'post', 0 );
	$data['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$data['cateid'] = $nv_Request->get_array( 'cateid', 'post', array() );
	$data['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$data['require'] = $nv_Request->get_int( 'require', 'post' );
	$data['keywords'] = $nv_Request->get_title( 'keywords', 'post', '', 1 );
	$data['alias'] = nv_substr( $nv_Request->get_title( 'alias', 'post', '', 1 ), 0, 255 );
	$data['description'] = $nv_Request->get_string( 'description', 'post', '' );
	$data['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $data['description'] ) ), '<br />' );
	$data['alias'] = ( $data['alias'] == '' ) ? change_alias( $data['title'] ) : change_alias( $data['alias'] );

	$image = $nv_Request->get_string( 'image', 'post', '' );
	if( is_file( NV_DOCUMENT_ROOT . $image ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' );
		$data['image'] = substr( $image, $lu );
	}
	else
	{
		$data['image'] = '';
	}

	if( $data['title'] == '' )
	{
		$error = $lang_module['group_name_empty'];
	}
	elseif( sizeof( $data['cateid'] ) == 0 and empty( $data['parentid'] ) )
	{
		$error = $lang_module['group_cateid_empty'];
	}

	$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . $table_name . ' WHERE groupid!=' . $data['groupid'] . ' AND ' . NV_LANG_DATA . '_alias= :alias' );

	$stmt->bindParam( ':alias', $data['alias'], PDO::PARAM_STR );
	$stmt->execute();
	$check_alias = $stmt->fetchColumn();
	if( $check_alias and $data['parentid'] > 0 )
	{
		$parentid_alias = $db->query( 'SELECT ' . NV_LANG_DATA . '_alias FROM ' . $table_name . ' WHERE groupid=' . $data['parentid'] )->fetchColumn();
		$data['alias'] = $parentid_alias . '-' . $data['alias'];
	}

	if( $data['groupid'] == 0 and $data['title'] != '' and $error == '' )
	{
		$listfield = '';
		$listvalue = '';
		foreach( $field_lang as $field_lang_i )
		{
			list( $flang, $fname ) = $field_lang_i;
			$listfield .= ', ' . $flang . '_' . $fname;
			if( $flang == NV_LANG_DATA )
			{
				$listvalue .= ', ' . $db->quote( $data[$fname] );
			}
			else
			{
				$listvalue .= ', ' . $db->quote( $data[$fname] );
			}
		}

		$_sql = 'SELECT max(weight) FROM ' . $table_name . ' WHERE parentid=' . $data['parentid'];
		$weight = $db->query( $_sql )->fetchColumn();
		$weight = intval( $weight ) + 1;

		$viewgroup = 'viewgroup_page_list';
		$subgroupid = '';

		$sql = "INSERT INTO " . $table_name . " (parentid, image,  weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require " . $listfield . " )
 			VALUES (" . $data['parentid'] . ", :image ," . (int)$weight . ", '0', '0', :viewgroup, '0', :subgroupid, '1', '1',  " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ",'0', 1, " . $data['require'] . " " . $listvalue . " )";

		$data_insert = array();
		$data_insert['viewgroup'] = $viewgroup;
		$data_insert['subgroupid'] = $subgroupid;
		$data_insert['image'] = $data['image'];
		$newgroupid = intval( $db->insert_id( $sql, 'groupid', $data_insert ) );

		if( $newgroupid > 0 )
		{
			// Cap nhat cateid
			foreach( $data['cateid'] as $cateid )
			{
				$db->query( 'INSERT INTO ' . $table_name . '_cateid (groupid, cateid) VALUES (' . $newgroupid .', ' . $cateid . ')' );
			}

			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_group', 'id ' . $newgroupid, $admin_info['userid'] );
			nv_fix_group_order();
			nv_del_moduleCache( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $data['parentid'] );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	elseif( $data['groupid'] > 0 and $data['title'] != '' and $error == '' )
	{
		try
		{
			$stmt = $db->prepare( 'UPDATE ' . $table_name . ' SET parentid=' . $data['parentid'] . ', image = :image, ' . NV_LANG_DATA . '_title= :title, ' . NV_LANG_DATA . '_alias = :alias, ' . NV_LANG_DATA . '_description= :description, ' . NV_LANG_DATA . '_keywords= :keywords, edit_time=' . NV_CURRENTTIME . ', is_require = ' . $data['require'] . ' WHERE groupid =' . $data['groupid'] );
			$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
			$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $data['alias'], PDO::PARAM_STR );
			$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );
			$stmt->bindParam( ':keywords', $data['keywords'], PDO::PARAM_STR );
			if( $stmt->execute() )
			{
				// Cap nhat cateid
				$data['cateid'] = array_map( 'intval', $data['cateid']);
				if( $data['cateid'] != $data['cateid_old'] )
				{
					foreach( $data['cateid'] as $cateid )
					{
						if( !in_array( $cateid, $data['cateid_old'] ) )
						{
							$db->query( 'INSERT INTO ' . $table_name . '_cateid (groupid, cateid) VALUES (' . $data['groupid'] .', ' . $cateid . ')' );
						}
					}

					foreach( $data['cateid_old'] as $cateid_old )
					{
						if( !in_array( $cateid_old, $data['cateid'] ) )
						{
							$db->query( 'DELETE FROM ' . $table_name . '_cateid WHERE cateid = ' . $cateid_old );
						}
					}
				}

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_group'], $data['title'], $admin_info['userid'] );
				if( $data['parentid'] != $data['parentid_old'] )
				{
					$stmt = $db->prepare( 'SELECT max(weight) FROM ' . $table_name . ' WHERE parentid= :parentid' );
					$stmt->bindParam( ':parentid', $data['parentid'], PDO::PARAM_INT );
					$stmt->execute();
					$weight = $stmt->fetchColumn();
					$weight = intval( $weight ) + 1;
					$sql = 'UPDATE ' . $table_name . ' SET weight=' . $weight . ' WHERE groupid=' . intval( $data['groupid'] );
					$db->query( $sql );
					nv_fix_group_order();
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

$sql = "SELECT groupid, " . NV_LANG_DATA . "_title, lev FROM " . $table_name . " WHERE groupid !='" . $data['groupid'] . "' ORDER BY sort ASC";
$result = $db->query( $sql );
$array_group_list = array();
$array_group_list[0] = array( '0', $lang_module['group_sub_sl'] );

while( list( $groupid_i, $title_i, $lev_i ) = $result->fetch( 3 ) )
{
	if( $lev_i == 0 )
	{
		$array_group_list[] = array( $groupid_i, $title_i );
	}
}

$lang_global['title_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 65 );
$lang_global['description_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 160 );

if( ! empty( $data['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $data['image'] ) )
{
	$data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data['image'];
}
$data['description'] = nv_br2nl( $data['description'] );

$xtpl = new XTemplate( 'group_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'CAPTION', $caption );

$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'URL', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=getcatalog&pid=' . $data['parentid'] . '&cid=' . nv_base64_encode( serialize( $data['cateid_old'] ) ) );
$xtpl->assign( 'GROUP_LIST', shops_show_group_list( $data['parentid'] ) );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;groupid=' . $data['groupid'] . '&amp;parentid=' . $data['parentid'] );

if( $error != '' )
{
	$xtpl->assign( 'error', $error );
	$xtpl->parse( 'main.error' );
}

foreach( $array_group_list as $rows_i )
{
	$sl = ( $rows_i[0] == $data['parentid'] ) ? ' selected="selected"' : '';
	$xtpl->assign( 'pgroup_i', $rows_i[0] );
	$xtpl->assign( 'ptitle_i', $rows_i[1] );
	$xtpl->assign( 'pselect', $sl );
	$xtpl->parse( 'main.parent_loop' );
}

if( empty( $data['alias'] ) )
{
	$xtpl->parse( 'main.getalias' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';