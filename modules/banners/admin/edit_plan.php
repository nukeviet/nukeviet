<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/12/2010 22:1
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );
$query = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE. '_plans WHERE id=' . $id;
$row = $db->query( $query )->fetch();

if( empty( $row ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$forms = nv_scandir( NV_ROOTDIR . '/modules/' . $module_name . '/forms', '/^form\_([a-zA-Z0-9\_\-]+)\.php$/' );
$forms = preg_replace( '/^form\_([a-zA-Z0-9\_\-]+)\.php$/', '\\1', $forms );

$error = '';

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$blang = strip_tags( $nv_Request->get_string( 'blang', 'post', '' ) );

	if( ! empty( $blang ) and ! in_array( $blang, $global_config['allow_sitelangs'] ) ) $blang = '';

	$title = nv_htmlspecialchars( strip_tags( $nv_Request->get_string( 'title', 'post', '' ) ) );
	$description = defined( 'NV_EDITOR' ) ? $nv_Request->get_string( 'description', 'post', '' ) : strip_tags( $nv_Request->get_string( 'description', 'post', '' ) );
	$form = $nv_Request->get_string( 'form', 'post', 'sequential' );

	if( ! in_array( $form, $forms ) ) $form = 'sequential';

	$width = $nv_Request->get_int( 'width', 'post', 0 );
	$height = $nv_Request->get_int( 'height', 'post', 0 );

	if( empty( $title ) )
	{
		$error = $lang_module['title_empty'];
	}
	elseif( $width < 50 or $height < 50 )
	{
		$error = $lang_module['size_incorrect'];
	}
	else
	{
		if( ! empty( $description ) ) $description = defined( 'NV_EDITOR' ) ? nv_nl2br( $description, '' ) : nv_nl2br( nv_htmlspecialchars( $description ), '<br />' );

		list( $blang_old, $form_old ) = $db->query( 'SELECT blang, form FROM ' . NV_BANNERS_GLOBALTABLE. '_plans WHERE id=' . intval( $id ) )->fetch( 3 );

		$stmt = $db->prepare( 'UPDATE ' . NV_BANNERS_GLOBALTABLE. '_plans SET blang= :blang, title= :title, description= :description, form= :form, width=' . $width . ', height=' . $height . ' WHERE id=' . $id );
		$stmt->bindParam( ':blang', $blang, PDO::PARAM_STR );
		$stmt->bindParam( ':title', $title, PDO::PARAM_STR );
		$stmt->bindParam( ':description', $description, PDO::PARAM_STR );
		$stmt->bindParam( ':form', $form, PDO::PARAM_STR );
		$stmt->execute();

		if( $form_old != $form or $blang_old != $blang )
		{
			nv_fix_banner_weight( $id );
		}

		nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_plan', 'planid ' . $id, $admin_info['userid'] );
		nv_CreateXML_bannerPlan();
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=info_plan&id=' . $id );
		die();
	}
}
else
{
	$blang = $row['blang'];
	$title = $row['title'];
	$description = nv_br2nl( $row['description'] );
	$form = $row['form'];
	$width = $row['width'];
	$height = $row['height'];
}

if( ! empty( $description ) ) $description = nv_htmlspecialchars( $description );
if( empty( $form ) ) $form = 'sequential';
if( empty( $width ) ) $width = 50;
if( empty( $height ) ) $height = 50;

$info = ( ! empty( $error ) ) ? $error : $lang_module['edit_plan_info'];
$is_error = ( ! empty( $error ) ) ? 1 : 0;

$allow_langs = array_flip( $global_config['allow_sitelangs'] );
$allow_langs = array_intersect_key( $language_array, $allow_langs );

$contents = array();
$contents['info'] = $info;
$contents['is_error'] = $is_error;
$contents['submit'] = $lang_module['edit_plan'];
$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_plan&amp;id=' . $id;
$contents['title'] = array( $lang_module['title'], 'title', $title, 255 );
$contents['blang'] = array( $lang_module['blang'], 'blang', $lang_module['blang_all'], $allow_langs, $blang );
$contents['form'] = array( $lang_module['form'], 'form', $forms, $form );
$contents['size'] = $lang_module['size'];
$contents['width'] = array( $lang_module['width'], 'width', $width, 4 );
$contents['height'] = array( $lang_module['height'], 'height', $height, 4 );
$contents['description'] = array( $lang_module['description'], 'description', $description, '99%', '300px', defined( 'NV_EDITOR' ) ? true : false );

if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

$contents = call_user_func( 'nv_edit_plan_theme', $contents );

$page_title = $lang_module['edit_plan'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';