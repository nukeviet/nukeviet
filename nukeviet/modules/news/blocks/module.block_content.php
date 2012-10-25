<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

global $global_config, $module_name, $module_info, $module_file, $lang_module, $client_info;

if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/block_content.tpl" ) )
{
    $block_theme = $module_info['template'];
}
else
{
    $block_theme = "default";
}
$xtpl = new XTemplate( "block_content.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file );

$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LINK_CONTENT', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content" );
$xtpl->assign( 'LINK_ADD_CONTENT', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;contentid=0&amp;checkss=" . md5( '0' . $client_info['session_id'] . $global_config['sitekey'] ) );

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>