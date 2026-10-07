<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_news_block_newscenter')) {
    /**
     * nv_block_config_news_newscenter()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_news_newscenter($module, $data_block)
    {
        global $nv_Cache, $site_mods, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('module.block_newscenter.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);

        // Giá trị mặc định trong file json là chuỗi rỗng nên cần chuẩn hóa về mảng
        $data_block['nocatid'] = empty($data_block['nocatid']) ? [] : array_map('intval', (array) $data_block['nocatid']);
        $data_block['margin_bottom'] = (int) ($data_block['margin_bottom'] ?? 4);
        $tpl->assign('CONFIG', $data_block);

        // Vị trí tooltip
        $tooltip_position = [
            'top' => $nv_Lang->getModule('tooltip_position_top'),
            'bottom' => $nv_Lang->getModule('tooltip_position_bottom'),
            'left' => $nv_Lang->getModule('tooltip_position_left'),
            'right' => $nv_Lang->getModule('tooltip_position_right')
        ];
        $tpl->assign('TOOLTIP_POSITION', $tooltip_position);

        // Chuyên mục
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY sort ASC';
        $tpl->assign('CATS', $nv_Cache->db($sql, '', $module));

        return $tpl->fetch('module.block_newscenter.config.tpl');
    }

    /**
     * nv_block_config_news_newscenter_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_config_news_newscenter_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_title('config_tooltip_position', 'post', '');
        $return['config']['tooltip_length'] = $nv_Request->get_absint('config_tooltip_length', 'post', 0);
        $return['config']['length_title'] = $nv_Request->get_int('config_length_title', 'post', 0);
        $return['config']['length_hometext'] = $nv_Request->get_int('config_length_hometext', 'post', 0);
        $return['config']['length_othertitle'] = $nv_Request->get_int('config_length_othertitle', 'post', 0);
        $return['config']['width'] = $nv_Request->get_int('config_width', 'post', 0);
        $return['config']['height'] = $nv_Request->get_int('config_height', 'post', 0);
        $return['config']['nocatid'] = $nv_Request->get_typed_array('config_nocatid', 'post', 'int', []);
        $return['config']['margin_bottom'] = min(5, $nv_Request->get_absint('config_margin_bottom', 'post', 4));

        return $return;
    }

    /**
     * nv_news_block_newscenter()
     *
     * @param array $block_config
     * @return string
     */
    function nv_news_block_newscenter($block_config)
    {
        global $nv_Cache, $module_data, $module_name, $module_upload, $global_array_cat, $global_config, $db, $module_config, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('module.block_newscenter.tpl', true, $block_config['module']);
        if (empty($dir)) {
            return '';
        }

        $order_articles_by = ($module_config[$module_name]['order_articles']) ? 'weight' : 'publtime';

        $db->sqlreset()
            ->select('id, catid, publtime, title, alias, hometext, homeimgthumb, homeimgfile, external_link')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->order($order_articles_by . ' DESC')
            ->limit($block_config['numrow']);
        if (empty($block_config['nocatid'])) {
            $db->where('status= 1');
        } else {
            $db->where('status= 1 AND catid NOT IN (' . implode(',', $block_config['nocatid']) . ')');
        }

        $list = $nv_Cache->db($db->sql(), 'id', $module_name);
        if (empty($list)) {
            return '';
        }

        // Bài đầu tiên hiển thị nổi bật, dùng ảnh gốc thay vì ảnh thumb
        $main_row = array_shift($list);
        $main_row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$main_row['catid']]['alias'] . '/' . $main_row['alias'] . '-' . $main_row['id'] . $global_config['rewrite_exturl'];
        $main_row['titleclean60'] = nv_clean60($main_row['title'], $block_config['length_title']);
        if ($main_row['homeimgthumb'] == 1 or $main_row['homeimgthumb'] == 2) {
            $main_row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $main_row['homeimgfile'];
        } elseif ($main_row['homeimgthumb'] == 3) {
            $main_row['imgsource'] = $main_row['homeimgfile'];
        } elseif (!empty($module_config[$module_name]['show_no_image'])) {
            $main_row['imgsource'] = NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'];
        } else {
            $main_row['imgsource'] = '';
        }

        // Tỉ lệ khung ảnh bài đầu tiên (%) theo cấu hình rộng x cao, bằng 0 thì giữ tỉ lệ gốc của ảnh
        $width = (int) ($block_config['width'] ?? 0);
        $height = (int) ($block_config['height'] ?? 0);
        $main_row['imgratio'] = ($width > 0 and $height > 0) ? round($height / $width * 100, 2) : 0;

        !empty($block_config['length_hometext']) && $main_row['hometext'] = nv_clean60(strip_tags($main_row['hometext']), $block_config['length_hometext']);

        // Độ dài tooltip bằng 0 thì lấy theo cấu hình của module, tránh đưa toàn bộ mô tả vào tooltip
        $tooltip_length = (int) $block_config['tooltip_length'];
        if ($tooltip_length <= 0) {
            $tooltip_length = (int) $module_config[$module_name]['tooltip_length'];
        }

        // Các bài còn lại
        $other_rows = [];
        foreach ($list as $row) {
            $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
            $row['titleclean60'] = nv_clean60($row['title'], $block_config['length_othertitle']);

            if ($row['homeimgthumb'] == 1) {
                $row['imgsource'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 2) {
                $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 3) {
                $row['imgsource'] = $row['homeimgfile'];
            } elseif (!empty($module_config[$module_name]['show_no_image'])) {
                $row['imgsource'] = NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'];
            } else {
                $row['imgsource'] = '';
            }

            $row['hometext_clean'] = $block_config['showtooltip'] ? nv_clean60(strip_tags($row['hometext']), $tooltip_length, true) : '';
            $other_rows[] = $row;
        }

        // Giữ để tương thích phiên bản cũ
        $block_config['margin_bottom'] = min(5, abs((int) ($block_config['margin_bottom'] ?? 4)));

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $block_config);
        $tpl->assign('MAIN', $main_row);
        $tpl->assign('OTHERS', $other_rows);

        return $tpl->fetch('module.block_newscenter.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $module = $block_config['module'];
    $content = nv_news_block_newscenter($block_config);
}
