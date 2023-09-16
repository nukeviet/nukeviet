<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Http;

use NukeViet\Core\Server;
use ValueError;
use Symfony\Polyfill\Intl\Idn\Idn;

/**
 * NukeViet\Http\Http
 *
 * @package NukeViet\Http
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Http extends Server
{
    /**
     * Variable to set dir
     */
    private $root_dir = '';
    private static $tmp_dir = '';

    /**
     * All site config
     */
    private static $site_config = [
        'version' => '4.x',
        'sitekey' => 'default',
        'site_charset' => 'utf-8',
    ];

    /**
     * The Mozilla CA certificate store
     */
    public static $default_cacert = '';

    /**
     * Error message and error code
     * Error code help user to show error message with optional language
     * Error message is default by english.
     */
    public static $error = [];

    public static $http_response_status_codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Unused',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        520 => 'Something is wrong'
    ];

    /**
     * __construct()
     *
     * @param mixed  $config
     * @param string $tmp_dir
     */
    public function __construct($config, $tmp_dir = 'tmp')
    {
        /**
         * Important!
         * This class must be put in a file which be stored in 2 subdir with root dir
         * If you store this file on other folder, you must change $store_dir below
         */
        $store_dir = '/../../../../';
        $this->root_dir = preg_replace('/[\/]+$/', '', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__) . $store_dir)));

        // Custom some config
        if (!empty($config['version'])) {
            Http::$site_config['version'] = $config['version'];
        }
        if (!empty($config['version'])) {
            Http::$site_config['sitekey'] = $config['sitekey'];
        }
        if (!empty($config['site_charset'])) {
            Http::$site_config['site_charset'] = $config['site_charset'];
        }
        if (!empty($config['default_cacert'])) {
            Http::$default_cacert = $config['default_cacert'];
        }

        // Find my domain
        parent::__construct();
        Http::$site_config['my_domain'] = $this->original_domain;

        // Check user custom temp dir
        if (!is_null($tmp_dir)) {
            Http::$tmp_dir = $this->root_dir . '/' . $tmp_dir;

            if (!is_dir(Http::$tmp_dir)) {
                Http::$tmp_dir = $this->root_dir . '/tmp';
            }
        }
    }

    /**
     * request()
     *
     * @param mixed $url
     * @param mixed $args
     * @return mixed
     * @throws ValueError
     */
    private static function request($url, $args)
    {
        $defaults = [
            'method' => 'GET',
            'origin_url' => null,
            'timeout' => 10,
            'redirection' => 5,
            'redirect_count' => 0,
            'requested' => 0,  // Number requested if redirection
            'httpversion' => '1.0',
            'user-agent' => 'NUKEVIET CMS ' . Http::$site_config['version'] . '. Developed by VINADES. Url: http://nukeviet.vn. Code: ' . md5(Http::$site_config['sitekey']),
            'referer' => null,
            'reject_unsafe_urls' => false,
            'blocking' => true,
            'headers' => [],
            'cookies' => [],
            'body' => null,
            'nobody' => false,
            'compress' => false,
            'decompress' => true,
            'sslverify' => false,
            'sslcertificates' => Http::$default_cacert,
            'stream' => false,
            'filename' => null,
            'limit_response_size' => null,
            'cipherstring_seclevel_1' => false, // For a URL having a certificate using RSA less than 2048 bits
        ];

        // Get full args
        $args = Http::build_args($args, $defaults);
        if (empty($args['redirect_count'])) {
            $args['origin_url'] = $url;
        }

        // Get url info
        $infoURL = Http::parse_url($url);

        // Check valid url
        if (empty($url) or empty($infoURL)) {
            Http::set_error(1);

            return false;
        }

        // Set SSL
        $args['ssl'] = ($infoURL['scheme'] == 'https' or $infoURL['scheme'] == 'ssl');

        /**
         * Block url
         * By basic version, all url will be enabled and no blocking by check function
         */
        //if( $this->is_blocking( $url ) )
        //{
        //	Http::set_error(2);
        //	return false;
        //}

        // Determine if this request is to OUR install of NukeViet
        $homeURL = Http::parse_url(Http::$site_config['my_domain']);
        $args['local'] = ($homeURL['host'] == $infoURL['host'] or 'localhost' == $infoURL['host']);
        unset($homeURL);

        // If Stream but no file, default is a file in temp dir with base $url name
        if ($args['stream'] and empty($args['filename'])) {
            $args['filename'] = Http::$tmp_dir . '/' . basename($url);
        }

        // Check if streaming a file
        if ($args['stream']) {
            $args['blocking'] = true;
            if (!@is_writable(dirname($args['filename']))) {
                Http::set_error(3);

                return false;
            }
        }

        // Default header is an empty array
        if (is_null($args['headers'])) {
            $args['headers'] = [];
        }

        if (!is_array($args['headers'])) {
            $args['headers'] = !empty($args['headers']) ? Http::parse_headers($args['headers']) : [];
        }

        // Get User Agent
        if (isset($args['headers']['User-Agent'])) {
            $args['user-agent'] = $args['headers']['User-Agent'];
            unset($args['headers']['User-Agent']);
        }

        if (isset($args['headers']['user-agent'])) {
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

        if ($args['httpversion'] == '1.1' and !isset($args['headers']['connection'])) {
            $args['headers']['connection'] = 'close';
        }

        Http::buildCookieHeader($args);

        Http::mbstring_binary_safe_encoding();

        if (!isset($args['headers']['Accept-Encoding'])) {
            if ($encoding = Encoding::accept_encoding($url, $args)) {
                $args['headers']['Accept-Encoding'] = $encoding;
            }
        }

        if ((!is_null($args['body']) and '' != $args['body']) or $args['method'] == 'POST' or $args['method'] == 'PUT') {
            if (is_array($args['body']) or is_object($args['body'])) {
                $args['body'] = http_build_query($args['body'], '', '&');

                if (!isset($args['headers']['Content-Type'])) {
                    $args['headers']['Content-Type'] = 'application/x-www-form-urlencoded; charset=' . Http::$site_config['site_charset'];
                }
            }

            if ($args['body'] === '') {
                $args['body'] = null;
            }

            if (!isset($args['headers']['Content-Length']) and !isset($args['headers']['content-length'])) {
                $args['headers']['Content-Length'] = strlen($args['body']);
            }
        }

        $response = Http::_dispatch_request($url, $args);

        Http::reset_mbstring_encoding();

        if (Http::is_error($response)) {
            return $response;
        }

        // Append cookies that were used in this request to the response
        if (!empty($args['cookies']) and is_array($response)) {
            $cookies_set = [];
            foreach ($response['cookies'] as $key => $value) {
                if (is_object($value)) {
                    $cookies_set[$key] = $value->name;
                } else {
                    $cookies_set[$key] = $value['name'];
                }
            }

            foreach ($args['cookies'] as $cookie) {
                if (!in_array($cookie->name, $cookies_set, true) and $cookie->test($url)) {
                    $response['cookies'][] = $cookie;
                }
            }
        }

        return $response;
    }

    /**
     * parse_str()
     *
     * @param mixed $str
     * @return array
     */
    private static function parse_str($str)
    {
        $r = [];
        parse_str($str, $r);

        return $r;
    }

    /**
     * set_error()
     *
     * @param mixed $code
     */
    public static function set_error($code)
    {
        $code = (int) $code;
        $message = '';

        switch ($code) {
            case 1: $message = 'A valid URL was not provided.';
                break;
            case 2: $message = 'User has blocked requests through HTTP.';
                break;
            case 3: $message = 'Destination directory for file streaming does not exist or is not writable.';
                break;
            case 4: $message = 'There are no HTTP transports available which can complete the requested request.';
                break;
            case 5: $message = 'Too many redirects.';
                break;
            case 6: $message = 'The SSL certificate for the host could not be verified.';
                break;
            case 7: $message = 'HTTP request failed.';
                break;
            case 8: $message = 'Could not open stream file.';
                break;
            case 9: $message = 'Failed to write request to temporary file.';
                break;
            case 10: $message = 'Could not open handle for fopen() to streamfile.';
                break;
            case 11: $message = 'HTTP Curl request failed.';
                break;
            default: $message = 'There are some unknow errors had been occurred.';
        }

        Http::$error['code'] = $code;
        Http::$error['message'] = $message;
    }

    /**
     * _dispatch_request()
     *
     * @param mixed $url
     * @param mixed $args
     * @return mixed
     */
    private static function _dispatch_request($url, $args)
    {
        static $transports = [];

        $class = Http::_get_first_available_transport($args, $url);

        if (!$class) {
            Http::set_error(4);

            return false;
        }

        // Transport claims to support request, instantiate it and give it a whirl.
        if (empty($transports[$class])) {
            $transports[$class] = new $class();
        }

        return $transports[$class]->request($url, $args);
    }

    /**
     * mbstring_binary_safe_encoding()
     *
     * @param bool $reset
     * @throws ValueError
     */
    public static function mbstring_binary_safe_encoding($reset = false)
    {
        static $encodings = [];
        static $overloaded = null;

        if (is_null($overloaded)) {
            $overloaded = (function_exists('mb_internal_encoding') and (ini_get('mbstring.func_overload') & 2));
        }

        if ($overloaded === false) {
            return;
        }

        if (!$reset) {
            $encoding = mb_internal_encoding();
            array_push($encodings, $encoding);
            mb_internal_encoding('ISO-8859-1');
        }

        if ($reset and $encodings) {
            $encoding = array_pop($encodings);
            mb_internal_encoding($encoding);
        }
    }

    /**
     * reset_mbstring_encoding()
     *
     * @throws ValueError
     */
    public static function reset_mbstring_encoding()
    {
        Http::mbstring_binary_safe_encoding(true);
    }

    /**
     * handle_redirects()
     *
     * @param mixed $url
     * @param mixed $args
     * @param mixed $response
     * @return mixed
     * @throws ValueError
     */
    public static function handle_redirects($url, $args, $response)
    {
        // If no redirects are present, or, redirects were not requested, perform no action.
        if (!isset($response['headers']['location']) or $args['redirection'] === 0) {
            return false;
        }

        // Only perform redirections on redirection http codes
        if ($response['response']['code'] > 399 or $response['response']['code'] < 300) {
            return false;
        }

        // Don't redirect if we've run out of redirects
        if ($args['redirection']-- <= 0) {
            Http::set_error(5);

            return false;
        }

        $redirect_location = $response['headers']['location'];

        // If there were multiple Location headers, use the last header specified
        if (is_array($redirect_location)) {
            $redirect_location = array_pop($redirect_location);
        }

        $redirect_location = Http::make_absolute_url($redirect_location, $url);

        // POST requests should not POST to a redirected location
        if ($args['method'] == 'POST') {
            if (in_array((int) $response['response']['code'], [302, 303], true)) {
                $args['method'] = 'GET';
            }
        }

        // Include valid cookies in the redirect process
        if (!empty($response['cookies'])) {
            foreach ($response['cookies'] as $cookie) {
                if ($cookie->test($redirect_location)) {
                    $args['cookies'][] = $cookie;
                }
            }
        }

        ++$args['redirect_count'];

        $http = new Http([], null);

        return $http->request($redirect_location, $args);
    }

    /**
     * make_absolute_url()
     *
     * @param mixed $maybe_relative_path
     * @param mixed $url
     * @return mixed
     */
    public static function make_absolute_url($maybe_relative_path, $url)
    {
        if (empty($url)) {
            return $maybe_relative_path;
        }

        // Check for a scheme
        if (str_contains($maybe_relative_path, '://')) {
            return $maybe_relative_path;
        }

        if (!$url_parts = parse_url($url)) {
            return $maybe_relative_path;
        }

        if (!$relative_url_parts = parse_url($maybe_relative_path)) {
            return $maybe_relative_path;
        }

        $absolute_path = $url_parts['scheme'] . '://' . $url_parts['host'];

        if (isset($url_parts['port'])) {
            $absolute_path .= ':' . $url_parts['port'];
        }

        // Start off with the Absolute URL path
        $path = !empty($url_parts['path']) ? $url_parts['path'] : '/';

        // If it's a root-relative path, then great
        if (!empty($relative_url_parts['path']) and $relative_url_parts['path'][0] == '/') {
            $path = $relative_url_parts['path'];
        }
        // Else it's a relative path
        elseif (!empty($relative_url_parts['path'])) {
            // Strip off any file components from the absolute path
            $path = substr($path, 0, strrpos($path, '/') + 1);

            // Build the new path
            $path .= $relative_url_parts['path'];

            // Strip all /path/../ out of the path
            while (strpos($path, '../') > 1) {
                $path = preg_replace('![^/]+/\.\./!', '', $path);
            }

            // Strip any final leading ../ from the path
            $path = preg_replace('!^/(\.\./)+!', '', $path);
        }

        // Add the Query string
        if (!empty($relative_url_parts['query'])) {
            $path .= '?' . $relative_url_parts['query'];
        }

        return $absolute_path . '/' . ltrim($path, '/');
    }

    /**
     * reset()
     */
    public function reset()
    {
        Http::$error = [];
    }

    /**
     * is_error()
     *
     * @param mixed $resources
     * @return bool
     */
    public static function is_error($resources)
    {
        return !(is_object($resources) and isset($resources->error) and empty($resources->error));
    }

    /**
     * _get_first_available_transport()
     *
     * @param mixed      $args
     * @param mixed|null $url
     * @return false|string
     */
    public static function _get_first_available_transport($args, $url = null)
    {
        $request_order = ['Curl', 'Streams'];

        // Loop over each transport on each HTTP request looking for one which will serve this request's needs
        foreach ($request_order as $transport) {
            $class = 'NukeViet\\Http\\' . $transport;

            // Check to see if this transport is a possibility, calls the transport statically
            if (!call_user_func([$class, 'test'], $args, $url)) {
                continue;
            }

            return $class;
        }

        return false;
    }

    /**
     * build_args()
     *
     * @param mixed $args
     * @param mixed $defaults
     * @return mixed
     */
    public static function build_args($args, $defaults)
    {
        if (is_object($args)) {
            $args = get_object_vars($args);
        } elseif (!is_array($args)) {
            $args = Http::parse_str($args);
        }

        return array_merge($defaults, $args);
    }

    /**
     * processResponse()
     *
     * @param mixed $strResponse
     * @return string[]
     */
    public static function processResponse($strResponse)
    {
        $res = explode("\r\n\r\n", $strResponse, 2);

        return ['headers' => $res[0], 'body' => $res[1] ?? ''];
    }

    /**
     * processHeaders()
     *
     * @param mixed  $headers
     * @param string $url
     * @return array
     */
    public static function processHeaders($headers, $url = '')
    {
        $newheaders = Http::parse_headers($headers);
        $response = [
            'code' => $newheaders['code'] ?? 0,
            'message' => $newheaders['message'] ?? ''
        ];

        $cookies = [];
        if (!empty($newheaders['set-cookie'])) {
            if (!is_array($newheaders['set-cookie'])) {
                $newheaders['set-cookie'] = [$newheaders['set-cookie']];
            }
            foreach ($newheaders['set-cookie'] as $value) {
                $cookies[] = new Cookie($value, $url);
            }
        }

        unset($newheaders['code'], $newheaders['message'], $newheaders['set-cookie']);

        return [
            'response' => $response,
            'headers' => $newheaders,
            'cookies' => $cookies
        ];
    }

    /**
     * buildCookieHeader()
     *
     * @param mixed $args
     */
    public static function buildCookieHeader(&$args)
    {
        if (!empty($args['cookies'])) {
            // Upgrade any name => value cookie pairs to NukeViet\Http\Cookie instances
            foreach ($args['cookies'] as $name => $value) {
                if (!is_object($value)) {
                    $args['cookies'][$name] = new Cookie(['name' => $name, 'value' => $value]);
                }
            }

            $cookies_header = '';
            foreach ((array) $args['cookies'] as $cookie) {
                $cookies_header .= $cookie->getHeaderValue() . '; ';
            }

            $cookies_header = substr($cookies_header, 0, -2);
            $args['headers']['cookie'] = $cookies_header;
        }
    }

    /**
     * filter_domain()
     *
     * @param mixed $domain
     * @return mixed
     */
    public static function filter_domain($domain)
    {
        if (preg_match("/^([a-z0-9](-*[a-z0-9])*)(\.([a-z0-9](-*[a-z0-9])*))*$/i", $domain) and preg_match('/^.{1,253}$/', $domain) and preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain)) {
            return $domain;
        }

        if ($domain == 'localhost') {
            return $domain;
        }

        if (filter_var($domain, FILTER_VALIDATE_IP)) {
            return $domain;
        }

        if (!empty($domain)) {
            if (function_exists('idn_to_ascii')) {
                $domain = idn_to_ascii($domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
            } else {
                $domain = Idn::idn_to_ascii($domain, Idn::IDNA_DEFAULT, Idn::INTL_IDNA_VARIANT_UTS46);
            }

            if (preg_match('/^xn\-\-([a-z0-9\-\.]+)\.([a-z0-9\-]+)$/', (string) $domain)) {
                return $domain;
            }
        }

        return '';
    }

    /**
     * parse_headers()
     *
     * @param mixed $response
     * @return array
     */
    public static function parse_headers($response)
    {
        if (is_string($response)) {
            // Split headers, one per array element
            $header_text = str_replace("\r\n", "\n", $response);
            $header_text = preg_replace('/\n[ \t]/', ' ', $header_text);
            $header_text = explode("\n", $header_text);
        } else {
            $header_text = $response;
        }

        // If a redirection has taken place, The headers for each page request may have been passed.
        // In this case, determine the final HTTP header and parse from there.
        for ($i = sizeof($header_text) - 1; $i >= 0; --$i) {
            if (!empty($header_text[$i]) and !str_contains($header_text[$i], ':')) {
                $header_text = array_splice($header_text, $i);
                break;
            }
        }

        $headers = [];
        foreach ($header_text as $line) {
            if (stripos($line, 'HTTP/') === 0) {
                $stack = explode(' ', $line, 3);
                $headers['code'] = !empty($stack[1]) ? (int) $stack[1] : 0;
                $headers['message'] = !empty($stack[2]) ? $stack[2] : '';
                if (empty($headers['message']) or $headers['message'] == $headers['code']) {
                    if (!empty(Http::$http_response_status_codes[$headers['code']])) {
                        $headers['message'] = Http::$http_response_status_codes[$headers['code']];
                    }
                }
            } else {
                unset($output);
                if (preg_match('/^([a-z][a-z0-9\-]*)\s*\:\s*(.*)$/i', $line, $output)) {
                    $key = strtolower(trim($output[1]));
                    if (!empty($key)) {
                        if (isset($headers[$key])) {
                            if (!is_array($headers[$key])) {
                                $headers[$key] = [$headers[$key]];
                            }

                            $headers[$key][] = trim($output[2]);
                        } else {
                            $headers[$key] = trim($output[2]);
                        }
                    }
                }
            }
        }

        return $headers;
    }

    /**
     * parse_url()
     *
     * @param mixed $url
     * @param bool  $return_bool
     * @return array|bool|int|string
     */
    public static function parse_url($url, $return_bool = false)
    {
        if (!($parts = parse_url($url))) {
            return false;
        }

        if (empty($parts['scheme']) or !preg_match('/^(https?|ssl)$/i', $parts['scheme'])) {
            return false;
        }

        if (empty($parts['host'])) {
            return false;
        }

        $domain = Http::filter_domain($parts['host']);
        if (empty($domain)) {
            return false;
        }

        if (isset($parts['user']) and !preg_match('/^([0-9a-z\-]|[\_])*$/i', $parts['user'])) {
            return false;
        }

        if (isset($parts['pass']) and !preg_match('/^([0-9a-z\-]|[\_])*$/i', $parts['pass'])) {
            return false;
        }

        if (isset($parts['path']) and !preg_match('/^[0-9a-z\+\-\_\/\&\=\#\.\,\;\%\\s\!\:]*$/i', $parts['path'])) {
            return false;
        }

        if (isset($parts['query']) and !preg_match('/^[0-9a-z\+\-\_\/\?\&\=\#\.\,\;\%\\s\!]*$/i', $parts['query'])) {
            return false;
        }

        if ($return_bool) {
            return true;
        }

        if (isset($parts['path'])) {
            if (substr($parts['path'], 0, 1) != '/') {
                $parts['path'] = '/' . $parts['path'];
            }
            $parts['path'] = explode('/', $parts['path']);
            $parts['path'] = array_map('rawurlencode', $parts['path']);
            $parts['path'] = implode('/', $parts['path']);
        } else {
            $parts['path'] = '/';
        }

        $parts['login'] = isset($parts['user']) ? ($parts['user'] . (isset($parts['pass']) ? ':' . $parts['pass'] : '') . '@') : '';
        $parts['file'] = explode('/', $parts['path']);
        $parts['file'] = array_pop($parts['file']);
        $parts['dir'] = substr($parts['path'], 0, strrpos($parts['path'], '/'));
        $parts['base'] = $parts['scheme'] . '://' . $parts['host'] . $parts['dir'];
        $parts['uri'] = $parts['scheme'] . '://' . $parts['login'] . $parts['host'];
        if (!empty($parts['port']) and $parts['port'] != 80 and $parts['port'] != 443) {
            $parts['uri'] .= ':' . $parts['port'];
        }
        $parts['uri'] .= $parts['path'];
        if (!empty($parts['query'])) {
            $parts['uri'] .= '?' . $parts['query'];
        }

        return $parts;
    }

    /**
     * post()
     *
     * @param mixed $url
     * @param array $args
     * @return mixed
     * @throws ValueError
     */
    public function post($url, $args = [])
    {
        $defaults = ['method' => 'POST'];
        $args = Http::build_args($args, $defaults);

        return Http::request($url, $args);
    }

    /**
     * get()
     *
     * @param mixed $url
     * @param array $args
     * @return mixed
     * @throws ValueError
     */
    public function get($url, $args = [])
    {
        $defaults = ['method' => 'GET'];
        $args = Http::build_args($args, $defaults);

        return Http::request($url, $args);
    }

    /**
     * head()
     *
     * @param mixed $url
     * @param array $args
     * @return mixed
     * @throws ValueError
     */
    public function head($url, $args = [])
    {
        $defaults = ['method' => 'HEAD'];
        $args = Http::build_args($args, $defaults);

        return Http::request($url, $args);
    }
}
