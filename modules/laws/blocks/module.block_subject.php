<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 06, 2011, 06:31:13 AM
 */

if ( ! defined( 'NV_IS_MOD_LAWS' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_law_block_subject' ) )
{
    function nv_law_block_subject ()
    {
        global $lang_module, $module_info, $module_file, $nv_laws_listsubject, $module_name;
		
        $xtpl = new XTemplate( "block_subject.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
        $xtpl->assign( 'LANG', $lang_module );
        $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
        $xtpl->assign( 'TEMPLATE', $module_info['template'] );
        $xtpl->assign( 'MODULE_FILE', $module_file );
		
        $title_length = 24;
		
        $html = "";
		$i = 1;
        foreach ( $nv_laws_listsubject as $cat )
        {
			$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=subject/" . $cat['alias'];
			$html .= "<li>\n";
			$html .= "<a title=\"" . $cat['title'] . "\" href=\"" . $link . "\">" . nv_clean60( $cat['title'], $title_length ) . "</a>\n";
			$html .= "</li>\n";
				
				if( $i >= 10 ) break;
				$i ++;
        }
		
        $xtpl->assign( 'CONTENT', $html );
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}

$content = nv_law_block_subject();

?>