<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
global $module_data, $module_name, $module_file, $global_array_cat, $global_config, $lang_module;
$xtpl = new XTemplate( "block_newscenter.tpl", NV_ROOTDIR . "/themes/" . $module_info ['template'] . "/modules/" . $module_file );
$xtpl->assign( 'lang', $lang_module );
$sql = "SELECT id, listcatid, publtime, exptime, title, alias, hometext, homeimgthumb, homeimgfile FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 0 , 4";
$result = $db->sql_query( $sql );
$i = 1;
while ( $row = $db->sql_fetchrow( $result ) )
{
    
    if ( ! empty( $row ['homeimgthumb'] ) )
    {
        $array_img = explode( "|", $row ['homeimgthumb'] );
    }
    else
    {
        $array_img = array( 
            "", "" 
        );
    }
    if ( $array_img [0] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img [0] ) )
    {
        $row ['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img [0];
    }
    elseif ( nv_is_url( $row ['homeimgfile'] ) )
    {
        $row ['imgsource'] = $row ['homeimgfile'];
    }
    elseif ( $row ['homeimgthumb'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row ['homeimgthumb'] ) )
    {
        $row ['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row ['homeimgthumb'];
    }
    else
    {
        $row ['imgsource'] = NV_BASE_SITEURL . 'themes/' . $global_config ['site_theme'] . '/images/no_image.gif';
    }
    
    $catid = explode( ',', $row ['listcatid'] );
    $row ['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat [$catid [0]] ['alias'] . "/" . $row ['alias'] . "-" . $row ['id'];
    $row ['hometext'] = nv_clean60( strip_tags( $row ['hometext'] ), 360 );
    if ( $i == 1 )
    {
        $xtpl->assign( 'main', $row );
        $i ++;
    }
    else
    {
        $xtpl->assign( 'othernews', $row );
        $xtpl->parse( 'main.othernews' );
    }
}
$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
?>