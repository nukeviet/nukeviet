<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
global $global_config, $module_name, $module_data, $global_array_cat;
$blocknewsid = 1;
$sql = "SELECT t1.id, t1.listcatid, t1.publtime, t1.exptime, t1.title, t1.alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $blocknewsid . " AND t1.inhome='1' and  t1.publtime < " . NV_CURRENTTIME . " AND (t1.exptime=0 OR t1.exptime >" . NV_CURRENTTIME . ") ORDER BY t2.weight ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );
if ( $num )
{
    $hot_news = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $row['listcatid'] = explode( ',', $row['listcatid'] );
        $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$row['listcatid'][0]]['alias'] . "/" . $row['alias'] . "-" . intval( $row['id'] );
        $hot_news[] = array( 
            'title' => $row['title'], 'link' => $link 
        );
    }
}
unset( $sql );
$sql = "SELECT `id`,`listcatid`,`title`,`alias`,`homeimgfile`,`homeimgalt` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE inhome='1' AND  publtime < " . NV_CURRENTTIME . " AND (exptime=0 OR exptime >" . NV_CURRENTTIME . ") ORDER BY id DESC LIMIT 4";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );
if ( $num )
{
    $lastest_news = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        if ( ! empty( $row['homeimgfile'] ) )
        {
            $row['image']['url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['homeimgfile'];
            $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['homeimgfile'] );
            $row['image']['width'] = $size[0];
            $row['image']['height'] = $size[1];
            $row['image']['alt'] = ! empty( $row['homeimgalt'] ) ? $row['homeimgalt'] : $row['title'];
        }
        $row['listcatid'] = explode( ',', $row['listcatid'] );
        $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$row['listcatid'][0]]['alias'] . "/" . $row['alias'] . "-" . intval( $row['id'] );
        $lastest_news[] = array( 
            'title' => $row['title'], 'link' => $link, 'image' => $row['image'] 
        );
    }
}
$content = nv_headline( $hot_news, $lastest_news );
?>