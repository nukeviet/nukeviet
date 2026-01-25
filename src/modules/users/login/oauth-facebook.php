<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

use NukeViet\OAuth\OAuth2\Facebook;

$provider = new Facebook([
    'clientId' => $global_config['facebook_client_id'],
    'clientSecret' => $global_config['facebook_client_secret'],
    'redirectUri' => NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=oauth&server=facebook',
    'graphApiVersion' => Facebook::API_VERSION
]);

// Chuyển hướng đến trang đăng nhập Facebook
if (!$nv_Request->isset_request('code', 'get') and !$nv_Request->isset_request('error', 'get')) {
    $authorizationUrl = $provider->getAuthorizationUrl();
    $nv_Request->set_Session('oauth2state', $provider->getState());
    nv_redirect_location($authorizationUrl);
}

$attribs = ['server' => $server];

if ($nv_Request->get_title('state', 'get', '') !== $nv_Request->get_title('oauth2state', 'session', '')) {
    // Kiểm tra CSRF
    $nv_Request->unset_request('oauth2state', 'session');
    $nv_Request->unset_request('openid_attribs', 'session');
    $attribs['result'] = 'notlogin';
} elseif ($nv_Request->isset_request('error', 'get')) {
    // Khi người dùng từ chối cấp quyền
    $attribs['result'] = 'cancel';
} else {
    // Thực hiện lấy thông tin người dùng
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
            'identity' => $ownerDetails->getLink() ?: $ownerDetails->getId(),
            'result' => 'is_res',
            'id' => $ownerDetails->getId(),
            'contact/email' => $ownerDetails->getEmail(),
            'namePerson/first' => $ownerDetails->getFirstName(),
            'namePerson/last' => $ownerDetails->getLastName(),
            'namePerson' => $ownerDetails->getName(),
            'person/gender' => $ownerDetails->getGender(),
            'server' => $server,
            'picture_url' => 'https://graph.facebook.com/' . $ownerDetails->getId() . '/picture?width=' . $global_config['avatar_width'] . '&height=' . $global_config['avatar_height'] . '&access_token=' . $token->getToken(),
            'picture_mode' => 0, // 0: Remote picture
            'current_mode' => 3
        ];
    } catch (Exception $e) {
        $attribs['result'] = 'notlogin';
        trigger_error($e->getMessage());
    }
}

$nv_Request->set_Session('openid_attribs', json_encode($attribs));

$op_redirect = (defined('NV_IS_USER')) ? 'editinfo/openid' : 'login';
$nv_redirect_session = $nv_Request->get_title('nv_redirect_' . $module_data, 'session', '');
$nv_redirect = '';
if (!empty($nv_redirect_session) and nv_redirect_decrypt($nv_redirect_session) != '') {
    $nv_redirect = $nv_redirect_session;
}
if (!empty($nv_redirect)) {
    $nv_redirect = '&nv_redirect=' . $nv_redirect;
}
$nv_redirect .= '&t=' . NV_CURRENTTIME;

$nv_Request->unset_request('nv_redirect_' . $module_data, 'session');
nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op_redirect . '&server=' . $server . '&result=1' . $nv_redirect);
