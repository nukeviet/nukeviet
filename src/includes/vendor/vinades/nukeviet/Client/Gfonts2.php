<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Client;

use NukeViet\Http\Http;

/**
 * NukeViet\Client\Gfonts2
 * Google Fonts version 2. Font không phụ thuộc vào trình duyệt
 *
 * @package NukeViet\Client
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2024 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class Gfonts2
{
    /**
     * @var string Thư mục chứa tệp CSS
     */
    private $cssdir = 'themes/default/css';

    /**
     * @var string thư mục chứa tệp font
     */
    private $fontdir = 'themes/default/webfonts';

    private $fontsPrefix = 'nvgfont2.';
    private $relfontdir = '../webfonts';

    private $fonts;
    private $cssRealFile;
    private $cssUrlFile;
    private $theme;

    /**
     * @param string $theme
     * @param array $gfonts
     */
    public function __construct(string $theme, array $gfonts = [])
    {
        global $global_config;

        $this->cssdir = 'themes/' . $theme . '/css';
        $this->fontdir = 'themes/' . $theme . '/webfonts';
        $this->theme = $theme;

        $this->fontsPrefix = 'nv.gfont2.' . ($global_config['idsite'] ?? 0) . '.';

        if (!empty($gfonts)) {
            $this->setFont($gfonts);
        }
    }

    /**
     * Thiết đặt font
     *
     * @param array $gfonts Array (
     *      [styles] => Array (
     *          ...
     *          [300] => Array (
     *              [n] => 1
     *              [i] => 1
     *          ),
     *          [400] => Array (
     *              [n] => 1
     *              [i] => 1
     *          ),
     *          ...
     *      )
     *      [family] => Roboto
     *  )
     * @throws \Exception
     * @return \NukeViet\Client\Gfonts2
     */
    public function setFont(array $gfonts)
    {
        if (!isset($gfonts['styles']) or !isset($gfonts['family'])) {
            throw new \Exception('Wrong parameters!');
        }

        $axixs = [];
        foreach ($gfonts['styles'] as $wght => $conf) {
            !empty($conf['n']) && ($axixs[] = '0,' . $wght);
            !empty($conf['i']) && ($axixs[] = '1,' . $wght);
        }
        asort($axixs);

        $this->fonts = 'https://fonts.googleapis.com/css2?family=' . urlencode($gfonts['family']) . ':ital,wght@' . implode(';', $axixs) . '&display=swap';

        $cssFile = $this->fontsPrefix . strtolower(urlencode($gfonts['family'])) . '.css';
        $this->cssRealFile = NV_ROOTDIR . '/' . $this->cssdir . '/' . $cssFile;
        $this->cssUrlFile = NV_BASE_SITEURL . $this->cssdir . '/' . $cssFile;

        return $this;
    }

    /**
     * @param array $gfonts
     * @return number
     */
    public function save(array $gfonts)
    {
        global $global_config;

        $this->destroyAll()->setFont($gfonts);

        $NV_Http = new Http($global_config, NV_TEMP_DIR);
        $args = [
            'headers' => [
                'Referer' => NV_MY_DOMAIN,
                'User-Agent' => NV_USER_AGENT,
            ],
            'httpversion' => '1.1'
        ];

        $result = $NV_Http->get($this->fonts, $args);

        // Lỗi trong thư viện
        if (!empty(Http::$error)) {
            return Http::$error['code'];
        }

        // Lỗi Google trả về
        if ($result['response']['code'] != 200) {
            return $result['response']['code'];
        }

        $result = $result['body'];

        // Tải từng tệp font
        $regex = '/https\:\/\/[^\) ]+\/([^\.\) ]+\.[^\) ]+)/';
        if (preg_match_all($regex, $result, $matches)) {
            $result = preg_replace_callback($regex, [$this, 'saveFont'], $result);
        }

        file_put_contents($this->cssRealFile, $result, LOCK_EX);
        return 0;
    }

    /**
     * Đường dẫn tương đối của tệp fonts
     *
     * @return string
     */
    public function getLink()
    {
        return $this->cssUrlFile;
    }

    /**
     * Xóa toàn bộ font đã thiết lập
     *
     * @return \NukeViet\Client\Gfonts2
     */
    public function destroyAll()
    {
        $cssFiles = scandir(NV_ROOTDIR . '/' . $this->cssdir);
        foreach ($cssFiles as $cssFile) {
            if ($cssFile != '.' and $cssFile != '..' and preg_match('/^' . preg_quote($this->fontsPrefix, '/') . '/', $cssFile)) {
                $this->destroyFont($cssFile);
            }
        }
        return $this;
    }

    /**
     * @param string $cssFile
     */
    private function destroyFont($cssFile)
    {
        $cssContent = file_get_contents(NV_ROOTDIR . '/' . $this->cssdir . '/' . $cssFile);
        preg_match_all('/url[\s]*\([\s]*(["\']*)' . preg_quote($this->relfontdir, '/') . '\/([a-zA-Z0-9\-\_\.]+)(["\']*)[\s]*\)/i', $cssContent, $m);
        if (!empty($m[2])) {
            foreach ($m[2] as $fileName) {
                $filePath = NV_ROOTDIR . '/' . $this->fontdir . '/' . $fileName;
                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }
        }
        unlink(NV_ROOTDIR . '/' . $this->cssdir . '/' . $cssFile);
    }

    /**
     * Lấy font trong tệp css về
     *
     * @param array $matches
     * @return string
     */
    private function saveFont($matches)
    {
        $dir = NV_ROOTDIR . '/' . $this->fontdir;
        $file = $this->fontsPrefix . $matches[1];

        if (file_exists($dir . '/' . $file)) {
            return $this->relfontdir . '/' . $file;
        }
        if ($this->downloadFont($matches[0], $dir, $file)) {
            return $this->relfontdir . '/' . $file;
        }

        return $matches[0];
    }

    /**
     * @param string $url
     * @param string $dir
     * @param string $filename
     * @return boolean
     */
    private function downloadFont($url, $dir, $filename)
    {
        global $global_config;

        $NV_Http = new Http($global_config, NV_TEMP_DIR);
        $args = [
            'headers' => [
                'Referer' => NV_MY_DOMAIN,
                'User-Agent' => NV_USER_AGENT,
            ],
            'stream' => true,
            'filename' => $dir . '/' . $filename,
            'httpversion' => '1.1'
        ];
        $result = $NV_Http->get($url, $args);

        return !(!empty(Http::$error) or $result['response']['code'] != 200 or empty($result['filename']) or !file_exists($result['filename']) or filesize($result['filename']) <= 0);
    }
}
