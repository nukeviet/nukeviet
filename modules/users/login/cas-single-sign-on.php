<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Oct 2014 08:34:25 GMT
 */

if( !defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

// Load the CAS lib
include NV_ROOTDIR . '/modules/users/CAS.php';

// Enable debugging
phpCAS::setDebug();

// Initialize phpCAS
phpCAS::client( $global_config['config_sso']['cas_version'], $global_config['config_sso']['cas_hostname'], $global_config['config_sso']['cas_port'], $global_config['config_sso']['cas_baseuri'] );

// For production use set the CA certificate that is the issuer of the cert
// on the CAS server and uncomment the line below
// phpCAS::setCasServerCACert($global_config['config_sso']['cas_certificate_path']);

// For quick testing you can disable SSL validation of the CAS server.
// THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
// VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
phpCAS::setNoCasServerValidation();

// set the language to french
//phpCAS::setLang(PHPCAS_LANG_FRENCH);

// force CAS authentication
phpCAS::forceAuthentication();

// logout if desired
if( defined( 'CAS_LOGOUT_URL_REDIRECT' ) )
{
	phpCAS::logoutWithRedirectService( CAS_LOGOUT_URL_REDIRECT );
}
$username = phpCAS::getUser();
if( !empty( $username ) )
{
	if( nv_function_exists( 'ldap_connect' ) )
	{
		$ldapconn = ldap_connect( $global_config['config_sso']['ldap_host_url'] );
		ldap_set_option( $ldapconn, LDAP_OPT_PROTOCOL_VERSION, $global_config['config_sso']['ldap_version'] );
    	ldap_set_option( $ldapconn, LDAP_OPT_REFERRALS, 0);
		if( !empty( $global_config['config_sso']['ldap_bind_dn'] ) and !empty( $global_config['config_sso']['ldap_bind_pw'] ) )
		{
			$ldapbind = ldap_bind( $ldapconn, $global_config['config_sso']['ldap_bind_dn'], $global_config['config_sso']['ldap_bind_pw'] );
		}
		else
		{
			$ldapbind = ldap_bind( $ldapconn );
		}
		if( $ldapbind ) // verify binding
		{
			$result = ldap_search( $ldapconn, $global_config['config_sso']['user_contexts'], '(uid=' . $username . ')' );
			$data = ldap_get_entries( $ldapconn, $result );

			$attribs = array(
				'identity' => md5( $username . '@' . $cas_host ),
				'result' => 'is_res',
				'id' => $username,
				'server' => $server,
				'current_mode' => 4
			);

			foreach( $global_config['config_sso']['config_field'] as $key => $ckey )
			{
				if( !empty( $ckey ) and isset( $data[0][$ckey] ) )
				{
					$attribs[$key] = $data[0][$ckey][0];
				}
			}
			if( isset( $attribs['email'] ) )
			{
				$attribs['contact/email'] = $attribs['email'];
				unset( $attribs['email'] );
			}

			if( isset( $attribs['firstname'] ) )
			{
				$attribs['namePerson/first'] = $attribs['firstname'];
				unset( $attribs['firstname'] );
			}
			if( isset( $attribs['lastname'] ) )
			{
				$attribs['namePerson/last'] = $attribs['lastname'];
				unset( $attribs['lastname'] );
			}

			if( isset( $attribs['gender'] ) )
			{
				$attribs['person/gender'] = $attribs['gender'];
				unset( $attribs['gender'] );
			}
		}
		ldap_close( $ldapconn );
	}
}
else
{
	$attribs = array( 'result' => 'notlogin' );
}

$nv_Request->set_Session( 'openid_attribs', serialize( $attribs ) );

$op_redirect = ( defined( 'NV_IS_USER' )) ? 'openid' : 'login';
Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op_redirect . '&server=' . $server . '&result=1&nv_redirect=' . $nv_redirect );
exit();