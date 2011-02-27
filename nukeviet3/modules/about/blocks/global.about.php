<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/25/2010 18:6
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_message_about' ) )
{

    /**
     * nv_message_about()
     * 
     * @return
     */
    function nv_message_about ( )
    {
        global $global_config, $site_mods, $db, $module_name;
        
        if ( ! isset( $site_mods['about'] ) ) return "";
        
        if ( $module_name == 'about' ) return "";
        
        $is_show = false;
        
        $pattern = "/^" . NV_LANG_DATA . "\_about\_([0-9]+)\_" . NV_CACHE_PREFIX . "\.cache$/i";
        
        $cache_files = nv_scandir( NV_ROOTDIR . "/" . NV_CACHEDIR, $pattern );
        
        if ( ( $count = count( $cache_files ) ) >= 1 )
        {
            $num = rand( 1, $count );
            $num --;
            $cache_file = $cache_files[$num];
            
            if ( ( $cache = nv_get_cache( $cache_file ) ) != false )
            {
                $cache = unserialize( $cache );
                $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=about&amp;" . NV_OP_VARIABLE . "=" . $cache['alias'];
                $title = $cache['page_title'];
                $bodytext = strip_tags( $cache['bodytext'] );
                
                $is_show = true;
            }
        }
        
        if ( ! $is_show )
        {
            $sql = "SELECT `id`,`title`,`alias`,`bodytext`,`keywords`,`add_time`,`edit_time` FROM `" . NV_PREFIXLANG . "_" . $site_mods['about']['module_data'] . "` WHERE `status`=1 ORDER BY rand() DESC LIMIT 1";
            
            if ( ( $query = $db->sql_query( $sql ) ) !== false )
            {
                if ( ( $row = $db->sql_fetchrow( $query ) ) !== false )
                {
                    $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=about&amp;" . NV_OP_VARIABLE . "=" . $row['alias'];
                    $title = $row['title'];
                    $bodytext = strip_tags( $row['bodytext'] );
                    $bodytext = nv_clean60( $bodytext, 300 );
                    
                    $is_show = true;
                }
            }
        }
        
        if ( $is_show )
        {
            if ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/about/block.about.tpl" ) )
            {
                $block_theme = $global_config['module_theme'];
            }
            elseif ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/about/block.about.tpl" ) )
            {
                $block_theme = $global_config['site_theme'];
            }
            else
            {
                $block_theme = "default";
            }
            
            $xtpl = new XTemplate( "block.about.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/about" );
            $xtpl->assign( 'LINK', $link );
            $xtpl->assign( 'TITLE', $title );
            $xtpl->assign( 'BODYTEXT', $bodytext );
            $xtpl->parse( 'main' );
            return $xtpl->text( 'main' );
        }
        
        return "";
    }
}

$content = nv_message_about();

?>