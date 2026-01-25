<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (headers_sent() or connection_status() != 0 or connection_aborted()) {
    trigger_error('Warning: Headers already sent', E_USER_WARNING);
}

/**
 * server_info_update()
 *
 * @param string $config_ini_file
 */
function server_info_update($config_ini_file)
{
    global $nv_Server;

    $proto = $nv_Server->getOriginalProtocol();
    $proto2 = ($proto == 'https') ? 'http' : 'https';
    $host = $nv_Server->getOriginalHost();
    if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
        $host = '[' . $host . ']';
    }

    $unset = ['http_code', 'date', 'expires', 'last-modified', 'connection', 'set-cookie', 'x-page-speed', 'x-powered-by', 'x-is-http', 'x-is-https'];

    if (!defined('CURL_HTTP_VERSION_2_0')) {
        define('CURL_HTTP_VERSION_2_0', 3);
    }

    $server_headers = [];
    $is_http2 = false;
    $ch = curl_init($proto . '://' . $host . NV_BASE_SITEURL . 'sload.php?response_headers_detect=1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 0);
    $response = curl_exec($ch);
    if (!empty($response) and !curl_errno($ch)) {
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
        $header_text = explode("\r\n", $header_text);
        foreach ($header_text as $line) {
            $t = explode(':', $line, 2);
            if (isset($t[1])) {
                $key = strtolower(trim($t[0]));
                $value = trim($t[1]);
                if (!empty($key) and !in_array($key, $unset, true)) {
                    $server_headers[] = "'" . addslashes($key) . "' => '" . addslashes($value) . "'";
                }
            } else {
                if (stripos(strtolower($line), 'http/2') !== false) {
                    $is_http2 = true;
                }
            }
        }
    }
    curl_close($ch);

    $server_headers = !empty($server_headers) ? implode(',', $server_headers) : '';

    $http_only = false;
    $https_only = false;
    $ch = curl_init($proto2 . '://' . $host . NV_BASE_SITEURL . 'sload.php?response_headers_detect=1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 0);
    $response = curl_exec($ch);
    if ($proto == 'https') {
        $https_only = (!empty($response) and !curl_errno($ch) and (strripos($response, 'x-is-http:') !== false)) ? false : true;
    } else {
        $http_only = (!empty($response) and !curl_errno($ch) and (strripos($response, 'x-is-https:') !== false)) ? false : true;
    }
    curl_close($ch);

    $contents = file_get_contents($config_ini_file);
    if (!empty($contents)) {
        $contents = preg_replace('/(\$sys\_info\[\'server\_headers\'\]\s*\=\s*\[)[^\]]*(\]\;)/', '\\1' . $server_headers . '\\2', $contents);
        $contents = preg_replace('/(\$sys\_info\[\'is\_http2\'\]\s*\=\s*)[^\;]+\;/', '\\1' . ($is_http2 ? 'true' : 'false') . ';', $contents);
        $contents = preg_replace('/(\$sys\_info\[\'http\_only\'\]\s*\=\s*)[^\;]+\;/', '\\1' . ($http_only ? 'true' : 'false') . ';', $contents);
        $contents = preg_replace('/(\$sys\_info\[\'https\_only\'\]\s*\=\s*)[^\;]+\;/', '\\1' . ($https_only ? 'true' : 'false') . ';', $contents);
        $contents = preg_replace('/(\$serverInfoUpdated\s*\=\s*)[^\;]+\;/', '\\1true;', $contents);

        @file_put_contents($config_ini_file, $contents, LOCK_EX);
    }
}

/**
 * check_ini()
 *
 * @param array  $ini_set
 * @param string $key
 * @param string $new_value
 */
function check_ini(&$ini_set, $key, $new_value)
{
    $value = ini_get($key);
    if ($value != $new_value) {
        if (ini_set($key, $new_value) !== false) {
            ini_get($key) == $new_value && $ini_set[$key] = $new_value;
        }
    }
}

/**
 * set_ini_file()
 *
 * @param array $sys_info
 */
function set_ini_file(&$sys_info)
{
    global $config_ini_file, $global_config;

    $content_config = '<?php' . "\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";

    //disable_classes
    $sys_info['disable_classes'] = (($disable_classes = ini_get('disable_classes')) != '' and $disable_classes != false) ? array_map('trim', preg_split("/[\s,]+/", $disable_classes)) : [];
    $content_config .= "\$sys_info['disable_classes'] = [" . ((!empty($sys_info['disable_classes'])) ? "'" . implode("', '", $sys_info['disable_classes']) . "'" : '') . "];\n";

    //disable_functions
    $sys_info['disable_functions'] = (($disable_functions = ini_get('disable_functions')) != '' and $disable_functions != false) ? array_map('trim', preg_split("/[\s,]+/", $disable_functions)) : [];
    if (extension_loaded('suhosin')) {
        $sys_info['disable_functions'] = array_merge($sys_info['disable_functions'], array_map('trim', preg_split("/[\s,]+/", ini_get('suhosin.executor.func.blacklist'))));
    }
    $content_config .= "\$sys_info['disable_functions'] = [" . ((!empty($sys_info['disable_functions'])) ? "'" . implode("', '", $sys_info['disable_functions']) . "'" : '') . "];\n";

    //ini_set_support
    $sys_info['ini_set_support'] = (function_exists('ini_set') and !in_array('ini_set', $sys_info['disable_functions'], true)) ? true : false;
    $content_config .= "\$sys_info['ini_set_support'] = " . ($sys_info['ini_set_support'] ? 'true' : 'false') . ";\n";

    //Kiem tra ho tro rewrite
    $_server_software = explode('/', $_SERVER['SERVER_SOFTWARE']);
    $sys_info['supports_rewrite'] = false;
    if (function_exists('apache_get_modules')) {
        $apache_modules = apache_get_modules();
        if (in_array('mod_rewrite', $apache_modules, true)) {
            $sys_info['supports_rewrite'] = 'rewrite_mode_apache';
        }
    } elseif (stripos($_server_software[0], 'Microsoft-IIS') !== false and $_server_software[1] >= 7) {
        if (isset($_SERVER['IIS_UrlRewriteModule']) and class_exists('DOMDocument') and !in_array('DOMDocument', $sys_info['disable_classes'], true)) {
            $sys_info['supports_rewrite'] = 'rewrite_mode_iis';
        }
    } elseif (stripos($_server_software[0], 'nginx') !== false) {
        $sys_info['supports_rewrite'] = 'nginx';
    } elseif (stripos($_server_software[0], 'Apache') !== false and stripos(PHP_SAPI, 'cgi-fcgi') !== false) {
        $sys_info['supports_rewrite'] = 'rewrite_mode_apache';
    } elseif (isset($_SERVER['HTTP_SUPPORT_REWRITE'])) {
        $sys_info['supports_rewrite'] = 'rewrite_mode_apache';
    }
    if (empty($sys_info['supports_rewrite']) and !empty(NV_MY_REWRITE_SUPPORTER)) {
        $sys_info['supports_rewrite'] = NV_MY_REWRITE_SUPPORTER;
    }
    $content_config .= "\$sys_info['supports_rewrite'] = " . (!empty($sys_info['supports_rewrite']) ? "'" . $sys_info['supports_rewrite'] . "'" : 'false') . ";\n";

    //zlib_support
    $sys_info['zlib_support'] = (extension_loaded('zlib')) ? true : false;
    $content_config .= "\$sys_info['zlib_support'] = " . ($sys_info['zlib_support'] ? 'true' : 'false') . ";\n";

    //mb_support
    $sys_info['mb_support'] = (extension_loaded('mbstring')) ? true : false;
    $content_config .= "\$sys_info['mb_support'] = " . ($sys_info['mb_support'] ? 'true' : 'false') . ";\n";

    //iconv_support
    $sys_info['iconv_support'] = (extension_loaded('iconv')) ? true : false;
    $content_config .= "\$sys_info['iconv_support'] = " . ($sys_info['iconv_support'] ? 'true' : 'false') . ";\n";

    //allowed_set_time_limit
    $sys_info['allowed_set_time_limit'] = (function_exists('set_time_limit') and !in_array('set_time_limit', $sys_info['disable_functions'], true)) ? true : false;
    $content_config .= "\$sys_info['allowed_set_time_limit'] = " . ($sys_info['allowed_set_time_limit'] ? 'true' : 'false') . ";\n";

    //os
    $sys_info['os'] = strtoupper((function_exists('php_uname') and !in_array('php_uname', $sys_info['disable_functions'], true) and php_uname('s') != '') ? php_uname('s') : PHP_OS);
    $content_config .= "\$sys_info['os'] = '" . $sys_info['os'] . "';\n";

    //fileuploads_support
    $sys_info['fileuploads_support'] = (ini_get('file_uploads')) ? true : false;
    $content_config .= "\$sys_info['fileuploads_support'] = " . ($sys_info['fileuploads_support'] ? 'true' : 'false') . ";\n";

    //curl_support
    $sys_info['curl_support'] = (extension_loaded('curl') and function_exists('curl_init') and !in_array('curl_init', $sys_info['disable_functions'], true)) ? true : false;
    $content_config .= "\$sys_info['curl_support'] = " . ($sys_info['curl_support'] ? 'true' : 'false') . ";\n";

    //ftp_support
    $sys_info['ftp_support'] = (function_exists('ftp_connect') and !in_array('ftp_connect', $sys_info['disable_functions'], true) and function_exists('ftp_chmod') and !in_array('ftp_chmod', $sys_info['disable_functions'], true) and function_exists('ftp_mkdir') and !in_array('ftp_mkdir', $sys_info['disable_functions'], true) and function_exists('ftp_chdir') and !in_array('ftp_chdir', $sys_info['disable_functions'], true) and function_exists('ftp_nlist') and !in_array('ftp_nlist', $sys_info['disable_functions'], true)) ? true : false;
    $content_config .= "\$sys_info['ftp_support'] = " . ($sys_info['ftp_support'] ? 'true' : 'false') . ";\n";

    //Xac dinh tien ich mo rong lam viec voi string
    $sys_info['string_handler'] = $sys_info['mb_support'] ? 'mb' : ($sys_info['iconv_support'] ? 'iconv' : 'php');
    $content_config .= "\$sys_info['string_handler'] = '" . $sys_info['string_handler'] . "';\n";

    //support_cache
    $sys_info['support_cache'] = [];
    if (class_exists('Memcached') and !in_array('Memcached', $sys_info['disable_classes'], true)) {
        $sys_info['support_cache'][] = 'memcached';
    }
    if (class_exists('Memcache') and !in_array('Memcache', $sys_info['disable_classes'], true)) {
        $sys_info['support_cache'][] = 'memcache';
    }
    if (class_exists('Redis') and !in_array('Redis', $sys_info['disable_classes'], true)) {
        $sys_info['support_cache'][] = 'redis';
    }
    $content_config .= "\$sys_info['support_cache'] = [" . ($sys_info['support_cache'] ? "'" . implode("', '", $sys_info['support_cache']) . "'" : '') . "];\n";

    //php_compress_methods
    $sys_info['php_compress_methods'] = [];
    if (function_exists('brotli_compress') and !in_array('brotli_compress', $sys_info['disable_functions'], true)) {
        $sys_info['php_compress_methods']['br'] = 'brotli_compress';
    }
    if (function_exists('gzdeflate') and !in_array('gzdeflate', $sys_info['disable_functions'], true)) {
        $sys_info['php_compress_methods']['deflate'] = 'gzdeflate';
    }
    if (function_exists('gzencode') and !in_array('gzencode', $sys_info['disable_functions'], true)) {
        $sys_info['php_compress_methods']['gzip'] = 'gzencode';
        $sys_info['php_compress_methods']['x-gzip'] = 'gzencode';
    }
    if (function_exists('gzcompress') and !in_array('gzcompress', $sys_info['disable_functions'], true)) {
        $sys_info['php_compress_methods']['compress'] = 'gzcompress';
        $sys_info['php_compress_methods']['x-compress'] = 'gzcompress';
    }
    $_compress_method = '';
    if (!empty($sys_info['php_compress_methods'])) {
        $_compress_method = [];
        foreach ($sys_info['php_compress_methods'] as $k => $f) {
            $_compress_method[] = "'" . $k . "' => '" . $f . "'";
        }
        $_compress_method = implode(', ', $_compress_method);
    }
    $content_config .= "\$sys_info['php_compress_methods'] = [" . $_compress_method . "];\n";

    $_temp = [];
    if (!empty($sys_info['server_headers'])) {
        foreach ($sys_info['server_headers'] as $key => $value) {
            $_temp[] = "'" . addslashes($key) . "' => '" . addslashes($value) . "'";
        }
    }
    $_temp = !empty($_temp) ? implode(',', $_temp) : '';
    $content_config .= "\$sys_info['server_headers'] = [" . $_temp . "];\n";
    $content_config .= "\$sys_info['is_http2'] = " . ($sys_info['is_http2'] ? 'true' : 'false') . ";\n";
    $content_config .= "\$sys_info['http_only'] = " . ($sys_info['http_only'] ? 'true' : 'false') . ";\n";
    $content_config .= "\$sys_info['https_only'] = " . ($sys_info['https_only'] ? 'true' : 'false') . ";\n";

    // Kiểm tra PHP hỗ trợ xử lý IPv6
    if (!((extension_loaded('sockets') and defined('AF_INET6')) or @inet_pton('::1'))) {
        $sys_info['ip6_support'] = false;
    } else {
        $sys_info['ip6_support'] = true;
    }
    $content_config .= "\$sys_info['ip6_support'] = " . ($sys_info['ip6_support'] ? 'true' : 'false') . ";\n";

    $ini_set = [];
    if ($sys_info['ini_set_support']) {
        check_ini($ini_set, 'display_startup_errors', 0);

        if (version_compare(PHP_VERSION, '8.0.0', '<')) {
            check_ini($ini_set, 'track_errors', 1);
        }

        check_ini($ini_set, 'log_errors', 0);
        check_ini($ini_set, 'display_errors', 0);

        $session_save_handler = ini_get('session.save_handler');
        $session_save_path = ini_get('session.save_path');
        if (strcasecmp($global_config['session_handler'], $session_save_handler) != 0) {
            if ($global_config['session_handler'] == 'memcached' and in_array('memcached', $sys_info['support_cache'], true) and !empty($global_config['memcached_host']) and !empty($global_config['memcached_port']) ) {
                ini_set('session.save_handler', 'memcached');
                $session_save_path != $global_config['memcached_host'] . ':' . $global_config['memcached_port'] && ini_set('session.save_path', $global_config['memcached_host'] . ':' . $global_config['memcached_port']);
                $new_session_save_handler = ini_get('session.save_handler');
                $new_session_save_path = ini_get('session.save_path');
                if ($new_session_save_handler == 'memcached' and $new_session_save_path == $global_config['memcached_host'] . ':' . $global_config['memcached_port']) {
                    $ini_set['session.save_handler'] = 'memcached';
                    $new_session_save_path != $session_save_path && $ini_set['session.save_path'] = $global_config['memcached_host'] . ':' . $global_config['memcached_port'];
                    $session_save_handler = $new_session_save_handler;
                    $session_save_path = $new_session_save_path;
                } else {
                    ini_set('session.save_handler', $session_save_handler);
                    $new_session_save_path != $session_save_path && ini_set('session.save_path', $session_save_path);
                }
            } elseif ($global_config['session_handler'] == 'redis' and in_array('redis', $sys_info['support_cache'], true) and !empty($global_config['redis_host']) and !empty($global_config['redis_port'])) {
                ini_set('session.save_handler', 'redis');
                $session_save_path != $global_config['redis_host'] . ':' . $global_config['redis_port'] && ini_set('session.save_path', $global_config['redis_host'] . ':' . $global_config['redis_port']);
                $new_session_save_handler = ini_get('session.save_handler');
                $new_session_save_path = ini_get('session.save_path');
                if ($new_session_save_handler == 'redis' and $new_session_save_path == $global_config['redis_host'] . ':' . $global_config['redis_port']) {
                    $ini_set['session.save_handler'] = 'redis';
                    $new_session_save_path != $session_save_path && $ini_set['session.save_path'] = $global_config['redis_host'] . ':' . $global_config['redis_port'];
                    $session_save_handler = $new_session_save_handler;
                    $session_save_path = $new_session_save_path;
                } else {
                    ini_set('session.save_handler', $session_save_handler);
                    $new_session_save_path != $session_save_path && ini_set('session.save_path', $session_save_path);
                }
            }
        }

        if (!isset($_SESSION)) {
            if (strcasecmp($session_save_handler, 'memcached') == 0) {
                check_ini($ini_set, 'memcached.sess_prefix', 'nv');
                check_ini($ini_set, 'memcached.sess_locking', '1');
                check_ini($ini_set, 'memcached.sess_binary_protocol', 'Off');
            }

            check_ini($ini_set, 'session.use_trans_sid', 0);
            check_ini($ini_set, 'session.auto_start', 0);
            check_ini($ini_set, 'session.use_cookies', 1);
            check_ini($ini_set, 'session.use_only_cookies', 1);
            check_ini($ini_set, 'session.cookie_httponly', 1);
            check_ini($ini_set, 'session.gc_probability', 1);

            //Kha nang chay Garbage Collection - trinh xoa session da het han truoc khi bat dau session_start();
            check_ini($ini_set, 'session.gc_divisor', 1000);
            //gc_probability / gc_divisor = phan tram (phan nghin) kha nang chay Garbage Collection
            check_ini($ini_set, 'session.gc_maxlifetime', 3600);
            //thoi gian sau khi het han phien lam viec de Garbage Collection tien hanh xoa, 60 phut
            check_ini($ini_set, 'session.cache_limiter', 'nocache');
        }

        check_ini($ini_set, 'allow_url_fopen', 1);
        check_ini($ini_set, 'user_agent', 'NV4');
        check_ini($ini_set, 'default_charset', strtoupper($global_config['site_charset']));

        $memory_limit = ini_get('memory_limit');
        if ((int) $memory_limit < 64) {
            if (ini_set('memory_limit', '64M') !== false) {
                (int) ini_get('memory_limit') == 64 && $ini_set['memory_limit'] = '64M';
            }
        }

        check_ini($ini_set, 'arg_separator.output', '&');
        check_ini($ini_set, 'auto_detect_line_endings', 0);
    }

    if (!empty($ini_set)) {
        $content_config .= "\n";
        foreach ($ini_set as $key => $value) {
            $content_config .= "ini_set('" . $key . "', '" . $value . "');\n";
        }
    }

    $content_config .= "\n";
    $content_config .= "\$serverInfoUpdated = false;\n";
    $content_config .= '$iniSaveTime = ' . NV_CURRENTTIME . ';';

    if (file_put_contents($config_ini_file, $content_config . "\n", LOCK_EX)) {
        if ($sys_info['curl_support']) {
            $url = NV_BASE_SITEURL . 'index.php';
            stripos($url, NV_MY_DOMAIN) !== 0 && $url = NV_MY_DOMAIN . $url;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '__serverInfoUpdate=1');
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Referer: ' . NV_MY_DOMAIN]);
            curl_exec($ch);
            curl_close($ch);
        }
    }
}

