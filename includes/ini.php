<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (headers_sent() or connection_status() != 0 or connection_aborted()) {
    trigger_error('Warning: Headers already sent', E_USER_WARNING);
}

if ($sys_info['ini_set_support']) {
    if ($global_config['session_handler'] == 'memcached') {
        if (ini_set('session.save_handler', 'memcached') === false or ini_set('session.save_path', NV_MEMCACHED_HOST . ':' . NV_MEMCACHED_PORT) === false) {
            trigger_error('Server does not support Memcached Session handler!', 256);
        }
    } elseif ($global_config['session_handler'] == 'redis') {
        if (ini_set('session.save_handler', 'redis') === false or ini_set('session.save_path', NV_REDIS_HOST . ':' . NV_REDIS_PORT) === false) {
            trigger_error('Server does not support Redis Session handler!', 256);
        }
    }

    if (! isset($_SESSION)) {
        //ini_set( 'session.save_handler', 'files' );
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
    }

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

$sys_info['zlib_support'] = (extension_loaded('zlib')) ? 1 : 0;
$sys_info['mb_support'] = (extension_loaded('mbstring')) ? 1 : 0;
$sys_info['iconv_support'] = (extension_loaded('iconv')) ? 1 : 0;
$sys_info['json_support'] = (extension_loaded('json')) ? 1 : 0;
$sys_info['allowed_set_time_limit'] = (function_exists('set_time_limit') and ! in_array('set_time_limit', $sys_info['disable_functions'])) ? 1 : 0;
$sys_info['os'] = strtoupper((function_exists('php_uname') and ! in_array('php_uname', $sys_info['disable_functions']) and php_uname('s') != '') ? php_uname('s') : PHP_OS);

$sys_info['fileuploads_support'] = (ini_get('file_uploads')) ? 1 : 0;
$sys_info['curl_support'] = (extension_loaded('curl') and (empty($sys_info['disable_functions']) or (! empty($sys_info['disable_functions']) and ! preg_grep('/^curl\_/', $sys_info['disable_functions'])))) ? 1 : 0;
$sys_info['ftp_support'] = (function_exists('ftp_connect') and ! in_array('ftp_connect', $sys_info['disable_functions']) and function_exists('ftp_chmod') and ! in_array('ftp_chmod', $sys_info['disable_functions']) and function_exists('ftp_mkdir') and ! in_array('ftp_mkdir', $sys_info['disable_functions']) and function_exists('ftp_chdir') and ! in_array('ftp_chdir', $sys_info['disable_functions']) and function_exists('ftp_nlist') and ! in_array('ftp_nlist', $sys_info['disable_functions'])) ? 1 : 0;

//Neu he thong khong ho tro php se bao loi
if (version_compare(PHP_VERSION, '5.5.0') < 0) {
    trigger_error('You are running an unsupported PHP version. Please upgrade to PHP 5.5 or higher before trying to install Nukeviet Portal', 256);
}

//Neu he thong khong ho tro opendir se bao loi
if (! (function_exists('opendir') and ! in_array('opendir', $sys_info['disable_functions']))) {
    trigger_error('Opendir function is not supported', 256);
}

//Neu he thong khong ho tro GD se bao loi
if (! (extension_loaded('gd'))) {
    trigger_error('GD not installed', 256);
}

//Neu he thong khong ho tro session se bao loi
if (! extension_loaded('session')) {
    trigger_error('Session object not supported', 256);
}

//Neu he thong khong ho tro mcrypt library se bao loi
if (! function_exists('openssl_encrypt')) {
    trigger_error('Openssl library not available', 256);
}

//Xac dinh tien ich mo rong lam viec voi string
$sys_info['string_handler'] = $sys_info['mb_support'] ? 'mb' : ($sys_info['iconv_support'] ? 'iconv' : 'php');
