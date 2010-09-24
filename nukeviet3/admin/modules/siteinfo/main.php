<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 22:5
 */
if ( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );
$page_title = $lang_global['mod_siteinfo'];

function nv_get_lang_module ( $mod )
{
    global $site_mods;
    $lang_module = array();
    if ( isset( $site_mods[$mod] ) )
    {
        if ( file_exists( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_INTERFACE . ".php" ) )
        {
            include ( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_INTERFACE . ".php" );
        }
        elseif ( file_exists( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_DATA . ".php" ) )
        {
            include ( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_DATA . ".php" );
        }
        elseif ( file_exists( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_en.php" ) )
        {
            include ( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_en.php" );
        }
    }
    return $lang_module;
}

$info = array();
foreach ( $site_mods as $mod => $value )
{
    if ( file_exists( NV_ROOTDIR . "/modules/" . $value['module_file'] . "/siteinfo.php" ) )
    {
        $siteinfo = array();
        $mod_data = $value['module_data'];
        include ( NV_ROOTDIR . "/modules/" . $value['module_file'] . "/siteinfo.php" );
        if ( ! empty( $siteinfo ) )
        {
            $info[$mod]['caption'] = $value['custom_title'];
            $info[$mod]['field'] = $siteinfo;
        }
    }
}
if ( ! empty( $info ) )
{
    $xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
    foreach ( $info as $if )
    {
        $xtpl->assign( 'CAPTION', $if['caption'] );
        foreach ( $if['field'] as $key => $field )
        {
            $xtpl->assign( 'CLASS', ( $key % 2 ) ? " class=\"second\"" : "" );
            $xtpl->assign( 'KEY', $field['key'] );
            $xtpl->assign( 'VALUE', $field['value'] );
            $xtpl->parse( 'main.loop' );
        }
        $xtpl->parse( 'main' );
    }
    $contents = $xtpl->text( 'main' );
}
elseif ( ! defined( 'NV_IS_SPADMIN' ) and ! empty( $site_mods ) )
{
    $arr_mod = array_keys( $site_mods );
    $module_name = $arr_mod[0];
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>