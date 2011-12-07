<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_SETTINGS'))
    die('Stop!!!');

$array_config_global = array();
if ($nv_Request->isset_request('submit', 'post'))
{
    $array_config_global['online_upd'] = $nv_Request->get_int('online_upd', 'post');
    $array_config_global['statistic'] = $nv_Request->get_int('statistic', 'post');
    $array_config_global['statistics_timezone'] = $nv_Request->get_int('statistics_timezone', 'post');

    $array_config_global['googleAnalyticsID'] = filter_text_input('googleAnalyticsID', 'post', '', 1, 20);
    if (!preg_match('/^UA-\d{4,}-\d+$/', $array_config_global['googleAnalyticsID']))
        $array_config_global['googleAnalyticsID'] = "";
    $array_config_global['googleAnalyticsSetDomainName'] = $nv_Request->get_int('googleAnalyticsSetDomainName', 'post');

    foreach ($array_config_global as $config_name => $config_value)
    {
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', '" . mysql_real_escape_string($config_name) . "', " . $db->dbescape($config_value) . ")");
    }

    nv_save_file_config_global();

    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    exit();
}

$page_title = $lang_module['global_statistics'];

$array_config_global['online_upd'] = ($global_config['online_upd']) ? ' checked="checked"' : '';
$array_config_global['statistic'] = ($global_config['statistic']) ? ' checked="checked"' : '';
$array_config_global['googleAnalyticsID'] = $global_config['googleAnalyticsID'];

$xtpl = new XTemplate("statistics.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file . "");
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config_global);
for ($i = -12; $i <= 13; ++$i)
{
    $xtpl->assign('TIMEZONESELECTED', ($global_config['statistics_timezone'] == $i) ? ' selected="selected"' : '');
    $xtpl->assign('TIMEZONEOP', $i);
    $xtpl->assign('TIMEZONELANGVALUE', 'GMT ' . (($i >= 0) ? '+' : '') . $i);
    $xtpl->parse('main.timezone');
}
for ($i = 0; $i < 3; ++$i)
{
    $xtpl->assign('GOOGLEANALYTICSSETDOMAINNAME_SELECTED', ($global_config['googleAnalyticsSetDomainName'] == $i) ? ' selected="selected"' : '');
    $xtpl->assign('GOOGLEANALYTICSSETDOMAINNAME_VALUE', $i);
    $xtpl->assign('GOOGLEANALYTICSSETDOMAINNAME_TITLE', $lang_module['googleAnalyticsSetDomainName_' . $i]);
    $xtpl->parse('main.googleAnalyticsSetDomainName');
}

$xtpl->parse('main');
$content = $xtpl->text('main');

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($content);
include (NV_ROOTDIR . "/includes/footer.php");
?>