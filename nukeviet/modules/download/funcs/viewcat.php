<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$contents = "";
if( empty( $list_cats ) )
{
	$page_title = $module_info['custom_title'];
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( '' );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

$download_config = nv_mod_down_config();

$today = mktime( 0, 0, 0, date( "n" ), date( "j" ), date( "Y" ) );
$yesterday = $today - 86400;

// View cat
if( empty( $catid ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

$array = array();
$subcats = array();

$c = $list_cats[$catid];
$subcats = $c['subcats'];

$page_title = $mod_title = $c['title'];
$key_words = $module_info['keywords'];
$description = $c['description'];

$per_page = 15;
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $catalias;

$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `catid`, `title`, `alias`, `introtext` , `uploadtime`, `author_name`, `filesize`, `fileimage`, `view_hits`, `download_hits`, `comment_allow`, `comment_hits` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid`=" . $c['id'] . " AND `status`=1 ORDER BY `uploadtime` DESC LIMIT " . ( $page - 1 ) * $per_page . ", " . $per_page;

$result = $db->sql_query( $sql );
$query = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $query );

while( $row = $db->sql_fetchrow( $result ) )
{
	$cattitle = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "\">" . $list_cats[$row['catid']]['title'] . "</a>";
	$more_link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "/" . $row['alias'];

	$uploadtime = ( int )$row['uploadtime'];
	if( $uploadtime >= $today )
	{
		$uploadtime = $lang_module['today'] . ", " . date( "H:i", $row['uploadtime'] );
	}
	elseif( $uploadtime >= $yesterday )
	{
		$uploadtime = $lang_module['yesterday'] . ", " . date( "H:i", $row['uploadtime'] );
	}
	else
	{
		$uploadtime = nv_date( "d/m/Y H:i", $row['uploadtime'] );
	}

	$img = NV_UPLOADS_DIR . $row['fileimage'];
	$imageinfo = nv_ImageInfo( NV_ROOTDIR . '/' . $img, 300, true, NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb' );

	$array[$row['id']] = array(
		'id' => ( int )$row['id'], //
		'title' => $row['title'], //
		'cattitle' => $cattitle, //
		'introtext' => $row['introtext'], //
		'uploadtime' => $uploadtime, //
		'author_name' => $row['author_name'], //
		'filesize' => ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : "", //
		'fileimage' => $imageinfo, //
		'view_hits' => ( int )$row['view_hits'], //
		'download_hits' => ( int )$row['download_hits'], //
		'more_link' => $more_link, //
		'edit_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . ( int )$row['id'] : "", //
		'del_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name : "" //
	);

	if( $row['comment_allow'] )
	{
		$array[$row['id']]['comment_hits'] = ( int )$row['comment_hits'];
	}
}

$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

$subs = array();
if( ! empty( $subcats ) )
{
	foreach( $subcats as $sub )
	{
		$array_item = array();
		$sql = "SELECT `id`, `catid`, `title`, `alias`, `introtext` , `uploadtime`, `author_name`, `filesize`, `fileimage`, `view_hits`, `download_hits`, `comment_allow`, `comment_hits` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid`=" . $sub . " AND `status`=1 ORDER BY `uploadtime` DESC LIMIT 0, 3";
		$result = $db->sql_query( $sql );

		if( ! $db->sql_numrows( $result ) ) continue;

		while( $row = $db->sql_fetchrow( $result ) )
		{
			$uploadtime = nv_date( "d/m/Y H:i", $row['uploadtime'] );

			$img = NV_UPLOADS_DIR . $row['fileimage'];
			$imageinfo = nv_ImageInfo( NV_ROOTDIR . '/' . $img, 300, true, NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb' );

			$array_item[] = array( //
				'id' => ( int )$row['id'], //
				'title' => $row['title'], //
				'introtext' => $row['introtext'], //
				'uploadtime' => $uploadtime, //
				'author_name' => ! empty( $row['author_name'] ) ? $row['author_name'] : $lang_module['unknown'], //
				'filesize' => ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : "", //
				'fileimage' => $imageinfo, //
				'view_hits' => ( int )$row['view_hits'], //
				'download_hits' => ( int )$row['download_hits'], //
				'more_link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "/" . $row['alias'],
				'edit_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . ( int )$row['id'], //
				'del_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name
			);
		}

		$subs[] = array(
			'catid' => $sub, //
			'title' => $list_cats[$sub]['title'], //
			'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$sub]['alias'], //
			'description' => $list_cats[$sub]['description'], //
			'posts' => $array_item
		);

		unset( $array_item );
	}
}

// Chuyen huong neu khong co noi dung gi
if( empty( $all_page ) and empty( $subs ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

$contents = theme_viewcat_download( $array, $download_config, $subs, $generate_page );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>