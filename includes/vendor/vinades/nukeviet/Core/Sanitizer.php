<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

use NukeViet\Site;

/**
 * NukeViet\Core\Sanitizer
 * Class dùng để lọc mã HTML tải về trước khi lưu CSDL
 *
 * $sanitizer = new NukeViet\Core\Sanitizer();
 * $contents = $sanitizer->crawlContentClean($contents, true);
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class Sanitizer
{
    /**
     * Các thẻ html được chấp nhận
     */
    private $allowedTags = [
        'a', 'article', 'aside', 'audio', 'b', 'blockquote', 'br', 'caption', 'code',
        'col', 'colgroup', 'dd', 'del', 'details', 'div', 'dl', 'dt', 'em', 'figcaption',
        'figure', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'hr', 'i', 'iframe',
        'img', 'li', 'main', 'nav', 'ol', 'p', 'picture', 'pre', 's', 'section', 'small',
        'source', 'span', 'strong', 'sub', 'sup', 'table', 'tbody', 'td', 'tfoot', 'th',
        'thead', 'tr', 'track', 'u', 'ul', 'video'
    ];

    /**
     * Các thuộc tính của thẻ html được chấp nhận
     */
    private $allowedTagsAttributes = [
        'a' => ['href', 'target'],
        'audio' => ['autoplay', 'controls', 'loop', 'muted', 'preload', 'src'],
        'blockquote' => ['cite'],
        'col' => ['span'],
        'colgroup' => ['span'],
        'del' => ['cite', 'datetime'],
        'details' => ['open'],
        'iframe' => ['name', 'src'],
        'img' => ['alt', 'src'],
        'li' => ['value'],
        'source' => ['media', 'src', 'srcset', 'type'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan'],
        'video' => ['autoplay', 'controls', 'loop', 'muted', 'poster', 'preload', 'src']
    ];

    /**
     * Các thuộc tính toàn cục được chấp nhận
     */
    private $allowedGlobalAttributes = [
        'class',
        'id',
        'title'
    ];

    /**
     * Các mã lệnh bị cấm
     */
    private $disableCommands = [
        'base64_decode',
        'cmd',
        'passthru',
        'eval',
        'exec',
        'system',
        'fopen',
        'fsockopen',
        'file',
        'file_get_contents',
        'readfile',
        'unlink'
    ];

    /**
     * __construct()
     */
    public function __construct()
    {
    }

    /**
     * setAllowedTags()
     *
     * @param array $tags
     */
    public function setAllowedTags($tags)
    {
        $this->allowedTags = $tags;
    }

    /**
     * getAllowedTags()
     *
     * @return array
     */
    public function getAllowedTags()
    {
        return $this->allowedTags;
    }

    /**
     * setAllowedTagsAttributes()
     *
     * @param array $tagsAttributes
     */
    public function setAllowedTagsAttributes($tagsAttributes)
    {
        $this->allowedTagsAttributes = $tagsAttributes;
    }

    /**
     * getAllowedTagsAttributes()
     *
     * @return array
     */
    public function getAllowedTagsAttributes()
    {
        return $this->allowedTagsAttributes;
    }

    /**
     * setAllowedGlobalAttributes()
     *
     * @param array $attributes
     */
    public function setAllowedGlobalAttributes($attributes)
    {
        $this->allowedGlobalAttributes = $attributes;
    }

    /**
     * getAllowedGlobalAttributes()
     *
     * @return array
     */
    public function getAllowedGlobalAttributes()
    {
        return $this->allowedGlobalAttributes;
    }

    /**
     * removeInvisibleContent()
     * Xóa các nội dung ẩn
     *
     * @param string $contents
     * @return string
     */
    public function removeInvisibleContent($contents)
    {
        return preg_replace(
            [
                '@<(head|style|script|object|embed|applet|noframes|noscript|noembed)[^>]*?>.*?</\\1>@siu',
                '@<(meta|link)[^>]*?>@siu'
            ],
            '',
            $contents
        );
    }

    /**
     * filterTags()
     * Lọc các thẻ HTML
     *
     * @param string $contents
     * @return string
     */
    public function filterTags($contents)
    {
        $preTag = null;
        $postTag = $contents;
        $tagOpen_start = strpos($contents, '<');

        while ($tagOpen_start !== false) {
            $preTag .= substr($postTag, 0, $tagOpen_start);
            $postTag = substr($postTag, $tagOpen_start);
            $fromTagOpen = substr($postTag, 1);
            $tagOpen_end = strpos($fromTagOpen, '>');

            if ($tagOpen_end === false) {
                break;
            }

            $tagOpen_nested = strpos($fromTagOpen, '<');

            if (($tagOpen_nested !== false) and ($tagOpen_nested < $tagOpen_end)) {
                $preTag .= substr($postTag, 0, ($tagOpen_nested + 1));
                $postTag = substr($postTag, ($tagOpen_nested + 1));
                $tagOpen_start = strpos($postTag, '<');
                continue;
            }

            $tagOpen_nested = (strpos($fromTagOpen, '<') + $tagOpen_start + 1);
            $currentTag = substr($fromTagOpen, 0, $tagOpen_end);
            $tagLength = strlen($currentTag);

            if (!$tagOpen_end) {
                $preTag .= $postTag;
                $tagOpen_start = strpos($postTag, '<');
            }

            $tagLeft = $currentTag;
            $attrSet = [];
            $currentSpace = strpos($tagLeft, ' ');

            if (substr($currentTag, 0, 1) == '/') {
                $isCloseTag = true;
                [$tagName] = explode(' ', $currentTag);
                $tagName = strtolower(substr($tagName, 1));
            } else {
                $isCloseTag = false;
                [$tagName] = explode(' ', $currentTag);
                $tagName = strtolower($tagName);
            }

            if ((!preg_match('/^[a-z][a-z0-9]*$/i', $tagName)) or !in_array($tagName, $this->allowedTags, true)) {
                $postTag = substr($postTag, ($tagLength + 2));
                $tagOpen_start = strpos($postTag, '<');
                continue;
            }

            while ($currentSpace !== false) {
                $fromSpace = substr($tagLeft, ($currentSpace + 1));
                $nextSpace = strpos($fromSpace, ' ');
                $openQuotes = strpos($fromSpace, '"');
                $closeQuotes = strpos(substr($fromSpace, ($openQuotes + 1)), '"') + $openQuotes + 1;

                if (str_contains($fromSpace, '=')) {
                    if (($openQuotes !== false) and (str_contains(substr($fromSpace, ($openQuotes + 1)), '"'))) {
                        $attr = substr($fromSpace, 0, ($closeQuotes + 1));
                    } else {
                        $attr = substr($fromSpace, 0, $nextSpace);
                    }
                } else {
                    $attr = substr($fromSpace, 0, $nextSpace);
                }

                if (!$attr) {
                    $attr = $fromSpace;
                }

                $attrSet[] = $attr;
                $tagLeft = substr($fromSpace, strlen($attr));
                $currentSpace = strpos($tagLeft, ' ');
            }

            if (!$isCloseTag) {
                if (!empty($attrSet)) {
                    $attrSet = $this->filterAttr($attrSet, $tagName);
                }
                $preTag .= '{@[' . $tagName;
                if (!empty($attrSet)) {
                    $preTag .= ' ' . implode(' ', $attrSet);
                }
                $preTag .= (strpos($fromTagOpen, '</' . $tagName)) ? ']@}' : ' /]@}';
            } else {
                $preTag .= '{@[/' . $tagName . ']@}';
            }

            $postTag = substr($postTag, ($tagLength + 2));
            $tagOpen_start = strpos($postTag, '<');
        }

        $contents = $preTag . $postTag;
        while (preg_match('/\<script([^\>]*)\>(.*)\<\/script\>/isU', $contents)) {
            $contents = preg_replace('/\<script([^\>]*)\>(.*)\<\/script\>/isU', '', $contents);
        }

        $contents = str_replace(["'", '"', '<', '>'], ['&#039;', '&quot;', '&lt;', '&gt;'], $contents);

        return trim(str_replace(['[@{', '}@]', '{@[', ']@}'], ['"', '"', '<', '>'], $contents));
    }

    /**
     * filterAttr()
     * Lọc các thuộc tính
     *
     * @param array $attrSet
     * @param mixed $tagName
     * @return array
     */
    private function filterAttr($attrSet, $tagName)
    {
        if (isset($this->allowedTagsAttributes[$tagName])) {
            $allowedAttributes = array_merge($this->allowedGlobalAttributes, $this->allowedTagsAttributes[$tagName]);
        } else {
            $allowedAttributes = $this->allowedGlobalAttributes;
        }

        $newSet = [];
        $count = sizeof($attrSet);

        for ($i = 0; $i < $count; ++$i) {
            if (!$attrSet[$i]) {
                continue;
            }
            [$attrName, $attrContent] = array_map('trim', explode('=', trim($attrSet[$i]), 2));

            $attrName = strtolower($attrName);
            if (!preg_match('/[a-z][a-z0-9\-]+/i', $attrName) or !in_array($attrName, $allowedAttributes, true) or preg_match('/^on/i', $attrName)) {
                continue;
            }

            if (!empty($attrContent)) {
                $attrContent = preg_replace('/[ ]+/', ' ', $attrContent);
                $attrContent = preg_replace('/^"(.*)"$/', '\\1', $attrContent);
                $attrContent = preg_replace("/^\'(.*)\'$/", '\\1', $attrContent);
                $attrContent = str_replace(['"', '&quot;'], "'", $attrContent);

                if (!$this->xssValid($attrContent)) {
                    continue;
                }
            } elseif ($attrContent !== '0') {
                $attrContent = $attrName;
            }
            $newSet[] = $attrName . '=[@{' . $attrContent . '}@]';
        }

        return $newSet;
    }

    /**
     * xssValid()
     *
     * @param string $value
     * @return bool
     */
    public function xssValid($value)
    {
        $value = Site::unhtmlentities($value);

        $search = [
            'expression' => '/e\s*x\s*p\s*r\s*e\s*s\s*s\s*i\s*o\s*n/si',
            'javascript' => '/j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t/si',
            'livescript' => '/l\s*i\s*v\s*e\s*s\s*c\s*r\s*i\s*p\s*t/si',
            'behavior' => '/b\s*e\s*h\s*a\s*v\s*i\s*o\s*r/si',
            'vbscript' => '/v\s*b\s*s\s*c\s*r\s*i\s*p\s*t/si',
            'script' => '/s\s*c\s*r\s*i\s*p\s*t/si',
            'applet' => '/a\s*p\s*p\s*l\s*e\s*t/si',
            'alert' => '/a\s*l\s*e\s*r\s*t/si',
            'document' => '/d\s*o\s*c\s*u\s*m\s*e\s*n\s*t/si',
            'write' => '/w\s*r\s*i\s*t\s*e/si',
            'cookie' => '/c\s*o\s*o\s*k\s*i\s*e/si',
            'window' => '/w\s*i\s*n\s*d\s*o\s*w/si',
            'data:' => '/d\s*a\s*t\s*a\s*\:/si'
        ];
        $value = preg_replace(array_values($search), array_keys($search), $value);

        if (preg_match('/(expression|javascript|behavior|vbscript|mocha|livescript)(\:*)/', $value)) {
            return false;
        }

        if (strcasecmp($value, strip_tags($value)) !== 0) {
            return false;
        }

        return !(!empty($this->disableCommands) and preg_match('#(' . implode('|', $this->disableCommands) . ')(\s*)\((.*?)\)#si', $value));
    }

    /**
     * removeWhitespaces()
     * Xóa khoảng trắng
     *
     * @param string $contents
     * @return string
     */
    public function removeWhitespaces($contents)
    {
        $contents = preg_replace(['/^((?:&nbsp;|\s)+)|(?1)$/', '/\s*&nbsp;(?:\s*&nbsp;)*\s*/', '/\s+/'], ['', '', ' '], trim($contents));
        $contents = preg_replace('/\<\/?br(\s*)?\/?(\s*)?\>/i', ' ', $contents);

        return preg_replace([
            '/>[^\S ]+/s',
            '/[^\S ]+</s',
            '/>[\s\n\r\t]+</',
        ], [
            '>',
            '<',
            '><',
        ], $contents);
    }

    /**
     * repairString()
     * Sửa lỗi
     *
     * @param string $contents
     * @return string
     */
    public function repairString($contents)
    {
        if (Site::class_exists('tidy')) {
            $tidy = new \Tidy();
            $contents = $tidy->repairString($contents, [
                'clean' => true,
                'indent' => false,
                'indent-attributes' => false,
                'show-body-only' => true,
                'wrap' => 0
            ], 'utf8');
        } else {
            $contents = mb_convert_encoding($contents, 'HTML-ENTITIES', 'UTF-8');
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->loadHTML($contents, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $dom->formatOutput = true;
            $contents = $dom->saveHTML();
            $contents = mb_convert_encoding($contents, 'UTF-8', 'HTML-ENTITIES');
        }

        return $contents;
    }

    /**
     * minifyString()
     *
     * @param string $contents
     * @return string
     */
    public function minifyString($contents)
    {
        return str_replace(["\r", "\n", "\r\n"], '', $contents);
    }

    /**
     * crawlContentClean()
     *
     * @param string $contents
     * @param bool   $isMinify
     * @return string
     */
    public function crawlContentClean($contents, $isMinify = false)
    {
        $contents = preg_replace('/^(.*?)<body[^>]*?>(.*?)<\/body>(.*?)$/si', '\\2', $contents);

        $contents = $this->removeInvisibleContent($contents);
        $contents = $this->filterTags($contents);
        $contents = $this->removeWhitespaces($contents);
        $contents = $this->repairString($contents);

        if ($isMinify) {
            $contents = $this->minifyString($contents);
        }

        return $contents;
    }
}
