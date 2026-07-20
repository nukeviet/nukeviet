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
     * Cổng của các dịch vụ thông dụng không phải web, không được phép xuất hiện trong URL
     * của site. Xem standardizePort().
     *
     * FTP, SSH, Telnet, SMTP, DNS, POP3, IMAP, LDAP, SMB, SMTPS, SMTP submission, LDAPS,
     * IMAPS, POP3S, MSSQL, Oracle, MySQL, RDP, PostgreSQL, VNC, Redis, Elasticsearch,
     * Memcached, MongoDB
     */
    public const BLOCKED_PORTS = [21, 22, 23, 25, 53, 110, 143, 389, 445, 465, 587, 636, 993, 995, 1433, 1521, 3306, 3389, 5432, 5900, 6379, 9200, 11211, 27017];

    /**
     * Host đã được chuẩn hóa từ môi trường thực thi PHP ví dụ domain.com hoặc [ip]
     * Không có giao thức, không có dấu / ở cuối.
     *
     * @var string
     */
    protected $server_host = '';

    /**
     * Giao thức đã được chuẩn hóa từ môi trường thực thi PHP. Luôn là 'http' hoặc 'https'.
     *
     * @var string
     */
    protected $server_protocol = '';

    /**
     * Cổng đã được chuẩn hóa từ môi trường thực thi PHP. Rỗng nếu là 80 hoặc 443 hoặc có dạng :PORT
     *
     * @var string
     */
    protected $server_port = '';

    /**
     * Domain đã được chuẩn hóa từ môi trường thực thi PHP có dạng protocol://host[:port]
     * Phần [:port] chỉ xuất hiện nếu cổng khác 80 hoặc 443.
     *
     * @var string
     *
     */
    protected $server_domain = '';

    /**
     * Giá trị phía client gửi lên từ header X-Forwarded-Host nếu có, mặc định nó bằng server_host
     *
     * @var string
     */
    protected $original_host = '';

    /**
     * Giao thức phía client gửi lên từ header:
     * - CF-Visitor (Cloudflare)
     * - X-Forwarded-Proto (reverse proxy)
     * Mặc định nó bằng server_protocol
     *
     * @var string
     */
    protected $original_protocol = '';

    /**
     * Cổng phía client gửi lên từ header X-Forwarded-Port nếu có, mặc định nó bằng server_port
     *
     * @var string
     */
    protected $original_port = '';

    /**
     * Domain phía client gửi lên ghép từ original_protocol, original_host và original_port.
     * Mặc định nó bằng server_domain
     *
     * @var string
     */
    protected $original_domain = '';

    /**
     * Đường dẫn đến thư mục chứa site tính từ thư mục gốc của domain đến thư mục có file index.php
     *
     * @var string
     */
    protected $sitePath = '';

    /**
     * __construct()
     */
    public function __construct()
    {
        // Xác định host của máy chủ website
        $this->server_host = $this->original_host = $this->standardizeHost((string) $this->getEnv(['HTTP_HOST', 'SERVER_NAME', 'Host']));

        // Xác định giao thức máy chủ website, luôn ép về đúng 'http' hoặc 'https'.
        $is_https = (isset($_SERVER['HTTPS']) and in_array(strtolower((string) $_SERVER['HTTPS']), ['on', '1'], true));
        if (!$is_https and isset($_SERVER['SERVER_PORT']) and $_SERVER['SERVER_PORT'] == '443') {
            $is_https = true;
        }
        $this->server_protocol = $is_https ? 'https' : 'http';
        $this->original_protocol = $this->server_protocol;

        // Xác định cổng máy chủ website
        $this->server_port = $this->original_port = $this->standardizePort($_SERVER['SERVER_PORT'] ?? '');

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
        $original_protocol = false;
        if (isset($_SERVER['HTTP_CF_VISITOR'])) {
            // Cloudflare thông báo giao thức của client ví dụ CF-Visitor: {"scheme":"https"}
            $cf_visitor = json_decode($_SERVER['HTTP_CF_VISITOR'], true);
            !empty($cf_visitor['scheme']) && $original_protocol = $cf_visitor['scheme'];
        }
        if (empty($original_protocol)) {
            // Reverse proxy thông báo giao thức của client ví dụ X-Forwarded-Proto: https
            $original_protocol = $this->getEnv(['HTTP_X_FORWARDED_PROTO', 'X-Forwarded-Proto']);
        }
        $original_port = $this->getEnv(['HTTP_X_FORWARDED_PORT', 'X-Forwarded-Port']);

        // Xử lý nếu có Reverse proxy, Cloudflare hoặc load balancer
        if ($original_host !== false or $original_protocol !== false) {
            if ($original_host !== false) {
                $this->original_host = $this->standardizeHost($original_host);
            }
            if ($original_protocol !== false) {
                $original_protocol = strtolower($original_protocol);
                $this->original_protocol = in_array($original_protocol, ['http', 'https'], true) ? $original_protocol : $this->server_protocol;
            }
            if ($original_port !== false) {
                $this->original_port = $this->standardizePort($original_port);
            }

            if (filter_var($this->original_host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                $this->original_domain = $this->original_protocol . '://' . $this->original_host . $this->original_port;
            } else {
                $this->original_domain = $this->original_protocol . '://[' . $this->original_host . ']' . $this->original_port;
            }
        }

        /*
         * Chuẩn hóa $_SERVER['SERVER_NAME'] và $_SERVER['HTTPS'] theo giá trị phía client.
         * Phòng trường hợp code những chỗ dùng hai biến này có thể sai
         * (ví dụ phía client dùng https nhưng server lại nhận http, hoặc proxy ghi đè Host
         * thành tên nội bộ khiến domain cookie và địa chỉ gửi mail bị sai).
         *
         * Theo CGI spec, SERVER_NAME là host mà client nhắm tới chứ không phải host của
         * backend, nên gán giá trị phía client vào đây là đúng ngữ nghĩa.
         */
        $_SERVER['SERVER_NAME'] = $this->original_host;
        if ($this->original_protocol === 'https') {
            $_SERVER['HTTPS'] = 'on';
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
     * @param string|array $key
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
     * Chuẩn hóa cổng website: Phải là số hợp lệ và loại trừ các cổng thông dụng
     * đã biết của các dịch vụ khác.
     *
     * @param mixed $port
     * @return string Rỗng hoặc có dạng :PORT
     */
    protected function standardizePort($port)
    {
        $port = ctype_digit((string) $port) ? (int) $port : 0;

        if ($port < 1 or $port > 65535 or $port === 80 or $port === 443) {
            return '';
        }

        return in_array($port, self::BLOCKED_PORTS, true) ? '' : (':' . $port);
    }

    /**
     * standardizeHost()
     *
     * @param string $host
     * @return string
     */
    protected function standardizeHost($host)
    {
        $host = trim($host);

        if ($host === '') {
            return '';
        }

        $host = (strpos($host, '://') !== false) ? $host : '//' . $host;
        $host = parse_url($host, PHP_URL_HOST);

        if (!is_string($host) || $host === '') {
            return '';
        }

        return rtrim(strtolower($host), '.');
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
