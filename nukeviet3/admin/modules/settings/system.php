<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['global_config'];
if ( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

$submit = $nv_Request->get_string( 'submit', 'post' );

$errormess = "";
$array_config_global = $global_config;

if ( $submit )
{
    $array_config_global = array();
    $array_config_global['admin_theme'] = filter_text_input( 'admin_theme', 'post', '', 1, 255 );
    $array_config_global['gfx_chk'] = $nv_Request->get_int( 'gfx_chk', 'post' );
    
    $array_config_global['site_keywords'] = filter_text_input( 'site_keywords', 'post', '', 1, 255 );
    $array_config_global['site_logo'] = filter_text_input( 'site_logo', 'post', '', 1, 255 );
    $array_config_global['site_email'] = filter_text_input( 'site_email', 'post', '', 1, 255 );
    $array_config_global['error_send_email'] = filter_text_input( 'error_send_email', 'post', '', 1, 255 );
    $array_config_global['site_phone'] = filter_text_input( 'site_phone', 'post', '', 1, 255 );
    $array_config_global['site_lang'] = filter_text_input( 'site_lang', 'post', '', 1, 255 );
    
    $array_config_global['site_timezone'] = filter_text_input( 'site_timezone', 'post', '', 1, 255 );
    $array_config_global['date_pattern'] = filter_text_input( 'date_pattern', 'post', '', 1, 255 );
    $array_config_global['time_pattern'] = filter_text_input( 'time_pattern', 'post', '', 1, 255 );
    $array_config_global['my_domains'] = filter_text_input( 'my_domains', 'post', '', 1, 255 );
    $array_config_global['cookie_prefix'] = filter_text_input( 'cookie_prefix', 'post', '', 1, 255 );
    $array_config_global['session_prefix'] = filter_text_input( 'session_prefix', 'post', '', 1, 255 );
    
    $array_config_global['gzip_method'] = $nv_Request->get_int( 'gzip_method', 'post' );
    $array_config_global['online_upd'] = $nv_Request->get_int( 'online_upd', 'post' );
    $array_config_global['statistic'] = $nv_Request->get_int( 'statistic', 'post' );
    $array_config_global['lang_multi'] = $nv_Request->get_int( 'lang_multi', 'post' );
    $array_config_global['proxy_blocker'] = $nv_Request->get_int( 'proxy_blocker', 'post' );
    $array_config_global['str_referer_blocker'] = $nv_Request->get_int( 'str_referer_blocker', 'post' );
    
   
    if ( $sys_info['supports_rewrite'] !== false )
    {
        $array_config_global['is_url_rewrite'] = $nv_Request->get_int( 'is_url_rewrite', 'post' );
        if ( $global_config['lang_multi'] == 0 )
        {
            $array_config_global['rewrite_optional'] = $nv_Request->get_int( 'rewrite_optional', 'post' );
        }
        else
        {
            $array_config_global['rewrite_optional'] = 0;
        }
    }
    
    foreach ( $array_config_global as $config_name => $config_value )
    {
        $db->sql_query( "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', " . $db->dbescape_string( $config_name ) . ", " . $db->dbescape_string( $config_value ) . ")" );
        $db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET 
        `config_value`=" . $db->dbescape_string( $config_value ) . " 
        WHERE `config_name` = " . $db->dbescape_string( $config_name ) . " 
        AND `lang` = 'sys' AND `module`='global' 
        LIMIT 1" );
    }
    
    if ( isset( $array_config_global['is_url_rewrite'] ) and $array_config_global['is_url_rewrite'] == 1 )
    {
        $reval = $filename = "";
        if ( $sys_info['supports_rewrite'] == "rewrite_mode_iis" )
        {
            $filename = "web.config";
            $reval = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $reval .= "<configuration>\n";
            $reval .= "    <system.webServer>\n";
            $reval .= "        <rewrite>\n";
            $reval .= "            <rules>\n";
            if ( $array_config_global['rewrite_optional'] )
            {
                $reval .= "                <rule name=\"Imported Rule 5\">\n";
                $reval .= "                    <match url=\"^([a-z0-9-]+)/([a-zA-Z0-9-/]+)/([0-9-/]+)/$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}&amp;" . NV_OP_VARIABLE . "={R:2}&amp;id={R:3}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                $reval .= "                <rule name=\"Imported Rule 52\">\n";
                $reval .= "                    <match url=\"^([a-z0-9-]+)/([a-zA-Z0-9-/]+)/([0-9-/]+)$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}&amp;" . NV_OP_VARIABLE . "={R:2}&amp;id={R:3}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                
                $reval .= "                <rule name=\"Imported Rule 4\">\n";
                $reval .= "                    <match url=\"^([a-z0-9-]+)/([a-zA-Z0-9-/]+)/$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}&amp;" . NV_OP_VARIABLE . "={R:2}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                $reval .= "                <rule name=\"Imported Rule 42\">\n";
                $reval .= "                    <match url=\"^([a-z0-9-]+)/([a-zA-Z0-9-/]+)$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}&amp;" . NV_OP_VARIABLE . "={R:2}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                
                $reval .= "                <rule name=\"Imported Rule 3\">\n";
                $reval .= "                    <match url=\"" . NV_ADMINDIR . "[/]*$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"" . NV_ADMINDIR . "/index.php\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                if ( defined( 'DIR_FORUM' ) and DIR_FORUM != "" and is_dir( NV_ROOTDIR . "/" . DIR_FORUM ) )
                {
                    $reval .= "                <rule name=\"Imported Rule 32\">\n";
                    $reval .= "                    <match url=\"" . DIR_FORUM . "[/]*$\" ignoreCase=\"false\" />\n";
                    $reval .= "                    <action type=\"Rewrite\" url=\"" . DIR_FORUM . "/index.php\" appendQueryString=\"false\" />\n";
                    $reval .= "                </rule>\n";
                }
                $reval .= "                <rule name=\"Imported Rule 2\">\n";
                $reval .= "                    <match url=\"^([a-z0-9-]+)/$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                
                $reval .= "                <rule name=\"Imported Rule 22\">\n";
                $reval .= "                    <match url=\"^([a-z0-9-]+)$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
            
            }
            else
            {
                $reval .= "                <rule name=\"Imported Rule 5\">\n";
                $reval .= "                    <match url=\"^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)/([0-9-/]+)/$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}&amp;" . NV_OP_VARIABLE . "={R:3}&amp;id={R:4}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                $reval .= "                <rule name=\"Imported Rule 52\">\n";
                $reval .= "                    <match url=\"^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)/([0-9-/]+)$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}&amp;" . NV_OP_VARIABLE . "={R:3}&amp;id={R:4}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                $reval .= "                <rule name=\"Imported Rule 4\">\n";
                $reval .= "                    <match url=\"^([a-z-]+)/([a-z-]+)/([a-zA-Z0-9-/]+)/$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}&amp;" . NV_OP_VARIABLE . "={R:3}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                $reval .= "                <rule name=\"Imported Rule 42\">\n";
                $reval .= "                    <match url=\"^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}&amp;" . NV_OP_VARIABLE . "={R:3}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                $reval .= "                <rule name=\"Imported Rule 3\">\n";
                $reval .= "                    <match url=\"^([a-z-]+)/([a-z0-9-]+)/$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                $reval .= "                <rule name=\"Imported Rule 32\">\n";
                $reval .= "                    <match url=\"^([a-z-]+)/([a-z0-9-]+)$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                $reval .= "                <rule name=\"Imported Rule 2\">\n";
                $reval .= "                    <match url=\"" . NV_ADMINDIR . "[/]*$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"" . NV_ADMINDIR . "/index.php\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                if ( defined( 'DIR_FORUM' ) and DIR_FORUM != "" and is_dir( NV_ROOTDIR . "/" . DIR_FORUM ) )
                {
                    $reval .= "                <rule name=\"Imported Rule 22\">\n";
                    $reval .= "                    <match url=\"" . DIR_FORUM . "[/]*$\" ignoreCase=\"false\" />\n";
                    $reval .= "                    <action type=\"Rewrite\" url=\"" . DIR_FORUM . "/index.php\" appendQueryString=\"false\" />\n";
                    $reval .= "                </rule>\n";
                }
                $reval .= "                <rule name=\"Imported Rule 1\">\n";
                $reval .= "                    <match url=\"^([a-z0-9-]+)/$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
                $reval .= "                <rule name=\"Imported Rule 12\">\n";
                $reval .= "                    <match url=\"^([a-z0-9-]+)$\" ignoreCase=\"false\" />\n";
                $reval .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}\" appendQueryString=\"false\" />\n";
                $reval .= "                </rule>\n";
            
            }
            $reval .= "            </rules>\n";
            $reval .= "        </rewrite>\n";
            $reval .= "    </system.webServer>\n";
            $reval .= "</configuration>\n";
        }
        elseif ( $sys_info['supports_rewrite'] == "rewrite_mode_apache" )
        {
            $filename = ".htaccess";
            $htaccess = "";
            
            $reval = "##################################################################################\n";
            $reval .= "#nukeviet_rewrite_start //Please do not change the contents of the following lines\n";
            $reval .= "##################################################################################\n\n";
            $reval .= "#Options +FollowSymLinks\n\n";
            $reval .= "<IfModule mod_rewrite.c>\n";
            $reval .= "RewriteEngine On\n";
            $reval .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
            $reval .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
            if ( $array_config_global['rewrite_optional'] )
            {
                $reval .= "RewriteRule ^([a-z0-9-]+)/([a-zA-Z0-9-/]+)/([0-9-/]+)/$ index.php?" . NV_NAME_VARIABLE . "=$1&" . NV_OP_VARIABLE . "=$2&id=$3\n";
                $reval .= "RewriteRule ^([a-z0-9-]+)/([a-zA-Z0-9-/]+)/([0-9-/]+)$ index.php?" . NV_NAME_VARIABLE . "=$1&" . NV_OP_VARIABLE . "=$2&id=$3\n";
                $reval .= "RewriteRule ^([a-z0-9-]+)/([a-zA-Z0-9-/]+)/$ index.php?" . NV_NAME_VARIABLE . "=$1&" . NV_OP_VARIABLE . "=$2\n";
                $reval .= "RewriteRule ^([a-z0-9-]+)/([a-zA-Z0-9-/]+)$ index.php?" . NV_NAME_VARIABLE . "=$1&" . NV_OP_VARIABLE . "=$2\n";
                $reval .= "RewriteRule ^" . NV_ADMINDIR . "[/]*$ " . NV_ADMINDIR . "/index.php\n";
                if ( defined( 'DIR_FORUM' ) and DIR_FORUM != "" and is_dir( NV_ROOTDIR . "/" . DIR_FORUM ) )
                {
                    $reval .= "RewriteRule ^" . DIR_FORUM . "[/]*$ " . DIR_FORUM . "/index.php\n";
                }
                $reval .= "RewriteRule ^([a-z0-9-]+)/$ index.php?" . NV_NAME_VARIABLE . "=$1\n";
                $reval .= "RewriteRule ^([a-z0-9-]+)$ index.php?" . NV_NAME_VARIABLE . "=$1\n";
            }
            else
            {
                $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)/([0-9-/]+)/$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2&" . NV_OP_VARIABLE . "=$3&id=$4\n";
                $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)/([0-9-/]+)$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2&" . NV_OP_VARIABLE . "=$3&id=$4\n";
                $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)/$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2&" . NV_OP_VARIABLE . "=$3\n";
                $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/([a-zA-Z0-9-/]+)$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2&" . NV_OP_VARIABLE . "=$3\n";
                $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2\n";
                $reval .= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2\n";
                $reval .= "RewriteRule ^" . NV_ADMINDIR . "[/]*$ " . NV_ADMINDIR . "/index.php\n";
                if ( defined( 'DIR_FORUM' ) and DIR_FORUM != "" and is_dir( NV_ROOTDIR . "/" . DIR_FORUM ) )
                {
                    $reval .= "RewriteRule ^" . DIR_FORUM . "[/]*$ " . DIR_FORUM . "/index.php\n";
                }
                $reval .= "RewriteRule ^([a-z-]+)/$ index.php?" . NV_LANG_VARIABLE . "=$1\n";
                $reval .= "RewriteRule ^([a-z-]+)$ index.php?" . NV_LANG_VARIABLE . "=$1\n";
            }
            $reval .= "</IfModule>\n\n";
            $reval .= "#nukeviet_rewrite_end\n";
            $reval .= "##################################################################################\n\n";
            
            if ( file_exists( NV_ROOTDIR . '/' . $filename ) )
            {
                $htaccess = @file_get_contents( NV_ROOTDIR . '/' . $filename );
                if ( ! empty( $htaccess ) )
                {
                    $htaccess = preg_replace( "/[\n]*[\#]+[\n]+\#nukeviet\_rewrite\_start(.*)\#nukeviet\_rewrite\_end[\n]+[\#]+[\n]*/s", "\n", $htaccess );
                    $htaccess = trim( $htaccess );
                }
            }
            $htaccess .= "\n\n" . $reval;
            $reval = $htaccess;
        }
        if ( ! empty( $filename ) and ! empty( $reval ) )
        {
            $savefile = true;
            try
            {
                file_put_contents( NV_ROOTDIR . "/" . $filename, $reval, LOCK_EX );
                if ( ! file_exists( NV_ROOTDIR . "/" . $filename ) or filesize( NV_ROOTDIR . "/" . $filename ) == 0 )
                {
                    $errormess .= sprintf( $lang_module['err_writable'], NV_BASE_SITEURL . $filename );
                    $savefile = false;
                }
            }
            catch ( Exception $e )
            {
                $savefile = false;
            }
            if ( ! $savefile )
            {
                $errormess .= sprintf( $lang_module['err_writable'], NV_BASE_SITEURL . $filename );
            }
        }
    }
    nv_save_file_config_global();
    nv_delete_all_cache(); //xoa toan bo cache
    if ( empty( $errormess ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
        exit();
    }
    else
    {
        $sql = $db->constructQuery( "SELECT `module`, `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` 
        WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `module` ASC", NV_LANG_DATA );
        $result = $db->sql_query( $sql );
        while ( list( $c_module, $c_config_name, $c_config_value ) = $db->sql_fetchrow( $result ) )
        {
            if ( $c_module == "global" )
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

$themeadmin_array = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme_admin'] );

$captcha_array = array(  //
    0 => $lang_module['captcha_0'], //
1 => $lang_module['captcha_1'], //
2 => $lang_module['captcha_2'], //
3 => $lang_module['captcha_3'], //
4 => $lang_module['captcha_4'], //
5 => $lang_module['captcha_5'], //
6 => $lang_module['captcha_6'], //
7 => $lang_module['captcha_7']  //
);

$proxy_blocker_array = array(  //
    0 => $lang_module['proxy_blocker_0'], //
1 => $lang_module['proxy_blocker_1'], //
2 => $lang_module['proxy_blocker_2'], //
3 => $lang_module['proxy_blocker_3']  //
);

$array_config_global['gzip_method'] = ( $global_config['gzip_method'] ) ? " checked" : "";
$array_config_global['online_upd'] = ( $global_config['online_upd'] ) ? " checked" : "";
$array_config_global['statistic'] = ( $global_config['statistic'] ) ? " checked" : "";
$array_config_global['lang_multi'] = ( $global_config['lang_multi'] ) ? " checked" : "";
$array_config_global['str_referer_blocker'] = ( $global_config['str_referer_blocker'] ) ? " checked" : "";
$array_config_global['my_domains'] = implode( ",", $global_config['my_domains'] );

$xtpl = new XTemplate( "system.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name . "" );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config_global );

foreach ( $themeadmin_array as $folder )
{
    $xtpl->assign( 'SELECTED', ( $global_config['admin_theme'] == $folder ) ? ' selected="selected"' : '' );
    $xtpl->assign( 'SITE_THEME_ADMIN', $folder );
    $xtpl->parse( 'main.admin_theme' );
}

foreach ( $captcha_array as $gfx_chk_i => $gfx_chk_lang )
{
    $xtpl->assign( 'GFX_CHK_SELECTED', ( $global_config['gfx_chk'] == $gfx_chk_i ) ? ' selected="selected"' : '' );
    $xtpl->assign( 'GFX_CHK_VALUE', $gfx_chk_i );
    $xtpl->assign( 'GFX_CHK_TITLE', $gfx_chk_lang );
    $xtpl->parse( 'main.opcaptcha' );
}

foreach ( $proxy_blocker_array as $proxy_blocker_i => $proxy_blocker_v )
{
    $xtpl->assign( 'PROXYSELECTED', ( $global_config['proxy_blocker'] == $proxy_blocker_i ) ? ' selected="selected"' : '' );
    $xtpl->assign( 'PROXYOP', $gfx_chk_i );
    $xtpl->assign( 'PROXYVALUE', $proxy_blocker_v );
    $xtpl->parse( 'main.proxy_blocker' );
}

if ( $sys_info['supports_rewrite'] !== false )
{
    $xtpl->assign( 'CHECKED1', ( $global_config['is_url_rewrite'] == 1 ) ? ' checked ' : '' );
    $xtpl->parse( 'main.support_rewrite' );
}

if ( $sys_info['supports_rewrite'] !== false and $global_config['lang_multi'] == 0 )
{
    $xtpl->assign( 'CHECKED2', ( $global_config['rewrite_optional'] == 1 ) ? ' checked ' : '' );
    $xtpl->parse( 'main.rewrite_optional' );
}

foreach ( $global_config['allow_sitelangs'] as $lang_i )
{
    if ( file_exists( NV_ROOTDIR . "/language/" . $lang_i . "/global.php" ) )
    {
        $xtpl->assign( 'LANGOP', $lang_i );
        $xtpl->assign( 'SELECTED', ( $lang_i == $global_config['site_lang'] ) ? "selected='selected'" : "" );
        $xtpl->assign( 'LANGVALUE', $language_array[$lang_i]['name'] );
        $xtpl->parse( 'main.site_lang_option' );
    }
}

$timezone_array = array_keys( nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/timezone.ini', true ) );

foreach ( $timezone_array as $site_timezone_i )
{
    $xtpl->assign( 'TIMEZONEOP', $site_timezone_i );
    $xtpl->assign( 'TIMEZONESELECTED', ( $site_timezone_i == $global_config['site_timezone'] ) ? "selected='selected'" : "" );
    $xtpl->assign( 'TIMEZONELANGVALUE', $site_timezone_i );
    $xtpl->parse( 'main.opsite_timezone' );
}

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