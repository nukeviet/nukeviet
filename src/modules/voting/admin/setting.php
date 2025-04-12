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

$page_title = $nv_Lang->getModule('setting');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('setting.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$array_config = [];
if ($nv_Request->isset_request('checkss', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error session!!!'
        ]);
    }

    //Đoạn xử lý lưu cấu hình ở đây
    $array_config['difftimeout'] = $nv_Request->get_int('difftimeout', 'post', 0);

    empty($array_config['difftimeout']) && $array_config['difftimeout'] = 1;
    $array_config['difftimeout'] *= 3600;

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = '" . $module_name . "' AND config_name = :config_name");
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change config module', '', $admin_info['userid']);
    $nv_Cache->delMod('settings');

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success')
    ]);
}

$array_config = $module_config[$module_name];
$array_config['difftimeout'] = round($array_config['difftimeout'] / 3600);

$tpl->assign('DATA', $array_config);

$contents = $tpl->fetch('setting.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
