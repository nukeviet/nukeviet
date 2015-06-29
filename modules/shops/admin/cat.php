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

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

list( $data['catid'], $data['parentid'], $data['title'], $data['alias'], $data['description'], $data[NV_LANG_DATA . '_descriptionhtml'], $data['keywords'], $data['groups_view'], $data['cat_allow_point'], $data['cat_number_point'], $data['cat_number_product'], $data['image'], $data['form'], $data['group_price'], $data['viewdescriptionhtml'], $data['newday'], $data['typeprice'] ) = array( 0, 0, '', '', '', '', '', '6', 0, 0, 0, '', '', $pro_config['group_price'], 0, 7, 1);

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );

$cat_form_exit = array();
$_form_exit = scandir( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
foreach ( $_form_exit as $_form ) {
	if( preg_match( '/^cat\_form\_([a-zA-Z0-9\-\_]+)\.tpl$/', $_form, $m ) )
	{
		$cat_form_exit[] = $m[1];
	}
}

if( ! empty( $savecat ) )
{
	$field_lang = nv_file_table( $table_name );

	$data['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$data['typeprice'] = $nv_Request->get_int( 'typeprice', 'post', 2 );
	$data['parentid_old'] = $nv_Request->get_int( 'parentid_old', 'post', 0 );
	$data['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$data['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$data['keywords'] = nv_substr( $nv_Request->get_title( 'keywords', 'post', '', 1 ), 0, 255 );
	$data['alias'] = nv_substr( $nv_Request->get_title( 'alias', 'post', '', 1 ), 0, 255 );
	$data['description'] = $nv_Request->get_string( 'description', 'post', '' );
	$data['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $data['description'] ) ), '<br />' );
	$data['descriptionhtml'] = $nv_Request->get_editor( 'descriptionhtml', '', NV_ALLOWED_HTML_TAGS );
	$data['viewdescriptionhtml'] = $nv_Request->get_int( 'viewdescriptionhtml', 'post', 0 );
	$data['cat_allow_point'] = $nv_Request->get_int( 'cat_allow_point', 'post', 0 );
	$data['cat_number_point'] = $nv_Request->get_int( 'cat_number_point', 'post', 0 );
	$data['cat_number_product'] = $nv_Request->get_int( 'cat_number_product', 'post', 0 );

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

	$data['form'] = $nv_Request->get_title( 'cat_form', 'post', '' );
	if( ! in_array( $data['form'], $cat_form_exit ) ) $data['form'] = '';

	$data['group_price'] = $nv_Request->get_textarea( 'group_price', '', 'br' );

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

		$sql = "INSERT INTO " . $table_name . " (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product " . $listfield . " )
 			VALUES (NULL, :parentid, :image," . $weight . ", '0', '0', :viewcat, '0', :subcatid, '1', '4', :newday, :typeprice, :form, :group_price, :viewdescriptionhtml, :admins, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", :groups_view, :cat_allow_point, :cat_number_point, :cat_number_product" . $listvalue . ")";
		$data_insert = array();
		$data_insert['parentid'] = $data['parentid'];
		$data_insert['image'] = $data['image'];
		$data_insert['subcatid'] = '';
		$data_insert['viewcat'] = 'viewcat_page_list';
		$data_insert['newday'] = $data['newday'];
		$data_insert['typeprice'] = $data['typeprice'];
		$data_insert['form'] = $data['form'];
		$data_insert['group_price'] = $data['group_price'];
		$data_insert['viewdescriptionhtml'] = $data['viewdescriptionhtml'];
		$data_insert['admins'] = $admins;
		$data_insert['groups_view'] = $data['groups_view'];
		$data_insert['cat_allow_point'] = $data['cat_allow_point'];
		$data_insert['cat_number_point'] = $data['cat_number_point'];
		$data_insert['cat_number_product'] = $data['cat_number_product'];
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
			$stmt = $db->prepare( "UPDATE " . $table_name . " SET parentid = :parentid, image = :image, typeprice = :typeprice, form = :form, group_price = :group_price, viewdescriptionhtml = :viewdescriptionhtml, " . NV_LANG_DATA . "_title= :title, " . NV_LANG_DATA . "_alias = :alias, " . NV_LANG_DATA . "_description= :description, " . NV_LANG_DATA . "_descriptionhtml = :descriptionhtml, " . NV_LANG_DATA . "_keywords= :keywords, groups_view= :groups_view, cat_allow_point = :cat_allow_point, cat_number_point = :cat_number_point, cat_number_product = :cat_number_product, edit_time=" . NV_CURRENTTIME . " WHERE catid =" . $data['catid'] );
			$stmt->bindParam( ':parentid', $data['parentid'], PDO::PARAM_INT );
			$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $data['alias'], PDO::PARAM_STR );
			$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );
			$stmt->bindParam( ':descriptionhtml', $data['descriptionhtml'], PDO::PARAM_STR );
			$stmt->bindParam( ':keywords', $data['keywords'], PDO::PARAM_STR );
			$stmt->bindParam( ':typeprice', $data['typeprice'], PDO::PARAM_INT );
			$stmt->bindParam( ':form', $data['form'], PDO::PARAM_STR );
			$stmt->bindParam( ':group_price', $data['group_price'], PDO::PARAM_STR );
			$stmt->bindParam( ':viewdescriptionhtml', $data['viewdescriptionhtml'], PDO::PARAM_INT );
			$stmt->bindParam( ':groups_view', $data['groups_view'], PDO::PARAM_STR );
			$stmt->bindParam( ':cat_allow_point', $data['cat_allow_point'], PDO::PARAM_INT );
			$stmt->bindParam( ':cat_number_point', $data['cat_number_point'], PDO::PARAM_INT );
			$stmt->bindParam( ':cat_number_product', $data['cat_number_product'], PDO::PARAM_INT );
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
else
{
	$data['parentid'] = $nv_Request->get_int( 'parentid', 'get,post', 0 );

	$data['catid'] = $nv_Request->get_int( 'catid', 'get', 0 );
	if( $data['catid'] > 0 )
	{
		$data = $db->query( 'SELECT * FROM ' . $table_name . ' where catid=' . $data['catid'] )->fetch();
		if( empty( $data ) )
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}
		$data['title'] = $data[NV_LANG_DATA . '_title'];
		$data['alias'] = $data[NV_LANG_DATA . '_alias'];
		$data['description'] = $data[NV_LANG_DATA . '_description'];
		$data['keywords'] = $data[NV_LANG_DATA . '_keywords'];
	}
	if( $data['parentid'] )
	{
		$data['form'] = $db->query( 'SELECT form FROM ' . $table_name . ' where catid=' . $data['parentid'] )->fetchColumn();
		list( $parent_title, $group_price ) = $db->query( 'SELECT ' . NV_LANG_DATA . '_title, group_price FROM ' . $table_name . ' where catid=' . $data['parentid'] )->fetch( 3 );
		$data['group_price'] = $group_price;
		$data['parent_title'] = $parent_title;
	}
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

$lang_global['title_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 65 );
$lang_global['description_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 160 );

if( ! empty( $data['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $data['image'] ) )
{
	$data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data['image'];
}
$data['description'] = nv_br2nl( $data['description'] );
if( $pro_config['point_active'] )
{
	if( $data['cat_allow_point'] )
	{
		$data['cat_number_point_dis'] = '';
		$data['cat_number_product_dis'] = '';
		$data['cat_allow_point'] = 'checked="checked"';
	}
	else
	{
		$data['cat_number_point_dis'] = 'readonly="readonly"';
		$data['cat_number_product_dis'] = 'readonly="readonly"';
		$data['cat_allow_point'] = '';
	}
	$data['cat_number_point'] = ! empty( $data['cat_number_point'] ) ? $data['cat_number_point'] : '';
	$data['cat_number_product'] = ! empty( $data['cat_number_product'] ) ? $data['cat_number_product'] : '';
}

if( $data['parentid'] )
{
	$lang_module['setting_group_price_space_note_cat'] = sprintf( $lang_module['setting_group_price_space_note_cat_1'], $data['parent_title'] );
}
else
{
	$URL_SETTING_GR_PRICE = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setting#setting_group_price';
	$lang_module['setting_group_price_space_note_cat'] = sprintf( $lang_module['setting_group_price_space_note_cat_0'], $URL_SETTING_GR_PRICE );
}

$xtpl = new XTemplate( 'cat_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'CAPTION', ( $data['catid'] > 0 ) ? $lang_module['edit_cat'] : $lang_module['add_cat'] );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'CAT_LIST', shops_show_cat_list( $data['parentid'] ) );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;catid=' . $data['catid'] . '&amp;parentid=' . $data['parentid'] );

if( $error != '' )
{
	$xtpl->assign( 'error', $error );
	$xtpl->parse( 'main.error' );
}

if( empty( $data['alias'] ) )
{
	$xtpl->parse( 'main.getalias' );
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

// Cach tinh gia san pham
$array_typeprice = array( $lang_module['typeprice_none'], $lang_module['config_discounts'], $lang_module['typeprice_per_product'] );
foreach( $array_typeprice as $key => $value )
{
	$ck = $data['typeprice'] == $key ? 'checked="checked"' : '';
	$xtpl->assign( 'TYPEPRICE', array( 'key' => $key, 'value' => $value, 'checked' => $ck ) );
	$xtpl->parse( 'main.typeprice_loop' );
}

if( $pro_config['point_active'] )
{
	$xtpl->parse( 'main.point' );
}

if( ! empty( $cat_form_exit ) )
{
	foreach ( $cat_form_exit as $_form )
	{
		$xtpl->assign( 'CAT_FORM', array(
			'value' => $_form,
			'selected' => ( $data['form'] == $_form ) ? ' selected="selected"' : '',
			'title' => $_form
		) );
		$xtpl->parse( 'main.cat_form.loop' );
	}
	$xtpl->parse( 'main.cat_form' );
}

$descriptionhtml = nv_htmlspecialchars( nv_editor_br2nl( $data[NV_LANG_DATA . '_descriptionhtml'] ) );
if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$descriptionhtml = nv_aleditor( 'descriptionhtml', '100%', '200px', $descriptionhtml, 'Basic' );
}
else
{
	$descriptionhtml = "<textarea style=\"width: 100%\" name=\"descriptionhtml\" id=\"descriptionhtml\" cols=\"20\" rows=\"15\">" . $descriptionhtml . "</textarea>";
}
$xtpl->assign( 'DESCRIPTIONHTML', $descriptionhtml );

for( $i = 0; $i <= 2; $i++ )
{
	$xtpl->assign( 'VIEWDESCRIPTION', array( 'value' => $i, 'checked' => $data['viewdescriptionhtml'] == $i ? ' checked="checked"' : '', 'title' => $lang_module['content_bodytext_display_' . $i] ) );
	$xtpl->parse( 'main.viewdescriptionhtml' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';