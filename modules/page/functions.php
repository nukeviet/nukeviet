<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_PAGE', true );

$sql = 'SELECT id,title,alias FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status=1 ORDER BY weight ASC';
$rows = nv_db_cache( $sql, 'alias', $module_name );

$id = 0;
$ab_links = array();

if( ! empty( $rows ) )
{
	$alias = ( ! empty( $array_op ) and ! empty( $array_op[0] ) ) ? $array_op[0] : '';

	if( ! empty( $alias ) and isset( $rows[$alias] ) )
	{
		$id = $rows[$alias]['id'];
		$nv_vertical_menu[] = array( $rows[$alias]['title'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $rows[$alias]['alias'] . $global_config['rewrite_exturl'], 1 );
		unset( $rows[$alias] );
	}
	else
	{
		$row = array_shift( $rows );
		$id = $row['id'];
		$nv_vertical_menu[] = array( $row['title'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'], 1 );
	}

	if( ! empty( $rows ) )
	{
		foreach( $rows as $row )
		{
			$nv_vertical_menu[] = array( $row['title'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'], 0 );
			$ab_links[] = array( 'title' => $row['title'], 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'] );
		}
	}
}