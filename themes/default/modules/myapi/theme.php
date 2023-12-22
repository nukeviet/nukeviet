<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_API_MOD')) {
    exit('Stop!!!');
}

/**
 * main_theme()
 *
 * @return string
 * @param mixed $type
 * @param mixed $roleCount
 * @param mixed $roleList
 * @param mixed $api_user
 * @param mixed $generate_page
 */
function main_theme($type, $roleCount, $roleList, $api_user, $generate_page)
{
    global $nv_Lang, $module_name, $site_mods, $global_config, $language_array;

    $page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

    $roleList = array_map(function ($item) {
        $item['credential_addtime_format'] = $item['credential_addtime'] > 0 ? nv_date('d/m/Y H:i', $item['credential_addtime']) : '';
        $item['credential_endtime_format'] = $item['credential_endtime'] > 0 ? nv_date('d/m/Y H:i', $item['credential_endtime']) : '';
        $item['credential_last_access_format'] = $item['credential_last_access'] > 0 ? nv_date('d/m/Y H:i', $item['credential_last_access']) : '';

        return $item;
    }, $roleList);
    $methods = [
        'password_verify' => [
            'key' => 'password_verify',
            'name' => $nv_Lang->getModule('auth_method_password_verify'),
            'ident' => '',
            'ips' => '',
            'not_access_authentication' => true
        ],
        'md5_verify' => [
            'key' => 'md5_verify',
            'name' => $nv_Lang->getModule('auth_method_md5_verify'),
            'ident' => '',
            'ips' => '',
            'not_access_authentication' => true
        ]
    ];
    if (isset($api_user['password_verify'])) {
        $methods['password_verify'] = array_merge($methods['password_verify'], $api_user['password_verify']);
        $methods['password_verify']['not_access_authentication'] = false;
    }
    if (isset($api_user['md5_verify'])) {
        $methods['md5_verify'] = array_merge($methods['md5_verify'], $api_user['md5_verify']);
        $methods['md5_verify']['not_access_authentication'] = false;
    }

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('PAGE_URL', $page_url);
    $stpl->assign('METHODS', $methods);
    $stpl->assign('TYPE', $type);
    $stpl->assign('ROLECOUNT', $roleCount);
    $stpl->assign('ROLELIST', $roleList);
    $stpl->assign('SETUP_LANGS', $global_config['setup_langs']);
    $stpl->assign('LANGUAGE_ARRAY', $language_array);
    $stpl->assign('SITE_MODS', $site_mods);
    $stpl->assign('GENERATE_PAGE', $generate_page);

    return $stpl->fetch('main.tpl');
}
