<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 06, 2011, 06:31:13 AM
 */

if ( ! defined( 'NV_IS_MOD_LAWS' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_law_block_cat' ) )
{
    function nv_law_block_cat ()
    {
        global $lang_module, $module_info, $module_file, $nv_laws_listcat, $module_name;
		
        $xtpl = new XTemplate( "block_cat.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
        $xtpl->assign( 'LANG', $lang_module );
        $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
        $xtpl->assign( 'TEMPLATE', $module_info['template'] );
        $xtpl->assign( 'MODULE_FILE', $module_file );
		
        $title_length = 24;
		
        $html = "";
		
		$i = 1;
        foreach ( $nv_laws_listcat as $cat )
        {
        	if( $cat['id'] == 0 ) continue;
            if ( $cat['parentid'] == 0 )
            {
				$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat['alias'];
				$html .= "<li>\n";
				$html .= "<a title=\"" . $cat['title'] . "\" href=\"" . $link . "\">" . nv_clean60( $cat['title'], $title_length ) . "</a>\n";
				if ( ! empty( $cat['subcats'] ) ) $html .= nv_content_subcat( $cat['subcats'], $title_length );
				$html .= "</li>\n";
				
				if( $i >= 10 ) break;
				$i ++;
            }
        }
        $xtpl->assign( 'CONTENT', $html );
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }

    function nv_content_subcat ( $list_sub, $title_length )
    {
        global $nv_laws_listcat, $module_name;
		
        if ( empty( $list_sub ) ) return "";
        else
        {
            $html = "<ul>\n";
            foreach ( $list_sub as $catid )
            {
				$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $nv_laws_listcat[$catid]['alias'];
				$html .= "<li>\n";
				$html .= "<a title=\"" . $nv_laws_listcat[$catid]['title'] . "\" href=\"" . $link . "\">" . nv_clean60( $nv_laws_listcat[$catid]['title'], $title_length ) . "</a>\n";
				if ( ! empty( $nv_laws_listcat[$catid]['subcats'] ) ) $html .= nv_content_subcat( $nv_laws_listcat[$catid]['subcats'], $title_length );
				$html .= "</li>\n";
            }
            $html .= "</ul>\n";
            return $html;
        }
    }
}

$content = nv_law_block_cat();

?>