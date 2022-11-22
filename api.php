<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_SYSTEM', true);
define('NV_REMOTE_API', true);

// Xác định thư mục gốc của site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Uapi\Uapi;
use NukeViet\Uapi\UapiResult;

// Default api trả về error
$apiresults = new ApiResult();
$uapiresults = new UapiResult();

// Kiểm tra tắt Remote API
if (empty($global_config['remote_api_access'])) {
    $apiresults->setCode(ApiResult::CODE_REMOTE_OFF)
        ->setMessage('Remote is off!!!')
        ->returnResult();
}

$api_credential = [];
$api_credential['apikey'] = $nv_Request->get_title('apikey', 'post', '');
$api_credential['timestamp'] = $nv_Request->get_int('timestamp', 'post', '');
$api_credential['hashsecret'] = $nv_Request->get_string('hashsecret', 'post', '');
if ($api_credential['timestamp'] + 5 < NV_CURRENTTIME or $api_credential['timestamp'] - 5 > NV_CURRENTTIME) {
    // Sai lệch thời gian hơn 5 giây
    $apiresults->setCode(ApiResult::CODE_MISSING_TIME)
        ->setMessage('Incorrect API time: ' . date('H:i:s d/m/Y', $api_credential['timestamp']) . ', Server time: ' . date('H:i:s d/m/Y', NV_CURRENTTIME))
        ->returnResult();
}

// Kiểm tra thông tin xác thực
$db->sqlreset()->from($db_config['prefix'] . '_api_user tb1');
$db->join('LEFT JOIN ' . NV_AUTHORS_GLOBALTABLE . ' tb2 ON tb1.userid=tb2.admin_id INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb3 ON tb1.userid=tb3.userid');
$db->select('tb1.userid, tb1.secret, tb1.ips, tb1.method, IFNULL(tb2.lev, -1) AS lev, IFNULL(tb2.is_suspend, -1) AS is_suspend, tb3.username');
$db->where('tb1.ident=:ident AND tb3.active=1');

try {
    $sth = $db->prepare($db->sql());
    $sth->bindParam(':ident', $api_credential['apikey'], PDO::PARAM_STR);
    $sth->execute();
    $credential_data = $sth->fetch();
} catch (Exception $e) {
    $apiresults->setCode(ApiResult::CODE_SYS_ERROR)
        ->setMessage('System error, please try again later !!!')
        ->returnResult();
}

if (empty($credential_data)) {
    $apiresults->setCode(ApiResult::CODE_NO_CREDENTIAL_FOUND)
        ->setMessage('No Api Credential found !!!')
        ->returnResult();
}

$credential_ips = json_decode($credential_data['ips'], true);
if (!empty($credential_ips)) {
    if (!in_array(NV_CLIENT_IP, $credential_ips, true)) {
        $apiresults->setCode(ApiResult::CODE_MISSING_IP)
            ->setMessage('Api IP fail !!! ')
            ->returnResult();
    }
}

if ((int) $credential_data['is_suspend'] > 0) {
    $apiresults->setCode(ApiResult::CODE_ADMIN_SUSPEND)
        ->setMessage('Admin is suspended !!!')
        ->returnResult();
}

$apisecret = $crypt->decrypt($credential_data['secret']);
if ($credential_data['method'] == 'password_verify' and !password_verify($apisecret . '_' . $api_credential['timestamp'], $api_credential['hashsecret'])) {
    $apiresults->setCode(ApiResult::CODE_AUTH_FAIL)
        ->setMessage('Api Authentication fail !!! ')
        ->returnResult();
} elseif ($credential_data['method'] == 'md5_verify' and md5($apisecret . '_' . $api_credential['timestamp']) != $api_credential['hashsecret']) {
    $apiresults->setCode(ApiResult::CODE_AUTH_FAIL)
        ->setMessage('Api Authentication fail !!! ')
        ->returnResult();
}

// Thông tin request
$api_request = [];
$api_request['action'] = $nv_Request->get_title('action', 'post', '');
$api_request['module'] = $nv_Request->get_title('module', 'post', '');
$api_request['language'] = $nv_Request->get_title(NV_LANG_VARIABLE, 'post', '');

if (empty($api_request['action'])) {
    $apiresults->setCode(ApiResult::CODE_MISSING_REQUEST_CMD)
        ->setMessage('Missing Api Command!!!')
        ->returnResult();
}

// Nếu site đa ngôn ngữ bắt buộc phải truyền tham số language
if (sizeof($global_config['allow_sitelangs']) > 1 and empty($api_request['language'])) {
    $apiresults->setCode(ApiResult::CODE_MISSING_LANG)
        ->setMessage('Lang Data is required for multi-language website!!!')
        ->returnResult();
} elseif (!empty($api_request['language']) and NV_LANG_DATA != $api_request['language']) {
    $apiresults->setCode(ApiResult::CODE_WRONG_LANG)
        ->setMessage('Wrong Lang Data!!!')
        ->returnResult();
}

