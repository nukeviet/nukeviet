<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN_2STEP_OAUTH')) {
    exit('Stop!!!');
}

$zalo = new NukeViet\Zalo\Zalo($global_config);

if (!empty($_GET['code'])) {
    try {
        $authorization_code = $_GET['code'];
        $code_verifier = !empty($_SESSION['admin_code_verifier']) ? $_SESSION['admin_code_verifier'] : '';
        unset($_SESSION['admin_code_verifier']);

        $result = $zalo->accesstokenGet($authorization_code, $code_verifier);
        if (empty($result)) {
            $error = $zalo->getError();
        } else {
            $result = $zalo->getUserInfo($result['access_token']);
            if (empty($result['id'])) {
                $error = $zalo->getError();
            } else {
                // Thành công
                $attribs = [
                    'identity' => $result['id'],
                    'full_identity' => $crypt->hash($result['id']),
                    'email' => '',
                    'name' => isset($result['name']) ? $result['name'] : '',
                    'first_name' => '',
                    'last_name' => '',
                ];
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else {
    $result = $zalo->permissionURLCreate(NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=2step&auth=zalo', 'user');
    if (empty($result['code_verifier']) or empty($result['permission_url'])) {
        exit('permission_url_error');
    }
    $_SESSION['admin_code_verifier'] = $result['code_verifier'];
    nv_redirect_location($result['permission_url']);
}