$config_ini_file = NV_ROOTDIR . '/' . NV_DATADIR . '/config_ini.' . NV_SERVER_PROTOCOL . '.' . preg_replace('/[^a-zA-Z0-9\.\_]/', '', NV_SERVER_NAME) . '.php';
if (isset($_POST['__serverInfoUpdate'])) {
    server_info_update($config_ini_file);
    exit(0);
}

$sys_info['server_headers'] = [];
$sys_info['is_http2'] = false;
$sys_info['http_only'] = false;
$sys_info['https_only'] = false;
$serverInfoUpdated = false;
$iniSaveTime = 0;

@include_once $config_ini_file;

if ($iniSaveTime + 86400 < NV_CURRENTTIME) {
    set_ini_file($sys_info);
}

//Neu he thong khong ho tro php se bao loi
if (version_compare(PHP_VERSION, '5.6.0') < 0) {
    http_response_code(500);
    trigger_error('You are running an unsupported PHP version. Please upgrade to PHP 5.6 or higher before trying to install Nukeviet Portal', 256);
}

//Neu he thong khong ho tro curl se bao loi
if (!(extension_loaded('curl') and (empty($sys_info['disable_functions']) or (!empty($sys_info['disable_functions']) and !preg_grep('/^curl\_/', $sys_info['disable_functions']))))) {
    http_response_code(500);
    trigger_error('The cURL library is not installed or its underlying functions are blocked', 256);
}

