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
 * NukeViet\Core\Ips
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class Ips
{
    const INCORRECT_IP = 'Incorrect IP address specified';

    public static $client_ip;

    public static $forward_ip;

    public static $remote_addr;

    public static $remote_ip;

    public static $my_ip2long;

    private static $ip6_support = false;

    private $trust_proxy = false;

    private $trusted_proxies = [];

    /**
     * __construct()
     *
     * @param bool  $trust_proxy     Có tin các header IP do proxy đặt hay không
     * @param array $trusted_proxies Danh sách IP/dải CIDR proxy tin cậy
     */
    public function __construct($trust_proxy = false, array $trusted_proxies = [])
    {
        $this->trust_proxy = (bool) $trust_proxy;
        $this->trusted_proxies = $trusted_proxies;

        self::$client_ip = trim(self::nv_get_clientip());
        self::$forward_ip = trim(self::nv_get_forwardip());
        self::$remote_addr = trim(self::nv_get_remote_addr());
        self::$remote_ip = trim($this->nv_getip());
        self::$my_ip2long = self::ip2long();

        if (self::$my_ip2long === false) {
            exit(self::INCORRECT_IP);
        }

        self::$ip6_support = ((extension_loaded('sockets') and defined('AF_INET6')) or @inet_pton('::1')) ? true : false;
    }

    /**
     * getIp()
     * Hàm tĩnh riêng của class
     *
     * @param string $variable_name
     * @return false|string
     */
    private static function getIp($variable_name)
    {
        $ip = Site::getEnv($variable_name);

        return ($ip and filter_var($ip, FILTER_VALIDATE_IP)) ? $ip : false;
    }

    /**
     * server_ip()
     * Hàm tĩnh công cộng của class
     *
     * @return string
     */
    public static function server_ip()
    {
        if (($ip = self::getIp('SERVER_ADDR')) !== false) {
            return $ip;
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
     * Hàm tĩnh riêng của class
     *
     * @return string
     */
    private static function nv_get_clientip()
    {
        if (($ip = self::getIp('HTTP_CLIENT_IP')) !== false) {
            return $ip;
        }
        if (($ip = self::getIp('HTTP_VIA')) !== false) {
            return $ip;
        }
        if (($ip = self::getIp('HTTP_X_COMING_FROM')) !== false) {
            return $ip;
        }
        if (($ip = self::getIp('HTTP_COMING_FROM')) !== false) {
            return $ip;
        }

        return 'none';
    }

    /**
     * nv_get_forwardip()
     * Hàm tĩnh riêng của class
     *
     * @return string
     */
    private static function nv_get_forwardip()
    {
        if (($ip = self::getIp('HTTP_X_FORWARDED_FOR')) !== false) {
            return $ip;
        }
        if (($ip = self::getIp('HTTP_X_FORWARDED')) !== false) {
            return $ip;
        }
        if (($ip = self::getIp('HTTP_FORWARDED_FOR')) !== false) {
            return $ip;
        }
        if (($ip = self::getIp('HTTP_FORWARDED')) !== false) {
            return $ip;
        }

        return 'none';
    }

    /**
     * nv_get_remote_addr()
     * Hàm tĩnh riêng của class
     * Địa chỉ IP người dùng đang truy cập do máy chủ cung cấp
     *
     * @return string
     */
    private static function nv_get_remote_addr()
    {
        if (($ip = self::getIp('REMOTE_ADDR')) !== false) {
            return $ip;
        }

        return 'none';
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
            if (self::$remote_addr != 'none' and $this->isTrustedProxy(self::$remote_addr)) {
                // Cloudflare
                if (($ip = self::getIp('HTTP_CF_CONNECTING_IP')) !== false) {
                    return $ip;
                }
                // X-Forwarded-For lấy từ phải sang trái bỏ qua chính ip của proxy
                if (($ip = $this->getForwardedClient()) !== false) {
                    return $ip;
                }
            }
        } else {
            // Tắt tin tưởng proxy thì đọc header rộng
            if (($ip = self::getIp('HTTP_CF_CONNECTING_IP')) !== false) {
                return $ip;
            }
            if (self::$client_ip != 'none') {
                return self::$client_ip;
            }
            if (self::$forward_ip != 'none') {
                return self::$forward_ip;
            }
        }

        if (self::$remote_addr != 'none') {
            return self::$remote_addr;
        }

        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            return '127.0.0.1';
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
        $xff = Site::getEnv('HTTP_X_FORWARDED_FOR');
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
     * nv_check_proxy()
     * Hàm tĩnh công cộng của class
     *
     * @return string
     */
    public static function nv_check_proxy()
    {
        $proxy = 'No';
        if (self::$client_ip != 'none' or self::$forward_ip != 'none') {
            $proxy = 'Lite';
        }
        $host = @gethostbyaddr(self::$remote_ip);
        if (stristr($host, 'proxy')) {
            $proxy = 'Mild';
        }
        if (self::$remote_ip == $host) {
            $proxy = 'Strong';
        }

        return $proxy;
    }

    /**
     * nv_validip()
     * Hàm công cộng của class
     *
     * @param mixed $ip
     * @return mixed
     */
    public function nv_validip($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /**
     * isIp4()
     * Hàm công cộng của class
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
     * Hàm công cộng của class
     *
     * @param string $ip
     * @return bool
     */
    public function isIp6($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
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

    /**
     * checkIp6()
     * Hàm công cộng của class
     * Kiểm tra xem địa chỉ IP $requestIp có nằm trong dải $ip hoặc bằng với $ip không
     *
     * @param string $requestIp
     * @param string $ip
     * @return bool|int
     */
    public function checkIp6($requestIp, $ip)
    {
        if (!self::$ip6_support) {
            // Không hỗ trợ xử lý IPv6 trả về -1
            return -1;
        }

        if (str_contains($ip, '/')) {
            [$address, $netmask] = explode('/', $ip, 2);

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
     * Hàm công cộng của class
     *
     * @param string $ip
     * @return bool
     */
    public function is_localhost($ip = '')
    {
        if (empty($ip)) {
            $ip = self::$remote_ip;
        }

        return substr($ip, 0, 4) == '127.' or $ip == '::1';
    }

    /**
     * ip2long()
     *
     * @param string $ip
     * @return false|int|string
     */
    public static function ip2long($ip = '')
    {
        empty($ip) && $ip = self::$remote_ip;

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

        if ($ip2long === -1 or $ip2long === false) {
            return false;
        }

        return $ip2long;
    }
}
