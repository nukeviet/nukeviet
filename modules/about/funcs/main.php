<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_ABOUT' ) ) die( 'Stop!!!' );

$contents = "";

if( $id )
{
	$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . $module_info['template'] . "_" . $id . "_" . NV_CACHE_PREFIX . ".cache"; // Cache tung giao dien

	if( ( $cache = nv_get_cache( $cache_file ) ) != false )
	{
		$cache = unserialize( $cache );
		$page_title = $mod_title = $cache['page_title'];
		$key_words = $cache['keywords'];
		$contents = $cache['contents'];
	}
	else
	{
		$cache = array();

		$sql = "SELECT `id`,`title`,`alias`,`bodytext`,`keywords`,`add_time`,`edit_time` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `status`=1 AND `id`=" . $id;
		$query = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $query );

		$row['add_time'] = nv_date( "H:i T l, d/m/Y", $row['add_time'] );
		$row['edit_time'] = nv_date( "H:i T l, d/m/Y", $row['edit_time'] );
		$contents = $cache['contents'] = nv_about_main( $row, $ab_links );
		$cache['bodytext'] = strip_tags( $row['bodytext'] );
		$cache['bodytext'] = nv_clean60( $cache['bodytext'], 300 );

		$page_title = $mod_title = $cache['page_title'] = $row['title'];

		if( ! empty( $row['keywords'] ) )
		{
			$key_words = $cache['keywords'] = $row['keywords'];
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
				$key_words = preg_replace( "/[ ]+/", ",", $key_words );
			}

			$cache['keywords'] = $key_words;

			$query = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "` SET `keywords`=" . $db->dbescape( $key_words ) . " WHERE `id` =" . $id;
			$db->sql_query( $query );
		}

		$cache['alias'] = $row['alias'];

		//Dung cho Block
		$cache = serialize( $cache );
		nv_set_cache( $cache_file, $cache );
	}
}
else
{
	$page_title = $module_info['custom_title'];
	$key_words = $module_info['keywords'];
	$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>