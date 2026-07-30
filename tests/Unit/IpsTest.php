<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Unit;

use NukeViet\Core\Ips;
use Tests\Support\UnitTester;

/**
 * Kiểm tra việc xác định IP thật của khách qua NukeViet\Core\Ips.
 *
 * Nguyên tắc: header IP do client gửi chỉ được tin khi bật tin tưởng proxy và
 * IP kết nối trực tiếp (REMOTE_ADDR) thuộc danh sách proxy tin cậy. Mọi trường hợp
 * còn lại phải trả về REMOTE_ADDR để không thể giả mạo IP bằng header.
 */
class IpsTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    /**
     * Các dải proxy tin cậy dùng cho test
     */
    private const PROXIES = ['10.0.0.0/8', '2400:cb00::/32'];

    /**
     * Các biến $_SERVER bị test ghi đè, cần phục hồi sau mỗi test
     */
    private const IP_KEYS = [
        'REMOTE_ADDR',
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'HTTP_X_REAL_IP',
        'HTTP_CLIENT_IP',
        'HTTP_VIA'
    ];

    /**
     * @var array
     */
    private $server_backup = [];

    /**
     * Đối tượng Ips của lần detect() gần nhất
     *
     * @var Ips
     */
    private $ips;

    protected function _before()
    {
        $this->server_backup = $_SERVER;
    }

    protected function _after()
    {
        $_SERVER = $this->server_backup;
    }

    /**
     * Nạp các biến $_SERVER rồi khởi tạo Ips, trả về IP mà hệ thống xác định được
     *
     * @param array $server        Các biến $_SERVER liên quan tới IP
     * @param bool  $trust_proxy   Bật/tắt tin tưởng proxy
     * @param array $proxies       Danh sách dải proxy tin cậy
     * @return string
     */
    private function detect(array $server, $trust_proxy, array $proxies = self::PROXIES)
    {
        foreach (self::IP_KEYS as $key) {
            unset($_SERVER[$key]);
        }
        foreach ($server as $key => $value) {
            $_SERVER[$key] = $value;
        }

        $this->ips = new Ips($trust_proxy, $proxies);

        return Ips::$remote_ip;
    }

    /**
     * Tắt tin tưởng proxy: mọi header IP do client gửi phải bị bỏ qua hoàn toàn.
     * Đây là ca hồi quy cho lỗ hổng giả mạo IP qua header khi trust proxy tắt.
     *
     * @group all
     * @group security
     */
    public function testHeadersIgnoredWhenTrustProxyDisabled()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '203.0.113.9',
            'HTTP_CF_CONNECTING_IP' => '1.2.3.4',
            'HTTP_X_FORWARDED_FOR' => '5.6.7.8',
            'HTTP_FORWARDED' => 'for=9.10.11.12',
            'HTTP_X_REAL_IP' => '13.14.15.16',
            'HTTP_CLIENT_IP' => '17.18.19.20'
        ], false);

        $this->assertSame('203.0.113.9', $ip, 'Tắt trust proxy nhưng IP vẫn lấy từ header do client gửi');
    }

    /**
     * Bật tin tưởng proxy nhưng REMOTE_ADDR không thuộc danh sách: vẫn phải bỏ qua header
     *
     * @group all
     * @group security
     */
    public function testHeadersIgnoredWhenRemoteAddrNotTrusted()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '203.0.113.9',
            'HTTP_CF_CONNECTING_IP' => '1.2.3.4',
            'HTTP_X_FORWARDED_FOR' => '5.6.7.8'
        ], true);

        $this->assertSame('203.0.113.9', $ip);
    }

    /**
     * Bật tin tưởng proxy và danh sách trống: không có proxy nào tin cậy nên bỏ qua header.
     * Trạng thái này tương đương với việc tắt tin tưởng proxy.
     *
     * @group all
     * @group security
     */
    public function testHeadersIgnoredWhenTrustedListEmpty()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.0.0.5',
            'HTTP_X_FORWARDED_FOR' => '5.6.7.8'
        ], true, []);

        $this->assertSame('10.0.0.5', $ip);
    }

    /**
     * Bật tin tưởng proxy, REMOTE_ADDR thuộc danh sách: ưu tiên header của Cloudflare
     *
     * @group all
     */
    public function testCloudflareHeaderTrustedFromTrustedProxy()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_CF_CONNECTING_IP' => '1.2.3.4',
            'HTTP_X_FORWARDED_FOR' => '5.6.7.8'
        ], true);

        $this->assertSame('1.2.3.4', $ip);
    }

    /**
     * Không có header Cloudflare thì lấy từ X-Forwarded-For
     *
     * @group all
     */
    public function testForwardedForTrustedFromTrustedProxy()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_X_FORWARDED_FOR' => '5.6.7.8'
        ], true);

        $this->assertSame('5.6.7.8', $ip);
    }

    /**
     * X-Forwarded-For nhiều chặng: duyệt từ phải sang trái, bỏ qua các IP proxy tin cậy,
     * IP hợp lệ đầu tiên không thuộc danh sách proxy chính là IP khách.
     *
     * @group all
     */
    public function testForwardedForMultiHopSkipsTrustedProxies()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_X_FORWARDED_FOR' => '198.51.100.7, 10.9.9.9, 10.8.8.8'
        ], true);

        $this->assertSame('198.51.100.7', $ip);
    }

    /**
     * Client tự chèn IP giả vào đầu X-Forwarded-For: chặng do proxy tin cậy ghi thêm
     * nằm bên phải nên vẫn lấy đúng IP thật, không lấy IP giả.
     *
     * @group all
     * @group security
     */
    public function testForwardedForSpoofedPrefixIgnored()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_X_FORWARDED_FOR' => '1.1.1.1, 198.51.100.7'
        ], true);

        $this->assertSame('198.51.100.7', $ip);
    }

    /**
     * X-Forwarded-For chỉ chứa IP không hợp lệ thì bỏ qua, quay về REMOTE_ADDR
     *
     * @group all
     */
    public function testInvalidForwardedForFallsBackToRemoteAddr()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_X_FORWARDED_FOR' => 'not-an-ip, 999.999.999.999'
        ], true);

        $this->assertSame('10.1.2.3', $ip);
    }

    /**
     * X-Real-IP: header rất phổ biến với Nginx, phải được đọc khi đứng sau proxy tin cậy.
     * Trước đây header này nằm trong danh sách dấu hiệu proxy nhưng không được phân tích,
     * khiến quản trị bật tin tưởng proxy mà IP vẫn ra IP của chính proxy.
     *
     * @group all
     */
    public function testRealIpTrustedFromTrustedProxy()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_X_REAL_IP' => '198.51.100.7'
        ], true);

        $this->assertSame('198.51.100.7', $ip);
    }

    /**
     * X-Forwarded-For được ưu tiên hơn X-Real-IP khi có cả hai
     *
     * @group all
     */
    public function testForwardedForTakesPrecedenceOverRealIp()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_X_FORWARDED_FOR' => '198.51.100.7',
            'HTTP_X_REAL_IP' => '203.0.113.50'
        ], true);

        $this->assertSame('198.51.100.7', $ip);
    }

    /**
     * Header Forwarded theo RFC 7239: đọc tham số for, bỏ qua proto/by/host,
     * xử lý được dấu nháy, cổng và IPv6 bọc ngoặc vuông.
     *
     * @group all
     */
    public function testRfc7239ForwardedTrustedFromTrustedProxy()
    {
        $cases = [
            'for=198.51.100.7' => '198.51.100.7',
            'for=198.51.100.7;proto=http;by=203.0.113.43' => '198.51.100.7',
            'For="198.51.100.7"' => '198.51.100.7',
            'proto=https;for=198.51.100.7' => '198.51.100.7',
            'for="198.51.100.7:47011"' => '198.51.100.7',
            'for="[2001:db8::99]:4711"' => '2001:db8::99',
            'for=2001:db8::99' => '2001:db8::99'
        ];

        foreach ($cases as $header => $expected) {
            $ip = $this->detect([
                'REMOTE_ADDR' => '10.1.2.3',
                'HTTP_FORWARDED' => $header
            ], true);

            $this->assertSame($expected, $ip, 'Phân tích sai header Forwarded: ' . $header);
        }
    }

    /**
     * Forwarded nhiều chặng: duyệt từ phải sang trái, bỏ qua chặng của proxy tin cậy
     * và các định danh ẩn danh theo RFC 7239 (unknown, _hidden).
     *
     * @group all
     * @group security
     */
    public function testRfc7239ForwardedMultiHopSkipsTrustedProxies()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_FORWARDED' => 'for=1.1.1.1, for=198.51.100.7, for=10.9.9.9;proto=http'
        ], true);

        $this->assertSame('198.51.100.7', $ip, 'Không bỏ qua chặng proxy tin cậy trong Forwarded');

        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_FORWARDED' => 'for=198.51.100.7, for=unknown, for=_hidden'
        ], true);

        $this->assertSame('198.51.100.7', $ip, 'Không bỏ qua định danh ẩn danh trong Forwarded');
    }

    /**
     * Forwarded không có tham số for hợp lệ thì bỏ qua, quay về REMOTE_ADDR
     *
     * @group all
     */
    public function testInvalidRfc7239ForwardedFallsBackToRemoteAddr()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '10.1.2.3',
            'HTTP_FORWARDED' => 'proto=https;by=203.0.113.43'
        ], true);

        $this->assertSame('10.1.2.3', $ip);
    }

    /**
     * Proxy tin cậy IPv6: header vẫn được tin khi REMOTE_ADDR thuộc dải IPv6 tin cậy
     *
     * @group all
     */
    public function testTrustedProxyIpv6()
    {
        $ip = $this->detect([
            'REMOTE_ADDR' => '2400:cb00::1',
            'HTTP_CF_CONNECTING_IP' => '2001:db8::99'
        ], true);

        $this->assertSame('2001:db8::99', $ip);
    }

    /**
     * isBehindProxy(): nhận diện dấu hiệu request đi qua proxy hoặc CDN.
     * Không phụ thuộc cấu hình tin cậy, chỉ xét sự hiện diện của header.
     *
     * @group all
     */
    public function testIsBehindProxy()
    {
        $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_X_REAL_IP'];

        foreach ($headers as $header) {
            $this->detect(['REMOTE_ADDR' => '203.0.113.9', $header => '198.51.100.7'], false);
            $this->assertTrue($this->ips->isBehindProxy(), 'Không nhận ra proxy qua header ' . $header);
        }

        // HTTP_VIA và HTTP_CLIENT_IP thường do proxy phía client đặt, không có nghĩa
        // website đứng sau reverse proxy nên KHÔNG được tính là dấu hiệu
        foreach (['HTTP_VIA', 'HTTP_CLIENT_IP'] as $header) {
            $this->detect(['REMOTE_ADDR' => '203.0.113.9', $header => '198.51.100.7'], false);
            $this->assertFalse($this->ips->isBehindProxy(), 'Nhận nhầm proxy phía client qua header ' . $header);
        }

        // Không có header nào thì không coi là đứng sau proxy
        $this->detect(['REMOTE_ADDR' => '203.0.113.9'], false);
        $this->assertFalse($this->ips->isBehindProxy());

        // Header rỗng (proxy đã strip) cũng không tính là dấu hiệu
        $this->detect(['REMOTE_ADDR' => '203.0.113.9', 'HTTP_CF_CONNECTING_IP' => '', 'HTTP_X_FORWARDED_FOR' => ''], false);
        $this->assertFalse($this->ips->isBehindProxy());

        // Header đầu rỗng nhưng header sau có giá trị thì vẫn phải nhận ra
        $this->detect(['REMOTE_ADDR' => '203.0.113.9', 'HTTP_CF_CONNECTING_IP' => '', 'HTTP_X_FORWARDED_FOR' => '198.51.100.7'], false);
        $this->assertTrue($this->ips->isBehindProxy());
    }

    /**
     * isProxyHeaderTrusted(): chỉ true khi bật tin tưởng proxy VÀ REMOTE_ADDR thuộc danh sách.
     * Đây là căn cứ để biết hệ thống có đang thực sự đọc header hay không.
     *
     * @group all
     */
    public function testIsProxyHeaderTrusted()
    {
        $headers = ['REMOTE_ADDR' => '10.1.2.3', 'HTTP_X_FORWARDED_FOR' => '198.51.100.7'];

        // Bật trust, REMOTE_ADDR thuộc danh sách
        $this->detect($headers, true);
        $this->assertTrue($this->ips->isProxyHeaderTrusted());

        // Tắt trust
        $this->detect($headers, false);
        $this->assertFalse($this->ips->isProxyHeaderTrusted());

        // Bật trust nhưng danh sách rỗng
        $this->detect($headers, true, []);
        $this->assertFalse($this->ips->isProxyHeaderTrusted());

        // Bật trust nhưng REMOTE_ADDR ngoài danh sách
        $this->detect(['REMOTE_ADDR' => '203.0.113.9', 'HTTP_X_FORWARDED_FOR' => '198.51.100.7'], true);
        $this->assertFalse($this->ips->isProxyHeaderTrusted());
    }

    /**
     * ipInRange(): khớp dải IPv4/IPv6, IP đơn không mask, và các đầu vào không hợp lệ
     *
     * @group all
     */
    public function testIpInRange()
    {
        $this->assertTrue(Ips::ipInRange('10.1.2.3', '10.0.0.0/8'));
        $this->assertFalse(Ips::ipInRange('11.1.2.3', '10.0.0.0/8'));

        // Dải không tròn byte
        $this->assertTrue(Ips::ipInRange('172.20.0.1', '172.16.0.0/12'));
        $this->assertFalse(Ips::ipInRange('172.32.0.1', '172.16.0.0/12'));

        // IP đơn không có mask
        $this->assertTrue(Ips::ipInRange('127.0.0.1', '127.0.0.1'));
        $this->assertFalse(Ips::ipInRange('127.0.0.2', '127.0.0.1'));

        // IPv6
        $this->assertTrue(Ips::ipInRange('2400:cb00::1', '2400:cb00::/32'));
        $this->assertFalse(Ips::ipInRange('2400:cc00::1', '2400:cb00::/32'));
        $this->assertTrue(Ips::ipInRange('::1', '::1'));

        // Không so khớp lẫn giữa hai họ địa chỉ
        $this->assertFalse(Ips::ipInRange('10.1.2.3', '2400:cb00::/32'));
        $this->assertFalse(Ips::ipInRange('2400:cb00::1', '10.0.0.0/8'));

        // Đầu vào không hợp lệ
        $this->assertFalse(Ips::ipInRange('10.1.2.3', ''));
        $this->assertFalse(Ips::ipInRange('10.1.2.3', '10.0.0.0/abc'));
        $this->assertFalse(Ips::ipInRange('10.1.2.3', '10.0.0.0/33'));
        $this->assertFalse(Ips::ipInRange('not-an-ip', '10.0.0.0/8'));
    }
}
