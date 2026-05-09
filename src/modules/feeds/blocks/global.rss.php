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

if (!nv_function_exists('nv_block_data_config_rss')) {
    /**
     * nv_block_data_config_rss()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_data_config_rss($module, $data_block)
    {
        global $nv_Lang;

        $data_block['title_length'] = isset($data_block['title_length']) ? (int) $data_block['title_length'] : 0;

        [$block_theme, $dir] = get_block_tpl_dir('global.rss.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);

        return $tpl->fetch('global.rss.config.tpl');
    }

    /**
     * nv_block_data_config_rss_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_data_config_rss_submit($module)
    {
        global $nv_Request, $nv_Lang;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['url'] = $nv_Request->get_title('config_url', 'post', '');
        $return['config']['number'] = $nv_Request->get_int('config_number', 'post', 0);
        $return['config']['isdescription'] = $nv_Request->get_int('config_isdescription', 'post', 0);
        $return['config']['ishtml'] = $nv_Request->get_int('config_ishtml', 'post', 0);
        $return['config']['ispubdate'] = $nv_Request->get_int('config_ispubdate', 'post', 0);
        $return['config']['istarget'] = $nv_Request->get_int('config_istarget', 'post', 0);
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 0);
        if (!nv_is_url($return['config']['url'])) {
            $return['error'][] = $nv_Lang->getModule('error_url');
        }

        return $return;
    }

    /**
     * @param string $description
     * @param string $alt
     * @return bool|string
     */
    function change_description(string $description, string $alt = ''): string
    {
        if (!empty($description)) {
            $img_src = '';
            if (preg_match('#<img.+src="([^"]+)"[^>]*>#i', $description, $matches)) {
                $img_src = $matches['1'];
            }
            $description = trim(strip_tags($description));
            $description = preg_replace("/[\r\n]+/", ' ', $description);
            $description = preg_replace("/(\&nbsp\;|\s)+/", ' ', $description);
            $description = nv_clean60($description, 500);
            if (!empty($img_src)) {
                $description = '<img src="' . ASSETS_STATIC_URL . '/images/pix.svg" style="background-image:url(' . $img_src . ')" alt="' . $alt . '" width="120" height="80"/>' . $description;
            }
        }

        return $description;
    }

    /**
     * @param string $link
     * @return string
     */
    function change_link(string $link)
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
        $cache_file = md5($url) . '_' . NV_CACHE_PREFIX . '.cache';
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
                                $description = '';
                                if ($item->getElementsByTagName('content')->length) {
                                    $description = $item->getElementsByTagName('content')->item(0)->nodeValue;
                                } elseif ($item->getElementsByTagName('summary')->length) {
                                    $description = $item->getElementsByTagName('summary')->item(0)->nodeValue;
                                }
                                $title = nv_htmlspecialchars(trim(strip_tags($item->getElementsByTagName('title')->item(0)->nodeValue)));
                                $pubtime = strtotime($item->getElementsByTagName('updated')->item(0)->nodeValue);
                                $key = $pubtime . '-' . $title;

                                $array_data[$key] = [
                                    'title' => $title,
                                    'description' => change_description($description, $title),
                                    'pubtime' => $pubtime,
                                    'link' => change_link($itemlLink)
                                ];
                            }
                        } else {
                            foreach ($feed->getElementsByTagName('item') as $item) {
                                $title = nv_htmlspecialchars(trim(strip_tags($item->getElementsByTagName('title')->item(0)->nodeValue)));
                                $pubtime = strtotime($item->getElementsByTagName('pubDate')->item(0)->nodeValue);
                                $key = $pubtime . '-' . $title;

                                $array_data[$key] = [
                                    'title' => $title,
                                    'description' => change_description($item->getElementsByTagName('description')->item(0)->nodeValue, $title),
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

            $cache = json_encode($data, NV_JSON_ENCODE);
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
        [$block_theme, $dir] = get_block_tpl_dir('global.rss.tpl', true, $block_config['module']);
        if (empty($dir)) {
            return '';
        }

        $array_rss = nv_get_rss($block_config['url']);
        if (empty($array_rss)) {
            return '';
        }

        $title_length = isset($block_config['title_length']) ? (int) $block_config['title_length'] : 0;
        $istarget = !empty($block_config['istarget']);
        $ishtml = !empty($block_config['ishtml']);
        $isdescription = !empty($block_config['isdescription']);
        $ispubdate = !empty($block_config['ispubdate']);
        $number = (int) $block_config['number'];

        $items = [];
        $count = 0;
        foreach ($array_rss as $item) {
            if ($count >= $number) {
                break;
            }
            $description = '';
            if ($isdescription && !empty($item['description'])) {
                $description = $ishtml ? $item['description'] : strip_tags($item['description']);
            }
            $items[] = [
                'title' => $item['title'],
                'text' => $title_length > 0 ? nv_clean60($item['title'], $title_length) : $item['title'],
                'link' => $item['link'],
                'description' => $description,
                'pubDate' => ($ispubdate && !empty($item['pubtime'])) ? nv_datetime_format($item['pubtime'], 0, 0) : '',
            ];
            ++$count;
        }

        if (empty($items)) {
            return '';
        }

        addition_module_assets($block_config['module'], 'css');

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('ISTARGET', $istarget);
        $tpl->assign('ITEMS', $items);

        return $tpl->fetch('global.rss.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_global_rss($block_config);
}
