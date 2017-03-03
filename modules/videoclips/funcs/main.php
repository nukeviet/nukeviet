<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if ( ! defined( 'NV_IS_MOD_VIDEOCLIPS' ) ) die( 'Stop!!!' );

$_otherTopic = array( 'main' => array(), 'sub' => array() );
if ( ! empty( $topicList ) )
{
	foreach ( $topicList as $__k => $__v )
	{
		if ( $__v['parentid'] == '0' ) $_otherTopic['main'][] = $topicList[$__k];
	}
}

$pgnum = 0;
if ( isset( $array_op[0] ) and ! empty( $array_op[0] ) )
{
	unset( $matches );
	if ( preg_match( "/^page\-(\d+)$/", $array_op[0], $matches ) ) $pgnum = ( int )$matches[1];
	else
	{
		$_tempUrl = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
		$_tempUrl = nv_url_rewrite( $_tempUrl, 1 );
		header( 'Location: ' . $_tempUrl, true, 301 );
		exit;
	}
}

$base_url = array();
$base_url['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$base_url['amp'] = "/page-";

$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.view FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` a, 
    `" . NV_PREFIXLANG . "_" . $module_data . "_hit` b 
    WHERE a.id=b.cid 
    AND a.status=1 
    ORDER BY a.id DESC 
    LIMIT " . $pgnum . "," . $configMods['otherClipsNum'];

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULECONFIG', $configMods );

if ( ! empty( $_otherTopic['main'] ) )
{
	$xtpl->assign( 'OTHETP', $lang_module['topic'] );
	foreach ( $_otherTopic['main'] as $_ottp )
	{
		$href = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $_ottp['alias'];
		$xtpl->assign( 'OTHERTOPIC', array(
			'href' => $href,
			'title' => $_ottp['title'],
			'img' => $_ottp['img'] ) );
		if ( ! empty( $_ottp['img'] ) )
		{
			$xtpl->parse( 'main.topicList.row1.img1' );
		}
		if ( ! empty( $_ottp['subcats'] ) )
		{
			$xtpl->parse( 'main.topicList.row1.iss1' );
		}
		$xtpl->parse( 'main.topicList.row1' );
	}
	$xtpl->parse( 'main.topicList' );
}

$result = $db->sql_query( $sql );
$res = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $res );
$all_page = intval( $all_page );
if ( $all_page )
{
	$i = 1;
	while ( $row = $db->sql_fetch_assoc( $result ) )
	{
		if ( ! empty( $row['img'] ) )
		{
			$imageinfo = nv_ImageInfo( NV_ROOTDIR . '/' . $row['img'], 120, true, NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name );
			$row['img'] = $imageinfo['src'];
		}
		else
		{
			$row['img'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/video.png";
		}
		$row['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $row['alias'];
		$row['sortTitle'] = nv_clean60( $row['title'], 20 );
		$xtpl->assign( 'OTHERCLIPSCONTENT', $row );
		if ( $i == 4 )
		{
			$i = 0;
			$xtpl->parse( 'main.otherClips.otherClipsContent.clearfix' );
		}
		$xtpl->parse( 'main.otherClips.otherClipsContent' );
		++$i;
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $configMods['otherClipsNum'], $pgnum );
	if ( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.otherClips.nv_generate_page' );
	}

	$xtpl->parse( 'main.otherClips' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( "main" );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>