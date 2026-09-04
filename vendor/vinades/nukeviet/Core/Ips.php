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

    /**
     * __construct()
     *
     * @param array $sys
     */
    public function __construct($sys = [])
    {
        $this->client_ip = trim($this->nv_get_clientip());
        $this->forward_ip = trim($this->nv_get_forwardip());
        $this->remote_addr = trim($this->nv_get_remote_addr());
        $this->remote_ip = trim($this->nv_getip());

        $this->ip6_support = (bool) $sys['ip6_support'];
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
     * nv_getip()
     *
     * @return string
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
     * nv_check_proxy()
     *
     * @return string
     */
    public function nv_check_proxy()
    {
        $proxy = 'No';
        if ($this->client_ip != 'none' or $this->forward_ip != 'none') {
            $proxy = 'Lite';
        }
        $host = @gethostbyaddr($this->remote_ip);
        if (stristr($host, 'proxy')) {
            $proxy = 'Mild';
        }
        if ($this->remote_ip == $host) {
            $proxy = 'Strong';
        }

        return $proxy;
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
     * Kiểm tra địa chỉ $ip có nằm trong dải CIDR $cidr không. Hỗ trợ IPv4 và IPv6.
     *
     * @param string $ip
     * @param string $cidr
     * @return bool
     */
    public static function ipInRange($ip, $cidr)
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
     * Danh sách các dải IP nội bộ/dành riêng (private, loopback, link-local,
     * cloud-metadata, CGNAT, benchmarking, documentation, multicast, reserved...)
     * dùng cho việc chống SSRF. Bất kỳ IP nằm trong các dải này đều bị coi là không an toàn.
     *
     * @return array
     */
    public static function unsafe_ranges()
    {
        return [
            // IPv4
            '0.0.0.0/8',        // "this host"
            '10.0.0.0/8',       // private (RFC1918)
            '100.64.0.0/10',    // CGNAT (RFC6598)
            '127.0.0.0/8',      // loopback
            '169.254.0.0/16',   // link-local / cloud-metadata (169.254.169.254)
            '172.16.0.0/12',    // private (RFC1918)
            '192.0.0.0/24',     // IETF protocol assignments
            '192.0.2.0/24',     // TEST-NET-1
            '192.168.0.0/16',   // private (RFC1918)
            '198.18.0.0/15',    // benchmarking
            '198.51.100.0/24',  // TEST-NET-2
            '203.0.113.0/24',   // TEST-NET-3
            '224.0.0.0/4',      // multicast
            '240.0.0.0/4',      // reserved + 255.255.255.255 broadcast
            // IPv6
            '::/128',           // unspecified
            '::1/128',          // loopback
            '::ffff:0:0/96',    // IPv4-mapped (belt-and-suspenders, đã canonicalize trước)
            '64:ff9b::/96',     // NAT64
            '100::/64',         // discard-only
            '2001:db8::/32',    // documentation
            '2002::/16',        // 6to4
            'fc00::/7',         // unique local (ULA)
            'fe80::/10',        // link-local
            'ff00::/8',         // multicast
        ];
    }

    /**
     * Nếu $bin (16 byte IPv6 nhị phân) là địa chỉ IPv6 có nhúng IPv4
     * (IPv4-mapped, IPv4-compatible, NAT64, 6to4) thì trả về địa chỉ IPv4
     * dạng chuỗi để kiểm tra theo dải IPv4. Ngược lại trả về null.
     *
     * @param string $bin
     * @return string|null
     */
    private static function extract_embedded_ipv4($bin)
    {
        if (strlen($bin) !== 16) {
            return null;
        }

        // IPv4-mapped: ::ffff:0:0/96 -> IPv4 nằm ở 32 bit cuối
        if (strncmp($bin, str_repeat("\0", 10) . "\xff\xff", 12) === 0) {
            return inet_ntop(substr($bin, 12, 4));
        }

        // IPv4-compatible (deprecated): ::/96 -> IPv4 ở 32 bit cuối
        // Bỏ qua :: và ::1 để chúng được xử lý bởi dải IPv6 dành riêng.
        if (strncmp($bin, str_repeat("\0", 12), 12) === 0) {
            $tail = substr($bin, 12, 4);
            if ($tail !== "\0\0\0\0" and $tail !== "\0\0\0\1") {
                return inet_ntop($tail);
            }
        }

        // NAT64: 64:ff9b::/96 -> IPv4 ở 32 bit cuối
        if (strncmp($bin, "\x00\x64\xff\x9b" . str_repeat("\0", 8), 12) === 0) {
            return inet_ntop(substr($bin, 12, 4));
        }

        // 6to4: 2002::/16 -> IPv4 nằm ở 32 bit kế tiếp prefix
        if (strncmp($bin, "\x20\x02", 2) === 0) {
            return inet_ntop(substr($bin, 2, 4));
        }

        return null;
    }

    /**
     * Kiểm tra một IP có phải IP công khai an toàn (không thuộc dải nội bộ/dành riêng)
     * để chống SSRF. Chuẩn hóa các địa chỉ IPv6 có nhúng IPv4
     * (::ffff:127.0.0.1, ::a.b.c.d, 64:ff9b::a.b.c.d, 2002:...) về IPv4 trước khi kiểm tra.
     *
     * @param string $ip
     * @return bool
     */
    public static function is_safe_public_ip($ip)
    {
        $ip = trim((string) $ip);
        if ($ip === '' or !filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        $bin = inet_pton($ip);
        if ($bin === false) {
            return false;
        }

        // Nếu là IPv6 chứa IPv4 nhúng thì kiểm tra IPv4 tương ứng
        if (strlen($bin) === 16) {
            $embedded = self::extract_embedded_ipv4($bin);
            if ($embedded !== null and $embedded !== false) {
                return self::is_safe_public_ip($embedded);
            }
        }

        foreach (self::unsafe_ranges() as $cidr) {
            if (self::ipInRange($ip, $cidr)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Resolve toàn bộ IPv4 (A) và IPv6 (AAAA) của một host.
     * Nếu $host đã là IP thì trả về chính nó.
     *
     * @param string $host
     * @return array
     */
    public static function resolve_host_ips($host)
    {
        $host = strtolower(trim((string) $host));
        if ($host === '') {
            return [];
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return [$host];
        }

        $ips = [];

        $ipv4 = gethostbynamel($host);
        if (is_array($ipv4)) {
            $ips = $ipv4;
        }

        if (function_exists('dns_get_record') && defined('DNS_AAAA')) {
            $aaaa = dns_get_record($host, DNS_AAAA);
            if (is_array($aaaa)) {
                foreach ($aaaa as $record) {
                    if (!empty($record['ipv6'])) {
                        $ips[] = $record['ipv6'];
                    }
                }
            }
        }

        return array_values(array_unique($ips));
    }

    /**
     * Kiểm tra một host có an toàn để fetch (chống SSRF) hay không.
     * Resolve tất cả bản ghi A và AAAA rồi yêu cầu mọi IP đều nằm ngoài
     * dải nội bộ/dành riêng (đã canonicalize IPv4-mapped/NAT64/6to4).
     * Trả về IP đầu tiên qua $pin_ip để ghim kết nối chống DNS rebinding.
     *
     * @param string      $host          Tên miền hoặc IP
     * @param string|null $pin_ip        (out) IP đã kiểm để ghim kết nối
     * @param bool        $allow_internal Cho phép IP nội bộ hay không
     * @return bool
     */
    public static function is_safe_host($host, &$pin_ip = null, $allow_internal = false)
    {
        $pin_ip = null;

        $ips = self::resolve_host_ips($host);
        if (empty($ips)) {
            return false;
        }

        if (!$allow_internal) {
            foreach ($ips as $ip) {
                if (!self::is_safe_public_ip($ip)) {
                    return false;
                }
            }
        }

        $pin_ip = $ips[0];

        return true;
    }
}
