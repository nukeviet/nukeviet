<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 21/12/2010, 8:10
 */

if ( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

/**
 * nv_getModVersion()
 * 
 * @param integer $updatetime
 * @return
 */
function nv_getModVersion( $updatetime = 3600 )
{
    global $global_config;

    $my_file = NV_ROOTDIR . '/' . NV_CACHEDIR . '/modules.version.' . NV_LANG_INTERFACE . '.xml';

    $xmlcontent = false;

    $p = NV_CURRENTTIME - $updatetime;

    if ( file_exists( $my_file ) and @filemtime( $my_file ) > $p )
    {
        $xmlcontent = simplexml_load_file( $my_file );
    }
    else
    {
        include ( NV_ROOTDIR . "/includes/class/geturl.class.php" );
        $getContent = new UrlGetContents( $global_config );
        $content = $getContent->get( 'http://update.nukeviet.vn/nukeviet.version.xml?module=all&lang=' . NV_LANG_INTERFACE );

        if ( ! empty( $content ) )
        {
            $xmlcontent = simplexml_load_string( $content );
            if ( $xmlcontent !== false )
            {
                file_put_contents( $my_file, $content );
            }
        }
    }

    return $xmlcontent;
}

$page_title = $lang_module['checkupdate'];
$contents = "";

$xtpl = new XTemplate( "checkupdate.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'LANG', $lang_module );

if ( $nv_Request->isset_request( 'i', 'get' ) )
{
    $i = $nv_Request->get_string( 'i', 'get' );

    if ( $i == 'sysUpd' or $i == 'sysUpdRef' )
    {
        $values = array();
        $values['userVersion'] = $global_config['version'];
        $new_version = ( $i == 'sysUpd' ) ? nv_geVersion( 28800 ) : nv_geVersion( 120 );
        $values['onlineVersion'] = sprintf( $lang_module['newVersion_detail'], ( string )$new_version->version, ( string )$new_version->name, nv_date( "d-m-Y H:i", strtotime( $new_version->date ) ) );
        $xtpl->assign( 'VALUE', $values );
        if ( nv_version_compare( $global_config['version'], $new_version->version ) < 0 )
        {
            $xtpl->assign( 'VERSION_INFO', ( string )$new_version->message );
            $xtpl->assign( 'VERSION_LINK', sprintf( $lang_module['newVersion_info'], ( string )$new_version->link ) );
            $xtpl->parse( 'sysUpd.inf' );
        }
        $xtpl->parse( 'sysUpd' );

        echo $xtpl->text( 'sysUpd' );
    } elseif ( $i == "modUpd" or $i == "modUpdRef" )
    {
        $_modules = ( $i == 'modUpd' ) ? nv_getModVersion( 28800 ) : nv_getModVersion( 120 );
        $_modules = nv_object2array( $_modules );
        $_modules = $_modules['module'];
        $modules = array();
        foreach ( $_modules as $m )
        {
            $name = array_shift( $m );
            $modules[$name] = $m;
            unset( $modules[$name]['date'] );
            $modules[$name]['pubtime'] = strtotime( $m['date'] );
        }

        $sql = "SELECT `module_file`, `mod_version`, `author` FROM `" . $db_config['prefix'] . "_setup_modules` GROUP BY `module_file` ORDER BY `module_file` ASC";
        $result = $db->sql_query( $sql );
        while ( list( $module_file, $mod_version, $author ) = $db->sql_fetchrow( $result ) )
        {
            if ( ! isset( $modules[$module_file] ) ) $modules[$module_file] = array();
            $v = "";
            $p = 0;
            unset( $matches );
            if ( preg_match( "/^([^\s]+)\s+([\d]+)$/", $mod_version, $matches ) )
            {
                $v = ( string )$matches[1];
                $p = ( int )$matches[2];
            }

            if ( isset( $modules[$module_file]['pubtime'], $modules[$module_file]['version'], $modules[$module_file]['author'] ) //
                and $modules[$module_file]['version'] == $v //
                and ( $modules[$module_file]['pubtime'] != $p or $modules[$module_file]['author'] != $author ) )
            {
                $sql2 = "UPDATE `" . $db_config['prefix'] . "_setup_modules` 
                SET `mod_version`=" . $db->dbescape( $v . ' ' . $modules[$module_file]['pubtime'] ) . ", 
                `author`=" . $db->dbescape( $modules[$module_file]['author'] ) . " 
                WHERE `module_file`=" . $db->dbescape( $module_file );
                $db->sql_query( $sql2 );
            }

            $modules[$module_file]['u_version'] = $v;
            $modules[$module_file]['u_pubtime'] = $p;
        }

        ksort( $modules );

        $a = 1;
        foreach ( $modules as $modname => $values )
        {
            if ( ! isset( $values['version'] ) )
            {
                $note = $lang_module['moduleNote1'];
                $cl = "Note1";
            } elseif ( ! isset( $values['u_version'] ) )
            {
                $note = sprintf( $lang_module['moduleNote2'], $values['link'] );
                $cl = "Note2";
            } elseif ( empty( $values['u_version'] ) )
            {
                $note = sprintf( $lang_module['moduleNote3'], $values['link'] );
                $cl = "Note3";
            } elseif ( nv_version_compare( $values['u_version'], $values['version'] ) < 0 )
            {
                $note = sprintf( $lang_module['moduleNote4'], $values['link'] );
                $cl = "Note4";
            }
            else
            {
                $note = $lang_module['moduleNote5'];
                $cl = "Note5";
            }

            $info = $lang_module['userVersion'] . ": ";
            $info .= ! empty( $values['u_version'] ) ? $values['u_version'] : "n/a";
            $info .= "; " . $lang_module['onlineVersion'] . ": ";
            $info .= ! empty( $values['version'] ) ? $values['version'] : "n/a";

            $tooltip = array();
            $tooltip[] = array( //
                'title' => $lang_module['userVersion'], //
                'content' => ( ! empty( $values['u_version'] ) ? $values['u_version'] : "n/a" ) . ( ! empty( $values['u_pubtime'] ) ? " (" . nv_date( "d-m-Y H:i", $values['u_pubtime'] ) . ")" : "" ) //
                );
            $tooltip[] = array( //
                'title' => $lang_module['onlineVersion'], //
                'content' => ( ! empty( $values['version'] ) ? $values['version'] : "n/a" ) . ( ! empty( $values['pubtime'] ) ? " (" . nv_date( "d-m-Y H:i", $values['pubtime'] ) . ")" : "" ) //
                );

            if ( isset( $values['author'] ) and ! empty( $values['author'] ) )
            {
                $tooltip[] = array( //
                    'title' => $lang_module['moduleAuthor'], //
                    'content' => $values['author'] //
                    );
            }

            if ( isset( $values['license'] ) and ! empty( $values['license'] ) )
            {
                $tooltip[] = array( //
                    'title' => $lang_module['moduleLicense'], //
                    'content' => $values['license'] //
                    );
            }

            if ( isset( $values['mode'] ) and ! empty( $values['mode'] ) )
            {
                $tooltip[] = array( //
                    'title' => $lang_module['moduleMode'], //
                    'content' => $values['mode'] == "sys" ? $lang_module['moduleModeSys'] : $lang_module['moduleModeOther'] //
                    );
            }

            if ( isset( $values['link'] ) and ! empty( $values['link'] ) )
            {
                $tooltip[] = array( //
                    'title' => $lang_module['moduleLink'], //
                    'content' => "<a href=\"" . $values['link'] . "\">" . $values['link'] . "</a>" //
                    );
            }
            
            if ( isset( $values['support'] ) and ! empty( $values['support'] ) )
            {
                $tooltip[] = array( //
                    'title' => $lang_module['moduleSupport'], //
                    'content' => "<a href=\"" . $values['support'] . "\">" . $values['support'] . "</a>" //
                    );
            }

            $xtpl->assign( 'CLASS', ( $a % 2 ) ? " class=\"second\"" : "" );
            $xtpl->assign( 'MODNAME', $modname );
            $xtpl->assign( 'MODINFO', $info );

            foreach ( $tooltip as $t )
            {
                $xtpl->assign( 'MODTOOLTIP', $t );
                $xtpl->parse( 'modUpd.loop.li' );
            }
            
            if ( ! isset( $values['version'] ) )
            {
                $xtpl->parse( 'modUpd.loop.note1' );
            }

            $xtpl->assign( 'MODCL', $cl );
            $xtpl->assign( 'MODNOTE', $note );
            $xtpl->parse( 'modUpd.loop' );
            $a++;
        }

        $xtpl->parse( 'modUpd' );

        echo $xtpl->text( 'modUpd' );
    }
    die();
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>