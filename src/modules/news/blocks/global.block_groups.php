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

if (!nv_function_exists('nv_block_news_groups')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_news_groups($module, $data_block)
    {
        global $nv_Cache, $site_mods, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('block_groups.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);
        $tpl->assign('MODULE', $module);
        $tpl->assign('SITE_MODS', $site_mods);

        // Các nhóm tin
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_block_cat ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, '', $module);
        $tpl->assign('BLOCKS', $list);

        // Vị trí tooltip
        $tooltip_position = [
            'top' => $nv_Lang->getModule('tooltip_position_top'),
            'bottom' => $nv_Lang->getModule('tooltip_position_bottom'),
            'left' => $nv_Lang->getModule('tooltip_position_left'),
            'right' => $nv_Lang->getModule('tooltip_position_right')
        ];
        $tpl->assign('TOOLTIP_POSITION', $tooltip_position);

        return $tpl->fetch('block_groups.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_block_config_news_groups_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['blockid'] = $nv_Request->get_int('config_blockid', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 20);
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_title('config_tooltip_position', 'post', '');
        $return['config']['tooltip_length'] = $nv_Request->get_absint('config_tooltip_length', 'post', 0);

        return $return;
    }

    /**
     * @param array $block_config
     * @return string|void
     */
    function nv_block_news_groups($block_config)
    {
        global $module_array_cat, $site_mods, $module_config, $global_config, $nv_Cache, $db, $nv_Lang;

        $module = $block_config['module'];
        $show_no_image = $module_config[$module]['show_no_image'];

        $db->sqlreset()
            ->select('
                t1.id, t1.catid, t1.title, t1.alias, t1.homeimgfile, t1.homeimgthumb, t1.homeimgalt,
                t1.hometext,t1.publtime,t1.external_link
            ')
            ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows t1')
            ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_block t2 ON t1.id = t2.id')
            ->where('t2.bid= ' . $block_config['blockid'] . ' AND t1.status= 1')
            ->order('t2.weight ASC')
            ->limit($block_config['numrow']);
        $list = $nv_Cache->db($db->sql(), '', $module);
        if (empty($list)) {
            return '';
        }

        [$block_theme, $dir] = get_block_tpl_dir('block_groups.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('MCONFIG', $module_config[$module]);
        $tpl->assign('CONFIG', $block_config);

        $array = [];
        foreach ($list as $row) {
            $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $module_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];

            // Ảnh nhỏ
            if ($row['homeimgthumb'] == 1) {
                $row['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 2) {
                $row['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 3) {
                $row['thumb'] = $row['homeimgfile'];
            } elseif (!empty($show_no_image)) {
                $row['thumb'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $row['thumb'] = '';
            }

            // Ảnh lớn
            if (!empty($row['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['homeimgfile'])) {
                $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['homeimgfile'];
            } elseif (nv_is_url($row['homeimgfile'])) {
                $row['imgsource'] = $row['homeimgfile'];
            } elseif (!empty($show_no_image)) {
                $row['imgsource'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $row['imgsource'] = '';
            }

            $row['homeimgalt'] = !empty($row['homeimgalt']) ? $row['homeimgalt'] : $row['title'];
            $row['title_clean'] = nv_clean60($row['title'], $block_config['title_length']);

            $row['hometext_clean'] = strip_tags($row['hometext']);
            $row['hometext_clean'] = nv_clean60($row['hometext_clean'], $block_config['tooltip_length'], true);

            $array[] = $row;
        }
        $tpl->assign('LIST', $array);

        return $tpl->fetch('block_groups.tpl');
    }
}
if (defined('NV_SYSTEM')) {
    global $site_mods, $module_name, $global_array_cat, $module_array_cat, $nv_Cache, $db;
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
        $content = nv_block_news_groups($block_config);
    }
}
