<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_WEBLINKS' ) ) die( 'Stop!!!' );
$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$atomlink = NV_MY_DOMAIN . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss";
if ( ! empty( $catid ) )
{
    $sql = "SELECT id, catid, add_time, title, alias, description, urlimg FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE catid='" . $catid . "' AND status='1' ORDER BY id ASC LIMIT 30";
}
else
{
    $sql = "SELECT id, catid, add_time, title, alias, description, urlimg FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE status='1' ORDER BY id ASC LIMIT 30";
}
list( $cattitle, $catalias ) = $db->sql_fetchrow( $db->sql_query( "SELECT title, alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE catid='" . $catid . "'" ) );
$result = $db->sql_query( $sql );
$content = '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">

<channel>
<title>' . $global_config['site_name'] . ' RSS: ' . $module_name . ' ' . $cattitle . '</title>
<link>' . NV_MY_DOMAIN . NV_BASE_SITEURL . '</link>
<atom:link href="' . $atomlink . '" rel="self" type="application/rss+xml" />
<description>' . $global_config['site_description'] . '</description>
<language>' . $global_config['site_lang'] . '</language>
<copyright>' . $global_config['site_name'] . '</copyright>
<docs>' . NV_MY_DOMAIN . NV_BASE_SITEURL . '?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=rss</docs>
<generator>Nukeviet Version ' . $global_config['version'] . '</generator>

<image>
<url>' . NV_BASE_SITEURL . 'images/' . $global_config['site_logo'] . '</url>
<title>' . $module_name . '</title>
<link>' . NV_MY_DOMAIN . NV_BASE_SITEURL . '</link>
</image>';

while ( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgfile ) = $db->sql_fetchrow( $result ) )
{
    $rsslink = NV_MY_DOMAIN . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $catalias . '/' . $alias . '-' . $id;
    $rimages = ( ! empty( $homeimgfile ) ) ? "<img src=\"" . NV_BASE_SITEURL . NV_UPLOADS_DIR . "/$homeimgfile\" width=\"100\" align=\"left\" border=\"0\">" : "";
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

header( "Content-Type: text/xml" );
header( "Content-Type: application/rss+xml" );
header( "Content-Encoding: none" );
echo nv_url_rewrite( $content );
die();
?>
