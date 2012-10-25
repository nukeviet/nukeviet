<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

global $db, $module_name, $module_data, $module_info, $module_file, $lang_module, $list_cats;

$path = NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file ;
if ( ! file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file  . '/block_topdownload.tpl' ) )
{
    $path = NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file ;
}

$xtpl = new XTemplate( "block_topdownload.tpl", $path);
$xtpl->assign( 'LANG', $lang_module );
$query = "SELECT catid, title, alias, download_hits FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `status`=1 ORDER BY download_hits DESC LIMIT 5";
$result = $db->sql_query( $query );
$i = 1;
while ( $row = $db->sql_fetchrow( $result ) )
{
    $catalias = $list_cats[$row['catid']]['alias'];
    $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $catalias . '/' . $row['alias'];
    $row['order'] = $i;
    $xtpl->assign( 'loop', $row );
    $xtpl->parse( 'main.loop' );
    ++$i;
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>