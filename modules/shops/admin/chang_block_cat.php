<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$bid = $nv_Request->get_int( 'bid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

if( empty( $bid ) ) die( 'NO_' . $bid );
$content = 'NO_' . $bid;

if( $mod == 'weight' and $new_vid > 0 )
{
	$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat WHERE bid=' . $bid;
	$result = $db->query( $sql );
	$numrows = $result->rowCount();
	if( $numrows != 1 ) die( 'NO_' . $topicid );

	$sql = 'SELECT bid FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat WHERE bid!=' . $bid . ' ORDER BY weight ASC';
	$result = $db->query( $sql );
	$weight = 0;
	while( $row = $result->fetch() )
	{
		++$weight;
		if( $weight == $new_vid ) ++$weight;
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_block_cat SET weight=' . $weight . ' WHERE bid=' . intval( $row['bid'] );
		$db->query( $sql );
	}

	$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_block_cat SET weight=' . $new_vid . ' WHERE bid=' . intval( $bid );
	$db->query( $sql );

	$content = 'OK_' . $bid;
}
elseif( $mod == 'adddefault' and $bid > 0 )
{
	$new_vid = ( intval( $new_vid ) == 1 ) ? 1 : 0;
	$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_block_cat SET adddefault=' . $new_vid . ' WHERE bid=' . intval( $bid );
	$db->query( $sql );
	$content = 'OK_' . $bid;
}

nv_del_moduleCache( $module_name );

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';