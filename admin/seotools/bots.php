<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_SEOTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['bots_config'];
$errormess = '';

$file_bots = NV_ROOTDIR . '/' . NV_DATADIR . '/bots.config';

$submit = $nv_Request->get_string( 'submit', 'post' );
$errormess = '';

if( $submit )
{
	$bots_name = array_unique( $nv_Request->get_array( 'bot_name', 'post' ) );
	$bot_agent = array_unique( $nv_Request->get_array( 'bot_agent', 'post' ) );
	$bot_ips = $nv_Request->get_array( 'bot_ips', 'post' );
	$bot_allowed = $nv_Request->get_array( 'bot_allowed', 'post' );
	$bots = array();

	foreach( $bots_name as $key => $value )
	{
		$value = strip_tags( $value );
		$agent = strip_tags( $bot_agent[$key] );
		if( $value != '' and $agent != '' )
		{
			$ips = strip_tags( $bot_ips[$key] );
			$allowed = ( isset( $bot_allowed[$key] ) and intval( $bot_allowed[$key] ) ) ? 1 : 0;
			$bots[$value] = array(
				'agent' => $bot_agent[$key],
				'ips' => $ips,
				'allowed' => $allowed
			);
		}
	}

	$contents = serialize( $bots );
	file_put_contents( $file_bots, $contents, LOCK_EX );
}

$bots = ( file_exists( $file_bots ) and filesize( $file_bots ) != 0 ) ? unserialize( file_get_contents( $file_bots ) ) : array();

if( empty( $bots ) and file_exists( NV_ROOTDIR . '/includes/bots.php' ) )
{
	include NV_ROOTDIR . '/includes/bots.php' ;
}
$a = 0;

$xtpl = new XTemplate( 'bots.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );

foreach( $bots as $name => $values )
{
	$array_data = array();
	$array_data['id'] = ++$a;
	$array_data['name'] = $name;
	$array_data['agent'] = $values['agent'];
	$array_data['ips'] = $values['ips'];
	$array_data['checked'] = ( empty( $values['allowed'] ) ) ? '' : 'checked="checked"';

	$xtpl->assign( 'DATA', $array_data );
	$xtpl->parse( 'main.loop' );
}

for( $index = 0; $index < 3; ++$index )
{
	$array_data = array();
	$array_data['id'] = ++$a;
	$array_data['name'] = '';
	$array_data['agent'] = '';
	$array_data['ips'] = '';
	$array_data['checked'] = '';
	$xtpl->assign( 'DATA', $array_data );
	$xtpl->parse( 'main.loop' );
}

if( $errormess != '' )
{
	$xtpl->assign( 'ERROR', $errormess );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';