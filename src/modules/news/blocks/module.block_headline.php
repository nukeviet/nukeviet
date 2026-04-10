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
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_news_headline($module, $data_block)
    {
        global $nv_Lang, $site_mods, $nv_Cache;

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_block_cat ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, '', $module);

        [$block_theme, $dir] = get_block_tpl_dir('block_headline.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);
        $tpl->assign('GROUPS', $list);

        return $tpl->fetch('block_headline.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_block_config_news_headline_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['group_id'] = $nv_Request->get_int('config_group_id', 'post', 0);

        return $return;
    }

    /**
     * @param array $block_config
     * @return string
     */
    function nv_block_headline($block_config)
    {
        global $module_data, $db, $module_name, $nv_Cache, $global_array_cat, $global_config, $module_upload, $module_config;

        [$block_theme, $dir] = get_block_tpl_dir('block_headline.tpl', true, $block_config['module'], true);
        $cache_file = 'block_headline_' . $block_theme . '_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
            return $cache;
        }

        $db->sqlreset()
        ->select('
            t1.id, t1.catid, t1.title, t1.alias, t1.homeimgfile, t1.homeimgthumb, t1.homeimgalt,
            t1.hometext, t1.publtime, t1.external_link
        ')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_rows t1')
        ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id')
        ->where('t2.bid= ' . $block_config['group_id'] . ' AND t1.status= 1')
        ->order('t2.weight ASC')
        ->limit(5);
        $result = $db->query($db->sql());

        $array = [];
        $stt = 0;
        while ($row = $result->fetch()) {
            $stt++;
            $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
            $row['cat_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'];
            $row['cat_name'] = $global_array_cat[$row['catid']]['title'];
            $row['imgsource'] = '';
            $row['homeimgalt'] = !empty($row['homeimgalt']) ? $row['homeimgalt'] : $row['title'];
            $width = $stt == 1 ? 700 : 350;

            if (!empty($row['homeimgfile']) and ($imginfo = nv_is_image(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['homeimgfile'])) != []) {
                $image = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];

                if ($imginfo['width'] <= $width) {
                    // Ảnh gốc nhỏ hơn mong muốn
                    $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
                } else {
                    $basename = preg_replace('/(.*)(\.[a-z]+)$/i', $module_name . '_' . $row['id'] . '_\1_' . $width . '\2', basename($image));
                    if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename)) {
                        // Đã tạo ảnh tạm
                        $imginfo = nv_is_image(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename);
                        $row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                    } else {
                        // Tạo ảnh tạm
                        $_image = new NukeViet\Files\Image($image, NV_MAX_WIDTH, NV_MAX_HEIGHT);
                        $_image->resizeXY($width, $width);
                        $_image->save(NV_ROOTDIR . '/' . NV_TEMP_DIR, $basename);
                        if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename)) {
                            $row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                        }
                    }
                }
            } elseif (nv_is_url($row['homeimgfile'])) {
                $row['imgsource'] = $row['homeimgfile'];
            } elseif (!empty($module_config[$module_name]['show_no_image'])) {
                $row['imgsource'] = NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'];
            }

            $array[] = $row;
        }
        $result->closeCursor();

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('DATA', $array);

        $content = $tpl->fetch('block_headline.tpl');
        $nv_Cache->setItem($module_name, $cache_file, $content);
        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    $module = $block_config['module'];
    $content = nv_block_headline($block_config);
}
