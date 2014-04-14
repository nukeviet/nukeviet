<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 26/7/2010, 5:0
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$openid_servers = array();

$openid_servers['google'] = array( //
	'identity' => 'https://www.google.com/accounts/o8/id', //
	'required' => array( //
		'email' => 'contact/email', //
		'firstname' => 'namePerson/first', //
		'lastname' => 'namePerson/last', //
		'gender' => 'person/gender' //
	) //
);

$openid_servers['yahoo'] = array( //
	'identity' => 'https://me.yahoo.com', //
	'required' => array( //
		'email' => 'contact/email', //
		'nickname' => 'namePerson/friendly', //
		'fullname' => 'namePerson', //
		'gender' => 'person/gender' //
	) //
);

$openid_servers['myopenid'] = array( //
	'identity' => 'https://www.myopenid.com', //
	'required' => array( //
		'email' => 'contact/email', //
		'nickname' => 'namePerson/friendly', //
		'fullname' => 'namePerson', //
		'gender' => 'person/gender' //
	) //
);

$openid_servers['nukeviet'] = array( //
	'identity' => 'http://nukeviet.vn/openid/index.php', //
	'required' => array( //
		'email' => 'contact/email', //
		'nickname' => 'namePerson/friendly', //
		'fullname' => 'namePerson', //
		'gender' => 'person/gender' //
	) //
);

$openid_servers['facebook'] = array( //
	'identity' => 'http://www.facebook.com/profile.php?id=%s', //
	'required' => array( //
		'email' => 'contact/email', //
		'username' => 'namePerson/friendly', //
		'name' => 'namePerson', //
		'gender' => 'person/gender' //
	) //
);