<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    die('Stop!!!');
}

$adminThemes = array( '' );
$adminThemes = array_merge($adminThemes, nv_scandir(NV_ROOTDIR . '/themes', $global_config['check_theme_admin']));
unset($adminThemes[0]);

$closed_site_Modes = array();
$closed_site_Modes[0] = $nv_Lang->getModule('closed_site_0');
if (defined('NV_IS_GODADMIN')) {
    $closed_site_Modes[1] = $nv_Lang->getModule('closed_site_1');
}
$closed_site_Modes[2] = $nv_Lang->getModule('closed_site_2');
$closed_site_Modes[3] = $nv_Lang->getModule('closed_site_3');

$allow_sitelangs = array();
foreach ($global_config['allow_sitelangs'] as $lang_i) {
    if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php')) {
        $allow_sitelangs[] = $lang_i;
    }
}

$timezone_array = array_keys($nv_parse_ini_timezone);
sort($timezone_array);

$errormess = '';
$array_config_define = array();

if ($nv_Request->isset_request('submit', 'post')) {
    $array_config_site = array();

    $admin_theme = $nv_Request->get_string('admin_theme', 'post');
    if (!empty($admin_theme) and in_array($admin_theme, $adminThemes)) {
        $array_config_site['admin_theme'] = $admin_theme;
    }

    $closed_site = $nv_Request->get_int('closed_site', 'post');
    if (isset($closed_site_Modes[$closed_site])) {
        $array_config_site['closed_site'] = $closed_site;
    }

    $site_email = nv_substr($nv_Request->get_title('site_email', 'post', '', 1), 0, 255);
    if (nv_check_valid_email($site_email) == '') {
        $array_config_site['site_email'] = $site_email;
    }

    $array_config_site['site_phone'] = nv_substr($nv_Request->get_title('site_phone', 'post', ''), 0, 20);

    $preg_replace = array( 'pattern' => "/[^a-z\-\_\.\,\;\:\@\/\\s]/i", 'replacement' => '' );
    $array_config_site['date_pattern'] = nv_substr($nv_Request->get_title('date_pattern', 'post', '', 0, $preg_replace), 0, 255);
    $array_config_site['time_pattern'] = nv_substr($nv_Request->get_title('time_pattern', 'post', '', 0, $preg_replace), 0, 255);

    $array_config_site['searchEngineUniqueID'] = $nv_Request->get_title('searchEngineUniqueID', 'post', '');
    if (preg_match('/[^a-zA-Z0-9\:\-\_\.]/', $array_config_site['searchEngineUniqueID'])) {
        $array_config_site['searchEngineUniqueID'] = '';
    }

    $array_config_site['ssl_https'] = $nv_Request->get_int('ssl_https', 'post');
    if ($array_config_site['ssl_https'] < 0 or $array_config_site['ssl_https'] > 2) {
        $array_config_site['ssl_https'] = 0;
    }

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    if (defined('NV_IS_GODADMIN')) {
        $array_config_global = array();
        $site_timezone = $nv_Request->get_title('site_timezone', 'post', '', 0);
        if (empty($site_timezone) or (!empty($site_timezone) and (in_array($site_timezone, $timezone_array) or $site_timezone == 'byCountry'))) {
            $array_config_global['site_timezone'] = $site_timezone;
        }
        $my_domains = $nv_Request->get_title('my_domains', 'post', '');
        $array_config_global['my_domains'] = array( NV_SERVER_NAME );

        if (!empty($my_domains)) {
            $my_domains = array_map('trim', explode(',', $my_domains));
            foreach ($my_domains as $dm) {
                $dm = preg_replace('/^(http|https)\:\/\//', '', $dm);
                $dm = preg_replace('/^([^\/]+)\/*(.*)$/', '\\1', $dm);
                $_p  = '';
                if (preg_match('/(.*)\:([0-9]+)$/', $dm, $m)) {
                    $dm = $m[1];
                    $_p  = ':' . $m[2];
                }
                $dm = nv_check_domain(nv_strtolower($dm));
                if (!empty($dm)) {
                    $array_config_global['my_domains'][] = $dm . $_p;
                }
            }
        }
        $array_config_global['my_domains'] = array_unique($array_config_global['my_domains']);
        $array_config_global['my_domains'] = implode(',', $array_config_global['my_domains']);

        $array_config_global['gzip_method'] = $nv_Request->get_int('gzip_method', 'post');
        $array_config_global['lang_multi'] = $nv_Request->get_int('lang_multi', 'post');

        $array_config_global['notification_active'] = $nv_Request->get_int('notification_active', 'post');
        $array_config_global['notification_autodel'] = $nv_Request->get_int('notification_autodel', 'post', 15);
        if ($array_config_global['notification_active'] != $global_config['notification_active']) {
            $db->query('UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET act=' . $array_config_global['notification_active'] . ', last_time=' . NV_CURRENTTIME . ', last_result=0 WHERE run_func="cron_notification_autodel"');
        }

        $site_lang = $nv_Request->get_title('site_lang', 'post', '', 1);
        if (!empty($site_lang) and in_array($site_lang, $allow_sitelangs)) {
            $array_config_global['site_lang'] = $site_lang;
        }

        $array_config_global['rewrite_enable'] = $nv_Request->get_int('rewrite_enable', 'post', 0);
        if ($array_config_global['lang_multi'] == 0) {
            if ($array_config_global['rewrite_enable']) {
                $array_config_global['rewrite_optional'] = $nv_Request->get_int('rewrite_optional', 'post', 0);
            } else {
                $array_config_global['rewrite_optional'] = 0;
            }
            $array_config_global['lang_geo'] = 0;
            $array_config_global['rewrite_op_mod'] = $nv_Request->get_title('rewrite_op_mod', 'post');
            if (!isset($site_mods[$array_config_global['rewrite_op_mod']]) or $array_config_global['rewrite_optional'] == 0) {
                $array_config_global['rewrite_op_mod'] = '';
            }
        } else {
            $array_config_global['rewrite_optional'] = 0;
            $array_config_global['lang_geo'] = $nv_Request->get_int('lang_geo', 'post', 0);
            $array_config_global['rewrite_op_mod'] = '';
        }

        $array_config_global['error_set_logs'] = $nv_Request->get_int('error_set_logs', 'post', 0);
        $error_send_email = nv_substr($nv_Request->get_title('error_send_email', 'post', '', 1), 0, 255);
        if (nv_check_valid_email($error_send_email) == '') {
            $array_config_global['error_send_email'] = $error_send_email;
        }

        $array_config_global['cdn_url'] = '';
        $cdn_url = rtrim($nv_Request->get_string('cdn_url', 'post'), '/');
        if (!empty($cdn_url)) {
            $cdn_url = preg_replace('/^(http|https)\:\/\//', '', $cdn_url);
            $cdn_url = preg_replace('/^([^\/]+)\/*(.*)$/', '\\1', $cdn_url);
            $_p  = '';
            if (preg_match('/(.*)\:([0-9]+)$/', $cdn_url, $m)) {
                $cdn_url = $m[1];
                $_p  = ':' . $m[2];
            }
            $cdn_url = nv_check_domain(nv_strtolower($cdn_url));
            if (!empty($cdn_url)) {
                $array_config_global['cdn_url'] = $cdn_url . $_p;
            }
        }
        $array_config_global['remote_api_access'] = (int)$nv_Request->get_bool('remote_api_access', 'post', 0);

        $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
        foreach ($array_config_global as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        // Cấu hình ghi ra hằng
        $array_config_define['nv_debug'] = (int)$nv_Request->get_bool('nv_debug', 'post');

        $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name");
        foreach ($array_config_define as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        nv_save_file_config_global();

        $array_config_rewrite = array(
            'rewrite_enable' => $array_config_global['rewrite_enable'],
            'rewrite_optional' => $array_config_global['rewrite_optional'],
            'rewrite_endurl' => $global_config['rewrite_endurl'],
            'rewrite_exturl' => $global_config['rewrite_exturl'],
            'rewrite_op_mod' => $array_config_global['rewrite_op_mod'],
        );
        $rewrite = nv_rewrite_change($array_config_rewrite);
        if (empty($rewrite[0])) {
            $errormess .= sprintf($nv_Lang->getModule('err_writable'), $rewrite[1]);
        }
    } else {
        $nv_Cache->delAll(false);
    }
    if (empty($errormess)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
} else {
    $array_config_define['nv_debug'] = NV_DEBUG;
}

$page_title = $nv_Lang->getModule('global_config');

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('DATA', $global_config);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);
$tpl->assign('NV_IS_GODADMIN', defined('NV_IS_GODADMIN'));
$tpl->assign('ALLOW_SITELANGS', $allow_sitelangs);
$tpl->assign('LANGUAGE_ARRAY', $language_array);
$tpl->assign('CONFIG_LANG_GEO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=language&amp;' . NV_OP_VARIABLE . '=countries');
$tpl->assign('CURRENT_TIME', sprintf($nv_Lang->getModule('current_time'), nv_date('H:i T l, d/m/Y', NV_CURRENTTIME)));

if (defined('NV_IS_GODADMIN')) {
    $result = $db->query("SELECT config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' AND module='global'");
    while (list($c_config_name, $c_config_value) = $result->fetch(3)) {
        $array_config_global[$c_config_name] = $c_config_value;
    }

    $tpl->assign('CONFIG', $array_config_global);
    $tpl->assign('SITE_MODS', $site_mods);
    $tpl->assign('TIMEZONES', $timezone_array);
    $tpl->assign('MY_DOMAINS', $array_config_global['my_domains']);
    $tpl->assign('CFG_DEFINE', $array_config_define);
}

$tpl->assign('ERROR', $errormess);
$tpl->assign('CLOSED_SITE_MODES', $closed_site_Modes);
$tpl->assign('ADMINTHEMES', $adminThemes);

$contents = $tpl->fetch('system.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
