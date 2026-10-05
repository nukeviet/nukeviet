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

if (!nv_function_exists('nv_block_news_cat')) {
    /**
     * nv_block_config_news_cat()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_news_cat($module, $data_block)
    {
        global $nv_Cache, $site_mods, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('global.block_news_cat.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);

        // Chuyên mục đã chọn đưa về mảng số nguyên để so khớp checkbox
        $data_block['catid'] = empty($data_block['catid']) ? [] : array_map('intval', (array) $data_block['catid']);
        $tpl->assign('CONFIG', $data_block);

        // Các chuyên mục
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY sort ASC';
        $tpl->assign('CATS', $nv_Cache->db($sql, '', $module));

        // Vị trí tooltip
        $tooltip_position = [
            'top' => $nv_Lang->getModule('tooltip_position_top'),
            'bottom' => $nv_Lang->getModule('tooltip_position_bottom'),
            'left' => $nv_Lang->getModule('tooltip_position_left'),
            'right' => $nv_Lang->getModule('tooltip_position_right')
        ];
        $tpl->assign('TOOLTIP_POSITION', $tooltip_position);

        return $tpl->fetch('global.block_news_cat.config.tpl');
    }

    /**
     * nv_block_config_news_cat_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_config_news_cat_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['catid'] = $nv_Request->get_typed_array('config_catid', 'post', 'int');
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 20);
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_title('config_tooltip_position', 'post', '');
        $return['config']['tooltip_length'] = $nv_Request->get_absint('config_tooltip_length', 'post', 0);

        return $return;
    }

    /**
     * nv_block_news_cat()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_news_cat($block_config)
    {
        global $nv_Cache, $module_array_cat, $site_mods, $module_config, $global_config, $db, $nv_Lang;

        $module = $block_config['module'];
        [$block_theme, $dir] = get_block_tpl_dir('global.block_news_cat.tpl', true, $module);
        if (empty($dir)) {
            return '';
        }

        // Giá trị mặc định trong json là chuỗi "0" nên cần lọc bỏ
        $catids = empty($block_config['catid']) ? [] : array_filter(array_map('intval', (array) $block_config['catid']));
        if (empty($catids)) {
            return '';
        }

        $show_no_image = $module_config[$module]['show_no_image'];
        $order_articles_by = ($module_config[$module]['order_articles']) ? 'weight' : 'publtime';

        $db->sqlreset()
            ->select('id, catid, title, alias, homeimgfile, homeimgthumb, homeimgalt, hometext, publtime, external_link')
            ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows')
            ->where('status= 1 AND catid IN(' . implode(',', $catids) . ')')
            ->order($order_articles_by . ' DESC')
            ->limit((int) $block_config['numrow']);
        $list = $nv_Cache->db($db->sql(), '', $module);
        if (empty($list)) {
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
        foreach ($list as $row) {
            if ($row['homeimgthumb'] == 1) {
                // Ảnh thumb
                $thumb = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 2) {
                // Ảnh gốc
                $thumb = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 3) {
                // Ảnh từ URL
                $thumb = $row['homeimgfile'];
            } elseif (!empty($show_no_image)) {
                // Ảnh mặc định
                $thumb = NV_BASE_SITEURL . $show_no_image;
            } else {
                $thumb = '';
            }

            $items[] = [
                'title' => $row['title'],
                'title_clean' => nv_clean60($row['title'], (int) $block_config['title_length']),
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $module_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'],
                'thumb' => $thumb,
                'homeimgalt' => empty($row['homeimgalt']) ? $row['title'] : $row['homeimgalt'],
                'external_link' => $row['external_link'],
                'hometext_clean' => $block_config['showtooltip'] ? nv_clean60(strip_tags($row['hometext']), $tooltip_length, true) : ''
            ];
        }

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('MCONFIG', $module_config[$module]);
        $tpl->assign('CONFIG', $block_config);
        $tpl->assign('ITEMS', $items);

        return $tpl->fetch('global.block_news_cat.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    global $nv_Cache, $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        if ($module == $module_name) {
            $module_array_cat = $global_array_cat;
            unset($module_array_cat[0]);
        } else {
            $module_array_cat = [];
            $sql = 'SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, keywords, groups_view, status FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY sort ASC';
            $list = $nv_Cache->db($sql, 'catid', $module);
            if (!empty($list)) {
                foreach ($list as $l) {
                    $module_array_cat[$l['catid']] = $l;
                    $module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
                }
            }
        }
        $content = nv_block_news_cat($block_config);
    }
}
