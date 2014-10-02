<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if ( ! defined( 'NV_IS_MOD_LAWS' ) ) die( 'Stop!!!' );

$channel = array();
$items = array();

$channel['title'] = $global_config['site_name'] . ' RSS: ' . $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$channel['atomlink'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss";
$channel['description'] = $global_config['site_description'];

if ( ! empty( $nv_laws_listcat ) )
{
    $catalias = isset( $array_op[1] ) ? $array_op[1] : "";
    $cid = 0;
    
    if ( ! empty( $catalias ) )
    {
        foreach ( $nv_laws_listcat as $c )
        {
            if ( $c['alias'] == $catalias )
            {
                $cid = $c['id'];
                break;
            }
        }
    }
    
    if ( $cid > 0 )
    {
        $channel['title'] = $global_config['site_name'] . ' RSS: ' . $module_info['custom_title'] . ' - ' . $nv_laws_listcat[$cid]['title'];
        $channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;cat=" . $nv_laws_listcat[$cid]['alias'];
        $channel['description'] = $nv_laws_listcat[$cid]['introduction'];
        
        $sql = "SELECT id, title, alias, introtext, addtime
        FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE cid=" . $cid . " 
        AND status=1 ORDER BY edittime DESC LIMIT 30";
    }
    else
    {
        $in = array_keys( $nv_laws_listcat );
        $in = implode( ",", $in );
        $sql = "SELECT id, title, alias, introtext, addtime
        FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE cid IN (" . $in . ") 
        AND status=1 ORDER BY edittime DESC LIMIT 30";
    }
    if ( $module_info['rss'] )
    {
        if ( ( $result = $db->query( $sql ) ) !== false )
        {
            while ( list( $id, $title, $alias, $introtext, $addtime ) = $result->fetch( 3 ) )
            {
                $items[] = array(  //
                    'title' => $title, //
					'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=detail/" . $alias, //
					'guid' => $module_name . '_' . $id, //
					'description' => $introtext, //
					'pubdate' => $addtime  //
                );
            }
        }
    }
}

nv_rss_generate( $channel, $items );
die();