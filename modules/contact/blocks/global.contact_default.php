<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_contact_default_info' ) )
{
    function nv_contact_default_info()
    {
        global $db, $site_mods, $global_config, $lang_global;

        if ( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods['contact']['module_file'] . '/block.contact_default.tpl' ) )
        {
            $block_theme = $global_config['module_theme'];
        }
        elseif ( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $site_mods['contact']['module_file'] . '/block.contact_default.tpl' ) )
        {
            $block_theme = $global_config['site_theme'];
        }
        else
        {
            $block_theme = 'default';
        }

        $sql = 'SELECT id, alias, phone, email, yahoo, skype FROM ' . NV_PREFIXLANG . '_' . $site_mods['contact']['module_data'] . '_department WHERE act=1 AND is_default=1';
        $array_department = nv_db_cache( $sql, 'id', 'contact' );
        if ( empty( $array_department ) )
        {
            $sql = 'SELECT id, alias, phone, email, yahoo, skype FROM ' . NV_PREFIXLANG . '_' . $site_mods['contact']['module_data'] . '_department WHERE act=1 ORDER BY weight LIMIT 1';
            $array_department = nv_db_cache( $sql, 'id', 'contact' );
        }

        if ( empty( $array_department ) ) return "";

        $xtpl = new XTemplate( 'block.contact_default.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods['contact']['module_file'] );
        $xtpl->assign( 'LANG', $lang_global );
        $row = array_shift( $array_department );
        if ( empty( $row ) ) return "";
        $row['emailhref'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

        $xtpl->assign( 'DEPARTMENT', $row );

        if ( ! empty( $row['phone'] ) )
        {
            $nums = array_map( "trim", explode( "|", nv_unhtmlspecialchars( $row['phone'] ) ) );
            $mainphone = array();
            foreach ( $nums as $k => $num )
            {
                unset( $m );
                if ( preg_match( "/^(.*)\s*\[([0-9\+\.\,\;\*\#]+)\]$/", $num, $m ) )
                {
                    $phone = array( 'number' => nv_htmlspecialchars( $m[1] ), 'href' => $m[2] );
                    $xtpl->assign( 'PHONE', $phone );
                    $xtpl->parse( 'main.phone.item.href' );
                    $xtpl->parse( 'main.phone.item.href2' );
                    
                    if ( $k == 0 ) $mainphone = $phone;
                }
                else
                {
                    
                    $num = preg_replace( "/\[[^\]]*\]/", "", $num );
                    $phone = array( 'number' => nv_htmlspecialchars( $num ) );
                    $xtpl->assign( 'PHONE', $phone );
                    
                    if ( $k == 0 ) $mainphone = $phone;
                }
                if ( $k ) $xtpl->parse( 'main.phone.item.comma' );
                $xtpl->parse( 'main.phone.item' );
            }
            
            $xtpl->parse( 'main.phone' );
            
            if( !empty( $mainphone ) )
            {
                $xtpl->assign( 'MAINPHONE', $mainphone );
                if( isset( $mainphone['href'] ) )
                {
                    $xtpl->parse( 'main.mainphone.href' );
                    $xtpl->parse( 'main.mainphone.href2' );
                }
                $xtpl->parse( 'main.mainphone' );
            }
        }

        if ( ! empty( $row['email'] ) )
        {
            $emails = array_map( "trim", explode( ",", $row['email'] ) );

            foreach ( $emails as $k => $email )
            {
                $xtpl->assign( 'EMAIL', $email );
                if ( $k ) $xtpl->parse( 'main.email.item.comma' );
                $xtpl->parse( 'main.email.item' );
            }

            $xtpl->parse( 'main.email' );
        }

        if ( ! empty( $row['yahoo'] ) )
        {
            $ys = array_map( "trim", explode( ",", $row['yahoo'] ) );
            foreach ( $ys as $k => $y )
            {
                $xtpl->assign( 'YAHOO', $y );
                if ( $k ) $xtpl->parse( 'main.yahoo.item.comma' );
                $xtpl->parse( 'main.yahoo.item' );
            }                        
            $xtpl->parse( 'main.yahoo' );
        }

        if ( ! empty( $row['skype'] ) )
        {
            $ss = array_map( "trim", explode( ",", $row['skype'] ) );
            foreach ( $ss as $k => $s )
            {
                $xtpl->assign( 'SKYPE', $s );
                if ( $k ) $xtpl->parse( 'main.skype.item.comma' );
                $xtpl->parse( 'main.skype.item' );
            }
            $xtpl->parse( 'main.skype' );
        }
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_contact_default_info();
}
