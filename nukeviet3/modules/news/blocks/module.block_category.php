<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
global $global_array_cat, $module_name, $module_info, $lang_module;
$subcat = array();
$cat = array();

$subcat[0] = array();

foreach ( $global_array_cat as $key => $array_subcat )
{
    $parentid = $array_subcat['parentid'];
    $catid = $array_subcat['catid'];
    if ( $parrentid = $catid and $parentid != 0 )
    {
        $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $array_subcat['alias'];
        $subcat[$parentid][] = array( 
            'catid' => $catid, 'title' => $array_subcat['title'], 'link' => $link 
        );
    }
}
foreach ( $global_array_cat as $catid => $array_cat )
{
    $catid_i = $array_cat['catid'];
    if ( $array_cat['parentid'] == 0 and $catid_i != 0 )
    {
        $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $array_cat['alias'];
        $sub = isset( $subcat[$catid_i] ) ? $subcat[$catid_i] : array();
        $cat[] = array( 
            'catid' => $catid_i, 'title' => $array_cat['title'], 'link' => $link, 'sub' => $sub 
        );
    }
}

$xtpl = new XTemplate( "block_category.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
if ( ! empty( $cat ) )
{
    $my_head .= "<script type=\"text/javascript\"	src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/js/jquery.category.news.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\">\n";
    $my_head .= "jQuery(document).ready(function(){\n";
    $my_head .= "$(\"#navmenu-v li\").hover(function(){\n";
    $my_head .= "	$(this).addClass(\"iehover\");\n";
    $my_head .= "	}, function(){\n";
    $my_head .= "	$(this).removeClass(\"iehover\");\n";
    $my_head .= "});\n";
    $my_head .= "});\n";
    $my_head .= "</script>\n";
    $my_head .= "<link rel=\"stylesheet\" type=\"text/css\"	href=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/category.news.css\" />\n";
    
    foreach ( $cat as $item )
    {
        $xtpl->assign( 'CAT', $item );
        if ( ! empty( $item['sub'] ) )
        {
            foreach ( $item['sub'] as $sub )
            {
                $xtpl->assign( 'SUB', $sub );
                $xtpl->parse( 'main.item.sub.loop' );
            }
            $xtpl->parse( 'main.item.sub' );
        }
        $xtpl->parse( 'main.item' );
    }
    $xtpl->parse( 'main' );
    $content = $xtpl->text( 'main' );
}

?>