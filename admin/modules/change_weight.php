<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$mod = $nv_Request->get_title( 'mod', 'post', '' );
$new_weight = $nv_Request->get_int( 'new_weight', 'post', 0 );

if( empty( $mod ) or empty( $new_weight ) or ! preg_match( $global_config['check_module'], $mod ) ) die( 'NO_' . $mod );

$sth = $db->prepare( 'SELECT weight FROM ' . NV_MODULES_TABLE . ' WHERE title= :title' );
$sth->bindParam( ':title', $mod, PDO::PARAM_STR );
$sth->execute();
$row = $sth->fetch();
if( empty( $row ) )
{
	die( 'NO_' . $mod );
}

$sth = $db->prepare( 'SELECT title FROM ' . NV_MODULES_TABLE . ' WHERE title != :title ORDER BY weight ASC' );
$sth->bindParam( ':title', $mod, PDO::PARAM_STR );
$sth->execute();

$weight = 0;
while( $row = $sth->fetch() )
{
	++$weight;
	if( $weight == $new_weight ) ++$weight;

	$sth2 = $db->prepare( 'UPDATE ' . NV_MODULES_TABLE . ' SET weight=' . $weight . ' WHERE title= :title');
	$sth2->bindParam( ':title', $row['title'], PDO::PARAM_STR );
	$sth2->execute();
}

$sth2 = $db->prepare( 'UPDATE ' . NV_MODULES_TABLE . ' SET weight=' . $new_weight . ' WHERE title= :title');
$sth2->bindParam( ':title', $mod, PDO::PARAM_STR );
$sth2->execute();

nv_del_moduleCache( 'modules' );

nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['weight'] . ' module: ' . $mod, $weight . ' -> ' . $new_weight, $admin_info['userid'] );

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $mod;
include NV_ROOTDIR . '/includes/footer.php';