<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/30/2009 1:31
 */

if (!defined('NV_ADMIN_2STEP_OAUTH')) {
    die('Stop!!!');
}

// $opt biến này có từ file gọi

use OAuth\ServiceFactory;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

$storage = new Session();
$serviceFactory = new ServiceFactory();
$credentials = new Credentials($global_config['google_client_id'], $global_config['google_client_secret'], NV_MAIN_DOMAIN . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=2step&auth=google');
$googleService = $serviceFactory->createService('google', $credentials, $storage, ['userinfo_email', 'userinfo_profile']);

if (!empty($_GET['code'])) {
    try {
        $googleService->requestAccessToken($_GET['code']);
        $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

        if (is_array($result) and !empty($result['id']) and !empty($result['email'])) {
            $result['email'] = nv_check_valid_email($result['email'], true);
            if (!empty($result['email'][0])) {
                // Kiểm tra email hợp lệ
                $error = $lang_global['admin_oauth_error_email'];
            } else {
                // Thành công
                $attribs = [
                    'identity' => $result['id'],
                    'full_identity' => $crypt->hash($result['id']),
                    'email' => $result['email'][1],
                    'name' => isset($result['name']) ? $result['name'] : '',
                    'first_name' => isset($result['given_name']) ? $result['given_name'] : '',
                    'last_name' => isset($result['family_name']) ? $result['family_name'] : '',
                ];
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else {
    $url = $googleService->getAuthorizationUri();
    nv_redirect_location($url);
}
