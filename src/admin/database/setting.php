<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getGlobal('mod_settings');
$array_sql_ext = ['sql', 'gz'];

$array_config_global = [];
$array_config_global['dump_backup_day'] = $global_config['dump_backup_day'];
$array_config_global['dump_backup_ext'] = $global_config['dump_backup_ext'];
$array_config_global['dump_interval'] = $global_config['dump_interval'];
$array_config_global['dump_autobackup'] = $global_config['dump_autobackup'];

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $array_config_global = [];
    $array_config_global['dump_backup_ext'] = $nv_Request->get_title('dump_backup_ext', 'post', '', 1);
    $array_config_global['dump_autobackup'] = $nv_Request->get_int('dump_autobackup', 'post');
    $array_config_global['dump_backup_day'] = $nv_Request->get_int('dump_backup_day', 'post');
    $array_config_global['dump_interval'] = $nv_Request->get_int('dump_interval', 'post', 1);
    $array_config_global['dump_backup_ext'] = (in_array($array_config_global['dump_backup_ext'], $array_sql_ext, true)) ? $array_config_global['dump_backup_ext'] : $array_sql_ext[0];

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    if ($array_config_global['dump_interval'] != $global_config['dump_interval']) {
        $dump_interval = $array_config_global['dump_interval'] * 1440;
        $db->query('UPDATE ' . NV_CRONJOBS_GLOBALTABLE . ' SET inter_val=' . $dump_interval . " WHERE run_file = 'dump_autobackup.php' AND run_func = 'cron_dump_autobackup'");
    }

    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass()
    ]);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('setting.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', $checkss);
$tpl->assign('DATA', $array_config_global);
$tpl->assign('SQL_EXTS', $array_sql_ext);

$contents = $tpl->fetch('setting.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
