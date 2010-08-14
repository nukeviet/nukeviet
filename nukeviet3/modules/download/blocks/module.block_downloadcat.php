<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

/**
 * getSubcategory()
 * 
 * @param mixed $subcats
 * @param mixed $list_cats
 * @param mixed $content
 * @return
 */
function getSubcategory( $subcats, $list_cats, &$content )
{
    global $module_name;
    if ( ! empty( $subcats ) )
    {
        $content .= "<ul>\n";
        foreach ( $subcats as $sub )
        {
            $content .= "<li>\n";
            $content .= "<a href=\"" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;cat=" . $list_cats[$sub]['alias'] . "\">" . $list_cats[$sub]['title'] . "</a>";
            getSubcategory( $list_cats[$sub]['subcats'], $list_cats, &$content );
            $content .= "</li>\n";
        }
        $content .= "</ul>\n";
    }
}

/**
 * block_list_cat()
 * 
 * @return
 */
function block_list_cat()
{
    global $module_name;

    $content = "";

    if ( $module_name == "download" )
    {
        $list_cats = nv_list_cats();

        if ( ! empty( $list_cats ) )
        {
            $content .= "<link rel=\"stylesheet\" href=\"" . NV_BASE_SITEURL . "themes/default/css/menu_news.css\" />\n";
            $content .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/menu_news.js\"></script>\n";
            $content .= "<div class=\"sidebarmenu\">\n";
            $content .= "<ul id=\"sidebarmenu1\">\n";

            foreach ( $list_cats as $cat )
            {
                if ( ! $cat['parentid'] )
                {
                    $content .= "<li>\n";
                    $content .= "<a href=\"" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;cat=" . $cat['alias'] . "\">" . $cat['title'] . "</a>\n";
                    getSubcategory( $cat['subcats'], $list_cats, &$content );
                    $content .= "</li>\n";
                }
            }
            $content .= "</ul>\n";
            $content .= "</div>\n";
        }
    }
    return $content;
}

$content = block_list_cat();

?>