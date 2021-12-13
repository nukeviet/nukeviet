<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Seo;

/**
 * NukeViet\Seo\BotManager
 *
 * @package NukeViet\Seo
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @since 4.3.08
 * @access public
 */
class BotManager
{
    private $privateWebsite = 0;
    private $headerBotName = 'robots';
    private $allBots = ['robots', 'googlebot', 'msnbot', 'coccocbot'];
    private $allBotsModes = [
        'robots' => ['all', 'none', 'index', 'noindex', 'follow', 'nofollow', 'archive', 'noarchive'],
        'googlebot' => ['all', 'none', 'index', 'noindex', 'follow', 'nofollow', 'archive', 'noarchive', 'noimageindex', 'nosnippet', 'notranslate', 'unavailable_after'],
        'msnbot' => ['all', 'none', 'index', 'noindex', 'follow', 'nofollow', 'archive', 'noarchive'],
        'coccocbot' => ['all', 'none', 'index', 'noindex', 'follow', 'nofollow', 'archive', 'noarchive'],
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
     * __construct()
     *
     * @param int $private_website
     */
    public function __construct($private_website = 0)
    {
        $this->privateWebsite = $private_website;
        $this->reset();
    }

    /**
     * reset()
     *
     * @return $this
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
     * setAll()
     *
     * @return $this
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
     * setPrivate()
     * Chặn lập chỉ mục theo cấu hình => Cho phép follow các liên kết
     * tuy nhiên không đánh chỉ mục
     *
     * @return $this
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
     * setNone()
     *
     * @return $this
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
     * setIndex()
     *
     * @return $this
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
     * setNoIndex()
     *
     * @return $this
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
     * setImageIndex()
     * Google BOT only
     *
     * @return $this
     */
    public function setImageIndex()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['noimageindex']);
        }

        return $this;
    }

    /**
     * setNoImageIndex()
     * Google BOT only
     *
     * @return $this
     */
    public function setNoImageIndex()
    {
        if (!$this->privateWebsite) {
            $this->modes['noimageindex'] = 'noimageindex';
        }

        return $this;
    }

    /**
     * setFollow()
     *
     * @return $this
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
     * setNoFollow()
     *
     * @return $this
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
     * setArchive()
     *
     * @return $this
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
     * setNoArchive()
     *
     * @return $this
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
     * setSnippet()
     * Google BOT only
     *
     * @return $this
     */
    public function setSnippet()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['nosnippet']);
        }

        return $this;
    }

    /**
     * setNoSnippet()
     * Google BOT only
     *
     * @return $this
     */
    public function setNoSnippet()
    {
        if (!$this->privateWebsite) {
            $this->modes['nosnippet'] = 'nosnippet';
        }

        return $this;
    }

    /**
     * setTranslate()
     * Google BOT only
     *
     * @return $this
     */
    public function setTranslate()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['notranslate']);
        }

        return $this;
    }

    /**
     * setNoTranslate()
     * Google BOT only
     *
     * @return $this
     */
    public function setNoTranslate()
    {
        if (!$this->privateWebsite) {
            $this->modes['notranslate'] = 'notranslate';
        }

        return $this;
    }

    /**
     * setUnavailableAfter()
     * Google BOT only
     *
     * @param mixed $time
     * @return $this
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
     * removeUnavailableAfter()
     * Google BOT only
     *
     * @return $this
     */
    public function removeUnavailableAfter()
    {
        if (!$this->privateWebsite) {
            unset($this->modes['unavailable_after']);
        }

        return $this;
    }

    /**
     * getMetaTags()
     *
     * @param bool $html
     * @return array|string
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
            $res .= '<meta ' . $link['name'] . '="' . $link['value'] . '" content="' . $link['content'] . '" />' . PHP_EOL;
        }

        return $res;
    }

    /**
     * outputToHeaders()
     *
     * @param mixed $headers
     * @param mixed $sys_info
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
     * printToHeaders()
     */
    public function printToHeaders()
    {
        $mode = array_intersect_key($this->modes, array_flip($this->allBotsModes[$this->headerBotName]));
        if (!empty($mode)) {
            @header('X-Robots-Tag: ' . implode(', ', $mode));
        }
    }
}
