<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:30
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $nv_Request;

$list_cats = nv_list_cats( true );

$page = $nv_Request->get_int( 'page', 'get', 1 );
$per_page = 15;

$key = nv_substr( $nv_Request->get_title( 'q', 'post', '', 1 ), 0, NV_MAX_SEARCH_LENGTH );
$cat = $nv_Request->get_int( 'cat', 'post', 0 );

$page_title = $lang_module['search'] . ' ' . $key;

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=search';

$array_where = array();

$db->sqlreset()
	->select( 'COUNT(*)' )
	->from( NV_PREFIXLANG . '_' . $module_data );

if( ! empty( $key ) )
{
	$array_where[] = '(title LIKE :keyword1 OR description LIKE :keyword2 OR introtext LIKE :keyword3)';
}
if( ! empty( $cat ) and isset( $list_cats[$cat] ) )
{
	$allcat = $list_cats[$cat]['subcats'];
	if( ! empty( $allcat ) )
	{
		$allcat[] = $cat;
		$array_where[] = 'catid IN (' . implode( ',', $allcat ) . ')';
	}
	else
	{
		$array_where[] = 'catid = ' . $cat;
	}
}
$array_where[] = 'status=1';

$db->where( implode(' AND ', $array_where) );

$sth = $db->prepare( $db->sql() );
if( ! empty( $key ) )
{
	$keyword = '%' . addcslashes( $key, '_%' ) . '%';
	$sth->bindParam( ':keyword1', $keyword, PDO::PARAM_STR );
	$sth->bindParam( ':keyword2', $keyword, PDO::PARAM_STR );
	$sth->bindParam( ':keyword3', $keyword, PDO::PARAM_STR );
}
$sth->execute();
$num_items = $sth->fetchColumn();

if( ! empty( $num_items ) )
{
	$download_config = nv_mod_down_config();

	$array = array();
	$today = mktime( 0, 0, 0, date( 'n' ), date( 'j' ), date( 'Y' ) );
	$yesterday = $today - 86400;

	$db->select( 'id, catid, title, alias, introtext , uploadtime, author_name, filesize, fileimage, view_hits, download_hits, comment_hits' )
		->order( 'uploadtime DESC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );

	$sth = $db->prepare( $db->sql() );
	if( ! empty( $key ) )
	{
		$keyword = '%' . addcslashes( $key, '_%' ) . '%';
		$sth->bindParam( ':keyword1', $keyword, PDO::PARAM_STR );
		$sth->bindParam( ':keyword2', $keyword, PDO::PARAM_STR );
		$sth->bindParam( ':keyword3', $keyword, PDO::PARAM_STR );
	}
	$sth->execute();

	while( $row = $sth->fetch() )
	{
		$cattitle = '<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $list_cats[$row['catid']]['alias'] . '">' . $list_cats[$row['catid']]['title'] . '</a>';

		$uploadtime = ( int )$row['uploadtime'];
		if( $uploadtime >= $today )
		{
			$uploadtime = $lang_module['today'] . ', ' . date( 'H:i', $row['uploadtime'] );
		}
		elseif( $uploadtime >= $yesterday )
		{
			$uploadtime = $lang_module['yesterday'] . ', ' . date( 'H:i', $row['uploadtime'] );
		}
		else
		{
			$uploadtime = nv_date( 'd/m/Y H:i', $row['uploadtime'] );
		}

		$array[$row['id']] = array(
			'id' => $row['id'],
			'title' => $row['title'],
			'cattitle' => $cattitle,
			'introtext' => $row['introtext'],
			'uploadtime' => $uploadtime,
			'author_name' => $row['author_name'],
			'filesize' => ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : '',
			'imagesrc' => ( ! empty( $row['fileimage'] ) ) ? NV_BASE_SITEURL . NV_FILES_DIR . $row['fileimage'] : '',
			'view_hits' => $row['view_hits'],
			'download_hits' => $row['download_hits'],
			'comment_hits' => ( int )$row['comment_hits'],
			'more_link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $list_cats[$row['catid']]['alias'] . '/' . $row['alias'] . $global_config['rewrite_exturl'],
			'edit_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;edit=1&amp;id=' . $row['id'] : '',
			'del_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name : ''
		);
	}
	$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );

	$contents = theme_viewcat_download( $array, $download_config, '', $generate_page );
	if( $page > 1 )
	{
		$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	}
}
else
{
	$contents = $lang_module['search_noresult'];
}

$key_words = $description = 'no';

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';