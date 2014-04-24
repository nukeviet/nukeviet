<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function SetProperties( $tag, $property_array )
{
    $css = '';
    if( empty( $tag ) ) return $css;

    foreach( $property_array as $property => $value )
    {
        if( $property != 'customcss' )
        {
            if( ! empty( $property ) and ! empty( $value ) )
            {
                $property = str_replace( '_', '-', $property );
                if( $property == 'background-image' ) $value = "url('" . $value . "')";
                $css .= $property . ':' . $value . ';';
            }
        }
        elseif( ! empty( $value ) )
        {
            $value = substr(trim($value), -1) == ';' ? $value : $value . ';';
            $css .= $value;
        }
    }
    $line = $css == '' ? '' : PHP_EOL . $tag . '{' . $css . '}';
    
    return $line;
}

function CustomStyle()
{
    global $module_config, $global_config;
     
    if( isset( $module_config['themes'][$global_config['site_theme']] ) ) 
    {
         $config_theme = unserialize( $module_config['themes'][$global_config['site_theme']] );
    }
    
    $property = SetProperties( 'body', $config_theme['body'] );
    
    return $property;
}
