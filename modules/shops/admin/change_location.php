<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$locationid = $nv_Request->get_int( 'locationid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = 'NO_' . $locationid;

list( $locationid, $parentid, $numsub ) = $db->query( 'SELECT id, parentid, numsub FROM ' . $db_config['prefix'] . '_' . $module_data . '_location WHERE id=' . $locationid )->fetch( 3 );

if( $locationid > 0 )
{
	if( $mod == 'weight' and $new_vid > 0 )
	{
		$sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_location WHERE id!=' . $locationid . ' AND parentid=' . $parentid . ' ORDER BY weight ASC';
		$result = $db->query( $sql );

		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_location SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}

		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_location SET weight=' . $new_vid . ' WHERE id=' . $locationid;
		$db->query( $sql );

		nv_fix_location_order();
		$content = 'OK_' . $parentid;
	}
	nv_del_moduleCache( $module_name );
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';