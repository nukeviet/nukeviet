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

use OAuth\OAuth2\Service\Facebook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

// Session storage
$storage = new Session();

$serviceFactory = new \OAuth\ServiceFactory();

// Setup the credentials for the requests
$credentials = new Credentials($global_config['facebook_client_id'], $global_config['facebook_client_secret'], NV_MAIN_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=oauth&server=facebook');

// Instantiate the Facebook service using the credentials, http client and storage mechanism for the token
$facebookService = $serviceFactory->createService('facebook', $credentials, $storage, array( 'email', 'user_photos' ));

if (!empty($_GET['code'])) {
    // This was a callback request from facebook, get the token
    $token = $facebookService->requestAccessToken($_GET['code'])->getAccessToken();

    // Send a request with it: /me?fields=id,name,email
    $result = json_decode($facebookService->request('/me?fields=id,name,email,link,first_name,last_name,gender'), true);
    if (isset($result['id'])) {
        $attribs = array(
            'identity' => $result['link'],
            'result' => 'is_res',
            'id' => $result['id'],
            'contact/email' => isset($result['email']) ? $result['email'] : '',
            'namePerson/first' => $result['first_name'],
            'namePerson/last' => $result['last_name'],
            'namePerson' => $result['name'],
            'person/gender' => $result['gender'],
            'server' => $server,
            'picture_url' => 'https://graph.facebook.com/' . $result['id'] . '/picture?width=' . $global_config['avatar_width'] . '&height=' . $global_config['avatar_height'] . '&access_token=' . $token,
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
    $url = $facebookService->getAuthorizationUri();
    nv_redirect_location($url);
}