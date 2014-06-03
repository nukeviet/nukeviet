<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:30
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

if( empty( $list_cats ) )
{
	$page_title = $module_info['custom_title'];
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( '' );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

$contents = '';

$download_config = nv_mod_down_config();

$today = mktime( 0, 0, 0, date( 'n' ), date( 'j' ), date( 'Y' ) );
$yesterday = $today - 86400;

//rating
if( $nv_Request->isset_request( 'rating', 'post' ) )
{
	$in = implode( ',', array_keys( $list_cats ) );

	$rating = $nv_Request->get_string( 'rating', 'post', '' );

	if( preg_match( '/^([0-9]+)\_([1-5]+)$/', $rating, $m ) )
	{
		$id = ( int )$m[1];
		$point = ( int )$m[2];

		if( $id and ( $point > 0 and $point < 6 ) )
		{
			$sql = 'SELECT id, rating_detail FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id . ' AND catid IN (' . $in . ') AND status=1';
			list( $id, $rating_detail ) = $db->query( $sql )->fetch( 3 );
			if( $id )
			{
				$total = $click = 0;
				if( ! empty( $rating_detail ) )
				{
					$rating_detail = explode( '|', $rating_detail );
					$total = ( int )$rating_detail[0];
					$click = ( int )$rating_detail[1];
				}

				$flrt = $nv_Request->get_string( 'flrt', 'session', '' );
				$flrt = ! empty( $flrt ) ? unserialize( $flrt ) : array();

				if( $id and ! in_array( $id, $flrt ) )
				{
					$flrt[] = $id;
					$flrt = serialize( $flrt );
					$nv_Request->set_Session( 'flrt', $flrt );

					$total = $total + $point;
					++$click;
					$rating_detail = $total . '|' . $click ;

					$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET rating_detail= :rating_detail WHERE id=' . $id );
					$stmt->bindParam( ':rating_detail', $rating_detail, PDO::PARAM_STR );
					$stmt->execute();

				}

				if( $total and $click )
				{
					$round = round( $total / $click );
					$content = sprintf( $lang_module['rating_string'], $lang_module['file_rating' . $round], $total, $click );
				}
				else
				{
					$content = $lang_module['file_rating0'];
				}

				die( $content );
			}
		}
	}

	die( $lang_module['rating_error1'] );
}

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

// View cat
$new_page = 3;
$array_cats = array();
foreach( $list_cats as $value )
{
	if( empty( $value['parentid'] ) )
	{
		$catid_i = $value['id'];
		if( empty( $value['subcats'] ) )
		{
			$in = 'catid=' . $catid_i;
		}
		else
		{
			$in = $value['subcats'];
			$in[] = $catid_i;
			$in = implode( ',', $in );
			$in = 'catid IN (' . $in . ')';
		}

		$db->sqlreset()
			->select( 'COUNT(*)' )
			->from( NV_PREFIXLANG . '_' . $module_data )
			->where( $in . ' AND status=1 ' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		if( $num_items )
		{
			$db->select( 'id, catid, title, alias, introtext , uploadtime, author_name, filesize, fileimage, view_hits, download_hits, comment_hits' );
			$db->order( 'uploadtime DESC' );
			$db->limit( $new_page );

			$result = $db->query( $db->sql() );

			$array_item = array();
			while( $row = $result->fetch() )
			{
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

				$array_item[$row['id']] = array(
					'id' => ( int )$row['id'],
					'title' => $row['title'],
					'introtext' => $row['introtext'],
					'uploadtime' => $uploadtime,
					'author_name' => ! empty( $row['author_name'] ) ? $row['author_name'] : $lang_module['unknown'],
					'filesize' => ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : '',
					'imagesrc' => ( ! empty( $row['fileimage'] ) ) ? NV_BASE_SITEURL . NV_FILES_DIR . $row['fileimage'] : '',
					'view_hits' => ( int )$row['view_hits'],
					'download_hits' => ( int )$row['download_hits'],
					'comment_hits' => ( int )$row['comment_hits'],
					'more_link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $list_cats[$row['catid']]['alias'] . '/' . $row['alias'] . $global_config['rewrite_exturl'],
					'edit_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;edit=1&amp;id=' . $row['id'] : '',
					'del_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name : ''
				);
			}

			$array_cats[$catid_i] = array();
			$array_cats[$catid_i]['id'] = $value['id'];
			$array_cats[$catid_i]['title'] = $value['title'];
			$array_cats[$catid_i]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $value['alias'];
			$array_cats[$catid_i]['description'] = $list_cats[$value['id']]['description'];
			$array_cats[$catid_i]['subcats'] = $list_cats[$value['id']]['subcats'];
			$array_cats[$catid_i]['items'] = $array_item;
		}
	}
}

$contents = theme_main_download( $array_cats, $list_cats, $download_config );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';