<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$page_title = $lang_module['global_statistics'];

$array_config_global['online_upd'] = ($global_config['online_upd']) ? ' checked="checked"' : '';
$array_config_global['statistic'] = ($global_config['statistic']) ? ' checked="checked"' : '';
$array_config_global['referer_blocker'] = ($global_config['referer_blocker']) ? ' checked="checked"' : '';
$array_config_global['googleAnalyticsID'] = $global_config['googleAnalyticsID'];
$array_config_global['googleAnalytics4ID'] = $global_config['googleAnalytics4ID'];

$xtpl = new XTemplate('statistics.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('CHECKSS', $checkss);

sort($timezone_array);
foreach ($timezone_array as $site_timezone_i) {
    $xtpl->assign('TIMEZONEOP', $site_timezone_i);
    $xtpl->assign('TIMEZONESELECTED', ($site_timezone_i == $global_config['statistics_timezone']) ? ' selected="selected"' : '');
    $xtpl->assign('TIMEZONELANGVALUE', $site_timezone_i);
    $xtpl->parse('main.timezone');
}

$xtpl->parse('main');
$content = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';
