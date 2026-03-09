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

        $return = '';

        $html = '<input class="form-control" name="config_url" type="text" value="' . $data_block['url'] . '"/>';
        $return .= '<div class="row mb-3"><label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('url') . ':</label><div class="col-sm-9">' . $html . '</div></div>';

        $html = "<select class=\"form-select\" name=\"config_number\">\n";
        for ($index = 1; $index <= 50; ++$index) {
            $sel = ($index == $data_block['number']) ? ' selected' : '';
            $html .= '<option value="' . $index . '" ' . $sel . '>' . $index . "</option>\n";
        }
        $html .= "</select>\n";
        $return .= '<div class="row mb-3"><label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('number') . ':</label><div class="col-sm-9">' . $html . '</div></div>';

        $data_block['title_length'] = isset($data_block['title_length']) ? (int) ($data_block['title_length']) : 0;
        $html = "<select class=\"form-select\" name=\"config_title_length\">\n";
        for ($index = 0; $index <= 255; ++$index) {
            $sel = ($index == $data_block['title_length']) ? ' selected' : '';
            $html .= '<option value="' . $index . '" ' . $sel . '>' . $index . "</option>\n";
        }
        $html .= "</select>\n";
        $return .= '<div class="row mb-3"><label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('title_length') . ':</label><div class="col-sm-9">' . $html . '</div></div>';

        $sel = ((int) ($data_block['isdescription']) == 1) ? 'checked="checked"' : '';
        $html = '<input class="form-check-input" type="checkbox" id="config_isdescription" name="config_isdescription" value="1" ' . $sel . ' /><label for="config_isdescription" class="form-check-label">' . $nv_Lang->getModule('block_yes') . "</label>\n";
        $return .= '<div class="row mb-3"><label class="col-sm-3 col-form-label py-0 text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('isdescription') . '</label><div class="col-sm-9"><div class="form-check">' . $html . '</div></div></div>';

        $sel = ((int) ($data_block['ishtml']) == 1) ? 'checked="checked"' : '';
        $html = '<input class="form-check-input" type="checkbox" id="config_ishtml" name="config_ishtml" value="1" ' . $sel . ' /><label for="config_ishtml" class="form-check-label">' . $nv_Lang->getModule('block_yes') . "</label>\n";
        $return .= '<div class="row mb-3"><label class="col-sm-3 col-form-label py-0 text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('ishtml') . ':</label><div class="col-sm-9"><div class="form-check">' . $html . '</div></div></div>';

        $sel = ((int) ($data_block['ispubdate']) == 1) ? 'checked="checked"' : '';
        $html = '<input class="form-check-input" type="checkbox" id="config_ispubdate" name="config_ispubdate" value="1" ' . $sel . ' /><label for="config_ispubdate" class="form-check-label">' . $nv_Lang->getModule('block_yes') . "</label>\n";
        $return .= '<div class="row mb-3"><label class="col-sm-3 col-form-label py-0 text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('ispubdate') . ':</label><div class="col-sm-9"><div class="form-check">' . $html . '</div></div></div>';

        $sel = ((int) ($data_block['istarget']) == 1) ? 'checked="checked"' : '';
        $html = '<input class="form-check-input" type="checkbox" id="config_istarget" name="config_istarget" value="1" ' . $sel . ' /><label for="config_istarget" class="form-check-label">' . $nv_Lang->getModule('block_yes') . "</label>\n";
        $return .= '<div class="row mb-3"><label class="col-sm-3 col-form-label py-0 text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('istarget') . ':</label><div class="col-sm-9"><div class="form-check">' . $html . '</div></div></div>';

        return $return;
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
        $return['config']['url'] = $nv_Request->get_title('config_url', 'post', '', 0);
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

    function change_description($description, $alt = '')
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
        global $global_config;

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/feeds/global.rss.tpl');

        $a = 1;
        $xtpl = new XTemplate('global.rss.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/feeds');
        $array_rrs = nv_get_rss($block_config['url']);
        $title_length = isset($block_config['title_length']) ? (int) ($block_config['title_length']) : 0;
        foreach ($array_rrs as $item) {
            if ($a <= $block_config['number']) {
                $item['description'] = ($block_config['ishtml']) ? $item['description'] : (!empty($item['description']) ? strip_tags($item['description']) : '');
                $item['target'] = ($block_config['istarget']) ? ' data-target="_blank"' : '';
                $item['class'] = ($a % 2 == 0) ? 'second' : '';
                if ($title_length > 0) {
                    $item['text'] = nv_clean60($item['title'], $title_length);
                } else {
                    $item['text'] = $item['title'];
                }
                $item['pubDate'] = !empty($item['pubtime']) ? nv_datetime_format($item['pubtime'], 0, 0) : '';
                $xtpl->assign('DATA', $item);
                if (!empty($item['description']) and $block_config['isdescription']) {
                    $xtpl->parse('main.loop.description');
                }
                if (!empty($item['pubDate']) and $block_config['ispubdate']) {
                    $xtpl->parse('main.loop.pubDate');
                }
                $xtpl->parse('main.loop');
                ++$a;
            } else {
                break;
            }
        }
        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_global_rss($block_config);
}
