<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );

global $db_config;

$rssarray = array();

$sql = 'SELECT catid, parentid, ' . NV_LANG_DATA . '_title AS title, ' . NV_LANG_DATA . '_alias AS alias FROM ' . $db_config['prefix'] . '_' . $mod_data . '_catalogs ORDER BY weight, sort';
$list = nv_db_cache( $sql, '', $mod_name );
foreach( $list as $value )
{
	$value['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=rss/' . $value['alias'];
	$rssarray[] = $value;
}