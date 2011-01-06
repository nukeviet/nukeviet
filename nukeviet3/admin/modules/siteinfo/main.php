<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 22:5
 */
if ( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );
$page_title = $lang_global['mod_siteinfo'];

/**
 * nv_get_lang_module()
 * 
 * @param mixed $mod
 * @return
 */
function nv_get_lang_module( $mod )
{
    global $site_mods;
    $lang_module = array();
    if ( isset( $site_mods[$mod] ) )
    {
        if ( file_exists( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_INTERFACE . ".php" ) )
        {
            include ( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_INTERFACE . ".php" );
        } elseif ( file_exists( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_DATA . ".php" ) )
        {
            include ( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_DATA . ".php" );
        } elseif ( file_exists( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_en.php" ) )
        {
            include ( NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_en.php" );
        }
    }
    return $lang_module;
}

/**
 * NukevietChange_getContents()
 * 
 * @param bool $refresh
 * @return
 */
function NukevietChange_getContents( $refresh = false )
{
    $url = "http://code.google.com/feeds/p/nuke-viet/svnchanges/basic";
    $xmlfile = "nukevietGoogleCode.cache";
    $load = false;
    $p = NV_CURRENTTIME - 18000;
    $p2 = NV_CURRENTTIME - 120;
    if ( ! file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $xmlfile ) ) $load = true;
    else
    {
        $filemtime = @filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $xmlfile );
        if ( $filemtime < $p ) $load = true;
        elseif ( $refresh and $filemtime < $p2 ) $load = true;
    }

    if ( $load )
    {
        include ( NV_ROOTDIR . '/includes/class/geturl.class.php' );
        $UrlGetContents = new UrlGetContents( $global_config );
        $content = $UrlGetContents->get( $url );
        if ( ! empty( $content ) )
        {
            if ( function_exists( 'mb_convert_encoding' ) ) $content = mb_convert_encoding( $content, "utf-8" );
            $content = simplexml_load_string( $content );
            $content = nv_object2array( $content );
            if ( ! empty( $content ) )
            {
                $code = array();
                $code['updated'] = strtotime( $content['updated'] );
                $code['link'] = $content['link'][0]['@attributes']['href'];
                $code['entry'] = array();

                if ( isset( $content['entry'] ) and ! empty( $content['entry'] ) )
                {
                    foreach ( $content['entry'] as $entry )
                    {
                        unset( $matches );
                        $cont = $entry['content'];
                        preg_match_all( "/(modify|add|delete)[^a-z0-9\/\.\-\_]+(\/trunk\/nukeviet3\/)([a-z0-9\/\.\-\_]+)/mi", $cont, $matches, PREG_SET_ORDER );
                        $cont = array();
                        if ( ! empty( $matches ) )
                        {
                            foreach ( $matches as $matche )
                            {
                                $key = strtolower( $matche[1] );
                                if ( ! isset( $cont[$key] ) ) $cont[$key] = array();
                                $cont[$key][] = $matche[3];
                            }
                        }

                        unset( $matches2 );
                        preg_match( "/Revision[\s]+([\d]*)[\s]*\:[\s]+(.*?)/Uis", $entry['title'], $matches2 );
                        $code['entry'][] = array( //
                            'updated' => strtotime( $entry['updated'] ), //
                            'title' => $matches2[2], //
                            'id' => $matches2[1], //
                            'link' => $entry['link']['@attributes']['href'], //
                            'author' => $entry['author']['name'], //
                            'content' => $cont //
                            );
                    }

                    nv_set_cache( $xmlfile, serialize( $code ) );
                    return $code;
                }
            }
        }
    }

    $content = nv_get_cache( $xmlfile );
    if ( ! $content ) return false;
    $content = unserialize( $content );
    return $content;
}

//Cap nhat thong tin tu du an NukeViet tren Google Code
if ( $nv_Request->isset_request( 'gcode', 'get' ) and ( $gcode = $nv_Request->get_int( 'gcode', 'get', 0 ) ) )
{
    if ( ! defined( 'NV_IS_SPADMIN' ) )
    {
        die();
    }

    if ( $gcode != 1 ) $changes = NukevietChange_getContents( true );
    else  $changes = NukevietChange_getContents();
    if ( ! empty( $changes ) and ! empty( $changes['entry'] ) )
    {
        $xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );

        $xtpl->assign( 'LANG', $lang_module );
        $xtpl->assign( 'CAPTION', $lang_module['nukevietChange_caption'] );
        $xtpl->assign( 'UPDATED', $lang_module['nukevietChange_upd'] . nv_date( " d-m-Y H:i", $changes['updated'] ) );
        $xtpl->assign( 'REFRESH', $lang_module['nukevietChange_refresh'] );
        $xtpl->assign( 'VISIT', $changes['link'] );

        foreach ( $changes['entry'] as $key => $entry )
        {
            //if ( $key == 10 ) break;
            $entry['tooltip'] = array();
            foreach ( $entry['content'] as $k => $v )
            {
                $entry['tooltip'][] = "<strong>" . $lang_module['nukevietChange_' . $k] . "</strong>: " . implode( ", ", $v );
            }
            $entry['tooltip'] = ! empty( $entry['tooltip'] ) ? "<ul><li>" . implode( "</li><li>", $entry['tooltip'] ) . "</li></ul>" : "";
            $entry['updated'] = nv_date( "d-m-Y H:i", $entry['updated'] );
            $xtpl->assign( 'CLASS', ( $key % 2 ) ? " class=\"second\"" : "" );
            $xtpl->assign( 'ENTRY', $entry );
            $xtpl->parse( 'NukevietChange.loop' );
        }
        $xtpl->parse( 'NukevietChange' );
        echo $xtpl->text( 'NukevietChange' );
        exit;
    }
    die();
}

