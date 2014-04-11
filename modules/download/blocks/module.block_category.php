<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

global $module_name, $lang_module, $module_data, $list_cats, $module_file;

$mainmenu = 20; // Do dai tieu de menu chinh
$submenu = 30; // Do dai tieu de menu con

$download_config = nv_mod_down_config();
$list_cats = nv_list_cats();

$xtpl = new XTemplate( 'block_category.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );

if( $download_config['is_addfile_allow'] )
{
	$xtpl->assign( 'UPLOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload' );
	$xtpl->parse( 'main.is_addfile_allow' );
}

#parse cat parent and sub cat
$in = 0;
foreach( $list_cats as $cat )
{
	if( empty( $cat['parentid'] ) )
	{
		++$in;
		$cat['in'] = $in == 1 ? 'in' : '';
		$cat['title_trim'] = nv_clean60( $cat['title'], $mainmenu );
		$cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat['alias'];
		$xtpl->assign( 'catparent', $cat );
		$xtpl->assign( 'catbox', $cat );

		if( ! empty( $cat['subcats'] ) )
		{
			foreach( $list_cats as $subcat )
			{
				if( $subcat['parentid'] == $cat['id'] )
				{
					$subcat['title_trim'] = nv_clean60( $subcat['title'], $submenu );
					$subcat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $subcat['alias'];
					$xtpl->assign( 'listsubcat', $subcat );
					$xtpl->assign( 'loopsubcatparent', $subcat );
					$xtpl->parse( 'main.catparent.subcatparent.loopsubcatparent' );
					$xtpl->parse( 'main.catbox.subcatbox.listsubcat' );
				}
			}
			$xtpl->parse( 'main.catbox.subcatbox' );
			$xtpl->parse( 'main.catparent.subcatparent' );
			$xtpl->parse( 'main.catparent.expand' );
		}

		if( ! empty( $post_cats[$cat['id']] ) )
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