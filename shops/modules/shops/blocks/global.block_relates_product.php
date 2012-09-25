<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_relates_product' ) )
{

    function nv_block_config_relates_blocks ( $module, $data_block, $lang_block )
    {
        global $db, $language_array, $db_config;
        $html = "";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['blockid'] . "</td>";
        $html .= "	<td><select name=\"config_blockid\">\n";
        $sql = "SELECT `bid`,  " . NV_LANG_DATA . "_title," . NV_LANG_DATA . "_alias FROM `" . $db_config['prefix'] . "_" . $module . "_block_cat` ORDER BY `weight` ASC";
        $list = nv_db_cache( $sql, 'catid', $module );
        foreach ( $list as $l )
        {
            $sel = ( $data_block['blockid'] == $l['bid'] ) ? ' selected' : '';
            $html .= "<option value=\"" . $l['bid'] . "\" " . $sel . ">" . $l[NV_LANG_DATA . '_title'] . "</option>\n";
        }
        $html .= "	</select></td>\n";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['numrow'] . "</td>";
        $html .= "	<td><input type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['cut_num'] . "</td>";
        $html .= "	<td><input type=\"text\" name=\"config_cut_num\" size=\"5\" value=\"" . $data_block['cut_num'] . "\"/></td>";
        $html .= "</tr>";
        return $html;
    }

    function nv_block_config_relates_blocks_submit ( $module, $lang_block )
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['blockid'] = $nv_Request->get_int( 'config_blockid', 'post', 0 );
        $return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
        $return['config']['cut_num'] = $nv_Request->get_int( 'config_cut_num', 'post', 0 );
        return $return;
    }
    
    if ( ! function_exists( 'CurrencyConversion' ) )
    {

        function CurrencyConversion ( $price, $currency_curent, $currency_convert, $block_config )
        {
            global $money_config, $module_config;
            $module = $block_config['module'];
            $pro_config = $module_config[$module];
            $str = number_format( $price, 0, '.', ' ' );
            if ( ! empty( $money_config ) )
            {
                if ( $currency_curent == $pro_config['money_unit'] )
                {
                    $value = doubleval( $money_config[$currency_convert]['exchange'] );
                    $price = doubleval( $price * $value );
                    $str = number_format( $price, 0, '.', ' ' );
                    $ss = "~";
                }
                elseif ( $currency_convert == $pro_config['money_unit'] )
                {
                    $value = doubleval( $money_config[$currency_curent]['exchange'] );
                    $price = doubleval( $price / $value );
                    $str = number_format( $price, 0, '.', ' ' );
                }
            }
            $ss = ( $currency_curent == $currency_convert ) ? "" : "~";
            return $ss . $str;
        }
    }

    function nv_relates_product ( $block_config )
    {
        global $site_mods, $global_config, $module_config, $module_name, $module_info, $global_array_cat, $db, $db_config, $my_head;
        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        $mod_file = $site_mods[$module]['module_file'];
        $pro_config = $module_config[$module];
        $array_cat_shops = $global_array_cat;
        /*get theme block*/
        if ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $mod_file . "/block.others_product.tpl" ) )
        {
            $block_theme = $global_config['site_theme'];
        }
        else
        {
            $block_theme = "default";
        }
        /*get $array_cat module*/
        if ( $module != $module_name )
        {
            $sql_cat = "SELECT catid, parentid, lev," . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewcat, numsubcat, subcatid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, inhome, " . NV_LANG_DATA . "_keywords, who_view, groups_view FROM `" . $db_config['prefix'] . "_" . $mod_data . "_catalogs` ORDER BY `order` ASC";
            $result = $db->sql_query( $sql_cat );
            while ( list( $catid_i, $parentid_i, $lev_i, $title_i, $alias_i, $viewcat_i, $numsubcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $db->sql_fetchrow( $result ) )
            {
                $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i . "";
                $array_cat_shops[$catid_i] = array( 
                    "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "numsubcat" => $numsubcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i, 'lev' => $lev_i 
                );
            }
            if ( file_exists( NV_ROOTDIR . "/themes/" . $block_theme . "/css/" . $mod_file . ".css" ) )
            {
                $my_head .= '<link rel="StyleSheet" href="' . NV_BASE_SITEURL . 'themes/' . $block_theme . '/css/' . $mod_file . '.css' . '" type="text/css" />';
            }
        }
        /*show data*/
        $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=";
        
		$xtpl = new XTemplate( "block.others_product.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );
        $sql = "SELECT  t1.id, t1.listcatid, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1.addtime,t1.homeimgthumb,t1.product_price,t1.product_discounts,t1.money_unit,t1.showprice FROM `" . $db_config['prefix'] . "_" . $module . "_rows` as t1 INNER JOIN `" . $db_config['prefix'] . "_" . $module . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $block_config['blockid'] . " AND t1.status= 1 AND  t1.publtime < " . NV_CURRENTTIME . " AND (t1.exptime=0 OR t1.exptime >" . NV_CURRENTTIME . ") ORDER BY t1.addtime DESC, t2.weight ASC LIMIT 0 , " . $block_config['numrow'];
        $query = $db->sql_query( $sql );
        $i = 1;
        $cut_num = $block_config['cut_num'];
        ///////////////////////////////////////////////////////////
        while ( list( $id_i, $listcatid_i, $title_i, $alias_i, $addtime_i, $homeimgthumb_i, $product_price_i, $product_discounts_i, $money_unit_i, $showprice_i ) = $db->sql_fetchrow( $query ) )
        {
            $thumb = explode( "|", $homeimgthumb_i );
            if ( ! empty( $thumb[0] ) && ! nv_is_url( $thumb[0] ) )
            {
                $thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module . "/" . $thumb[0];
            }
            else
            {
                $thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $mod_file . "/no-image.jpg";
            }
            $xtpl->assign( 'link', $link . $array_cat_shops[$listcatid_i]['alias'] . "/" . $alias_i . "-" . $id_i );
            $title_i = nv_clean60( $title_i, $cut_num );
            $xtpl->assign( 'title', $title_i );
            $xtpl->assign( 'src_img', $thumb[0] );
            $xtpl->assign( 'time', nv_date( 'd-m-Y h:i:s A', $addtime_i ) );
            if ( $pro_config['active_price'] == '1' && $showprice_i == '1' )
            {
                
                $product_price = CurrencyConversion( $product_price_i, $money_unit_i, $pro_config['money_unit'], $block_config );
                $xtpl->assign( 'product_price', $product_price );
                $xtpl->assign( 'money_unit', $pro_config['money_unit'] );
                if ( $product_discounts_i != 0 )
                {
                    $price_product_discounts = $product_price_i - ( $product_price_i * ( $product_discounts_i / 100 ) );
                    $xtpl->assign( 'product_discounts', CurrencyConversion( $price_product_discounts, $money_unit_i, $pro_config['money_unit'], $block_config ) );
                    $xtpl->assign( 'class_money', 'discounts_money' );
                    $xtpl->parse( 'main.loop.discounts' );
                }
                else
                {
                    $xtpl->assign( 'class_money', 'money' );
                }
                $xtpl->parse( 'main.loop.price' );
            }
            $bg = ( $i % 2 == 0 ) ? "bg" : "";
            $xtpl->assign( "bg", $bg );
            $xtpl->parse( 'main.loop' );
            $i ++;
        }
        ///////////////////////////////////////////////////////////////////////////////////
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}

if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods;
    $module = $block_config['module'];
    $content = nv_relates_product( $block_config );
}

?>