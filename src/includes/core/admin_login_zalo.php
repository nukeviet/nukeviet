<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN_ACTIVE_2STEP_OAUTH')) {
    exit('Stop!!!');
}

$myZalo = new NukeViet\Zalo\MyZalo($global_config);

if ($nv_Request->isset_request('code', 'get')) {
    try {
        $code_verifier = $nv_Request->get_string('admin_code_verifier', 'session', '');
        $nv_Request->unset_request('admin_code_verifier', 'session');

        $state = $nv_Request->get_string('admin_oauth_state', 'session', '');
        $nv_Request->unset_request('admin_oauth_state', 'session');

        $state_return = $nv_Request->get_string('state', 'get', '');

        // Kiểm tra state chống CSRF
        if (empty($state) or empty($state_return) or !hash_equals($state, $state_return)) {
            $error = 'invalid_state';
        } else {
            $result = $myZalo->accesstokenGet($code_verifier, 'user');
            if (empty($result)) {
                $error = $myZalo->getError();
            } else {
                $result = $myZalo->getUserInfo($result['access_token']);
                if (empty($result['id'])) {
                    $error = $myZalo->getError();
                } else {
                    // Thành công
                    $attribs = [
                        'identity' => $result['id'],
                        'full_identity' => $crypt->hash($result['id']),
                        'email' => '',
                        'name' => $result['name'] ?? '',
                        'first_name' => '',
                        'last_name' => '',
                    ];
                }
            }
        }
    } catch (Throwable $e) {
        trigger_error($e);
        $error = $e->getMessage();
    }
} else {
    $result = $myZalo->permissionURLCreate(NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php?auth=zalo', 'user');
    if (empty($result['code_verifier']) or empty($result['permission_url'])) {
        nv_htmlOutput('permission_url_error');
    }
    $nv_Request->set_Session('admin_code_verifier', $result['code_verifier']);
    $nv_Request->set_Session('admin_oauth_state', $result['state']);
    nv_redirect_location($result['permission_url']);
}
