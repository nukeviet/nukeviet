<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Http;

use ValueError;

/**
 * NukeViet\Http\Streams
 *
 * @package NukeViet\Http
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Streams
{
    /**
     * request()
     *
     * @param mixed $url
     * @param array $args
     * @return mixed
     * @throws ValueError
     */
    public function request($url, $args = [])
    {
        $defaults = [
            'method' => 'GET',
            'timeout' => 5,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => [],
            'body' => null,
            'cookies' => []
        ];

        $args = Http::build_args($args, $defaults);

        // Get user agent
        if (isset($args['headers']['User-Agent'])) {
            $args['user-agent'] = $args['headers']['User-Agent'];
            unset($args['headers']['User-Agent']);
        } elseif (isset($args['headers']['user-agent'])) {
            $args['user-agent'] = $args['headers']['user-agent'];
            unset($args['headers']['user-agent']);
        }

        // Get Referer
        if (isset($args['headers']['Referer'])) {
            $args['referer'] = $args['headers']['Referer'];
            unset($args['headers']['Referer']);
        } elseif (isset($args['headers']['referer'])) {
            $args['referer'] = $args['headers']['referer'];
            unset($args['headers']['referer']);
        }

        // Construct Cookie: header if any cookies are set
        Http::buildCookieHeader($args);

        $arrURL = parse_url($url);

        $connect_host = $arrURL['host'];

        $secure_transport = ($arrURL['scheme'] == 'ssl' or $arrURL['scheme'] == 'https');
        if (!isset($arrURL['port'])) {
            if ($arrURL['scheme'] == 'ssl' or $arrURL['scheme'] == 'https') {
                $arrURL['port'] = 443;
                $secure_transport = true;
            } else {
                $arrURL['port'] = 80;
            }
        }

        if (isset($args['headers']['Host']) or isset($args['headers']['host'])) {
            if (isset($args['headers']['Host'])) {
                $arrURL['host'] = $args['headers']['Host'];
            } else {
                $arrURL['host'] = $args['headers']['host'];
            }

            unset($args['headers']['Host'], $args['headers']['host']);
        }

        // Certain versions of PHP have issues with 'localhost' and IPv6, It attempts to connect to ::1,
        // which fails when the server is not set up for it. For compatibility, always connect to the IPv4 address.
        if (strtolower($connect_host) == 'localhost') {
            $connect_host = '127.0.0.1';
        }

        $connect_host = $secure_transport ? 'ssl://' . $connect_host : 'tcp://' . $connect_host;

        $is_local = (isset($args['local']) and $args['local']);
        $ssl_verify = (isset($args['sslverify']) and $args['sslverify']);

        // NukeViet has no proxy setup
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => $ssl_verify,
                //'CN_match' => $arrURL['host'], // This is handled by self::verify_ssl_certificate()
                'capture_peer_cert' => $ssl_verify,
                'SNI_enabled' => true,
                'cafile' => $args['sslcertificates'],
                'allow_self_signed' => !$ssl_verify,
            ]
        ]);

        $timeout = (int) floor($args['timeout']);
        $utimeout = $timeout == $args['timeout'] ? 0 : 1000000 * $args['timeout'] % 1000000;
        $connect_timeout = max($timeout, 1);

        $connection_error = null; // Store error number
        $connection_error_str = null; // Store error string

        // In the event that the SSL connection fails, silence the many PHP Warnings
        if ($secure_transport) {
            $error_reporting = error_reporting(0);
        }

        // No proxy option on NukeViet, maybe in future!!!!
        //if( $proxy->is_enabled() and $proxy->send_through_proxy( $url ) )
        //{
        //	$handle = @stream_socket_client( 'tcp://' . $proxy->host() . ':' . $proxy->port(), $connection_error, $connection_error_str, $connect_timeout, STREAM_CLIENT_CONNECT, $context );
        //}
        //else
        //{
        $handle = @stream_socket_client($connect_host . ':' . $arrURL['port'], $connection_error, $connection_error_str, $connect_timeout, STREAM_CLIENT_CONNECT, $context);
        //}

        if ($secure_transport) {
            error_reporting($error_reporting);
        }

        if ($handle === false) {
            // SSL connection failed due to expired/invalid cert, or, OpenSSL configuration is broken
            if ($secure_transport and $connection_error === 0 and $connection_error_str === '') {
                Http::set_error(6);

                return false;
            }

            Http::set_error(7);

            return false;
        }

        // Verify that the SSL certificate is valid for this request
        if ($secure_transport and $ssl_verify /* and ! $proxy->is_enabled() */) {
            if (!self::verify_ssl_certificate($handle, $arrURL['host'])) {
                Http::set_error(6);

                return false;
            }
        }

        stream_set_timeout($handle, $timeout, $utimeout);

        //if( $proxy->is_enabled() and $proxy->send_through_proxy( $url ) )
        //{
        //	//Some proxies require full URL in this field.
        //	$requestPath = $url;
        //}
        //else
        //{
        $requestPath = $arrURL['path'] . (isset($arrURL['query']) ? '?' . $arrURL['query'] : '');
        //}

        if (empty($requestPath)) {
            $requestPath .= '/';
        }

        $strHeaders = strtoupper($args['method']) . ' ' . $requestPath . ' HTTP/' . $args['httpversion'] . "\r\n";

        //if( $proxy->is_enabled() and $proxy->send_through_proxy( $url ) )
        //{
        //	$strHeaders .= 'Host: ' . $arrURL['host'] . ':' . $arrURL['port'] . "\r\n";
        //}
        //else
        //{
        $strHeaders .= 'Host: ' . $arrURL['host'] . "\r\n";
        //}

        if (isset($args['user-agent'])) {
            $strHeaders .= 'User-agent: ' . $args['user-agent'] . "\r\n";
        }

        // Add referer if not empty
        if (!empty($args['referer'])) {
            $strHeaders .= 'Referer: ' . $args['referer'] . "\r\n";
        }

        if (is_array($args['headers'])) {
            foreach ((array) $args['headers'] as $header => $headerValue) {
                $strHeaders .= $header . ': ' . $headerValue . "\r\n";
            }
        } else {
            $strHeaders .= $args['headers'];
        }

        //if( $proxy->use_authentication() )
        //{
        //	$strHeaders .= $proxy->authentication_header() . "\r\n";
        //}

        $strHeaders .= "\r\n";

        if (!is_null($args['body'])) {
            $strHeaders .= $args['body'];
        }

        fwrite($handle, $strHeaders);

        if (!$args['blocking']) {
            stream_set_blocking($handle, 0);
            fclose($handle);

            return ['headers' => [], 'body' => '', 'response' => ['code' => false, 'message' => false], 'cookies' => []];
        }

        $strResponse = '';
        $bodyStarted = false;
        $keep_reading = true;
        $block_size = 4096;
        if (isset($args['limit_response_size'])) {
            $block_size = min($block_size, $args['limit_response_size']);
        }

        // If streaming to a file setup the file handle
        if ($args['stream']) {
            $stream_handle = @fopen($args['filename'], 'w+');

            if (!$stream_handle) {
                Http::set_error(8);

                return false;
            }

            $bytes_written = 0;
            while (!feof($handle) and $keep_reading) {
                $block = fread($handle, $block_size);

                if (!$bodyStarted) {
                    $strResponse .= $block;

                    if (strpos($strResponse, "\r\n\r\n")) {
                        $process = Http::processResponse($strResponse);
                        $bodyStarted = true;
                        $block = $process['body'];
                        unset($strResponse);
                        $process['body'] = '';
                    }
                }

                $this_block_size = strlen($block);

                if (isset($args['limit_response_size']) and ($bytes_written + $this_block_size) > $args['limit_response_size']) {
                    $block = substr($block, 0, ($args['limit_response_size'] - $bytes_written));
                }

                $bytes_written_to_file = fwrite($stream_handle, $block);

                if ($bytes_written_to_file != $this_block_size) {
                    fclose($handle);
                    fclose($stream_handle);
                    Http::set_error(9);

                    return false;
                }

                $bytes_written += $bytes_written_to_file;

                $keep_reading = (!isset($args['limit_response_size']) or $bytes_written < $args['limit_response_size']);
            }

            fclose($stream_handle);
        } else {
            $header_length = 0;

            // Not end file and some one
            while (!feof($handle) and $keep_reading) {
                $block = fread($handle, $block_size);
                $strResponse .= $block;

                if (!$bodyStarted and strpos($strResponse, "\r\n\r\n")) {
                    $header_length = strpos($strResponse, "\r\n\r\n") + 4;
                    $bodyStarted = true;
                }

                $keep_reading = (!$bodyStarted or !isset($args['limit_response_size']) or strlen($strResponse) < ($header_length + $args['limit_response_size']));
            }

            $process = Http::processResponse($strResponse);
            unset($strResponse);
        }

        fclose($handle);

        $arrHeaders = Http::processHeaders($process['headers'], $url);

        $response = [
            'headers' => $arrHeaders['headers'],
            'body' => null, // Not yet processed
            'response' => $arrHeaders['response'],
            'cookies' => $arrHeaders['cookies'],
            'filename' => $args['filename']
        ];

        // Handle redirects
        if (false !== ($redirect_response = Http::handle_redirects($url, $args, $response))) {
            return $redirect_response;
        }

        // If the body was chunk encoded, then decode it.
        if (!empty($process['body']) and isset($arrHeaders['headers']['transfer-encoding']) and 'chunked' == $arrHeaders['headers']['transfer-encoding']) {
            $process['body'] = Http::chunkTransferDecode($process['body']);
        }

        if ($args['decompress'] === true and Encoding::should_decode($arrHeaders['headers']) === true) {
            $process['body'] = Encoding::decompress($process['body']);
        }

        if (isset($args['limit_response_size']) and strlen($process['body']) > $args['limit_response_size']) {
            $process['body'] = substr($process['body'], 0, $args['limit_response_size']);
        }

        $response['body'] = str_replace("\xEF\xBB\xBF", '', $process['body']);

        return $response;
    }

    /**
     * verify_ssl_certificate()
     *
     * @param mixed $stream
     * @param mixed $host
     * @return bool
     */
    public static function verify_ssl_certificate($stream, $host)
    {
        $context_options = stream_context_get_options($stream);

        if (empty($context_options['ssl']['peer_certificate'])) {
            return false;
        }

        $cert = openssl_x509_parse($context_options['ssl']['peer_certificate']);
        if (!$cert) {
            return false;
        }

        // If the request is being made to an IP address, we'll validate against IP fields in the cert (if they exist)
        $host_type = (Http::is_ip_address($host) ? 'ip' : 'dns');

        $certificate_hostnames = [];

        if (!empty($cert['extensions']['subjectAltName'])) {
            $match_against = preg_split('/,\s*/', $cert['extensions']['subjectAltName']);

            foreach ($match_against as $match) {
                list($match_type, $match_host) = explode(':', $match);
                if ($host_type == strtolower(trim($match_type))) {
                    // IP: or DNS:

                    $certificate_hostnames[] = strtolower(trim($match_host));
                }
            }
        } elseif (!empty($cert['subject']['CN'])) {
            // Only use the CN when the certificate includes no subjectAltName extension
            $certificate_hostnames[] = strtolower($cert['subject']['CN']);
        }

        // Exact hostname/IP matches
        if (in_array(strtolower($host), $certificate_hostnames, true)) {
            return true;
        }

        // IP's can't be wildcards, Stop processing
        if ($host_type == 'ip') {
            return false;
        }

        // Test to see if the domain is at least 2 deep for wildcard support
        if (substr_count($host, '.') < 2) {
            return false;
        }

        // Wildcard subdomains certs (*.example.com) are valid for a.example.com but not a.b.example.com
        $wildcard_host = preg_replace('/^[^.]+\./', '*.', $host);

        return in_array(strtolower($wildcard_host), $certificate_hostnames, true);
    }

    /**
     * test()
     *
     * @param array $args
     * @return bool
     */
    public static function test($args = [])
    {
        if (!function_exists('stream_socket_client')) {
            return false;
        }

        $is_ssl = (isset($args['ssl']) and $args['ssl']);

        if ($is_ssl) {
            if (!extension_loaded('openssl')) {
                return false;
            }

            if (!function_exists('openssl_x509_parse')) {
                return false;
            }
        }

        return true;
    }
}
