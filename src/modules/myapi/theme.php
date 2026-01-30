<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_API_MOD')) {
    exit('Stop!!!');
}

/**
 * Trang chính giao diện API
 *
 * @return string
 * @param mixed $type
 * @param mixed $roleCount
 * @param mixed $roleList
 * @param mixed $api_user
 * @param mixed $generate_page
 */
function main_theme($type, $roleCount, $roleList, $api_user, $generate_page): string
{
    global $nv_Lang, $module_name, $language_array;

    $page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

    $methods = [
        'password_verify' => $nv_Lang->getModule('auth_method_password_verify'),
        'md5_verify' => $nv_Lang->getModule('auth_method_md5_verify')
    ];

    foreach ($methods as $key => $name) {
        $method = $api_user[$key] ?? [];
        $method['key'] = $key;
        $method['name'] = $name;
        $method['not_access_authentication'] = empty($api_user[$key]) ? true : false;
        $methods[$key] = $method;
    }

    foreach ($roleList as &$role) {
        $role['status'] = !empty($role['status']) ? $nv_Lang->getModule('active') : $nv_Lang->getModule('inactive');
        $role['credential_status'] = (int) $role['credential_status'];
        $role['credential_status_format'] = $role['credential_status'] === 1 ? $nv_Lang->getModule('activated') : ($role['credential_status'] === 0 ? $nv_Lang->getModule('suspended') : $nv_Lang->getModule('not_activated'));
        $role['credential_addtime'] = $role['credential_addtime'] > 0 ? nv_datetime_format($role['credential_addtime']) : '';
        $role['credential_endtime'] = $role['credential_endtime'] > 0 ? nv_datetime_format($role['credential_endtime']) : ($role['credential_endtime'] == 0 ? $nv_Lang->getModule('indefinitely') : '');
        $role['credential_quota'] = $role['credential_quota'] > 0 ? nv_number_format($role['credential_quota']) : ($role['credential_quota'] == 0 ? $nv_Lang->getModule('no_quota') : '');
        $role['credential_access_count'] = $role['credential_access_count'] >= 0 ? $role['credential_access_count'] : '';
        $role['credential_last_access'] = $role['credential_last_access'] > 0 ? nv_datetime_format($role['credential_last_access']) : '';
    }
    unset($role);

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('LANGUAGE_ARRAY', $language_array);
    $tpl->assign('PAGE_URL', $page_url);
    $tpl->assign('TYPE_PUBLIC', [
        'active' => $type == 'public' ? 'active' : '',
        'url' => $page_url,
        'name' => $nv_Lang->getModule('api_role_type_public2')
    ]);
    $tpl->assign('TYPE_PRIVATE', [
        'active' => $type == 'private' ? 'active' : '',
        'url' => $page_url . '&amp;type=private',
        'name' => $nv_Lang->getModule('api_role_type_private2')
    ]);
    $tpl->assign('METHODS', $methods);
    $tpl->assign('TYPE', $type);
    $tpl->assign('ROLELIST', $roleList);
    $tpl->assign('GENERATE_PAGE', $generate_page);

    return $tpl->fetch('main.tpl');
}
