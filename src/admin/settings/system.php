<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$adminThemes = [''];
$adminThemes = array_merge($adminThemes, nv_scandir(NV_ROOTDIR . '/themes', $global_config['check_theme_admin']));
unset($adminThemes[0]);

$closed_site_Modes = [];
$closed_site_Modes[0] = $nv_Lang->getModule('closed_site_0');
if (defined('NV_IS_GODADMIN')) {
    $closed_site_Modes[1] = $nv_Lang->getModule('closed_site_1');
}
$closed_site_Modes[2] = $nv_Lang->getModule('closed_site_2');
$closed_site_Modes[3] = $nv_Lang->getModule('closed_site_3');

// Thay đổi chế độ site
if (defined('NV_IS_GODADMIN')) {
    if ($nv_Request->isset_request('site_mode', 'post')) {
        if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Error session!!!'
            ]);
        }

        $array_config_global = [];
        $array_config_global['closed_site'] = $nv_Request->get_int('closed_site', 'post', $global_config['closed_site']);
        if (!isset($closed_site_Modes[$array_config_global['closed_site']])) {
            $array_config_global['closed_site'] = $global_config['closed_site'];
        }

        $reopening_date = !empty($array_config_global['closed_site']) ? $nv_Request->get_title('reopening_date', 'post', '') : '';
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $reopening_date, $m)) {
            $reopening_hour = $nv_Request->get_int('reopening_hour', 'post', 0);
            $reopening_min = $nv_Request->get_int('reopening_min', 'post', 0);
            $array_config_global['site_reopening_time'] = mktime($reopening_hour, $reopening_min, 0, $m[2], $m[1], $m[3]);
        } else {
            $array_config_global['site_reopening_time'] = 0;
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'closed_site', $array_config_global['closed_site'], $admin_info['userid']);

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
        foreach ($array_config_global as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        nv_save_file_config_global();

        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success'),
            'refresh' => true
        ]);
    }
}

$allow_sitelangs = [];
foreach ($global_config['allow_sitelangs'] as $lang_i) {
    if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php')) {
        $allow_sitelangs[] = $lang_i;
    }
}

$timezone_array = array_keys($nv_parse_ini_timezone);
$array_config_define = [];

