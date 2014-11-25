<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'Users',
	'modfuncs' => 'main,login,logout,register,lostpass,changepass,active,editinfo,avatar,lostactivelink,openid,regroups,thu,memberlist',
	'submenu' => 'main,login,logout,register,lostpass,changepass,active,openid,editinfo,lostactivelink,regroups,memberlist',
	'is_sysmod' => 1,
	'virtual' => 0,
	'version' => '4.0.00',
	'date' => 'Wed, 20 Oct 2010 00:00:00 GMT',
	'author' => 'VINADES (contact@vinades.vn)',
	'note' => ''
);