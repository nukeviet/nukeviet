<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * curl_get_headers()
 * 
 * @param mixed $url 
 * @return array 
 */
function curl_get_headers($url)
{
    if (!defined('CURL_HTTP_VERSION_2_0')) {
        define('CURL_HTTP_VERSION_2_0', 3);
    }

    $headers = [];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
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
        foreach ($header_text as $k => $line) {
            if ($k == 0) {
                $headers['http_code'] = trim($line);
            } else {
                list($key, $value) = explode(': ', $line);
                $key = strtolower(trim($key));
                if (!empty($key)) {
                    $headers[$key] = $value;
                }
            }
        }
    }
    curl_close($ch);

    return $headers;
}

/**
 * server_info_update()
 * 
 */
function server_info_update()
{
    global $nv_Server, $config_ini_file;

    $proto = $nv_Server->getOriginalProtocol();
    $proto2 = ($proto == 'https') ? 'http' : 'https';
    $host = $nv_Server->getOriginalHost();
    if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
        $host = '[' . $host . ']';
    }

    $unset = ['http_code', 'date', 'expires', 'last-modified', 'connection', 'set-cookie', 'x-page-speed', 'x-powered-by', 'x-is-http', 'x-is-https'];

    $headers = curl_get_headers($proto . '://' . $host . NV_BASE_SITEURL . 'index.php?response_headers_detect=1');
    $is_http2 = (!empty($headers['http_code']) and strpos($headers['http_code'], 'HTTP/2') === 0) ? true : false;

    $server_headers = [];
    if (!empty($headers)) {
        foreach ($headers as $key => $value) {
            if (!in_array($key, $unset, true)) {
                $server_headers[] = "'" . addslashes($key) . "' => '" . addslashes($value) . "'";
            }
        }
    }
    $server_headers = !empty($server_headers) ? implode(',', $server_headers) : '';

    $http_only = false;
    $https_only = false;
    $headers = curl_get_headers($proto2 . '://' . $host . NV_BASE_SITEURL . 'index.php?response_headers_detect=1');
    if ($proto == 'https') {
        $https_only = !empty($headers['x-is-http']) ? false : true;
    } else {
        $http_only = !empty($headers['x-is-https']) ? false : true;
    }

    $contents = file_get_contents($config_ini_file);
    $contents = preg_replace('/(\$sys\_info\[\'server\_headers\'\]\s*\=\s*\[)[^\]]*(\]\;)/', '\\1' . $server_headers . '\\2', $contents);
    $contents = preg_replace('/(\$sys\_info\[\'is\_http2\'\]\s*\=\s*)[^\;]+\;/', '\\1' . ($is_http2 ? 'true' : 'false') . ';', $contents);
    $contents = preg_replace('/(\$sys\_info\[\'http\_only\'\]\s*\=\s*)[^\;]+\;/', '\\1' . ($http_only ? 'true' : 'false') . ';', $contents);
    $contents = preg_replace('/(\$sys\_info\[\'https\_only\'\]\s*\=\s*)[^\;]+\;/', '\\1' . ($https_only ? 'true' : 'false') . ';', $contents);
    $contents = preg_replace('/(\$serverInfoUpdated\s*\=\s*)[^\;]+\;/', '\\1true;', $contents);

    @file_put_contents($config_ini_file, $contents, LOCK_EX);
}

server_info_update();
exit(0);
