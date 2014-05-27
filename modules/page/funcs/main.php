<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_PAGE' ) ) die( 'Stop!!!' );

$contents = '';

if( $id )
{
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status=1 AND id=' . $id;
	$row = $db->query( $sql )->fetch();

	$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'], true );
	if( ! empty( $array_op ) and $_SERVER['REQUEST_URI'] != $base_url_rewrite )
	{
		Header( 'Location: ' . $base_url_rewrite );
		die();
	}
	$canonicalUrl = NV_MY_DOMAIN . $base_url_rewrite;

	if( ! empty( $row['image'] ) && ! nv_is_url( $row['image'] ) )
	{
		$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['image'];
	}
	$row['add_time'] = nv_date( 'H:i T l, d/m/Y', $row['add_time'] );
	$row['edit_time'] = nv_date( 'H:i T l, d/m/Y', $row['edit_time'] );

	$module_info['layout_funcs'][$op_file] = !empty( $row['layout_func'] ) ? $row['layout_func'] : $module_info['layout_funcs'][$op_file];

	if( ! empty( $row['keywords'] ) )
	{
		$key_words = $row['keywords'];
	}
	else
	{
		$key_words = nv_get_keywords( $row['bodytext'] );

		if( empty( $key_words ) )
		{
			$key_words = nv_unhtmlspecialchars( $row['title'] );
			$key_words = strip_punctuation( $key_words );
			$key_words = trim( $key_words );
			$key_words = nv_strtolower( $key_words );
			$key_words = preg_replace( '/[ ]+/', ',', $key_words );
		}

		$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET keywords= :keywords WHERE id =' . $id );
		$stmt->bindParam( ':keywords', $keywords, PDO::PARAM_STR, strlen( $keywords ) );
		$stmt->execute();
	}

	$page_title = $mod_title = $row['title'];
	$description = $row['description'];
	$id_profile_googleplus = $row['gid'];

	// comment
	define( 'NV_COMM_ID', $id );
	define( 'NV_COMM_ALLOWED', $row['activecomm'] );
	require_once NV_ROOTDIR . '/modules/comment/comment.php';

	$contents = nv_page_main( $row, $ab_links );
}
else
{
	$page_title = $module_info['custom_title'];
	$key_words = $module_info['keywords'];
	$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';