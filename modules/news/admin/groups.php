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
list( $bid, $title, $alias, $description, $image, $keywords ) = array( 0, '', '', '', '', '' );

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if( ! empty( $savecat ) )
{
	$bid = $nv_Request->get_int( 'bid', 'post', 0 );
	$title = $nv_Request->get_title( 'title', 'post', '', 1 );
	$keywords = $nv_Request->get_title( 'keywords', 'post', '', 1 );
	$alias = $nv_Request->get_title( 'alias', 'post', '' );
	$description = $nv_Request->get_string( 'description', 'post', '' );
	$description = nv_nl2br( nv_htmlspecialchars( strip_tags( $description ) ), '<br />' );
	$alias = ( $alias == '' ) ? change_alias( $title ) : change_alias( $alias );

	$image = $nv_Request->get_string( 'image', 'post', '' );
	if( is_file( NV_DOCUMENT_ROOT . $image ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" );
		$image = substr( $image, $lu );
	}
	else
	{
		$image = '';
	}

	if( empty( $title ) )
	{
		$error = $lang_module['error_name'];
	}
	elseif( $bid == 0 )
	{
		$weight = $db->query( "SELECT max(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "_block_cat" )->fetchColumn();
		$weight = intval( $weight ) + 1;

		$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_block_cat (adddefault, numbers, title, alias, description, image, weight, keywords, add_time, edit_time) VALUES (0, 4, :title , :alias, :description, :image, :weight, :keywords, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
		$data_insert = array();
		$data_insert['title'] = $title;
		$data_insert['alias'] = $alias;
		$data_insert['description'] = $description;
		$data_insert['image'] = $image;
		$data_insert['weight'] = $weight;
		$data_insert['keywords'] = $keywords;
		
		if( $db->insert_id( $sql, 'bid', $data_insert ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_blockcat', " ", $admin_info['userid'] );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	else
	{
		$stmt = $db->prepare( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_block_cat SET title= :title, alias = :alias, description= :description, image= :image, keywords= :keywords, edit_time=" . NV_CURRENTTIME . " WHERE bid =" . $bid );
		$stmt->bindParam( ':title', $title, PDO::PARAM_STR );
		$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
		$stmt->bindParam( ':description', $description, PDO::PARAM_STR );
		$stmt->bindParam( ':image', $image, PDO::PARAM_STR );
		$stmt->bindParam( ':keywords', $keywords, PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->execute() )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_blockcat', "blockid " . $bid, $admin_info['userid'] );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die('den day');
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
}

$bid = $nv_Request->get_int( 'bid', 'get', 0 );
if( $bid > 0 )
{
	list( $bid, $title, $alias, $description, $image, $keywords ) = $db->query( "SELECT bid, title, alias, description, image, keywords FROM " . NV_PREFIXLANG . "_" . $module_data . "_block_cat where bid=" . $bid )->fetch( 3 );
	$lang_module['add_block_cat'] = $lang_module['edit_block_cat'];
}

$lang_global['title_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 65 );
$lang_global['description_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 160 );

$xtpl = new XTemplate( 'groups.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'BLOCK_CAT_LIST', nv_show_block_cat_list() );

$xtpl->assign( 'bid', $bid );
$xtpl->assign( 'title', $title );
$xtpl->assign( 'alias', $alias );
$xtpl->assign( 'keywords', $keywords );
$xtpl->assign( 'description', nv_htmlspecialchars( nv_br2nl( $description ) ) );

if( ! empty( $image ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $image ) )
{
	$image = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $image;
}
$xtpl->assign( 'image', $image );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( empty( $alias ) )
{
	$xtpl->parse( 'main.getalias' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';