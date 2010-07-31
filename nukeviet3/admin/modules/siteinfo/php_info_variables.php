<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 22:4
 */

if ( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

require_once ( NV_ROOTDIR . "/includes/core/phpinfo.php" );
$array = phpinfo_array( 32, 1 );
$contents = "";
if ( ! empty( $array['PHP Variables'] ) )
{
    $xtpl = new XTemplate( "variables_php.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
    $caption = $lang_module['variables_php'];
    $thead = array( 
        $lang_module['variable'], $lang_module['value'] 
    );
    
    $xtpl->assign( 'CAPTION', $caption );
    $xtpl->assign( 'THEAD0', $thead[0] );
    $xtpl->assign( 'THEAD1', $thead[1] );
    $a = 0;
    foreach ( $array['PHP Variables'] as $key => $value )
    {
        $xtpl->assign( 'CLASS', ( $a % 2 ) ? " class=\"second\"" : "" );
        $xtpl->assign( 'KEY', $key );
        $xtpl->assign( 'VALUE', $value );
        $xtpl->parse( 'main.loop' );
        $a ++;
    }
    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );
}

$page_title = $lang_module['variables_php'];
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>