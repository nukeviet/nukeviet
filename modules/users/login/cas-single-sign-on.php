<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Oct 2014 08:34:25 GMT
 */

if( !defined( 'NV_IS_MOD_USER' ) )
	die( 'Stop!!!' );

$phpcas_path = NV_ROOTDIR . '/modules/users';

// Path to the ca chain that issued the cas server certificate
$cas_server_ca_cert_path = '/path/to/cachain.pem';

// Load the CAS lib
//require_once $phpcas_path . '/CAS.php';
include NV_ROOTDIR . '/modules/users/CAS.php';

// Enable debugging
phpCAS::setDebug( );

// Initialize phpCAS
phpCAS::client( CAS_VERSION_2_0, $global_config['config_sso']['cas_hostname'], $global_config['config_sso']['cas_port'], $global_config['config_sso']['cas_baseuri'] );

// For production use set the CA certificate that is the issuer of the cert
// on the CAS server and uncomment the line below
// phpCAS::setCasServerCACert($global_config['config_sso']['cas_certificate_path']);

// For quick testing you can disable SSL validation of the CAS server.
// THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
// VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
phpCAS::setNoCasServerValidation( );

// set the language to french
//phpCAS::setLang(PHPCAS_LANG_FRENCH);

// force CAS authentication
phpCAS::forceAuthentication( );

// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().

// logout if desired
if( defined( 'CAS_LOGOUT_URL_REDIRECT' ) )
{
	phpCAS::logoutWithRedirectService( CAS_LOGOUT_URL_REDIRECT );
}
$username = phpCAS::getUser( );
if( !empty( $username ) )
{
	$attribs = array(
		'identity' => md5( $username . '@' . $cas_host ),
		'result' => 'is_res',
		'id' => $username,
		'contact/email' => $username . '@openroad.vn',
		'namePerson' => $username,
		'person/gender' => '',
		'server' => $server,
		'current_mode' => 4
	);

}
else
{
	$attribs = array( 'result' => 'notlogin' );
}

$nv_Request->set_Session( 'openid_attribs', serialize( $attribs ) );

$op_redirect = ( defined( 'NV_IS_USER' )) ? 'openid' : 'login';
Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op_redirect . '&server=' . $server . '&result=1&nv_redirect=' . $nv_redirect );
exit( );