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

/**
 * NukeViet\Http\Curl
 *
 * @package NukeViet\Http
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Curl
{
    /**
     * Temporary header storage for during requests.
     * @access private
     * @var string
     */
    private $headers = '';

    /**
     * Temporary body storage for during requests.
     * @access private
     * @var string
     */
    private $body = '';

    /**
     * The maximum amount of data to recieve from the remote server
     * @access private
     * @var int
     */
    private $max_body_length = false;

    /**
     * The file resource used for streaming to file.
     * @access private
     * @var resource
     */
    private $stream_handle = false;

    /**
     * The error code and error message.
     * @access public
     * @var array
     */
    public $error = [];

    /**
     * request()
     *
     * @param string $url
     * @param array  $args
     * @return mixed
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

        // Get User Agent
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

        // Construct Cookie: header if any cookies are set.
        Http::buildCookieHeader($args);

        $handle = curl_init();

        /*
        // No Proxy setting so proxy be omitted
        // cURL offers really easy proxy support.
        $proxy = new Http_proxy();

        if( $proxy->is_enabled() and $proxy->send_through_proxy( $url ) )
        {
            curl_setopt( $handle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP );
            curl_setopt( $handle, CURLOPT_PROXY, $proxy->host() );
            curl_setopt( $handle, CURLOPT_PROXYPORT, $proxy->port() );

            if( $proxy->use_authentication() )
            {
                curl_setopt( $handle, CURLOPT_PROXYAUTH, CURLAUTH_ANY );
                curl_setopt( $handle, CURLOPT_PROXYUSERPWD, $proxy->authentication() );
            }
        }
         */

        //$is_local = (isset($args['local']) and $args['local']);
        if (!empty($args['sslverify'])) {
            if (!empty($args['sslcertificates'])) {
                $cainfo = $args['sslcertificates'];
            } else {
                $cainfo = ini_get('curl.cainfo');
                if (empty($cainfo)) {
                    $cainfo = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/cacert.pem';
                }
            }
            $ssl_verify = !empty($cainfo);
        } else {
            $ssl_verify = false;
        }

        // CURLOPT_TIMEOUT and CURLOPT_CONNECTTIMEOUT expect integers. Have to use ceil since
        // a value of 0 will allow an unlimited timeout.
        $timeout = (int) ceil($args['timeout']);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($handle, CURLOPT_TIMEOUT, $timeout);

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, ($ssl_verify === true) ? 2 : false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, $ssl_verify);
        if ($ssl_verify) {
            curl_setopt($handle, CURLOPT_CAINFO, $cainfo);
        }
        !empty($args['user-agent']) && curl_setopt($handle, CURLOPT_USERAGENT, $args['user-agent']);

        // Add Curl referer if not empty
        if (!empty($args['referer'])) {
            curl_setopt($handle, CURLOPT_AUTOREFERER, true);
            curl_setopt($handle, CURLOPT_REFERER, $args['referer']);
        }

        // The option doesn't work with safe mode or when open_basedir is set, and there's a
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, false);

        if (defined('CURLOPT_PROTOCOLS')) {
            // PHP 5.2.10 / cURL 7.19.4
            curl_setopt($handle, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        }

        switch ($args['method']) {
            case 'HEAD':
                curl_setopt($handle, CURLOPT_NOBODY, true);
                break;
            case 'POST':
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, $args['body']);
                break;
            case 'PUT':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($handle, CURLOPT_POSTFIELDS, $args['body']);
                break;
            default:
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, $args['method']);

                if (!is_null($args['body'])) {
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $args['body']);
                }

                break;
        }

        if ($args['blocking'] === true) {
            curl_setopt($handle, CURLOPT_HEADERFUNCTION, [$this, 'stream_headers']);
            curl_setopt($handle, CURLOPT_WRITEFUNCTION, [$this, 'stream_body']);
        }

        curl_setopt($handle, CURLOPT_HEADER, false);

        if (isset($args['limit_response_size'])) {
            $this->max_body_length = (int) ($args['limit_response_size']);
        } else {
            $this->max_body_length = false;
        }

        // If streaming to a file open a file handle, and setup our curl streaming handler
        if (!empty($args['stream'])) {
            $this->stream_handle = @fopen($args['filename'], 'w+');

            if (!$this->stream_handle) {
                Http::set_error(10);

                return $this;
            }
        } else {
            $this->stream_handle = false;
        }

        if (!empty($args['headers'])) {
            // cURL expects full header strings in each element
            $headers = [];
            foreach ($args['headers'] as $name => $value) {
                $headers[] = "{$name}: $value";
            }

            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        }

        if ($args['httpversion'] == '1.0') {
            curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        } else {
            curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        }

        // We don't need to return the body, so don't. Just execute request and return.
        if (!$args['blocking']) {
            curl_exec($handle);

            if ($curl_error = curl_error($handle)) {
                curl_close($handle);

                Http::set_error(11);

                return $this;
            }

            if (in_array((int) curl_getinfo($handle, CURLINFO_HTTP_CODE), [301, 302], true)) {
                curl_close($handle);

                Http::set_error(5);

                return $this;
            }

            curl_close($handle);

            return ['headers' => [], 'body' => '', 'response' => ['code' => false, 'message' => false], 'cookies' => []];
        }

        $theResponse = curl_exec($handle);
        $theHeaders = Http::processHeaders($this->headers, $url);
        $theBody = $this->body;

        $this->headers = '';
        $this->body = '';

        $curl_error = curl_errno($handle);

        // If an error occured, or, no response
        if ($curl_error or (strlen($theBody) == 0 and empty($theHeaders['headers']))) {
            if (CURLE_WRITE_ERROR /* 23 */ == $curl_error and $args['stream']) {
                fclose($this->stream_handle);

                Http::set_error(9);

                return $this;
            }

            if ($curl_error = curl_error($handle)) {
                curl_close($handle);

                Http::set_error(11);

                return $this;
            }

            if (in_array((int) curl_getinfo($handle, CURLINFO_HTTP_CODE), [301, 302], true)) {
                curl_close($handle);

                Http::set_error(5);

                return $this;
            }
        }

        $response = [];
        $response['code'] = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $response['message'] = $response['code'];

        curl_close($handle);

        if (!empty($args['stream'])) {
            fclose($this->stream_handle);
        }

        $response = [
            'headers' => $theHeaders['headers'],
            'body' => null,
            'response' => $response,
            'cookies' => $theHeaders['cookies'],
            'filename' => !empty($args['filename']) ? $args['filename'] : ''
        ];

        // Handle redirects
        if (($redirect_response = Http::handle_redirects($url, $args, $response)) !== false) {
            return $redirect_response;
        }

        if (!empty($args['decompress']) and Encoding::should_decode($theHeaders['headers']) === true) {
            $theBody = Encoding::decompress($theBody);
        }

        $response['body'] = str_replace("\xEF\xBB\xBF", '', $theBody);

        return $response;
    }

    /**
     * stream_headers()
     *
     * @param mixed $handle
     * @param mixed $headers
     * @return int
     */
    private function stream_headers($handle, $headers)
    {
        $this->headers .= $headers;

        return strlen($headers);
    }

    /**
     * stream_body()
     *
     * @param mixed $handle
     * @param mixed $data
     * @return false|int
     */
    private function stream_body($handle, $data)
    {
        $data_length = strlen($data);

        if ($this->max_body_length and (strlen($this->body) + $data_length) > $this->max_body_length) {
            $data = substr($data, 0, ($this->max_body_length - $data_length));
        }

        if ($this->stream_handle) {
            $bytes_written = fwrite($this->stream_handle, $data);
        } else {
            $this->body .= $data;
            $bytes_written = $data_length;
        }

        return $bytes_written;
    }

    /**
     * test()
     *
     * @param array $args
     * @return bool
     */
    public static function test($args = [])
    {
        if (!function_exists('curl_init') or !function_exists('curl_exec')) {
            return false;
        }

        $is_ssl = (isset($args['ssl']) and $args['ssl']);

        if ($is_ssl) {
            $curl_version = curl_version();
            if (!(CURL_VERSION_SSL & $curl_version['features'])) {
                // Does this cURL version support SSL requests?
                return false;
            }
        }

        return true;
    }
}
