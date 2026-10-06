<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    // OAID và App ID của Zalo là chuỗi số, khóa bí mật chỉ gồm chữ, số, gạch dưới và gạch ngang
    $array_config = [];
    $array_config['zaloOfficialAccountID'] = preg_replace('/[^0-9]/', '', $nv_Request->get_title('zaloOfficialAccountID', 'post', ''));
    $array_config['zaloAppID'] = preg_replace('/[^0-9]/', '', $nv_Request->get_title('zaloAppID', 'post', ''));
    $array_config['zaloAppSecretKey'] = preg_replace('/[^a-zA-Z0-9\_\-]/', '', $nv_Request->get_title('zaloAppSecretKey', 'post', ''));

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindValue(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindValue(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('config'), $page_title, $admin_info['userid']);
    $nv_Cache->delAll();

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

$array_config = [
    'zaloOfficialAccountID' => $global_config['zaloOfficialAccountID'] ?? '',
    'zaloAppID' => $global_config['zaloAppID'] ?? '',
    'zaloAppSecretKey' => $global_config['zaloAppSecretKey'] ?? '',
    'checkss' => csrf_create($csrf_key)
];

// Hướng dẫn tạo OA và ứng dụng Zalo, kèm Home URL và Callback URL cần khai báo
$nv_Lang->setModule('zalo_oa_note', $nv_Lang->getModule('zalo_oa_note', 'https://oa.zalo.me/manage/oa?option=create', 'https://oa.zalo.me/manage/oa'));
$nv_Lang->setModule('zalo_app_note', $nv_Lang->getModule('zalo_app_note', 'https://developers.zalo.me/createapp', 'https://developers.zalo.me/apps', NV_MY_DOMAIN, NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php', NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php'));

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config_oauth_zalo.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;oauth_config=' . $oauth_config);
$tpl->assign('DATA', $array_config);

$contents = $tpl->fetch('config_oauth_zalo.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
