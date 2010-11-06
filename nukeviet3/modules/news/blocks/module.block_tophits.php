<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$number_day = 100; 
$publtime = NV_CURRENTTIME - $number_day * 86400;
global $global_config, $module_name, $module_data, $module_file, $global_array_cat, $module_config, $module_info;

$blockwidth = $module_config [$module_name] ['blockwidth'];

$array_block_news = array();
$sql = "SELECT id, listcatid, publtime, exptime, title, alias, homeimgthumb, homeimgfile FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `publtime` BETWEEN  " . $publtime . " AND " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `hitstotal` DESC LIMIT 0 , 20";
$result = $db->sql_query( $sql );
while ( list( $id, $listcatid, $publtime, $exptime, $title, $alias, $homeimgthumb, $homeimgfile ) = $db->sql_fetchrow( $result ) )
{
    if ( ! empty( $homeimgthumb ) )
    {
        $array_img = explode( "|", $homeimgthumb );
    }
    else
    {
        $array_img = array( 
            "", "" 
        );
    }
    if ( $array_img [0] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img [0] ) )
    {
        $imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img [0];
    }
    elseif ( nv_is_url( $homeimgfile ) )
    {
        $imgurl = $homeimgfile;
    }
    elseif ( $homeimgfile != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $homeimgfile ) )
    {
        $imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
    }
    else
    {
        $imgurl = "";
    }
    $catid = end( explode( ",", $listcatid ) );
    
    $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat [$catid] ['alias'] . "/" . $alias . "-" . $id;
    
    $array_block_news [] = array( 
        'id' => $id, 'listcatid' => $listcatid, 'title' => $title, 'link' => $link, 'imgurl' => $imgurl, 'width' => $blockwidth 
    );

}

$xtpl = new XTemplate( "block_news.tpl", NV_ROOTDIR . "/themes/" . $module_info ['template'] . "/modules/" . $module_file );
$a = 1;
foreach ( $array_block_news as $array_news )
{
    $xtpl->assign( 'blocknews', $array_news );
    if ( ! empty( $array_news ['imgurl'] ) )
    {
        $xtpl->parse( 'main.newloop.imgblock' );
    }
    $xtpl->parse( 'main.newloop' );
    $xtpl->assign( 'BACKGROUND', ( $a % 2 ) ? 'bg ' : '' );
    $a ++;
}
$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>