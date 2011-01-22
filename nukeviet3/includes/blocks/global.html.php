<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES ., JSC. All rights reserved
 * @Createdate Jan 10, 2011  6:04:30 PM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_block_data_config_html' ) )
{

    function nv_block_data_config_html ( $module, $data_block, $lang_block )
    {
        global $lang_module;
        
        if ( defined( 'NV_EDITOR' ) )
        {
            require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
        }
        
        $htmlcontent = ( defined( 'NV_EDITOR' ) ) ? nv_editor_br2nl( $data_block['htmlcontent'] ) : nv_br2nl( $data_block['htmlcontent'] );
        $htmlcontent = nv_htmlspecialchars( $htmlcontent );
        if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
        {
            $html = nv_aleditor( "htmlcontent", '660px', '150px', $htmlcontent );
        }
        else
        {
            $html = "<textarea style=\"width: 660px\" name=\"htmlcontent\" id=\"htmlcontent\" cols=\"20\" rows=\"8\">" . $htmlcontent . "</textarea>";
        }
        return '<tr><td>' . $lang_block['htmlcontent'] . '</td><td>' . $html . '</td></tr>';
    }

    function nv_block_data_config_html_submit ( $module, $lang_block )
    {
        $xhtml = filter_text_textarea( 'htmlcontent', '', NV_ALLOWED_HTML_TAGS );
        
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['htmlcontent'] = defined( 'NV_EDITOR' ) ? nv_editor_nl2br( $xhtml ) : nv_nl2br( $xhtml, '<br />' );
        return $return;
    }

    function nv_block_global_html ( $block_config )
    {
        return $block_config['htmlcontent'];
    }
}

if ( defined( 'NV_SYSTEM' ) )
{
    $content = nv_block_global_html( $block_config );
}

?>