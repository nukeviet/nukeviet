<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 05/07/2010 09:47
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'About',
	'modfuncs' => 'main',
	'is_sysmod' => 1,
	'virtual' => 1,
	'version' => '4.0.01',
	'date' => 'Wed, 20 Oct 2010 00:00:00 GMT',
	'author' => 'VINADES (contact@vinades.vn)',
	'note' => '',
	'uploads_dir' => array( $module_name )
);