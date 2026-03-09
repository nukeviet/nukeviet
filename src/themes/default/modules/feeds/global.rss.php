<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_global_rss')) {
    /**
     * change_description()
     *
     * @param string $description
     * @return mixed
     */
    function change_description($description)
    {
        $img_src = '';
        if (!empty($description)) {
            if (preg_match('#<img.+src="([^"]+)"[^>]*>#i', $description, $matches)) {
                $img_src = $matches['1'];
            }
            $description = trim(strip_tags($description));
            $description = preg_replace("/[\r\n]+/", ' ', $description);
            $description = preg_replace("/(\&nbsp\;|\s)+/", ' ', $description);
            $description = nv_clean60($description, 500);
        }

        return [$description, $img_src];
    }

    /**
     * change_link()
     *
     * @param string $link
     * @return string
     */
    function change_link($link)
    {
        if (!empty($link)) {
            $link = trim(strip_tags($link));
            if (!nv_is_url($link)) {
                return '';
            }
        }

        return $link;
    }

    /**
     * nv_get_rss()
     *
     * @param string $url
     * @return array
     */
    function nv_get_rss($url)
    {
        global $nv_Cache;
        $data = [
            'updatetime' => 0,
            'md5contents' => '',
            'contents' => []
        ];
        $cache_file = md5($url . 'avatar') . '_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem('rss', $cache_file)) != false) {
            $data = json_decode($cache, true);
            empty($data['updatetime']) && $data['updatetime'] = 0;
            empty($data['md5contents']) && $data['md5contents'] = '';
            empty($data['contents']) && $data['contents'] = [];
        }

        if ($data['updatetime'] < NV_CURRENTTIME - 3600) {
            $data = [
                'updatetime' => NV_CURRENTTIME,
                'md5contents' => $data['md5contents'],
                'contents' => $data['contents']
            ];
            $xml_source = url_get_contents($url);
            if (!empty($xml_source)) {
                $md5contents = md5($xml_source);
                if ($md5contents != $data['md5contents']) {
                    $feed = new DOMDocument('1.0', 'utf-8');
                    libxml_use_internal_errors(true);
                    if ($feed->loadXML($xml_source)) {
                        $array_data = [];

                        if ($feed->getElementsByTagName('feed')->length > 0 && $feed->getElementsByTagName('rss')->length <= 0) {
                            foreach ($feed->getElementsByTagName('entry') as $item) {
                                $links = $item->getElementsByTagName('link');
                                $itemlLink = $links->item(0)->getAttribute('href');
                                if ($item->getElementsByTagName('content')->length) {
                                    $description = $item->getElementsByTagName('content')->item(0)->nodeValue;
                                } elseif ($item->getElementsByTagName('summary')->length) {
                                    $description = $item->getElementsByTagName('summary')->item(0)->nodeValue;
                                }
                                $title = nv_htmlspecialchars(trim(strip_tags($item->getElementsByTagName('title')->item(0)->nodeValue)));
                                $pubtime = strtotime($item->getElementsByTagName('updated')->item(0)->nodeValue);
                                $key = $pubtime . '-' . $title;

                                list($description, $avatar) = change_description($description);
                                $array_data[$key] = [
                                    'title' => $title,
                                    'description' => $description,
                                    'avatar' => $avatar,
                                    'pubtime' => $pubtime,
                                    'link' => change_link($itemlLink)
                                ];
                            }
                        } else {
                            foreach ($feed->getElementsByTagName('item') as $item) {
                                $title = nv_htmlspecialchars(trim(strip_tags($item->getElementsByTagName('title')->item(0)->nodeValue)));
                                $pubtime = strtotime($item->getElementsByTagName('pubDate')->item(0)->nodeValue);
                                $key = $pubtime . '-' . $title;

                                list($description, $avatar) = change_description($item->getElementsByTagName('description')->item(0)->nodeValue);
                                $array_data[$key] = [
                                    'title' => $title,
                                    'description' => $description,
                                    'avatar' => $avatar,
                                    'pubtime' => $pubtime,
                                    'link' => change_link($item->getElementsByTagName('link')->item(0)->nodeValue)
                                ];
                            }
                        }

                        if (!empty($array_data)) {
                            krsort($array_data);
                            $data['md5contents'] = $md5contents;
                            $data['contents'] = array_values($array_data);
                        }
                    }
                }
            }

            $cache = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $nv_Cache->setItem('rss', $cache_file, $cache);
        }

        return $data['contents'];
    }

    /**
     * nv_block_global_rss()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_global_rss($block_config)
    {
        $array_rrs = nv_get_rss($block_config['url']);
        if (empty($array_rrs)) {
            return '';
        }
        $array_rrs = array_slice($array_rrs, 0, $block_config['number']);

        $keys = array_keys($array_rrs);
        foreach ($keys as $key) {
            if (empty($block_config['ishtml'])) {
                $array_rrs[$key]['avatar'] = '';
            }

            if (empty($block_config['isdescription'])) {
                $array_rrs[$key]['description'] = '';
            }

            $array_rrs[$key]['text'] = $array_rrs[$key]['title'];
            if (!empty($block_config['title_length'])) {
                $array_rrs[$key]['text'] = nv_clean60($array_rrs[$key]['title'], (int) $block_config['title_length']);
            }

            $array_rrs[$key]['pubDate'] = ($block_config['ispubdate'] and !empty($array_rrs[$key]['pubtime'])) ? nv_datetime_format($array_rrs[$key]['pubtime']) : '';
            $array_rrs[$key]['target'] = $block_config['istarget'];
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('RSS', $array_rrs);

        return $stpl->fetch('global.rss.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_global_rss($block_config);
}
