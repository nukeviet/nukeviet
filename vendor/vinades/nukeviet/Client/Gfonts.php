<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 30/07/2015 10:00
 */

namespace NukeViet\Client;

use NukeViet\Http\Http;

/**
 * Gfonts
 *
 * @package NukeViet Gfonts
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @version 4.0
 * @access public
 */
class Gfonts
{
    private $fontsPrefix = 'nvgfont.';
    private $cssdir = 'assets/css';
    private $fontdir = 'assets/fonts';
    private $relfontdir = '../fonts';
    private $fontsLang = '';
    private $fonts, $cssRealFile, $cssUrlFile;

    /**
     * Gfonts::__construct()
     *
     * @param mixed $gfonts
     * @param mixed $client_info
     * @return void
     */
    public function __construct($gfonts = array(), $client_info = array())
    {
        $this->cssdir = NV_ASSETS_DIR . '/css';
        $this->fontdir = NV_ASSETS_DIR . '/fonts';
        $stringFonts = '';

        if (!empty($gfonts)) {
            $this->fontsLang = ! empty($gfonts['subset']) ? preg_replace('/[^a-z0-9\,\-]/i', '', strtolower($gfonts['subset'])) : '';
            $stringFonts = $this->stringFonts($gfonts['fonts']);
            $this->fonts = 'family=' . $stringFonts;
            $stringFonts = str_replace(':', '.', $stringFonts);
            if (! empty($this->fontsLang)) {
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
     * Gfonts::getUrlCss()
     *
     * @return
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
     * Gfonts::getDataCss()
     *
     * @return
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
     * Gfonts::downloadFont()
     *
     * @param mixed $url
     * @param mixed $dir
     * @param mixed $filename
     * @return
     */
    private function downloadFont($url, $dir, $filename)
    {
        global $global_config, $client_info;

        $NV_Http = new Http($global_config, NV_TEMP_DIR);
        $args = array(
            'headers' => array(
                'Referer' => $client_info['selfurl'],
                'User-Agent' => NV_USER_AGENT,
            ),
            'stream' => true,
            'filename' => $dir . '/' . $filename,
            'timeout' => 0
        );
        $result = $NV_Http->get($url, $args);

        if (!empty(Http::$error) or $result['response']['code'] != 200 or empty($result['filename']) or !file_exists($result['filename']) or filesize($result['filename']) <= 0) {
            return false;
        }
        return true;
    }

    /**
     * Gfonts::download_Callback()
     *
     * @param mixed $matches
     * @return
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
     * Gfonts::addfile()
     *
     * @param bool $data
     * @return
     */
    private function addfile($data = false)
    {
        global $global_config, $client_info;

        $NV_Http = new Http($global_config, NV_TEMP_DIR);
        $args = array(
            'headers' => array(
                'Referer' => $client_info['selfurl'],
                'User-Agent' => NV_USER_AGENT,
            )
        );

        $result = $NV_Http->get($this->fonts, $args);
        if (!empty(Http::$error) or $result['response']['code'] != 200) {
            return '';
        }

        $result = $result['body'];
        $Regex = '/http\:\/\/[^\) ]+\/([^\.\) ]+\.[^\) ]+)/';

        if (preg_match_all($Regex, $result, $matches)) {
            $result = preg_replace_callback($Regex, array($this, 'download_Callback'), $result);
        }

        @file_put_contents($this->cssRealFile, $result);

        return $data ? $result : $this->cssUrlFile;
    }

    /**
     * Gfonts::stringFonts()
     *
     * @param mixed $fonts
     * @return
     */
    private function stringFonts($fonts)
    {
        if (empty($fonts)) {
            return '';
        }
        $_fonts = array();
        foreach ($fonts as $k => $font) {
            $_fonts[$k] = urlencode($font['family']);
            if (isset($font['styles']) and ! empty($font['styles'])) {
                $_fonts[$k] .= ':' . $font['styles'];
            }
        }

        return implode($_fonts, '|');
    }

    /**
     * Gfonts::destroyAll()
     *
     * @return void
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
     * Gfonts::destroyFont()
     *
     * @param mixed $cssFile
     * @return void
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
