<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 2, 2017 2:06:56 PM
 */

if (isset($_GET['response_headers_detect'])) {
    exit(0);
}

define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;

// Default api trả về error
$apiresults = new ApiResult();

// Kiểm tra tắt Remote API
if (empty($global_config['remote_api_access'])) {
    $apiresults->setCode(ApiResult::CODE_REMOTE_OFF)->setMessage('Remote is off!!!')->returnResult();
}

$api_credential = [];
$api_credential['apikey'] = $nv_Request->get_title('apikey', 'post', '');
$api_credential['apisecret'] = $nv_Request->get_title('apisecret', 'post', '');

// Kiểm tra thông tin xác thực
$db->sqlreset()->from(NV_AUTHORS_GLOBALTABLE . '_api_credential tb1');
$db->join('INNER JOIN ' . NV_AUTHORS_GLOBALTABLE . ' tb2 ON tb1.admin_id=tb2.admin_id INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb3 ON tb1.admin_id=tb3.userid');
$db->select('tb1.admin_id, tb1.credential_secret, tb1.api_roles, tb2.lev, tb3.username');
$db->where('tb1.credential_ident=:credential_ident AND tb2.is_suspend=0 AND tb3.active=1');

try {
    $sth = $db->prepare($db->sql());
    $sth->bindParam(':credential_ident', $api_credential['apikey'], PDO::PARAM_STR);
    $sth->execute();
    $credential_data = $sth->fetch();
} catch (Exception $e) {
    $apiresults->setCode(ApiResult::CODE_SYS_ERROR)->setMessage('System error, please try again later!!!')->returnResult();
}

if (empty($credential_data)) {
    $apiresults->setCode(ApiResult::CODE_NO_CREDENTIAL_FOUND)->setMessage('No Api Credential found!!!')->returnResult();
}

if (strcmp($api_credential['apisecret'], $crypt->decrypt($credential_data['credential_secret'])) !== 0) {
    $apiresults->setCode(ApiResult::CODE_AUTH_FAIL)->setMessage('Api Authentication fail!!!')->returnResult();
}

// Cập nhật lại lần cuối sử dụng
$sql = 'UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_api_credential SET last_access=' . NV_CURRENTTIME . ' WHERE credential_ident=:credential_ident';
$sth = $db->prepare($sql);
$sth->bindParam(':credential_ident', $api_credential['apikey'], PDO::PARAM_STR);
$sth->execute();

// Thông tin Admin
Api::setAdminId($credential_data['admin_id']);
Api::setAdminLev($credential_data['lev']);
Api::setAdminName($credential_data['username']);

// Thông tin request
$api_request = [];
$api_request['action'] = $nv_Request->get_title('action', 'post', '');
$api_request['module'] = $nv_Request->get_title('module', 'post', '');

// Xác định các quyền được thiết lập trong CSDL
$credential_data['api_roles'] = array_filter(explode(',', $credential_data['api_roles']));
$credential_data['api_allowed'] = [
    '' => []
];
if (!empty($credential_data['api_roles'])) {
    $sql = 'SELECT role_data FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_role WHERE role_id IN(' . implode(',', $credential_data['api_roles']) . ')';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $row['role_data'] = unserialize($row['role_data']);
        foreach ($row['role_data'] as $sysormod => $sdata) {
            if ($sysormod == 'sys') {
                $credential_data['api_allowed'][''] = array_unique(array_merge_recursive($credential_data['api_allowed'][''], $sdata));
            } else {
                if (!isset($credential_data['api_allowed'][$sysormod])) {
                    $credential_data['api_allowed'][$sysormod] = [];
                }
                foreach ($sdata as $mod_name => $apis) {
                    if (!isset($credential_data['api_allowed'][$sysormod][$mod_name])) {
                        $credential_data['api_allowed'][$sysormod][$mod_name] = [];
                    }
                    $credential_data['api_allowed'][$sysormod][$mod_name] = array_unique(array_merge_recursive($credential_data['api_allowed'][$sysormod][$mod_name], $apis));
                }
            }
        }
    }
}

if (empty($api_request['action'])) {
    $apiresults->setCode(ApiResult::CODE_MISSING_REQUEST_CMD)->setMessage('Missing Api Command!!!')->returnResult();
} elseif (empty($api_request['module'])) {
    // Api hệ thống
    if (!in_array($api_request['action'], $credential_data['api_allowed'][''])) {
        $apiresults->setCode(ApiResult::CODE_API_NOT_EXISTS)->setMessage('Api Command Not Found!!!')->returnResult();
    }
    $classname = 'NukeViet\\Api\\' . $api_request['action'];
} else {
    // Api module theo ngôn ngữ
    if (!isset($credential_data['api_allowed'][NV_LANG_DATA])) {
        $apiresults->setCode(ApiResult::CODE_LANG_NOT_EXISTS)->setMessage('Api Lang Not Found!!!')->returnResult();
    } elseif (!isset($credential_data['api_allowed'][NV_LANG_DATA][$api_request['module']]) or !isset($sys_mods[$api_request['module']])) {
        $apiresults->setCode(ApiResult::CODE_MODULE_NOT_EXISTS)->setMessage('Api Module Not Found!!!')->returnResult();
    } elseif (!in_array($api_request['action'], $credential_data['api_allowed'][NV_LANG_DATA][$api_request['module']])) {
        $apiresults->setCode(ApiResult::CODE_API_NOT_EXISTS)->setMessage('Api Command Not Found!!!')->returnResult();
    }

    $module_info = $sys_mods[$api_request['module']];
    $module_file = $module_info['module_file'];
    $classname = 'NukeViet\\Module\\' . $module_file . '\\Api\\' . $api_request['action'];
}

// Class tồn tại
if (!class_exists($classname)) {
    $apiresults->setCode(ApiResult::CODE_API_NOT_EXISTS)->setMessage('API not exists!!!')->returnResult();
}

if (!empty($api_request['module'])) {
    /*
     * Nếu API của module kiểm tra xem admin có phải là Admin module không
     * Nếu quản trị tối cao và điều hành chung thì nghiễm nhiên có quyền quản trị module
     */
    if ($credential_data['lev'] > 2 and !in_array($credential_data['admin_id'], explode(',', $module_info['admins']))) {
        $apiresults->setCode(NukeViet\Api\ApiResult::CODE_NO_MODADMIN_RIGHT)->setMessage('Admin do not have the right to manage this module!!!')->returnResult();
    }

    // Kiểm tra quyền thực thi API theo quy định của API
    if ($classname::getAdminLev() < $credential_data['lev']) {
        $apiresults->setCode(NukeViet\Api\ApiResult::CODE_ADMINLEV_NOT_ENOUGH)->setMessage('Admin level not enough to perform this api!!!')->returnResult();
    }

    Api::setModuleName($api_request['module']);
    Api::setModuleInfo($module_info);
}

unset($credential_data, $api_request);

define('NV_ADMIN', true);
$nv_Lang->loadGlobal(true);
require NV_ROOTDIR . '/includes/core/admin_functions.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

// Thực hiện API
$api = new $classname();
$api->setResultHander($apiresults);
$return = $api->execute();

Api::reset();

Header('Cache-Control: no-cache, must-revalidate');
Header('Content-type: application/json');

if (defined('NV_ADMIN') or NV_ANTI_IFRAME != 0) {
    Header('X-Frame-Options: SAMEORIGIN');
}

Header('X-Content-Type-Options: nosniff');
Header('X-XSS-Protection: 1; mode=block');

ob_start('ob_gzhandler');
echo $return;
exit(0);
