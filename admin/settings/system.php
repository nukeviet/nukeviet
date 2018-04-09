<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (! defined('NV_IS_FILE_SETTINGS')) {
    die('Stop!!!');
}

$adminThemes = array( '' );
$adminThemes = array_merge($adminThemes, nv_scandir(NV_ROOTDIR . '/themes', $global_config['check_theme_admin']));
unset($adminThemes[0]);

$closed_site_Modes = array();
$closed_site_Modes[0] = $lang_module['closed_site_0'];
if (defined('NV_IS_GODADMIN')) {
    $closed_site_Modes[1] = $lang_module['closed_site_1'];
}
$closed_site_Modes[2] = $lang_module['closed_site_2'];
$closed_site_Modes[3] = $lang_module['closed_site_3'];

$allow_sitelangs = array();
foreach ($global_config['allow_sitelangs'] as $lang_i) {
    if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php')) {
        $allow_sitelangs[] = $lang_i;
    }
}

$timezone_array = array_keys($nv_parse_ini_timezone);

$errormess = '';
$array_config_define = array();

if ($nv_Request->isset_request('submit', 'post')) {
    $array_config_site = array();

    $admin_theme = $nv_Request->get_string('admin_theme', 'post');
    if (! empty($admin_theme) and in_array($admin_theme, $adminThemes)) {
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

    $array_config_site['googleMapsAPI'] = $nv_Request->get_title('googleMapsAPI', 'post', '');
    if (preg_match('/[^a-zA-Z0-9\_\-]/', $array_config_site['googleMapsAPI'])) {
        $array_config_site['googleMapsAPI'] = '';
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
        if (empty($site_timezone) or (! empty($site_timezone) and (in_array($site_timezone, $timezone_array) or $site_timezone == 'byCountry'))) {
            $array_config_global['site_timezone'] = $site_timezone;
        }
        $my_domains = $nv_Request->get_title('my_domains', 'post', '');
        $array_config_global['my_domains'] = array( NV_SERVER_NAME );

        if (! empty($my_domains)) {
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
                if (! empty($dm)) {
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
        if (! empty($site_lang) and in_array($site_lang, $allow_sitelangs)) {
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
            if (! isset($site_mods[$array_config_global['rewrite_op_mod']]) or $array_config_global['rewrite_optional'] == 0) {
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
        if (! empty($cdn_url)) {
            $cdn_url = preg_replace('/^(http|https)\:\/\//', '', $cdn_url);
            $cdn_url = preg_replace('/^([^\/]+)\/*(.*)$/', '\\1', $cdn_url);
            $_p  = '';
            if (preg_match('/(.*)\:([0-9]+)$/', $cdn_url, $m)) {
                $cdn_url = $m[1];
                $_p  = ':' . $m[2];
            }
            $cdn_url = nv_check_domain(nv_strtolower($cdn_url));
            if (! empty($cdn_url)) {
                $array_config_global['cdn_url'] = $cdn_url . $_p;
            }
        }

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
            $errormess .= sprintf($lang_module['err_writable'], $rewrite[1]);
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

$page_title = $lang_module['global_config'];

$xtpl = new XTemplate('system.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $global_config);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

for ($i = 0; $i <= 2; $i ++) {
    $ssl_https = array(
        'key' => $i,
        'title' => $lang_module['ssl_https_' . $i],
        'selected' => $i == $global_config['ssl_https'] ? ' selected="selected"' : ''
    );

    $xtpl->assign('SSL_HTTPS', $ssl_https);
    $xtpl->parse('main.ssl_https');
}

if (defined('NV_IS_GODADMIN')) {
    $result = $db->query("SELECT config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' AND module='global'");
    while (list($c_config_name, $c_config_value) = $result->fetch(3)) {
        $array_config_global[$c_config_name] = $c_config_value;
    }

    $lang_multi = $array_config_global['lang_multi'];
    $xtpl->assign('CHECKED_GZIP_METHOD', ($array_config_global['gzip_method']) ? ' checked="checked"' : '');
    $xtpl->assign('CHECKED_LANG_MULTI', ($array_config_global['lang_multi']) ? ' checked="checked"' : '');
    $xtpl->assign('CHECKED_NOTIFI_ACTIVE', ($array_config_global['notification_active']) ? ' checked="checked"' : '');
    $xtpl->assign('CHECKED_ERROR_SET_LOGS', ($array_config_global['error_set_logs']) ? ' checked="checked"' : '');
    $xtpl->assign('CHECKED_REWRITE_ENABLE', ($array_config_global['rewrite_enable'] == 1) ? ' checked ' : '');
    $xtpl->assign('CHECKED_REWRITE_OPTIONAL', ($array_config_global['rewrite_optional'] == 1) ? ' checked ' : '');

    $xtpl->assign('MY_DOMAINS', $array_config_global['my_domains']);

    foreach ($site_mods as $mod => $row) {
        $xtpl->assign('MODE_VALUE', $mod);
        $xtpl->assign('MODE_SELECTED', ($mod == $array_config_global['rewrite_op_mod']) ? "selected='selected'" : "");
        $xtpl->assign('MODE_NAME', $row['custom_title']);
        $xtpl->parse('main.system.rewrite_op_mod');
    }

    $xtpl->assign('SHOW_REWRITE_OPTIONAL', ($lang_multi == 0 and $array_config_global['rewrite_enable']) ? '' : ' style="display:none"');
    $xtpl->assign('SHOW_REWRITE_OP_MOD', ($array_config_global['rewrite_optional'] == 1) ? '' : ' style="display:none"');

    if (sizeof($global_config['allow_sitelangs']) > 1) {
        foreach ($allow_sitelangs as $lang_i) {
            $xtpl->assign('LANGOP', $lang_i);
            $xtpl->assign('SELECTED', ($lang_i == $array_config_global['site_lang']) ? "selected='selected'" : "");
            $xtpl->assign('LANGVALUE', $language_array[$lang_i]['name']);
            $xtpl->parse('main.system.lang_multi.site_lang_option');
        }
        if ($lang_multi) {
            $xtpl->assign('CONFIG_LANG_GEO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=language&' . NV_OP_VARIABLE . '=countries');
            $xtpl->assign('CHECKED_LANG_GEO', ($array_config_global['lang_geo'] == 1) ? ' checked ' : '');
            $xtpl->parse('main.system.lang_multi.lang_geo');
        }
        $xtpl->parse('main.system.lang_multi');
    }
    $xtpl->assign('CURRENT_TIME', sprintf($lang_module['current_time'], nv_date('H:i T l, d/m/Y', NV_CURRENTTIME)));
    $xtpl->assign('TIMEZONEOP', 'byCountry');
    $xtpl->assign('TIMEZONESELECTED', ($array_config_global['site_timezone'] == 'byCountry') ? "selected='selected'" : "");
    $xtpl->assign('TIMEZONELANGVALUE', $lang_module['timezoneByCountry']);
    $xtpl->parse('main.system.opsite_timezone');

    sort($timezone_array);
    foreach ($timezone_array as $site_timezone_i) {
        $xtpl->assign('TIMEZONEOP', $site_timezone_i);
        $xtpl->assign('TIMEZONESELECTED', ($site_timezone_i == $array_config_global['site_timezone']) ? "selected='selected'" : "");
        $xtpl->assign('TIMEZONELANGVALUE', $site_timezone_i);
        $xtpl->parse('main.system.opsite_timezone');
    }

    $array_config_define['nv_debug'] = empty($array_config_define['nv_debug']) ? '' : ' checked="checked"';
    $xtpl->assign('CFG_DEFINE', $array_config_define);

    $xtpl->parse('main.system');
}

if ($errormess != '') {
    $xtpl->assign('ERROR', $errormess);
    $xtpl->parse('main.error');
}

foreach ($adminThemes as $name) {
    $xtpl->assign('THEME_NAME', $name);
    $xtpl->assign('THEME_SELECTED', ($name == $global_config['admin_theme'] ? ' selected="selected"' : ''));
    $xtpl->parse('main.admin_theme');
}

foreach ($closed_site_Modes as $value => $name) {
    $xtpl->assign('MODE_VALUE', $value);
    $xtpl->assign('MODE_NAME', $name);
    $xtpl->assign('MODE_SELECTED', ($value == $global_config['closed_site'] ? ' selected="selected"' : ''));
    $xtpl->parse('main.closed_site_mode');
}

$xtpl->parse('main');
$content = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';