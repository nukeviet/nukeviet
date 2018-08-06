<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/28/2009 20:8
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (headers_sent() or connection_status() != 0 or connection_aborted()) {
    trigger_error('Warning: Headers already sent', E_USER_WARNING);
}

$nv_resquest_serverext_key = array('php_support', 'opendir_support', 'gd_support', 'xml_support', 'openssl_support', 'session_support', 'fileuploads_support', 'json_support');

if ($sys_info['ini_set_support']) {
    ini_set('session.use_trans_sid', 0);
    ini_set('session.auto_start', 0);
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.gc_probability', 1);
    //Kha nang chay Garbage Collection - trinh xoa session da het han truoc khi bat dau session_start();
    ini_set('session.gc_divisor', 1000);
    //gc_probability / gc_divisor = phan tram (phan nghin) kha nang chay Garbage Collection
    ini_set('session.gc_maxlifetime', 3600);
    //thoi gian sau khi het han phien lam viec de Garbage Collection tien hanh xoa, 60 phut
    ini_set('allow_url_fopen', 1);
    ini_set('user_agent', 'NV4');
    ini_set('default_charset', $global_config['site_charset']);

    $memoryLimitMB = ( integer )ini_get('memory_limit');

    if ($memoryLimitMB < 64) {
        ini_set('memory_limit', '64M');
    }

    ini_set('arg_separator.output', '&');
    ini_set('auto_detect_line_endings', 0);
}

$sys_info['php_required_min'] = '5.6.0';
$sys_info['php_allowed_max'] = '7.2.99';
$sys_info['php_version'] = PHP_VERSION;
$sys_info['php_support'] = (version_compare($sys_info['php_version'], $sys_info['php_required_min']) >= 0 and version_compare($sys_info['php_version'], $sys_info['php_allowed_max']) <= 0) ? 1 : 0;
$sys_info['opendir_support'] = (function_exists('opendir') and !in_array('opendir', $sys_info['disable_functions'])) ? 1 : 0;
$sys_info['gd_support'] = (extension_loaded('gd')) ? 1 : 0;
$sys_info['xml_support'] = (extension_loaded('xml')) ? 1 : 0;
$sys_info['fileuploads_support'] = (ini_get('file_uploads')) ? 1 : 0;
$sys_info['zlib_support'] = (extension_loaded('zlib')) ? 1 : 0;
$sys_info['session_support'] = (extension_loaded('session')) ? 1 : 0;
$sys_info['mb_support'] = (extension_loaded('mbstring')) ? 1 : 0;
$sys_info['iconv_support'] = (extension_loaded('iconv')) ? 1 : 0;
$sys_info['json_support'] = (extension_loaded('json')) ? 1 : 0;
$sys_info['curl_support'] = (extension_loaded('curl') and function_exists('curl_init') and !in_array('curl_init', $sys_info['disable_functions'])) ? 1 : 0;
$sys_info['allowed_set_time_limit'] = (function_exists('set_time_limit') and !in_array('set_time_limit', $sys_info['disable_functions'])) ? 1 : 0;

$sys_info['os'] = strtoupper((function_exists('php_uname') and !in_array('php_uname', $sys_info['disable_functions']) and php_uname('s') != '') ? php_uname('s') : PHP_OS);
$sys_info['ftp_support'] = (function_exists('ftp_connect') and !in_array('ftp_connect', $sys_info['disable_functions']) and function_exists('ftp_chmod') and !in_array('ftp_chmod', $sys_info['disable_functions']) and function_exists('ftp_mkdir') and !in_array('ftp_mkdir', $sys_info['disable_functions']) and function_exists('ftp_chdir') and !in_array('ftp_chdir', $sys_info['disable_functions']) and function_exists('ftp_nlist') and !in_array('ftp_nlist', $sys_info['disable_functions'])) ? 1 : 0;
$sys_info['openssl_support'] = (function_exists('openssl_encrypt')) ? 1 : 0;

//Xac dinh tien ich mo rong lam viec voi string
$sys_info['string_handler'] = $sys_info['mb_support'] ? 'mb' : ($sys_info['iconv_support'] ? 'iconv' : 'php');

//Kiem tra ho tro rewrite
$sys_info['supports_rewrite'] = false;
if (function_exists('apache_get_modules')) {
    $apache_modules = apache_get_modules();

    if (in_array('mod_rewrite', $apache_modules)) {
        $sys_info['supports_rewrite'] = 'rewrite_mode_apache';
    }
} elseif (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false) {
    if (isset($_SERVER['IIS_UrlRewriteModule']) and (php_sapi_name() == 'cgi-fcgi') and class_exists('DOMDocument')) {
        $sys_info['supports_rewrite'] = 'rewrite_mode_iis';
    } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
        $sys_info['supports_rewrite'] = 'rewrite_mode_apache';
    }
}
