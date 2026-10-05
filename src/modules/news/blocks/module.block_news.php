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

        [$block_theme, $dir] = get_block_tpl_dir('module.block_news.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);

        // Vị trí tooltip
        $tooltip_position = [
            'top' => $nv_Lang->getModule('tooltip_position_top'),
            'bottom' => $nv_Lang->getModule('tooltip_position_bottom'),
            'left' => $nv_Lang->getModule('tooltip_position_left'),
            'right' => $nv_Lang->getModule('tooltip_position_right')
        ];
        $tpl->assign('TOOLTIP_POSITION', $tooltip_position);

        return $tpl->fetch('module.block_news.config.tpl');
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
        $return['config']['tooltip_position'] = $nv_Request->get_title('config_tooltip_position', 'post', '');
        $return['config']['tooltip_length'] = $nv_Request->get_absint('config_tooltip_length', 'post', 0);

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
        global $nv_Cache, $module_array_cat, $db, $module_config, $global_config, $site_mods, $nv_Lang;

        $module = $block_config['module'];
        [$block_theme, $dir] = get_block_tpl_dir('module.block_news.tpl', true, $module);
        if (empty($dir)) {
            return '';
        }

        $blockwidth = $module_config[$module]['blockwidth'];
        $show_no_image = $module_config[$module]['show_no_image'];
        $order_articles_by = ($module_config[$module]['order_articles']) ? 'weight' : 'publtime';

        $numrow = (isset($block_config['numrow'])) ? $block_config['numrow'] : 20;

        $cache_file = 'block_news_' . $numrow . '_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module, $cache_file)) != false) {
            $array_block_news = unserialize($cache, NV_UNSERIALIZE_SAFE);
        } else {
            $array_block_news = [];

            $db->sqlreset()
                ->select('id, catid, publtime, exptime, title, alias, homeimgthumb, homeimgfile, homeimgalt, hometext, external_link')
                ->from(NV_PREFIXLANG . '_' . $mod_data . '_rows')
                ->where('status= 1')
                ->order($order_articles_by . ' DESC')
                ->limit($numrow);
            $result = $db->query($db->sql());

            while ($_scratch = $result->fetch(3)) {
                [$id, $catid, $publtime, $exptime, $title, $alias, $homeimgthumb, $homeimgfile, $homeimgalt, $hometext, $external_link] = $_scratch;
                unset($_scratch);
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
                    'homeimgalt' => $homeimgalt,
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

        if (empty($array_block_news)) {
            return '';
        }

        $block_config['showtooltip'] = !empty($block_config['showtooltip']);

        // Vị trí tooltip chỉ nhận các giá trị hợp lệ
        if (!in_array($block_config['tooltip_position'] ?? '', ['top', 'bottom', 'left', 'right'], true)) {
            $block_config['tooltip_position'] = 'bottom';
        }

        // Độ dài tooltip bằng 0 thì không cắt mô tả
        $tooltip_length = (int) ($block_config['tooltip_length'] ?? 0);

        $items = [];
        foreach ($array_block_news as $array_news) {
            $items[] = [
                'title' => $array_news['title'],
                'link' => $array_news['link'],
                'imgurl' => $array_news['imgurl'],
                // Cache cũ chưa có homeimgalt
                'homeimgalt' => empty($array_news['homeimgalt']) ? $array_news['title'] : $array_news['homeimgalt'],
                'external_link' => $array_news['external_link'],
                'hometext_clean' => $block_config['showtooltip'] ? nv_clean60(strip_tags($array_news['hometext']), $tooltip_length, true) : '',
                'is_new' => ($array_news['publtime'] + 86400 * $array_news['newday']) >= NV_CURRENTTIME
            ];
        }

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('MCONFIG', $module_config[$module]);
        $tpl->assign('CONFIG', $block_config);
        $tpl->assign('ITEMS', $items);

        return $tpl->fetch('module.block_news.tpl');
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
