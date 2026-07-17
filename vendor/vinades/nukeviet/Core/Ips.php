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
 * NukeViet\Core\Ips
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Ips
{
    public $client_ip;

    public $forward_ip;

    public $remote_addr;

    public $remote_ip;

    public $is_proxy = 0;

    private $ip6_support = false;

    private $trust_proxy = false;

    private $trusted_proxies = [];

    /**
     * __construct()
     *
     * @param array $sys
     * @param bool  $trust_proxy     Có tin các header IP do proxy đặt hay không
     * @param array $trusted_proxies Danh sách IP/dải CIDR proxy tin cậy
     */
    public function __construct($sys = [], $trust_proxy = false, array $trusted_proxies = [])
    {
        $this->trust_proxy = (bool) $trust_proxy;
        $this->trusted_proxies = $trusted_proxies;
        $this->client_ip = trim($this->nv_get_clientip());
        $this->forward_ip = trim($this->nv_get_forwardip());
        $this->remote_addr = trim($this->nv_get_remote_addr());
        $this->remote_ip = trim($this->nv_getip());
        $this->ip6_support = !empty($sys['ip6_support']);
    }

    /**
     * @param string $variable_name
     * @return string|false
     */
    private function getIp($variable_name)
    {
        $ip = $this->nv_getenv($variable_name);
        return ($ip and filter_var($ip, FILTER_VALIDATE_IP)) ? $ip : false;
    }

    /**
     * nv_getenv()
     *
     * @param string $key
     * @return string
     */
    private function nv_getenv($key)
    {
        if (isset($_SERVER[$key])) {
            if (strpos($_SERVER[$key], ',')) {
                $_arr = explode(',', $_SERVER[$key]);

                return trim($_arr[0]);
            }

            return $_SERVER[$key];
        }
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        if (@getenv($key)) {
            return @getenv($key);
        }
        if (function_exists('apache_getenv') and apache_getenv($key, true)) {
            return apache_getenv($key, true);
        }

        return '';
    }

    /**
     * nv_validip()
     *
     * @param string $ip
     * @return bool
     */
    public function nv_validip($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /**
     * isIp4()
     *
     * @param string $ip
     * @return bool
     */
    public function isIp4($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    /**
     * isIp6()
     *
     * @param string $ip
     * @return bool
     */
    public function isIp6($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    /**
     * server_ip()
     *
     * @return string
     */
    public function server_ip()
    {
        $serverip = $this->nv_getenv('SERVER_ADDR');
        if ($this->nv_validip($serverip)) {
            return $serverip;
        }
        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            return '127.0.0.1';
        }
        if (function_exists('gethostbyname')) {
            return gethostbyname($_SERVER['SERVER_NAME']);
        }

        return 'none';
    }

    /**
     * nv_get_clientip()
     *
     * @return string
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
        }

        return 'none';
    }

    /**
     * nv_get_forwardip()
     *
     * @return string
     */
    private function nv_get_forwardip()
    {
        if ($this->nv_getenv('HTTP_X_FORWARDED_FOR') and $this->nv_validip($this->nv_getenv('HTTP_X_FORWARDED_FOR'))) {
            return $this->nv_getenv('HTTP_X_FORWARDED_FOR');
        }
        if ($this->nv_getenv('HTTP_X_FORWARDED') and $this->nv_validip($this->nv_getenv('HTTP_X_FORWARDED'))) {
            return $this->nv_getenv('HTTP_X_FORWARDED');
        }
        if ($this->nv_getenv('HTTP_FORWARDED_FOR') and $this->nv_validip($this->nv_getenv('HTTP_FORWARDED_FOR'))) {
            return $this->nv_getenv('HTTP_FORWARDED_FOR');
        }
        if ($this->nv_getenv('HTTP_FORWARDED') and $this->nv_validip($this->nv_getenv('HTTP_FORWARDED'))) {
            return $this->nv_getenv('HTTP_FORWARDED');
        }

        return 'none';
    }

    /**
     * nv_get_remote_addr()
     * Địa chỉ IP người dùng đang truy cập do máy chủ cung cấp
     *
     * @return string
     */
    private function nv_get_remote_addr()
    {
        if ($this->nv_getenv('REMOTE_ADDR') and $this->nv_validip($this->nv_getenv('REMOTE_ADDR'))) {
            return $this->nv_getenv('REMOTE_ADDR');
        }

        return 'none';
    }

    /**
     * Lấy IP client thật từ header X-Forwarded-For khi đứng sau proxy tin cậy.
     * Duyệt danh sách từ phải sang trái, bỏ qua các IP thuộc danh sách proxy tin cậy,
     * IP hợp lệ đầu tiên là client IP.
     *
     * @return false|string
     */
    private function getForwardedClient()
    {
        $xff = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? (string) $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
        if (empty($xff)) {
            return false;
        }

        $parts = explode(',', $xff);
        for ($i = count($parts) - 1; $i >= 0; $i--) {
            $ip = trim($parts[$i]);
            if ($ip === '' or !filter_var($ip, FILTER_VALIDATE_IP)) {
                continue;
            }
            if (!$this->isTrustedProxy($ip)) {
                return $ip;
            }
        }

        return false;
    }

    /**
     * Lấy IP thật của người dùng đang truy cập
     *
     * @return string
     */
    private function nv_getip()
    {
        if ($this->trust_proxy) {
            /**
             * Bật tính năng tin tưởng proxy thì chỉ đọc các header chuẩn
             * bỏ qua các header cũ, header không theo chuẩn.
             */
            if ($this->remote_addr != 'none' and $this->isTrustedProxy($this->remote_addr)) {
                // Cloudflare
                if (($ip = $this->getIp('HTTP_CF_CONNECTING_IP')) !== false) {
                    return $ip;
                }
                // X-Forwarded-For lấy từ phải sang trái bỏ qua chính ip của proxy
                if (($ip = $this->getForwardedClient()) !== false) {
                    return $ip;
                }
            }
        } else {
            // Tắt tin tưởng proxy thì đọc header rộng
            if (($ip = $this->getIp('HTTP_CF_CONNECTING_IP')) !== false) {
                return $ip;
            }
            if ($this->client_ip != 'none') {
                return $this->client_ip;
            }
            if ($this->forward_ip != 'none') {
                return $this->forward_ip;
            }
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
     * Kiểm tra một IP có thuộc danh sách proxy tin cậy hay không
     *
     * @param string $ip
     * @return bool
     */
    private function isTrustedProxy(string $ip)
    {
        foreach ($this->trusted_proxies as $cidr) {
            if (self::ipInRange($ip, $cidr)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kiểm tra địa chỉ $ip có nằm trong dải CIDR $cidr không. Hỗ trợ IPv4 và IPv6.
     *
     * @param string $ip
     * @param string $cidr
     * @return bool
     */
    public static function ipInRange(string $ip, string $cidr)
    {
        $cidr = trim($cidr);
        if ($cidr === '') {
            return false;
        }

        // IP đơn không có mask thì coi như /32 (IPv4) hoặc /128 (IPv6)
        if (strpos($cidr, '/') === false) {
            $cidr .= (strpos($cidr, ':') !== false) ? '/128' : '/32';
        }

        [$subnet, $bits] = explode('/', $cidr, 2);
        if (!ctype_digit($bits)) {
            return false;
        }
        $bits = (int) $bits;

        $ip_bin = inet_pton($ip);
        $subnet_bin = inet_pton($subnet);

        if ($ip_bin === false or $subnet_bin === false or strlen($ip_bin) !== strlen($subnet_bin) or $bits > strlen($ip_bin) * 8) {
            return false;
        }

        $bytes = intdiv($bits, 8);
        $remainder = $bits % 8;

        // So khớp các byte nguyên
        if ($bytes > 0 and strncmp($ip_bin, $subnet_bin, $bytes) !== 0) {
            return false;
        }

        // So khớp phần bit lẻ còn lại
        if ($remainder > 0) {
            $mask = ~(0xff >> $remainder) & 0xff;
            if ((ord($ip_bin[$bytes]) & $mask) !== (ord($subnet_bin[$bytes]) & $mask)) {
                return false;
            }
        }

        return true;
    }

    /**
     * checkIp6()
     * Kiểm tra xem địa chỉ IP $requestIp có nằm trong dải $ip hoặc bằng với $ip không
     *
     * @param string $requestIp
     * @param string $ip
     * @return bool|int
     */
    public function checkIp6($requestIp, $ip)
    {
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

    /**
     * is_localhost()
     *
     * @param string $ip
     * @return bool
     */
    public function is_localhost($ip = '')
    {
        if (empty($ip)) {
            $ip = $this->remote_ip;
        }

        return substr($ip, 0, 4) == '127.' or $ip == '::1';
    }

    /**
     * Kiểm tra một chuỗi có phải địa chỉ IP đơn hoặc dải CIDR hợp lệ (IPv4/IPv6) không
     *
     * @param string $cidr
     * @return bool
     */
    public static function validCidr($cidr)
    {
        $cidr = trim((string) $cidr);
        if ($cidr === '') {
            return false;
        }

        // Trường hợp CIDR: địa_chỉ/số_bit
        if (strpos($cidr, '/') !== false) {
            [$ip, $mask] = explode('/', $cidr, 2);
            if (!ctype_digit($mask) || strlen($mask) > 3) {
                return false;
            }
            $mask = (int) $mask;
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                return $mask >= 0 and $mask <= 32;
            }
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                return $mask >= 0 and $mask <= 128;
            }

            return false;
        }

        // Trường hợp chỉ là một địa chỉ IP đơn lẻ
        return (bool) filter_var($cidr, FILTER_VALIDATE_IP);
    }
}
