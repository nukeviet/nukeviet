<?php

/**
 * Seeder — PicsumImageDownloader
 *
 * Tải ảnh từ picsum.photos (deterministic theo seed-key) + cache theo path
 * `uploads/news/<catalias>/<seed>_<W>x<H>.jpg`. Tránh hammer picsum.photos
 * bằng cách check file exists trước khi tải.
 *
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 */

declare(strict_types=1);

namespace Seeder;

use RuntimeException;

class PicsumImageDownloader
{
    private const PICSUM_BASE_URL = 'https://picsum.photos/seed';
    private const TIMEOUT_SECONDS = 15;
    private const USER_AGENT = 'NV5-Seeder/1.0 (+nukeviet.vn)';

    private string $uploadsDir;
    private bool $forceRedownload;

    /** @var array<string,bool> Track file đã verify trong run này (tránh stat trùng) */
    private array $verifiedThisRun = [];

    /**
     * @param string $uploadsDir       Đường dẫn tuyệt đối tới NV_ROOTDIR/uploads
     * @param bool   $forceRedownload  Nếu true, luôn tải lại bỏ qua cache
     */
    public function __construct(string $uploadsDir, bool $forceRedownload = false)
    {
        $this->uploadsDir      = rtrim($uploadsDir, '/\\');
        $this->forceRedownload = $forceRedownload;
    }

    /**
     * Tải ảnh + cache.
     *
     * @param string $seed       Stable key (vd 'thoisu-1') — picsum dùng làm hash
     * @param int    $width
     * @param int    $height
     * @param string $subDir     Subdir trong uploads (vd 'news/thoi-su')
     * @return string|null       Relative path từ NV_ROOTDIR (vd 'uploads/news/thoi-su/thoisu-1_1200x630.jpg'), null nếu fail
     */
    public function fetch(string $seed, int $width, int $height, string $subDir = 'news'): ?string
    {
        // Validate seed: chỉ a-z0-9- (chống path traversal)
        if (!preg_match('/^[a-z0-9-]+$/i', $seed)) {
            return null;
        }
        if ($width < 1 || $height < 1 || $width > 4000 || $height > 4000) {
            return null;
        }

        $subDir = trim($subDir, '/\\');
        // Validate subDir: chỉ a-z0-9_-/ (đảm bảo không có ../ hay ký tự lạ).
        // Cho phép '_' để hỗ trợ format Y_m (vd 'news/2026_05').
        if (!preg_match('#^[a-z0-9_-]+(/[a-z0-9_-]+)*$#i', $subDir)) {
            return null;
        }

        $absDir = $this->uploadsDir . '/' . $subDir;
        if (!is_dir($absDir)) {
            if (!@mkdir($absDir, 0755, true) && !is_dir($absDir)) {
                return null;
            }
        }

        $filename = $seed . '_' . $width . 'x' . $height . '.jpg';
        $absPath  = $absDir . '/' . $filename;
        $relPath  = 'uploads/' . $subDir . '/' . $filename;

        // Cache hit
        if (!$this->forceRedownload && isset($this->verifiedThisRun[$absPath])) {
            return $relPath;
        }
        if (!$this->forceRedownload && is_file($absPath) && filesize($absPath) > 1024) {
            $this->verifiedThisRun[$absPath] = true;
            return $relPath;
        }

        // Download
        $url = self::PICSUM_BASE_URL . '/' . rawurlencode($seed) . '/' . $width . '/' . $height;
        $imageData = $this->httpGet($url);
        if ($imageData === null || strlen($imageData) < 1024) {
            return null;
        }

        if (file_put_contents($absPath, $imageData) === false) {
            return null;
        }

        $this->verifiedThisRun[$absPath] = true;
        return $relPath;
    }

    /**
     * GET request đơn giản. Ưu tiên cURL nếu có, fallback file_get_contents với stream context.
     */
    private function httpGet(string $url): ?string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) return null;
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 5,
                CURLOPT_CONNECTTIMEOUT => self::TIMEOUT_SECONDS,
                CURLOPT_TIMEOUT        => self::TIMEOUT_SECONDS,
                CURLOPT_USERAGENT      => self::USER_AGENT,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ]);
            $body = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);

            if ($code === 200 && is_string($body) && $body !== '') {
                return $body;
            }
            return null;
        }

        // Fallback file_get_contents
        $ctx = stream_context_create([
            'http' => [
                'method'        => 'GET',
                'timeout'       => self::TIMEOUT_SECONDS,
                'user_agent'    => self::USER_AGENT,
                'follow_location' => 1,
                'max_redirects' => 5,
            ],
            'ssl'  => [
                'verify_peer'      => true,
                'verify_peer_name' => true,
            ],
        ]);
        $body = @file_get_contents($url, false, $ctx);
        return $body === false ? null : $body;
    }
}
