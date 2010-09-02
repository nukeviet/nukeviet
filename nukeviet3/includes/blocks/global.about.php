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

    /**
     * nv_message_about()
     * 
     * @return
     */
    function nv_message_about()
    {
        global $global_config, $site_mods;

        if ( ! isset( $site_mods['about'] ) ) return "";

        $pattern = "/^" . NV_LANG_DATA . "\_about\_([0-9]+)\_" . NV_CACHE_PREFIX . "\.cache$/i";

        $cache_files = nv_scandir( NV_ROOTDIR . "/" . NV_CACHEDIR, $pattern );

        if ( ( $count = count( $cache_files ) ) >= 1 )
        {
            $num = rand( 1, $count );
            $num--;
            $cache_file = $cache_files[$num];

            if ( ( $cache = nv_get_cache( $cache_file ) ) != false )
            {
                $cache = unserialize( $cache );
                $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=about&amp;" . NV_OP_VARIABLE . "=" . $cache['alias'];
                $title = $cache['page_title'];
                $bodytext = strip_tags( $cache['contents'] );
                $bodytext = nv_clean60( $bodytext, 300 );

                $block_theme = ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/blocks/global.about.tpl" ) ) ? $global_config['site_theme'] : "default";

                $xtpl = new XTemplate( "global.about.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/blocks" );
                $xtpl->assign( 'LINK', $link );
                $xtpl->assign( 'TITLE', $title );
                $xtpl->assign( 'BODYTEXT', $bodytext );
                $xtpl->parse( 'main' );
                return $xtpl->text( 'main' );
            }
        }

        return "";
    }
}

$content = nv_message_about();

?>