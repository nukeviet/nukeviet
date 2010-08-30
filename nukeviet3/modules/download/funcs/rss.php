<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$content = "";

$list_cats = nv_list_cats();
if ( ! empty( $list_cats ) )
{
    $atomlink = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss";
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
        $sql = "SELECT `id`, `catid`, `uploadtime`, `title`, `alias`, `introtext`, `fileimage` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid`=" . $catid . " AND `status`=1 ORDER BY `uploadtime` DESC LIMIT 30";
        $title = $global_config['site_name'] . ' RSS: ' . $module_name . ' - ' . $list_cats[$catid]['title'];
        $description = $list_cats[$catid]['description'];
        $channel_link = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;cat=" . $list_cats[$catid]['alias'];
    }
    else
    {
        $in = array_keys( $list_cats );
        $in = implode( ",", $in );
        $sql = "SELECT `id`, `catid`, `uploadtime`, `title`, `alias`, `introtext`, `fileimage` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid` IN (" . $in . ") AND `status`=1 ORDER BY `uploadtime` DESC LIMIT 30";
        $title = $global_config['site_name'] . ' RSS: ' . $module_name;
        $description = $global_config['site_description'];
        $channel_link = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
    }
    $content .= '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title>' . $title . '</title>
<link>' . $channel_link . '</link>
<atom:link href="' . $atomlink . '" rel="self" type="application/rss+xml" />
<description>' . $description . '</description>
<language>' . $global_config['site_lang'] . '</language>
<copyright>' . $global_config['site_name'] . '</copyright>
<docs>' . NV_MY_DOMAIN . NV_BASE_SITEURL . '?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=rss</docs>
<generator>Nukeviet Version ' . $global_config['version'] . '</generator>

<image>
<url>' . NV_BASE_SITEURL . 'images/logo.png</url>
<title>' . $module_name . '</title>
<link>' . NV_MY_DOMAIN . NV_BASE_SITEURL . '</link>
</image>';
    
    $result = $db->sql_query( $sql );
    while ( list( $id, $cid, $publtime, $title, $alias, $hometext, $homeimgfile ) = $db->sql_fetchrow( $result ) )
    {
        $rsslink = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$cid]['alias'] . "/" . $alias;
        $rimages = ( ! empty( $homeimgfile ) ) ? "<img src=\"" . $homeimgfile . "\" width=\"100\" align=\"left\" border=\"0\">" : "";
        $content .= '
	<item>
	<title>' . $title . '</title>
	<link>' . $rsslink . '</link>
	<guid isPermaLink="false">' . $id . '</guid>
	<description>' . htmlspecialchars( $rimages . $hometext, ENT_QUOTES ) . '</description>
	<pubDate>' . gmdate( "D, j M Y H:m:s", $publtime ) . ' GMT</pubDate>
	</item>';
    }
    $content .= '
</channel>
</rss>';
}

header( "Content-Type: text/xml" );
header( "Content-Type: application/rss+xml" );
header( "Content-Encoding: none" );
echo nv_url_rewrite( $content );
die();

?>