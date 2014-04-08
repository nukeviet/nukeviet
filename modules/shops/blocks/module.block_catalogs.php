<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if( ! function_exists( 'nv_pro_catalogs' ) )
{
	/**
	 * nv_pro_catalogs()
	 * 
	 * @return
	 */
	function nv_pro_catalogs()
	{
		global $lang_module, $module_info, $module_file, $global_array_cat;

		$xtpl = new XTemplate( "block.catalogs.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'THEME_TEM', NV_BASE_SITEURL . "themes/" . $module_info['template'] );

		$cut_num = 24;
		$html = "";

		foreach( $global_array_cat as $cat )
		{
			if( $cat['parentid'] == 0 )
			{
				if( $cat['inhome'] == '1' )
				{
					$html .= "<li>\n";
					$html .= "<a title=\"" . $cat['title'] . "\" href=\"" . $cat['link'] . "\">" . nv_clean60( $cat['title'], $cut_num ) . "</a>\n";
					if( ! empty( $cat['subcatid'] ) ) $html .= html_viewsub( $cat['subcatid'] );
					$html .= "</li>\n";
				}
			}
		}

		$xtpl->assign( 'CONTENT', $html );

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	/**
	 * html_viewsub()
	 * 
	 * @param mixed $list_sub
	 * @return
	 */
	function html_viewsub( $list_sub )
	{
		global $global_array_cat, $cut_num;

		if( empty( $list_sub ) ) return "";
		else
		{
			$html = "<ul>\n";
			$list = explode( ",", $list_sub );

			foreach( $list as $catid )
			{
				if( $global_array_cat[$catid]['inhome'] == '1' )
				{
					$html .= "<li>\n";
					$html .= "<a title=\"" . $global_array_cat[$catid]['title'] . "\" href=\"" . $global_array_cat[$catid]['link'] . "\">" . nv_clean60( $global_array_cat[$catid]['title'], $cut_num ) . "</a>\n";
					if( ! empty( $global_array_cat[$catid]['subcatid'] ) ) $html .= html_viewsub( $global_array_cat[$catid]['subcatid'] );
					$html .= "</li>\n";
				}
			}

			$html .= "</ul>\n";

			return $html;
		}
	}
}

$content = nv_pro_catalogs();