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
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );

	if( $db->sql_numrows( $result ) != 1 )
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
		die();
	}

	$row = $db->sql_fetchrow( $result );

	$page_title = $lang_module['aabout12'];
	$action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
}
else
{
	$page_title = $lang_module['aabout1'];
	$action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
}

$error = "";

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$title = filter_text_input( 'title', 'post', '', 1 );
	$alias = filter_text_input( 'alias', 'post', '', 1 );
	$bodytext = nv_editor_filter_textarea( 'bodytext', '', NV_ALLOWED_HTML_TAGS );
	$keywords = nv_strtolower( filter_text_input( 'keywords', 'post', '', 0 ) );

	if( empty( $title ) )
	{
		$error = $lang_module['aabout9'];
	}
	elseif( strip_tags( $bodytext ) == "" )
	{
		$error = $lang_module['aabout10'];
	}
	else
	{
		$bodytext = nv_editor_nl2br( $bodytext );
		$alias = empty( $alias ) ? change_alias( $title ) : change_alias( $alias );

		if( empty( $keywords ) )
		{
			$keywords = nv_get_keywords( $bodytext );
			if( empty( $keywords ) )
			{
				$keywords = nv_unhtmlspecialchars( $title );
				$keywords = strip_punctuation( $keywords );
				$keywords = trim( $keywords );
				$keywords = nv_strtolower( $keywords );
				$keywords = preg_replace( "/[ ]+/", ",", $keywords );
			}
		}

		if( $id )
		{
			$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "` SET 
            `title`=" . $db->dbescape( $title ) . ", `alias` =  " . $db->dbescape( $alias ) . ", 
            `bodytext`=" . $db->dbescape( $bodytext ) . ", `keywords`=" . $db->dbescape( $keywords ) . ", `edit_time`=" . NV_CURRENTTIME . " WHERE `id` =" . $id;
			$db->sql_query( $sql );
			$publtime = $row['add_time'];
		}
		else
		{
			list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "`" ) );
			$weight = intval( $weight ) + 1;

			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` VALUES (
            NULL, " . $db->dbescape( $title ) . ", " . $db->dbescape( $alias ) . ", " . $db->dbescape( $bodytext ) . ", " . $db->dbescape( $keywords ) . ", 
            " . $weight . ", " . $admin_info['admin_id'] . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 1);";
			$id = $db->sql_query_insert_id( $sql );
			$publtime = NV_CURRENTTIME;
		}
		
		nv_del_moduleCache( $module_name );
		
		if( $db->sql_affectedrows() > 0 )
		{
			if( $id )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit about', "ID:  " . $id, $admin_info['userid'] );
			}
			else
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Add about', " ", $admin_info['userid'] );
			}

			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main" );
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
	if( $id )
	{
		$title = $row['title'];
		$alias = $row['alias'];
		$keywords = $row['keywords'];
		$bodytext = nv_editor_br2nl( $row['bodytext'] );
	}
	else
	{
		$title = $alias = $bodytext = $keywords = "";
	}
}

if( ! empty( $bodytext ) ) $bodytext = nv_htmlspecialchars( $bodytext );

if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$bodytext = nv_aleditor( "bodytext", '100%', '300px', $bodytext );
}
else
{
	$bodytext = "<textarea style=\"width:100%;height:300px\" name=\"bodytext\">" . $bodytext . "</textarea>";
}

$xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', $action );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$xtpl->assign( 'TITLE', $title );
$xtpl->assign( 'ALIAS', $alias );
$xtpl->assign( 'ID', $id );
$xtpl->assign( 'KEYWORDS', $keywords );
$xtpl->assign( 'BODYTEXT', $bodytext );

if( empty( $alias ) ) $xtpl->parse( 'main.get_alias' );

if( $error )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>