// Xác định các quyền được thiết lập trong CSDL
$credential_data['api_allowed'] = [
    '' => []
];
$forAdmin = [];
$roles = [];

$sql = 'SELECT tb2.role_id, tb2.role_object, tb2.role_data FROM ' . $db_config['prefix'] . '_api_role_credential tb1 INNER JOIN ' . $db_config['prefix'] . '_api_role tb2 ON (tb1.role_id=tb2.role_id  AND tb2.status = 1) WHERE (tb1.userid = ' . $credential_data['userid'] . ' AND tb1.status = 1)';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $row['role_data'] = json_decode($row['role_data'], true);
    if (!empty($row['role_data'])) {
        foreach ($row['role_data'] as $sysormod => $sdata) {
            if ($sysormod == 'sys') {
                $credential_data['api_allowed'][''] = array_unique(array_merge_recursive($credential_data['api_allowed'][''], $sdata));
                foreach ($sdata as $ele) {
                    $k = 'sys|' . $ele;
                    if ($row['role_object'] == 'admin') {
                        $forAdmin[] = $k;
                    }
                    !isset($roles[$k]) && $roles[$k] = [];
                    $roles[$k][] = $row['role_id'];
                }
            } else {
                if (!isset($credential_data['api_allowed'][$sysormod])) {
                    $credential_data['api_allowed'][$sysormod] = [];
                }
                foreach ($sdata as $mod_name => $apis) {
                    if (!isset($credential_data['api_allowed'][$sysormod][$mod_name])) {
                        $credential_data['api_allowed'][$sysormod][$mod_name] = [];
                    }
                    $credential_data['api_allowed'][$sysormod][$mod_name] = array_unique(array_merge_recursive($credential_data['api_allowed'][$sysormod][$mod_name], $apis));
                    foreach ($apis as $ele) {
                        $k = $sysormod . '|' . $mod_name . '|' . $ele;
                        if ($row['role_object'] == 'admin') {
                            $forAdmin[] = $k;
                        }
                        !isset($roles[$k]) && $roles[$k] = [];
                        $roles[$k][] = $row['role_id'];
                    }
                }
            }
        }
    }
}

$adminLev = false;
if (empty($api_request['module'])) {
    // Api hệ thống
    if (!in_array($api_request['action'], $credential_data['api_allowed'][''], true)) {
        $apiresults->setCode(ApiResult::CODE_API_NOT_EXISTS)
            ->setMessage('Api Command Not Found!!!')
            ->returnResult();
    }

    $testObject = 'sys|' . $api_request['action'];
    $apidir = 'Uapi';
    if (!empty($forAdmin) and in_array($testObject, $forAdmin, true)) {
        if ((int) $credential_data['lev'] <= 0) {
            $apiresults->setCode(ApiResult::CODE_NO_ADMIN_FOUND)
                ->setMessage('You are not admin !!!')
                ->returnResult();
        }

        $adminLev = true;
        $apidir = 'Api';
        define('NV_ADMIN', true);
    }

    $role_id = $roles[$testObject];
    $classname = 'NukeViet\\' . $apidir . '\\' . $api_request['action'];
} else {
    // Api module theo ngôn ngữ
    if (!isset($credential_data['api_allowed'][NV_LANG_DATA])) {
        $apiresults->setCode(ApiResult::CODE_LANG_NOT_EXISTS)
            ->setMessage('Api Lang Not Found!!!')
            ->returnResult();
    }

    if (!isset($credential_data['api_allowed'][NV_LANG_DATA][$api_request['module']]) or !isset($sys_mods[$api_request['module']])) {
        $apiresults->setCode(ApiResult::CODE_MODULE_NOT_EXISTS)
            ->setMessage('Api Module Not Found!!!')
            ->returnResult();
    }

    if (!in_array($api_request['action'], $credential_data['api_allowed'][NV_LANG_DATA][$api_request['module']], true)) {
        $apiresults->setCode(ApiResult::CODE_API_NOT_EXISTS)
            ->setMessage('Api Command Not Found!!!')
            ->returnResult();
    }

    $testObject = NV_LANG_DATA . '|' . $api_request['module'] . '|' . $api_request['action'];
    $apidir = 'uapi';
    if (!empty($forAdmin) and in_array($testObject, $forAdmin, true)) {
        if ((int) $credential_data['lev'] <= 0) {
            $apiresults->setCode(ApiResult::CODE_NO_ADMIN_FOUND)
                ->setMessage('You are not admin !!!')
                ->returnResult();
        }

        $adminLev = true;
        $apidir = 'Api';
    }

    $module_info = $sys_mods[$api_request['module']];
    $module_file = $module_info['module_file'];
    $role_id = $roles[$testObject];
    $classname = 'NukeViet\\Module\\' . $module_file . '\\' . $apidir . '\\' . $api_request['action'];
}

