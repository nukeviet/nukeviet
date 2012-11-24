<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_ABOUT', true );

$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . NV_CACHE_PREFIX . ".cache"; // Cache dung tai tat ca cac giao dien

if( ( $cache = nv_get_cache( $cache_file ) ) != false )
{
	$abouts = unserialize( $cache );
}
else
{
	$abouts = array();

	$sql = $db->sql_query( "SELECT `id`,`title`,`alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `status`=1 ORDER BY `weight` ASC" );
	while( $row = $db->sql_fetchrow( $sql ) )
	{
		$abouts[$row['alias']] = array( //
			'id' => $row['id'], //
			'title' => $row['title'], //
			'alias' => $row['alias'] //
		);
	}

	$cache = serialize( $abouts );
	nv_set_cache( $cache_file, $cache );
}

$id = 0;
$ab_links = array();

if( ! empty( $abouts ) )
{
	$alias = ( ! empty( $array_op ) and ! empty( $array_op[0] ) ) ? $array_op[0] : "";

	if( ! empty( $alias ) and isset( $abouts[$alias] ) )
	{
		$id = $abouts[$alias]['id'];
		$nv_vertical_menu[] = array( $abouts[$alias]['title'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $abouts[$alias]['alias'], 1 );
		unset( $abouts[$alias] );
	}
	else
	{
		$about = array_shift( $abouts );
		$id = $about['id'];
		$nv_vertical_menu[] = array( $about['title'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $about['alias'], 1 );
	}

	if( ! empty( $abouts ) )
	{
		foreach( $abouts as $about )
		{
			$nv_vertical_menu[] = array( $about['title'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $about['alias'], 0 );
			$ab_links[] = array( "title" => $about['title'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $about['alias'] );
		}
	}
}

?>