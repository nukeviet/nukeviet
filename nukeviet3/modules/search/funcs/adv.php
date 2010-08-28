<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate 04/05/2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$array_modul = LoadModulesSearch();

$mod = filter_text_input( 'search_mod', 'get', 'all', 1 );

if ( ! empty( $mod ) and isset( $array_modul[$mod] ) )
{
    $mods = array( $mod => $array_modul[$mod] );
    $limit = 10;
    $is_generate_page = true;
}
else
{
    $mods = $array_modul;
    $limit = 3;
    $is_generate_page = false;
}

$key = filter_text_input( 'search_query', 'get', '', 0, NV_MAX_SEARCH_LENGTH );
$len_key = 0;

$logic = filter_text_input( 'logic', 'get', 'OR' );
if ( $logic != 'AND' ) $logic = 'OR';

$checkss = filter_text_input( 'search_ss', 'get', '', 1, 32 );
$ss = md5( $client_info['session_id'] . $global_config['sitekey'] );

if ( ! preg_match( "/^[a-z0-9]{32}$/", $checkss ) or $checkss != $ss )
{
    $key = "";
}
else
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

if ( $len_key < NV_MIN_SEARCH_LENGTH )
{
    die( '&nbsp;&nbsp;' );
}

$pages = $nv_Request->get_int( 'page', 'get', 0 );

$contents = "";
$ss = md5( $client_info['session_id'] . $global_config['sitekey'] );

foreach ( $mods as $m_name => $m_values )
{
    $all_page = 0;
    $result_array = array();

    $dbkeyword = $db->dblikeescape( $key );

    include ( NV_ROOTDIR . "/modules/" . $m_values['module_file'] . "/search.php" );

    if ( ! empty( $all_page ) and ! empty( $result_array ) )
    {
        $contents .= result_theme( $result_array, $m_name, $m_values['custom_title'], $key, $logic, $ss, $is_generate_page, $pages, $limit, $all_page );
    }
}

if ( empty( $contents ) )
{
    $contents = $lang_module['search_none'] . " &quot;" . $key . "&quot;";
}

echo $contents;

?>