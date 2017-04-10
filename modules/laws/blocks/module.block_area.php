<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 06, 2011, 06:31:13 AM
 */

if ( ! defined( 'NV_IS_MOD_LAWS' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_law_block_area' ) )
{
    function nv_law_block_area ()
    {
        global $lang_module, $module_info, $module_file, $global_config, $nv_laws_listarea, $module_name;

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/block_area.tpl' ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		else
		{
			$block_theme = 'default';
		}

        $xtpl = new XTemplate( "block_area.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_info['module_theme'] );
        $xtpl->assign( 'LANG', $lang_module );
        $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
        $xtpl->assign( 'TEMPLATE', $module_info['template'] );
        $xtpl->assign( 'MODULE_FILE', $module_file );

        $title_length = 24;

        $html = "";
		$i = 1;
        foreach ( $nv_laws_listarea as $cat )
        {
            if ( $cat['parentid'] == 0 )
            {
				$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=area/" . $cat['alias'];
				$html .= "<li>\n";
				$html .= "<a title=\"" . $cat['title'] . "\" href=\"" . $link . "\">" . nv_clean60( $cat['title'], $title_length ) . "</a>\n";
				if ( ! empty( $cat['subcats'] ) ) $html .= nv_content_subcat1( $cat['subcats'], $title_length );
				$html .= "</li>\n";

				if( $i >= 10 ) break;
				$i ++;
            }
        }
        $xtpl->assign( 'CONTENT', $html );
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }

    function nv_content_subcat1 ( $list_sub, $title_length )
    {
        global $nv_laws_listarea, $module_name;

        if ( empty( $list_sub ) ) return "";
        else
        {
            $html = "<ul>\n";
            foreach ( $list_sub as $catid )
            {
				$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=area/" . $nv_laws_listarea[$catid]['alias'];
				$html .= "<li>\n";
				$html .= "<a title=\"" . $nv_laws_listarea[$catid]['title'] . "\" href=\"" . $link . "\">" . nv_clean60( $nv_laws_listarea[$catid]['title'], $title_length ) . "</a>\n";
				if ( ! empty( $nv_laws_listarea[$catid]['subcats'] ) ) $html .= nv_content_subcat1( $nv_laws_listarea[$catid]['subcats'], $title_length );
				$html .= "</li>\n";
            }
            $html .= "</ul>\n";
            return $html;
        }
    }
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_law_block_area();
}