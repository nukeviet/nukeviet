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

$array_url_instruction['config'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:users:oauth#c%E1%BA%A5u_hinh_v%E1%BB%9Bi_oauth_google';

if ($nv_Request->isset_request('save', 'post')) {
    $array_config['oauth_client_id'] = (string) $nv_Request->get_title('oauth_client_id', 'post', '');
    if ($checkss !== $nv_Request->get_string('checkss', 'post')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Session error!'
        ]);
    }
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");

    $sth->bindValue(':config_name', 'google_client_id', PDO::PARAM_STR);
    $sth->bindParam(':config_value', $array_config['oauth_client_id'], PDO::PARAM_STR);
    $sth->execute();

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('config'), $page_title, $admin_info['userid']);
    $nv_Cache->delAll();

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

$array_config['oauth_client_id'] = $global_config['google_client_id'];
$array_config['checkss'] = $checkss;

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config_oauth_google-identity.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;oauth_config=' . $oauth_config);
$tpl->assign('DATA', $array_config);

$contents = $tpl->fetch('config_oauth_google-identity.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
