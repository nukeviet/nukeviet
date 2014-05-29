<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$contents = '';
$publtime = 0;

if( nv_user_in_groups( $global_array_cat[$catid]['groups_view'] ) )
{
	$query = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $id );
	$news_contents = $query->fetch();
	if( $news_contents['id'] > 0 )
	{
		$body_contents = $db->query( 'SELECT bodyhtml as bodytext, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save, gid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $news_contents['id'] / 2000 ) . ' where id=' . $news_contents['id'] )->fetch();
		$news_contents = array_merge( $news_contents, $body_contents );
		unset( $body_contents );

		$show_no_image = $module_config[$module_name]['show_no_image'];

		if( defined( 'NV_IS_MODADMIN' ) or ( $news_contents['status'] == 1 and $news_contents['publtime'] < NV_CURRENTTIME and ( $news_contents['exptime'] == 0 or $news_contents['exptime'] > NV_CURRENTTIME ) ) )
		{
			$time_set = $nv_Request->get_int( $module_name . '_' . $op . '_' . $id, 'session' );
			if( empty( $time_set ) )
			{
				$nv_Request->set_Session( $module_data . '_' . $op . '_' . $id, NV_CURRENTTIME );
				$query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hitstotal=hitstotal+1 WHERE id=' . $id;
				$db->query( $query );

				$array_catid = explode( ',', $news_contents['listcatid'] );
				foreach( $array_catid as $catid_i )
				{
					$query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET hitstotal=hitstotal+1 WHERE id=' . $id;
					$db->query( $query );
				}
			}
			$news_contents['showhometext'] = $module_config[$module_name]['showhometext'];
			$news_contents['homeimgalt'] = ( empty( $news_contents['homeimgalt'] ) ) ? $news_contents['title'] : $news_contents['homeimgalt'];
			if( ! empty( $news_contents['homeimgfile'] ) and $news_contents['imgposition'] > 0 )
			{
				$src = $alt = $note = '';
				$width = $height = 0;
				if( $news_contents['homeimgthumb'] == 1 and $news_contents['imgposition'] == 1 )
				{
					$src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'];
					$width = $module_config[$module_name]['homewidth'];
				}
				elseif( $news_contents['homeimgthumb'] == 3 )
				{
					$src = $news_contents['homeimgfile'];
					$width = ( $news_contents['imgposition'] == 1 ) ? $module_config[$module_name]['homewidth'] : $module_config[$module_name]['imagefull'];
				}
				elseif( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'] ) )
				{
					$src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'];
					if( $news_contents['imgposition'] == 1 )
					{
						$width = $module_config[$module_name]['homewidth'];
					}
					else
					{
						$imagesize = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'] );
						if( $imagesize[0] > 0 and $imagesize[0] > $module_config[$module_name]['imagefull'] )
						{
							$width = $module_config[$module_name]['imagefull'];
						}
						else
						{
							$width = $imagesize[0];
						}
					}
				}

				if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'] ) )
				{
					$news_contents['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'];
					$meta_property['og:image'] = NV_MY_DOMAIN . $news_contents['homeimgfile'];
				}
				elseif( !empty( $show_no_image ) )
				{
					$meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $show_no_image;
				}

				$news_contents['image'] = array(
					'src' => $src,
					'width' => $width,
					'alt' => $news_contents['homeimgalt'],
					'note' => $news_contents['homeimgalt'],
					'position' => $news_contents['imgposition']
				);
			}
			elseif( ! empty( $show_no_image ) )
			{
				$meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $show_no_image;
			}
			if( $alias_url == $news_contents['alias'] )
			{
				$publtime = intval( $news_contents['publtime'] );
			}
		}
	}

	if( $publtime == 0 )
	{
		$redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect );
	}


	$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$news_contents['catid']]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true );
	if( $_SERVER['REQUEST_URI'] != $base_url_rewrite )
	{
		Header( 'Location: ' . $base_url_rewrite );
		die();
	}

	if( $catid != $news_contents['catid'] )
	{
		$canonicalUrl = NV_MY_DOMAIN . $base_url_rewrite;
	}

	$news_contents['url_sendmail'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=sendmail/' . $global_array_cat[$catid]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true );
	$news_contents['url_print'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print/' . $global_array_cat[$catid]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true );
	$news_contents['url_savefile'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=savefile/' . $global_array_cat[$catid]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true );

	$sql = 'SELECT title, link, logo FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid = ' . $news_contents['sourceid'];
	$result = $db->query( $sql );

	list( $sourcetext, $source_link, $source_logo ) = $result->fetch( 3 );
	unset( $sql, $result );

	$news_contents['newscheckss'] = md5( $news_contents['id'] . session_id() . $global_config['sitekey'] );
	if( $module_config[$module_name]['config_source'] == 0 ) $news_contents['source'] = $sourcetext;
	elseif( $module_config[$module_name]['config_source'] == 1 ) $news_contents['source'] = $source_link;
	elseif( $module_config[$module_name]['config_source'] == 2 && ! empty( $source_logo ) ) $news_contents['source'] = '<img width="100px" src="' . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/source/' . $source_logo . '">';
	$news_contents['publtime'] = nv_date( 'l - d/m/Y H:i', $news_contents['publtime'] );

	$related_new_array = array();
	$db->sqlreset()
		->select( 't1.id, t1.title, t1.alias, t1.publtime, t2.newday, t1.homeimgfile, t1.homeimgthumb, t1.hometext' )
		->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' t1' )
		->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
		->where( 't1.status=1 AND t1.publtime > ' . $publtime . ' AND t1.publtime < ' . NV_CURRENTTIME )
		->order( 'id ASC' )
		->limit( $st_links );

	$related = $db->query( $db->sql() );
	while( $row = $related->fetch() )
	{
		if( $row['homeimgthumb'] == 1 ) //image thumb
		{
			$row['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $row['homeimgfile'];
		}
		elseif( $row['homeimgthumb'] == 2 ) //image file
		{
			$row['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['homeimgfile'];
		}
		elseif( $row['homeimgthumb'] == 3 ) //image url
		{
			$row['imghome'] = $row['homeimgfile'];
		}
		elseif( ! empty( $show_no_image ) ) //no image
		{
			$row['imghome'] = NV_BASE_SITEURL . $show_no_image;
		}
		else
		{
			$row['imghome'] = '';
		}

		$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
		$related_new_array[] = array(
			'title' => $row['title'],
			'time' => $row['publtime'],
			'link' => $link,
			'newday' => $row['newday'],
			'hometext' => $row['hometext'],
			'imghome' => $row['imghome']
		);
	}
	$related->closeCursor();

	sort( $related_new_array, SORT_NUMERIC );

	$related_array = array();

	$db->sqlreset()
		->select( 't1.id, t1.title, t1.alias, t1.publtime, t2.newday, t1.homeimgfile, t1.homeimgthumb, t1.hometext' )
		->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' t1' )
		->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
		->where( 't1.status=1 AND t1.publtime < ' . $publtime . ' AND t1.publtime < ' . NV_CURRENTTIME )
		->order( 'id DESC' )
		->limit( $st_links );

	$related = $db->query( $db->sql() );
	while( $row = $related->fetch() )
	{
		if( $row['homeimgthumb'] == 1 ) //image thumb
		{
			$row['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $row['homeimgfile'];
		}
		elseif( $row['homeimgthumb'] == 2 ) //image file
		{
			$row['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['homeimgfile'];
		}
		elseif( $row['homeimgthumb'] == 3 ) //image url
		{
			$row['imghome'] = $row['homeimgfile'];
		}
		elseif( ! empty( $show_no_image ) ) //no image
		{
			$row['imghome'] = NV_BASE_SITEURL . $show_no_image;
		}
		else
		{
			$row['imghome'] = '';
		}

		$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
		$related_array[] = array(
			'title' => $row['title'],
			'time' => $row['publtime'],
			'link' => $link,
			'newday' => $row['newday'],
			'hometext' => $row['hometext'],
			'imghome' => $row['imghome']
		);
	}

	$related->closeCursor();
	unset( $related, $row );

	$topic_array = array();
	$topic_a = '';
	if( $news_contents['topicid'] > 0 )
	{
		list( $topic_title, $topic_alias ) = $db->query( 'SELECT title, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid = ' . $news_contents['topicid'] )->fetch( 3 );

		$db->sqlreset()
			->select( 't1.id, t1.catid, t1.title, t1.alias, t1.publtime, t2.newday, t1.homeimgfile, t1.homeimgthumb, t1.hometext' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_rows t1' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->where( 't1.status=1 AND t1.topicid = ' . $news_contents['topicid'] . ' AND t1.id != ' . $id )
			->order( 'id DESC' )
			->limit( $st_links );

		$topic = $db->query( $db->sql() );
		while( $row = $topic->fetch() )
		{
			if( $row['homeimgthumb'] == 1 ) //image thumb
			{
				$row['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $row['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 2 ) //image file
			{
				$row['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 3 ) //image url
			{
				$row['imghome'] = $row['homeimgfile'];
			}
			elseif( ! empty( $show_no_image ) ) //no image
			{
				$row['imghome'] = NV_BASE_SITEURL . $show_no_image;
			}
			else
			{
				$row['imghome'] = '';
			}

			$topiclink = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['topic'] . '/' . $topic_alias;
			$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'];
			$topic_array[] = array(
				'title' => $row['title'],
				'link' => $link,
				'time' => $row['publtime'],
				'newday' => $row['newday'],
				'topiclink' => $topiclink,
				'topictitle' => $topic_title,
				'hometext' => $row['hometext'],
				'imghome' => $row['imghome']
			);
		}
		$topic->closeCursor();
		unset( $topic, $rows );
	}

	if( $news_contents['allowed_rating'] )
	{
		$time_set_rating = $nv_Request->get_int( $module_name . '_' . $op . '_' . $news_contents['id'], 'cookie', 0 );
		if( $time_set_rating > 0 )
		{
			$news_contents['disablerating'] = 1;
		}
		else
		{
			$news_contents['disablerating'] = 0;
		}
		$news_contents['stringrating'] = sprintf( $lang_module['stringrating'], $news_contents['total_rating'], $news_contents['click_rating'] );
		$news_contents['numberrating'] = ( $news_contents['click_rating'] > 0 ) ? round( $news_contents['total_rating'] / $news_contents['click_rating'], 1 ) : 0;
		$news_contents['langstar'] = array(
			'note' => $lang_module['star_note'],
			'verypoor' => $lang_module['star_verypoor'],
			'poor' => $lang_module['star_poor'],
			'ok' => $lang_module['star_ok'],
			'good' => $lang_module['star_good}'],
			'verygood' => $lang_module['star_verygood']
		);
	}

	list( $post_username, $post_full_name ) = $db->query( 'SELECT username, full_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $news_contents['admin_id'] )->fetch( 3 );

	$news_contents['post_name'] = empty( $post_full_name ) ? $post_username : $post_full_name;

	$array_keyword = array();
	$key_words = array();
	$_query = $db->query( 'SELECT a1.keyword, a2.alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id a1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_tags a2 ON a1.tid=a2.tid WHERE a1.id=' . $news_contents['id'] );
	while( $row = $_query->fetch() )
	{
		$array_keyword[] = $row;
		$key_words[] = $row['keyword'];
	}

	// comment
	define( 'NV_COMM_ID', $news_contents['id'] );
	define( 'NV_COMM_ALLOWED', $news_contents['allowed_comm'] );
	require_once NV_ROOTDIR . '/modules/comment/comment.php';
	
	$contents = detail_theme( $news_contents, $array_keyword, $related_new_array, $related_array, $topic_array );
	$id_profile_googleplus = $news_contents['gid'];

	$page_title = $news_contents['title'];
	$key_words = implode( ', ', $key_words );
	$description = $news_contents['hometext'];
}
else
{
	$contents = no_permission( $global_array_cat[$catid]['groups_view'] );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';