//Neu he thong khong ho tro opendir se bao loi
if (!(function_exists('opendir') and !in_array('opendir', $sys_info['disable_functions'], true))) {
    http_response_code(500);
    trigger_error('Opendir function is not supported', 256);
}

//Neu he thong khong ho tro GD se bao loi
if (!(extension_loaded('gd'))) {
    http_response_code(500);
    trigger_error('GD not installed', 256);
}

//Neu he thong khong ho tro json se bao loi
if (!extension_loaded('json')) {
    http_response_code(500);
    trigger_error('Json object not supported', 256);
}

//Neu he thong khong ho tro xml se bao loi
if (!extension_loaded('xml')) {
    http_response_code(500);
    trigger_error('Xml library not supported', 256);
}

//Neu he thong khong ho tro openssl encrypt se bao loi
if (!function_exists('openssl_encrypt')) {
    http_response_code(500);
    trigger_error('Openssl library not available', 256);
}

//Neu he thong khong ho tro session se bao loi
$session_save_handler = ini_get('session.save_handler');
$session_save_path = ini_get('session.save_path');
if (!extension_loaded('session') or empty($session_save_handler) or ($session_save_handler != 'files' and empty($session_save_path))) {
    http_response_code(500);
    trigger_error('Session object not supported', 256);
}