//Noi dung chinh cua trang
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
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'CAPTION', $lang_module['moduleInfo'] );
    $a = 0;
    foreach ( $info as $if )
    {
        foreach ( $if['field'] as $field )
        {
            $xtpl->assign( 'CLASS', ( $a % 2 ) ? " class=\"second\"" : "" );
            $xtpl->assign( 'KEY', $field['key'] );
            $xtpl->assign( 'VALUE', $field['value'] );
            $xtpl->assign( 'MODULE', $if['caption'] );
            $xtpl->parse( 'main.main1.loop' );
            $a++;
        }
    }
    $xtpl->parse( 'main.main1' );

    //Thong tin phien ban NukeViet
    if ( defined( 'NV_IS_GODADMIN' ) )
    {
        $field = array();
        $field[] = array( 'key' => $lang_module['version_user'], 'value' => $global_config['version'] );
        $new_version = nv_geVersion( 28800 ); //kem tra lai sau 8 tieng
        $info = "";
        if ( ! empty( $new_version ) )
        {
            $field[] = array( //
                'key' => $lang_module['version_news'], //
                'value' => sprintf( $lang_module['newVersion_detail'], ( string )$new_version->version, nv_date( "d-m-Y H:i", strtotime( $new_version->date ) ) ) );

            if ( nv_version_compare( $global_config['version'], $new_version->version ) < 0 )
            {
                $info = sprintf( $lang_module['newVersion_info'], NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=webtools&amp;" . NV_OP_VARIABLE . "=checkupdate" );
            }
        }

        $xtpl->assign( 'CAPTION', $lang_module['version'] );
        $xtpl->assign( 'ULINK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=webtools&amp;" . NV_OP_VARIABLE . "=checkupdate" );
        $xtpl->assign( 'CHECKVERSION', $lang_module['checkversion'] );

        foreach ( $field as $key => $value )
        {
            $xtpl->assign( 'CLASS', ( $key % 2 ) ? " class=\"second\"" : "" );
            $xtpl->assign( 'KEY', $value['key'] );
            $xtpl->assign( 'VALUE', $value['value'] );
            $xtpl->parse( 'main.main2.loop' );
        }

        if ( ! empty( $info ) )
        {
            $xtpl->assign( 'INFO', $info );
            $xtpl->parse( 'main.main2.inf' );
        }

        $xtpl->parse( 'main.main2' );
    }

    if ( defined( 'NV_IS_SPADMIN' ) )
    {
        $xtpl->parse( 'main.main3' );
    }

    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );
} elseif ( ! defined( 'NV_IS_SPADMIN' ) and ! empty( $site_mods ) )
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