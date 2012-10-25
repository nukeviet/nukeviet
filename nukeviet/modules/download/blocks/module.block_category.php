<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

global $module_name, $lang_module, $module_data, $list_cats, $module_file;

$download_config = nv_mod_down_config();
$list_cats = nv_list_cats();

$xtpl = new XTemplate( "block_category.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

if ( $download_config['is_addfile_allow'] )
{
    $xtpl->assign( 'UPLOAD', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=upload" );
    $xtpl->parse( 'main.is_addfile_allow' );
}

#parse cat parent and sub cat
foreach ( $list_cats as $cat )
{
    if ( empty( $cat['parentid'] ) )
    {
        $cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat['alias'];
        $xtpl->assign( 'catparent', $cat );
        $xtpl->assign( 'catbox', $cat );
		
        if ( ! empty( $cat['subcats'] ) )
        {
            foreach ( $list_cats as $subcat )
            {
                if ( $subcat['parentid'] == $cat['id'] )
                {
                    $subcat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $subcat['alias'];
                    $xtpl->assign( 'listsubcat', $subcat );
                    $xtpl->assign( 'loopsubcatparent', $subcat );
                    $xtpl->parse( 'main.catparent.subcatparent.loopsubcatparent' );
                    $xtpl->parse( 'main.catbox.subcatbox.listsubcat' );
                }
            }
            $xtpl->parse( 'main.catbox.subcatbox' );
            $xtpl->parse( 'main.catparent.subcatparent' );
        }
		
        if ( ! empty( $post_cats[$cat['id']] ) )
        {
            $xtpl->assign( 'itemcat', $post_cats[$cat['id']] );
            $xtpl->parse( 'main.catbox.itemcat' );
        }
		
        $xtpl->parse( 'main.catbox' );
        $xtpl->parse( 'main.catparent' );
    }
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>