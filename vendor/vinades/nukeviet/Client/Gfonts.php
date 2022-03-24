<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Client;

use NukeViet\Http\Http;

/**
 * NukeViet\Client\Gfonts
 *
 * @package NukeViet\Client
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Gfonts
{
    private $fontsPrefix = 'nvgfont.';
    private $cssdir = 'assets/css';
    private $fontdir = 'assets/fonts';
    private $relfontdir = '../fonts';
    private $fontsLang = '';
    private $fonts;
    private $cssRealFile;
    private $cssUrlFile;

    /**
     * __construct()
     *
     * @param array $gfonts
     * @param array $client_info
     */
    public function __construct($gfonts = [], $client_info = [])
    {
        $this->cssdir = NV_ASSETS_DIR . '/css';
        $this->fontdir = NV_ASSETS_DIR . '/fonts';
        $stringFonts = '';

        if (!empty($gfonts)) {
            $this->fontsLang = !empty($gfonts['subset']) ? preg_replace('/[^a-z0-9\,\-]/i', '', strtolower($gfonts['subset'])) : '';
            $stringFonts = $this->stringFonts($gfonts['fonts']);
            $this->fonts = 'family=' . $stringFonts;
            $stringFonts = str_replace(':', '.', $stringFonts);
            if (!empty($this->fontsLang)) {
                $this->fonts .= '&subset=' . $this->fontsLang;
                $stringFonts .= '.' . $this->fontsLang;
            }
            $this->fonts = 'http://fonts.googleapis.com/css?' . $this->fonts;
            $stringFonts = preg_replace('/[^a-z0-9\.]/i', '', $stringFonts);
        }

        if (!empty($client_info)) {
            $cssFile = $this->fontsPrefix . strtolower($stringFonts . '.' . $client_info['browser']['key'] . $client_info['browser']['version']) . '.css';
            $this->cssRealFile = NV_ROOTDIR . '/' . $this->cssdir . '/' . $cssFile;
            $this->cssUrlFile = NV_BASE_SITEURL . $this->cssdir . '/' . $cssFile;
        }
    }

    /**
     * getUrlCss()
     *
     * @return mixed
     */
    public function getUrlCss()
    {
        if (empty($this->fonts)) {
            return '';
        }
        if (file_exists($this->cssRealFile)) {
            return $this->cssUrlFile;
        }

        return $this->addfile();
    }

    /**
     * getDataCss()
     *
     * @return mixed
     */
    public function getDataCss()
    {
        if (empty($this->fonts)) {
            return '';
        }
        if (file_exists($this->cssRealFile)) {
            return file_get_contents($this->cssRealFile);
        }

        return $this->addfile(true);
    }

    /**
     * downloadFont()
     *
     * @param string $url
     * @param string $dir
     * @param string $filename
     * @return bool
     */
    private function downloadFont($url, $dir, $filename)
    {
        global $global_config, $client_info;

        $NV_Http = new Http($global_config, NV_TEMP_DIR);
        $args = [
            'headers' => [
                'Referer' => $client_info['selfurl'],
                'User-Agent' => NV_USER_AGENT,
            ],
            'stream' => true,
            'filename' => $dir . '/' . $filename,
            'timeout' => 0
        ];
        $result = $NV_Http->get($url, $args);

        if (!empty(Http::$error) or $result['response']['code'] != 200 or empty($result['filename']) or !file_exists($result['filename']) or filesize($result['filename']) <= 0) {
            return false;
        }

        return true;
    }

    /**
     * download_Callback()
     *
     * @param array $matches
     * @return string
     */
    private function download_Callback($matches)
    {
        $dir = NV_ROOTDIR . '/' . $this->fontdir;
        if (file_exists($dir . '/' . $matches[1])) {
            return $this->relfontdir . '/' . $matches[1];
        }
        if ($this->downloadFont($matches[0], $dir, $matches[1])) {
            return $this->relfontdir . '/' . $matches[1];
        }

        return $matches[0];
    }

    /**
     * addfile()
     *
     * @param bool $data
     * @return string
     */
    private function addfile($data = false)
    {
        global $global_config, $client_info;

        $NV_Http = new Http($global_config, NV_TEMP_DIR);
        $args = [
            'headers' => [
                'Referer' => $client_info['selfurl'],
                'User-Agent' => NV_USER_AGENT,
            ]
        ];

        $result = $NV_Http->get($this->fonts, $args);
        if (!empty(Http::$error) or $result['response']['code'] != 200) {
            return '';
        }

        $result = $result['body'];
        $Regex = '/http\:\/\/[^\) ]+\/([^\.\) ]+\.[^\) ]+)/';

        if (preg_match_all($Regex, $result, $matches)) {
            $result = preg_replace_callback($Regex, [$this, 'download_Callback'], $result);
        }

        @file_put_contents($this->cssRealFile, $result);

        return $data ? $result : $this->cssUrlFile;
    }

    /**
     * stringFonts()
     *
     * @param array $fonts
     * @return string
     */
    private function stringFonts($fonts)
    {
        if (empty($fonts)) {
            return '';
        }
        $_fonts = [];
        foreach ($fonts as $k => $font) {
            $_fonts[$k] = urlencode($font['family']);
            if (isset($font['styles']) and !empty($font['styles'])) {
                $_fonts[$k] .= ':' . $font['styles'];
            }
        }

        return implode('|', $_fonts);
    }

    /**
     * destroyAll()
     */
    public function destroyAll()
    {
        $cssFiles = scandir(NV_ROOTDIR . '/' . $this->cssdir);
        foreach ($cssFiles as $cssFile) {
            if ($cssFile != '.' and $cssFile != '..' and preg_match('/^' . preg_quote($this->fontsPrefix, '/') . '/', $cssFile)) {
                $this->destroyFont($cssFile);
            }
        }
    }

    /**
     * destroyFont()
     *
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
                    @unlink($filePath);
                }
            }
        }
        @unlink(NV_ROOTDIR . '/' . $this->cssdir . '/' . $cssFile);
    }
}
