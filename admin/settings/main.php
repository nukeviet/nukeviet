<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$page_title = sprintf( $lang_module['lang_site_config'], $language_array[NV_LANG_DATA]['name'] );

$submit = $nv_Request->get_string( 'submit', 'post' );
$errormess = '';

if( $submit )
{
	$array_config = array();
	$array_config['site_theme'] = nv_substr( $nv_Request->get_title( 'site_theme', 'post', '', 1 ), 0, 255 );
    $array_config['mobile_theme'] = nv_substr( $nv_Request->get_title( 'mobile_theme', 'post', '', 1 ), 0, 255 );
	$array_config['site_name'] = nv_substr( $nv_Request->get_title( 'site_name', 'post', '', 1 ), 0, 255 );
	$array_config['switch_mobi_des'] = $nv_Request->get_int( 'switch_mobi_des', 'post', 0 );
	$site_logo = $nv_Request->get_title( 'site_logo', 'post' );

	$array_config['site_keywords'] = nv_substr( $nv_Request->get_title( 'site_keywords', 'post', '', 1 ), 0, 255 );

	if( ! empty( $array_config['site_keywords'] ) )
	{
		$site_keywords = array_map( 'trim', explode( ',', $array_config['site_keywords'] ) );
		$array_config['site_keywords'] = array();

		if( ! empty( $site_keywords ) )
		{
			foreach( $site_keywords as $keywords )
			{
				if( ! empty( $keywords ) and ! is_numeric( $keywords ) )
				{
					$array_config['site_keywords'][] = $keywords;
				}
			}
		}

		$array_config['site_keywords'] = ( ! empty( $array_config['site_keywords'] ) ) ? implode( ', ', $array_config['site_keywords'] ) : '';
	}

	if( ! nv_is_url( $site_logo ) and file_exists( NV_DOCUMENT_ROOT . $site_logo ) )
	{
		$lu = strlen( NV_BASE_SITEURL );
		$array_config['site_logo'] = substr( $site_logo, $lu );
	}
	elseif( ! nv_is_url( $site_logo ) )
	{
		$array_config['site_logo'] = 'images/logo.png';
	}

	$array_config['site_home_module'] = nv_substr( $nv_Request->get_title( 'site_home_module', 'post', '', 1 ), 0, 255 );
	$array_config['site_description'] = nv_substr( $nv_Request->get_title( 'site_description', 'post', '', 1 ), 0, 255 );
	$array_config['disable_site_content'] = $nv_Request->get_editor( 'disable_site_content', '', NV_ALLOWED_HTML_TAGS );

	if( empty( $array_config['disable_site_content'] ) )
	{
		$array_config['disable_site_content'] = $lang_global['disable_site_content'];
	}

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = :config_name AND lang = '" . NV_LANG_DATA . "' AND module='global'" );
	foreach( $array_config as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR, 30 );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}

	nv_delete_all_cache();

	if( empty( $errormess ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&rand=' . nv_genpass() );
		exit();
	}
	else
	{
		$sql = "SELECT module, config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' OR lang='" . NV_LANG_DATA . "' ORDER BY module ASC";
		$result = $db->query( $sql );

		while( list( $c_module, $c_config_name, $c_config_value ) = $result->fetch( 3 ) )
		{
			if( $c_module == 'global' )
			{
				$global_config[$c_config_name] = $c_config_value;
			}
			else
			{
				$module_config[$c_module][$c_config_name] = $c_config_value;
			}
		}
	}
}

$theme_array = array();
$theme_array_file = nv_scandir( NV_ROOTDIR . '/themes', $global_config['check_theme'] );

$mobile_theme_array = array();
$mobile_theme_array_file = nv_scandir( NV_ROOTDIR . '/themes', $global_config['check_theme_mobile'] );

$sql = 'SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0';
$result = $db->query( $sql );
while( list( $theme ) = $result->fetch( 3 ) )
{
	if( in_array( $theme, $theme_array_file ) )
	{
		$theme_array[] = $theme;
	}
    elseif( in_array( $theme, $mobile_theme_array_file ) )
    {
        $mobile_theme_array[] = $theme;
    }
}

$global_config['switch_mobi_des'] = ! empty( $global_config['switch_mobi_des'] ) ? ' checked="checked"' : '';

if( ! nv_is_url( $global_config['site_logo'] ) and file_exists( NV_ROOTDIR . '/' . $global_config['site_logo'] ) )
{
	$site_logo = NV_BASE_SITEURL . $global_config['site_logo'];
}
else
{
	$site_logo = $global_config['site_logo'];
}

$value_setting = array(
	'sitename' => $global_config['site_name'],
	'site_logo' => $site_logo,
	'site_keywords' => $global_config['site_keywords'],
	'description' => $global_config['site_description'],
	'switch_mobi_des' => $global_config['switch_mobi_des']
);

$module_array = array();

$sql = 'SELECT title, custom_title FROM ' . NV_MODULES_TABLE . ' WHERE act=1 ORDER BY weight ASC';
$module_array = $db->query( $sql )->fetchAll();

if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
$lang_module['browse_image'] = $lang_global['browse_image'];

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'VALUE', $value_setting );

foreach( $theme_array as $folder )
{
	$xtpl->assign( 'SELECTED', ( $global_config['site_theme'] == $folder ) ? ' selected="selected"' : '' );
	$xtpl->assign( 'SITE_THEME', $folder );
	$xtpl->parse( 'main.site_theme' );
}

if( ! empty( $mobile_theme_array ) )
{
    foreach( $mobile_theme_array as $folder )
    {
        $xtpl->assign( 'SELECTED', ( $global_config['mobile_theme'] == $folder ) ? ' selected="selected"' : '' );
        $xtpl->assign( 'SITE_THEME', $folder );
        $xtpl->parse( 'main.mobile_theme.loop' );
    }
    $xtpl->parse( 'main.mobile_theme' );
}

foreach( $module_array as $mod )
{
	$xtpl->assign( 'SELECTED', ( $global_config['site_home_module'] == $mod['title'] ) ? ' selected="selected"' : '' );
	$xtpl->assign( 'MODULE', $mod );
	$xtpl->parse( 'main.module' );
}

$global_config['disable_site_content'] = htmlspecialchars( nv_editor_br2nl( $global_config['disable_site_content'] ) );

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$disable_site_content = nv_aleditor( 'disable_site_content', '100%', '100px', $global_config['disable_site_content'] );
}
else
{
	$disable_site_content = "<textarea style=\"width:100%;height:100px\" name=\"disable_site_content\" id=\"disable_site_content\">" . $global_config['disable_site_content'] . "</textarea>";
}

$xtpl->assign( 'DISABLE_SITE_CONTENT', $disable_site_content );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );

if( $errormess != '' )
{
	$xtpl->assign( 'ERROR', $errormess );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';