<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN_ACTIVE_2STEP_OAUTH')) {
    exit('Stop!!!');
}

use NukeViet\OAuth\OAuth2\Google;

// $opt biến này có từ file gọi
$provider = new Google([
    'clientId' => $global_config['google_client_id'],
    'clientSecret' => $global_config['google_client_secret'],
    'redirectUri' => NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php?auth=google',
]);

// Chuyển hướng đến trang đăng nhập Google
if (!$nv_Request->isset_request('code', 'get')) {
    $authorizationUrl = $provider->getAuthorizationUrl();
    $nv_Request->set_Session('oauth2state', $provider->getState());
    nv_redirect_location($authorizationUrl);
}

// Kiểm tra CSRF
if ($nv_Request->get_title('state', 'get', '') !== $nv_Request->get_title('oauth2state', 'session', '')) {
    $nv_Request->unset_request('oauth2state', 'session');
    $error = 'Invalid state!';
} else {
    try {
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $nv_Request->get_title('code', 'get', '')
        ]);
        /**
         *
         * @var \NukeViet\OAuth\OAuth2\GoogleUser $ownerDetails
         */
        $ownerDetails = $provider->getResourceOwner($token);
        $attribs = [
            'identity' => $ownerDetails->getId(),
            'full_identity' => $crypt->hash($ownerDetails->getId()),
            'email' => $ownerDetails->getEmail(),
            'name' => $ownerDetails->getName(),
            'first_name' => $ownerDetails->getFirstName(),
            'last_name' => $ownerDetails->getLastName()
        ];
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
