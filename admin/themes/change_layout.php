<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/7/2010 2:23
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$func_id = $nv_Request->get_int( 'funcid', 'post' );
$layout = $nv_Request->get_title( 'layout', 'post', '', 1 );
$selectthemes_old = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'get', $selectthemes_old );

$sth = $db->prepare( 'SELECT func_id FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=' . $func_id . ' AND theme= :theme');
$sth->bindParam( ':theme', $selectthemes, PDO::PARAM_STR );
$sth->execute();
$row = $sth->fetch();

if( empty($row) )
{
	$sth = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_modthemes VALUES(' . $func_id . ', :layout, :theme)' );
	$sth->bindParam( ':theme', $selectthemes, PDO::PARAM_STR );
}
else
{
	$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_modthemes SET layout = :layout WHERE func_id=' . $func_id );
}
$sth->bindParam( ':layout', $layout, PDO::PARAM_STR );

if( $sth->execute() )
{
	echo $lang_module['setup_updated_layout'];
}
else
{
	echo $lang_module['setup_error_layout'];
}

nv_del_moduleCache( 'themes' );