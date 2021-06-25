<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

$_cas_config = unserialize($global_config['config_sso']);

// Enable debugging
phpCAS::setDebug();

// Initialize phpCAS
phpCAS::client($_cas_config['cas_version'], $_cas_config['cas_hostname'], $_cas_config['cas_port'], $_cas_config['cas_baseuri']);

// For production use set the CA certificate that is the issuer of the cert
// on the CAS server and uncomment the line below
// phpCAS::setCasServerCACert($_cas_config['cas_certificate_path']);

// For quick testing you can disable SSL validation of the CAS server.
// THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
// VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
phpCAS::setNoCasServerValidation();

// set the language to french
//phpCAS::setLang(PHPCAS_LANG_FRENCH);

phpCAS::handleLogoutRequests(false); // https://wiki.jasig.org/display/casum/single+sign+out#SingleSignOut-Howitworks

// force CAS authentication
phpCAS::forceAuthentication();

// logout if desired
if (defined('CAS_LOGOUT_URL_REDIRECT')) {
    phpCAS::logoutWithRedirectService(CAS_LOGOUT_URL_REDIRECT);
}
$username = phpCAS::getUser();
if (!empty($username)) {
    if (nv_function_exists('ldap_connect')) {
        $ldapconn = ldap_connect($_cas_config['ldap_host_url']);
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, $_cas_config['ldap_version']);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
        if (!empty($_cas_config['ldap_bind_dn']) and !empty($_cas_config['ldap_bind_pw'])) {
            $ldapbind = ldap_bind($ldapconn, $_cas_config['ldap_bind_dn'], $_cas_config['ldap_bind_pw']);
        } else {
            $ldapbind = ldap_bind($ldapconn);
        }
        if ($ldapbind) {
            // verify binding

            $result = ldap_search($ldapconn, $_cas_config['user_contexts'], '(uid=' . $username . ')');
            $data = ldap_get_entries($ldapconn, $result);

            $attribs = [
                'identity' => md5($username . '@' . $cas_host),
                'result' => 'is_res',
                'id' => $username,
                'server' => $server,
                'current_mode' => 4
            ];

            foreach ($_cas_config['config_field'] as $key => $ckey) {
                if (!empty($ckey) and isset($data[0][$ckey])) {
                    $attribs[$key] = $data[0][$ckey][0];
                }
            }
            if (isset($attribs['email'])) {
                $attribs['contact/email'] = $attribs['email'];
                unset($attribs['email']);
            }

            if (isset($attribs['firstname'])) {
                $attribs['namePerson/first'] = $attribs['firstname'];
                unset($attribs['firstname']);
            }
            if (isset($attribs['lastname'])) {
                $attribs['namePerson/last'] = $attribs['lastname'];
                unset($attribs['lastname']);
            }

            if (isset($attribs['gender'])) {
                $attribs['person/gender'] = $attribs['gender'];
                unset($attribs['gender']);
            }
        }
        ldap_close($ldapconn);
    }
} else {
    $attribs = [
        'result' => 'notlogin'
    ];
}

$nv_Request->set_Session('openid_attribs', serialize($attribs));

$op_redirect = (defined('NV_IS_USER')) ? 'editinfo/openid' : 'login';
$nv_redirect = nv_get_redirect();
if (!empty($nv_redirect)) {
    $nv_redirect = '&nv_redirect=' . $nv_redirect;
}
nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op_redirect . '&server=' . $server . '&result=1' . $nv_redirect);
