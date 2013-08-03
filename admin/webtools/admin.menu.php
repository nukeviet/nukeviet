<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2013 VINADES.,JSC. All rights reserved
 * @createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$submenu['clearsystem'] = $lang_module['clearsystem'];
if( empty( $global_config['idsite'] ) )
{
	$submenu['checkupdate'] = $lang_module['checkupdate'];
	$submenu['config'] = $lang_module['config'];
	if( NV_LANG_INTERFACE == 'vi' )
	{
		$submenu['mudim'] = $lang_module['mudim'];
	}
}

?>