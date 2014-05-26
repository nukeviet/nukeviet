<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_show_tags_list()
 *
 * @return
 */
function nv_show_tags_list( $q = '' )
{
	global $db, $lang_module, $lang_global, $module_name, $module_data, $op, $module_file, $global_config, $module_info;

	$db->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_' . $module_data . '_tags' )->order( 'alias ASC' );

	if( ! empty( $q ) )
	{
		$q = strip_punctuation( $q );
		$db->where( 'keywords LIKE :keywords' );
	}
	else
	{
		$db->order( 'alias ASC' );
	}

	$sth = $db->prepare( $db->sql() );
	if( ! empty( $q ) )
	{
		$sth->bindValue( ':keywords', '%' . $q . '%', PDO::PARAM_STR );
	}
	$sth->execute();

	$xtpl = new XTemplate( 'tags_lists.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );

	$number = 0;
	while( $row = $sth->fetch() )
	{
		$row['number'] = ++$number;
		$row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['tag'] . '/' . $row['alias'];
		$row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;tid=' . $row['tid'] . '#edit';
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
	}
	$sth->closeCursor();

	if( empty( $q ) and $number > 9 )
	{
		$xtpl->parse( 'main.other' );
	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	if( empty( $contents ) )
	{
		$contents = '&nbsp;';
	}
	return $contents;
}

if( $nv_Request->isset_request( 'del_tid', 'get' ) )
{
	$tid = $nv_Request->get_int( 'del_tid', 'get', 0 );
	if( $tid )
	{
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE tid=' . $tid );
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE tid=' . $tid );
	}
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_show_tags_list();
	include NV_ROOTDIR . '/includes/footer.php';
}
elseif( $nv_Request->isset_request( 'q', 'get' ) )
{
	$q = $nv_Request->get_title( 'q', 'get', '' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_show_tags_list( $q );
	include NV_ROOTDIR . '/includes/footer.php';
}

$error = '';
$savecat = 0;
list( $tid, $title, $alias, $description, $image, $keywords ) = array( 0, '', '', '', '', '' );

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if( ! empty( $savecat ) )
{
	$tid = $nv_Request->get_int( 'tid', 'post', 0 );
	$keywords = $nv_Request->get_title( 'keywords', 'post', '' );
	$alias = $nv_Request->get_title( 'alias', 'post', '' );
	$description = $nv_Request->get_string( 'description', 'post', '' );
	$description = nv_nl2br( nv_htmlspecialchars( strip_tags( $description ) ), '<br />' );

	$alias = str_replace( '-', ' ', nv_unhtmlspecialchars( $alias ) );
	$keywords = explode( ',', nv_strtolower( $keywords ) );
	$keywords[] = $alias;
	$keywords = array_map( 'strip_punctuation', $keywords );
	$keywords = array_map( 'trim', $keywords );
	$keywords = array_diff( $keywords, array( '' ) );
	$keywords = array_unique( $keywords );
	$keywords = implode( ',', $keywords );

	$alias = str_replace( ' ', '-', strip_punctuation( $alias ) );

	$image = $nv_Request->get_string( 'image', 'post', '' );
	if( is_file( NV_DOCUMENT_ROOT . $image ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
		$image = substr( $image, $lu );
	}
	else
	{
		$image = '';
	}
	if( empty( $alias ) )
	{
		$error = $lang_module['error_name'];
	}
	else
	{
		if( $tid == 0 )
		{
			$sth = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags (numnews, alias, description, image, keywords) VALUES (0, :alias, :description, :image, :keywords)' );
			$msg_lg = 'add_tags';
		}
		else
		{
			$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET alias = :alias, description = :description, image = :image, keywords = :keywords WHERE tid =' . $tid );
			$msg_lg = 'edit_tags';
		}

		try
		{
			$sth->bindParam( ':alias', $alias, PDO::PARAM_STR );
			$sth->bindParam( ':description', $description, PDO::PARAM_STR );
			$sth->bindParam( ':image', $image, PDO::PARAM_STR );
			$sth->bindParam( ':keywords', $keywords, PDO::PARAM_STR );
			$sth->execute();

			nv_insert_logs( NV_LANG_DATA, $module_name, $msg_lg, $alias, $admin_info['userid'] );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}
		catch( PDOException $e )
		{
			$error = $lang_module['errorsave'];
		}
	}
}

$tid = $nv_Request->get_int( 'tid', 'get', 0 );
if( $tid > 0 )
{
	list( $tid, $alias, $description, $image, $keywords ) = $db->query( 'SELECT tid, alias, description, image, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where tid=' . $tid )->fetch( 3 );
	$lang_module['add_tags'] = $lang_module['edit_tags'];
}

$lang_global['title_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 65 );
$lang_global['description_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 160 );

$xtpl = new XTemplate( 'tags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'TAGS_LIST', nv_show_tags_list() );

$xtpl->assign( 'tid', $tid );
$xtpl->assign( 'alias', $alias );
$xtpl->assign( 'keywords', $keywords );
$xtpl->assign( 'description', nv_htmlspecialchars( nv_br2nl( $description ) ) );

if( ! empty( $image ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $image ) )
{
	$image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
}
$xtpl->assign( 'image', $image );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['tags'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';