// Lưu Cấu hình chung
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $errormess = '';
    $array_config_site = [];

    $admin_theme = $nv_Request->get_string('admin_theme', 'post');
    $array_config_site['admin_theme'] = (!empty($admin_theme) and in_array($admin_theme, $adminThemes, true)) ? $admin_theme : '';

    $site_email = nv_substr($nv_Request->get_title('site_email', 'post', '', 1), 0, 255);
    $check = nv_check_valid_email($site_email, true);
    if ($check[0] == '') {
        $array_config_site['site_email'] = $check[1];
    } else {
        $array_config_site['site_email'] = '';
    }

    $array_config_site['site_phone'] = nv_substr($nv_Request->get_string('site_phone', 'post', '', false, false), 0, 50);
    if (preg_match('/\[(.+)\]$/', $array_config_site['site_phone'])) {
        $array_config_site['site_phone'] = preg_replace_callback('/\[(.+)\]/', function ($matches) {
            return preg_replace('/[^0-9\.\,\;\+\-\*\#\[\]]+/', '', $matches[0]);
        }, $array_config_site['site_phone']);
    }

    $array_config_site['searchEngineUniqueID'] = $nv_Request->get_title('searchEngineUniqueID', 'post', '');
    if (preg_match('/[^a-zA-Z0-9\:\-\_\.]/', $array_config_site['searchEngineUniqueID'])) {
        $array_config_site['searchEngineUniqueID'] = '';
    }

    $array_config_site['ssl_https'] = $nv_Request->get_int('ssl_https', 'post');
    if ($array_config_site['ssl_https'] < 0 or $array_config_site['ssl_https'] > 2) {
        $array_config_site['ssl_https'] = 0;
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change System setting', '', $admin_info['userid']);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    if (defined('NV_IS_GODADMIN')) {
        $array_config_global = [];
        $site_timezone = $nv_Request->get_title('site_timezone', 'post', '', 0);
        if (empty($site_timezone) or (!empty($site_timezone) and (in_array($site_timezone, $timezone_array, true) or $site_timezone == 'byCountry'))) {
            $array_config_global['site_timezone'] = $site_timezone;
        }
        $my_domains = $nv_Request->get_title('my_domains', 'post', '');
        if (!empty($my_domains)) {
            $my_domains = array_map('trim', explode(',', $my_domains));
            $sizeof = count($my_domains);
            for ($i = 0; $i < $sizeof; ++$i) {
                $dm = preg_replace('/^(http|https)\:\/\//', '', $my_domains[$i]);
                $dm = preg_replace('/^([^\/]+)\/*(.*)$/', '\\1', $dm);
                $_p = '';
                $m = [];
                if (preg_match('/(.*)(\:[0-9]+)$/', $dm, $m)) {
                    $dm = $m[1];
                    $_p = $m[2];
                }
                $dm = nv_check_domain(nv_strtolower($dm));
                if (!empty($dm)) {
                    $my_domains[$i] = $dm . $_p;
                } else {
                    unset($my_domains[$i]);
                }
            }
        } else {
            $my_domains = [];
        }
        array_unshift($my_domains, NV_SERVER_NAME);
        $my_domains = array_unique($my_domains);
        $my_domains = array_values($my_domains);

        $array_config_global['my_domains'] = implode(',', $my_domains);

        $array_config_global['gzip_method'] = $nv_Request->get_int('gzip_method', 'post');
        $array_config_global['blank_operation'] = (int) $nv_Request->get_bool('blank_operation', 'post', false);
        $array_config_global['resource_preload'] = $nv_Request->get_int('resource_preload', 'post');
        $array_config_global['lang_multi'] = $nv_Request->get_int('lang_multi', 'post');

        $array_config_global['notification_active'] = $nv_Request->get_int('notification_active', 'post');
        $array_config_global['notification_autodel'] = $nv_Request->get_int('notification_autodel', 'post', 15);
        if ($array_config_global['notification_active'] != $global_config['notification_active']) {
            $db->query('UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET act=' . $array_config_global['notification_active'] . ', last_time=' . NV_CURRENTTIME . ', last_result=0 WHERE run_func="cron_notification_autodel"');
        }

        $site_lang = $nv_Request->get_title('site_lang', 'post', '', 1);
        if (!empty($site_lang) and in_array($site_lang, $allow_sitelangs, true)) {
            $array_config_global['site_lang'] = $site_lang;
        }

        $array_config_global['rewrite_enable'] = (int) $nv_Request->get_bool('rewrite_enable', 'post', false);
        $array_config_global['admin_rewrite'] = (int) $nv_Request->get_bool('admin_rewrite', 'post', false);
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
        $array_config_global['error_separate_file'] = $nv_Request->get_int('error_separate_file', 'post', 0);
        $error_send_email = nv_substr($nv_Request->get_title('error_send_email', 'post', '', 1), 0, 255);
        $check = nv_check_valid_email($error_send_email, true);
        if ($check[0] == '') {
            $array_config_global['error_send_email'] = $check[1];
        } else {
            $array_config_global['error_send_email'] = '';
        }

        $array_config_global['static_noquerystring'] = (int) $nv_Request->get_bool('static_noquerystring', 'post', false);

        $array_config_global['unsign_vietwords'] = (int) $nv_Request->get_bool('unsign_vietwords', 'post', false);

        $array_config_global['cached'] = $nv_Request->get_title('cached', 'post', 'files');
        if (!in_array($array_config_global['cached'], ['files', 'memcached', 'redis'])) {
            $array_config_global['cached'] = 'files';
        }
        if (in_array($array_config_global['cached'], ['memcached', 'redis'])) {
            if (!in_array($array_config_global['cached'], get_loaded_extensions())) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('cached_extension_needed', $array_config_global['cached'], ucfirst($array_config_global['cached'])),
                    'input' => 'cached'
                ]);
            }
        }

        $array_config_global['memcached_host'] = nv_unhtmlspecialchars($nv_Request->get_title('memcached_host', 'post', '127.0.0.1', 1));
        $array_config_global['memcached_port'] = nv_unhtmlspecialchars($nv_Request->get_int('memcached_port', 'post', 11211));
        if ($array_config_global['cached'] == 'memcached' && (empty($array_config_global['memcached_host']) || empty($array_config_global['memcached_port']))) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('memcached_config_needed'),
                'input' => empty($array_config_global['memcached_host']) ? 'memcached_host' : 'memcached_port'
            ]);
        }

        $array_config_global['redis_host'] = nv_unhtmlspecialchars($nv_Request->get_title('redis_host', 'post', '127.0.0.1', 1));
        $array_config_global['redis_port'] = $nv_Request->get_int('redis_port', 'post', 6379);
        $redis_password = nv_unhtmlspecialchars($nv_Request->get_title('redis_password', 'post', '', 0));
        $redis_password == '******' && $redis_password = $global_config['redis_password'];
        $array_config_global['redis_password'] = $crypt->encrypt($redis_password);
        $array_config_global['redis_db_index'] = $nv_Request->get_int('redis_db_index', 'post', 0);
        $array_config_global['redis_timeout'] = $nv_Request->get_float('redis_timeout', 'post', 2.5);
        if ($array_config_global['cached'] == 'redis' && (empty($array_config_global['redis_host']) || empty($array_config_global['redis_port']))) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('redis_config_needed'),
                'input' => empty($array_config_global['redis_host']) ? 'redis_host' : 'redis_port'
            ]);
        }

        $array_config_global['cache_prefix'] = nv_substr($nv_Request->get_title('cache_prefix', 'post', ''), 0, 50);
        if (!empty($array_config_global['cache_prefix']) and !preg_match('/^[a-z]+[a-z0-9\_]*$/i', $array_config_global['cache_prefix'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('cache_prefix_invalid'),
                'input' => 'cache_prefix'
            ]);
        }

        // Kiểm tra kết nối cache
        $_config = $array_config_global;
        $_config['redis_password'] = empty($_config['redis_password']) ? '' : $crypt->decrypt($_config['redis_password']);
        $check = \NukeViet\Cache::testInstance($_config);
        if ($check !== 'success') {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('cache_test_error', $check)
            ]);
        }

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
        foreach ($array_config_global as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        // Cấu hình ghi ra hằng
        $array_config_define['nv_debug'] = (int) $nv_Request->get_bool('nv_debug', 'post');

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name");
        foreach ($array_config_define as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        nv_save_file_config_global();

        $array_config_rewrite = [
            'rewrite_enable' => $array_config_global['rewrite_enable'],
            'rewrite_optional' => $array_config_global['rewrite_optional'],
            'rewrite_endurl' => $global_config['rewrite_endurl'],
            'rewrite_exturl' => $global_config['rewrite_exturl'],
            'rewrite_op_mod' => $array_config_global['rewrite_op_mod'],
            'admin_rewrite' => $array_config_global['admin_rewrite'],
        ];
        $rewrite = nv_rewrite_change($array_config_rewrite);
        if (empty($rewrite[0])) {
            $errormess .= $nv_Lang->getModule('err_writable', $rewrite[1]);
        }

        $diff1 = array_diff($my_domains, $global_config['my_domains']);
        $diff2 = array_diff($global_config['my_domains'], $my_domains);
        if (!empty($diff1) or !empty($diff2) or $array_config_global['admin_rewrite'] != $global_config['admin_rewrite']) {
            $save_config = nv_server_config_change($my_domains, $array_config_global['admin_rewrite']);
            if ($save_config[0] !== true) {
                $errormess .= (!empty($errormess) ? '<br/>' : '') . $nv_Lang->getModule('err_save_sysconfig', $save_config[1]);
            }
        }
    } else {
        $nv_Cache->delAll(false);
    }

    if (!empty($errormess)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $errormess
        ]);
    }

    // Set giá trị này để hàm nv_url_rewrite nhận đúng config mới
    $global_config['admin_rewrite'] = $array_config_global['admin_rewrite'];

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, $array_config_global['admin_rewrite'])
    ]);
}

