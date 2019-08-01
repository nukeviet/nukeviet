<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_WEBTOOLS')) {
    die('Stop!!!');
}

$timezone_array = array_keys($nv_parse_ini_timezone);
$array_config_global = [];

if ($nv_Request->isset_request('submit', 'post')) {
    $array_config_global['online_upd'] = $nv_Request->get_int('online_upd', 'post');
    $array_config_global['statistic'] = $nv_Request->get_int('statistic', 'post');

    $statistics_timezone = nv_substr($nv_Request->get_title('statistics_timezone', 'post', '', 0), 0, 255);

    if (!empty($statistics_timezone) and in_array($statistics_timezone, $timezone_array)) {
        $array_config_global['statistics_timezone'] = $statistics_timezone;
    } else {
        $array_config_global['statistics_timezone'] = NV_SITE_TIMEZONE_NAME;
    }

    $array_config_global['googleAnalyticsID'] = nv_substr($nv_Request->get_title('googleAnalyticsID', 'post', '', 1), 0, 20);

    if (!preg_match('/^UA\-\d{4,}\-\d+$/', $array_config_global['googleAnalyticsID'])) {
        $array_config_global['googleAnalyticsID'] = '';
    }

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$page_title = $nv_Lang->getModule('global_statistics');

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

sort($timezone_array);

$tpl->assign('CONFIG', $global_config);
$tpl->assign('TIMEZONES', $timezone_array);

$contents = $tpl->fetch('statistics.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
