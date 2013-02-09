<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/12/2010 12:25
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$forms = nv_scandir( NV_ROOTDIR . '/modules/' . $module_name . '/forms', "/^form\_([a-zA-Z0-9\_\-]+)\.php$/" );
$forms = preg_replace( "/^form\_([a-zA-Z0-9\_\-]+)\.php$/", "\\1", $forms );

$error = "";

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

		$sql = "INSERT INTO `" . NV_BANNERS_PLANS_GLOBALTABLE . "` VALUES (NULL, " . $db->dbescape( $blang ) . ", " . $db->dbescape( $title ) . ", 
        " . $db->dbescape( $description ) . ", " . $db->dbescape( $form ) . ", " . $width . ", " . $height . ", 1)";

		$id = $db->sql_query_insert_id( $sql );

		nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_plan', "planid " . $id, $admin_info['userid'] );
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=info_plan&id=" . $id );
		die();
	}
}
else
{
	$blang = $title = $description = "";
	$form = "sequential";
	$width = $height = 50;
}

if( ! empty( $description ) ) $description = nv_htmlspecialchars( $description );
if( empty( $width ) ) $width = 50;
if( empty( $height ) ) $height = 50;

$info = ( ! empty( $error ) ) ? $error : $lang_module['add_plan_info'];
$is_error = ( ! empty( $error ) ) ? 1 : 0;

$allow_langs = array_flip( $global_config['allow_sitelangs'] );
$allow_langs = array_intersect_key( $language_array, $allow_langs );

$contents = array();
$contents['info'] = $info;
$contents['is_error'] = $is_error;
$contents['submit'] = $lang_module['add_plan'];
$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add_plan";
$contents['title'] = array( $lang_module['title'], 'title', $title, 255 );
$contents['blang'] = array( $lang_module['blang'], 'blang', $lang_module['blang_all'], $allow_langs, $blang );
$contents['form'] = array( $lang_module['form'], 'form', $forms, $form );
$contents['size'] = $lang_module['size'];
$contents['width'] = array( $lang_module['width'], 'width', $width, 4 );
$contents['height'] = array( $lang_module['height'], 'height', $height, 4 );
$contents['description'] = array( $lang_module['description'], 'description', $description, '99%', '300px', defined( 'NV_EDITOR' ) ? true : false );

if( defined( 'NV_EDITOR' ) ) @require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

$contents = nv_add_plan_theme( $contents );

$page_title = $lang_module['add_plan'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>