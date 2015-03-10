<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$lang_siteinfo = nv_get_lang_module( $mod );

if( $data['type'] == 'review_new' )
{
	list( $id, $listcatid, $title, $alias ) = $db->query( 'SELECT id, listcatid, ' . NV_LANG_DATA . '_title title, ' . NV_LANG_DATA . '_alias alias FROM ' . $db_config['prefix'] . '_' . $site_mods[$mod]['module_data'] . '_rows WHERE id=' . $data['content']['product_id'] )->fetch( 3 );
	if( $mod != $module_name )
	{
		$sql = 'SELECT ' . NV_LANG_DATA . '_alias AS alias FROM ' . $db_config['prefix'] . '_' . $site_mods[$mod]['module_data'] . '_catalogs ORDER BY sort ASC';
		$global_array_cat = nv_db_cache( $sql, 'catid', $module_name );
	}
	$data['title'] = sprintf( $lang_siteinfo['review_notification_new'], $data['send_from'], $title );
	$data['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$listcatid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];
}