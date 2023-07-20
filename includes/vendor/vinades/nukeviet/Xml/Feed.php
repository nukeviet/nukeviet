<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Xml;

use DOMDocument;
use DOMException;
use NukeViet\Site;

/**
 * NukeViet\Xml\Feed
 *
 * @package NukeViet\Xml
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class Feed
{
    private static $description_max_length = 500;

    /**
     * __construct()
     */
    public function __construct()
    {
    }

    /**
     * parse_description()
     *
     * @param mixed $description
     * @param mixed $rootdir
     * @param mixed $domain
     * @return (false|string)[]
     */
    private static function parse_description($description, $rootdir, $domain)
    {
        $description_image = '';
        empty($description) && $description = '';

        if (!empty($description)) {
            if (preg_match('#<img.+src="([^"]+)"[^>]*>#i', $description, $matches)) {
                if (str_starts_with($matches['1'], $domain)) {
                    $matches['1'] = substr($matches['1'], strlen($domain));
                }
                if (preg_match('/^https?/', $matches['1'])) {
                    $description_image = $matches['1'];
                } elseif (file_exists($rootdir . '/' . $matches['1'])) {
                    $description_image = $matches['1'];
                }
            }

            $description = trim(strip_tags($description));
            $description = Site::unhtmlspecialchars($description);
            $description = preg_replace("/[\r\n]+/", ' ', $description);
            $description = preg_replace("/(\&nbsp\;|\s)+/", ' ', $description);
            if (function_exists('nv_clean60')) {
                $description = nv_clean60($description, self::$description_max_length, false);
            }
        }

        return [$description_image, $description];
    }

    /**
     * get_img_info()
     *
     * @param mixed $image
     * @param mixed $type
     * @param mixed $rootdir
     * @param mixed $domain
     * @return array|false
     */
    private static function get_img_info($image, $type, $rootdir, $domain)
    {
        if (!file_exists($image)) {
            return false;
        }

        $size = getimagesize($image);
        if (empty($size['mime']) or empty($size[0]) or empty($size[1])) {
            return false;
        }

        if (!preg_match('/(gif|jpe?g|png)$/i', $size['mime'])) {
            return false;
        }

        if ($type == 'rss') {
            if ($size[0] > 144 or $size[1] > 400) {
                return false;
            }
        } else {
            if ($size[1] * 2 != $size[0]) {
                return false;
            }
        }

        $count = strlen($rootdir);

        return [
            'src' => $domain . substr($image, $count),
            'width' => $size[0],
            'height' => $size[1]
        ];
    }

    /**
     * make_external_url()
     *
     * @param mixed $url
     * @param mixed $domain
     * @return mixed
     */
    private static function make_external_url($url, $domain)
    {
        if (preg_match('/^https?/', $url)) {
            return $url;
        }

        return $domain . $url;
    }

    /**
     * create_time()
     *
     * @param mixed $time
     * @param mixed $timemode
     * @return string
     */
    private static function create_time($time, $timemode)
    {
        return ($timemode == 'ISO8601') ? date('c', $time) : gmdate('D, j M Y H:i:s', $time) . ' GMT';
    }

    /**
     * create_uuid()
     *
     * @param mixed $link
     * @return string
     */
    private static function create_uuid($link)
    {
        preg_match('/^([a-z0-9]{8})([a-z0-9]{4})([a-z0-9]{4})([a-z0-9]{4})([a-z0-9]{12})$/i', md5($link), $matches);

        return 'urn:uuid:' . $matches[1] . '-' . $matches[2] . '-' . $matches[3] . '-' . $matches[4] . '-' . $matches[5];
    }

    /**
     * content_encoded_create()
     *
     * @param mixed $content_encoded
     * @param mixed $title
     * @param mixed $description
     * @param mixed $link
     * @param mixed $lang
     * @param mixed $charset
     * @param mixed $timemode
     * @return false|string
     * @throws DOMException
     */
    private static function content_encoded_create($content_encoded, $title, $description, $link, $lang, $charset, $timemode)
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $html = $doc->appendChild($doc->createElement('html'));
        $html->setAttribute('lang', $lang);
        $html->setAttribute('prefix', 'op: http://media.facebook.com/op#');
        $head = $html->appendChild($doc->createElement('head'));
        $head_meta = $head->appendChild($doc->createElement('meta'));
        $head_meta->setAttribute('charset', $charset);
        $head_link = $head->appendChild($doc->createElement('link'));
        $head_link->setAttribute('rel', 'canonical');
        $head_link->setAttribute('href', $link);
        $head_meta = $head->appendChild($doc->createElement('meta'));
        $head_meta->setAttribute('property', 'op:markup_version');
        $head_meta->setAttribute('content', 'v1.0');
        $head_meta = $head->appendChild($doc->createElement('meta'));
        $head_meta->setAttribute('property', 'fb:article_style');
        $head_meta->setAttribute('content', $content_encoded['template']);
        $body = $html->appendChild($doc->createElement('body'));
        $body_article = $body->appendChild($doc->createElement('article'));
        $body_article_header = $body_article->appendChild($doc->createElement('header'));
        if (!empty($content_encoded['image'])) {
            $figure = $body_article_header->appendChild($doc->createElement('figure'));
            $img = $figure->appendChild($doc->createElement('img'));
            $img->setAttribute('src', $content_encoded['image']);
            $figure->appendChild($doc->createElement('figcaption', $content_encoded['image_caption']));
        }
        $body_article_header->appendChild($doc->createElement('h1', $title));
        $body_article_header->appendChild($doc->createElement('h2', $description));
        if (!empty($content_encoded['opkicker'])) {
            $opkicker = $body_article_header->appendChild($doc->createElement('h3', $content_encoded['opkicker']));
            $opkicker->setAttribute('class', 'op-kicker');
        }
        if (!empty($content_encoded['pubdate'])) {
            $content_encoded['published'] = self::create_time($content_encoded['pubdate'], $timemode);
            $pubdate = $body_article_header->appendChild($doc->createElement('time', $content_encoded['published_display']));
            $pubdate->setAttribute('class', 'op-published');
            $pubdate->setAttribute('datetime', $content_encoded['published']);
        }
        if (!empty($content_encoded['modifydate'])) {
            $content_encoded['modified'] = self::create_time($content_encoded['modifydate'], $timemode);
            $modifydate = $body_article_header->appendChild($doc->createElement('time', $content_encoded['modified_display']));
            $modifydate->setAttribute('class', 'op-modified');
            $modifydate->setAttribute('datetime', $content_encoded['modified']);
        }

        return $doc->saveXML($doc->documentElement);
    }

    /**
     * rss_create()
     * https://www.rssboard.org/rss-validator/
     *
     * @param mixed $channel_data
     * @param mixed $items_data
     * @param mixed $xsl
     * @param mixed $timemode
     * @return false|string
     * @throws DOMException
     */
    public static function rss_create($channel_data, $items_data, $xsl, $timemode)
    {
        if (empty($channel_data['title']) or empty($channel_data['link']) or empty($channel_data['description'])) {
            return false;
        }

        [, $channel_data['description']] = self::parse_description($channel_data['description'], $channel_data['rootdir'], $channel_data['domain']);

        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->preserveWhiteSpace = false;

        $xml->appendChild($xml->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="' . self::make_external_url($xsl, $channel_data['domain']) . '" media="screen"'));
        $rss = $xml->createElement('rss');
        $rss_node = $xml->appendChild($rss);
        $rss_node->setAttribute('version', '2.0');
        !empty($channel_data['atomlink']) && $rss_node->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');

        $channel = $xml->createElement('channel');
        $channel_node = $rss_node->appendChild($channel);

        if (!empty($channel_data['atomlink'])) {
            $channel_atom_link = $xml->createElement('atom:link');
            $channel_atom_link->setAttribute('href', self::make_external_url($channel_data['atomlink'], $channel_data['domain']));
            $channel_atom_link->setAttribute('rel', 'self');
            //$channel_atom_link->setAttribute('type', 'application/rss+xml');
            $channel_node->appendChild($channel_atom_link);
        }

        // Required channel elements
        $channel_title_node = $channel_node->appendChild($xml->createElement('title'));
        $channel_title_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($channel_data['title'])));
        $channel_node->appendChild($xml->createElement('link', self::make_external_url($channel_data['link'], $channel_data['domain'])));
        $channel_description_node = $channel_node->appendChild($xml->createElement('description'));
        $channel_description_node->appendChild($xml->createCDATASection($channel_data['description']));

        // Optional channel elements
        !empty($channel_data['lang']) && $channel_node->appendChild($xml->createElement('language', $channel_data['lang']));
        if (!empty($channel_data['copyright'])) {
            $channel_copyright_node = $channel_node->appendChild($xml->createElement('copyright'));
            $channel_copyright_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($channel_data['copyright'])));
        }
        !empty($channel_data['docs']) && $channel_node->appendChild($xml->createElement('docs', self::make_external_url($channel_data['docs'], $channel_data['domain'])));
        if (!empty($channel_data['generator'])) {
            $channel_generator_node = $channel_node->appendChild($xml->createElement('generator'));
            $channel_generator_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($channel_data['generator'])));
        }
        if (!empty($channel_data['pubDate'])) {
            $channel_data['pubDate'] = self::create_time($channel_data['pubDate'], $timemode);
            $channel_node->appendChild($xml->createElement('pubDate', $channel_data['pubDate']));
            $channel_node->appendChild($xml->createElement('lastBuildDate', $channel_data['pubDate']));
        }
        if (!empty($channel_data['image'])) {
            if (($img_info = self::get_img_info($channel_data['image'], 'rss', $channel_data['rootdir'], $channel_data['domain'])) !== false) {
                $channel_image_node = $channel_node->appendChild($xml->createElement('image'));
                $channel_image_node->appendChild($xml->createElement('url', $img_info['src']));
                $channel_image_title_node = $channel_image_node->appendChild($xml->createElement('title'));
                $channel_image_title_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($channel_data['title'])));
                $channel_image_node->appendChild($xml->createElement('link', self::make_external_url($channel_data['link'], $channel_data['domain'])));
                $channel_image_node->appendChild($xml->createElement('width', $img_info['width']));
                $channel_image_node->appendChild($xml->createElement('height', $img_info['height']));
            }
        }

        if (!empty($items_data)) {
            foreach ($items_data as $item_data) {
                // All elements of an item are optional,
                // however at least one of title or description must be present
                if (!empty($item_data['title']) or !empty($item_data['description'])) {
                    $item_node = $channel_node->appendChild($xml->createElement('item'));

                    if (!empty($item_data['title'])) {
                        $item_title_node = $item_node->appendChild($xml->createElement('title'));
                        $item_title_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($item_data['title'])));
                    }
                    !empty($item_data['link']) && $item_node->appendChild($xml->createElement('link', self::make_external_url($item_data['link'], $channel_data['domain'])));
                    if (!empty($item_data['guid'])) {
                        $guid_link = $xml->createElement('guid');
                        $guid_link->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($item_data['guid'])));
                        $guid_link->setAttribute('isPermaLink', 'false');
                        $item_node->appendChild($guid_link);
                    }
                    if (!empty($item_data['pubdate'])) {
                        $item_data['pubdate'] = self::create_time($item_data['pubdate'], $timemode);
                        $item_node->appendChild($xml->createElement('pubDate', $item_data['pubdate']));
                    }
                    if (!empty($item_data['author'])) {
                        $item_author_node = $item_node->appendChild($xml->createElement('author'));
                        $item_author_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($item_data['author'])));
                    }
                    if (!empty($item_data['description'])) {
                        [$item_data['description_image'], $item_data['description']] = self::parse_description($item_data['description'], $channel_data['rootdir'], $channel_data['domain']);
                        $item_description_node = $item_node->appendChild($xml->createElement('description'));
                        if (!empty($item_data['description_image'])) {
                            $item_data['description_image'] = self::make_external_url($item_data['description_image'], $channel_data['domain']);
                            $item_data['description_image'] = '<img src="' . $item_data['description_image'] . '" alt="" align="left" border="0" width="120"/>';
                            $item_description_node->appendChild($xml->createCDATASection($item_data['description_image']));
                        }
                        $item_description_node->appendChild($xml->createTextNode($item_data['description']));
                    }

                    if (!empty($item_data['content'])) {
                        $content = self::content_encoded_create($item_data['content'], $item_data['title'], $item_data['description'], self::make_external_url($item_data['link'], $channel_data['domain']), $channel_data['lang'], $channel_data['charset'], $timemode);

                        $item_content_node = $item_node->appendChild($xml->createElement('content:encoded'));
                        $item_content_node->appendChild($xml->createCDATASection($content));
                    }
                }
            }
        }

        $xml->formatOutput = true;

        return $xml->saveXML();
    }

    /**
     * atom_create()
     * https://www.rssboard.org/rss-validator/
     *
     * @param mixed $channel_data
     * @param mixed $items_data
     * @param mixed $xsl
     * @return false|string
     * @throws DOMException
     */
    public static function atom_create($channel_data, $items_data, $xsl)
    {
        if (empty($channel_data['title']) or empty($channel_data['updated'])) {
            return false;
        }
        if (!empty($channel_data['link'])) {
            $channel_data['link'] = self::make_external_url($channel_data['link'], $channel_data['domain']);
        }
        $channel_data['uuid'] = self::create_uuid(!empty($channel_data['link']) ? $channel_data['link'] : $channel_data['title']);
        $channel_data['updated'] = self::create_time($channel_data['updated'], 'ISO8601');

        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->preserveWhiteSpace = false;

        $xml->appendChild($xml->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="' . self::make_external_url($xsl, $channel_data['domain']) . '" media="screen"'));
        $feed_node = $xml->appendChild($xml->createElement('feed'));
        $feed_node->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');

        // Required feed elements
        $feed_title_node = $feed_node->appendChild($xml->createElement('title'));
        $feed_title_node->setAttribute('type', 'html');
        $feed_title_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($channel_data['title'])));
        $feed_node->appendChild($xml->createElement('updated', $channel_data['updated']));
        $feed_node->appendChild($xml->createElement('id', $channel_data['uuid']));

        // Optional feed elements
        if (!empty($channel_data['atomlink'])) {
            $feed_atom_link = $xml->createElement('link');
            $feed_atom_link->setAttribute('href', self::make_external_url($channel_data['atomlink'], $channel_data['domain']));
            $feed_atom_link->setAttribute('rel', 'self');
            $feed_node->appendChild($feed_atom_link);
        }
        if (!empty($channel_data['link'])) {
            $feed_link_node = $feed_node->appendChild($xml->createElement('link'));
            $feed_link_node->setAttribute('href', $channel_data['link']);
            $feed_link_node->setAttribute('rel', 'alternate');
            $feed_link_node->setAttribute('type', 'text/html');
        }
        if (!empty($channel_data['description'])) {
            [, $channel_data['description']] = self::parse_description($channel_data['description'], $channel_data['rootdir'], $channel_data['domain']);

            $feed_subtitle_node = $feed_node->appendChild($xml->createElement('subtitle'));
            $feed_subtitle_node->setAttribute('type', 'html');
            $feed_subtitle_node->appendChild($xml->createCDATASection($channel_data['description']));
        }
        if (!empty($channel_data['copyright'])) {
            $feed_author_node = $feed_node->appendChild($xml->createElement('author'));
            $feed_author_name_node = $feed_author_node->appendChild($xml->createElement('name'));
            $feed_author_name_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($channel_data['copyright'])));

            $feed_copyright_node = $feed_node->appendChild($xml->createElement('rights', 'Copyright &#169; ' . $channel_data['copyright']));
            $feed_copyright_node->setAttribute('type', 'html');
        }
        !empty($channel_data['generator']) && $feed_node->appendChild($xml->createElement('generator', $channel_data['generator']));
        !empty($channel_data['icon']) && $feed_node->appendChild($xml->createElement('icon', self::make_external_url($channel_data['icon'], $channel_data['domain'])));
        if (!empty($channel_data['image'])) {
            if (($img_info = self::get_img_info($channel_data['image'], 'atom', $channel_data['rootdir'], $channel_data['domain'])) !== false) {
                $feed_node->appendChild($xml->createElement('logo', $img_info['src']));
            }
        }

        if (!empty($items_data)) {
            foreach ($items_data as $item_data) {
                if (!empty($item_data['title']) and !empty($item_data['pubdate'])) {
                    if (!empty($item_data['link'])) {
                        $item_data['link'] = self::make_external_url($item_data['link'], $channel_data['domain']);
                    }
                    $item_data['uuid'] = self::create_uuid(!empty($item_data['link']) ? $item_data['link'] : $item_data['title']);
                    $item_data['pubdate'] = self::create_time($item_data['pubdate'], 'ISO8601');

                    $entry_node = $feed_node->appendChild($xml->createElement('entry'));

                    // Required Elements of <entry>
                    $entry_title_node = $entry_node->appendChild($xml->createElement('title'));
                    $entry_title_node->setAttribute('type', 'html');
                    $entry_title_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($item_data['title'])));
                    $entry_node->appendChild($xml->createElement('updated', $item_data['pubdate']));
                    $entry_node->appendChild($xml->createElement('id', $item_data['uuid']));

                    // Optional elements of <entry>
                    if (!empty($item_data['link'])) {
                        $entry_link_node = $entry_node->appendChild($xml->createElement('link'));
                        $entry_link_node->setAttribute('type', 'text/html');
                        $entry_link_node->setAttribute('href', $item_data['link']);
                    }
                    if (!empty($item_data['author'])) {
                        $entry_author_node = $entry_node->appendChild($xml->createElement('author'));
                        $entry_author_name_node = $entry_author_node->appendChild($xml->createElement('name'));
                        $entry_author_name_node->appendChild($xml->createCDATASection(Site::unhtmlspecialchars($item_data['author'])));
                    }
                    if (!empty($item_data['description'])) {
                        [$item_data['description_image'], $item_data['description']] = self::parse_description($item_data['description'], $channel_data['rootdir'], $channel_data['domain']);
                        $entry_summary_node = $entry_node->appendChild($xml->createElement('summary'));
                        if (!empty($item_data['description_image'])) {
                            $item_data['description_image'] = self::make_external_url($item_data['description_image'], $channel_data['domain']);
                            $item_data['description_image'] = '<img src="' . $item_data['description_image'] . '" alt="" align="left" border="0" width="120"/>';
                            $entry_summary_node->appendChild($xml->createCDATASection($item_data['description_image']));
                        }
                        $entry_summary_node->appendChild($xml->createTextNode($item_data['description']));
                        $entry_summary_node->setAttribute('type', 'html');
                    }
                }
            }
        }

        $xml->formatOutput = true;

        return $xml->saveXML();
    }
}
