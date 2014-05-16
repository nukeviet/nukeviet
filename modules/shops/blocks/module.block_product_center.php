<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if( ! function_exists( 'nv_product_center' ) )
{
	/**
	 * nv_product_center()
	 *
	 * @return
	 */
	function nv_product_center( $block_config )
	{
		global $module_name, $lang_module, $module_info, $module_file, $global_array_cat, $db, $module_data, $db_config, $pro_config, $global_config;

		$module = $block_config['module'];

		$num_view = 5;
		$num = 30;
		$array = array();

		$xtpl = new XTemplate( "block.product_center.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'THEME_TEM', NV_BASE_SITEURL . "themes/" . $module_info['template'] );
		$xtpl->assign( 'WIDTH', $pro_config['homewidth'] );
		$xtpl->assign( 'NUMVIEW', $num_view );

		$cache_file = NV_LANG_DATA . "_block_module_product_center_" . NV_CACHE_PREFIX . ".cache";
		if( ( $cache = nv_get_cache( $module_name, $cache_file ) ) != false )
		{
			$array = unserialize( $cache );
		}
		else
		{
			$db->sqlreset()
				->select( 'bid' )
				->from( $db_config['prefix'] . "_" . $module_data . "_block_cat" )
				->order( 'weight ASC' )
				->limit( 1 );

			$result = $db->query( $db->sql() );
			$bid = $result->fetchColumn();

			$db->sqlreset()
				->select( "t1.id, t1.listcatid, t1." . NV_LANG_DATA . "_title AS title, t1." . NV_LANG_DATA . "_alias AS alias, t1.homeimgfile, t1.homeimgthumb , t1.homeimgalt" )
				->from( $db_config['prefix'] . "_" . $module_data . "_rows t1" )
				->join( "INNER JOIN " . $db_config['prefix'] . "_" . $module_data . "_block t2 ON t1.id = t2.id" )
				->where( "t2.bid= " . $bid . " AND t1.status =1" )
				->order( 't1.id DESC' )
				->limit( $num );

			$array = nv_db_cache( $db->sql(), 'id', $module_name );
			$cache = serialize( $array );
			nv_set_cache( $module_name, $cache_file, $cache );
		}

		foreach( $array as $row )
		{
			$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$row['listcatid']]['alias'] . "/" . $row['alias'] . "-" . $row['id'] . $global_config['rewrite_exturl'];

			if( $row['homeimgthumb'] == 1 ) //image thumb
			{
				$src_img = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module . '/' . $row['homeimgfile'];
			}
			elseif( $row['homeimgthumb'] == 2 ) //image file
			{
				$src_img = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $row['homeimgfile'];
			}
			elseif( $row['homeimgthumb'] == 3 ) //image url
			{
				$src_img = $row['homeimgfile'];
			}
			else //no image
			{
				$src_img = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/shops/no-image.jpg';
			}

			$xtpl->assign( 'LINK', $link );
			$xtpl->assign( 'TITLE', $row['title'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $row['title'], 30 ) );
			$xtpl->assign( 'SRC_IMG', $src_img );

			$xtpl->parse( 'main.items' );
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

$content = nv_product_center( $block_config );