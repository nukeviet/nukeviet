<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 4/13/2010 20:00
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_error_info()
 * 
 * @return
 */
function nv_error_info ( )
{
    global $lang_global, $global_config, $error_info;
    if ( empty( $error_info ) ) return;
    
    $errortype = array( 
        E_ERROR => array( 
        $lang_global['error_error'], "bad.png" 
    ), //
E_WARNING => array( 
        $lang_global['error_warning'], "warning.png" 
    ), //
E_PARSE => array( 
        $lang_global['error_error'], "bad.png" 
    ), //
E_NOTICE => array( 
        $lang_global['error_notice'], "comment.png" 
    ), //
E_CORE_ERROR => array( 
        $lang_global['error_error'], "bad.png" 
    ), //
E_CORE_WARNING => array( 
        $lang_global['error_warning'], "warning.png" 
    ), //
E_COMPILE_ERROR => array( 
        $lang_global['error_error'], "bad.png" 
    ), //
E_COMPILE_WARNING => array( 
        $lang_global['error_warning'], "warning.png" 
    ), //
E_USER_ERROR => array( 
        $lang_global['error_error'], "bad.png" 
    ), //
E_USER_WARNING => array( 
        $lang_global['error_warning'], "warning.png" 
    ), //
E_USER_NOTICE => array( 
        $lang_global['error_notice'], "comment.png" 
    ), //
E_STRICT => array( 
        $lang_global['error_notice'], "comment.png" 
    ), //
E_RECOVERABLE_ERROR => array( 
        $lang_global['error_error'], "bad.png" 
    ), //
E_DEPRECATED => array( 
        $lang_global['error_notice'], "comment.png" 
    ), //
E_USER_DEPRECATED => array( 
        $lang_global['error_warning'], "warning.png" 
    ) 
    );
    
    if ( defined( 'NV_ADMIN' ) and file_exists( NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/system/error_info.tpl" ) )
    {
        $tpl_path = NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/system";
        $image_path = NV_BASE_SITEURL . "themes/" . $global_config['admin_theme'] . "/images/icons/";
    }
    elseif ( defined( 'NV_ADMIN' ) )
    {
        $tpl_path = NV_ROOTDIR . "/themes/admin_default/system";
        $image_path = NV_BASE_SITEURL . "themes/admin_default/images/";
    }
    elseif ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/system/error_info.tpl" ) )
    {
        $tpl_path = NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/system";
        $image_path = NV_BASE_SITEURL . "themes/" . $global_config['site_theme'] . "/images/icons/";
    }
    else
    {
        $tpl_path = NV_ROOTDIR . "/themes/default/system";
        $image_path = NV_BASE_SITEURL . "themes/default/images/icons/";
    }
    
    $xtpl = new XTemplate( "error_info.tpl", $tpl_path );
    $xtpl->assign( 'TPL_E_CAPTION', $lang_global['error_info_caption'] );
    $a = 0;
    foreach ( $error_info as $key => $value )
    {
        $xtpl->assign( 'TPL_E_CLASS', ( $a % 2 ) ? " class=\"second\"" : "" );
        $xtpl->assign( 'TPL_E_ALT', $errortype[$value['errno']][0] );
        $xtpl->assign( 'TPL_E_SRC', $image_path . $errortype[$value['errno']][1] );
        $xtpl->assign( 'TPL_E_ERRNO', $errortype[$value['errno']][0] );
        $xtpl->assign( 'TPL_E_MESS', $value['info'] );
        $xtpl->set_autoreset();
        $xtpl->parse( 'error_info.error_item' );
        $a ++;
    }
    $xtpl->parse( 'error_info' );
    return $xtpl->text( 'error_info' );
}

/**
 * nv_info_die()
 * 
 * @param string $page_title
 * @param mixed $info_title
 * @param mixed $info_content
 * @return
 */
function nv_info_die ( $page_title = "", $info_title, $info_content, $adminlink = 0 )
{
    global $lang_global, $global_config;
    if ( empty( $page_title ) ) $page_title = $global_config['site_description'];
    if ( defined( 'NV_ADMIN' ) and isset( $global_config['admin_theme'] ) and file_exists( NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/system/info_die.tpl" ) )
    {
        $tpl_path = NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/system";
    }
    elseif ( defined( 'NV_ADMIN' ) and file_exists( NV_ROOTDIR . "/themes/admin_default/system/info_die.tpl" ) )
    {
        $tpl_path = NV_ROOTDIR . "/themes/admin_default/system";
    }
    elseif ( isset( $global_config['module_theme'] ) and file_exists( NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/system/info_die.tpl" ) )
    {
        $tpl_path = NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/system";
    }
    elseif ( isset( $global_config['site_theme'] ) and file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/system/info_die.tpl" ) )
    {
        $tpl_path = NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/system";
    }
    else
    {
        $tpl_path = NV_ROOTDIR . "/themes/default/system";
    }
    
    $size = @getimagesize( NV_ROOTDIR . '/' . $global_config['site_logo'] );
    
    $xtpl = new XTemplate( "info_die.tpl", $tpl_path );
    
    $xtpl->assign( 'SITE_CHERSET', $global_config['site_charset'] );
    $xtpl->assign( 'PAGE_TITLE', $page_title );
    $xtpl->assign( 'HOME_LINK', $global_config['site_url'] );
    $xtpl->assign( 'LOGO', NV_BASE_SITEURL . $global_config['site_logo'] );
    $xtpl->assign( 'WIDTH', $size[0] );
    $xtpl->assign( 'HEIGHT', $size[1] );
    $xtpl->assign( 'INFO_TITLE', $info_title );
    $xtpl->assign( 'INFO_CONTENT', $info_content );
    $xtpl->assign( 'GO_HOMEPAGE', $lang_global['go_homepage'] );
    if ( defined( 'NV_IS_ADMIN' ) )
    {
        $xtpl->assign( 'ADMIN_LINK', NV_BASE_SITEURL . NV_ADMINDIR . "/index.php" );
        $xtpl->assign( 'GO_ADMINPAGE', $lang_global['admin_page'] );
        $xtpl->parse( 'main.adminlink' );
    }
    $xtpl->parse( 'main' );
    include ( NV_ROOTDIR . "/includes/header.php" );
    $xtpl->out( 'main' );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    die();
}

/**
 * nv_rss_generate()
 * 
 * @param mixed $channel
 * @param mixed $imamge
 * @param mixed $items
 * @return void
 */
function nv_rss_generate ( $channel, $items )
{
    global $db, $global_config;
    
    if ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/layout/rss.tpl" ) )
    {
        $path = NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/layout/";
    }
    else
    {
        $path = NV_ROOTDIR . "/themes/default/layout/";
    }
    
    $xtpl = new XTemplate( "rss.tpl", $path );
    
    $channel['title'] = nv_unhtmlspecialchars( $channel['title'] );
    $channel['description'] = nv_unhtmlspecialchars( $channel['description'] );
    $channel['lang'] = $global_config['site_lang'];
    $channel['copyright'] = htmlspecialchars( $global_config['site_name'] );
    $channel['docs'] = NV_MY_DOMAIN . NV_BASE_SITEURL . '?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=rss';
    $channel['generator'] = htmlspecialchars( 'Nukeviet Version ' . $global_config['version'] );
    
    $xtpl->assign( 'CHANNEL', $channel );
    
    if ( file_exists( NV_ROOTDIR . '/' . $global_config['site_logo'] ) )
    {
        $image = NV_ROOTDIR . '/' . $global_config['site_logo'];
        $image = nv_ImageInfo( $image, 144, true, NV_UPLOADS_REAL_DIR );
        
        if ( ! empty( $image ) )
        {
            $image['title'] = $channel['title'];
            $image['link'] = $channel['link'];
            $image['src'] = NV_MY_DOMAIN . $image['src'];
            
            $xtpl->assign( 'IMAGE', $image );
            $xtpl->parse( 'main.image' );
        }
    }
    
    if ( ! empty( $items ) )
    {
        foreach ( $items as $item )
        {
            if ( ! empty( $item['title'] ) )
            {
                $item['title'] = nv_unhtmlspecialchars( $item['title'] );
            }
            
            if ( ! empty( $item['description'] ) )
            {
                $item['description'] = htmlspecialchars( $item['description'], ENT_QUOTES );
            }
            
            $item['pubdate'] = gmdate( "D, j M Y H:m:s", $item['pubdate'] ) . ' GMT';
            
            $xtpl->assign( 'ITEM', $item );
            $xtpl->parse( 'main.item' );
        }
    }
    
    $xtpl->parse( 'main' );
    $content = $xtpl->text( 'main' );
    $content = $db->unfixdb( $content );
    $content = nv_url_rewrite( $content );
    
    header( "Content-Type: text/xml" );
    header( "Content-Type: application/rss+xml" );
    header( "Content-Encoding: none" );
    echo $content;
    die();
}

?>