<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

$url = NV_MY_DOMAIN . NV_BASE_SITEURL;
$params = [
    'token' => 'ADG',
    'cds' => 'dsd',
    'anhtu' => [
        'a' => '1',
        'b' => 2
    ]
];

$params['zalo'] = $global_config['zaloAppID'];
ksort($params);
$post_string = http_build_query($params);

$parts = parse_url($url);
$is_https = ($parts['scheme'] === 'https');
$url = ($is_https ? 'ssl://' : '') . $parts['host'];
$port = isset($parts['port']) ? $parts['port'] : ($is_https ? 443 : 80);
$referer = $parts['scheme'] . '://' . $parts['host'] . ($is_https ? ':' . 443 : '');

$fp = fsockopen($url, $port, $errno, $errstr, 30);

$out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
$out .= "Host: " . $parts['host'] . "\r\n";
$out .= "User-Agent: NUKEVIET\r\n";
$out .= "Referer: " . $referer . "\r\n";
$out .= "X-Nukeviet-Signature: " . hash("sha256", mb_convert_encoding($post_string, 'UTF-8') . $global_config['zaloOASecretKey'] . $global_config['sitekey']) . "\r\n";
$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
$out .= "Content-Length: " . strlen($post_string) . "\r\n";
$out .= "Connection: Close\r\n\r\n";
$out .= $post_string;

fwrite($fp, $out);

$response = '';

while (!@feof($fp)) {
    $response .= @fgets($fp, 4096);
}

fclose($fp);
print_r($response);
