<?php

/**
 * @Project NUKEVIET 3.x 
 * @Author VINADES (contact@vinades.vn) 
 * @Copyright (C) 2012 VINADES. All rights reserved 
 * @Createdate Apr 20, 2010 10:47:41 AM 
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$channel = array();
$items = array();

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$channel['description'] = !empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];

$list_cats = nv_list_cats();

if ( ! empty( $list_cats ) )
{
    $catalias = isset( $array_op[1] ) ? $array_op[1] : "";
    $catid = 0;
    
    if ( ! empty( $catalias ) )
    {
        foreach ( $list_cats as $c )
        {
            if ( $c['alias'] == $catalias )
            {
                $catid = $c['id'];
                break;
            }
        }
    }
    
    if ( $catid > 0 )
    {
        $channel['title'] = $module_info['custom_title'] . ' - ' . $list_cats[$catid]['title'];
        $channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;cat=" . $list_cats[$catid]['alias'];
        $channel['description'] = $list_cats[$catid]['description'];
        
        $sql = "SELECT `id`, `catid`, `uploadtime`, `title`, `alias`, `introtext`, `fileimage` 
        FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid`=" . $catid . " 
        AND `status`=1 ORDER BY `uploadtime` DESC LIMIT 30";
    }
    else
    {
        $in = array_keys( $list_cats );
        $in = implode( ",", $in );
        $sql = "SELECT `id`, `catid`, `uploadtime`, `title`, `alias`, `introtext`, `fileimage` 
        FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid` IN (" . $in . ") 
        AND `status`=1 ORDER BY `uploadtime` DESC LIMIT 30";
    }
    if ( $module_info['rss'] )
    {
        if ( ( $result = $db->sql_query( $sql ) ) !== false )
        {
            while ( list( $id, $cid, $publtime, $title, $alias, $hometext, $homeimgfile ) = $db->sql_fetchrow( $result ) )
            {
                $rimages = ( ! empty( $homeimgfile ) ) ? "<img src=\"" . NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . $homeimgfile . "\" width=\"100\" align=\"left\" border=\"0\">" : "";
                $items[] = array(  //
                    'title' => $title, //
					'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$cid]['alias'] . "/" . $alias, //
					'guid' => $module_name . '_' . $id, //
					'description' => $rimages . $hometext, //
					'pubdate' => $publtime  //
                );
            }
        }
    }
}

nv_rss_generate( $channel, $items );
die();

?>