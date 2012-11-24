<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/12/2010, 13:16
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$file_metatags = NV_ROOTDIR . "/" . NV_DATADIR . "/metatags.xml";

$metatags = array();
$metatags['meta'] = array();
$ignore = array( 'content-type', 'generator', 'description', 'keywords' );
$vas = array( '{CONTENT-LANGUAGE} (' . $lang_global['Content_Language'] . ')', '{LANGUAGE} (' . $lang_global['LanguageName'] . ')', '{SITE_NAME} (' . $global_config['site_name'] . ')', '{SITE_EMAIL} (' . $global_config['site_email'] . ')' );

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $metaGroupsName = $nv_Request->get_array( 'metaGroupsName', 'post' );
    $metaGroupsValue = $nv_Request->get_array( 'metaGroupsValue', 'post' );
    $metaContents = $nv_Request->get_array( 'metaContents', 'post' );

    foreach ( $metaGroupsName as $key => $name )
    {
        if ( $name == "http-equiv" or $name == "name" )
        {
            $value = strtolower( trim( strip_tags( $metaGroupsValue[$key] ) ) );
            $content = trim( strip_tags( $metaContents[$key] ) );
            if ( preg_match( "/^[a-zA-Z0-9\-\_\.]+$/", $value ) //
                and ! in_array( $value, $ignore ) //
                and preg_match( "/^([^\'\"]+)$/", $content ) //
                and ! in_array( ( $newArray = array( 'group' => $name, 'value' => $value, 'content' => $content ) ), $metatags['meta'] ) )
            {
                $metatags['meta'][] = $newArray;
            }
        }
    }

    if ( file_exists( $file_metatags ) ) nv_deletefile( $file_metatags );

    if ( ! empty( $metatags['meta'] ) )
    {
        include ( NV_ROOTDIR . '/includes/class/array2xml.class.php' );
        $array2XML = new Array2XML();
        $array2XML->saveXML( $metatags, 'metatags', $file_metatags, $global_config['site_charset'] );
    }
}
else
{
    if ( file_exists( $file_metatags ) )
    {
        $mt = simplexml_load_file( $file_metatags );
        $mt = nv_object2array( $mt );
        if ( $mt['meta_item'] )
        {
            if ( isset( $mt['meta_item'][0] ) ) $metatags['meta'] = $mt['meta_item'];
            else  $metatags['meta'][] = $mt['meta_item'];
        }
    }
}

$page_title = $lang_module['metaTagsConfig'];

$xtpl = new XTemplate( "metatags.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NOTE', sprintf( $lang_module['metaTagsNote'], implode( ", ", $ignore ) ) );
$xtpl->assign( 'VARS', $lang_module['metaTagsVar'] . ": " . implode( ", ", $vas ) );

if ( ! empty( $metatags['meta'] ) )
{
    foreach ( $metatags['meta'] as $value )
    {
        $value['h_selected'] = $value['group'] == 'http-equiv' ? " selected=\"selected\"" : "";
        $value['n_selected'] = $value['group'] == 'name' ? " selected=\"selected\"" : "";
        $xtpl->assign( 'DATA', $value );
        $xtpl->parse( 'main.loop' );
    }
}

for ( $i = 0; $i < 3; ++$i )
{
    $data = array( 'content' => '', 'value' => '', 'h_selected' => '', 'n_selected' => '' );
    $xtpl->assign( 'DATA', $data );
    $xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>