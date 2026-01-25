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

if (!nv_function_exists('nv_block_headline')) {
    /**
     * nv_block_config_news_headline()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_news_headline($module, $data_block)
    {
        global $nv_Lang;

        $tooltip_position = [
            'top' => $nv_Lang->getModule('tooltip_position_top'),
            'bottom' => $nv_Lang->getModule('tooltip_position_bottom'),
            'left' => $nv_Lang->getModule('tooltip_position_left'),
            'right' => $nv_Lang->getModule('tooltip_position_right')
        ];

        $html = '<div class="row mb-3">';
        $html .= '<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('showtooltip') . ':</label>';
        $html .= '<div class="col-sm-9">';
        $html .= '<div class="row g-2 align-items-center">';
        $html .= '<div class="col-sm-2">';
        $html .= '<input class="form-check-input" type="checkbox" value="1" name="config_showtooltip" ' . ($data_block['showtooltip'] == 1 ? 'checked="checked"' : '') . ' /></div>';
        $html .= '<div class="col-sm-5">';
        $html .= '<div class="input-group">';
        $html .= '<div class="input-group-text">' . $nv_Lang->getModule('tooltip_position') . '</div>';
        $html .= '<select name="config_tooltip_position" class="form-select">';

        foreach ($tooltip_position as $key => $value) {
            $html .= '<option value="' . $key . '" ' . ($data_block['tooltip_position'] == $key ? 'selected="selected"' : '') . '>' . $value . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-5">';
        $html .= '<div class="input-group">';
        $html .= '<div class="input-group-text">' . $nv_Lang->getModule('tooltip_length') . '</div>';
        $html .= '<input type="text" class="form-control" name="config_tooltip_length" value="' . $data_block['tooltip_length'] . '"/>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_news_headline_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_config_news_headline_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_string('config_tooltip_position', 'post', 0);
        $return['config']['tooltip_length'] = $nv_Request->get_string('config_tooltip_length', 'post', 0);

        return $return;
    }

    /**
     * nv_block_headline()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_headline($block_config)
    {
        global $nv_Cache, $module_name, $module_data, $db_slave, $my_head, $module_info, $module_upload, $global_array_cat, $global_config;

        $array_bid_content = [];

        $cache_file = 'block_headline_' . NV_CACHE_PREFIX . '.cache';

        if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
            $array_bid_content = unserialize($cache);
        } else {
            $id = 0;
            $db_slave->sqlreset()->select('bid, title, numbers')->from(NV_PREFIXLANG . '_' . $module_data . '_block_cat')->order('weight ASC')->limit(2);
            $result = $db_slave->query($db_slave->sql());

            while ([$bid, $titlebid, $numberbid] = $result->fetch(3)) {
                ++$id;
                $array_bid_content[$id] = [
                    'id' => $id,
                    'bid' => $bid,
                    'title' => $titlebid,
                    'number' => $numberbid
                ];
            }

            foreach ($array_bid_content as $i => $array_bid) {
                $db_slave->sqlreset()->select('t1.id, t1.catid, t1.title, t1.alias, t1.homeimgfile, t1.homeimgalt, t1.hometext, t1.external_link')->from(NV_PREFIXLANG . '_' . $module_data . '_rows t1')->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id')->where('t1.status= 1 AND t2.bid=' . $array_bid['bid'])->order('t2.weight ASC')->limit($array_bid['number']);

                $result = $db_slave->query($db_slave->sql());
                $array_content = [];
                while ([$id, $catid_i, $title, $alias, $homeimgfile, $homeimgalt, $hometext, $external_link] = $result->fetch(3)) {
                    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];
                    $array_content[] = [
                        'title' => $title,
                        'link' => $link,
                        'homeimgfile' => $homeimgfile,
                        'homeimgalt' => $homeimgalt,
                        'hometext' => $hometext,
                        'external_link' => $external_link
                    ];
                }
                $array_bid_content[$i]['content'] = $array_content;
            }
            $cache = serialize($array_bid_content);
            $nv_Cache->setItem($module_name, $cache_file, $cache);
        }

        [$template, $dir] = get_module_tpl_dir('block_headline.tpl', true);
        $xtpl = new XTemplate('block_headline.tpl', $dir);

        $xtpl->assign('PIX_IMG', ASSETS_STATIC_URL . '/images/pix.svg');
        $xtpl->assign('TEMPLATE', $template);
        $xtpl->assign('TOOLTIP_POSITION', $block_config['tooltip_position']);

        $images = [];

        // Tab 1: Tab có ảnh
        if (!empty($array_bid_content[1]['content'])) {
            $hot_news = $array_bid_content[1]['content'];
            $a = 0;
            foreach ($hot_news as $hot_news_i) {
                if ($hot_news_i['external_link']) {
                    $hot_news_i['target_blank'] = ' target="_blank"';
                }

                if (!empty($hot_news_i['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $hot_news_i['homeimgfile'])) {
                    $images_url = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $hot_news_i['homeimgfile'];
                } elseif (nv_is_url($hot_news_i['homeimgfile'])) {
                    $images_url = $hot_news_i['homeimgfile'];
                }

                if (!empty($images_url)) {
                    $hot_news_i['image_alt'] = !empty($hot_news_i['homeimgalt']) ? $hot_news_i['homeimgalt'] : $hot_news_i['title'];
                    $hot_news_i['imgID'] = $a;
                    $hot_news_i['imgActive'] = $a == 0 ? 'active' : ''; // bootstrap 4/5
                    $hot_news_i['imgCurrent'] = $a == 0 ? 'true' : 'false'; // bootstrap 4/5
                    $hot_news_i['imagefull'] = $images_url;
                    $images[] = $images_url;
                    $xtpl->assign('HOTSNEWS', $hot_news_i);
                    $xtpl->parse('main.hots_news_img.loop');
                    $xtpl->parse('main.hots_news_img.loop2'); // bootstrap 4/5
                    ++$a;
                }
            }
            $xtpl->parse('main.hots_news_img');
        }

        $a = 0;
        foreach ($array_bid_content as $array_bid) {
            $array_bid['selected'] = $a == 0 ? 'true' : 'false'; // bootstrap 4/5
            $array_bid['active'] = $a == 0 ? 'active' : ''; // bootstrap 4/5
            $array_bid['show_active'] = $a == 0 ? 'show active' : ''; // bootstrap 4/5
            $xtpl->assign('TAB_TITLE', $array_bid);
            $xtpl->parse('main.loop_tabs_title');

            $content_bid = $array_bid['content'];
            if (!empty($content_bid)) {
                foreach ($content_bid as $lastest) {
                    if (!empty($lastest['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $lastest['homeimgfile'])) {
                        $lastest['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $lastest['homeimgfile'];
                    } elseif (nv_is_url($lastest['homeimgfile'])) {
                        $lastest['homeimgfile'] = $lastest['homeimgfile'];
                    } else {
                        $lastest['homeimgfile'] = '';
                    }

                    if (!$block_config['showtooltip']) {
                        $xtpl->assign('TITLE', 'title="' . $lastest['title'] . '"');
                    }

                    $lastest['hometext_clean'] = strip_tags($lastest['hometext']);
                    $lastest['hometext_clean'] = nv_clean60($lastest['hometext_clean'], $block_config['tooltip_length'], true);

                    if ($lastest['external_link']) {
                        $lastest['target_blank'] = ' target="_blank"';
                    }

                    $xtpl->assign('LASTEST', $lastest);

                    // bootstrap 4/5
                    if ($block_config['showtooltip']) {
                        $xtpl->parse('main.loop_tabs_content.content.loop.tooltip');
                    }

                    $xtpl->parse('main.loop_tabs_content.content.loop');
                }
                $xtpl->parse('main.loop_tabs_content.content');
            }

            $xtpl->parse('main.loop_tabs_content');
            ++$a;
        }

        if ($block_config['showtooltip']) {
            $xtpl->parse('main.tooltip');
        }

        if (!empty($images)) {
            $xtpl->assign('IMGPRELOAD', '"' . implode('","', $images) . '"');
            $xtpl->parse('main.imgpreload');
        }
        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $module = $block_config['module'];
    $content = nv_block_headline($block_config);
}
