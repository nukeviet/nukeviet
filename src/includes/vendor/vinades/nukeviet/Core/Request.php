<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

use NukeViet\Site;

/**
 * NukeViet\Core\Request
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class Request
{
    const IS_HEADERS_SENT = 'Warning: Headers already sent';

    const INCORRECT_ORIGIN = 'Incorrect Origin specified';

    const REQUEST_BLOCKED = 'Your request is blocked';

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

    private $origin;

    private $method;

    /**
     * @var int
     *
     * 0 cross origin referer
     * 1 same origin referer
     * 2 không có referer
     */
    public $referer_key;

    /**
     * @var int
     *
     * 0 cross origin
     * 1 same origin
     * 2 no origin header
     */
    private $origin_key;

    public $referer_host = '';

    public $ref_origin = '';

    public $referer_queries = false;

    public $request_uri;

    public $user_agent;

    public $search_engine = '';

    private $request_default_mode = 'request';

    private $allow_request_mods = [
        'get',
        'post',
        'request',
        'cookie',
        'session',
        'env',
        'server'
    ];

    private $cookie_prefix = 'NV4';

    private $session_prefix = 'NV4';

    private $cookie_key = 'nv4';

    private $secure = false;

    private $httponly = true;

    private $SameSite = '';

    private $cookie_share = false;

    private $set_cookie_by_options = false;

    private $remote_ip;

    private $str_referer_blocker = false;

    private $engine_allowed = [];

    // Cac tags bi cam dung mac dinh, co the go bo bang cach thay doi cac tags cho phep cua NV_ALLOWED_HTML_TAGS
    private $disabletags = [
        'applet',
        'body',
        'basefont',
        'head',
        'html',
        'id',
        'meta',
        'xml',
        'blink',
        'link',
        'style',
        'script',
        'iframe',
        'frame',
        'frameset',
        'ilayer',
        'layer',
        'bgsound',
        'title',
        'base'
    ];

    protected $remoteAttrCheck = [
        'action' => ['form'],
        'src' => ['iframe', 'embed'],
        'data' => ['object']
    ];

    /**
     * Các attr bị cấm, sẽ bị lọc bỏ.
     * - Tất cả các arrt bắt đầu bằng on
     * - Các attr bên dưới
     */
    private $disabledattributes = [
        'action',
        'background',
        'codebase',
        'dynsrc',
        'lowsrc',
        'allownetworking', // Control a SWF file’s access to network functionality by setting the allowNetworking parameter = internal
        'allowscriptaccess', // Loại bỏ điều khiển cho phép javascript trong embed, tự động đặt = never
        'fscommand', // attacker can use this when executed from within an embedded Flash object
        'seeksegmenttime' // this is a method that locates the specified point on the element’s segment time line and begins playing from that point. The segment consists of one repetition of the time line including reverse play using the AUTOREVERSE attribute.
    ];

    private $disablecomannds = [
        'base64_decode',
        'cmd',
        'passthru',
        'eval',
        'exec',
        'system',
        'fopen',
        'fsockopen',
        'file',
        'file_get_contents',
        'readfile',
        'unlink'
    ];

    /**
     * @var array
     */
    protected $corsHeaders = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type', // Các Header được phép trong CORS
        'Access-Control-Allow-Methods' => 'PUT, GET, POST, DELETE, OPTIONS', // Các phương thước được phép trong CORS
        'Access-Control-Allow-Credentials' => 'true', // Cho phép gửi cookie trong truy vấn CORS
        'Access-Control-Max-Age' => 10 * 60 * 60, // 10 min, max age for Chrome. Thời gian cache preflight request (request OPTIONS kiểm tra)
        'Vary' => 'Origin' // Thông báo cho trình duyệt biết, mỗi Origin khác nhau sẽ có mỗi phản hồi khác nhau thay vì dùng *
    ];

    /**
     * @since 4.4.01
     */
    protected $restrictCrossDomain = true;
    protected $validCrossDomains = [];
    protected $validCrossIPs = [];
    protected $crossAllowedVariables = [];

    protected $isOriginValid = false;
    protected $isRefererValid = false;

    protected $isIpValid = false;

    protected $isRestrictDomain = true;
    protected $validDomains = [];

    /**
     * @since 4.5.00
     */
    private $allowNullOrigin = false;
    private $allowNullOriginIps = [];

    /**
     * @since 5.0.00
     */
    private $autoACAO = false;
    private $nat_ports = [];
    static $Server;

    /**
     * __construct()
     *
     * @param array                 $config
     * @param string                $ip
     * @param \NukeViet\Core\Server $nv_Server
     */
    public function __construct($config, $ip, $nv_Server = false)
    {
        if (isset($config['allowed_html_tags']) and is_array($config['allowed_html_tags'])) {
            $this->disabletags = array_diff($this->disabletags, $config['allowed_html_tags']);
        }
        if (!empty($config['allow_request_mods'])) {
            if (!is_array($config['allow_request_mods'])) {
                $config['allow_request_mods'] = [$config['allow_request_mods']];
            }
            $this->allow_request_mods = array_intersect($this->allow_request_mods, $config['allow_request_mods']);
        }
        if (!empty($config['request_default_mode']) and in_array($config['request_default_mode'], $this->allow_request_mods, true)) {
            $this->request_default_mode = $config['request_default_mode'];
        }
        if (!empty($config['cookie_secure'])) {
            $this->secure = true;
        }
        if (!empty($config['cookie_httponly'])) {
            $this->httponly = true;
        }
        if (!empty($config['cookie_share'])) {
            $this->cookie_share = true;
        }
        if (!empty($config['cookie_SameSite']) and in_array($config['cookie_SameSite'], [
            'Lax',
            'Strict',
            'None'
        ], true)) {
            $this->SameSite = $config['cookie_SameSite'];
        }
        $this->set_cookie_by_options = version_compare(PHP_VERSION, '7.3.0', '>=');
        if (!empty($config['cookie_prefix'])) {
            $this->cookie_prefix = preg_replace('/[^a-zA-Z0-9\_]+/', '', $config['cookie_prefix']);
        }
        if (!empty($config['session_prefix'])) {
            $this->session_prefix = preg_replace('/[^a-zA-Z0-9\_]+/', '', $config['session_prefix']);
        }
        if (!empty($config['sitekey'])) {
            $this->cookie_key = $config['sitekey'];
        }
        if (!empty($config['str_referer_blocker'])) {
            $this->str_referer_blocker = true;
        }
        $this->engine_allowed = (array) $config['engine_allowed'];

        if (defined('NV_ADMIN')) {
            $this->restrictCrossDomain = !empty($config['crossadmin_restrict']) ? true : false;
            $this->validCrossDomains = !empty($config['crossadmin_valid_domains']) ? ((array) $config['crossadmin_valid_domains']) : [];
            $this->validCrossIPs = !empty($config['crossadmin_valid_ips']) ? ((array) $config['crossadmin_valid_ips']) : [];
            $this->crossAllowedVariables = !empty($config['crossadmin_allowed_variables']) ? ((array) $config['crossadmin_allowed_variables']) : [];
        } elseif (defined('NV_REMOTE_API')) {
            $this->restrictCrossDomain = false;
            $this->validCrossDomains = [];
            $this->validCrossIPs = [];
            $this->crossAllowedVariables = [];
        } else {
            $this->restrictCrossDomain = !empty($config['crosssite_restrict']) ? true : false;
            $this->validCrossDomains = !empty($config['crosssite_valid_domains']) ? ((array) $config['crosssite_valid_domains']) : [];
            $this->validCrossIPs = !empty($config['crosssite_valid_ips']) ? ((array) $config['crosssite_valid_ips']) : [];
            $this->crossAllowedVariables = !empty($config['crosssite_allowed_variables']) ? ((array) $config['crosssite_allowed_variables']) : [];
        }

        $this->isRestrictDomain = !empty($config['domains_restrict']) ? true : false;
        $this->validDomains = !empty($config['domains_whitelist']) ? ((array) $config['domains_whitelist']) : [];
        $this->allowNullOrigin = !empty($config['allow_null_origin']) ? true : false;
        $this->allowNullOriginIps = !empty($config['ip_allow_null_origin']) ? ((array) $config['ip_allow_null_origin']) : [];
        $this->autoACAO = !empty($config['auto_acao']) ? true : false;
        if (!empty($config['nat_ports'])) {
            foreach ($config['nat_ports'] as $port) {
                preg_match('/^[0-9]+$/', $port) && $this->nat_ports[] = (string) $port;
            }
        }

        $this->remote_ip = !empty($ip) ? $ip : Ips::$remote_ip;
        if (Ips::ip2long($this->remote_ip) === false) {
            http_response_code(403);
            trigger_error(Ips::INCORRECT_IP, 256);
        }

        $this->cookie_key = md5($this->cookie_key);

        self::$Server = ($nv_Server === false) ? new Server() : $nv_Server;
        $this->Initialize();
        $this->get_cookie_save_path();

        $this->sessionStart(!empty($config['https_only']));
        $_REQUEST = array_merge($_POST, array_diff_key($_GET, $_POST));
    }

    /**
     * Initialize()
     */
    private function Initialize()
    {
        if (count($_GET)) {
            $array_keys = array_keys($_GET);
            foreach ($array_keys as $k) {
                if (!preg_match('/^[a-zA-Z0-9\_]+$/', $k) or is_numeric($k)) {
                    unset($_GET[$k]);
                }
            }
        }
        if (count($_POST)) {
            $array_keys = array_keys($_POST);
            foreach ($array_keys as $k) {
                if ((!preg_match('/^[a-zA-Z0-9\_]+$/', $k) and $k != 'g-recaptcha-response' and $k != 'cf-turnstile-response') or is_numeric($k)) {
                    unset($_POST[$k]);
                }
            }
        }
        if (count($_COOKIE)) {
            $array_keys = array_keys($_COOKIE);
            foreach ($array_keys as $k) {
                if (!preg_match('/^[a-zA-Z0-9\_]+$/', $k) or is_numeric($k)) {
                    // Name cannot contain = , ; [space] /t /r /n /013 /014
                    if (!preg_match('/[\,\=\; \t\r\n\x0D\x0E]+/', $k)) {
                        setcookie($k, '', NV_CURRENTTIME - 3600);
                    }
                    unset($_COOKIE[$k]);
                }
            }
        }
        if (count($_FILES) and strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $array_keys = array_keys($_FILES);
            foreach ($array_keys as $k) {
                if (!preg_match('/^[a-zA-Z0-9\_]+$/', $k) or is_numeric($k)) {
                    unset($_FILES[$k]);
                }
            }
        }
        $query = http_build_query($_GET);
        $_SERVER['QUERY_STRING'] = $query;
        $_SERVER['argv'] = [$query];
        $this->request_uri = (empty($_SERVER['REQUEST_URI'])) ? $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] : $_SERVER['REQUEST_URI'];
        $doc_root = isset($_SERVER['DOCUMENT_ROOT']) ? preg_replace('/[\/]+$/', '', str_replace(DIRECTORY_SEPARATOR, '/', $_SERVER['DOCUMENT_ROOT'])) : '';

        if (defined('NV_BASE_SITEURL')) {
            $base_siteurl = preg_replace('/[\/]+$/', '', NV_BASE_SITEURL);
        } else {
            $base_siteurl = self::$Server->getWebsitePath();
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
        $_SERVER['SERVER_PORT'] = Site::getEnv('SERVER_PORT');
        $_SERVER['SERVER_PROTOCOL'] = Site::getEnv('SERVER_PROTOCOL');

        if (defined('NV_SERVER_NAME')) {
            $this->server_name = NV_SERVER_NAME;
        } else {
            $this->server_name = self::$Server->getServerHost();
        }
        if (defined('NV_SERVER_PROTOCOL')) {
            $this->server_protocol = NV_SERVER_PROTOCOL;
        } else {
            $this->server_protocol = self::$Server->getServerProtocol();
        }
        if (defined('NV_SERVER_PORT')) {
            $this->server_port = NV_SERVER_PORT;
        } else {
            $this->server_port = self::$Server->getServerPort();
        }

        $this->base_siteurl = $base_siteurl;
        $this->base_adminurl = $base_siteurl . (NV_ADMINDIR != '' ? '/' . NV_ADMINDIR : '');
        $this->doc_root = $doc_root;

        if (defined('NV_MY_DOMAIN')) {
            $this->my_current_domain = NV_MY_DOMAIN;
        } else {
            $this->my_current_domain = self::$Server->getOriginalDomain();
        }

        $this->headerstatus = (substr(php_sapi_name(), 0, 3) == 'cgi') ? 'Status:' : $_SERVER['SERVER_PROTOCOL'];
        $this->site_url = $this->my_current_domain . $this->base_siteurl;
        $this->standardizeReferer();
        $this->standardizeOrigin();
        $this->method = strtoupper(Site::getEnv(['REQUEST_METHOD', 'Method']));

        // CORS handle
        if (!empty($this->origin)) {
            $hasControlRequestHeader = Site::getEnv(['HTTP_ACCESS_CONTROL_REQUEST_HEADERS', 'Access-Control-Request-Headers']);

            if ($this->autoACAO) {
                $this->corsHeaders['Access-Control-Allow-Origin'] = $this->getAllowOriginHeaderValue();
                foreach ($this->corsHeaders as $header => $value) {
                    header($header . ': ' . $value);
                }
            }

            // Kiểm tra preflight request
            if ($this->method == 'OPTIONS' and !empty($hasControlRequestHeader)) {
                exit(0);
            }
        }

        if ($this->str_referer_blocker and !empty($_SERVER['QUERY_STRING']) and $this->referer_key == 0 and empty($this->search_engine)) {
            header('Location: ' . $this->site_url);
            exit(0);
        }

        $user_agent = (string) Site::getEnv('HTTP_USER_AGENT');
        $user_agent = substr(htmlspecialchars($user_agent), 0, 255);
        if (!empty($user_agent)) {
            $user_agent = trim($user_agent);
        }
        if (empty($user_agent) or $user_agent == '-') {
            $user_agent = 'none';
        }
        $this->user_agent = $user_agent;
        $_SERVER['HTTP_USER_AGENT'] = $user_agent;

        // Cross-Site handle
        if (count($_POST) or $this->method == 'POST') {
            if ($this->origin_key == 0 or $this->referer_key !== 1) {
                // Post cross hoặc không same referer
                if (!$this->restrictCrossDomain or in_array($this->remote_ip, $this->validCrossIPs, true)) {
                    $this->isIpValid = true;
                }
            } else {
                // Same referer hoặc không cross
                $this->isIpValid = true;
            }
            if (!(($this->isRefererValid and (empty($this->origin) or $this->isOriginValid)) or $this->isIpValid)) {
                $crossAllowedVariables = false;
                if (!empty($this->crossAllowedVariables)) {
                    foreach ($this->crossAllowedVariables as $variables) {
                        $intersect = array_intersect_key($variables, $_POST);
                        if (count($intersect) != count($variables)) {
                            continue;
                        }
                        $allowed = true;
                        foreach ($intersect as $k => $v) {
                            if (!empty($v) and $v != $_POST[$k]) {
                                $allowed = false;
                                break;
                            }
                        }
                        if ($allowed) {
                            $crossAllowedVariables = true;
                            break;
                        }
                    }
                }

                if (!$crossAllowedVariables) {
                    http_response_code(403);
                    trigger_error(Request::REQUEST_BLOCKED, 256);
                }
            }
        }
    }

    /**
     * standardizeOrigin()
     * Chuẩn hóa, kiểm tra Origin header
     */
    private function standardizeOrigin()
    {
        $this->origin = Site::getEnv(['HTTP_ORIGIN', 'Origin']);
        if (!empty($this->origin)) {
            $origin = parse_url($this->origin);
            if (isset($origin['scheme']) and in_array($origin['scheme'], ['http', 'https', 'ftp', 'gopher'], true) and isset($origin['host'])) {
                $origin['port'] = (isset($origin['port']) and $origin['port'] != '80' and $origin['port'] != '443') ? (string) $origin['port'] : '';
                $scheme_host_origin = $origin['scheme'] . '://' . $origin['host'];
                $_SERVER['HTTP_ORIGIN'] = $scheme_host_origin . (!empty($origin['port']) ? ':' . $origin['port'] : '');
                $this->origin = $_SERVER['HTTP_ORIGIN'];

                $this->origin_key = 0;
                if ($this->my_current_domain == $this->origin) {
                    $this->origin_key = 1;
                } elseif (!empty($this->nat_ports)) {
                    $host = self::$Server->getOriginalHost();
                    if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
                        $host = '[' . $host . ']';
                    }
                    $ports = $this->nat_ports;
                    $ports[] = (string) self::$Server->getOriginalPort();
                    if (($scheme_host_origin == self::$Server->getOriginalProtocol() . '://' . $host) and in_array($origin['port'], $ports, true)) {
                        $this->origin_key = 1;
                    }
                }
            } elseif (strtolower($this->origin) == 'null') {
                // Null Origin xem như là Cross-Site
                $this->origin_key = 0;
            } else {
                /*
                 * Origin có dạng `Origin: <scheme> "://" <hostname> [ ":" <port> ]` hoặc null
                 * Nếu sai thì từ chối truy vấn
                 */
                unset($_SERVER['HTTP_ORIGIN']);
                http_response_code(403);
                trigger_error(Request::INCORRECT_ORIGIN, 256);
            }
        } else {
            $this->origin_key = 2;
        }
    }

    /**
     * standardizeReferer()
     * Chuẩn hóa, kiểm tra Referer header
     */
    private function standardizeReferer()
    {
        $this->referer = Site::getEnv(['HTTP_REFERER', 'Referer']);
        if (!empty($this->referer)) {
            $ref = parse_url($this->referer);
            if (isset($ref['scheme']) and in_array($ref['scheme'], ['http', 'https', 'ftp', 'gopher'], true) and isset($ref['host'])) {
                $this->ref_origin = ($ref['scheme'] . '://' . $ref['host'] . ((isset($ref['port']) and $ref['port'] != '80' and $ref['port'] != '443') ? (':' . $ref['port']) : ''));
                // Server dạng IPv6 trực tiếp
                if (substr($ref['host'], 0, 1) == '[' and substr($ref['host'], -1) == ']') {
                    $ref['host'] = substr($ref['host'], 1, -1);
                }
                if (preg_match('/^' . preg_quote($ref['host'], '/') . '/', $this->server_name)) {
                    $this->referer_key = 1;
                } else {
                    $this->referer_key = 0;
                    if (!empty($this->engine_allowed)) {
                        foreach ($this->engine_allowed as $se => $v) {
                            if (preg_match('/' . preg_quote($v['host_pattern'], '/') . '/i', $ref['host'])) {
                                $this->search_engine = $se;
                                break;
                            }
                        }
                    }
                }
                $this->referer_host = $ref['host'];
                $tmp = [];
                $base = $this->referer;
                if (isset($ref['query']) and !empty($ref['query'])) {
                    [$base, $query_string] = explode('?', $this->referer);
                    parse_str($query_string, $parameters);
                    foreach ($parameters as $key => $value) {
                        if (preg_match('/^[a-zA-Z\_][a-zA-Z0-9\_]*$/', $key)) {
                            $tmp[$key] = $this->security_get($value, true);
                        }
                    }
                }
                if (!empty($tmp)) {
                    $this->referer_queries = $tmp;
                    $_SERVER['HTTP_REFERER'] = $base . '?' . http_build_query($tmp);
                } else {
                    $_SERVER['HTTP_REFERER'] = $base;
                }
                $this->referer = $_SERVER['HTTP_REFERER'];

                if (!$this->restrictCrossDomain or $this->referer_key === 1 or in_array($this->ref_origin, $this->validCrossDomains, true)) {
                    $this->isRefererValid = true;
                }
            } else {
                $this->referer_key = 0;
                $this->referer = '';
                unset($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->referer_key = 2;
            unset($_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * get_cookie_save_path()
     */
    private function get_cookie_save_path()
    {
        $this->cookie_path = $this->base_siteurl . '/';
        if ($this->cookie_share) {
            $cookie_domain = preg_replace('/^([w]{3})\./', '', $this->server_name);
            $this->cookie_domain = (preg_match('/^([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/', $cookie_domain)) ? '.' . $cookie_domain : '';
        } else {
            $this->cookie_domain = '';
        }
    }

    /**
     * sessionStart()
     *
     * @param bool $https_only
     */
    private function sessionStart($https_only)
    {
        if (headers_sent() or connection_status() != 0 or connection_aborted()) {
            http_response_code(500);
            trigger_error(Request::IS_HEADERS_SENT, 256);
        }

        $_secure = ($this->server_protocol == 'https' and $https_only) ? 1 : 0;
        if ($this->set_cookie_by_options) {
            $options = [
                'lifetime' => NV_LIVE_SESSION_TIME,
                'path' => $this->cookie_path,
                'domain' => $this->cookie_domain,
                'secure' => $_secure,
                'httponly' => 1
            ];
            if ($this->SameSite == 'Lax' or $this->SameSite == 'Strict') {
                $options['samesite'] = $this->SameSite;
            }
            session_set_cookie_params($options);
        } else {
            session_set_cookie_params(NV_LIVE_SESSION_TIME, $this->cookie_path, $this->cookie_domain, $_secure, 1);
        }

        session_name($this->cookie_prefix . '_sess');
        ini_set('session.use_strict_mode', 1);
        session_start();
        $session_id = session_id();

        $_SESSION = (isset($_SESSION) and is_array($_SESSION)) ? $_SESSION : [];
        if (count($_SESSION)) {
            $array_keys = array_keys($_SESSION);
            foreach ($array_keys as $k) {
                if (!preg_match('/^[a-zA-Z0-9\_]+$/', $k) or is_numeric($k)) {
                    unset($_SESSION[$k]);
                }
            }
        }

        if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < NV_CURRENTTIME - 180) {
            $_SESSION = [];
            session_destroy();
            session_start();
        }

        $this->session_id = $session_id;
    }

    /**
     * filterAttr()
     *
     * @param array  $attrSet
     * @param string $tagName
     * @param bool   $isvalid
     * @return array
     */
    private function filterAttr($attrSet, $tagName, &$isvalid)
    {
        $newSet = [];

        for ($i = 0, $count = count($attrSet); $i < $count; ++$i) {
            if (!$attrSet[$i]) {
                continue;
            }
            $attrSubSet = array_map('trim', explode('=', trim($attrSet[$i]), 2));
            $attrSubSet[0] = strtolower($attrSubSet[0]);

            if (!preg_match('/[a-z]+/i', $attrSubSet[0]) or in_array($attrSubSet[0], $this->disabledattributes, true) or preg_match('/^on/i', $attrSubSet[0])) {
                continue;
            }

            if (!empty($attrSubSet[1])) {
                $attrSubSet[1] = preg_replace('/[ ]+/', ' ', $attrSubSet[1]);
                $attrSubSet[1] = preg_replace('/^"(.*)"$/', '\\1', $attrSubSet[1]);
                $attrSubSet[1] = preg_replace("/^\'(.*)\'$/", '\\1', $attrSubSet[1]);
                $attrSubSet[1] = str_replace(['"', '&quot;'], "'", $attrSubSet[1]);

                $value = Site::unhtmlentities($attrSubSet[1]);

                // Security check Data URLs
                if (preg_match('/^[\r\n\s\t]*d\s*a\s*t\s*a\s*\:([^\,]*?)\;*[\r\n\s\t]*(base64)*?[\r\n\s\t]*\,[\r\n\s\t]*(.*?)[\r\n\s\t]*$/isu', $value, $m)) {
                    if (empty($m[2])) {
                        $dataURLs = urldecode($m[3]);
                    } else {
                        $dataURLs = (string) base64_decode($m[3], true);
                    }

                    $checkValid = true;
                    $this->filterTags($dataURLs, $checkValid);
                    if (!$checkValid) {
                        continue;
                    }
                }

                if (preg_match('/\<\s*s\s*c\s*r\s*i\s*p\s*t([^\>]*)\>(.*)\<\s*\/\s*s\s*c\s*r\s*i\s*p\s*t\s*\>/isU', $value)) {
                    continue;
                }

                $search = [
                    'javascript' => '/j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t/si',
                    'vbscript' => '/v\s*b\s*s\s*c\s*r\s*i\s*p\s*t/si',
                    'script' => '/s\s*c\s*r\s*i\s*p\s*t/si',
                    'applet' => '/a\s*p\s*p\s*l\s*e\s*t/si',
                    'alert' => '/a\s*l\s*e\s*r\s*t/si',
                    'document' => '/d\s*o\s*c\s*u\s*m\s*e\s*n\s*t/si',
                    'write' => '/w\s*r\s*i\s*t\s*e/si',
                    'cookie' => '/c\s*o\s*o\s*k\s*i\s*e/si',
                    'window' => '/w\s*i\s*n\s*d\s*o\s*w/si',
                    'data:' => '/d\s*a\s*t\s*a\s*\:/si'
                ];
                $value = preg_replace(array_values($search), array_keys($search), $value);

                // Giới hạn link từ các tên miền bên ngoài
                if ($this->isRestrictDomain and isset($this->remoteAttrCheck[$attrSubSet[0]]) and in_array($tagName, $this->remoteAttrCheck[$attrSubSet[0]], true)) {
                    $url_info = parse_url($value);
                    if (isset($url_info['host'])) {
                        $domain = $url_info['host'];
                        $callBack = function ($domain_allowed) use ($domain) {
                            return preg_match('/^' . preg_quote($domain, '/') . '$/iu', $domain_allowed);
                        };
                        if (!array_filter($this->validDomains, $callBack)) {
                            continue;
                        }
                    }
                }

                // Security remove object param tag
                if ('param' == $tagName and 'name' == $attrSubSet[0] and preg_match('/^[\r\n\s\t]*(allowscriptaccess|allownetworking)/isu', strtolower($value))) {
                    return [];
                }
                if (preg_match('/(expression|javascript|behaviour|vbscript|mocha|livescript)(\:*)/', $value)) {
                    continue;
                }
                if (!empty($this->disablecomannds) and preg_match('#(' . implode('|', $this->disablecomannds) . ')(\s*)\((.*?)\)#si', $value)) {
                    continue;
                }

                if ('href' != $attrSubSet[0]) {
                    $attrSubSet[1] = preg_replace_callback('/\#([0-9ABCDEFabcdef]{3,6})[\;]*/', function ($hex) {
                        if (preg_match('/[^0-9ABCDEFabcdef]/', $hex[1])) {
                            return $hex[0];
                        }
                        $color = $hex[1];
                        $l = strlen($color);
                        if ($l != 3 and $l != 6) {
                            return $hex[0];
                        }
                        $l /= 3;

                        return 'rgb(' . (hexdec(substr($color, 0, 1 * $l))) . ', ' . (hexdec(substr($color, 1 * $l, 1 * $l))) . ', ' . (hexdec(substr($color, 2 * $l, 1 * $l))) . ');';
                    }, $attrSubSet[1]);
                }
            } elseif ($attrSubSet[1] !== '0') {
                $attrSubSet[1] = $attrSubSet[0];
            }
            $newSet[] = $attrSubSet[0] . '=[@{' . $attrSubSet[1] . '}@]';
        }

        if ($tagName == 'embed') {
            $newSet[] = 'allowscriptaccess=[@{never}@]';
            $newSet[] = 'allownetworking=[@{internal}@]';
        }

        return $newSet;
    }

    /**
     * filterTags()
     *
     * @param string $source
     * @param bool   $isvalid
     * @return string
     */
    private function filterTags($source, &$isvalid = true)
    {
        $checkInvalid = 0;
        $source = preg_replace('/\<\s*s\s*c\s*r\s*i\s*p\s*t([^\>]*)\>(.*)\<\s*\/\s*s\s*c\s*r\s*i\s*p\s*t\s*\>/isU', '', $source, -1, $checkInvalid);
        if ($checkInvalid > 0) {
            $isvalid = false;
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

            if (!$tagOpen_end) {
                $preTag .= $postTag;
                $tagOpen_start = strpos($postTag, '<');
            }

            $tagLeft = $currentTag;
            $attrSet = [];
            $currentSpace = strpos($tagLeft, ' ');

            if (substr($currentTag, 0, 1) == '/') {
                $isCloseTag = true;
                [$tagName] = explode(' ', $currentTag);
                $tagName = strtolower(substr($tagName, 1));
            } else {
                $isCloseTag = false;
                [$tagName] = explode(' ', $currentTag);
                $tagName = strtolower($tagName);
            }

            if ((!preg_match('/^[a-z][a-z0-9]*$/i', $tagName)) or in_array($tagName, $this->disabletags, true)) {
                $postTag = substr($postTag, ($tagLength + 2));
                $tagOpen_start = strpos($postTag, '<');
                $isvalid = false;
                continue;
            }

            while ($currentSpace !== false) {
                $fromSpace = substr($tagLeft, ($currentSpace + 1));
                $nextSpace = strpos($fromSpace, ' ');
                $openQuotes = strpos($fromSpace, '"');
                $closeQuotes = strpos(substr($fromSpace, ($openQuotes + 1)), '"') + $openQuotes + 1;

                if (str_contains($fromSpace, '=')) {
                    if (($openQuotes !== false) and (str_contains(substr($fromSpace, ($openQuotes + 1)), '"'))) {
                        $attr = substr($fromSpace, 0, ($closeQuotes + 1));
                    } else {
                        $attr = substr($fromSpace, 0, $nextSpace);
                    }
                } else {
                    $attr = substr($fromSpace, 0, $nextSpace);
                }

                if (!$attr) {
                    $attr = $fromSpace;
                }

                $attrSet[] = $attr;
                $tagLeft = substr($fromSpace, strlen($attr));
                $currentSpace = strpos($tagLeft, ' ');
            }

            if (!$isCloseTag) {
                if (!empty($attrSet)) {
                    $attrSet = $this->filterAttr($attrSet, $tagName, $isvalid);
                }
                if (!('param' == $tagName and empty($attrSet))) {
                    $preTag .= '{@[' . $tagName;
                    if (!empty($attrSet)) {
                        $preTag .= ' ' . implode(' ', $attrSet);
                    }
                    $preTag .= (strpos($fromTagOpen, '</' . $tagName)) ? ']@}' : ' /]@}';
                    if ($tagName == 'object') {
                        if (preg_match('/\]\@\}([\s]+)\{\@\[' . $tagName . '/', $preTag, $m)) {
                            $space = $m[1] . '    ';
                        } else {
                            $space = "\n    ";
                        }
                        $preTag .= $space . '{@[param name=[@{allowscriptaccess}@] value=[@{never}@] /]@}' . $space . "{@[param name=[@{allownetworking}@] value=[@{internal}@] /]@}\n";
                    }
                }
            } else {
                $preTag .= '{@[/' . $tagName . ']@}';
            }

            $postTag = substr($postTag, ($tagLength + 2));
            $tagOpen_start = strpos($postTag, '<');
        }

        $preTag .= $postTag;
        while (preg_match('/\<\s*s\s*c\s*r\s*i\s*p\s*t([^\>]*)\>(.*)\<\s*\/\s*s\s*c\s*r\s*i\s*p\s*t\>/isU', $preTag)) {
            $preTag = preg_replace('/\<script([^\>]*)\>(.*)\<\/script\>/isU', '', $preTag);
        }
        $preTag = str_replace(["'", '"', '<', '>'], ['&#039;', '&quot;', '&lt;', '&gt;'], $preTag);

        return trim(str_replace(['[@{', '}@]', '{@[', ']@}'], ['"', '"', '<', '>'], $preTag));
    }

    /**
     * security_get()
     *
     * @param array $value
     * @param bool  $decode
     * @return mixed
     */
    private function security_get($value, $decode = false)
    {
        if (is_array($value)) {
            $keys = array_keys($value);
            foreach ($keys as $key) {
                $value[$key] = $this->security_get($value[$key], $decode);
            }
        } else {
            if (!empty($value) and !is_numeric($value)) {
                if ($decode == true) {
                    $value = urldecode($value);
                }

                $value = str_replace(["\t", "\r", "\n", '../'], '', $value);
                $value = Site::unhtmlentities($value);
                unset($matches);
                preg_match_all('/<!\[cdata\[(.*?)\]\]>/is', $value, $matches);
                $value = str_replace($matches[0], $matches[1], $value);
                $value = strip_tags($value);
                $value = preg_replace('#(' . implode('|', $this->disablecomannds) . ')(\s*)\((.*?)\)#si', '', $value);
                $value = str_replace(["'", '"', '<', '>'], ['&#039;', '&quot;', '&lt;', '&gt;'], $value);
                $value = trim($value);
            }
        }

        return $value;
    }

    /**
     * security_post()
     *
     * @param mixed $value
     * @return array|string
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
            $value = str_replace(['[', ']'], ['&#91;', '&#93;'], $value);

            if (preg_match_all('/<!\[cdata\[(.*?)\]\]>/is', $value, $matches)) {
                $value = str_replace($matches[0], $matches[1], $value);
            }
            $value = $this->filterTags($value);
        }

        return $value;
    }

    /**
     * security_cookie()
     *
     * @param mixed $value
     * @return mixed
     */
    private function security_cookie($value)
    {
        return $value;
    }

    /**
     * security_session()
     *
     * @param mixed $value
     * @return mixed
     */
    private function security_session($value)
    {
        return $value;
    }

    /**
     * parse_mode()
     *
     * @param string $mode
     * @return mixed
     */
    private function parse_mode($mode)
    {
        if (empty($mode)) {
            return [$this->request_default_mode];
        }
        $mode = array_map('trim', explode(',', strtolower($mode)));
        $mode = array_intersect($this->allow_request_mods, $mode);
        if (empty($mode)) {
            return [$this->request_default_mode];
        }

        return array_values($mode);
    }

    /**
     * encodeCookie()
     *
     * @param array|string $string
     * @return string
     */
    private function encodeCookie($string)
    {
        $prefix = '';
        if (is_array($string)) {
            $string = json_encode($string);
            $prefix = 'jsn.';
        }
        $iv = substr($this->cookie_key, 0, 16);
        $string = openssl_encrypt($string, 'aes-256-cbc', $this->cookie_key, 0, $iv);

        return $prefix . strtr($string, '+/=', '-_,');
    }

    /**
     * decodeCookie()
     *
     * @param string $string
     * @return array|false|string
     */
    private function decodeCookie($string)
    {
        $isJsonDecode = false;
        if (substr($string, 0, 4) == 'jsn.') {
            $string = substr($string, 4);
            $isJsonDecode = true;
        }
        $string = strtr($string, '-_,', '+/=');
        $iv = substr($this->cookie_key, 0, 16);
        $string = openssl_decrypt($string, 'aes-256-cbc', $this->cookie_key, 0, $iv);
        if ($isJsonDecode) {
            return json_decode($string, true);
        }

        return $string;
    }

    /**
     * get_value()
     *
     * @param string      $name
     * @param string|null $mode
     * @param mixed|null  $default
     * @param bool        $decode
     * @param bool        $filter
     * @return mixed
     */
    private function get_value($name, $mode = null, $default = null, $decode = true, $filter = true)
    {
        $modes = $this->parse_mode($mode);
        foreach ($modes as $mode) {
            switch ($mode) {
                case 'get':
                    if (array_key_exists($name, $_GET)) {
                        $value = $_GET[$name];
                        if (empty($value) or is_numeric($value)) {
                            return $value;
                        }
                        $value = self::compound_unicode($value);
                        return ($filter == true) ? $this->security_get($value) : $value;
                    }
                    break;
                case 'post':
                    if (array_key_exists($name, $_POST)) {
                        $value = $_POST[$name];
                        if (empty($value) or is_numeric($value)) {
                            return $value;
                        }
                        $value = self::compound_unicode($value);
                        return ($filter == true) ? $this->security_post($value) : $value;
                    }
                    break;
                case 'cookie':
                    $name = preg_replace('/[^a-zA-Z0-9\_]/', '', $name);
                    if (array_key_exists($this->cookie_prefix . '_' . $name, $_COOKIE)) {
                        $value = $_COOKIE[$this->cookie_prefix . '_' . $name];
                        if ($decode) {
                            $value = $this->decodeCookie($value);
                        }
                        if (empty($value) or is_numeric($value)) {
                            return $value;
                        }

                        return ($filter == true) ? $this->security_cookie($value) : $value;
                    }
                    break;
                case 'session':
                    $name = preg_replace('/[^a-zA-Z0-9\_]/', '', $name);
                    if (array_key_exists($this->session_prefix . '_' . $name, $_SESSION)) {
                        $value = $_SESSION[$this->session_prefix . '_' . $name];
                        if (empty($value) or is_numeric($value)) {
                            return $value;
                        }

                        return ($filter == true) ? $this->security_session($value) : $value;
                    }
                    break;
                case 'request':
                    if (array_key_exists($name, $_POST)) {
                        $value = $_POST[$name];
                        if (empty($value) or is_numeric($value)) {
                            return $value;
                        }
                        $value = self::compound_unicode($value);
                        return ($filter == true) ? $this->security_post($value) : $value;
                    }
                    if (array_key_exists($name, $_GET)) {
                        $value = $_GET[$name];
                        if (empty($value) or is_numeric($value)) {
                            return $value;
                        }
                        $value = self::compound_unicode($value);
                        return ($filter == true) ? $this->security_get($value) : $value;
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
     * set_Cookie()
     *
     * @param string           $name
     * @param array|int|string $value
     * @param int              $expire
     * @param bool             $encode
     * @return bool
     */
    public function set_Cookie($name, $value = '', $expire = 0, $encode = true)
    {
        if (!is_string($value) and !is_numeric($value) and !is_array($value)) {
            return false;
        }
        $name = preg_replace('/[^a-zA-Z0-9\_]/', '', $name);
        if (empty($name)) {
            return false;
        }
        $name = $this->cookie_prefix . '_' . $name;
        if ($encode) {
            $value = $this->encodeCookie($value);
        }
        $expire = (int) $expire;
        if (!empty($expire)) {
            $expire += NV_CURRENTTIME;
        }

        if ($this->set_cookie_by_options) {
            $options = [
                'expires' => $expire,
                'path' => $this->cookie_path,
                'domain' => $this->cookie_domain,
                'secure' => $this->secure,
                'httponly' => $this->httponly
            ];
            if (!empty($this->SameSite) and (in_array($this->SameSite, [
                'Lax',
                'Strict'
            ], true) or ($this->SameSite == 'None' and !empty($this->secure)))) {
                $options['samesite'] = $this->SameSite;
            }

            return setcookie($name, $value, $options);
        }

        return setcookie($name, $value, $expire, $this->cookie_path, $this->cookie_domain, $this->secure, $this->httponly);
    }

    /**
     * set_Session()
     *
     * @param string           $name
     * @param array|int|string $value
     * @return bool
     */
    public function set_Session($name, $value = '')
    {
        if (!is_string($value) and !is_numeric($value) and !is_array($value)) {
            return false;
        }
        $name = preg_replace('/[^a-zA-Z0-9\_]/', '', $name);
        if (empty($name)) {
            return false;
        }
        $name = $this->session_prefix . '_' . $name;
        $_SESSION[$name] = $value;

        return true;
    }

    /**
     * unset_request()
     *
     * @param string $names
     * @param string $mode
     * @return array|false|void
     */
    public function unset_request($names, $mode)
    {
        if (empty($names)) {
            return [];
        }
        $names = ',' . $names;
        unset($matches);
        preg_match_all("/\,\s*([a-zA-Z\_]{1}[a-zA-Z0-9\-\_]*)/", $names, $matches);
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
                    $name = preg_replace('/[^a-zA-Z0-9\_]/', '', $name);
                    if (empty($name)) {
                        continue;
                    }
                    $name2 = $this->cookie_prefix . '_' . $name;
                    if (!isset($_COOKIE[$name2])) {
                        continue;
                    }
                    $expire = NV_CURRENTTIME - 3600;

                    setcookie($name2, '', $expire, $this->cookie_path, $this->cookie_domain, $this->secure, $this->httponly);

                    unset($_COOKIE[$name2]);
                }
            } elseif ($arr == 'session') {
                foreach ($names as $name) {
                    $name = preg_replace('/[^a-zA-Z0-9\_]/', '', $name);
                    if (empty($name)) {
                        continue;
                    }
                    $name2 = $this->session_prefix . '_' . $name;
                    if (!isset($_SESSION[$name2])) {
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
     * isset_request()
     *
     * @param string $names
     * @param string $mode
     * @param bool   $all
     * @return array|bool
     */
    public function isset_request($names, $mode, $all = true)
    {
        if (empty($names)) {
            return [];
        }
        $names = ',' . $names;
        unset($matches);
        preg_match_all("/\,\s*([a-zA-Z\_]{1}[a-zA-Z0-9\-\_]*)/", $names, $matches);
        $names = $matches[1];
        if (empty($names)) {
            return false;
        }
        $names = array_flip($names);
        $mode = $this->parse_mode($mode);
        foreach ($mode as $arr) {
            $array_keys = array_keys($names);
            foreach ($array_keys as $name) {
                $name2 = preg_replace('/[^a-zA-Z0-9\_]/', '', $name);
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
                } elseif ($arr == 'cookie' and isset($_COOKIE[$this->cookie_prefix . '_' . $name2])) {
                    if (empty($all)) {
                        return true;
                    }
                    unset($names[$name]);
                } elseif ($arr == 'session' and isset($_SESSION[$this->session_prefix . '_' . $name2])) {
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

        return !(!empty($names));
    }

    /**
     * get_bool()
     *
     * @param string      $name
     * @param string|null $mode
     * @param mixed|null  $default
     * @param bool        $decode
     * @param bool        $filter
     * @return bool
     */
    public function get_bool($name, $mode = null, $default = null, $decode = true, $filter = true)
    {
        return (bool) $this->get_value($name, $mode, $default, $decode, $filter);
    }

    /**
     * get_int()
     *
     * @param string      $name
     * @param string|null $mode
     * @param mixed|null  $default
     * @param bool        $decode
     * @param bool        $filter
     * @return int
     */
    public function get_int($name, $mode = null, $default = null, $decode = true, $filter = true)
    {
        return (int) $this->get_value($name, $mode, $default, $decode, $filter);
    }

    /**
     * get_absint()
     *
     * @since 4.3.08
     *
     * @param string $name
     * @param string $mode
     * @param int    $default
     * @param bool   $decode
     * @param bool   $filter
     * @return int
     */
    public function get_absint($name, $mode = null, $default = null, $decode = true, $filter = true)
    {
        return abs((int) ($this->get_value($name, $mode, $default, $decode, $filter)));
    }

    /**
     * get_page()
     *
     * @param string $name
     * @param string|null $mode
     * @param mixed|null $default
     * @return int
     */
    public function get_page($name, $mode = null, $default = 1, $max = 99999999)
    {
        $page = (int) $this->get_value($name, $mode, $default);
        if ($page > $max or $page < 1) {
            $page = 1;
        }
        return $page;
    }

    /**
     * get_float()
     *
     * @param string      $name
     * @param string|null $mode
     * @param mixed|null  $default
     * @param bool        $decode
     * @param bool        $filter
     * @return float
     */
    public function get_float($name, $mode = null, $default = null, $decode = true, $filter = true)
    {
        return (float) $this->get_value($name, $mode, $default, $decode, $filter);
    }

    /**
     * get_string()
     *
     * @param string      $name
     * @param string|null $mode
     * @param mixed|null  $default
     * @param bool        $decode
     * @param bool        $filter
     * @return string
     */
    public function get_string($name, $mode = null, $default = null, $decode = true, $filter = true)
    {
        return (string) $this->get_value($name, $mode, $default, $decode, $filter);
    }

    /**
     * _get_title()
     *
     * @param string $value
     * @param bool   $specialchars
     * @param array  $preg_replace
     * @return string
     */
    private function _get_title($value, $specialchars, $preg_replace)
    {
        $value = strip_tags($value);
        if ((bool) $specialchars == true) {
            $search = ['&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '%', '^', ':', '{', '}', '`', '~'];
            $replace = ['&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;'];

            $value = str_replace($replace, $search, $value);
            $value = str_replace('&#x23;', '#', $value);
            $value = str_replace($search, $replace, $value);
            $value = preg_replace("/([^\&]+)\#/", '\\1&#x23;', $value);
        }

        if (!empty($preg_replace)) {
            if (isset($preg_replace['pattern']) and !empty($preg_replace['pattern']) and isset($preg_replace['replacement'])) {
                $value = preg_replace($preg_replace['pattern'], $preg_replace['replacement'], $value);
            }
        }

        return trim($value);
    }

    /**
     * get_title()
     *
     * @param string      $name
     * @param string|null $mode
     * @param mixed|null  $default
     * @param bool        $specialchars
     * @param array       $preg_replace
     * @param bool        $filter
     * @return string
     */
    public function get_title($name, $mode = null, $default = null, $specialchars = false, $preg_replace = [], $filter = true)
    {
        $value = (string) $this->get_value($name, $mode, $default, true, $filter);

        return $this->_get_title($value, $specialchars, $preg_replace);
    }

    /**
     * _get_editor()
     *
     * @param string $value
     * @param string $allowed_html_tags
     * @return string
     */
    private function _get_editor($value, $allowed_html_tags)
    {
        if (!empty($allowed_html_tags)) {
            $allowed_html_tags = array_map('trim', explode(',', $allowed_html_tags));
            $allowed_html_tags = '<' . implode('><', $allowed_html_tags) . '>';
            $value = strip_tags($value, $allowed_html_tags);
        }

        return trim($value);
    }

    /**
     * get_editor()
     *
     * @param string $name
     * @param string $default
     * @param string $allowed_html_tags
     * @param bool   $filter
     * @return string
     */
    public function get_editor($name, $default = '', $allowed_html_tags = '', $filter = true)
    {
        $value = (string) $this->get_value($name, 'post', $default, true, $filter);

        return $this->_get_editor($value, $allowed_html_tags);
    }

    /**
     * _get_textarea()
     *
     * @param string $value
     * @param string $allowed_html_tags
     * @param bool   $save
     * @return string
     */
    private function _get_textarea($value, $allowed_html_tags, $save)
    {
        if (!empty($allowed_html_tags)) {
            $allowed_html_tags = array_map('trim', explode(',', $allowed_html_tags));
            $allowed_html_tags = '<' . implode('><', $allowed_html_tags) . '>';
            $value = strip_tags($value, $allowed_html_tags);
        }
        if ((bool) $save) {
            $value = strtr($value, [
                "\r\n" => '<br />',
                "\r" => '<br />',
                "\n" => '<br />'
            ]);
        }

        return trim($value);
    }

    /**
     * get_textarea()
     *
     * @param string $name
     * @param string $default
     * @param string $allowed_html_tags
     * @param bool   $save
     * @param bool   $filter
     * @return string
     */
    public function get_textarea($name, $default = '', $allowed_html_tags = '', $save = false, $filter = true)
    {
        $value = (string) $this->get_value($name, 'post', $default, true, $filter);

        return $this->_get_textarea($value, $allowed_html_tags, $save);
    }

    /**
     * get_array()
     *
     * @param string      $name
     * @param string|null $mode
     * @param mixed|null  $default
     * @param bool        $decode
     * @param bool        $filter
     * @return array
     */
    public function get_array($name, $mode = null, $default = null, $decode = true, $filter = true)
    {
        return (array) $this->get_value($name, $mode, $default, $decode, $filter);
    }

    /**
     * get_typed_array()
     *
     * @param string      $name
     * @param string|null $mode
     * @param string|null $type
     * @param mixed|null  $default
     * @param bool        $specialchars
     * @param array       $preg_replace
     * @param string      $allowed_html_tags
     * @param bool        $save
     * @param bool        $filter
     * @return array
     */
    public function get_typed_array($name, $mode = null, $type = null, $default = null, $specialchars = false, $preg_replace = [], $allowed_html_tags = '', $save = false, $filter = true)
    {
        $arr = $this->get_array($name, $mode, $default, true, $filter);
        $array_keys = array_keys($arr);
        foreach ($array_keys as $key) {
            switch ($type) {
                case 'bool':
                    $arr[$key] = (bool) $arr[$key];
                    break;
                case 'int':
                    $arr[$key] = (int) $arr[$key];
                    break;
                case 'float':
                    $arr[$key] = (float) $arr[$key];
                    break;
                case 'string':
                    $arr[$key] = (string) $arr[$key];
                    break;
                case 'array':
                    $arr[$key] = (array) $arr[$key];
                    break;
                case 'title':
                    $arr[$key] = (string) $this->_get_title($arr[$key], $specialchars, $preg_replace);
                    break;
                case 'textarea':
                    $arr[$key] = (string) $this->_get_textarea($arr[$key], $allowed_html_tags, $save);
                    break;
                case 'editor':
                    $arr[$key] = (string) $this->_get_editor($arr[$key], $allowed_html_tags);
            }
        }

        return $arr;
    }

    /**
     * getAllowOriginHeaderValue()
     *
     * @return mixed
     */
    private function getAllowOriginHeaderValue()
    {
        // Không block hoặc domain hợp lệ (domain trong danh sách hoặc là self) hoặc null và
        if (
            !$this->restrictCrossDomain or
            $this->origin_key === 1 or
            ($this->origin === 'null' and $this->allowNullOrigin and (
                empty($this->allowNullOriginIps) or in_array($this->remote_ip, $this->allowNullOriginIps, true)
            )) or
            in_array($this->origin, $this->validCrossDomains, true)
        ) {
            $this->isOriginValid = true;

            return $this->origin;
        }

        $this->isOriginValid = false;

        return $this->my_current_domain;
    }

    /**
     * sessionRegenerateId()
     *
     * @param bool $saveOldSession
     */
    public function sessionRegenerateId($saveOldSession = false)
    {
        $saveOldSession && $old_session = $_SESSION;
        $new_sessid = function_exists('session_create_id') ? session_create_id() : substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);
        $_SESSION['deleted_time'] = NV_CURRENTTIME;
        session_commit();
        ini_set('session.use_strict_mode', 0);
        session_id($new_sessid);
        session_start();
        $saveOldSession && $_SESSION = $old_session;
        $this->session_id = $new_sessid;
    }

    /**
     * Hàm chuyển Unicode tổ hợp sang Unicode dựng sẵn
     * @param mixed $value
     * @return mixed
     */
    public static function compound_unicode($value)
    {
        if (is_array($value)) {
            $keys = array_keys($value);
            foreach ($keys as $key) {
                $value[$key] = self::compound_unicode($value[$key]);
            }
        } elseif (is_string($value)) {
            // Sử dụng Normalizer nếu extension intl được cài đặt
            if (class_exists('Normalizer')) {
                $value = \Normalizer::normalize($value, \Normalizer::FORM_C);
            } else {
                // Fallback cho trường hợp không có intl
                $map = [
                    'ả' => 'ả', 'á' => 'á', 'à' => 'à', 'ạ' => 'ạ', 'ã' => 'ã',
                    'ẳ' => 'ẳ', 'ắ' => 'ắ', 'ằ' => 'ằ', 'ặ' => 'ặ', 'ẵ' => 'ẵ',
                    'ẩ' => 'ẩ', 'ấ' => 'ấ', 'ầ' => 'ầ', 'ậ' => 'ậ', 'ẫ' => 'ẫ',
                    'Ả' => 'Ả', 'Á' => 'Á', 'À' => 'À', 'Ạ' => 'Ạ', 'Ã' => 'Ã',
                    'Ẳ' => 'Ẳ', 'Ắ' => 'Ắ', 'Ằ' => 'Ằ', 'Ặ' => 'Ặ', 'Ẵ' => 'Ẵ',
                    'Ẩ' => 'Ẩ', 'Ấ' => 'Ấ', 'Ầ' => 'Ầ', 'Ậ' => 'Ậ', 'Ẫ' => 'Ẫ',
                    'ậ' => 'ậ', 'â' => 'â',

                    'ẻ' => 'ẻ', 'é' => 'é', 'è' => 'è', 'ẹ' => 'ẹ', 'ẽ' => 'ẽ',
                    'ể' => 'ể', 'ế' => 'ế', 'ề' => 'ề', 'ệ' => 'ệ', 'ễ' => 'ễ',
                    'Ẻ' => 'Ẻ', 'É' => 'É', 'È' => 'Ề', 'Ẹ' => 'Ẹ', 'Ẽ' => 'Ẽ',
                    'Ể' => 'Ể', 'Ế' => 'Ế', 'Ề' => 'Ề', 'Ệ' => 'Ệ', 'Ễ' => 'Ễ',
                    'ệ' => 'ệ',

                    'ỉ' => 'ỉ', 'í' => 'í', 'ì' => 'ì', 'ị' => 'ị', 'ĩ' => 'ĩ',
                    'Ỉ' => 'Ỉ', 'Í' => 'Í', 'Ì' => 'Ì', 'Ị' => 'Ị', 'Ĩ' => 'Ĩ',

                    'ỏ' => 'ỏ', 'ó' => 'ó', 'ò' => 'ò', 'ọ' => 'ọ', 'õ' => 'õ',
                    'ở' => 'ở', 'ớ' => 'ớ', 'ờ' => 'ờ', 'ợ' => 'ợ', 'ỡ' => 'ỡ',
                    'ổ' => 'ổ', 'ố' => 'ố', 'ồ' => 'ồ', 'ộ' => 'ộ', 'ỗ' => 'ỗ',
                    'Ỏ' => 'Ò', 'Ó' => 'Ó', 'Ò' => 'Ồ', 'Ọ' => 'Ọ', 'Õ' => 'Õ',
                    'Ở' => 'Ở', 'Ớ' => 'Ớ', 'Ờ' => 'Ờ', 'Ợ' => 'Ợ', 'Ỡ' => 'Ỡ',
                    'Ổ' => 'Ổ', 'Ố' => 'Ố', 'Ồ' => 'Ồ', 'Ộ' => 'Ộ', 'Ỗ' => 'Ỗ',
                    'ô' => 'ô',

                    'ủ' => 'ủ', 'ú' => 'ú', 'ù' => 'ù', 'ụ' => 'ụ', 'ũ' => 'ũ',
                    'ử' => 'ử', 'ứ' => 'ứ', 'ừ' => 'ừ', 'ự' => 'ự', 'ữ' => 'ữ',
                    'Ủ' => 'Ủ', 'Ú' => 'Ú', 'Ù' => 'Ù', 'Ụ' => 'Ụ', 'Ũ' => 'Ũ',
                    'Ử' => 'Ủ', 'Ứ' => 'Ứ', 'Ừ' => 'Ừ', 'Ự' => 'Ự', 'Ữ' => 'Ữ',

                    'ỷ' => 'ỷ', 'ý' => 'ý', 'ỳ' => 'ỳ', 'ỵ' => 'ỵ', 'ỹ' => 'ỹ',
                    'Ỷ' => 'Ỷ', 'Ý' => 'Ý', 'Ỳ' => 'Ỳ', 'Ỵ' => 'Ỵ', 'Ỹ' => 'Ỹ'
                ];

                $value = strtr($value, $map);
            }
        }

        return $value;
    }
}
