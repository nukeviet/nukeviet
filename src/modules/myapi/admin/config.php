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

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($nv_Request->isset_request('checkss', 'post')) {
    if ($checkss == $nv_Request->get_string('checkss', 'post')) {
        $array_config_global = [
            'remote_api_access' => (int) $nv_Request->get_bool('remote_api_access', 'post', false),
            'api_check_time' => $nv_Request->get_absint('api_check_time', 'post', 0)
        ];

        // Cho phép sai lệch từ 1 giây - 1 ngày
        if ($array_config_global['api_check_time'] <= 0 or $array_config_global['api_check_time'] > 1440) {
            $array_config_global['api_check_time'] = 5;
        }
    
        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
        foreach ($array_config_global as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }
        nv_save_file_config_global();
        nv_jsonOutput(
            [
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success')
            ]
        );
    } else {
        nv_jsonOutput(
            [
                'status' => 'NO',
                'mess' => $nv_Lang->getGlobal('error_code_11')
            ]
        );
    }
}

$page_title = $nv_Lang->getModule('config');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', $checkss);
$tpl->assign('DATA', $global_config);

$contents = $tpl->fetch('config.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
