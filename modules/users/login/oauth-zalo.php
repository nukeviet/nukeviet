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

$zalo = new NukeViet\Zalo\Zalo($global_config);

if ($nv_Request->isset_request('code', 'get')) {
    $authorization_code = $nv_Request->get_string('code', 'get', '');
    $code_verifier = $nv_Request->get_string('code_verifier', 'session', '');
    $nv_Request->unset_request('code_verifier', 'session');

    $result = $zalo->accesstokenGet($authorization_code, $code_verifier);
    if (empty($result)) {
        $err = $zalo->getError();
        isset($lang_module[$err]) && $err = $lang_module[$err];
        exit($err);
    }

    $result = $zalo->getUserInfo($result['access_token']);
    if (isset($result['id'])) {
        $attribs = [
            'identity' => $result['id'],
            'result' => 'is_res',
            'id' => $result['id'],
            'contact/email' => '',
            'namePerson/first' => '',
            'namePerson/last' => '',
            'namePerson' => $result['name'],
            'person/gender' => $result['gender'],
            'server' => $server,
            'picture_url' => $result['picture'],
            'picture_mode' => 0, // 0: Remote picture
            'current_mode' => 3
        ];
    } else {
        $attribs = ['result' => 'notlogin'];
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
    $nv_redirect .= '&t=' . NV_CURRENTTIME;

    $nv_Request->unset_request('nv_redirect_' . $module_data, 'session');
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op_redirect . '&server=' . $server . '&result=1' . $nv_redirect);
}

$result = $zalo->permissionURLCreate(NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=oauth&server=zalo', 'user');
if (empty($result['code_verifier']) or empty($result['permission_url'])) {
    exit('permission_url_error');
}
$nv_Request->set_Session('code_verifier', $result['code_verifier']);
nv_redirect_location($result['permission_url']);
