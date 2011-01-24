<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$page_title = sprintf( $lang_module['lang_site_config'], $language_array[NV_LANG_DATA]['name'] );

if ( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

$submit = $nv_Request->get_string( 'submit', 'post' );
$errormess = "";
if ( $submit )
{
    $array_config = array();
    $array_config['site_theme'] = filter_text_input( 'site_theme', 'post', '', 1, 255 );
    $array_config['site_name'] = filter_text_input( 'site_name', 'post', '', 1, 255 );
    $site_logo = filter_text_input( 'site_logo', 'post' );
    
    if ( ! nv_is_url( $site_logo ) and file_exists( NV_DOCUMENT_ROOT . $site_logo ) )
    {
        $lu = strlen( NV_BASE_SITEURL );
        $array_config['site_logo'] = substr( $site_logo, $lu );
    }
    elseif ( ! nv_is_url( $site_logo ) )
    {
        $array_config['site_logo'] = "images/logo.png";
    }
    
    $array_config['site_home_module'] = filter_text_input( 'site_home_module', 'post', '', 1, 255 );
    $array_config['site_description'] = filter_text_input( 'site_description', 'post', '', 1, 255 );
    $array_config['disable_site'] = $nv_Request->get_int( 'disable_site', 'post' );
    $array_config['disable_site_content'] = filter_text_textarea( 'disable_site_content', '', NV_ALLOWED_HTML_TAGS );
    $array_config['footer_content'] = filter_text_textarea( 'footer_content', '', NV_ALLOWED_HTML_TAGS );
    
    if ( empty( $array_config['disable_site_content'] ) )
    {
        $array_config['disable_site_content'] = $lang_global['disable_site_content'];
    }
    $array_config['disable_site_content'] = nv_nl2br( $array_config['disable_site_content'], '<br />' ); // dung de save vao csdl
    foreach ( $array_config as $config_name => $config_value )
    {
        $db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('" . NV_LANG_DATA . "', 'global', " . $db->dbescape( $config_name ) . ", " . $db->dbescape( $config_value ) . ")" );
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

$value_setting = array(  //
    "sitename" => $global_config['site_name'], //
"site_logo" => ( nv_is_url( $global_config['site_logo'] ) ) ? NV_BASE_SITEURL . $global_config['site_logo'] : $global_config['site_logo'], //
"description" => $global_config['site_description'], //
"disable_content" => $global_config['disable_site_content'], //
"footer_content" => isset( $global_config['footer_content'] ) ? $global_config['footer_content'] : ""  //
);

$module_array = array();

$sql = "SELECT title, custom_title FROM `" . NV_MODULES_TABLE . "` WHERE `act`=1 ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $module_array[] = $row;
}

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file . "" );

$lang_module['browse_image'] = $lang_global['browse_image'];

$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'VALUE', $value_setting );

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

$content .= "<script type=\"text/javascript\">\n";
$content .= "//<![CDATA[\n";
$content .= '$("input[name=selectimg]").click(function(){
						var area = "site_logo";
						var path= "";						
						var currentpath= "images";						
						var type= "image";
						nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type="+type+"&currentpath="+currentpath, "NVImg", "850", "420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
						return false;
					});\n';
$content .= "//]]>\n";
$content .= "</script>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $content );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>