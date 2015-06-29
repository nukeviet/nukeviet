<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$submenu['file'] = $lang_module['file_backup'];
if( defined( 'NV_IS_GODADMIN' ) )
{
	$submenu['setting'] = $lang_global['mod_settings'];
}