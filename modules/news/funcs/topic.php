<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$topicalias = isset( $array_op[1] ) ? trim( $array_op[1] ) : '';
$page = ( isset( $array_op[2] ) and substr( $array_op[2], 0, 5 ) == 'page-' ) ? intval( substr( $array_op[2], 5 ) ) : 1;

$sth = $db->prepare( 'SELECT topicid, title, image, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE alias= :alias' );
$sth->bindParam( ':alias', $topicalias, PDO::PARAM_STR );
$sth->execute();

list( $topicid, $page_title, $topic_image, $description, $key_words ) = $sth->fetch( 3 );

if( $topicid > 0 )
{
	$array_mod_title[] = array(
		'catid' => 0,
		'title' => $page_title,
		'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['topic'] . '/' . $topicalias
	);

	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( NV_PREFIXLANG . '_' . $module_data . '_rows' )
		->where( 'status=1 AND topicid = ' . $topicid );

	$num_items = $db->query( $db->sql() )->fetchColumn();

	$db->select( 'id, catid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, hitstotal, hitscm, total_rating, click_rating' )
		->order( 'publtime DESC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );

	$topic_array = array();
	$end_publtime = 0;
	$show_no_image = $module_config[$module_name]['show_no_image'];

	$result = $db->query( $db->sql() );
	while( $item = $result->fetch() )
	{
		if( $item['homeimgthumb'] == 1 )//image thumb
		{
			$item['src'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
		}
		elseif( $item['homeimgthumb'] == 2 )//image file
		{
			$item['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
		}
		elseif( $item['homeimgthumb'] == 3 )//image url
		{
			$item['src'] = $item['homeimgfile'];
		}
		elseif( ! empty( $show_no_image ) )//no image
		{
			$item['src'] = NV_BASE_SITEURL . $show_no_image;
		}
		else
		{
			$item['imghome'] = '';
		}
		$item['alt'] = ! empty( $item['homeimgalt'] ) ? $item['homeimgalt'] : $item['title'];
		$item['width'] = $module_config[$module_name]['homewidth'];

		$end_publtime = $item['publtime'];

		$item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
		$topic_array[] = $item;
	}
	$result->closeCursor();
	unset( $result, $row );

	$topic_other_array = array();

	$db->sqlreset()
		->select( 'id, catid, addtime, edittime, publtime, title, alias, hitstotal' )
		->from( NV_PREFIXLANG . '_' . $module_data . '_rows' )
		->where( 'status=1 AND topicid = ' . $topicid . ' AND publtime < ' . $end_publtime )
		->order( 'publtime DESC' )
		->limit( $st_links );

	$result = $db->query( $db->sql() );
	while( $item = $result->fetch() )
	{
		$item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
		$topic_other_array[] = $item;
	}
	unset( $result, $row, $arr_listcatid );

	$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['topic'] . '/' . $topicalias;
	$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );

	if( ! empty( $topic_image ) )
	{
		$topic_image = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/topics/' . $topic_image;
	}

	$contents = topic_theme( $topic_array, $topic_other_array, $generate_page, $page_title, $description, $topic_image );

	if( $page > 1 )
	{
		$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	}
}
else
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';