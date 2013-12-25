<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );

if( $id )
{
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$result = $db->sql_query( $sql );

	if( $db->sql_numrows( $result ) != 1 )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	$row = $db->sql_fetchrow( $result );

	$page_title = $lang_module['edit'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
}
else
{
	$page_title = $lang_module['add'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

$selectthemes = ( ! empty( $site_mods[$module_name]['theme'] ) ) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir( NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout'] );
$error = '';

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
	$description = $row['description'];
	$l = mb_strlen( $description, 'UTF-8' );
	for( $i = 0; $i < $l; $i++ )
	{
		$s = trim( mb_substr( $description, $i, 1 ) );
		if( ! empty( $s ) )
		{
			echo $s . '------' . urlencode( $s ) . '<br>';

		}
	}

	die( $description );

	$row['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $row['description'] ) ), '<br />' );

	$row['bodytext'] = $nv_Request->get_editor( 'bodytext', '', NV_ALLOWED_HTML_TAGS );
	$row['keywords'] = nv_strtolower( $nv_Request->get_title( 'keywords', 'post', '', 0 ) );

	$row['socialbutton'] = $nv_Request->get_int( 'socialbutton', 'post', 0 );
	$row['activecomm'] = $nv_Request->get_int( 'activecomm', 'post', 0 );
	$row['facebookappid'] = $nv_Request->get_title( 'facebookappid', 'post', '' );
	$row['layout_func'] = $nv_Request->get_title( 'layout_func', 'post', '' );
	$row['gid'] = $nv_Request->get_int( 'gid', 'post', 0 );

	if( empty( $row['title'] ) )
	{
		$error = $lang_module['empty_title'];
	}
	elseif( strip_tags( $row['bodytext'] ) == '' )
	{
		$error = $lang_module['empty_bodytext'];
	}
	elseif( empty( $row['layout_func'] ) OR in_array( 'layout.' . $row['layout_func'] . '.tpl', $layout_array ) )
	{
		$row['bodytext'] = nv_editor_nl2br( $row['bodytext'] );
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
			$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET
					 title=" . $db->dbescape( $row['title'] ) . ", alias = " . $db->dbescape( $row['alias'] ) . ", image=" . $db->dbescape( $row['image'] ) . ", imagealt=" . $db->dbescape( $row['imagealt'] ) . ", description=" . $db->dbescape( $row['description'] ) . ",
					 bodytext=" . $db->dbescape( $row['bodytext'] ) . ", keywords=" . $db->dbescape( $row['keywords'] ) . ",
					 socialbutton=" . $row['socialbutton'] . ", activecomm=" . $row['activecomm'] . ", facebookappid=" . $db->dbescape( $row['facebookappid'] ) . ",
					 layout_func=" . $db->dbescape( $row['layout_func'] ) . ", gid=" . $row['gid'] . ", edit_time=" . NV_CURRENTTIME . " WHERE id =" . $id;
			$publtime = $row['add_time'];
		}
		else
		{
			list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "" ) );
			$weight = intval( $weight ) + 1;

			$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "
					(title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, facebookappid, layout_func, gid, weight, admin_id, add_time, edit_time, status) VALUES 
					(" . $db->dbescape( $row['title'] ) . ", " . $db->dbescape( $row['alias'] ) . ", " . $db->dbescape( $row['image'] ) . ", " . $db->dbescape( $row['imagealt'] ) . ", " . $db->dbescape( $row['description'] ) . ", " . $db->dbescape( $row['bodytext'] ) . ",
					" . $db->dbescape( $row['keywords'] ) . ", " . $row['socialbutton'] . ", " . $row['activecomm'] . ", " . $db->dbescape( $row['facebookappid'] ) . ",
					" . $db->dbescape( $row['layout_func'] ) . "," . $row['gid'] . ", " . $weight . ", " . $admin_info['admin_id'] . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 1);";
			$publtime = NV_CURRENTTIME;
		}

		if( $db->exec( $sql ) )
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
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main' );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
}
elseif( $id )
{
	$row['bodytext'] = nv_editor_br2nl( $row['bodytext'] );
}
else
{
	$row['image'] = '';
	$row['layout_func'] = '';
	$row['bodytext'] = '';
	$row['activecomm'] = 0;
	$row['socialbutton'] = 1;
	$row['gid'] = 0;
}

if( ! empty( $row['bodytext'] ) ) $row['bodytext'] = nv_htmlspecialchars( $row['bodytext'] );

if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$row['bodytext'] = nv_aleditor( 'bodytext', '100%', '300px', $row['bodytext'] );
}
else
{
	$row['bodytext'] = "<textarea style=\"width:100%;height:300px\" name=\"bodytext\">" . $row['bodytext'] . "</textarea>";
}

if( ! empty( $row['image'] ) AND is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['image'] ) )
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
	$value = preg_replace( $global_config['check_op_layout'], "\\1", $value );
	$xtpl->assign( 'LAYOUT_FUNC', array( 'key' => $value, 'selected' => ( $row['layout_func'] == $value ) ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.layout_func' );
}
$sql = "SELECT * FROM " . $db_config['prefix'] . "_googleplus ORDER BY weight ASC";
$result = $db->sql_query( $sql );
if( $db->sql_numrows( $result ) )
{
	$array_googleplus = array();
	$array_googleplus[] = array( 'gid' => - 1, 'title' => $lang_module['googleplus_1'] );
	$array_googleplus[] = array( 'gid' => 0, 'title' => $lang_module['googleplus_0'] );
	while( $grow = $db->sql_fetch_assoc( $result ) )
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
for( $i = 0; $i <= 1; ++$i )
{
	$xtpl->assign( 'ACTIVECOMM', array(
		'key' => $i,
		'title' => $lang_module['activecomm_' . $i],
		'selected' => ( $i == $row['activecomm'] ) ? ' selected="selected"' : ''
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

?>