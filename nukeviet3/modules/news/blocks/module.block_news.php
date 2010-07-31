<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$blocknewsid = 2;

global $global_config, $module_name, $module_data, $global_array_cat;
$array_block_news = array();
$sql = "SELECT id, listcatid, publtime, exptime, title, alias, homeimgthumb FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 0 , 20";
$result = $db->sql_query( $sql );
while ( list( $id, $listcatid, $publtime, $exptime, $title, $alias, $homeimgthumb ) = $db->sql_fetchrow( $result ) )
{
    $catid = end( explode( ",", $listcatid ) );
    $imgurl = "";
    $imagesizex = $imagesizey = 0;
    $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'] . "/" . $alias . "-" . $id;
    if ( $homeimgthumb != "" )
    {
        $arr_homeimgthumb = explode( "|", $homeimgthumb );
        if ( isset( $arr_homeimgthumb[1] ) and ! empty( $arr_homeimgthumb[1] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $arr_homeimgthumb[1] ) )
        {
            $imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $arr_homeimgthumb[1];
            $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $arr_homeimgthumb[1] );
            $imagesizex = $size[0];
            $imagesizey = $size[1];
        }
    }
    $array_block_news[] = array( 
        'id' => $id, 'listcatid' => $listcatid, 'title' => $title, 'link' => $link, 'imgurl' => $imgurl, 'width' => $imagesizex, 'height' => $imagesizey 
    );

}
$content = block_news( $array_block_news );
?>