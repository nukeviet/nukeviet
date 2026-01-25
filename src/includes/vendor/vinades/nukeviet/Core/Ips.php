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

    /**
     * __construct()
     */
    public function __construct()
    {
        self::$client_ip = trim(self::nv_get_clientip());
        self::$forward_ip = trim(self::nv_get_forwardip());
        self::$remote_addr = trim(self::nv_get_remote_addr());
        self::$remote_ip = trim(self::nv_getip());
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
     * nv_getip()
     * Hàm tĩnh riêng của class
     *
     * @return string
     */
    private static function nv_getip()
    {
        if (($ip = self::getIp('HTTP_CF_CONNECTING_IP')) !== false) {
            return $ip;
        }
        if (self::$client_ip != 'none') {
            return self::$client_ip;
        }
        if (self::$forward_ip != 'none') {
            return self::$forward_ip;
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
