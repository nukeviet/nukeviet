<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['lang_site_config'];

if ( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

$submit = $nv_Request->get_string( 'submit', 'post' );
$images = nv_scandir( NV_ROOTDIR . '/images', "/^([a-zA-Z0-9\_\-\.]+)\.(gif|jpg|jpeg|png)$/" );

$errormess = "";

if ( $submit )
{
    $array_config = array();
    $array_config['site_theme'] = filter_text_input( 'site_theme', 'post', '', 1, 255 );
    $array_config['site_name'] = filter_text_input( 'site_name', 'post', '', 1, 255 );
    $array_config['site_logo'] = filter_text_input( 'site_logo', 'post', '', 1, 255 );
    if ( ! in_array( $array_config['site_logo'], $images ) )
    {
        $array_config['site_logo'] = "logo.png";
    }
    $array_config['site_home_module'] = filter_text_input( 'site_home_module', 'post', '', 1, 255 );
    $array_config['site_description'] = filter_text_input( 'site_description', 'post', '', 1, 255 );
    $array_config['disable_site'] = $nv_Request->get_int( 'disable_site', 'post' );
    $array_config['disable_site_content'] = filter_text_textarea( 'disable_site_content', '', NV_ALLOWED_HTML_TAGS );
    if ( empty( $array_config['disable_site_content'] ) )
    {
        $array_config['disable_site_content'] = $lang_global['disable_site_content'];
    }
    $array_config['disable_site_content'] = nv_nl2br( $array_config['disable_site_content'], '<br />' ); // dung de save vao csdl
    foreach ( $array_config as $config_name => $config_value )
    {
        $db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` 
        SET `config_value`=" . $db->dbescape( $config_value ) . " 
        WHERE `config_name` = " . $db->dbescape( $config_name ) . " 
        AND `lang` = '" . NV_LANG_DATA . "' AND `module`='global' 
        LIMIT 1" );
    }
    if ( $array_config['site_theme'] != $global_config['site_theme'] )
    {
        $global_config['site_theme'] = $array_config['site_theme'];
        nv_set_layout_site();
    }
    nv_save_file_config_global();
    if ( empty( $errormess ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&rand=' . nv_genpass() );
        exit();
    }
    else
    {
        $sql = $db->constructQuery( "SELECT `module`, `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` 
        WHERE `lang`=[s] OR `lang`=[s] ORDER BY `module` ASC", 'sys', NV_LANG_DATA );
        $result = $db->sql_query( $sql );
        while ( list( $c_module, $c_config_name, $c_config_value ) = $db->sql_fetchrow( $result ) )
        {
            if ( $c_module == "global" ) $global_config[$c_config_name] = $c_config_value;
            else $module_config[$c_module][$c_config_name] = $c_config_value;
        }
    }
}
$theme_array = array();
$theme_array_file = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );
$sql = "SELECT DISTINCT `theme` FROM `" . NV_PREFIXLANG . "_modthemes`  WHERE `func_id`=0";
$result = $db->sql_query( $sql );
while ( list( $theme ) = $db->sql_fetchrow( $result ) )
{
    if ( in_array( $theme, $theme_array_file ) )
    {
        $theme_array[] = $theme;
    }
}

$global_config['disable_site_content'] = nv_br2nl( $global_config['disable_site_content'] ); // dung de lay data tu CSDL
$global_config['disable_site_content'] = nv_htmlspecialchars( $global_config['disable_site_content'] );

$value_setting[] = array(  //
    "sitename" => $global_config['site_name'], //
    "site_logo" => $global_config['site_logo'], //
	"description" => $global_config['site_description'], //
	"disable_content" => $global_config['disable_site_content']  //
);

$module_array = array();

$sql = "SELECT title, custom_title FROM `" . NV_MODULES_TABLE . "` WHERE `act`=1 ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $module_array[] = $row;
}

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name . "" );
$xtpl->assign( 'LANG', $lang_module );
foreach ( $value_setting as $value_setting_i )
{
    $xtpl->assign( 'VALUE', $value_setting_i );
}

foreach ( $theme_array as $folder )
{
    $xtpl->assign( 'SELECTED', ( $global_config['site_theme'] == $folder ) ? ' selected="selected"' : '' );
    $xtpl->assign( 'SITE_THEME', $folder );
    $xtpl->parse( 'main.site_theme' );
}
foreach ( $module_array as $mod )
{
    $xtpl->assign( 'SELECTED', ( $global_config['site_home_module'] == $mod['title'] ) ? ' selected="selected"' : '' );
    $xtpl->assign( 'MODULE', $mod );
    $xtpl->parse( 'main.module' );
}

$xtpl->assign( 'CHECKED3', ( $global_config['disable_site'] == 1 ) ? ' checked' : '' );

$xtpl->parse( 'main' );
$content = "";
if ( $errormess != "" )
{
    $content .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $content .= "<blockquote class=\"error\"><span>" . $errormess . "</span></blockquote>\n";
    $content .= "</div>\n";
    $content .= "<div class=\"clear\"></div>\n";
}
$content .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $content );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>