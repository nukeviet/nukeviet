<?php

/** * @Project NUKEVIET 3.0 * @Author VINADES (contact@vinades.vn) * @Copyright (C) 2010 VINADES. All rights reserved * @Createdate Apr 20, 2010 10:47:41 AM */
if ( ! defined( 'NV_SYSTEM' ) )
{
    die( 'Stop!!!' );
}

define( 'NV_IS_MOD_SEARCH', true );

function LoadModulesSearch ( )
{
    global $site_mods, $global_config;
    $pathmodule = NV_ROOTDIR . "/modules";
    $folder = nv_scandir( $pathmodule, $global_config['check_module'] );
    $arrayfolder = array();
    foreach ( $site_mods as $mod => $arr_mod )
    {
        $pathserch = $pathmodule . "/" . $arr_mod['module_file'] . "/search.php";
        if ( file_exists( $pathserch ) )
        {
            $arrayfolder[$mod] = array( 
                "module_name" => $mod, "module_file" => $arr_mod['module_file'], "module_data" => $arr_mod['module_data'], "custom_title" => $arr_mod['custom_title'] 
            );
        }
    }
    return $arrayfolder;
}

function nv_substr_clean ( $string, $start, $length )
{
    global $global_config;
    if ( nv_strlen( $string ) > $length )
    {
        $string = nv_substr( $string, $start, $length );
        $pos_bg = nv_strpos( $string, " " ) + 1;
        $pos_en = nv_strrpos( $string, " " );
        $string = nv_substr( $string, $pos_bg, $pos_en - $pos_bg );
    }
    return $string;
}

function BoldKeywordInStr ( $str, $keyword )
{
    global $global_config;
    $str = nv_unhtmlspecialchars( strip_tags( $str ) );
    $pos = nv_strpos( $str, $keyword );
    
    $keyword .= " " . nv_EncString( $keyword );
    $array_keyword = explode( " ", $keyword );
    $array_keyword = array_unique( $array_keyword );
    
    if ( $pos === false )
    {
        foreach ( $array_keyword as $k )
        {
            $pos = nv_strpos( $str, $k );
            if ( $pos !== false )
            {
                break;
            }
        }
    }
    
    if ( $pos === false )
    {
        $str = nv_clean60( $str, 300 );
    }
    else
    {
        $index = 0;
        $strlen = nv_strlen( $str );
        if ( $strlen > 300 )
        {
            if ( $pos > 150 and $pos + 150 <= $strlen )
            {
                $index = $pos - 150;
            }
            elseif ( $pos + 150 > $strlen )
            {
                $index = $strlen - 300;
            }
            $str = '...' . nv_substr_clean( $str, $index, 300 ) . '...';
        }
        foreach ( $array_keyword as $k )
        {
            $str = preg_replace( "/(" . preg_quote( $k ) . ")/i", "<span class=\"keyword\">\\1</span>", $str );
        }
    }
    return $str;
}
?>