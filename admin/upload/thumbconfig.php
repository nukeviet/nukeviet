<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$thumb_type = $nv_Request->get_array( 'thumb_type', 'post' );
	$thumb_width = $nv_Request->get_array( 'thumb_width', 'post' );
	$thumb_height = $nv_Request->get_array( 'thumb_height', 'post' );
	$thumb_quality = $nv_Request->get_array( 'thumb_quality', 'post' );

	$did = $nv_Request->get_int( 'other_dir', 'post', 0 );
	$other_type = $nv_Request->get_int( 'other_type', 'post', 0 );
	if( $did and $other_type )
	{
		$thumb_type[$did] = $other_type;
		$thumb_width[$did] = $nv_Request->get_int( 'other_thumb_width', 'post', 0 );
		$thumb_height[$did] = $nv_Request->get_int( 'other_thumb_height', 'post', 0 );
		$thumb_quality[$did] = $nv_Request->get_int( 'other_thumb_quality', 'post', 0 );
	}
	foreach( $thumb_type as $did => $type )
	{
		$did = intval( $did );
		$type = intval( $type );
		$width = intval( $thumb_width[$did] );
		if( $type == 2 )
		{
			$width = 0;
		}
		elseif( $width > 1000 or $width < 1 )
		{
			$width = 100;
		}
		$height = intval( $thumb_height[$did] );
		if( $type == 1 )
		{
			$height = 0;
		}
		elseif( $height > 1000 or $height < 1 )
		{
			$height = 100;
		}
		$quality = $thumb_quality[$did];
		if( $quality > 100 or $quality < 20 )
		{
			$quality = 90;
		}
		$db->query( 'UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_dir SET
			thumb_type = ' . $type . ', thumb_width = ' . $width . ',
			thumb_height = ' . $height . ', thumb_quality = ' . $quality . '
			WHERE did = ' . $did );
	}
}
$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'LANG', $lang_module );

$thumb_type = array();
$i = 0;
$lang_module['thumb_type_0'] = '';

$sql = 'SELECT * FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir ORDER BY dirname ASC';
$result = $db->query( $sql );
while( $data = $result->fetch() )
{
	if( $data['did'] == 0 )
	{
		$data['dirname'] = $lang_module['thumb_dir_default'];
		$forid = 1;
	}
	else
	{
		$forid = 0;
	}
	if( $data['thumb_type'] )
	{
		for( $id = $forid; $id < 5; $id++ )
		{
			$type = array(
				'id' => $id,
				'selected' => ( $id == $data['thumb_type'] ) ? ' selected="selected"' : '',
				'name' => $lang_module['thumb_type_' . $id]
			);
			$xtpl->assign( 'TYPE', $type );
			$xtpl->parse( 'main.loop.thumb_type' );
		}
		$xtpl->assign( 'DATA', $data );
		$xtpl->parse( 'main.loop' );
	}
	else
	{
		$xtpl->assign( 'OTHER_DIR', $data );
		$xtpl->parse( 'main.other_dir' );
	}
}

for( $id = 0; $id < 5; $id++ )
{
	$type = array( 'id' => $id, 'name' => $lang_module['thumb_type_' . $id] );
	$xtpl->assign( 'TYPE', $type );
	$xtpl->parse( 'main.other_type' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['thumbconfig'];
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';