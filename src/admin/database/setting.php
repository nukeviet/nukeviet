<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getGlobal('mod_settings');
$array_sql_ext = ['sql', 'gz'];

$errormess = '';
$array_config_global = array();
$array_config_global['dump_backup_day'] = $global_config['dump_backup_day'];
$array_config_global['dump_backup_ext'] = $global_config['dump_backup_ext'];
$array_config_global['dump_interval'] = $global_config['dump_interval'];

if ($nv_Request->isset_request('submit', 'post')) {
    $array_config_global = array();
    $array_config_global['dump_backup_ext'] = $nv_Request->get_title('dump_backup_ext', 'post', '', 1);
    $array_config_global['dump_autobackup'] = $nv_Request->get_int('dump_autobackup', 'post');
    $array_config_global['dump_backup_day'] = $nv_Request->get_int('dump_backup_day', 'post');
    $array_config_global['dump_interval'] = $nv_Request->get_int('dump_interval', 'post', 1);
    $array_config_global['dump_backup_ext'] = (in_array($array_config_global['dump_backup_ext'], $array_sql_ext)) ? $array_config_global['dump_backup_ext'] : $array_sql_ext[0];

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    if ($array_config_global['dump_interval'] != $global_config['dump_interval']) {
        $dump_interval = $array_config_global['dump_interval'] * 1440;
        $db->query("UPDATE " . NV_CRONJOBS_GLOBALTABLE . " SET inter_val=" . $dump_interval . " WHERE run_file = 'dump_autobackup.php' AND run_func = 'cron_dump_autobackup'");
    }

    nv_save_file_config_global();
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('DATA', $array_config_global);
$tpl->assign('SQL_EXT', $array_sql_ext);
$tpl->assign('DUMP_AUTOBACKUP', $global_config['dump_autobackup']);

$contents = $tpl->fetch('setting.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
