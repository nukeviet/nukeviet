<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );

if( $id )
{
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$row = $db->query( $sql )->fetch();

	if(empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	$page_title = $lang_module['edit'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
}
else
{
	$page_title = $lang_module['add'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

$selectthemes = ( ! empty( $site_mods[$module_name]['theme'] ) ) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir( NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout'] );
$error = '';
$groups_list = nv_groups_list();

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$row['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
	$row['alias'] = $nv_Request->get_title( 'alias', 'post', '', 1 );

	$image = $nv_Request->get_string( 'image', 'post', '' );
	if( is_file( NV_DOCUMENT_ROOT . $image ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
		$row['image'] = substr( $image, $lu );
	}
	else
	{
		$row['image'] = '';
	}
	$row['imagealt'] = $nv_Request->get_title( 'imagealt', 'post', '', 1 );

	$row['description'] = $nv_Request->get_string( 'description', 'post', '' );
	$row['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $row['description'] ) ), '<br />' );

	$row['bodytext'] = $nv_Request->get_editor( 'bodytext', '', NV_ALLOWED_HTML_TAGS );
	$row['keywords'] = nv_strtolower( $nv_Request->get_title( 'keywords', 'post', '', 0 ) );

	$row['socialbutton'] = $nv_Request->get_int( 'socialbutton', 'post', 0 );
	$row['facebookappid'] = $nv_Request->get_title( 'facebookappid', 'post', '' );
	$row['layout_func'] = $nv_Request->get_title( 'layout_func', 'post', '' );
	$row['gid'] = $nv_Request->get_int( 'gid', 'post', 0 );

	$_groups_post = $nv_Request->get_array( 'activecomm', 'post', array() );
	$row['activecomm'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	if( empty( $row['title'] ) )
	{
		$error = $lang_module['empty_title'];
	}
	elseif( strip_tags( $row['bodytext'] ) == '' )
	{
		$error = $lang_module['empty_bodytext'];
	}
	elseif( empty( $row['layout_func'] ) or in_array( 'layout.' . $row['layout_func'] . '.tpl', $layout_array ) )
	{
		$row['alias'] = empty( $row['alias'] ) ? change_alias( $row['title'] ) : change_alias( $row['alias'] );

		if( empty( $row['keywords'] ) )
		{
			$row['keywords'] = nv_get_keywords( $row['title'] );
			if( empty( $row['keywords'] ) )
			{
				$row['keywords'] = nv_unhtmlspecialchars( $row['keywords'] );
				$row['keywords'] = strip_punctuation( $row['keywords'] );
				$row['keywords'] = trim( $row['keywords'] );
				$row['keywords'] = nv_strtolower( $row['keywords'] );
				$row['keywords'] = preg_replace( '/[ ]+/', ',', $row['keywords'] );
			}
		}

		if( $id )
		{
			$_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title = :title, alias = :alias, image = :image, imagealt = :imagealt, description = :description, bodytext = :bodytext, keywords = :keywords, socialbutton = :socialbutton, activecomm = :activecomm, facebookappid = :facebookappid, layout_func = :layout_func, gid = :gid, admin_id = :admin_id, edit_time = ' . NV_CURRENTTIME . ' WHERE id =' . $id;
			$publtime = $row['add_time'];
		}
		else
		{
			$weight = $db->query( "SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data )->fetchColumn();
			$weight = intval( $weight ) + 1;

			$_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, facebookappid, layout_func, gid, weight,admin_id, add_time, edit_time, status) VALUES (:title, :alias, :image, :imagealt, :description, :bodytext, :keywords, :socialbutton, :activecomm, :facebookappid, :layout_func, :gid, ' . $weight . ', :admin_id, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 1)';

			$publtime = NV_CURRENTTIME;
		}

		try
		{
			$sth = $db->prepare( $_sql );
			$sth->bindParam( ':title', $row['title'], PDO::PARAM_STR );
			$sth->bindParam( ':alias', $row['alias'], PDO::PARAM_STR );
			$sth->bindParam( ':image', $row['image'], PDO::PARAM_STR );
			$sth->bindParam( ':imagealt', $row['imagealt'], PDO::PARAM_STR );
			$sth->bindParam( ':description', $row['description'], PDO::PARAM_STR );
			$sth->bindParam( ':bodytext', $row['bodytext'], PDO::PARAM_STR, strlen( $row['bodytext'] ) );
			$sth->bindParam( ':keywords', $row['keywords'], PDO::PARAM_STR );
			$sth->bindParam( ':socialbutton', $row['socialbutton'], PDO::PARAM_INT );
			$sth->bindParam( ':activecomm', $row['activecomm'], PDO::PARAM_INT );
			$sth->bindParam( ':facebookappid', $row['facebookappid'], PDO::PARAM_STR );
			$sth->bindParam( ':layout_func', $row['layout_func'], PDO::PARAM_STR );
			$sth->bindParam( ':gid', $row['gid'], PDO::PARAM_INT );
			$sth->bindParam( ':admin_id', $admin_info['admin_id'], PDO::PARAM_INT );
			$sth->execute();

			if( $sth->rowCount() )
			{
				if( $id )
				{
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit', 'ID: ' . $id, $admin_info['userid'] );
				}
				else
				{
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add', ' ', $admin_info['userid'] );
				}

				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main' );
				die();
			}
			else
			{
				$error = $lang_module['errorsave'];
			}
		}
		catch( PDOException $e )
		{
			$error = $lang_module['errorsave'];
		}
	}
}
elseif( empty( $id)  )
{
	$row['image'] = '';
	$row['layout_func'] = '';
	$row['bodytext'] = '';
	$row['activecomm'] = $module_config[$module_name]['setcomm'];
	$row['socialbutton'] = 1;
	$row['gid'] = 0;
}

if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

$row['bodytext'] = htmlspecialchars( nv_editor_br2nl( $row['bodytext'] ) );
if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$row['bodytext'] = nv_aleditor( 'bodytext', '100%', '300px', $row['bodytext'] );
}
else
{
	$row['bodytext'] = '<textarea style="width:100%;height:300px" name="bodytext">' . $row['bodytext'] . '</textarea>';
}

if( ! empty( $row['image'] ) and is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['image'] ) )
{
	$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['image'];
}
$lang_global['title_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 65 );
$lang_global['description_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 160 );

$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', $action );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'DATA', $row );
$xtpl->assign( 'BODYTEXT', $row['bodytext'] );
$xtpl->assign( 'SOCIALBUTTON', ( $row['socialbutton'] ) ? ' checked="checked"' : '' );

foreach( $layout_array as $value )
{
	$value = preg_replace( $global_config['check_op_layout'], '\\1', $value );
	$xtpl->assign( 'LAYOUT_FUNC', array( 'key' => $value, 'selected' => ( $row['layout_func'] == $value ) ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.layout_func' );
}
$sql = "SELECT * FROM " . $db_config['prefix'] . "_googleplus ORDER BY weight ASC";
$_grows = $db->query( $sql )->fetchAll();
if( sizeof( $_grows ) )
{
	$array_googleplus = array();
	$array_googleplus[] = array( 'gid' => - 1, 'title' => $lang_module['googleplus_1'] );
	$array_googleplus[] = array( 'gid' => 0, 'title' => $lang_module['googleplus_0'] );
	foreach ( $_grows as $grow )
	{
		$array_googleplus[] = $grow;
	}
	foreach( $array_googleplus as $grow )
	{
		$grow['selected'] = ( $row['gid'] == $grow['gid'] ) ? ' selected="selected"' : '';
		$xtpl->assign( 'GOOGLEPLUS', $grow );
		$xtpl->parse( 'main.googleplus.gid' );
	}
	$xtpl->parse( 'main.googleplus' );
}

$activecomm = explode( ',', $row['activecomm'] );
foreach( $groups_list as $_group_id => $_title )
{
	$xtpl->assign( 'ACTIVECOMM', array(
		'value' => $_group_id,
		'checked' => in_array( $_group_id, $activecomm ) ? ' checked="checked"' : '',
		'title' => $_title
	) );
	$xtpl->parse( 'main.activecomm' );
}

if( empty( $alias ) ) $xtpl->parse( 'main.get_alias' );

if( $error )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';