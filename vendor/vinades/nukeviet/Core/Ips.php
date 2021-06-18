<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

namespace NukeViet\Core;

class Ips
{
    public $client_ip;

    public $forward_ip;

    public $remote_addr;

    public $remote_ip;

    public $is_proxy = 0;

    private $ip6_support = false;

    /**
     * @param array $sys
     */
    public function __construct($sys = [])
    {
        $this->client_ip = trim($this->nv_get_clientip());
        $this->forward_ip = trim($this->nv_get_forwardip());
        $this->remote_addr = trim($this->nv_get_remote_addr());
        $this->remote_ip = trim($this->nv_getip());

        $this->ip6_support = (bool)$sys['ip6_support'];
    }

    /**
     * ips::nv_getenv()
     *
     * @param mixed $key
     * @return
     *
     */
    private function nv_getenv($key)
    {
        if (isset($_SERVER[$key])) {
            if (strpos($_SERVER[$key], ',')) {
                $_arr = explode(',', $_SERVER[$key]);
                return trim($_arr[0]);
            } else {
                return $_SERVER[$key];
            }
        } elseif (isset($_ENV[$key])) {
            return $_ENV[$key];
        } elseif (@getenv($key)) {
            return @getenv($key);
        } elseif (function_exists('apache_getenv') and apache_getenv($key, true)) {
            return apache_getenv($key, true);
        }
        return '';
    }

    /**
     * ips::nv_validip()
     *
     * @param mixed $ip
     * @return
     *
     */
    public function nv_validip($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /**
     * @param string $ip
     * @return mixed
     */
    public function isIp4($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    /**
     * @param string $ip
     * @return mixed
     */
    public function isIp6($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    /**
     * ips::server_ip()
     *
     * @return
     *
     */
    public function server_ip()
    {
        $serverip = $this->nv_getenv('SERVER_ADDR');
        if ($this->nv_validip($serverip)) {
            return $serverip;
        } elseif ($_SERVER['SERVER_NAME'] == 'localhost') {
            return '127.0.0.1';
        } elseif (function_exists('gethostbyname')) {
            return gethostbyname($_SERVER['SERVER_NAME']);
        }
        return 'none';
    }

    /**
     * ips::nv_get_clientip()
     * IP có thể gửi qua HTTP Request Header
     *
     * @return
     *
     */
    private function nv_get_clientip()
    {
        $clientip = '';
        if ($this->nv_getenv('HTTP_CLIENT_IP')) {
            $clientip = $this->nv_getenv('HTTP_CLIENT_IP');
        } elseif ($this->nv_getenv('HTTP_VIA')) {
            $clientip = $this->nv_getenv('HTTP_VIA');
        } elseif ($this->nv_getenv('HTTP_X_COMING_FROM')) {
            $clientip = $this->nv_getenv('HTTP_X_COMING_FROM');
        } elseif ($this->nv_getenv('HTTP_COMING_FROM')) {
            $clientip = $this->nv_getenv('HTTP_COMING_FROM');
        }

        if ($this->nv_validip($clientip)) {
            return $clientip;
        } else {
            return 'none';
        }
    }

    /**
     * ips::nv_get_forwardip()
     * IP client được chuyển tiếp qua máy chủ Proxy
     *
     * @return
     *
     */
    private function nv_get_forwardip()
    {
        if ($this->nv_getenv('HTTP_X_FORWARDED_FOR') and $this->nv_validip($this->nv_getenv('HTTP_X_FORWARDED_FOR'))) {
            return $this->nv_getenv('HTTP_X_FORWARDED_FOR');
        } elseif ($this->nv_getenv('HTTP_X_FORWARDED') and $this->nv_validip($this->nv_getenv('HTTP_X_FORWARDED'))) {
            return $this->nv_getenv('HTTP_X_FORWARDED');
        } elseif ($this->nv_getenv('HTTP_FORWARDED_FOR') and $this->nv_validip($this->nv_getenv('HTTP_FORWARDED_FOR'))) {
            return $this->nv_getenv('HTTP_FORWARDED_FOR');
        } elseif ($this->nv_getenv('HTTP_FORWARDED') and $this->nv_validip($this->nv_getenv('HTTP_FORWARDED'))) {
            return $this->nv_getenv('HTTP_FORWARDED');
        } else {
            return 'none';
        }
    }

    /**
     * ips::nv_get_remote_addr()
     * Địa chỉ IP người dùng đang truy cập do máy chủ cung cấp
     *
     * @return
     *
     */
    private function nv_get_remote_addr()
    {
        if ($this->nv_getenv('REMOTE_ADDR') and $this->nv_validip($this->nv_getenv('REMOTE_ADDR'))) {
            return $this->nv_getenv('REMOTE_ADDR');
        }
        return 'none';
    }

    /**
     * ips::nv_getip()
     *
     * @return
     *
     */
    private function nv_getip()
    {
        if ($this->client_ip != 'none') {
            return $this->client_ip;
        }
        if ($this->forward_ip != 'none') {
            return $this->forward_ip;
        }
        if ($this->remote_addr != 'none') {
            return $this->remote_addr;
        }

        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            return '127.0.0.1';
        }
        return 'none';
    }

    /**
     * ips::nv_check_proxy()
     *
     * @return
     *
     */
    public function nv_check_proxy()
    {
        $proxy = 'No';
        if ($this->client_ip != 'none' or $this->forward_ip != 'none') {
            $proxy = 'Lite';
        }
        $host = @getHostByAddr($this->remote_ip);
        if (stristr($host, 'proxy')) {
            $proxy = 'Mild';
        }
        if ($this->remote_ip == $host) {
            $proxy = 'Strong';
        }
        return $proxy;
    }

    /**
     * Kiểm tra xem địa chỉ IP $requestIp có nằm trong dải $ip hoặc bằng với $ip không
     *
     * @param string $requestIp
     * @param string $ip
     * @return integer|boolean -1 false true
     */
    public function checkIp6($requestIp, $ip) {
        if (!$this->ip6_support) {
            // Không hỗ trợ xử lý IPv6 trả về -1
            return -1;
        }

        if (strpos($ip, '/') !== false) {
            list($address, $netmask) = explode('/', $ip, 2);

            if ($netmask === '0') {
                return (bool) unpack('n*', inet_pton($address));
            }

            if ($netmask < 1 or $netmask > 128) {
                return false;
            }
        } else {
            $address = $ip;
            $netmask = 128;
        }

        $bytesAddr = unpack('n*', inet_pton($address));
        $bytesTest = unpack('n*', inet_pton($requestIp));

        if (!$bytesAddr or !$bytesTest) {
            return false;
        }

        for ($i = 1, $ceil = ceil($netmask / 16); $i <= $ceil; ++$i) {
            $left = $netmask - 16 * ($i - 1);
            $left = ($left <= 16) ? $left : 16;
            $mask = ~(0xffff >> $left) & 0xffff;
            if (($bytesAddr[$i] & $mask) != ($bytesTest[$i] & $mask)) {
                return false;
            }
        }

        return true;
    }
}
