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
    $css = $line = '';
    if( empty( $tag ) ) return '';
    
    if( is_array( $property_array ) )
    {
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
        $line .= $css == '' ? '' : $tag . '{' . $css . '}';
    }
    else
    {
        $css .= $property_array;
        $line .= $css == '' ? '' : $css;
    }

    return $line;
}

function CustomStyle()
{
    global $module_config, $global_config;
    $property = '';
    
    if( isset( $module_config['themes'][$global_config['site_theme']] ) ) 
    {
         $config_theme = unserialize( $module_config['themes'][$global_config['site_theme']] );
    }

    $property .= SetProperties( 'body', $config_theme['body'] );
    $property .= SetProperties( 'a, a:link, a:active, a:visited', $config_theme['a_link'] );
    $property .= SetProperties( 'a:hover', $config_theme['a_link_hover'] );
    $property .= SetProperties( '#wraper', $config_theme['content'] );
    $property .= SetProperties( '#header', $config_theme['header'] );
    $property .= SetProperties( '#footer', $config_theme['footer'] );
	$property .= SetProperties( '.panel, .well, .nv-block-banners', $config_theme['block'] );
	$property .= SetProperties( '.panel-default>.panel-heading', $config_theme['block_heading'] );
	// Không nên thay đổi "generalcss"
    $property .= SetProperties( 'generalcss', $config_theme['generalcss'] );
    
    return $property;
}
