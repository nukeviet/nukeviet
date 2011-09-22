<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */
if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );
define( 'NV_IS_MOD_SHOPS', true );
require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

global $global_array_cat,$global_array_group;
$global_array_cat = array();
$catid = 0;
$parentid = 0;
$set_viewcat = "";
$alias_cat_url = isset( $array_op[0] ) ? $array_op[0] : "";

//Xac dinh RSS
if ($module_info['rss'])
{
	$rss[] = array(//
		'title' => $module_info['custom_title'], //
		'src' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss"//
	);
}

$arr_cat_title = array();
$sql = "SELECT catid, parentid, lev," . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewcat, numsubcat, subcatid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, inhome, " . NV_LANG_DATA . "_keywords, who_view, groups_view FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
while ( list( $catid_i, $parentid_i, $lev_i, $title_i, $alias_i, $viewcat_i, $numsubcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $db->sql_fetchrow( $result ) )
{
    $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i . "";
    $global_array_cat[$catid_i] = array( 
        "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "numsubcat" => $numsubcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i, 'lev' => $lev_i 
    );
    if ( $alias_cat_url == $alias_i )
    {
        $catid = $catid_i;
        $parentid = $parentid_i;
    }
    //Xac dinh RSS
    if ( $module_info['rss'])
    {
        $rss[] = array( //
			'title' => $module_info['custom_title'] . ' - ' . $title_i, //
			'src' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . $alias_i//
		);
    }	
}

unset( $result, $alias_cat_url, $catid_i, $parentid_i, $title_i, $alias_i );
/*group*/
$global_array_group = array();
$sql = "SELECT groupid, parentid, lev," . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewgroup, numsubgroup, subgroupid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, inhome, " . NV_LANG_DATA . "_keywords, who_view, groups_view FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
while ( list( $groupid_i, $parentid_i, $lev_i, $title_i, $alias_i, $viewgroup_i, $numsubgroup_i, $subgroupid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $db->sql_fetchrow( $result ) )
{
    $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=group/" . $alias_i . "-" . $groupid_i;
    $global_array_group[$groupid_i] = array( 
        "group" => $groupid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewgroup" => $viewgroup_i, "numsubgroup" => $numsubgroup_i, "subgroupid" => $subgroupid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i, 'lev' => $lev_i 
    );
}
/*end group*/
$page = 0;
$per_page = $pro_config['per_page'];
$count_op = count( $array_op );
if ( ! empty( $array_op ) and $op == "main" )
{
    if ( $catid == 0 )
    {
        $contents = $lang_module['nocatpage'] . $array_op[0];
        if ( ! empty( $array_op[1] ) )
        {
            if ( substr( $array_op[1], 0, 5 ) == "page-" )
            {
                $page = intval( substr( $array_op[1], 5 ) );
            }
        }
    }
    else
    {
        $op = "main";
        if ( $count_op == 1 or substr( $array_op[1], 0, 5 ) == "page-" )
        {
            $op = "viewcat";
            if ( $count_op > 1 )
            {
                //$set_viewcat = "viewcat_page_new";
                $page = intval( substr( $array_op[1], 5 ) );
            }
        }
        elseif ( $count_op == 2 )
        {
            $array_page = explode( "-", $array_op[1] );
            $id = intval( end( $array_page ) );
            $number = strlen( $id ) + 1;
            $alias_url = substr( $array_op[1], 0, - $number );
            if ( $id > 0 and $alias_url != "" )
            {
                $op = "detail";
            }
        }
        $parentid = $catid;
        while ( $parentid > 0 )
        {
            $array_cat_i = $global_array_cat[$parentid];
            $array_mod_title[] = array( 
                'catid' => $parentid, 'title' => $array_cat_i['title'], 'link' => $array_cat_i['link'] 
            );
            $parentid = $array_cat_i['parentid'];
        }
        sort( $array_mod_title, SORT_NUMERIC );
    }
}

function GetCatidInParent ( $catid )
{
    global $global_array_cat, $array_cat;
    $array_cat[] = $catid;
    $subcatid = explode( ",", $global_array_cat[$catid]['subcatid'] );
    if ( ! empty( $subcatid ) )
    {
        foreach ( $subcatid as $id )
        {
            if ( $id > 0 )
            {
                if ( $global_array_cat[$id]['numsubcat'] == 0 )
                {
                    $array_cat[] = $id;
                }
                else
                {
                    $array_cat_temp = GetCatidInParent( $id );
                    foreach ( $array_cat_temp as $catid_i )
                    {
                        $array_cat[] = $catid_i;
                    }
                }
            }
        }
    }
    return array_unique( $array_cat );
}

function GetDataIn ( $result, $catid )
{
    global $global_array_cat, $module_name, $db, $link, $module_info;
    $data_content = array();
    $data = array();
    while ( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_price, $product_discounts, $money_unit,$showprice ) = $db->sql_fetchrow( $result ) )
    {
        $thumb = explode( "|", $homeimgthumb );
        if ( ! empty( $thumb[0] ) && ! nv_is_url( $thumb[0] ) )
        {
            $thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
        }
        else
        {
            $thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
        }
        $data[] = array( 
            "id" => $id, "publtime" => $publtime, "title" => $title, "alias" => $alias, "hometext" => $hometext, "address" => $address, "homeimgalt" => $homeimgalt, "homeimgthumb" => $thumb[0], "product_price" => $product_price, "product_discounts" => $product_discounts, "money_unit" => $money_unit,"showprice" => $showprice, "link_pro" => $link . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id, "link_order" => $link . "setcart&amp;id=" . $id 
        );
    }
    $data_content['id'] = $catid;
    $data_content['title'] = $global_array_cat[$catid]['title'];
    $data_content['data'] = $data;
    $data_content['alias'] = $global_array_cat[$catid]['alias'];
    return $data_content;
}
function GetDataInGroup ( $result, $groupid )
{
    global $global_array_group, $module_name, $db, $link, $module_info,$global_array_cat;
    $data_content = array();
    $data = array();
    while ( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_price, $product_discounts, $money_unit,$showprice ) = $db->sql_fetchrow( $result ) )
    {
        $thumb = explode( "|", $homeimgthumb );
        if ( ! empty( $thumb[0] ) && ! nv_is_url( $thumb[0] ) )
        {
            $thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
        }
        else
        {
            $thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
        }
        $data[] = array( 
            "id" => $id, "publtime" => $publtime, "title" => $title, "alias" => $alias, "hometext" => $hometext, "address" => $address, "homeimgalt" => $homeimgalt, "homeimgthumb" => $thumb[0], "product_price" => $product_price, "product_discounts" => $product_discounts, "money_unit" => $money_unit,"showprice" => $showprice, "link_pro" => $link . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id, "link_order" => $link . "setcart&amp;id=" . $id 
        );
    }
    $data_content['id'] = $groupid;
    $data_content['title'] = $global_array_group[$groupid]['title'];
    $data_content['data'] = $data;
    $data_content['alias'] = $global_array_group[$groupid]['alias'];
    return $data_content;
}
function FormatNumber ( $number, $decimals = 0, $thousand_separator = '&nbsp;', $decimal_point = '.' )
{
    $str = number_format( $number, 0, ',', '.' );
    return $str;
}

