<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 30 Nov 2014 01:54:12 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$openid_server_config = array(
	'identity' => 'https://me.yahoo.com',
	'required' => array(
		'email' => 'contact/email',
		'nickname' => 'namePerson/friendly',
		'fullname' => 'namePerson',
		'gender' => 'person/gender'
	)
);