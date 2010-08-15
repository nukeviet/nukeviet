<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/25/2010 18:6
 */
if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' ); 

if ( ! function_exists( 'nv_message_about' ) )
{
    function nv_message_about()
    {
        global $global_config, $db, $lang_global, $site_mods, $module_file;

        $sql = "SELECT `id`,`title`,`alias`,`bodytext` FROM `" . NV_PREFIXLANG . "_about` WHERE status = 1 LIMIT 1";
        $result = $db->sql_query( $sql );
        $num = $db->sql_numrows( $result );
        if ( $num )
        {
            list( $id, $title, $alias, $bodytext ) = $db->sql_fetchrow( $result );
            $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=about&amp;" . NV_OP_VARIABLE . "=" . $alias;
            $bodytext = strip_tags( $bodytext );
            $bodytext = nv_clean60( $bodytext, 300 );
            $xtpl = new XTemplate( "global.about.tpl", NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/blocks/" );
            $xtpl->assign( 'LINK', $link );
            $xtpl->assign( 'TITLE', $title );
            $xtpl->assign( 'BODYTEXT', $bodytext );
            $xtpl->parse( 'main' );
            $content = $xtpl->text( 'main' );
            return $content;
        }
    }
}

$content = nv_message_about();

?>