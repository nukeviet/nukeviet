<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2/3/2012, 9:10
 */

namespace NukeViet\Http;

class Http
{
    /**
     * Variable to set dir
     */
    private $root_dir = '';
    private static $tmp_dir = '';

    /**
     * All site config
     */
    private static $site_config = array(
        'version' => '4.x',
        'sitekey' => 'default',
        'site_charset' => 'utf-8',
    );

    /**
     * Error message and error code
     * Error code help user to show error message with optional language
     * Error message is default by english.
     */
    public static $error = array();

    /**
     *
     * @param mixed $config
     * @param string $tmp_dir
     * @return
     */
    public function __construct($config, $tmp_dir = 'tmp')
    {
        /**
         * Important!
         * This class must be put in a file which be stored in 2 subdir with root dir
         * If you store this file on other folder, you must change $store_dir below
         */
        $store_dir = '/../../../../';
        $this->root_dir = preg_replace('/[\/]+$/', '', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__file__) . $store_dir)));

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

        // Find my domain
        $server_name = preg_replace('/^[a-z]+\:\/\//i', '', $this->get_Env(array( 'HTTP_HOST', 'SERVER_NAME' )));
        $server_protocol = strtolower(preg_replace('/^([^\/]+)\/*(.*)$/', '\\1', $this->get_Env('SERVER_PROTOCOL'))) . (($this->get_Env('HTTPS') == 'on') ? 's' : '');
        $server_port = $this->get_Env('SERVER_PORT');
        $server_port = ($server_port == '80') ? '' : (':' . $server_port);

        if (filter_var($server_name, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            Http::$site_config['my_domain'] = $server_protocol . '://' . $server_name . $server_port;
        } else {
            Http::$site_config['my_domain'] = $server_protocol . '://[' . $server_name . ']' . $server_port;
        }

        // Check user custom temp dir
        if (!is_null($tmp_dir)) {
            Http::$tmp_dir = $this->root_dir . '/' . $tmp_dir;

            if (!is_dir(Http::$tmp_dir)) {
                Http::$tmp_dir = $this->root_dir . '/tmp';
            }
        }
    }

    /**
     *
     * @param mixed $url
     * @param mixed $args
     * @return
     */
    private function request($url, $args)
    {
        $defaults = array(
            'method' => 'GET',
            'timeout' => 10,
            'redirection' => 5,
            'requested' => 0,  // Number requested if redirection
            'httpversion' => 1.0,
            'user-agent' => 'NUKEVIET CMS ' . Http::$site_config['version'] . '. Developed by VINADES. Url: http://nukeviet.vn. Code: ' . md5(Http::$site_config['sitekey']),
            'referer' => null,
            'reject_unsafe_urls' => false,
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
            'body' => null,
            'compress' => false,
            'decompress' => true,
            'sslverify' => true,
            'sslcertificates' => $this->root_dir . '/includes/certificates/ca-bundle.crt',
            'stream' => false,
            'filename' => null,
            'limit_response_size' => null,
        );

        // Get full args
        $args = $this->build_args($args, $defaults);

        // Get url info
        $infoURL = @parse_url($url);

        // Check valid url
        if (empty($url) or empty($infoURL['scheme'])) {
            $this->set_error(1);
            return false;
        }

        // Set SSL
        $args['ssl'] = $infoURL['scheme'] == 'https' or $infoURL['scheme'] == 'ssl';

        /**
         * Block url
         * By basic version, all url will be enabled and no blocking by check function
         */
        //if( $this->is_blocking( $url ) )
        //{
        //	$this->set_error(2);
        //	return false;
        //}

        // Determine if this request is to OUR install of NukeViet
        $homeURL = parse_url(Http::$site_config['my_domain']);
        $args['local'] = $homeURL['host'] == $infoURL['host'] or 'localhost' == $infoURL['host'];
        unset($homeURL);

        // If Stream but no file, default is a file in temp dir with base $url name
        if ($args['stream'] and empty($args['filename'])) {
            $args['filename'] = Http::$tmp_dir . '/' . basename($url);
        }

        // Check if streaming a file
        if ($args['stream']) {
            $args['blocking'] = true;
            if (!@is_writable(dirname($args['filename']))) {
                $this->set_error(3);
                return false;
            }
        }

        // Default header is an empty array
        if (is_null($args['headers'])) {
            $args['headers'] = array();
        }

        if (!is_array($args['headers'])) {
            $processedHeaders = Http::processHeaders($args['headers'], $url);
            $args['headers'] = $processedHeaders['headers'];
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
                $args['body'] = http_build_query($args['body'], null, '&');

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

        $response = $this->_dispatch_request($url, $args);

        Http::reset_mbstring_encoding();

        if ($this->is_error($response)) {
            return $response;
        }

        // Append cookies that were used in this request to the response
        if (!empty($args['cookies'])) {
            $cookies_set = array();
            foreach ($response['cookies'] as $key => $value) {
                if (is_object($value)) {
                    $cookies_set[$key] = $value->name;
                } else {
                    $cookies_set[$key] = $value['name'];
                }
            }

            foreach ($args['cookies'] as $cookie) {
                if (!in_array($cookie->name, $cookies_set) and $cookie->test($url)) {
                    $response['cookies'][] = $cookie;
                }
            }
        }

        return $response;
    }

    /**
     *
     * @param mixed $key
     * @return
     */
    private function get_Env($key)
    {
        if (!is_array($key)) {
            $key = array( $key );
        }

        foreach ($key as $k) {
            if (isset($_SERVER[$k])) {
                return $_SERVER[$k];
            } elseif (isset($_ENV[$k])) {
                return $_ENV[$k];
            } elseif (@getenv($k)) {
                return @getenv($k);
            } elseif (function_exists('apache_getenv') and apache_getenv($k, true)) {
                return apache_getenv($k, true);
            }
        }

        return '';
    }

    /**
     *
     * @param mixed $str
     * @return
     */
    private function parse_str($str)
    {
        $r = array();
        parse_str($str, $r);

        return $r;
    }

    /**
     *
     * @param mixed $code
     * @return
     */
    public static function set_error($code)
    {
        $code = intval($code);
        $message = '';

        switch ($code) {
            case 1: $message = 'A valid URL was not provided.'; break;
            case 2: $message = 'User has blocked requests through HTTP.'; break;
            case 3: $message = 'Destination directory for file streaming does not exist or is not writable.'; break;
            case 4: $message = 'There are no HTTP transports available which can complete the requested request.'; break;
            case 5: $message = 'Too many redirects.'; break;
            case 6: $message = 'The SSL certificate for the host could not be verified.'; break;
            case 7: $message = 'HTTP request failed.'; break;
            case 8: $message = 'Could not open stream file.'; break;
            case 9: $message = 'Failed to write request to temporary file.'; break;
            case 10: $message = 'Could not open handle for fopen() to streamfile.'; break;
            case 11: $message = 'HTTP Curl request failed.'; break;
            default: $message = 'There are some unknow errors had been occurred.';
        }

        self::$error['code'] = $code;
        self::$error['message'] = $message;
    }

    /**
     *
     * @param mixed $url
     * @param mixed $args
     * @return
     */
    private function _dispatch_request($url, $args)
    {
        static $transports = array();

        $class = $this->_get_first_available_transport($args, $url);

        if (!$class) {
            $this->set_error(4);
            return false;
        }

        // Transport claims to support request, instantiate it and give it a whirl.
        if (empty($transports[$class])) {
            $transports[$class] = new $class;
        }

        $response = $transports[$class]->request($url, $args);

        return $response;
    }

    /**
     *
     * @param bool $reset
     * @return
     */
    public static function mbstring_binary_safe_encoding($reset = false)
    {
        static $encodings = array();
        static $overloaded = null;

        if (is_null($overloaded)) {
            $overloaded = function_exists('mb_internal_encoding') and (ini_get('mbstring.func_overload') & 2);
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
     *
     * @return
     */
    public static function reset_mbstring_encoding()
    {
        Http::mbstring_binary_safe_encoding(true);
    }

    /**
     *
     * @param mixed $url
     * @param mixed $args
     * @param mixed $response
     * @return
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
        if ($args['redirection'] -- <= 0) {
            $this->set_error(5);
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
            if (in_array($response['response']['code'], array(302, 303))) {
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

        $http = new Http(array(), null);
        return $http->request($redirect_location, $args);
    }

    /**
     *
     * @param mixed $maybe_relative_path
     * @param mixed $url
     * @return
     */
    public static function make_absolute_url($maybe_relative_path, $url)
    {
        if (empty($url)) {
            return $maybe_relative_path;
        }

        // Check for a scheme
        if (strpos($maybe_relative_path, '://') !== false) {
            return $maybe_relative_path;
        }

        if (!$url_parts = @parse_url($url)) {
            return $maybe_relative_path;
        }

        if (!$relative_url_parts = @parse_url($maybe_relative_path)) {
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
     *
     * @return
     */
    public function reset()
    {
        $this->error = array();
    }

    /**
     *
     * @param mixed $resources
     * @return
     */
    public function is_error($resources)
    {
        if (is_object($resources) and isset($resources->error) and empty($resources->error)) {
            return false;
        }

        return true;
    }

    /**
     *
     * @param mixed $args
     * @param mixed $url
     * @return
     */
    public function _get_first_available_transport($args, $url = null)
    {
        $request_order = array('Curl', 'Streams');

        // Loop over each transport on each HTTP request looking for one which will serve this request's needs
        foreach ($request_order as $transport) {
            $class = 'NukeViet\\Http\\' . $transport;

            // Check to see if this transport is a possibility, calls the transport statically
            if (!call_user_func(array( $class, 'test' ), $args, $url)) {
                continue;
            }

            return $class;
        }

        return false;
    }

    /**
     *
     * @param mixed $args
     * @param mixed $defaults
     * @return
     */
    public static function build_args($args, $defaults)
    {
        if (is_object($args)) {
            $args = get_object_vars($args);
        } elseif (!is_array($args)) {
            $args = $this->parse_str($args);
        }

        return array_merge($defaults, $args);
    }

    /**
     *
     * @param mixed $strResponse
     * @return
     */
    public static function processResponse($strResponse)
    {
        $res = explode("\r\n\r\n", $strResponse, 2);

        return array( 'headers' => $res[0], 'body' => isset($res[1]) ? $res[1] : '' );
    }

    /**
     *
     * @param mixed $headers
     * @param string $url
     * @return
     */
    public static function processHeaders($headers, $url = '')
    {
        // Split headers, one per array element
        if (is_string($headers)) {
            $headers = str_replace("\r\n", "\n", $headers);
            $headers = preg_replace('/\n[ \t]/', ' ', $headers);
            $headers = explode("\n", $headers);
        }

        $response = array(
            'code' => 0,
            'message' => ''
        );

        // If a redirection has taken place, The headers for each page request may have been passed.
        // In this case, determine the final HTTP header and parse from there.
        for ($i = sizeof($headers) - 1; $i >= 0; $i --) {
            if (!empty($headers[$i]) and strpos($headers[$i], ':') === false) {
                $headers = array_splice($headers, $i);
                break;
            }
        }

        $cookies = array();
        $newheaders = array();
        foreach (( array ) $headers as $tempheader) {
            if (empty($tempheader)) {
                continue;
            }

            if (strpos($tempheader, ':') === false) {
                $stack = explode(' ', $tempheader, 3);
                $stack[] = '';
                list(, $response['code'], $response['message']) = $stack;
                continue;
            }

            list($key, $value) = explode(':', $tempheader, 2);

            $key = strtolower($key);
            $value = trim($value);

            if (isset($newheaders[$key])) {
                if (!is_array($newheaders[$key])) {
                    $newheaders[$key] = array( $newheaders[$key] );
                }

                $newheaders[$key][] = $value;
            } else {
                $newheaders[$key] = $value;
            }

            if ('set-cookie' == $key) {
                $cookies[] = new Cookie($value, $url);
            }
        }

        return array(
            'response' => $response,
            'headers' => $newheaders,
            'cookies' => $cookies
        );
    }

    /**
     *
     * @param mixed $args
     * @return
     */
    public static function buildCookieHeader(&$args)
    {
        if (!empty($args['cookies'])) {
            // Upgrade any name => value cookie pairs to NukeViet\Http\Cookie instances
            foreach ($args['cookies'] as $name => $value) {
                if (!is_object($value)) {
                    $args['cookies'][$name] = new Cookie(array( 'name' => $name, 'value' => $value ));
                }
            }

            $cookies_header = '';
            foreach (( array ) $args['cookies'] as $cookie) {
                $cookies_header .= $cookie->getHeaderValue() . '; ';
            }

            $cookies_header = substr($cookies_header, 0, -2);
            $args['headers']['cookie'] = $cookies_header;
        }
    }

    /**
     *
     * @param mixed $maybe_ip
     * @return
     */
    public static function is_ip_address($maybe_ip)
    {
        if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $maybe_ip)) {
            return 4;
        }

        if (strpos($maybe_ip, ':') !== false and preg_match('/^(((?=.*(::))(?!.*\3.+\3))\3?|([\dA-F]{1,4}(\3|:\b|$)|\2))(?4){5}((?4){2}|(((2[0-4]|1\d|[1-9])?\d|25[0-5])\.?\b){4})$/i', trim($maybe_ip, ' []'))) {
            return 6;
        }

        return false;
    }

    /**
     *
     * @param mixed $url
     * @param mixed $args
     * @return
     */
    public function post($url, $args = array())
    {
        $defaults = array( 'method' => 'POST' );
        $args = $this->build_args($args, $defaults);
        return $this->request($url, $args);
    }

    /**
     *
     * @param mixed $url
     * @param mixed $args
     * @return
     */
    public function get($url, $args = array())
    {
        $defaults = array( 'method' => 'GET' );
        $args = $this->build_args($args, $defaults);
        return $this->request($url, $args);
    }

    /**
     *
     * @param mixed $url
     * @param mixed $args
     * @return
     */
    public function head($url, $args = array())
    {
        $defaults = array('method' => 'HEAD');
        $args = $this->build_args($args, $defaults);
        return $this->request($url, $args);
    }
}