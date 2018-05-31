<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Oct 2014 08:34:25 GMT
 */

if (! defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
}

use OAuth\OAuth2\Service\Google;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

// Session storage
$storage = new Session();

$serviceFactory = new \OAuth\ServiceFactory();

// Setup the credentials for the requests
$credentials = new Credentials($global_config['google_client_id'], $global_config['google_client_secret'], NV_MAIN_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=oauth&server=google');

// Instantiate the Google service using the credentials, http client and storage mechanism for the token
/** @var $googleService Google */
$googleService = $serviceFactory->createService('google', $credentials, $storage, array(
    'userinfo_email',
    'userinfo_profile'
));

if (!empty($_GET['code'])) {
    // This was a callback request from google, get the token
    $googleService->requestAccessToken($_GET['code']);

    // Send a request with it
    $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

    if (isset($result['email'])) {
        $attribs = array(
            'identity' => $result['link'],
            'result' => 'is_res',
            'id' => $result['id'],
            'contact/email' => $result['email'],
            'namePerson/first' => $result['family_name'],
            'namePerson/last' => $result['given_name'],
            'namePerson' => $result['name'],
            'person/gender' => $result['gender'],
            'server' => $server,
            'picture_url' => $result['picture'],
            'picture_mode' => 0, // 0: Remote picture
            'current_mode' => 3
        );
    } else {
        $attribs = array( 'result' => 'notlogin' );
    }
    $nv_Request->set_Session('openid_attribs', serialize($attribs));

    $op_redirect = (defined('NV_IS_USER')) ? 'editinfo/openid' : 'login';
    $nv_redirect_session = $nv_Request->get_title('nv_redirect_' . $module_data, 'session', '');
    $nv_redirect = '';
    if (!empty($nv_redirect_session) and nv_redirect_decrypt($nv_redirect_session) != '') {
        $nv_redirect = $nv_redirect_session;
    }
    if (!empty($nv_redirect)) {
        $nv_redirect = '&nv_redirect=' . $nv_redirect;
    }
    $nv_Request->unset_request('nv_redirect_' . $module_data, 'session');
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op_redirect . '&server=' . $server . '&result=1' . $nv_redirect);
} else {
    $url = $googleService->getAuthorizationUri();
    nv_redirect_location($url);
}