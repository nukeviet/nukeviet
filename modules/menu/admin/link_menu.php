<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$post['mid'] = $nv_Request->get_int( 'mid', 'post', 0 );

$xtpl = new XTemplate( 'rows.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . $post['mid'] . ' ORDER BY sort';
$result = $db->query( $sql );

$arr_item[0] = array(
	'key' => 0,
	'title' => $lang_module['cat0'],
	'selected' => ($post['parentid'] == 0) ? " selected=\"selected\"" : ""
);

while( $row = $result->fetch() )
{
	$sp_title = '';
	if( $row['lev'] > 0 )
	{
		for( $i = 1; $i <= $row['lev']; ++$i )
		{
			$sp_title .= $sp;
		}
	}
	$arr_item[$row['id']] = array(
		'key' => $row['id'],
		'title' => $sp_title . $row['title'],
		"selected" => ($post['parentid'] == $row['parentid']) ? " selected=\"selected\"" : ""
	);
}

foreach( $arr_item as $arr_items )
{
	$xtpl->assign( 'cat', $arr_items );
	$xtpl->parse( 'main.cat' );
}

$contents = $xtpl->text( 'main.cat' );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
exit();