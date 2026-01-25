<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_WEBTOOLS')) {
    exit('Stop!!!');
}

$timezone_array = array_keys($nv_parse_ini_timezone);
$array_config_global = [];

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $array_config_global['online_upd'] = $nv_Request->get_int('online_upd', 'post');
    $array_config_global['statistic'] = $nv_Request->get_int('statistic', 'post');
    $array_config_global['stat_excl_bot'] = (int) $nv_Request->get_bool('stat_excl_bot', 'post', false);
    $array_config_global['referer_blocker'] = $nv_Request->get_int('referer_blocker', 'post', 0);

    $statistics_timezone = nv_substr($nv_Request->get_title('statistics_timezone', 'post', '', 0), 0, 255);

    if (!empty($statistics_timezone) and in_array($statistics_timezone, $timezone_array, true)) {
        $array_config_global['statistics_timezone'] = $statistics_timezone;
    } else {
        $array_config_global['statistics_timezone'] = NV_SITE_TIMEZONE_NAME;
    }

    $array_config_global['googleAnalyticsID'] = nv_substr($nv_Request->get_title('googleAnalyticsID', 'post', '', 1), 0, 20);
    $array_config_global['googleAnalytics4ID'] = nv_substr($nv_Request->get_title('googleAnalytics4ID', 'post', '', 1), 0, 20);

    if (!preg_match('/^UA\-\d{4,}\-\d+$/', $array_config_global['googleAnalyticsID'])) {
        $array_config_global['googleAnalyticsID'] = '';
    }
    if (!(preg_match('/^UA\-\d{4,}\-\d+$/', $array_config_global['googleAnalytics4ID']) or preg_match('/^G\-[a-zA-Z0-9]{8,}$/', $array_config_global['googleAnalytics4ID']))) {
        $array_config_global['googleAnalytics4ID'] = '';
    }

    $array_config_global['google_tag_manager'] = nv_substr($nv_Request->get_title('google_tag_manager', 'post', '', 1), 0, 20);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);
    $respon = [];
    $respon['status'] = 'success';
    $respon['mess'] = $nv_Lang->getGlobal('save_success');
    $respon['refresh'] = 1;
    nv_jsonOutput($respon);
}

$page_title = $nv_Lang->getModule('global_statistics');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('statistics.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', $checkss);

sort($timezone_array);
$tpl->assign('TIMEZONE_ARRAY', $timezone_array);
$tpl->assign('GCONFIG', $global_config);

$contents = $tpl->fetch('statistics.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
