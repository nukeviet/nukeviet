<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 17:30
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$mod = $nv_Request->get_title( 'mod', 'post' );
if( empty( $mod ) or ! preg_match( $global_config['check_module'], $mod ) ) die( 'NO_' . $mod );

$sth = $db->prepare( 'SELECT in_menu FROM ' . NV_MODULES_TABLE . ' WHERE title= :title' );
$sth->bindParam( ':title', $mod, PDO::PARAM_STR );
$sth->execute();
$row = $sth->fetch();
if( empty( $row ) )
{
	die( 'NO_' . $mod );
}

$modfile = $site_mods[$mod]['module_file'];

$include_file = NV_ROOTDIR . '/modules/' . $modfile . '/funcs/main.php';

if( file_exists( $include_file ) and filesize( $include_file ) != 0 )
{
	$in_menu = $row['in_menu'] ? 0 : 1;
}
else
{
	$in_menu = 0;
}

$sth = $db->prepare( 'UPDATE ' . NV_MODULES_TABLE . ' SET in_menu=' . $in_menu . ' WHERE title= :title');
$sth->bindParam( ':title', $mod, PDO::PARAM_STR );
$sth->execute();

nv_del_moduleCache( 'modules' );

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $mod;
include NV_ROOTDIR . '/includes/footer.php';

?>