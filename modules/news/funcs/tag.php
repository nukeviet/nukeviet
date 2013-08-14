<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) )
	die( 'Stop!!!' );

$alias = trim( $_GET['alias'] );
$array_op = explode( '/', $alias );
$alias = $array_op[0];

if( isset( $array_op[1] ) )
{
	if( sizeof( $array_op ) == 2 AND preg_match( "/^page\-([0-9]+)$/", $array_op[1], $m ) )
	{
		$page = intval( $m[1] );
	}
	else
	{
		$alias = '';
	}
}
$page_title = trim( str_replace( '-', ' ', $alias ) );

if( ! empty( $page_title ) AND $page_title == strip_punctuation( $page_title ) )
{
	list( $tid, $image_tag, $description, $key_words ) = $db->sql_fetchrow( $db->sql_query( "SELECT `tid`, `image`, `description`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags` WHERE `alias`=" . $db->dbescape( $alias ) ) );
	if( $tid > 0 )
	{
		$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=tag/" . $alias;
		if( $page > 1 )
		{
			$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
		}
		$key_words = $module_info['keywords'];

		$array_mod_title[] = array(
			'catid' => 0,
			'title' => $page_title,
			'link' => $base_url
		);

		$query = $db->sql_query( "SELECT SQL_CALC_FOUND_ROWS `id`, `catid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`=1 AND `id` IN (SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags_id` WHERE `tid`=" . $tid . ") ORDER BY `publtime` DESC LIMIT " . ($page - 1) * $per_page . "," . $per_page );
		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );

		$item_array = array( );
		$end_publtime = 0;
		$show_no_image = $module_config[$module_name]['show_no_image'];

		while( $item = $db->sql_fetch_assoc( $query ) )
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
			elseif( $show_no_image )//no image
			{
				$item['src'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
			}
			else
			{
				$item['imghome'] = '';
			}
			$item['alt'] = ! empty( $item['homeimgalt'] ) ? $item['homeimgalt'] : $item['title'];
			$item['width'] = $module_config[$module_name]['homewidth'];

			$end_publtime = $item['publtime'];

			$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$item_array[] = $item;
		}
		$db->sql_freeresult( $query );
		unset( $query, $row );

		$item_array_other = array( );
		$query = $db->sql_query( "SELECT `id`, `catid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`=1 AND `id` IN (SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags_id` WHERE `tid`=" . $tid . ") AND `publtime` < " . $end_publtime . " ORDER BY `publtime` DESC LIMIT 0," . $st_links . "" );

		while( $item = $db->sql_fetch_assoc( $query ) )
		{
			$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$item_array_other[] = $item;
		}

		unset( $query, $row, $arr_listcatid );

		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );

		if( ! empty( $image_tag ) )
		{
			$image_tag = NV_BASE_SITEURL . NV_FILES_DIR . "/" . $module_name . "/topics/" . $image_tag;
		}
		$contents = topic_theme( $item_array, $item_array_other, $generate_page, $page_title, $description, $image_tag );

		if( $page > 1 )
		{
			$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
		}
		include (NV_ROOTDIR . '/includes/header.php');
		echo nv_site_theme( $contents );
		include (NV_ROOTDIR . '/includes/footer.php');
	}
}

$redirect = "<meta http-equiv=\"Refresh\" content=\"3;URL=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";
nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect );
?>