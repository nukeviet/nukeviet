<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN_2STEP_OAUTH')) {
    exit('Stop!!!');
}

use NukeViet\OAuth\OAuth2\Facebook;

// $opt biến này có từ file gọi
$provider = new Facebook([
    'clientId' => $global_config['facebook_client_id'],
    'clientSecret' => $global_config['facebook_client_secret'],
    'redirectUri' => NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=2step&auth=facebook',
    'graphApiVersion' => Facebook::API_VERSION
]);

// Chuyển hướng đến trang đăng nhập Facebook
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
         * @var \NukeViet\OAuth\OAuth2\FacebookUser $ownerDetails
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
