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

if (!nv_function_exists('nv_news_block_news')) {
    /**
     * nv_block_config_news()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_news($module, $data_block)
    {
        global $nv_Lang;

        $tooltip_position = [
            'top' => $nv_Lang->getModule('tooltip_position_top'),
            'bottom' => $nv_Lang->getModule('tooltip_position_bottom'),
            'left' => $nv_Lang->getModule('tooltip_position_left'),
            'right' => $nv_Lang->getModule('tooltip_position_right')
        ];

        $html = '<div class="row mb-3">';
        $html .= '	<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('numrow') . ':</label>';
        $html .= '	<div class="col-sm-9"><input type="text" name="config_numrow" class="form-control" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="row mb-3">';
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
     * nv_block_config_news_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_config_news_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_string('config_tooltip_position', 'post', 0);
        $return['config']['tooltip_length'] = $nv_Request->get_string('config_tooltip_length', 'post', 0);

        return $return;
    }

    /**
     * nv_news_block_news()
     *
     * @param array  $block_config
     * @param string $mod_data
     * @return string
     */
    function nv_news_block_news($block_config, $mod_data)
    {
        global $nv_Cache, $module_array_cat, $module_info, $db_slave, $module_config, $global_config, $site_mods;

        $module = $block_config['module'];
        $blockwidth = $module_config[$module]['blockwidth'];
        $show_no_image = $module_config[$module]['show_no_image'];
        $order_articles_by = ($module_config[$module]['order_articles']) ? 'weight' : 'publtime';

        $numrow = (isset($block_config['numrow'])) ? $block_config['numrow'] : 20;

        $cache_file = 'block_news_' . $numrow . '_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module, $cache_file)) != false) {
            $array_block_news = unserialize($cache);
        } else {
            $array_block_news = [];

            $db_slave->sqlreset()
                ->select('id, catid, publtime, exptime, title, alias, homeimgthumb, homeimgfile, hometext, external_link')
                ->from(NV_PREFIXLANG . '_' . $mod_data . '_rows')
                ->where('status= 1')
                ->order($order_articles_by . ' DESC')
                ->limit($numrow);
            $result = $db_slave->query($db_slave->sql());

            while ([$id, $catid, $publtime, $exptime, $title, $alias, $homeimgthumb, $homeimgfile, $hometext, $external_link] = $result->fetch(3)) {
                $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $module_array_cat[$catid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];
                if ($homeimgthumb == 1) {
                    //image thumb
                    $imgurl = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile;
                } elseif ($homeimgthumb == 2) {
                    //image file
                    $imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile;
                } elseif ($homeimgthumb == 3) {
                    //image url
                    $imgurl = $homeimgfile;
                } elseif (!empty($show_no_image)) {
                    //no image
                    $imgurl = NV_BASE_SITEURL . $show_no_image;
                } else {
                    $imgurl = '';
                }
                $array_block_news[] = [
                    'id' => $id,
                    'title' => $title,
                    'link' => $link,
                    'imgurl' => $imgurl,
                    'width' => $blockwidth,
                    'hometext' => $hometext,
                    'external_link' => $external_link,
                    'publtime' => $publtime,
                    'newday' => $module_array_cat[$catid]['newday']
                ];
            }
            $cache = serialize($array_block_news);
            $nv_Cache->setItem($module, $cache_file, $cache);
        }

        [$template, $dir] = get_module_tpl_dir('block_news.tpl', true);
        $xtpl = new XTemplate('block_news.tpl', $dir);
        $xtpl->assign('TEMPLATE', $template);

        foreach ($array_block_news as $array_news) {
            $newday = $array_news['publtime'] + (86400 * $array_news['newday']);
            $array_news['hometext_clean'] = strip_tags($array_news['hometext']);
            $array_news['hometext_clean'] = nv_clean60($array_news['hometext_clean'], $block_config['tooltip_length'], true);

            if ($array_news['external_link']) {
                $array_news['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('blocknews', $array_news);

            if (!empty($array_news['imgurl'])) {
                $xtpl->parse('main.newloop.imgblock');
            }

            if (!$block_config['showtooltip']) {
                $xtpl->assign('TITLE', 'title="' . $array_news['title'] . '"');
            }

            if ($newday >= NV_CURRENTTIME) {
                $xtpl->parse('main.newloop.newday');
            }

            // Bootstrap 4/5
            if ($block_config['showtooltip']) {
                $xtpl->assign('TOOLTIP_POSITION', $block_config['tooltip_position']);
                $xtpl->parse('main.newloop.tooltip');
            }

            $xtpl->parse('main.newloop');
        }

        if ($block_config['showtooltip']) {
            $xtpl->assign('TOOLTIP_POSITION', $block_config['tooltip_position']);
            $xtpl->parse('main.tooltip');
        }

        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    global $nv_Cache, $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $mod_data = $site_mods[$module]['module_data'];
        if ($module == $module_name) {
            $module_array_cat = $global_array_cat;
            unset($module_array_cat[0]);
        } else {
            $module_array_cat = [];
            $sql = 'SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, newday, description, keywords, groups_view, status FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_cat ORDER BY sort ASC';
            $list = $nv_Cache->db($sql, 'catid', $module);
            if (!empty($list)) {
                foreach ($list as $l) {
                    $module_array_cat[$l['catid']] = $l;
                    $module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
                }
            }
        }
        $content = nv_news_block_news($block_config, $mod_data);
    }
}
