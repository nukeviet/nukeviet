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

        $sql = 'SELECT id, phone, email, yahoo, skype FROM ' . NV_PREFIXLANG . '_' . $site_mods['contact']['module_data'] . '_department WHERE act=1 AND is_default=1';
        $array_department = nv_db_cache( $sql, 'id', 'contact' );
        if ( empty( $array_department ) )
        {
            $sql = 'SELECT id, phone, email, yahoo, skype FROM ' . NV_PREFIXLANG . '_' . $site_mods['contact']['module_data'] . '_department WHERE act=1 ORDER BY weight LIMIT 1';
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
            $xtpl->parse( 'main.phone' );
        }

        if ( ! empty( $row['email'] ) )
        {
            $xtpl->parse( 'main.email' );
        }

        if ( ! empty( $row['yahoo'] ) )
        {
            $xtpl->parse( 'main.yahoo' );
        }

        if ( ! empty( $row['skype'] ) )
        {
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
