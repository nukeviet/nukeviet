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

nv_add_hook($module_name, 'zalo_webhook', $priority, function (): void {
    global $global_config, $nv_Request;

    if ($nv_Request->isset_request('zalo', 'get')) {
        $zaloAppID = $nv_Request->get_string('zalo', 'get', '');

        if ($zaloAppID == $global_config['zaloAppID']) {
            if ($_SERVER['HTTP_USER_AGENT'] == 'ZaloWebhook') {
                $signature = !empty($_SERVER['HTTP_X_ZEVENT_SIGNATURE']) ? substr(trim($_SERVER['HTTP_X_ZEVENT_SIGNATURE']), 4) : '';
                if (!empty($signature)) {
                    $json = @file_get_contents('php://input');
                    $params = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
                    $my_signature = hash('sha256', $params['app_id'] . mb_convert_encoding($json, 'UTF-8') . $params['timestamp'] . $global_config['zaloOASecretKey']);
                    if (strcmp($my_signature, $signature) === 0) {
                        $params['zalo'] = $global_config['zaloAppID'];
                        $parts = parse_url(NV_MY_DOMAIN . NV_BASE_SITEURL);

                        // Fire and Forget POST Request
                        ksort($params);
                        $post_string = http_build_query($params);
                        $is_https = ($parts['scheme'] === 'https');
                        $referer = $parts['scheme'] . '://' . $parts['host'];
                        if (!$is_https) {
                            $port = $parts['port'] ?? 80;
                            $host = $parts['host'] . ($port != 80 ? ':' . $port : '');
                            isset($parts['port']) && $referer .= ':' . $parts['port'];
                            $fp = fsockopen($parts['host'], $port, $errno, $errstr, 30);
                        } else {
                            $context = stream_context_create([
                                'ssl' => [
                                    'verify_peer' => false,
                                    'verify_peer_name' => false
                                ]
                            ]);
                            $port = $parts['port'] ?? 443;
                            $host = $parts['host'] . ($port != 443 ? ':' . $port : '');
                            $referer .= ':' . ($parts['port'] ?? 443);
                            $fp = stream_socket_client('ssl://' . $parts['host'] . ':' . $port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
                        }

                        $path = $parts['path'] ?? '/';
                        if (isset($parts['query'])) {
                            $path .= '?' . $parts['query'];
                        }

                        $out = 'POST ' . $path . " HTTP/1.1\r\n";
                        $out .= 'Host: ' . $host . "\r\n";
                        $out .= "User-Agent: NUKEVIET\r\n";
                        $out .= 'Referer: ' . $referer . "\r\n";
                        $out .= 'X-Nukeviet-Signature: ' . hash('sha256', mb_convert_encoding($post_string, 'UTF-8') . $global_config['zaloOASecretKey'] . $global_config['sitekey']) . "\r\n";
                        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
                        $out .= 'Content-Length: ' . strlen($post_string) . "\r\n";
                        $out .= "Connection: Close\r\n\r\n";
                        $out .= $post_string;

                        fwrite($fp, $out);
                        if ($is_https) {
                            stream_set_timeout($fp, 1);
                            stream_get_contents($fp, -1);
                        }
                        fclose($fp);
                    }
                }
                http_response_code(200);
                exit();
            }
        }
    } elseif ($nv_Request->isset_request('zalo', 'post')) {
        $zaloAppID = $nv_Request->get_string('zalo', 'post', '');

        if ($zaloAppID == $global_config['zaloAppID']) {
            if ($_SERVER['HTTP_USER_AGENT'] == 'NUKEVIET') {
                $signature = !empty($_SERVER['HTTP_X_NUKEVIET_SIGNATURE']) ? $_SERVER['HTTP_X_NUKEVIET_SIGNATURE'] : '';
                if (!empty($signature)) {
                    $webhook_data = $_POST;
                    ksort($webhook_data);
                    $my_signature = hash('sha256', mb_convert_encoding(http_build_query($webhook_data), 'UTF-8') . $global_config['zaloOASecretKey'] . $global_config['sitekey']);
                    if (strcmp($my_signature, $signature) === 0) {
                        !empty($webhook_data['event_name']) && $webhook_data['event_name'] = preg_replace('/[^a-z0-9\_]+/', '', $webhook_data['event_name']);
                        if (!empty($webhook_data['app_id']) and $webhook_data['app_id'] == $global_config['zaloAppID'] and !empty($webhook_data['event_name'])) {
                            require NV_ROOTDIR . '/modules/zalo/inc.php';
                        }
                    }
                }
                http_response_code(200);
                exit();
            }
        }
    }
});
