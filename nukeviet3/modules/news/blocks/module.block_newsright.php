<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
global $module_data, $module_name, $module_file, $global_array_cat, $lang_module, $my_head;
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.tabs.min.js\"></script>\n";
$my_head .= "	<script type=\"text/javascript\">\n";
$my_head .= "	$(function() {\n";
$my_head .= "		$(\"#tabs\").tabs();\n";
$my_head .= "	});\n";
$my_head .= "	</script>\n";

$xtpl = new XTemplate( "block_newsright.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );

$xtpl->assign( 'BASESITE', NV_BASE_SITEURL );
$xtpl->assign( 'LANG', $lang_module );
$sql = "SELECT id, listcatid, publtime, exptime, title, alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `hitstotal` DESC LIMIT 0 , 5";
$result = $db->sql_query( $sql );
$chk_topview = $db->sql_numrows( $result );
if ( $chk_topview )
{
    $i = 1;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $catid = explode( ',', $row['listcatid'] );
        $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid[0]]['alias'] . "/" . $row['alias'] . "-" . $row['id'];
        $row['catname'] = $global_array_cat[$catid[0]]['title'];
        $xtpl->assign( 'topviews', $row );
        $xtpl->parse( 'main.topviews.loop' );
    }
    $xtpl->parse( 'main.topviews' );
}
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `status`= 1 ORDER BY `cid` DESC LIMIT 0 , 5";
$result = $db->sql_query( $sql );
$chk_cm = $db->sql_numrows( $result );
if ( $chk_cm )
{
    $i = 1;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        list( $catid, $alias ) = $db->sql_fetchrow( $db->sql_query( "SELECT listcatid, alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE id=" . $row['id'] . "" ) );
        $catid = explode( ',', $catid );
        $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid[0]]['alias'] . "/" . $alias . "-" . $row['id'];
        $row['catname'] = $global_array_cat[$catid[0]]['title'];
        $row['content'] = nv_clean60( $row['content'], 100 );
        $xtpl->assign( 'topcomment', $row );
        $xtpl->parse( 'main.topcomment.loop' );
    }
    $xtpl->parse( 'main.topcomment' );
}
$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
?>