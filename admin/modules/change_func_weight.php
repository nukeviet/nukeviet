<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/7/2010 2:9
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$fid = $nv_Request->get_int( 'fid', 'post', 0 );
$new_weight = $nv_Request->get_int( 'new_weight', 'post', 0 );

if( empty( $fid ) or empty( $new_weight ) ) die( 'NO|' . $fid );

$row = $db->query( 'SELECT in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE func_id=' . $fid )->fetch();

if( empty( $row ) ) die( 'NO|' . $fid );

$sth = $db->prepare( 'UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight=0 WHERE in_module= :in_module AND show_func = 0' );
$sth->bindParam( ':in_module', $row['in_module'], PDO::PARAM_STR );
$sth->execute();

$sth = $db->prepare( 'SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :in_module AND func_id!=' . $fid . ' AND show_func = 1 ORDER BY subweight ASC' );
$sth->bindParam( ':in_module', $row['in_module'], PDO::PARAM_STR );
$sth->execute();

$weight = 0;
while( $row = $sth->fetch() )
{
	++$weight;

	if( $weight == $new_weight ) ++$weight;

	$db->query( 'UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight=' . $weight . ' WHERE func_id=' . $row['func_id'] );
}

$db->query( 'UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight=' . $new_weight . ' WHERE func_id=' . $fid );
nv_del_moduleCache( 'modules' );

include NV_ROOTDIR . '/includes/header.php';
echo 'OK|show_funcs';
include NV_ROOTDIR . '/includes/footer.php';