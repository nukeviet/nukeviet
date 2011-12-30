<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
 
if ( ! defined( 'NV_IS_MOD_WEBLINKS' ) ) die( 'Stop!!!' );

global $global_array_cat, $catid;

if ( empty( $catid ) ) $catid = 0;

function nv_weblink_category ( )
{
    global $global_array_cat, $lang_module, $module_file,$module_info;
	
    $xtpl = new XTemplate( "block_category.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'TEMPLATE', $module_info['template'] );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'BLOCK_ID', "web" . rand( 1, 1000 ) );
	
    if ( ! empty( $global_array_cat ) )
    {
        $title_length = 20;
        $html = "";
		
        foreach ( $global_array_cat as $cat )
        {
            if ( $cat['parentid'] == 0 )
            {
                $html .= "<li>\n";
                $html .= "<a title=\"" . $cat['title'] . "\" href=\"" . $cat['link'] . "\">" . nv_clean60( $cat['title'], $title_length ) . "</a>\n";
                $html .= nv_weblink_sub_category( $cat['catid'], $title_length );
                $html .= "</li>\n";
            }
        }
		
        $xtpl->assign( 'HTML_CONTENT', $html );
        $xtpl->parse( 'main' );
		
        return $xtpl->text( 'main' );
    }
}

function nv_weblink_sub_category ( $catid, $title_length )
{
    global $global_array_cat;
	
    if ( empty( $catid ) )
    {
        return "";
    }
    else
    {
        $html = "<ul>\n";
        foreach ( $global_array_cat as $cat )
        {
            if ( $cat['parentid'] == $catid )
            {
                $html .= "<li>\n";
                $html .= "<a title=\"" . $global_array_cat[$cat['catid']]['title'] . "\" href=\"" . $global_array_cat[$cat['catid']]['link'] . "\">" . nv_clean60( $global_array_cat[$cat['catid']]['title'], $title_length ) . "</a>\n";
                $html .= nv_weblink_sub_category( $cat['catid'], $title_length );
                $html .= "</li>\n";
            }
        }
        $html .= "</ul>\n";
		
        return $html;
    }
}

$content = nv_weblink_category ();

?>