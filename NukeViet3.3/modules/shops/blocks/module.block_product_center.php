<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if ( ! function_exists( 'nv_product_center' ) )
{
    function nv_product_center ()
    {
    	global $pro_config, $global_config, $module_name, $lang_module, $module_info, $module_file, $global_array_cat,$db,$module_data,$db_config;
    	$num_view = 6;
    	$num = 30;
	    $xtpl = new XTemplate( "block.product_center.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	    $xtpl->assign( 'LANG', $lang_module );
	    $xtpl->assign( 'THEME_TEM', NV_BASE_SITEURL . "themes/" . $module_info['template'] );

	    $sql = "SELECT bid, ".NV_LANG_DATA."_title FROM `" . $db_config ['prefix'] . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC LIMIT 1";
	    $result = $db->sql_query( $sql );
	    list( $bid, $titlebid ) = $db->sql_fetchrow( $result );
	    
	    $sql = "SELECT t1.id, t1.listcatid, t1.".NV_LANG_DATA."_title, t1.".NV_LANG_DATA."_alias, t1.homeimgthumb , t1.homeimgalt FROM `" . $db_config ['prefix'] . "_" . $module_data . "_rows` as t1 INNER JOIN `" . $db_config ['prefix'] . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $bid . " AND t1.status= 1 AND t1.inhome='1' and  t1.publtime < " . NV_CURRENTTIME . " AND (t1.exptime=0 OR t1.exptime >" . NV_CURRENTTIME . ") ORDER BY t1.id DESC LIMIT 0 , " . $num;
        $result = $db->sql_query( $sql );
        $array_content = array();
        $i = 1;
        $j = 1;
        $page_i = "";
        while ( list( $id, $listcatid, $title, $alias, $homeimgthumb, $homeimgalt ) = $db->sql_fetchrow( $result ) )
        {
            $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id;
	        $thumb = explode ( "|", $homeimgthumb );
			if (! empty ( $thumb [0] ) && ! nv_is_url ( $thumb [0] )) {
				$thumb [0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb [0];
			} else {
				$thumb [0] = NV_BASE_SITEURL . "themes/" . $module_info ['template'] . "/images/" . $module_file . "/no-image.jpg";
			}
            $xtpl->assign( 'LINK', $link );
            $xtpl->assign( 'TITLE', $title );
            $xtpl->assign( 'TITLE0', nv_clean60 ( $title, 30 ) );
            $xtpl->assign( 'SRC_IMG', $thumb [0] );
            $xtpl->parse( 'main.loop.items' );
            if ($i%$num_view == 0){ 
            	
            	$page_i .= "<li><a href=\"#\">".$j."</a></li>";
            	++$j;
            	$xtpl->parse( 'main.loop' );
            }
            ++$i;
        }
		if ($i> $num_view && ($i-1)%$num_view != 0 ) {
			$page_i .= "<li><a href=\"#\">".$j."</a></li>";
			$xtpl->parse( 'main.loop' );
		}
        $xtpl->assign( 'page', $page_i );
	    $xtpl->parse( 'main' );
	    return $xtpl->text( 'main' );
    }
}

$content = nv_product_center();
?>