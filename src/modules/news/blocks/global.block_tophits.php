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

if (!nv_function_exists('nv_news_block_tophits')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_tophits_blocks($module, $data_block)
    {
        global $nv_Cache, $site_mods, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('block_tophits.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);
        $tpl->assign('MODULE', $module);
        $tpl->assign('SITE_MODS', $site_mods);

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
        $list = $nv_Cache->db($sql, '', $module);
        $tpl->assign('CATS', $list);

        return $tpl->fetch('block_tophits.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_block_config_tophits_blocks_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['number_day'] = $nv_Request->get_int('config_number_day', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_title('config_tooltip_position', 'post', 0);
        $return['config']['tooltip_length'] = $nv_Request->get_absint('config_tooltip_length', 'post', 0);
        $return['config']['nocatid'] = $nv_Request->get_typed_array('config_nocatid', 'post', 'int', []);

        return $return;
    }

    /**
     * @param array  $block_config
     * @param string $mod_data
     * @return string
     */
    function nv_news_block_tophits($block_config, $mod_data)
    {
        global $module_array_cat, $site_mods, $db_slave, $module_config, $global_config, $nv_Lang;

        $module = $block_config['module'];
        $blockwidth = $module_config[$module]['blockwidth'];
        $show_no_image = $module_config[$module]['show_no_image'];
        $publtime = NV_CURRENTTIME - $block_config['number_day'] * 86400;

        $array_block_news = [];
        $db_slave->sqlreset()
            ->select('id, catid, publtime, title, alias, homeimgthumb, homeimgfile, homeimgalt, hometext, external_link')
            ->from(NV_PREFIXLANG . '_' . $mod_data . '_rows')
            ->order('hitstotal DESC')
            ->limit($block_config['numrow']);
        if (empty($block_config['nocatid'])) {
            $db_slave->where('status= 1 AND publtime > ' . $publtime);
        } else {
            $db_slave->where('status= 1 AND publtime > ' . $publtime . ' AND catid NOT IN (' . implode(',', $block_config['nocatid']) . ')');
        }

        $result = $db_slave->query($db_slave->sql());
        while ($_scratch = $result->fetch(3)) {
            [$id, $catid, $publtime, $title, $alias, $homeimgthumb, $homeimgfile, $homeimgalt, $hometext, $external_link] = $_scratch;
            unset($_scratch);
            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $module_array_cat[$catid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];

            // Ảnh nhỏ
            if ($homeimgthumb == 1) {
                $imgurl = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile;
            } elseif ($homeimgthumb == 2) {
                $imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile;
            } elseif ($homeimgthumb == 3) {
                $imgurl = $homeimgfile;
            } elseif (!empty($show_no_image)) {
                $imgurl = NV_BASE_SITEURL . $show_no_image;
            } else {
                $imgurl = '';
            }

            // Ảnh lớn
            if (!empty($homeimgfile) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile)) {
                $imgsource = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile;
            } elseif (nv_is_url($homeimgfile)) {
                $imgsource = $homeimgfile;
            } elseif (!empty($show_no_image)) {
                $imgsource = NV_BASE_SITEURL . $show_no_image;
            } else {
                $imgsource = '';
            }

            $array_block_news[] = [
                'id' => $id,
                'title' => $title,
                'homeimgalt' => empty($homeimgalt) ? $title : $homeimgalt,
                'link' => $link,
                'thumb' => $imgurl,
                'imgsource' => $imgsource,
                'width' => $blockwidth,
                'hometext' => $hometext,
                'hometext_clean' => nv_clean60(strip_tags($hometext), $block_config['tooltip_length'], true),
                'external_link' => $external_link
            ];
        }
        if (empty($array_block_news)) {
            return '';
        }
        [$block_theme, $dir] = get_block_tpl_dir('block_tophits.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('MCONFIG', $module_config[$module]);
        $tpl->assign('CONFIG', $block_config);
        $tpl->assign('LIST', $array_block_news);

        return $tpl->fetch('block_tophits.tpl');
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
            $sql = 'SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, keywords, groups_view, status FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_cat ORDER BY sort ASC';
            $list = $nv_Cache->db($sql, 'catid', $module);
            if (!empty($list)) {
                foreach ($list as $l) {
                    $module_array_cat[$l['catid']] = $l;
                    $module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
                }
            }
        }
        $content = nv_news_block_tophits($block_config, $mod_data);
    }
}
