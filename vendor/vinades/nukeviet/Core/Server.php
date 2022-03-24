<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

/**
 * NukeViet\Core\Server
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Server
{
    /**
     * @var string
     *             Host của máy chủ website (máy chủ thật)
     */
    protected $server_host = '';

    /**
     * @var string
     *             Giao thức của máy chủ website (máy chủ thật)
     */
    protected $server_protocol = '';

    /**
     * @var string
     *             Cổng của máy chủ website (máy chủ thật)
     *             Rỗng nếu là 80 hoặc 443 hoặc có dạng :PORT
     */
    protected $server_port = '';

    /**
     * @var string
     *             Domain chạy thật của máy chủ có dạng protocol://host[:port]
     */
    protected $server_domain = '';

    /**
     * @var string
     */
    protected $original_host = '';

    /**
     * @var string
     */
    protected $original_protocol = '';

    /**
     * @var string
     */
    protected $original_port = '';

    /**
     * @var string
     */
    protected $original_domain = '';

    /**
     * @var string
     *             Đường dẫn đến thư mục chứa site tính từ thư mục gốc của domain đến thư mục có file index.php
     */
    protected $sitePath = '';

    /**
     * __construct()
     */
    public function __construct()
    {
        // Xác định host của máy chủ website
        $this->server_host = $this->original_host = $this->standardizeHost((string) $this->getEnv(['HTTP_HOST', 'SERVER_NAME', 'Host']));
        $_SERVER['SERVER_NAME'] = $this->server_host;

        // Xác định giao thức máy chủ website
        $this->server_protocol = strtolower(preg_replace('/^([^\/]+)\/*(.*)$/', '\\1', $_SERVER['SERVER_PROTOCOL']));
        if (isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) == 'on') {
            $this->server_protocol .= 's';
        }
        $this->original_protocol = $this->server_protocol;

        // Xác định cổng máy chủ website
        $this->server_port = $this->original_port = ($_SERVER['SERVER_PORT'] == '80' or $_SERVER['SERVER_PORT'] == '443') ? '' : (':' . $_SERVER['SERVER_PORT']);

        // Xác định domain chạy thật của máy chủ
        if (filter_var($this->server_host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            $this->server_domain = $this->server_protocol . '://' . $this->server_host . $this->server_port;
        } else {
            $this->server_domain = $this->server_protocol . '://[' . $this->server_host . ']' . $this->server_port;
        }
        $this->original_domain = $this->server_domain;

        /*
         * Xác định lại host, port, protocol phía client nếu có Forwarded
         * Ví dụ Server thiết lập HTTP proxy, load balancer
         */
        $original_host = $this->getEnv(['HTTP_X_FORWARDED_HOST', 'X-Forwarded-Host']);
        $original_protocol = $this->getEnv(['HTTP_X_FORWARDED_PROTO', 'X-Forwarded-Proto']);
        $original_port = $this->getEnv(['HTTP_X_FORWARDED_PORT', 'X-Forwarded-Port']);
        // Nếu có FORWARDED_HOST hoặc FORWARDED_PROTO
        if ($original_host !== false or $original_protocol !== false) {
            if ($original_host !== false) {
                $this->original_host = $this->standardizeHost($original_host);
            }
            if ($original_protocol !== false) {
                $this->original_protocol = strtolower($original_protocol);
            }
            if ($original_port !== false) {
                if ($original_port != 80 and $original_port != 443) {
                    $original_port = ':' . $original_port;
                }
                $this->original_port = $original_port;
            }

            if (filter_var($this->original_host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                $this->original_domain = $this->original_protocol . '://' . $this->original_host . $this->original_port;
            } else {
                $this->original_domain = $this->original_protocol . '://[' . $this->original_host . ']' . $this->original_port;
            }
        }

        // Xác định đường dẫn đến thư mục chứa site
        $site_path = pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
        if ($site_path == DIRECTORY_SEPARATOR) {
            $site_path = '';
        }
        if (!empty($site_path)) {
            $site_path = str_replace(DIRECTORY_SEPARATOR, '/', $site_path);
        }
        if (!empty($site_path)) {
            $site_path = preg_replace('/[\/]+$/', '', $site_path);
        }
        if (!empty($site_path)) {
            $site_path = preg_replace('/^[\/]*(.*)$/', '/\\1', $site_path);
        }
        if (defined('NV_WYSIWYG') and !defined('NV_ADMIN')) {
            $site_path = preg_replace('/\/' . NV_EDITORSDIR . '(.*)$/i', '', $site_path);
        } elseif (defined('NV_IS_UPDATE') or defined('NV_IS_INSTALL')) {
            $site_path = preg_replace('/\/install(\/(index|update)\.php.*)*$/i', '', $site_path);
        } elseif (defined('NV_ADMIN')) {
            $site_path = preg_replace('/\/' . NV_ADMINDIR . '(\/index\.php.*)*$/i', '', $site_path);
        } elseif (!empty($site_path)) {
            $site_path = preg_replace('/\/index\.php(.*)$/', '', $site_path);
        }

        $this->sitePath = $site_path;
    }

    /**
     * getEnv()
     *
     * @param string $key
     * @return string
     */
    protected function getEnv($key)
    {
        if (!is_array($key)) {
            $key = [$key];
        }
        foreach ($key as $k) {
            if (isset($_SERVER[$k])) {
                return $_SERVER[$k];
            }
            if (isset($_ENV[$k])) {
                return $_ENV[$k];
            }
            if (@getenv($k)) {
                return @getenv($k);
            }
            if (function_exists('apache_getenv') and apache_getenv($k, true)) {
                return apache_getenv($k, true);
            }
        }

        return false;
    }

    /**
     * standardizeHost()
     *
     * @param string $host
     * @return string
     */
    protected function standardizeHost($host)
    {
        return preg_replace('/(\:[0-9]+)$/', '', preg_replace('/^[a-z]+\:\/\//i', '', trim($host)));
    }

    /**
     * getServerHost()
     *
     * @return string
     */
    public function getServerHost()
    {
        return $this->server_host;
    }

    /**
     * getServerPort()
     *
     * @return string
     */
    public function getServerPort()
    {
        return $this->server_port;
    }

    /**
     * getServerProtocol()
     *
     * @return string
     */
    public function getServerProtocol()
    {
        return $this->server_protocol;
    }

    /**
     * getServerDomain()
     *
     * @return string
     */
    public function getServerDomain()
    {
        return $this->server_domain;
    }

    /**
     * getOriginalHost()
     *
     * @return string
     */
    public function getOriginalHost()
    {
        return $this->original_host;
    }

    /**
     * getOriginalPort()
     *
     * @return string
     */
    public function getOriginalPort()
    {
        return $this->original_port;
    }

    /**
     * getOriginalProtocol()
     *
     * @return string
     */
    public function getOriginalProtocol()
    {
        return $this->original_protocol;
    }

    /**
     * getOriginalDomain()
     *
     * @return string
     */
    public function getOriginalDomain()
    {
        return $this->original_domain;
    }

    /**
     * getWebsitePath()
     *
     * @return string
     */
    public function getWebsitePath()
    {
        return $this->sitePath;
    }
}
