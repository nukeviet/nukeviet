<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['block'];

$error = '';
$savecat = 0;
$data = array(
	'bid' => 0,
	'title' => '',
	'alias' => '',
	'description' => '',
	'keywords' => ''
);

$table_name = $db_config['prefix'] . '_' . $module_data . '_block_cat';
$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );

if( ! empty( $savecat ) )
{
	$field_lang = nv_file_table( $table_name );

	$data['bid'] = $nv_Request->get_int( 'bid', 'post', 0 );
	$data['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$data['keywords'] = nv_substr( $nv_Request->get_title( 'keywords', 'post', '', 1 ), 0, 255 );
	$data['alias'] = nv_substr( $nv_Request->get_title( 'alias', 'post', '', 1 ), 0, 255 );
	$data['description'] = $nv_Request->get_string( 'description', 'post', '' );
	$data['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $data['description'] ) ), '<br />' );

	// Cat mo ta cho chinh xac
	if( strlen( $data['description'] ) > 255 )
	{
		$data['description'] = nv_clean60( $data['description'], 250 );
	}

	$data['alias'] = ( $data['alias'] == '' ) ? change_alias( $data['title'] ) : change_alias( $data['alias'] );

	// Kiem tra loi
	if( empty( $data['title'] ) )
	{
		$error = $lang_module['block_error_name'];
	}
	else
	{
		if( $data['bid'] == 0 )
		{
			$stmt = $db->prepare( 'SELECT bid FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat WHERE ' . NV_LANG_DATA . '_alias= :alias' );
			$stmt->bindParam( ':alias', $data['alias'], PDO::PARAM_STR );
			$stmt->execute();
			if( $stmt->rowCount() )
			{
				$error = $lang_module['block_error_alias'];
			}
			else
			{
				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$listfield = '';
				$listvalue = '';

				foreach( $field_lang as $field_lang_i )
				{
					list( $flang, $fname ) = $field_lang_i;
					$listfield .= ', ' . $flang . '_' . $fname;
					$listvalue .= ', :' . $flang . '_' . $fname;
				}

				$sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block_cat (bid, adddefault,image, weight, add_time, edit_time " . $listfield . ") VALUES (NULL, 0, '', " . $weight . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . " " . $listvalue . ")";

				$data_insert = array();
				foreach( $field_lang as $field_lang_i )
				{
					list( $flang, $fname ) = $field_lang_i;
					$data_insert[$flang . '_' . $fname] = $data[$fname];
				}

				if( $db->insert_id( $sql, 'bid', $data_insert ) )
				{
					nv_del_moduleCache( $module_name );
					Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
					die();
				}
				else
				{
					$error = $lang_module['errorsave'];
				}
			}
		}
		else
		{
			$stmt = $db->prepare( 'SELECT bid FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat WHERE ' . NV_LANG_DATA . '_alias= :alias AND bid!=' . $data['bid'] );
			$stmt->bindParam( ':alias', $data['alias'], PDO::PARAM_STR );
			$stmt->execute();
			if( $stmt->rowCount() )
			{
				$error = $lang_module['block_error_alias'];
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_block_cat SET ' . NV_LANG_DATA . '_title= :title, ' . NV_LANG_DATA . '_alias = :alias, ' . NV_LANG_DATA . '_description= :description, ' . NV_LANG_DATA . '_keywords= :keywords, edit_time=' . NV_CURRENTTIME . ' WHERE bid =' . $data['bid'] );
				$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
				$stmt->bindParam( ':alias', $data['alias'], PDO::PARAM_STR );
				$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );
				$stmt->bindParam( ':keywords', $data['keywords'], PDO::PARAM_STR );
				if( $stmt->execute() )
				{
					$error = $lang_module['saveok'];
					nv_del_moduleCache( $module_name );
					Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
					die();
				}
				else
				{
					$error = $lang_module['errorsave'];
				}
			}
		}
	}
}

$xtpl = new XTemplate( 'blockcat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'BLOCK_CAT_LIST', nv_show_block_cat_list() );

$data['bid'] = $nv_Request->get_int( 'bid', 'get', 0 );
if( $data['bid'] > 0 )
{
	list( $data['bid'], $data['title'], $data['alias'], $data['description'], $data['keywords'] ) = $db->query( 'SELECT bid, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_description, ' . NV_LANG_DATA . '_keywords FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat where bid=' . $data['bid'] )->fetch( 3 );
	$lang_module['add_block_cat'] = $lang_module['edit_block_cat'];
}

$xtpl->assign( 'DATA', $data );

if( $error != '' )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( $data['alias'] != '' )
{
	$xtpl->parse( 'main.alias' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';