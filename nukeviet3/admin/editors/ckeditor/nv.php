<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Apr 10, 2010  10:08:08 AM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

@require_once ( str_replace( '\\\\', '/', dirname( __file__ ) ) . '/ckeditor_php5.php' );
@require_once ( str_replace( '\\\\', '/', dirname( __file__ ) ) . '/../ckfinder/ckfinder.php' );

/**
 * nv_aleditor()
 * 
 * @param mixed $textareaname
 * @param string $width
 * @param string $height
 * @param string $val
 * @return
 */

function nv_aleditor ( $textareaname, $width = "100%", $height = '450px', $val = '' )
{
    // Create class instance.
    

    $editortoolbar = array( 
        array( 
        'Cut', 'Copy', 'Paste', 'PasteText', 'PasteWord', '-', 'Undo', 'Redo', '-', 'Link', 'Unlink', 'Anchor', '-', 'Image', 'Flash', 'Table', 'Font', 'FontSize', 'RemoveFormat', 'Templates', 'Maximize' 
    ), array( 
        'Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'Subscript', 'Superscript', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'OrderedList', 'UnorderedList', '-', 'Outdent', 'Indent', 'Blockquote', 'CreateDiv', '-', 'TextColor', 'BGColor', 'SpecialChar', 'Smiley', 'PageBreak', 'Source' 
    ) 
    );
    
    $CKEditor = new CKEditor();
    // Do not print the code directly to the browser, return it instead
    $CKEditor->returnOutput = true;
    $CKEditor->config['skin'] = 'office2003';
    $CKEditor->config['entities'] = false;
    //$CKEditor->config['enterMode'] = 2;
    $CKEditor->config['language'] = NV_LANG_INTERFACE;
    $CKEditor->config['toolbar'] = $editortoolbar;
    
    // Path to CKEditor directory, ideally instead of relative dir, use an absolute path:
    //   $CKEditor->basePath = '/ckeditor/'
    // If not set, CKEditor will try to detect the correct path.
    $CKEditor->basePath = NV_BASE_SITEURL . '' . NV_EDITORSDIR . '/ckeditor/';
    // Set global configuration (will be used by all instances of CKEditor).
    if ( ! empty( $width ) )
    {
        $CKEditor->config['width'] = strpos( $width, '%' ) ? $width : intval( $width );
    }
    
    if ( ! empty( $height ) )
    {
        $CKEditor->config['height'] = strpos( $height, '%' ) ? $height : intval( $height );
    }
    
    // Change default textarea attributes
    $CKEditor->textareaAttributes = array( 
        "cols" => 80, "rows" => 10 
    );
    
    // Create first instance.
    CKFinder::SetupCKEditor( $CKEditor, NV_BASE_SITEURL . '' . NV_EDITORSDIR . '/ckfinder/' );
    
    return $CKEditor->editor( $textareaname, $val );
}

/**
 * nv_add_editor_js()
 * 
 * @return
 */
function nv_add_editor_js ( )
{
    return "";
}

?>