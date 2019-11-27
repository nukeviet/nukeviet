<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/9/2010, 3:21
 */

namespace NukeViet\Seo;

/**
 * @author VINADES.,JSC <contact@vinades.vn>
 *
 * @since 4.3.08
 *
 */
class BotManager
{
    private $privateWebsite = 0;
    private $headerBotName = 'robots';
    private $allBots = ['robots', 'googlebot', 'msnbot'];
    private $allBotsModes = [
        'robots' => ['all', 'none', 'index', 'noindex', 'follow', 'nofollow', 'archive', 'noarchive'],
        'googlebot' => ['all', 'none', 'index', 'noindex', 'follow', 'nofollow', 'archive', 'noarchive', 'noimageindex', 'nosnippet', 'notranslate', 'unavailable_after'],
        'msnbot' => ['all', 'none', 'index', 'noindex', 'follow', 'nofollow', 'archive', 'noarchive'],
    ];

    /**
     * @var array
     *
     * Mặc định các công cụ tìm kiếm đều cho phép index, follow
     * do đó mặc định website không chỉ ra thẻ meta robot trừ khi
     * có cấu hình chặn trong quản trị
     */
    private $defaultModes = [];
    private $modes = [];

    /**
     * @param number $private_website
     */
    public function __construct($private_website = 0)
    {
        $this->privateWebsite = $private_website;
        $this->reset();
    }

    /**
     * @return \NukeViet\Seo\BotManager
     */
    public function reset()
    {
        $this->modes = $this->defaultModes;

        if ($this->privateWebsite) {
            $this->setPrivate();
        }
        return $this;
    }

    /**
     * @return \NukeViet\Seo\BotManager
     */
    public function setAll()
    {
        if (!$this->privateWebsite) {
            $this->modes = [
                'all' => 'all'
            ];
        }
        return $this;
    }

    /**
     * Chặn lập chỉ mục theo cấu hình => Cho phép follow các liên kết
     * tuy nhiên không đánh chỉ mục
     *
     * @return \NukeViet\Seo\BotManager
     */
    public function setPrivate()
    {
        $this->modes = [
            'noindex' => 'noindex',
            'follow' => 'follow'
        ];
        return $this;
    }

    /**
     * @return \NukeViet\Seo\BotManager
     */
    public function setNone()
    {
        if (!$this->privateWebsite) {
            $this->modes = [
                'none' => 'none'
            ];
        }
        return $this;
    }

    /**
     * @return \NukeViet\Seo\BotManager
     */
    public function setIndex()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['all'], $this->modes['none'], $this->modes['noindex']);
            $this->modes['index'] = 'index';
        }
        return $this;
    }

    /**
     * @return \NukeViet\Seo\BotManager
     */
    public function setNoIndex()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['all'], $this->modes['none'], $this->modes['index']);
            $this->modes['noindex'] = 'noindex';
        }
        return $this;
    }

    /**
     * Google BOT only
     *
     * @return \NukeViet\Seo\BotManager
     */
    public function setImageIndex()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['noimageindex']);
        }
        return $this;
    }

    /**
     * Google BOT only
     *
     * @return \NukeViet\Seo\BotManager
     */
    public function setNoImageIndex()
    {
        if (!$this->privateWebsite) {
            $this->modes['noimageindex'] = 'noimageindex';
        }
        return $this;
    }

    /**
     * @return \NukeViet\Seo\BotManager
     */
    public function setFollow()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['all'], $this->modes['none'], $this->modes['nofollow']);
            $this->modes['follow'] = 'follow';
        }
        return $this;
    }

    /**
     * @return \NukeViet\Seo\BotManager
     */
    public function setNoFollow()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['all'], $this->modes['none'], $this->modes['follow']);
            $this->modes['nofollow'] = 'nofollow';
        }
        return $this;
    }

    /**
     * @return \NukeViet\Seo\BotManager
     */
    public function setArchive()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['noarchive']);
            $this->modes['archive'] = 'archive';
        }
        return $this;
    }

    /**
     * @return \NukeViet\Seo\BotManager
     */
    public function setNoArchive()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['archive']);
            $this->modes['noarchive'] = 'noarchive';
        }
        return $this;
    }

    /**
     * Google BOT only
     *
     * @return \NukeViet\Seo\BotManager
     */
    public function setSnippet()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['nosnippet']);
        }
        return $this;
    }

    /**
     * Google BOT only
     *
     * @return \NukeViet\Seo\BotManager
     */
    public function setNoSnippet()
    {
        if (!$this->privateWebsite) {
            $this->modes['nosnippet'] = 'nosnippet';
        }
        return $this;
    }

    /**
     * Google BOT only
     *
     * @return \NukeViet\Seo\BotManager
     */
    public function setTranslate()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['notranslate']);
        }
        return $this;
    }

    /**
     * Google BOT only
     *
     * @return \NukeViet\Seo\BotManager
     */
    public function setNoTranslate()
    {
        if (!$this->privateWebsite) {
            $this->modes['notranslate'] = 'notranslate';
        }
        return $this;
    }

    /**
     * Google BOT only
     *
     * @param int $time
     * @return \NukeViet\Seo\BotManager
     */
    public function setUnavailableAfter($time)
    {
        if (!$this->privateWebsite) {
            // RFC 850 date
            $this->modes['unavailable_after'] = 'unavailable_after: ' . gmdate('d-M-y H:i:s', $time) . ' GMT';
        }
        return $this;
    }

    /**
     * Google BOT only
     *
     * @return \NukeViet\Seo\BotManager
     */
    public function removeUnavailableAfter()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['unavailable_after']);
        }
        return $this;
    }

    /**
     * @param boolean $html
     * @return string[][]
     */
    public function getMetaTags($html = false)
    {
        $return = [];
        foreach ($this->allBots as $botname) {
            $mode = array_intersect_key($this->modes, array_flip($this->allBotsModes[$botname]));
            if (!empty($mode)) {
                $return[] = [
                    'name' => 'name',
                    'value' => $botname,
                    'content' => implode(', ', $mode)
                ];
            }
        }
        if (!$html) {
            return $return;
        }
        $res = '';
        foreach ($return as $link) {
            $res .= "<meta " . $link['name'] . "=\"" . $link['value'] . "\" content=\"" . $link['content'] . "\" />" . PHP_EOL;
        }
        return $res;
    }

    /**
     * @param array $headers
     * @param array $sys_info
     */
    public function outputToHeaders(&$headers, &$sys_info)
    {
        $mode = array_intersect_key($this->modes, array_flip($this->allBotsModes[$this->headerBotName]));
        if (!empty($mode)) {
            unset($sys_info['server_headers']['x-robots-tag']);
            $headers['X-Robots-Tag'] = implode(', ', $mode);
        }
    }

    /**
     *
     */
    public function printToHeaders()
    {
        $mode = array_intersect_key($this->modes, array_flip($this->allBotsModes[$this->headerBotName]));
        if (!empty($mode)) {
            @Header('X-Robots-Tag: ' . implode(', ', $mode));
        }
    }
}
