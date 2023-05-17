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
$db->select('tb1.userid, tb1.secret, tb1.ips, tb1.method, IFNULL(tb2.lev, -1) AS lev, IFNULL(tb2.is_suspend, -1) AS is_suspend, tb3.username, tb3.in_groups');
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
            ->setMessage('Api IP ' . NV_CLIENT_IP . ' fail !!!')
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
$flood_rules = [];
$log_period_list = [];
$time_wrong = [];
$quota_exhausted = [];

$sql = 'SELECT tb1.addtime, tb1.endtime, tb1.access_count, tb1.quota, tb2.role_id, tb2.role_object, tb2.role_data, tb2.log_period, tb2.flood_rules FROM ' . $db_config['prefix'] . '_api_role_credential tb1 INNER JOIN ' . $db_config['prefix'] . '_api_role tb2 ON (tb1.role_id=tb2.role_id  AND tb2.status = 1) WHERE (tb1.userid = ' . $credential_data['userid'] . ' AND tb1.status = 1)';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    !empty($row['log_period']) && $log_period_list[] = $row['role_id'];
    ((int) $row['addtime'] > NV_CURRENTTIME or (!empty($row['endtime']) and (int) $row['endtime'] < NV_CURRENTTIME)) && $time_wrong[] = $row['role_id'];
    (!empty($row['quota']) and (int) $row['access_count'] >= (int) $row['quota']) && $quota_exhausted[] = $row['role_id'];
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
                    if (!empty($row['flood_rules']) and $row['flood_rules'] !== '[]') {
                        !isset($flood_rules[$k]) && $flood_rules[$k] = [];
                        $flood_rules[$k][] = [$row['flood_rules'], $row['role_id']];
                    }
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
                        if (!empty($row['flood_rules']) and $row['flood_rules'] !== '[]') {
                            !isset($flood_rules[$k]) && $flood_rules[$k] = [];
                            $flood_rules[$k][] = [$row['flood_rules'], $row['role_id']];
                        }
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
    $my_flood_rules = !empty($flood_rules[$testObject]) ? $flood_rules[$testObject] : [];
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
        define('NV_ADMIN', true);
    }

    $module_info = $sys_mods[$api_request['module']];
    $module_file = $module_info['module_file'];
    $role_id = $roles[$testObject];
    $my_flood_rules = !empty($flood_rules[$testObject]) ? $flood_rules[$testObject] : [];
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
    $check_in_groups = nv_user_groups($credential_data['in_groups'], true);
    Uapi::setUserGroups($check_in_groups[0]);

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

// Kiểm tra flood blocker
if (!empty($my_flood_rules)) {
    foreach ($my_flood_rules as $dt) {
        list($flood_rules, $flood_role_id) = $dt;
        $flood_rules = json_decode($flood_rules, true);

        $select = [];
        foreach (array_keys($flood_rules) as $interval) {
            $select[] = 'COUNT(CASE WHEN log_time >= ' . (NV_CURRENTTIME - $interval) . ' THEN 1 END) AS count' . $interval;
        }
        $sql = 'SELECT ' . implode(', ', $select) . ' FROM ' . $db_config['prefix'] . '_api_role_logs WHERE role_id = ' . $flood_role_id . ' AND userid = ' . $credential_data['userid'];
        $countlist = $db->query($sql)->fetch();
        if (!empty($countlist)) {
            foreach ($flood_rules as $interval => $limit) {
                if (!empty($countlist['count' . $interval])) {
                    if ((int) $countlist['count' . $interval] >= $limit) {
                        $apiresults->setCode(ApiResult::CODE_REQUEST_LIMIT_EXCEEDED)
                            ->setMessage('Request limit exceeded!!!')
                            ->returnResult();
                    }
                }
            }
        }
    }
}

// Cập nhật CSDL
$role_id = array_unique($role_id);
$values = [];
$is_time_wrong = true;
$is_quota_exhausted = true;
foreach ($role_id as $rid) {
    if (in_array($rid, $log_period_list, true)) {
        $values[] = '(' . $rid . ', ' . $credential_data['userid'] . ', ' . $db->quote($api_request['action']) . ', ' . NV_CURRENTTIME . ', ' . $db->quote($client_info['ip']) . ')';
    }

    if (!in_array($rid, $time_wrong, true)) {
        $is_time_wrong = false;
    }

    if (!in_array($rid, $quota_exhausted, true)) {
        $is_quota_exhausted = false;
    }
}

// Nếu thời gian bắt đầu hoặc kết thúc không phù hợp
if ($is_time_wrong) {
    $apiresults->setCode(ApiResult::CODE_WRONG_TIME)
        ->setMessage('API request at wrong time !!!')
        ->returnResult();
}

// Nếu hết hạn ngạch
if ($is_quota_exhausted) {
    $apiresults->setCode(ApiResult::CODE_QUOTA_EXHAUSTED)
        ->setMessage('Quota exhausted !!!')
        ->returnResult();
}

if (!empty($values)) {
    $values = implode(', ', $values);
    $db->query('INSERT IGNORE INTO ' . $db_config['prefix'] . '_api_role_logs (role_id, userid, command, log_time, log_ip) VALUES ' . $values);
}

$role_id = implode(',', $role_id);
$db->query('UPDATE ' . $db_config['prefix'] . '_api_role_credential SET access_count = access_count + 1, last_access = ' . NV_CURRENTTIME . ' WHERE userid = ' . $credential_data['userid'] . ' AND role_id IN (' . $role_id . ')');
$db->query('UPDATE ' . $db_config['prefix'] . '_api_user SET last_access = ' . NV_CURRENTTIME . ' WHERE userid = ' . $credential_data['userid']);

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
