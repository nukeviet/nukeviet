<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2013 VINADES.,JSC. All rights reserved
 * @createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$submenu['file'] = $lang_module['file_backup'];
if( defined( "NV_IS_GODADMIN" ) )
{
	$submenu['setting'] = $lang_global['mod_settings'];
}

?>