<?php

namespace NukeViet\Core;

/**
 * Request
 *
 * @package
 * @author
 * @copyright VINADES.,JSC
 * @version 2010
 * @access public
 */
class Request
{
    const IS_HEADERS_SENT = 'Warning: Headers already sent';
    const INCORRECT_IP = 'Incorrect IP address specified';
    public $session_id;
    public $doc_root;
    public $site_url;
    public $base_siteurl;
    public $base_adminurl;
    public $server_name;
    public $server_protocol;
    public $server_port;
    public $my_current_domain;
    public $headerstatus;
    public $session_save_path;
    public $cookie_path;
    public $cookie_domain;
    public $referer;
    public $referer_key;
    public $referer_host = '';
    public $referer_queries = false;
    public $request_uri;
    public $user_agent;
    public $search_engine = '';
    private $request_default_mode = 'request';
    private $allow_request_mods = array( 'get', 'post', 'request', 'cookie', 'session', 'env', 'server' );
    private $cookie_prefix = 'NV4';
    private $session_prefix = 'NV4';
    private $cookie_key = 'nv4';
    private $secure = false;
    private $httponly = true;
    private $ip_addr;
    private $is_filter = false;
    private $str_referer_blocker = false;
    private $engine_allowed = array();

    // Cac tags bi cam dung mac dinh, co the go bo bang cach thay doi cac tags cho phep cua NV_ALLOWED_HTML_TAGS
    private $disabletags = array( 'applet', 'body', 'basefont', 'head', 'html', 'id', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base' );
    private $disabledattributes = array( 'action', 'background', 'codebase', 'dynsrc', 'lowsrc' );
    private $disablecomannds = array( 'base64_decode', 'cmd', 'passthru', 'eval', 'exec', 'system', 'fopen', 'fsockopen', 'file', 'file_get_contents', 'readfile', 'unlink' );

    /**
     * Request::__construct()
     *
     * @param mixed $config
     * @param mixed $ip
     * @return
     */
    public function __construct($config, $ip)
    {
        if (isset($config['allowed_html_tags']) and is_array($config['allowed_html_tags'])) {
            $this->disabletags = array_diff($this->disabletags, $config['allowed_html_tags']);
        }
        if (isset($config['allow_request_mods']) and ! empty($config['allow_request_mods'])) {
            if (! is_array($config['allow_request_mods'])) {
                $config['allow_request_mods'] = array( $config['allow_request_mods'] );
            }
            $this->allow_request_mods = array_intersect($this->allow_request_mods, $config['allow_request_mods']);
        }
        if (isset($config['request_default_mode']) and ! empty($config['request_default_mode']) and in_array($config['request_default_mode'], $this->allow_request_mods)) {
            $this->request_default_mode = $config['request_default_mode'];
        }
        if (isset($config['cookie_secure']) and ! empty($config['cookie_secure'])) {
            $this->secure = true;
        }
        if (isset($config['cookie_httponly']) and ! empty($config['cookie_httponly'])) {
            $this->httponly = true;
        }
        if (isset($config['cookie_prefix']) and ! empty($config['cookie_prefix'])) {
            $this->cookie_prefix = preg_replace('/[^a-zA-Z0-9\_]+/', '', $config['cookie_prefix']);
        }
        if (isset($config['session_prefix']) and ! empty($config['session_prefix'])) {
            $this->session_prefix = preg_replace('/[^a-zA-Z0-9\_]+/', '', $config['session_prefix']);
        }
        if (isset($config['sitekey']) and ! empty($config['sitekey'])) {
            $this->cookie_key = $config['sitekey'];
        }
        if (! empty($config['str_referer_blocker'])) {
            $this->str_referer_blocker = true;
        }
        $this->engine_allowed = ( array )$config['engine_allowed'];
        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if (preg_match('#^(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$#', $ip)) {
            $ip2long = ip2long($ip);
        } else {
            if (substr_count($ip, '::')) {
                $ip = str_replace('::', str_repeat(':0000', 8 - substr_count($ip, ':')) . ':', $ip);
            }
            $ip = explode(':', $ip);
            $r_ip = '';
            foreach ($ip as $v) {
                $r_ip .= str_pad(base_convert($v, 16, 2), 16, 0, STR_PAD_LEFT);
            }
            $ip2long = base_convert($r_ip, 2, 10);
        }

        if ($ip2long == - 1 or $ip2long === false) {
            trigger_error(Request::INCORRECT_IP, 256);
        }
        $this->ip_addr = $ip2long;

        $this->cookie_key = md5($this->cookie_key);

        if (extension_loaded('filter') and filter_id(ini_get('filter.default')) !== FILTER_UNSAFE_RAW) {
            $this->is_filter = true;
        }
        $this->Initialize();
        $this->get_cookie_save_path();

        $_ssl_https = (isset($config['ssl_https'])) ? $config['ssl_https'] : 0;
        $this->sessionStart($_ssl_https);
        $_REQUEST = array_merge($_POST, array_diff_key($_GET, $_POST));
    }

    /**
     * Request::get_Env()
     *
     * @param mixed $key
     * @return
     */
    private function get_Env($key)
    {
        if (! is_array($key)) {
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
     * Request::fixQuery()
     *
     * @param mixed $var
     * @param mixed $mode
     * @return
     */
    private function fixQuery(&$var, $mode)
    {
        $array_keys = array_keys($var);
        foreach ($array_keys as $k) {
            if (is_array($var[$k])) {
                $this->fixQuery($var[$k], $mode);
            } elseif (is_string($var[$k])) {
                if ($mode == 'get') {
                    $var[$k] = $this->security_get($var[$k]);
                }
            }
        }
    }

    /**
     *
     */
    private function Initialize()
    {
        if (sizeof($_GET)) {
            $array_keys = array_keys($_GET);
            foreach ($array_keys as $k) {
                if (! preg_match('/^[a-zA-Z0-9\_]+$/', $k) or is_numeric($k)) {
                    unset($_GET[$k]);
                }
            }
            $this->fixQuery($_GET, 'get');
        }
        if (sizeof($_POST)) {
            $array_keys = array_keys($_POST);
            foreach ($array_keys as $k) {
                if ((!preg_match('/^[a-zA-Z0-9\_]+$/', $k) and $k != 'g-recaptcha-response') or is_numeric($k)) {
                    unset($_POST[$k]);
                }
            }
            $this->fixQuery($_POST, 'post');
        }
        if (sizeof($_COOKIE)) {
            $array_keys = array_keys($_COOKIE);
            foreach ($array_keys as $k) {
                if (! preg_match('/^[a-zA-Z0-9\_]+$/', $k) or is_numeric($k)) {
                    @setcookie($k, '', NV_CURRENTTIME - 3600);
                    unset($_COOKIE[$k]);
                }
            }
            $this->fixQuery($_COOKIE, 'cookie');
        }
        if (sizeof($_FILES) and strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $array_keys = array_keys($_FILES);
            foreach ($array_keys as $k) {
                if (! preg_match('/^[a-zA-Z0-9\_]+$/', $k) or is_numeric($k)) {
                    unset($_FILES[$k]);
                }
            }
            $this->fixQuery($_FILES, 'files');
        }
        $query = http_build_query($_GET);
        $_SERVER['QUERY_STRING'] = $query;
        $_SERVER['argv'] = array( $query );
        $this->request_uri = (empty($_SERVER['REQUEST_URI'])) ? $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] : $_SERVER['REQUEST_URI'];
        $doc_root = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '';
        if (! empty($doc_root)) {
            $doc_root = str_replace(DIRECTORY_SEPARATOR, '/', $doc_root);
        }
        if (! empty($doc_root)) {
            $doc_root = preg_replace('/[\/]+$/', '', $doc_root);
        }

        if (defined('NV_BASE_SITEURL')) {
            $base_siteurl = preg_replace('/[\/]+$/', '', NV_BASE_SITEURL);
        } else {
            $base_siteurl = pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
            if ($base_siteurl == DIRECTORY_SEPARATOR) {
                $base_siteurl = '';
            }
            if (! empty($base_siteurl)) {
                $base_siteurl = str_replace(DIRECTORY_SEPARATOR, '/', $base_siteurl);
            }
            if (! empty($base_siteurl)) {
                $base_siteurl = preg_replace('/[\/]+$/', '', $base_siteurl);
            }
            if (! empty($base_siteurl)) {
                $base_siteurl = preg_replace('/^[\/]*(.*)$/', '/\\1', $base_siteurl);
            }
            if (defined('NV_WYSIWYG') and ! defined('NV_ADMIN')) {
                $base_siteurl = preg_replace('#/' . NV_EDITORSDIR . '(.*)$#', '', $base_siteurl);
            } elseif (defined('NV_IS_UPDATE')) {
                // Update se bao gom ca admin nen update phai dat truoc

                $base_siteurl = preg_replace('#/install(.*)$#', '', $base_siteurl);
            } elseif (defined('NV_ADMIN')) {
                $base_siteurl = preg_replace('#/' . NV_ADMINDIR . '(.*)$#i', '', $base_siteurl);
            } elseif (! empty($base_siteurl)) {
                $base_siteurl = preg_replace('#/index\.php(.*)$#', '', $base_siteurl);
            }
        }

        if (NV_ROOTDIR !== $doc_root . $base_siteurl) {
            $doc_root = NV_ROOTDIR;
            $count = substr_count($base_siteurl, '/');
            for ($i = 0; $i < $count; ++$i) {
                $doc_root = preg_replace('#\/[^\/]+$#', '', $doc_root);
            }
            $_SERVER['DOCUMENT_ROOT'] = $doc_root;
        }
        $_SERVER['SCRIPT_FILENAME'] = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF'];
        $_SERVER['SERVER_PORT'] = $this->get_Env('SERVER_PORT');
        $_SERVER['SERVER_PROTOCOL'] = $this->get_Env('SERVER_PROTOCOL');

        if (defined('NV_SERVER_NAME')) {
            $this->server_name = NV_SERVER_NAME;
        } else {
            $this->server_name = preg_replace('/^[a-z]+\:\/\//i', '', $this->get_Env(array( 'HTTP_HOST', 'SERVER_NAME' )));
            $this->server_name = preg_replace('/(\:[0-9]+)$/', '', $this->server_name);
        }
        $_SERVER['SERVER_NAME'] = $this->server_name;

        $this->base_siteurl = $base_siteurl;
        $this->base_adminurl = $base_siteurl . (NV_ADMINDIR != '' ? '/' . NV_ADMINDIR : '');
        $this->doc_root = $doc_root;
        if (defined('NV_SERVER_PROTOCOL')) {
            $this->server_protocol = NV_SERVER_PROTOCOL;
        } else {
            $this->server_protocol = strtolower(preg_replace('/^([^\/]+)\/*(.*)$/', '\\1', $_SERVER['SERVER_PROTOCOL'])) . (($this->get_Env('HTTPS') == 'on') ? 's' : '');
        }
        if (defined('NV_SERVER_PORT')) {
            $this->server_port = NV_SERVER_PORT;
        } else {
            $this->server_port = ($_SERVER['SERVER_PORT'] == '80' or $_SERVER['SERVER_PORT'] == '443') ? '' : (':' . $_SERVER['SERVER_PORT']);
        }

        if (defined('NV_MY_DOMAIN')) {
            $this->my_current_domain = NV_MY_DOMAIN;
        } else {
            if (filter_var($this->server_name, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                $this->my_current_domain = $this->server_protocol . '://' . $this->server_name . $this->server_port;
            } else {
                $this->my_current_domain = $this->server_protocol . '://[' . $this->server_name . ']' . $this->server_port;
            }
        }

        $this->headerstatus = (substr(php_sapi_name(), 0, 3) == 'cgi') ? 'Status:' : $_SERVER['SERVER_PROTOCOL'];

        $this->site_url = $this->my_current_domain . $this->base_siteurl;
        $this->referer = $this->get_Env(array( 'HTTP_REFERER', 'Referer' ));
        if (! empty($this->referer)) {
            $ref = @parse_url($this->referer);
            if (isset($ref['scheme']) and in_array($ref['scheme'], array( 'http', 'https', 'ftp', 'gopher' )) and isset($ref['host'])) {
                if (substr($ref['host'], 0, 1) == '[' and substr($ref['host'], -1) == ']') {
                    $ref['host'] = substr($ref['host'], 1, -1);
                }
                if (preg_match('/^' . preg_quote($ref['host']) . '/', $this->server_name)) {
                    $this->referer_key = 1;
                } else {
                    $this->referer_key = 0;
                    if (! empty($this->engine_allowed)) {
                        foreach ($this->engine_allowed as $se => $v) {
                            if (preg_match('/' . preg_quote($v['host_pattern']) . '/i', $ref['host'])) {
                                $this->search_engine = $se;
                                break;
                            }
                        }
                    }
                }
                $this->referer_host = $ref['host'];
                $tmp = array();
                $base = $this->referer;
                if (isset($ref['query']) and ! empty($ref['query'])) {
                    list($base, $query_string) = explode('?', $this->referer);
                    parse_str($query_string, $parameters);
                    foreach ($parameters as $key => $value) {
                        if (preg_match('/^[a-zA-Z\_][a-zA-Z0-9\_]*$/', $key)) {
                            $tmp[$key] = $this->security_get($value, true);
                        }
                    }
                }
                if (! empty($tmp)) {
                    $this->referer_queries = $tmp;
                    $_SERVER['HTTP_REFERER'] = $base . '?' . http_build_query($tmp);
                } else {
                    $_SERVER['HTTP_REFERER'] = $base;
                }
                $this->referer = $_SERVER['HTTP_REFERER'];
            } else {
                $this->referer_key = 0;
                $this->referer = '';
                unset($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->referer_key = 2;
            unset($_SERVER['HTTP_REFERER']);
        }
        if ($this->str_referer_blocker and ! empty($_SERVER['QUERY_STRING']) and $this->referer_key == 0 and empty($this->search_engine)) {
            header('Location: ' . $this->site_url);
            exit(0);
        }

        $user_agent = ( string )$this->get_Env('HTTP_USER_AGENT');
        $user_agent = substr(htmlspecialchars($user_agent), 0, 255);
        if(!empty($user_agent)) $user_agent = trim($user_agent);
        if (empty($user_agent) or $user_agent == '-') {
            $user_agent = 'none';
        }
        $this->user_agent = $user_agent;
        $_SERVER['HTTP_USER_AGENT'] = $user_agent;
    }

    /**
     * Request::get_cookie_save_path()
     *
     * @return
     */
    private function get_cookie_save_path()
    {
        $this->cookie_path = $this->base_siteurl . '/';
        $cookie_domain = preg_replace('/^([w]{3})\./', '', $this->server_name);
        $this->cookie_domain = (preg_match('/^([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/', $cookie_domain)) ? '.' . $cookie_domain : '';
    }

    /**
     * Request::sessionStart()
     *
     * @return
     */
    private function sessionStart($_ssl_https)
    {
        if (headers_sent() or connection_status() != 0 or connection_aborted()) {
            trigger_error(Request::IS_HEADERS_SENT, 256);
        }

        $_secure = ($this->server_protocol == 'https' and $_ssl_https == 1) ? 1 : 0;
        session_set_cookie_params(NV_LIVE_SESSION_TIME, $this->cookie_path, $this->cookie_domain, $_secure, 1);

        session_name($this->cookie_prefix . '_sess');
        session_start();
        $session_id = session_id();

        $_SESSION = (isset($_SESSION) and is_array($_SESSION)) ? $_SESSION : array();
        if (sizeof($_SESSION)) {
            $array_keys = array_keys($_SESSION);
            foreach ($array_keys as $k) {
                if (! preg_match('/^[a-zA-Z0-9\_]+$/', $k) or is_numeric($k)) {
                    unset($_SESSION[$k]);
                }
            }
            $this->fixQuery($_SESSION, 'session');
        }
        $this->session_id = $session_id;
    }

    /**
     * Request::chr_hexdec_callback()
     *
     * @param mixed $m
     * @return
     */
    private function chr_hexdec_callback($m)
    {
        return chr(hexdec($m[1]));
    }

    /**
     * Request::chr_callback()
     *
     * @param mixed $m
     * @return
     */
    private function chr_callback($m)
    {
        return chr($m[1]);
    }

    /**
     * Request::color_hex2rgb_callback()
     *
     * @param mixed $hex
     * @return
     */
    private function color_hex2rgb_callback($hex)
    {
        if (preg_match('/[^0-9ABCDEFabcdef]/', $hex[1])) {
            return $hex[0];
        }
        $color = $hex[1];
        $l = strlen($color);
        if ($l != 3 and $l != 6) {
            return $hex[0];
        }
        $l = $l / 3;
        return 'rgb(' . (hexdec(substr($color, 0, 1 * $l))) . ', ' . (hexdec(substr($color, 1 * $l, 1 * $l))) . ', ' . (hexdec(substr($color, 2 * $l, 1 * $l))) . ');';
    }

    /**
     * Request::unhtmlentities()
     *
     * @param mixed $value
     * @return
     */
    private function unhtmlentities($value)
    {
        $value = preg_replace("/%3A%2F%2F/", '', $value);
        $value = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $value);
        $value = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $value);
        $value = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $value);
        $value = str_ireplace(array( '&#x53;&#x43;&#x52;&#x49;&#x50;&#x54;', '&#x26;&#x23;&#x78;&#x36;&#x41;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x31;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x36;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x31;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x33;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x33;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x32;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x39;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x30;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x34;&#x3B;', '/*', '*/', '<!--', '-->', '<!-- -->', '&#x0A;', '&#x0D;', '&#x09;', '' ), '', $value);
        $search = '/&#[xX]0{0,8}(21|22|23|24|25|26|27|28|29|2a|2b|2d|2f|30|31|32|33|34|35|36|37|38|39|3a|3b|3d|3f|40|41|42|43|44|45|46|47|48|49|4a|4b|4c|4d|4e|4f|50|51|52|53|54|55|56|57|58|59|5a|5b|5c|5d|5e|5f|60|61|62|63|64|65|66|67|68|69|6a|6b|6c|6d|6e|6f|70|71|72|73|74|75|76|77|78|79|7a|7b|7c|7d|7e);?/i';
        $value = preg_replace_callback($search, array( $this, 'chr_hexdec_callback' ), $value);
        $search = '/&#0{0,8}(33|34|35|36|37|38|39|40|41|42|43|45|47|48|49|50|51|52|53|54|55|56|57|58|59|61|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|106|107|108|109|110|111|112|113|114|115|116|117|118|119|120|121|122|123|124|125|126);?/i';
        $value = preg_replace_callback($search, array( $this, 'chr_callback' ), $value);
        $search = array( '&#60', '&#060', '&#0060', '&#00060', '&#000060', '&#0000060', '&#60;', '&#060;', '&#0060;', '&#00060;', '&#000060;', '&#0000060;', '&#x3c', '&#x03c', '&#x003c', '&#x0003c', '&#x00003c', '&#x000003c', '&#x3c;', '&#x03c;', '&#x003c;', '&#x0003c;', '&#x00003c;', '&#x000003c;', '&#X3c', '&#X03c', '&#X003c', '&#X0003c', '&#X00003c', '&#X000003c', '&#X3c;', '&#X03c;', '&#X003c;', '&#X0003c;', '&#X00003c;', '&#X000003c;', '&#x3C', '&#x03C', '&#x003C', '&#x0003C', '&#x00003C', '&#x000003C', '&#x3C;', '&#x03C;', '&#x003C;', '&#x0003C;', '&#x00003C;', '&#x000003C;', '&#X3C', '&#X03C', '&#X003C', '&#X0003C', '&#X00003C', '&#X000003C', '&#X3C;', '&#X03C;', '&#X003C;', '&#X0003C;', '&#X00003C;', '&#X000003C;', '\x3c', '\x3C', '\u003c', '\u003C' );
        $value = str_ireplace($search, '<', $value);
        return $value;
    }

    /**
     * Request::filterAttr()
     *
     * @param mixed $attrSet
     * @return
     */
    private function filterAttr($attrSet)
    {
        $newSet = array();

        for ($i = 0, $count = sizeof($attrSet); $i < $count; ++$i) {
            if (! $attrSet[$i]) {
                continue;
            }
            $attrSubSet = array_map('trim', explode('=', trim($attrSet[$i]), 2));
            $attrSubSet[0] = strtolower($attrSubSet[0]);

            if (! preg_match('/[a-z]+/i', $attrSubSet[0]) or in_array($attrSubSet[0], $this->disabledattributes) or preg_match('/^on/i', $attrSubSet[0])) {
                continue;
            }

            if (! empty($attrSubSet[1])) {
                $attrSubSet[1] = preg_replace('/[ ]+/', ' ', $attrSubSet[1]);
                $attrSubSet[1] = preg_replace("/^\"(.*)\"$/", "\\1", $attrSubSet[1]);
                $attrSubSet[1] = preg_replace("/^\'(.*)\'$/", "\\1", $attrSubSet[1]);
                $attrSubSet[1] = str_replace(array( '"', '&quot;' ), "'", $attrSubSet[1]);

                if (preg_match("/(expression|javascript|behaviour|vbscript|mocha|livescript)(\:*)/", $attrSubSet[1])) {
                    continue;
                }

                if (! empty($this->disablecomannds) and preg_match('#(' . implode('|', $this->disablecomannds) . ')(\s*)\((.*?)\)#si', $attrSubSet[1])) {
                    continue;
                }

                $value = $this->unhtmlentities($attrSubSet[1]);
                $search = array(
                    'javascript' => '/j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t/si',
                    'vbscript' => '/v\s*b\s*s\s*c\s*r\s*i\s*p\s*t/si',
                    'script' => '/s\s*c\s*r\s*i\s*p\s*t/si',
                    'applet' => '/a\s*p\s*p\s*l\s*e\s*t/si',
                    'alert' => '/a\s*l\s*e\s*r\s*t/si',
                    'document' => '/d\s*o\s*c\s*u\s*m\s*e\s*n\s*t/si',
                    'write' => '/w\s*r\s*i\s*t\s*e/si',
                    'cookie' => '/c\s*o\s*o\s*k\s*i\s*e/si',
                    'window' => '/w\s*i\s*n\s*d\s*o\s*w/si'
                );
                $value = preg_replace(array_values($search), array_keys($search), $value);

                if (preg_match("/(expression|javascript|behaviour|vbscript|mocha|livescript)(\:*)/", $value)) {
                    continue;
                }

                if (! empty($this->disablecomannds) and preg_match('#(' . implode('|', $this->disablecomannds) . ')(\s*)\((.*?)\)#si', $value)) {
                    continue;
                }

                $attrSubSet[1] = preg_replace_callback('/\#([0-9ABCDEFabcdef]{3,6})[\;]*/', array( $this, 'color_hex2rgb_callback' ), $attrSubSet[1]);
            } elseif ($attrSubSet[1] !== '0') {
                $attrSubSet[1] = $attrSubSet[0];
            }
            $newSet[] = $attrSubSet[0] . '=[@{' . $attrSubSet[1] . '}@]';
        }
        return $newSet;
    }

    /**
     * Request::filterTags()
     *
     * @param mixed $source
     * @return
     */
    private function filterTags($source)
    {
        $source = preg_replace('/\<script([^\>]*)\>(.*)\<\/script\>/isU', '', $source);
        if (in_array('iframe', $this->disabletags)) {
            if (preg_match_all("/<iframe[a-z0-9\s\=\"]*src\=\"(http(s)?\:)?\/\/([w]{3})?\.youtube[^\/]+\/embed\/([^\?]+)(\?[^\"]+)?\"[^\>]*\><\/iframe>/isU", $source, $match)) {
                foreach ($match[0] as $key => $_m) {
                    $vid = $match[4][$key];
                    $width = intval(preg_replace("/^(.*)width\=\"([\d]+)\"(.*)$/isU", "\\2", $_m));
                    $height = intval(preg_replace("/^(.*)height\=\"([\d]+)\"(.*)$/isU", "\\2", $_m));

                    $width = ($width > 0) ? $width : 480;
                    $height = ($height > 0) ? $height : 360;

                    $ojwplayer = '<object height="' . $height . '" width="' . $width . '"><param name="movie" value="//www.youtube.com/v/' . $vid . '?rel=0&amp;hl=pt_BR&amp;version=3" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><embed allowfullscreen="true" allowscriptaccess="always" height="' . $height . '" src="//www.youtube.com/v/' . $vid . '?rel=0&amp;autoplay=1&amp;hl=pt_BR&amp;version=3" type="application/x-shockwave-flash" width="' . $width . '"></embed></object>';
                    $source = str_replace($_m, $ojwplayer, $source);
                }
            }
        }

        $preTag = null;
        $postTag = $source;
        $tagOpen_start = strpos($source, '<');

        while ($tagOpen_start !== false) {
            $preTag .= substr($postTag, 0, $tagOpen_start);
            $postTag = substr($postTag, $tagOpen_start);
            $fromTagOpen = substr($postTag, 1);
            $tagOpen_end = strpos($fromTagOpen, '>');

            if ($tagOpen_end === false) {
                break;
            }

            $tagOpen_nested = strpos($fromTagOpen, '<');

            if (($tagOpen_nested !== false) and ($tagOpen_nested < $tagOpen_end)) {
                $preTag .= substr($postTag, 0, ($tagOpen_nested + 1));
                $postTag = substr($postTag, ($tagOpen_nested + 1));
                $tagOpen_start = strpos($postTag, '<');
                continue;
            }

            $tagOpen_nested = (strpos($fromTagOpen, '<') + $tagOpen_start + 1);
            $currentTag = substr($fromTagOpen, 0, $tagOpen_end);
            $tagLength = strlen($currentTag);

            if (! $tagOpen_end) {
                $preTag .= $postTag;
                $tagOpen_start = strpos($postTag, '<');
            }

            $tagLeft = $currentTag;
            $attrSet = array();
            $currentSpace = strpos($tagLeft, ' ');

            if (substr($currentTag, 0, 1) == '/') {
                $isCloseTag = true;
                list($tagName) = explode(' ', $currentTag);
                $tagName = strtolower(substr($tagName, 1));
            } else {
                $isCloseTag = false;
                list($tagName) = explode(' ', $currentTag);
                $tagName = strtolower($tagName);
            }

            if ((! preg_match('/^[a-z][a-z0-9]*$/i', $tagName)) or in_array($tagName, $this->disabletags)) {
                $postTag = substr($postTag, ($tagLength + 2));
                $tagOpen_start = strpos($postTag, '<');
                continue;
            }

            while ($currentSpace !== false) {
                $fromSpace = substr($tagLeft, ($currentSpace + 1));
                $nextSpace = strpos($fromSpace, ' ');
                $openQuotes = strpos($fromSpace, '"');
                $closeQuotes = strpos(substr($fromSpace, ($openQuotes + 1)), '"') + $openQuotes + 1;

                if (strpos($fromSpace, '=') !== false) {
                    if (($openQuotes !== false) and (strpos(substr($fromSpace, ($openQuotes + 1)), '"') !== false)) {
                        $attr = substr($fromSpace, 0, ($closeQuotes + 1));
                    } else {
                        $attr = substr($fromSpace, 0, $nextSpace);
                    }
                } else {
                    $attr = substr($fromSpace, 0, $nextSpace);
                }

                if (! $attr) {
                    $attr = $fromSpace;
                }

                $attrSet[] = $attr;
                $tagLeft = substr($fromSpace, strlen($attr));
                $currentSpace = strpos($tagLeft, ' ');
            }

            if (! $isCloseTag) {
                $preTag .= '{@[' . $tagName;

                if (! empty($attrSet)) {
                    $attrSet = $this->filterAttr($attrSet);
                    $preTag .= ' ' . implode(' ', $attrSet);
                }

                $preTag .= (strpos($fromTagOpen, '</' . $tagName)) ? ']@}' : ' /]@}';
            } else {
                $preTag .= '{@[/' . $tagName . ']@}';
            }

            $postTag = substr($postTag, ($tagLength + 2));
            $tagOpen_start = strpos($postTag, '<');
        }

        $preTag .= $postTag;
        $preTag = str_replace(array( "'", '"', '<', '>' ), array( "&#039;", "&quot;", "&lt;", "&gt;" ), $preTag);
        return trim(str_replace(array( "[@{", "}@]", "{@[", "]@}" ), array( '"', '"', "<", '>' ), $preTag));
    }

    /**
     * Request::security_get()
     *
     * @param mixed $value
     * @return
     */
    private function security_get($value, $decode = false)
    {
        if (is_array($value)) {
            $keys = array_keys($value);
            foreach ($keys as $key) {
                $value[$key] = $this->security_get($value[$key], $decode);
            }
        } else {
            if (! empty($value) and ! is_numeric($value)) {
                if ($decode == true) {
                    $value = urldecode($value);
                }

                $value = str_replace(array( "\t", "\r", "\n", "../" ), "", $value);
                $value = $this->unhtmlentities($value);
                unset($matches);
                preg_match_all('/<!\[cdata\[(.*?)\]\]>/is', $value, $matches);
                $value = str_replace($matches[0], $matches[1], $value);
                $value = strip_tags($value);
                $value = preg_replace('#(' . implode('|', $this->disablecomannds) . ')(\s*)\((.*?)\)#si', "", $value);
                $value = str_replace(array( "'", '"', '<', '>' ), array( "&#039;", "&quot;", "&lt;", "&gt;" ), $value);
                $value = trim($value);
            }
        }
        return $value;
    }

    /**
     * Request::security_post()
     *
     * @param mixed $value
     * @return
     */
    public function security_post($value)
    {
        if (is_array($value)) {
            $keys = array_keys($value);
            foreach ($keys as $key) {
                $value[$key] = $this->security_post($value[$key]);
            }
        } else {
            // Fix block tag
            $value = str_replace(array( '[', ']' ), array( '&#91;', '&#93;' ), $value);

            if (preg_match_all('/<!\[cdata\[(.*?)\]\]>/is', $value, $matches)) {
                $value = str_replace($matches[0], $matches[1], $value);
            }
            $value = $this->filterTags($value);
        }
        return $value;
    }

    /**
     * Request::security_cookie()
     *
     * @param mixed $value
     * @return
     */
    private function security_cookie($value)
    {
        return $value;
    }

    /**
     * Request::security_session()
     *
     * @param mixed $value
     * @return
     */
    private function security_session($value)
    {
        return $value;
    }

    /**
     * Request::parse_mode()
     *
     * @param mixed $mode
     * @return
     */
    private function parse_mode($mode)
    {
        if (empty($mode)) {
            return array( $this->request_default_mode );
        }
        $mode = explode(',', $mode);
        $mode = array_map('trim', $mode);
        $mode = array_map('strtolower', $mode);
        $mode = array_intersect($this->allow_request_mods, $mode);
        if (empty($mode)) {
            return array( $this->request_default_mode );
        }
        return array_values($mode);
    }

    /**
     * Request::base64Encode()
     *
     * @param mixed $input
     * @return
     */
    private function base64Encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_,');
    }

    /**
     * Request::base64Decode()
     *
     * @param mixed $input
     * @return
     */
    private function base64Decode($input)
    {
        return base64_decode(strtr($input, '-_,', '+/='));
    }

    /**
     * Request::encodeCookie()
     *
     * @param mixed $string
     * @return
     */
    private function encodeCookie($string)
    {
        $result = '';
        $strlen = strlen($string);
        for ($i = 0; $i < $strlen; ++$i) {
            $char = substr($string, $i, 1);
            $keychar = substr($this->cookie_key, ($i % 32) - 1, 1);
            $result .= chr(ord($char) + ord($keychar));
        }
        return $this->base64Encode($result);
    }

    /**
     * Request::decodeCookie()
     *
     * @param mixed $string
     * @return
     */
    private function decodeCookie($string)
    {
        $result = '';
        $string = $this->base64Decode($string);
        $strlen = strlen($string);
        for ($i = 0; $i < $strlen; ++$i) {
            $char = substr($string, $i, 1);
            $keychar = substr($this->cookie_key, ($i % 32) - 1, 1);
            $result .= chr(ord($char) - ord($keychar));
        }
        return $result;
    }

    /**
     * Request::get_value()
     *
     * @param mixed $name
     * @param mixed $mode
     * @param mixed $default
     * @param bool $decode
     * @return
     */
    private function get_value($name, $mode = null, $default = null, $decode = true)
    {
        $modes = $this->parse_mode($mode);
        foreach ($modes as $mode) {
            switch ($mode) {
                case 'get':
                    if (array_key_exists($name, $_GET)) {
                        $value = $_GET[$name];
                        return $value;
                    }
                    break;
                case 'post':
                    if (array_key_exists($name, $_POST)) {
                        $value = $_POST[$name];
                        if (empty($value) or is_numeric($value)) {
                            return $value;
                        }
                        return $this->security_post($value);
                    }
                    break;
                case 'cookie':
                    if (array_key_exists($this->cookie_prefix . '_' . $name, $_COOKIE)) {
                        $value = $_COOKIE[$this->cookie_prefix . '_' . $name];
                        if ($decode) {
                            $value = $this->decodeCookie($value);
                        }
                        if (empty($value)) {
                            return $value;
                        }
                        return $this->security_cookie($value);
                    }
                    break;
                case 'session':
                    if (array_key_exists($this->session_prefix . '_' . $name, $_SESSION)) {
                        $value = $_SESSION[$this->session_prefix . '_' . $name];
                        if ($decode) {
                            $value = $this->decodeCookie($value);
                        }
                        if (empty($value)) {
                            return $value;
                        }
                        return $this->security_session($value);
                    }
                    break;
                case 'request':
                    if (array_key_exists($name, $_POST)) {
                        $value = $_POST[$name];
                        if (empty($value) or is_numeric($value)) {
                            return $value;
                        }
                        return $this->security_post($value);
                    } elseif (array_key_exists($name, $_GET)) {
                        $value = $_GET[$name];
                        return $value;
                    }
                    break;
                case 'env':
                    if (array_key_exists($name, $_ENV)) {
                        $value = $_ENV[$name];
                        return $value;
                    }
                    break;
                case 'server':
                    if (array_key_exists($name, $_SERVER)) {
                        $value = $_SERVER[$name];
                        return $value;
                    }
                    break;
            }
        }
        return $default;
    }

    /**
     * Request::set_Cookie()
     *
     * @param mixed $name
     * @param string $value
     * @param integer $expire
     * @param bool $encode
     * @return
     */
    public function set_Cookie($name, $value = '', $expire = 0, $encode = true)
    {
        if (is_array($value)) {
            return false;
        }
        if (empty($name)) {
            return false;
        }
        $name = $this->cookie_prefix . '_' . $name;
        if ($encode) {
            $value = $this->encodeCookie($value);
        }
        $expire = intval($expire);
        if (! empty($expire)) {
            $expire += NV_CURRENTTIME;
        }

        return setcookie($name, $value, $expire, $this->cookie_path, $this->cookie_domain, $this->secure, $this->httponly);
    }

    /**
     * Request::set_Session()
     *
     * @param mixed $name
     * @param string $value
     * @return
     */
    public function set_Session($name, $value = '')
    {
        if (is_array($value)) {
            return false;
        }
        if (empty($name)) {
            return false;
        }
        $name = $this->session_prefix . '_' . $name;
        $value = $this->encodeCookie($value);
        $_SESSION[$name] = $value;
        return true;
    }

    /**
     * Request::unset_request()
     *
     * @param mixed $names
     * @param mixed $mode
     * @return
     */
    public function unset_request($names, $mode)
    {
        if (empty($names)) {
            return array();
        }
        $names = ',' . $names;
        unset($matches);
        preg_match_all("/\,\s*([a-zA-Z\_]{1}[a-zA-Z0-9\_]*)/", $names, $matches);
        $names = $matches[1];
        if (empty($names)) {
            return false;
        }
        $mode = $this->parse_mode($mode);
        foreach ($mode as $arr) {
            if ($arr == 'get') {
                foreach ($names as $name) {
                    if (empty($name)) {
                        continue;
                    }
                    unset($_GET[$name]);
                }
            } elseif ($arr == 'post') {
                foreach ($names as $name) {
                    if (empty($name)) {
                        continue;
                    }
                    unset($_POST[$name]);
                }
            } elseif ($arr == 'cookie') {
                foreach ($names as $name) {
                    if (empty($name)) {
                        continue;
                    }
                    $name2 = $this->cookie_prefix . '_' . $name;
                    if (! isset($_COOKIE[$name2])) {
                        continue;
                    }
                    $expire = NV_CURRENTTIME - 3600;

                    setcookie($name2, '', $expire, $this->cookie_path, $this->cookie_domain, $this->secure, $this->httponly);

                    unset($_COOKIE[$name2]);
                }
            } elseif ($arr == 'session') {
                foreach ($names as $name) {
                    if (empty($name)) {
                        continue;
                    }
                    $name2 = $this->session_prefix . '_' . $name;
                    if (! isset($_SESSION[$name2])) {
                        continue;
                    }
                    unset($_SESSION[$name2]);
                }
            } elseif ($arr == 'request') {
                foreach ($names as $name) {
                    if (empty($name)) {
                        continue;
                    }
                    unset($_REQUEST[$name]);
                }
            } elseif ($arr == 'env') {
                foreach ($names as $name) {
                    if (empty($name)) {
                        continue;
                    }
                    unset($_ENV[$name]);
                }
            } elseif ($arr == 'server') {
                foreach ($names as $name) {
                    if (empty($name)) {
                        continue;
                    }
                    unset($_SERVER[$name]);
                }
            }
        }
    }

    /**
     * Request::isset_request()
     *
     * @param mixed $names
     * @param mixed $mode
     * @param bool $all
     * @return
     */
    public function isset_request($names, $mode, $all = true)
    {
        if (empty($names)) {
            return array();
        }
        $names = ',' . $names;
        unset($matches);
        preg_match_all("/\,\s*([a-zA-Z\_]{1}[a-zA-Z0-9\_]*)/", $names, $matches);
        $names = $matches[1];
        if (empty($names)) {
            return false;
        }
        $names = array_flip($names);
        $mode = $this->parse_mode($mode);
        foreach ($mode as $arr) {
            $array_keys = array_keys($names);
            foreach ($array_keys as $name) {
                if ($arr == 'get' and isset($_GET[$name])) {
                    if (empty($all)) {
                        return true;
                    }
                    unset($names[$name]);
                } elseif ($arr == 'post' and isset($_POST[$name])) {
                    if (empty($all)) {
                        return true;
                    }
                    unset($names[$name]);
                } elseif ($arr == 'cookie' and isset($_COOKIE[$this->cookie_prefix . '_' . $name])) {
                    if (empty($all)) {
                        return true;
                    }
                    unset($names[$name]);
                } elseif ($arr == 'session' and isset($_SESSION[$this->session_prefix . '_' . $name])) {
                    if (empty($all)) {
                        return true;
                    }
                    unset($names[$name]);
                } elseif ($arr == 'request' and isset($_REQUEST[$name])) {
                    if (empty($all)) {
                        return true;
                    }
                    unset($names[$name]);
                } elseif ($arr == 'env' and isset($_ENV[$name])) {
                    if (empty($all)) {
                        return true;
                    }
                    unset($names[$name]);
                } elseif ($arr == 'server' and isset($_SERVER[$name])) {
                    if (empty($all)) {
                        return true;
                    }
                    unset($names[$name]);
                }
            }
        }
        if (! empty($names)) {
            return false;
        }
        return true;
    }

    /**
     * Request::get_bool()
     *
     * @param mixed $name
     * @param mixed $mode
     * @param mixed $default
     * @param bool $decode
     * @return
     */
    public function get_bool($name, $mode = null, $default = null, $decode = true)
    {
        return ( bool )$this->get_value($name, $mode, $default, $decode);
    }

    /**
     * Request::get_int()
     *
     * @param mixed $name
     * @param mixed $mode
     * @param mixed $default
     * @param bool $decode
     * @return
     */
    public function get_int($name, $mode = null, $default = null, $decode = true)
    {
        return ( int )$this->get_value($name, $mode, $default, $decode);
    }

    /**
     * Request::get_float()
     *
     * @param mixed $name
     * @param mixed $mode
     * @param mixed $default
     * @param bool $decode
     * @return
     */
    public function get_float($name, $mode = null, $default = null, $decode = true)
    {
        return ( float )$this->get_value($name, $mode, $default, $decode);
    }

    /**
     * Request::get_string()
     *
     * @param mixed $name
     * @param mixed $mode
     * @param mixed $default
     * @param bool $decode
     * @return
     */
    public function get_string($name, $mode = null, $default = null, $decode = true)
    {
        return ( string )$this->get_value($name, $mode, $default, $decode);
    }

    /**
     * Request::_get_title()
     *
     * @param mixed $value
     * @param mixed $specialchars
     * @param mixed $preg_replace
     * @return
     */
    private function _get_title($value, $specialchars, $preg_replace)
    {
        $value = strip_tags($value);
        if (( bool )$specialchars == true) {
            $search = array( '&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '%', '^', ':', '{', '}', '`', '~' );
            $replace = array( '&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;' );

            $value = str_replace($replace, $search, $value);
            $value = str_replace("&#x23;", "#", $value);
            $value = str_replace($search, $replace, $value);
            $value = preg_replace("/([^\&]+)\#/", "\\1&#x23;", $value);
        }

        if (! empty($preg_replace)) {
            if (isset($preg_replace['pattern']) and ! empty($preg_replace['pattern']) and isset($preg_replace['replacement'])) {
                $value = preg_replace($preg_replace['pattern'], $preg_replace['replacement'], $value);
            }
        }
        return trim($value);
    }

    /**
     * Request::get_title()
     *
     * @param mixed $name
     * @param mixed $mode
     * @param mixed $default
     * @param bool $specialchars
     * @param mixed $preg_replace
     * @return
     */
    public function get_title($name, $mode = null, $default = null, $specialchars = false, $preg_replace = array())
    {
        $value = ( string )$this->get_value($name, $mode, $default);
        return $this->_get_title($value, $specialchars, $preg_replace);
    }

    /**
     * Request::_get_editor()
     *
     * @param mixed $value
     * @param mixed $allowed_html_tags
     * @return
     */
    private function _get_editor($value, $allowed_html_tags)
    {
        if (! empty($allowed_html_tags)) {
            $allowed_html_tags = array_map('trim', explode(',', $allowed_html_tags));
            $allowed_html_tags = '<' . implode('><', $allowed_html_tags) . '>';
            $value = strip_tags($value, $allowed_html_tags);
        }
        return trim($value);
    }

    /**
     * Request::get_editor()
     *
     * @param mixed $name
     * @param mixed $default
     * @param bool $allowed_html_tags
     * @param mixed $save
     * @return
     */
    public function get_editor($name, $default = '', $allowed_html_tags = '')
    {
        $value = ( string )$this->get_value($name, 'post', $default);
        return $this->_get_editor($value, $allowed_html_tags);
    }

    /**
     * Request::_get_textarea()
     *
     * @param mixed $value
     * @param mixed $allowed_html_tags
     * @param mixed $save
     * @return
     */
    private function _get_textarea($value, $allowed_html_tags, $save)
    {
        if (! empty($allowed_html_tags)) {
            $allowed_html_tags = array_map('trim', explode(',', $allowed_html_tags));
            $allowed_html_tags = '<' . implode('><', $allowed_html_tags) . '>';
            $value = strip_tags($value, $allowed_html_tags);
        }
        if (( bool )$save) {
            $value = strtr($value, array(
                "\r\n" => '<br />',
                "\r" => '<br />',
                "\n" => '<br />'
            ));
        }
        return trim($value);
    }

    /**
     * Request::get_textarea()
     *
     * @param mixed $name
     * @param mixed $default
     * @param bool $allowed_html_tags
     * @param mixed $save
     * @return
     */
    public function get_textarea($name, $default = '', $allowed_html_tags = '', $save = false)
    {
        $value = ( string )$this->get_value($name, 'post', $default);
        return $this->_get_textarea($value, $allowed_html_tags, $save);
    }

    /**
     * Request::get_array()
     *
     * @param mixed $name
     * @param mixed $mode
     * @param mixed $default
     * @param bool $decode
     * @return
     */
    public function get_array($name, $mode = null, $default = null, $decode = true)
    {
        return ( array )$this->get_value($name, $mode, $default, $decode);
    }

    /**
     * Request::get_typed_array()
     *
     * @param mixed $name
     * @param mixed $mode
     * @param mixed $type
     * @param mixed $default
     * @return
     */
    public function get_typed_array($name, $mode = null, $type = null, $default = null, $specialchars = false, $preg_replace = array(), $allowed_html_tags = '', $save = false)
    {
        $arr = $this->get_array($name, $mode, $default);
        $array_keys = array_keys($arr);
        foreach ($array_keys as $key) {
            switch ($type) {
                case 'bool':
                    $arr[$key] = ( bool )$arr[$key];
                    break;
                case 'int':
                    $arr[$key] = ( int )$arr[$key];
                    break;
                case 'float':
                    $arr[$key] = ( float )$arr[$key];
                    break;
                case 'string':
                    $arr[$key] = ( string )$arr[$key];
                    break;
                case 'array':
                    $arr[$key] = ( array )$arr[$key];
                    break;
                case 'title':
                    $arr[$key] = ( string )$this->_get_title($arr[$key], $specialchars, $preg_replace);
                    break;
                case 'textarea':
                    $arr[$key] = ( string )$this->_get_textarea($arr[$key], $allowed_html_tags, $save);
                    break;
                case 'editor':
                    $arr[$key] = ( string )$this->_get_editor($arr[$key], $allowed_html_tags);
            }
        }
        return $arr;
    }
}