$page_title = $nv_Lang->getModule('global_config');

$array_config_define['nv_debug'] = NV_DEBUG;
$global_config['checkss'] = $checkss;
$global_config['reopening_date'] = '';
$global_config['reopening_hour'] = 0;
$global_config['reopening_min'] = 0;
if (!empty($global_config['site_reopening_time'])) {
    $tdate = date('d/m/Y|H|i', $global_config['site_reopening_time']);
    [$global_config['reopening_date'], $global_config['reopening_hour'], $global_config['reopening_min']] = explode('|', $tdate);
    $global_config['reopening_date'] = nv_u2d_post($global_config['site_reopening_time']);
}

if (!empty($global_config['site_phone']) and !empty($global_config['site_int_phone'])) {
    $global_config['site_phone'] .= '[' . $global_config['site_int_phone'] . ']';
}
$global_config['redis_password'] = '******';

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->registerPlugin('modifier', 'str_pad', 'str_pad');
$tpl->setTemplateDir(get_module_tpl_dir('system.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('DATA', $global_config);
$tpl->assign('ALLOW_SITELANGS', $allow_sitelangs);
$tpl->assign('LANGUAGE_ARRAY', $language_array);
$tpl->assign('SITE_MODS', $site_mods);
$tpl->assign('ADMINTHEMES', $adminThemes);
$tpl->assign('CLOSED_SITE_MODES', $closed_site_Modes);
$tpl->assign('CRYPT', $crypt);

$array_config_global = [];
if (defined('NV_IS_GODADMIN')) {
    $result = $db->query('SELECT config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' AND module='global'");
    while ([$c_config_name, $c_config_value] = $result->fetch(3)) {
        $array_config_global[$c_config_name] = $c_config_value;
    }
}
$tpl->assign('GDATA', $array_config_global);
$tpl->assign('DDATA', $array_config_define);

sort($timezone_array);
$tpl->assign('TIMEZONES', $timezone_array);

$preload_opts = [
    $nv_Lang->getModule('resource_preload_not'),
    $nv_Lang->getModule('resource_preload_headers'),
    $nv_Lang->getModule('resource_preload_html')
];
$tpl->assign('PRELOAD_OPTS', $preload_opts);

$contents = $tpl->fetch('system.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