//eg : echo CurrencyConversion ( 100000, 'USD', 'VND' );
/*return string money eg: 100 000 000*/
function CurrencyConversion ( $price, $currency_curent, $currency_convert )
{
    global $money_config, $pro_config; //die($price." ");
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

//eg : echo CurrencyConversion ( 100000, 'USD', 'VND' );
/*return double money eg: 100000000 */
function CurrencyConversionToNumber ( $price, $currency_curent, $currency_convert )
{
    global $money_config, $pro_config;
    if ( ! empty( $money_config ) )
    {
        if ( $currency_curent == $pro_config['money_unit'] )
        {
            $value = doubleval( $money_config[$currency_convert]['exchange'] );
            $price = doubleval( $price * $value );
        }
        elseif ( $currency_convert == $pro_config['money_unit'] )
        {
            $value = doubleval( $money_config[$currency_curent]['exchange'] );
            $price = doubleval( $price / $value );
        }
    }
    return $price;
}

/*set view old product*/
function SetSessionProView ( $id, $title, $alias, $addtime, $link,$homeimgthumb )
{
	global $module_data;
    if ( ! isset( $_SESSION[$module_data . '_proview'] ) ) $_SESSION[$module_data . '_proview'] = array();
    if ( ! isset( $_SESSION[$module_data . '_proview'][$id] ) )
    {
        $_SESSION[$module_data . '_proview'][$id] = array( 
            'title' => $title, 'alias' => $alias, 'addtime' => $addtime, 'link' => $link, 'homeimgthumb' => $homeimgthumb
        );
    }
}

function redict_link ( $lang_view, $lang_back, $nv_redirect )
{
    global $lang_module;
    $contents = "<div class=\"frame\">";
    $contents .= $lang_view . "<br /><br />\n";
    $contents .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
    $contents .= "<a href=\"" . $nv_redirect . "\">" . $lang_back . "</a>";
    $contents .= "</div>";
    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

?>