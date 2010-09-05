<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
global $global_config, $module_name, $module_data, $global_array_cat;

$array_bid_content = array();

$cache_file = NV_LANG_DATA . "_" . $module_name . "_block_headline_" . NV_CACHE_PREFIX . ".cache";
if ( ( $cache = nv_get_cache( $cache_file ) ) != false )
{
    $array_bid_content = unserialize( $cache );
}
else
{
	$id = 0;
    $sql = "SELECT bid, title, number FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC LIMIT 0 , 2";
    $result = $db->sql_query( $sql );
    while ( list( $bid, $titlebid, $numberbid ) = $db->sql_fetchrow( $result ) )
    {
        $id ++;
        $array_bid_content[$id] = array( 
            "id" => $id, "bid" => $bid, "title" => $titlebid, "number" => $numberbid 
        );
    }
    
    foreach ( $array_bid_content as $i => $array_bid )
    {
        $sql = "SELECT t1.id, t1.listcatid, t1.title, t1.alias, t1.homeimgfile, t1.homeimgalt FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $array_bid['bid'] . " AND t1.status= 1 AND t1.inhome='1' and  t1.publtime < " . NV_CURRENTTIME . " AND (t1.exptime=0 OR t1.exptime >" . NV_CURRENTTIME . ") ORDER BY t2.weight ASC LIMIT 0 , " . $array_bid['number'];
        $result = $db->sql_query( $sql );
        $array_content = array();
        while ( list( $id, $listcatid, $title, $alias, $homeimgfile, $homeimgalt ) = $db->sql_fetchrow( $result ) )
        {
            $arr_catid = explode( ',', $listcatid );
            $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$arr_catid[0]]['alias'] . "/" . $alias . "-" . $id;
            $array_content[] = array( 
                'title' => $title, 'link' => $link, 'homeimgfile' => $homeimgfile, 'homeimgalt' => $homeimgalt 
            );
        }
        $array_bid_content[$i]['content'] = $array_content;
    }
    $cache = serialize( $array_bid_content );
    nv_set_cache( $cache_file, $cache );
}

$content = nv_headline( $array_bid_content );
?>