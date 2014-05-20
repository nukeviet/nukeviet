<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['categories'];

$error = $admins = '';
$savecat = 0;
list( $catid, $parentid, $title, $titlesite, $alias, $description, $keywords, $groups_view, $image, $viewdescription ) = array( 0, 0, '', '', '', '', '', '6', '', 0 );

$groups_list = nv_groups_list();

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if( ! empty( $savecat ) )
{
	$catid = $nv_Request->get_int( 'catid', 'post', 0 );
	$parentid_old = $nv_Request->get_int( 'parentid_old', 'post', 0 );
	$parentid = $nv_Request->get_int( 'parentid', 'post', 0 );
	$title = $nv_Request->get_title( 'title', 'post', '', 1 );
	$titlesite = $nv_Request->get_title( 'titlesite', 'post', '', 1 );
	$keywords = $nv_Request->get_title( 'keywords', 'post', '', 1 );
	$alias = $nv_Request->get_title( 'alias', 'post', '' );
	$description = $nv_Request->get_string( 'description', 'post', '' );
	$description = nv_nl2br( nv_htmlspecialchars( strip_tags( $description ) ), '<br />' );
	$viewdescription = $nv_Request->get_int( 'viewdescription', 'post', 0 );
	$alias = ( $alias == '' ) ? change_alias( $title ) : change_alias( $alias );

	$_groups_post = $nv_Request->get_array( 'groups_view', 'post', array() );
	$groups_view = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

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

	if( ! defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		if( ! ( isset( $array_cat_admin[$admin_id][$parentid] ) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1 ) )
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid );
			die();
		}
	}

	if( $catid == 0 and $title != '' )
	{
		$weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid )->fetchColumn();
		$weight = intval( $weight ) + 1;
		$viewcat = 'viewcat_page_new';
		$subcatid = '';

		$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_cat (parentid, title, titlesite, alias, description, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, keywords, admins, add_time, edit_time, groups_view) VALUES
			(:parentid, :title, :titlesite, :alias, :description, '', '" . $viewdescription . "', :weight, '0', '0', :viewcat, '0', :subcatid, '1', '3', '2', :keywords, :admins, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", :groups_view)";

		$data_insert = array();
		$data_insert['parentid'] = $parentid;
		$data_insert['title'] = $title;
		$data_insert['titlesite'] = $titlesite;
		$data_insert['alias'] = $alias;
		$data_insert['description'] = $description;
		$data_insert['weight'] = $weight;
		$data_insert['viewcat'] = $viewcat;
		$data_insert['subcatid'] = $subcatid;
		$data_insert['keywords'] = $keywords;
		$data_insert['admins'] = $admins;
		$data_insert['groups_view'] = $groups_view;

		$newcatid = $db->insert_id( $sql, 'catid', $data_insert );
		if( $newcatid > 0 )
		{
			require_once NV_ROOTDIR . '/includes/action_' . $db->dbtype . '.php';

			nv_copy_structure_table( NV_PREFIXLANG . '_' . $module_data . '_' . $newcatid , NV_PREFIXLANG . '_' . $module_data . '_rows' );
			nv_fix_cat_order();

			if( ! defined( 'NV_IS_ADMIN_MODULE' ) )
			{
				$db->query( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_admins (userid, catid, admin, add_content, pub_content, edit_content, del_content) VALUES (' . $admin_id . ', ' . $newcatid . ', 1, 1, 1, 1, 1)' );
			}

			nv_del_moduleCache( $module_name );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['add_cat'], $title, $admin_info['userid'] );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	elseif( $catid > 0 and $title != '' )
	{
		$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET parentid= :parentid, title= :title, titlesite=:titlesite, alias = :alias, description= :description, image= :image, viewdescription= :viewdescription, keywords= :keywords, groups_view= :groups_view, edit_time=' . NV_CURRENTTIME . ' WHERE catid =' . $catid );
		$stmt->bindParam( ':parentid', $parentid, PDO::PARAM_INT);
		$stmt->bindParam( ':title', $title, PDO::PARAM_STR );
		$stmt->bindParam( ':titlesite', $titlesite, PDO::PARAM_STR );
		$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
		$stmt->bindParam( ':image', $image, PDO::PARAM_STR );
		$stmt->bindParam( ':viewdescription', $viewdescription, PDO::PARAM_STR );
		$stmt->bindParam( ':keywords', $keywords, PDO::PARAM_STR );
		$stmt->bindParam( ':description', $description, PDO::PARAM_STR );
		$stmt->bindParam( ':groups_view', $groups_view, PDO::PARAM_STR );
		$stmt->execute();

		if( $stmt->rowCount() )
		{
			if( $parentid != $parentid_old )
			{
				$weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid )->fetchColumn();
				$weight = intval( $weight ) + 1;

				$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE catid=' . intval( $catid );
				$db->query( $sql );

				nv_fix_cat_order();
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_cat'], $title, $admin_info['userid'] );
			}

			nv_del_moduleCache( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	else
	{
		$error = $lang_module['error_name'];
	}
}

$parentid = $nv_Request->get_int( 'parentid', 'get,post', 0 );

$catid = $nv_Request->get_int( 'catid', 'get', 0 );
if( $catid > 0 and isset( $global_array_cat[$catid] ) )
{
	$parentid = $global_array_cat[$catid]['parentid'];
	$title = $global_array_cat[$catid]['title'];
	$titlesite = $global_array_cat[$catid]['titlesite'];
	$alias = $global_array_cat[$catid]['alias'];
	$description = $global_array_cat[$catid]['description'];
	$viewdescription = $global_array_cat[$catid]['viewdescription'];
	$image = $global_array_cat[$catid]['image'];
	$keywords = $global_array_cat[$catid]['keywords'];
	$groups_view = $global_array_cat[$catid]['groups_view'];

	if( ! defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		if( ! ( isset( $array_cat_admin[$admin_id][$parentid] ) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1 ) )
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid );
			die();
		}
	}

	$caption = $lang_module['edit_cat'];
	$array_in_cat = GetCatidInParent( $catid );
}
else
{
	$caption = $lang_module['add_cat'];
	$array_in_cat = array();
}

$groups_view = explode( ',', $groups_view );

$array_cat_list = array();
if( defined( 'NV_IS_ADMIN_MODULE' ) )
{
	$array_cat_list[0] = $lang_module['cat_sub_sl'];
}
foreach( $global_array_cat as $catid_i => $array_value )
{
	$lev_i = $array_value['lev'];
	if( defined( 'NV_IS_ADMIN_MODULE' ) or ( isset( $array_cat_admin[$admin_id][$catid_i] ) and $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 ) )
	{
		$xtitle_i = '';
		if( $lev_i > 0 )
		{
			$xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
			for( $i = 1; $i <= $lev_i; ++$i )
			{
				$xtitle_i .= '---';
			}
			$xtitle_i .= '>&nbsp;';
		}
		$xtitle_i .= $array_value['title'];
		$array_cat_list[$catid_i] = $xtitle_i;
	}
}

if( ! empty( $array_cat_list ) )
{
	$cat_listsub = array();
	while( list( $catid_i, $title_i ) = each( $array_cat_list ) )
	{
		if( ! in_array( $catid_i, $array_in_cat ) )
		{
			$cat_listsub[] = array(
				'value' => $catid_i,
				'selected' => ( $catid_i == $parentid ) ? ' selected="selected"' : '',
				'title' => $title_i
			);
		}
	}

	$groups_views = array();
	foreach( $groups_list as $group_id => $grtl )
	{
		$groups_views[] = array(
			'value' => $group_id,
			'checked' => in_array( $group_id, $groups_view ) ? ' checked="checked"' : '',
			'title' => $grtl
		);
	}
}

$lang_global['title_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 65 );
$lang_global['description_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 160 );

$xtpl = new XTemplate( 'cat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'caption', $caption );
$xtpl->assign( 'catid', $catid );
$xtpl->assign( 'title', $title );
$xtpl->assign( 'titlesite', $titlesite );
$xtpl->assign( 'alias', $alias );
$xtpl->assign( 'parentid', $parentid );
$xtpl->assign( 'keywords', $keywords );
$xtpl->assign( 'description', nv_htmlspecialchars( nv_br2nl( $description ) ) );

$xtpl->assign( 'CAT_LIST', nv_show_cat_list( $parentid ) );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );
if( ! empty( $image ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $image ) )
{
	$image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
}
$xtpl->assign( 'image', $image );

for( $i = 0; $i <= 2; $i++ )
{
	$data = array(
		'value' => $i,
		'selected' => ( $viewdescription == $i ) ? ' checked="checked"' : '',
		'title' => $lang_module['viewdescription_' . $i]
	);
	$xtpl->assign( 'VIEWDESCRIPTION', $data );
	$xtpl->parse( 'main.content.viewdescription' );
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( ! empty( $array_cat_list ) )
{
	if( empty( $alias ) )
	{
		$xtpl->parse( 'main.content.getalias' );
	}

	foreach( $cat_listsub as $data )
	{
		$xtpl->assign( 'cat_listsub', $data );
		$xtpl->parse( 'main.content.cat_listsub' );
	}

	foreach( $groups_views as $data )
	{
		$xtpl->assign( 'groups_views', $data );
		$xtpl->parse( 'main.content.groups_views' );
	}

	$xtpl->parse( 'main.content' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';