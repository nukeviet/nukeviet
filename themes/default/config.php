<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 17 Apr 2014 04:03:46 GMT
 */

if ( !defined( 'NV_IS_FILE_THEMES' ) )	die( 'Stop!!! 123' );

$config_theme = array();
$propety = array();

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    // Css propety for body
	$property['color'] = $nv_Request->get_string( 'body_color', 'post', '' );
	$property['font_size'] = $nv_Request->get_string( 'body_font_size', 'post', '' );
    $property['font_family'] = $nv_Request->get_string( 'body_font_family', 'post', '' );
    $property['font_weight'] = $nv_Request->get_bool( 'body_font_weight', 'post', 0 );
    $property['font_weight'] = $property['font_weight'] ? 'bold' : '';
    $property['font_style'] = $nv_Request->get_bool( 'body_font_italic', 'post', 0 );
    $property['font_style'] = $property['font_style'] ? 'italic' : '';
    $property['background_color'] = $nv_Request->get_string( 'body_background_color', 'post', '' );
    $property['background_image'] = $nv_Request->get_string( 'body_background_image', 'post', '' );
    $property['background_repeat'] = $nv_Request->get_string( 'body_background_repeat', 'post', '' );
    $property['background_position'] = $nv_Request->get_string( 'body_background_position', 'post', '' );
    $property['margin'] = $nv_Request->get_string( 'body_margin', 'post', '' );
    $property['margin_top'] = $nv_Request->get_string( 'body_margin_top', 'post', '' );
    $property['margin_bottom'] = $nv_Request->get_string( 'body_margin_bottom', 'post', '' );
    $property['margin_left'] = $nv_Request->get_string( 'body_margin_left', 'post', '' );
    $property['margin_right'] = $nv_Request->get_string( 'body_margin_right', 'post', '' );
    $property['padding'] = $nv_Request->get_string( 'body_padding', 'post', '' );
    $property['padding_top'] = $nv_Request->get_string( 'body_padding_top', 'post', '' );
    $property['padding_bottom'] = $nv_Request->get_string( 'body_padding_bottom', 'post', '' );
    $property['padding_left'] = $nv_Request->get_string( 'body_padding_left', 'post', '' );
    $property['padding_right'] = $nv_Request->get_string( 'body_padding_right', 'post', '' );
    $property['customcss'] = $nv_Request->get_textarea( 'body_customcss', 'post', '' );

    $config_theme['body'] = $property;
    
	$config_value = serialize( $config_theme );

	if ( isset( $module_config['themes'][$selectthemes] ) )
	{
		$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = :config_name AND lang = '" . NV_LANG_DATA . "' AND module='themes'" );
	}
	else
	{
		$sth = $db->prepare( "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . NV_LANG_DATA . "', 'themes', :config_name, :config_value)" );
	}

	$sth->bindParam( ':config_name', $selectthemes, PDO::PARAM_STR );
	$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR, strlen( $config_value ) );
	$sth->execute();

	nv_del_moduleCache( 'settings' );
    
    if( file_exists( NV_ROOTDIR . "/" . SYSTEM_FILES_DIR . "/css/theme_" . $selectthemes . "_" . $global_config['idsite'] . ".css" ) )
    {
        nv_deletefile( NV_ROOTDIR . "/" . SYSTEM_FILES_DIR . "/css/theme_" . $selectthemes . "_" . $global_config['idsite'] . ".css" );
    }
    
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&selectthemes=' . $selectthemes . '&rand=' . nv_genpass() );
	die();
}
elseif ( isset( $module_config['themes'][$selectthemes] ) )
{
	$config_theme = unserialize( $module_config['themes'][$selectthemes] );
}
else
{
	require NV_ROOTDIR . '/themes/' . $selectthemes . '/config_default.php';
}

$xtpl = new XTemplate( 'config.tpl', NV_ROOTDIR . '/themes/' . $selectthemes . '/system/' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'NV_ADMIN_THEME', $global_config['admin_theme'] );
$xtpl->assign( 'SELECTTHEMES', $selectthemes );
$xtpl->assign( 'UPLOADS_DIR', NV_UPLOADS_DIR . '/' . $module_name );

$config_theme['body']['font_weight'] = ( $config_theme['body']['font_weight'] ) ? ' checked="checked"' : '';   
$config_theme['body']['font_style'] = ( $config_theme['body']['font_style'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'CONFIG_THEME_BODY', $config_theme['body'] );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );