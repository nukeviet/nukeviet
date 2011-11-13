<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 24/8/2010, 2:0
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

$array_modul = LoadModulesSearch();

$key = filter_text_input( 'q', 'get', '', 0, NV_MAX_SEARCH_LENGTH );
$len_key = 0;

$logic = filter_text_input( 'logic', 'get', 'AND' );
if ( $logic != 'AND' ) $logic = 'OR';

$checkss = filter_text_input( 'search_checkss', 'get', '', 1, 32 );
$ss = md5( $client_info['session_id'] . $global_config['sitekey'] );

if ( ! preg_match( "/^[a-z0-9]{32}$/", $checkss ) or $checkss != $ss )
{
    $key = "";
}

if ( ! empty( $key ) )
{
    $key = nv_unhtmlspecialchars( $key );
    if ( $logic == 'OR' )
    {
        $key = preg_replace( array( "/^([\S]{1})\s/uis", "/\s([\S]{1})\s/uis", "/\s([\S]{1})$/uis" ), " ", $key );
    }
    $key = strip_punctuation( $key );
    $key = trim( $key );
    $len_key = nv_strlen( $key );
    $key = nv_htmlspecialchars( $key );
}

$mod = filter_text_input( 'mod', 'get', 'all', 1 );
if ( ! isset( $array_modul[$mod] ) )
{
    $mod = "all";
}

$contents = call_user_func( "main_theme", $key, $ss, $logic, $array_modul, $mod );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?> 