// Class tồn tại
if (!class_exists($classname)) {
    $apiresults->setCode(ApiResult::CODE_API_NOT_EXISTS)
        ->setMessage('API not exists!!!')
        ->returnResult();
}

if ($adminLev) {
    if (!empty($api_request['module'])) {
        /*
         * Nếu API của module kiểm tra xem admin có phải là Admin module không
         * Nếu quản trị tối cao và điều hành chung thì nghiễm nhiên có quyền quản trị module
         */
        if ($credential_data['lev'] > 2 and !in_array((int) $credential_data['userid'], array_map('intval', explode(',', $module_info['admins'])), true)) {
            $apiresults->setCode(ApiResult::CODE_NO_MODADMIN_RIGHT)
                ->setMessage('Admin do not have the right to manage this module!!!')
                ->returnResult();
        }

        // Kiểm tra quyền thực thi API theo quy định của API
        if ($classname::getAdminLev() < $credential_data['lev']) {
            $apiresults->setCode(ApiResult::CODE_ADMINLEV_NOT_ENOUGH)
                ->setMessage('Admin level not enough to perform this api!!!')
                ->returnResult();
        }

        Api::setModuleName($api_request['module']);
        Api::setModuleInfo($module_info);

        // Ngôn ngữ admin của module nếu API của module
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/admin_' . NV_LANG_INTERFACE . '.php')) {
            require NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/admin_' . NV_LANG_INTERFACE . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/admin_' . NV_LANG_DATA . '.php')) {
            require NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/admin_' . NV_LANG_DATA . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/admin_en.php')) {
            require NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/admin_en.php';
        }
    }

    // Thông tin Admin
    Api::setAdminId($credential_data['userid']);
    Api::setAdminLev($credential_data['lev']);
    Api::setAdminName($credential_data['username']);

    // Ngôn ngữ Global
    if (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_global.php')) {
        require NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_global.php';
    } elseif (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_global.php')) {
        require NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_global.php';
    } elseif (file_exists(NV_ROOTDIR . '/includes/language/en/admin_global.php')) {
        require NV_ROOTDIR . '/includes/language/en/admin_global.php';
    }
} else {
    // Thông tin User
    Uapi::setUserId($credential_data['userid']);
    Uapi::setUserName($credential_data['username']);

    if (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/global.php')) {
        require NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/global.php';
    } elseif (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/global.php')) {
        require NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/global.php';
    } elseif (file_exists(NV_ROOTDIR . '/includes/language/en/global.php')) {
        require NV_ROOTDIR . '/includes/language/en/global.php';
    }

    if (!empty($api_request['module'])) {
        Uapi::setModuleName($api_request['module']);
        Uapi::setModuleInfo($module_info);

        // Ngôn ngữ của module nếu API của module
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php')) {
            require NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/' . NV_LANG_DATA . '.php')) {
            require NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/' . NV_LANG_DATA . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/en.php')) {
            require NV_ROOTDIR . '/modules/' . $module_info['module_file'] . '/language/en.php';
        }
    }
}

// Cập nhật lại lần cuối sử dụng
$role_id = array_unique($role_id);
$role_id = implode(',', $role_id);
$db->query('UPDATE ' . $db_config['prefix'] . '_api_role_credential SET access_count = access_count + 1, last_access = ' . NV_CURRENTTIME . ' WHERE userid = ' . $credential_data['userid'] . ' AND role_id IN (' . $role_id . ')');
$db->query('UPDATE ' . $db_config['prefix'] . '_api_user SET last_access = ' . NV_CURRENTTIME . ' WHERE userid = ' . $credential_data['userid']);

// Ghi nhật ký
if (!empty($global_config['remote_api_log'])) {
    nv_insert_logs(NV_LANG_DATA, $api_request['module'], 'LOG_REMOTE_API_REQUEST', 'Command: ' . $api_request['action'], $credential_data['userid']);
}

unset($credential_data, $api_request, $role_id, $forAdmin, $roles);

$adminLev && require NV_ROOTDIR . '/includes/core/admin_functions.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

// Thực hiện API
$api = new $classname();
if ($adminLev) {
    $api->setResultHander($apiresults);
    $return = $api->execute();
    Api::reset();
} else {
    $api->setResultHander($uapiresults);
    $return = $api->execute();
    Uapi::reset();
}

header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json');

if (defined('NV_ADMIN') or NV_ANTI_IFRAME != 0) {
    header('X-Frame-Options: SAMEORIGIN');
}

header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('X-Robots-Tag: noindex, nofollow');

ob_start('ob_gzhandler');
echo $return;
exit(0);
