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
        global $nv_Cache, $module_name, $module_data, $db_slave, $module_upload, $global_array_cat, $global_config, $custom_preloads;

        $block_contents = [];
        $cache_file = preg_replace('/[^a-z0-9\_\-]+/', '_', $block_config['block_name']) . '_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
            $block_contents = json_decode($cache, true);

            if (!isset($block_contents['showtooltip']) or !isset($block_contents['tooltip_position']) or !isset($block_contents['tooltip_length']) or $block_contents['showtooltip'] != $block_config['showtooltip'] or $block_contents['tooltip_position'] != $block_config['tooltip_position'] or $block_contents['tooltip_length'] != $block_config['tooltip_length']) {
                $block_contents = [];
            }
        }

        if (empty($block_contents)) {
            $block_contents = [
                'carousel' => [],
                'blocks' => [],
                'imgpreload' => [],
                'showtooltip' => $block_config['showtooltip'],
                'tooltip_position' => $block_config['tooltip_position'],
                'tooltip_length' => $block_config['tooltip_length']
            ];

            $db_slave->sqlreset()->select('bid, title, numbers')->from(NV_PREFIXLANG . '_' . $module_data . '_block_cat')->order('weight ASC')->limit(2);
            $result = $db_slave->query($db_slave->sql());

            $blockslist = [];
            $bids = [];
            while ($_scratch = $result->fetch(3)) {
                [$bid, $titlebid, $numberbid] = $_scratch;
                unset($_scratch);
                $blockslist[$bid] = [
                    'bid' => $bid,
                    'title' => $titlebid,
                    'number' => $numberbid
                ];
                $bids[] = $bid;
            }

            $carousel_img_count = 0;
            $is_active = false;
            if (!empty($bids)) {
                foreach ($bids as $bid) {
                    $block = [
                        'bid' => $blockslist[$bid]['bid'],
                        'title' => $blockslist[$bid]['title'],
                        'items' => []
                    ];
                    $db_slave->sqlreset()->select('t1.id, t1.catid, t1.publtime, t1.title, t1.alias, t1.homeimgthumb, t1.homeimgfile, t1.homeimgalt, t1.hometext, t1.external_link')->from(NV_PREFIXLANG . '_' . $module_data . '_rows t1')->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id')->where('t1.status= 1 AND t2.bid=' . $bid)->order('t2.weight ASC')->limit($blockslist[$bid]['number']);
                    $result = $db_slave->query($db_slave->sql());
                    while ($_scratch = $result->fetch(3)) {
                        [$id, $catid_i, $publtime, $title, $alias, $homeimgthumb, $homeimgfile, $homeimgalt, $hometext, $external_link] = $_scratch;
                        unset($_scratch);
                        $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];
                        $thumb = '';
                        if (!empty($homeimgfile)) {
                            if ($homeimgthumb == 1) {
                                //image thumb
                                $thumb = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $homeimgfile;
                                if (!empty($global_config['cdn_url'])) {
                                    $thumb = $global_config['cdn_url'] . $thumb;
                                }
                            } elseif ($homeimgthumb == 2) {
                                //image file
                                $thumb = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $homeimgfile;
                                if (!empty($global_config['cdn_url'])) {
                                    $thumb = $global_config['cdn_url'] . $thumb;
                                }
                            } elseif ($homeimgthumb == 3) {
                                $thumb = $homeimgfile;
                            }
                        }

                        $block['items'][$id] = [
                            'title' => $title,
                            'link' => $link,
                            'homeimgfile' => $thumb,
                            'homeimgalt' => $homeimgalt,
                            'hometext' => trim(strip_tags($hometext)),
                            'external_link' => $external_link,
                            'newday' => $publtime + (86400 * $global_array_cat[$catid_i]['newday'])
                        ];

                        if (!$is_active and $carousel_img_count < 5) {
                            $image_url = '';
                            if (!empty($homeimgfile) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $homeimgfile)) {
                                $size = getimagesize(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $homeimgfile);
                                $image_url = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $homeimgfile;
                                $block_contents['imgpreload'][] = [
                                    'src' => $image_url,
                                    'mime' => $size['mime']
                                ];
                            } elseif (nv_is_url($homeimgfile)) {
                                $image_url = $homeimgfile;
                            }

                            if (!empty($image_url)) {
                                $block_contents['carousel'][] = [
                                    'link' => $link,
                                    'external_link' => $external_link,
                                    'alt' => !empty($homeimgalt) ? $homeimgalt : $title,
                                    'src' => $image_url,
                                    'title' => $title
                                ];
                            }
                            ++$carousel_img_count;
                        }
                    }

                    $block_contents['blocks'][] = $block;
                    $is_active = true;
                }
            }

            $cache = json_encode($block_contents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $nv_Cache->setItem($module_name, $cache_file, $cache);
        }

        if (!empty($block_contents['imgpreload'])) {
            foreach ($block_contents['imgpreload'] as $preload) {
                $custom_preloads[] = [
                    'as' => 'image',
                    'href' => $preload['src'],
                    'type' => $preload['mime']
                ];
            }
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
        $stpl->assign('BLOCK_CONTENTS', $block_contents);

        return $stpl->fetch('block_headline.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_headline($block_config);
}
