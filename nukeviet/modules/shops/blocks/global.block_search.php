<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_search_product' ) )
{

    function nv_search_product ( $block_config )
    {	
        global $site_mods, $my_head, $db_config, $db, $global_array_group, $module_name, $module_info, $nv_Request,$catid,$module_config;
        
        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        $mod_file = $site_mods[$module]['module_file'];
        $array_group = $global_array_group;
        $pro_config = $module_config[$module];
        include ( NV_ROOTDIR . "/modules/" . $mod_file . "/language/".NV_LANG_DATA.".php" );
        
        $keyword = $nv_Request->get_string( 'keyword', 'get' );
        $price1_temp = $nv_Request->get_string( 'price1', 'get', '' );
		$price2_temp = $nv_Request->get_string( 'price2', 'get', '' );
        $typemoney = $nv_Request->get_string( 'typemoney', 'get','' );
        $sourceid = $nv_Request->get_int( 'sid', 'get',0 );
        $cataid = $nv_Request->get_int( 'cata', 'get', 0 );
        if ( $cataid == 0 ) $cataid = $catid;
        if ($price1_temp=='') $price1 = -1; else $price1 = floatval($price1_temp);
		if ($price2_temp=='') $price2 = -1; else $price2 = floatval($price2_temp); 
		      
        if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module . "/block.search.tpl" ) )
        {
            $block_theme = $module_info['template'];
        }
        else
        {
            $block_theme = "default";
        }
        if ( $module != $module_name )
        {
           $my_head .=  "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $mod_file . "/js/user.js\"></script>\n";
        }
        $xtpl = new XTemplate( "block.search.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module );
        $xtpl->assign( 'LANG', $lang_module );
        $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
        
    	$sql = "SELECT `catid`, `lev`, `" . NV_LANG_DATA . "_title` as title FROM `" . $db_config['prefix'] . "_" . $module . "_catalogs` ORDER BY `order` ASC";
        $result = $db->sql_query( $sql );
        while ( $row = $db->sql_fetchrow( $result,2 ) )
        {
            $xtitle_i = "";
            if ( $row['lev'] > 0 )
            {
                $xtitle_i .= "&nbsp;&nbsp;&nbsp;";
                for ( $i = 1; $i <= $row['lev']; $i ++ )
                {
                    $xtitle_i .= "&nbsp;&nbsp;&nbsp;";
                }
                $xtitle_i .= "&nbsp;";
            }
            $row['xtitle'] = $xtitle_i . $row['title'];
            $row['selected'] = ( $cataid == $row['catid'] ) ? "selected=\"selected\"" : "";
            $xtpl->assign( 'ROW', $row );
            $xtpl->parse( 'main.loopcata' );
        }
        //get money
     	$sql = "SELECT `code`,`currency` FROM `" . $db_config['prefix'] . "_" . $module . "_money_" . NV_LANG_DATA . "`";
        $result = $db->sql_query( $sql );
        while ( $row = $db->sql_fetchrow( $result,2 ) )
        {
            $row['selected'] = ( $typemoney == $row['code'] ) ? "selected=\"selected\"" : "";
            $xtpl->assign( 'ROW', $row );
            $xtpl->parse( 'main.typemoney' );
        }
    	$sql = "SELECT ".NV_LANG_DATA."_title as title,sourceid FROM `" . $db_config['prefix'] . "_" . $module . "_sources`";
        $result = $db->sql_query( $sql );
        while ( $row = $db->sql_fetchrow( $result,2 ) )
        {
            $row['selected'] = ( $row['sourceid'] == $sourceid ) ? "selected=\"selected\"" : "";
            $xtpl->assign( 'ROW', $row );
            $xtpl->parse( 'main.loopsource' );
        }
        if ($price1==-1) $price1 =""; if ($price2==-1) $price2 ="";
        $xtpl->assign( 'value_keyword', $keyword );
        $xtpl->assign( 'value_price1', $price1 );
        $xtpl->assign( 'value_price2', $price2 );
        if ( $pro_config['active_price']) $xtpl->parse( 'main.price' );
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}
if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods, $global_array_group, $module_name;
    $content = nv_search_product( $block_config );
}

?>