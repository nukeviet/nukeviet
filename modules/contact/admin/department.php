<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 22, 2010 3:00:20 PM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( 'department.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$a = 0;
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department ORDER BY full_name';
$result = $db->query( $sql );
while( $row = $result->fetch() )
{
	++$a;
	$xtpl->assign( 'ROW', array(
		'full_name' => $row['full_name'],
		'email' => $row['email'],
		'phone' => $row['phone'],
		'fax' => $row['fax'],
		'id' => $row['id'],
		'url_part' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['id'] . '/0/1',
		'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=row&amp;id=' . $row['id']
	) );

	$array = array( $lang_global['disable'], $lang_global['active'] );

	foreach( $array as $key => $val )
	{
		$xtpl->assign( 'STATUS', array(
			'key' => $key,
			'selected' => $key == $row['act'] ? ' selected="selected"' : '',
			'title' => $val
		) );

		$xtpl->parse( 'main.row.status' );
	}

	$xtpl->parse( 'main.row' );
}
if( empty( $a ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=row' );
	die();
}
$xtpl->assign( 'URL_ADD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=row' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['department_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';