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

    /**
     * Các header cho biết request đi qua reverse proxy hoặc CDN đặt trước máy chủ
     *
     * Chỉ gồm các header mà reverse proxy/CDN thực tế đặt. Không đưa vào HTTP_VIA hay
     * HTTP_CLIENT_IP: chúng thường do proxy phía client (proxy doanh nghiệp, ISP) đặt,
     * mà loại proxy đó kết nối trực tiếp tới máy chủ nên website không hề đứng sau proxy.
     * Nhận diện lẫn sẽ dẫn tới khuyên người quản trị thêm dải IP proxy công cộng vào
     * danh sách tin cậy, tự tạo ra lỗ hổng giả mạo IP.
     */
    private const PROXY_HEADERS = [
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'HTTP_X_REAL_IP'
    ];

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
        /**
         * Bật tính năng tin tưởng proxy thì chỉ đọc các header chuẩn
         * bỏ qua các header cũ, header không theo chuẩn.
         */
        if ($this->trust_proxy) {
            if (self::$remote_addr != 'none' and $this->isTrustedProxy(self::$remote_addr)) {
                // Cloudflare
                if (($ip = self::getIp('HTTP_CF_CONNECTING_IP')) !== false) {
                    return $ip;
                }
                // X-Forwarded-For lấy từ phải sang trái bỏ qua chính ip của proxy
                if (($ip = $this->getForwardedClient()) !== false) {
                    return $ip;
                }
                // Forwarded theo RFC 7239, cũng duyệt từ phải sang trái
                if (($ip = $this->getRfc7239Client()) !== false) {
                    return $ip;
                }
                // X-Real-IP: proxy ghi thẳng IP khách, chỉ một giá trị
                if (($ip = self::getIp('HTTP_X_REAL_IP')) !== false) {
                    return $ip;
                }
            }
        }

        /**
         * Tắt tin tưởng proxy, hoặc IP kết nối trực tiếp không thuộc danh sách proxy tin cậy
         * thì bỏ qua toàn bộ header IP do client gửi, chỉ dùng IP do máy chủ cung cấp.
         */
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
     *
     * @return false|string
     */
    private function getForwardedClient()
    {
        $xff = Site::getEnv('HTTP_X_FORWARDED_FOR');
        if (empty($xff)) {
            return false;
        }

        return $this->pickClientFromChain(explode(',', $xff));
    }

    /**
     * Lấy IP client thật từ header Forwarded (RFC 7239) khi đứng sau proxy tin cậy.
     * Mỗi chặng có dạng for=192.0.2.60;proto=http;by=203.0.113.43, các chặng ngăn nhau
     * bởi dấu phẩy. Chỉ quan tâm tham số for, bỏ qua proto, by, host.
     *
     * @return false|string
     */
    private function getRfc7239Client()
    {
        $forwarded = Site::getEnv('HTTP_FORWARDED');
        if (empty($forwarded)) {
            return false;
        }

        $chain = [];
        foreach (explode(',', $forwarded) as $element) {
            foreach (explode(';', $element) as $param) {
                [$name, $value] = array_pad(explode('=', $param, 2), 2, '');
                if (strtolower(trim($name)) !== 'for') {
                    continue;
                }
                $chain[] = self::normalizeForwardedFor($value);
                break;
            }
        }

        return $this->pickClientFromChain($chain);
    }

    /**
     * Chuẩn hóa giá trị tham số for của header Forwarded về địa chỉ IP.
     * Theo RFC 7239 giá trị có thể nằm trong dấu nháy kép, kèm cổng, riêng IPv6 còn
     * bọc trong dấu ngoặc vuông: "[2001:db8::1]:4711", "192.0.2.43:47011".
     * Các định danh ẩn danh (_hidden, unknown) trả về nguyên trạng rồi bị loại ở bước
     * kiểm tra IP hợp lệ.
     *
     * @param string $value
     * @return string
     */
    private static function normalizeForwardedFor($value)
    {
        $value = trim(trim($value), '"');

        // IPv6 bọc trong ngoặc vuông, phần sau dấu ] là cổng nên bỏ đi
        if (str_starts_with($value, '[')) {
            $end = strpos($value, ']');

            return ($end === false) ? '' : substr($value, 1, $end - 1);
        }

        // Đúng một dấu hai chấm nghĩa là IPv4 kèm cổng. Nhiều dấu hai chấm là IPv6 trần
        if (substr_count($value, ':') === 1) {
            $value = substr($value, 0, strpos($value, ':'));
        }

        return $value;
    }

    /**
     * Duyệt chuỗi IP chuyển tiếp từ phải sang trái, bỏ qua các giá trị không phải IP
     * hợp lệ và các IP thuộc danh sách proxy tin cậy. IP tìm được đầu tiên là IP khách.
     * Duyệt từ phải sang vì phần bên trái do client tự gửi nên giả mạo được, phần bên
     * phải mới là do các proxy tin cậy ghi thêm.
     *
     * @param array $chain
     * @return false|string
     */
    private function pickClientFromChain(array $chain)
    {
        for ($i = count($chain) - 1; $i >= 0; $i--) {
            $ip = trim($chain[$i]);
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
     * Kiểm tra request hiện tại có dấu hiệu đi qua proxy hoặc CDN hay không, dựa vào
     * sự hiện diện của các header IP do proxy đặt.
     * Đây chỉ là dấu hiệu, chưa biết có tin cậy hay không, client tự gửi được các header này.
     *
     * @return bool
     */
    public function isBehindProxy()
    {
        foreach (self::PROXY_HEADERS as $header) {
            if (Site::getEnv($header) != '') {
                return true;
            }
        }

        return false;
    }

    /**
     * Header IP do proxy đặt có thực sự được tin trong request hiện tại hay không:
     * tùy chọn tin cậy proxy đang bật và IP kết nối trực tiếp thuộc danh sách proxy tin cậy.
     * Trả về false nghĩa là hệ thống đang bỏ qua mọi header IP và dùng REMOTE_ADDR.
     *
     * @return bool
     */
    public function isProxyHeaderTrusted()
    {
        return ($this->trust_proxy and self::$remote_addr != 'none' and $this->isTrustedProxy(self::$remote_addr));
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

        if (Site::function_exists('dns_get_record') and defined('DNS_AAAA')) {
            $aaaa = @dns_get_record($host, DNS_AAAA);
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
     * @param string      $host           Tên miền hoặc IP
     * @param string|null $pin_ip         (out) IP đã kiểm để ghim kết nối
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
