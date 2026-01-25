<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_news_block_newscenter')) {
    /**
     * nv_news_block_newscenter()
     *
     * @param array $block_config
     * @return string|void
     */
    function nv_news_block_newscenter($block_config)
    {
        global $nv_Cache, $module_data, $module_name, $module_upload, $global_array_cat, $global_config, $db, $module_config, $module_info, $nv_Lang;

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

        if (!empty($list)) {
            $width = $block_config['width'] ?? 400;
            $height = $block_config['height'] ?? 268;

            $main_row = array_shift($list);
            $main_row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$main_row['catid']]['alias'] . '/' . $main_row['alias'] . '-' . $main_row['id'] . $global_config['rewrite_exturl'];
            $main_row['width'] = $width;
            if (!empty($main_row['homeimgfile']) and ($imginfo = nv_is_image(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $main_row['homeimgfile'])) != []) {
                $image = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $main_row['homeimgfile'];

                if ($imginfo['width'] <= $width and $imginfo['height'] <= $height) {
                    $main_row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $main_row['homeimgfile'];
                    $main_row['width'] = $imginfo['width'];
                } else {
                    $basename = preg_replace('/(.*)(\.[a-z]+)$/i', $module_name . '_' . $main_row['id'] . '_\1_' . $width . '-' . $height . '\2', basename($image));
                    if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename)) {
                        $imginfo = nv_is_image(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename);
                        $main_row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                        $main_row['width'] = $imginfo['width'];
                    } else {
                        $_image = new NukeViet\Files\Image($image, NV_MAX_WIDTH, NV_MAX_HEIGHT);
                        $_image->resizeXY($width, $height);
                        $_image->save(NV_ROOTDIR . '/' . NV_TEMP_DIR, $basename);
                        if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename)) {
                            $main_row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                            $main_row['width'] = $_image->create_Image_info['width'];
                        }
                    }
                }
            } elseif (nv_is_url($main_row['homeimgfile'])) {
                $main_row['imgsource'] = $main_row['homeimgfile'];
            } elseif (!empty($module_config[$module_name]['show_no_image'])) {
                $main_row['imgsource'] = NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'];
            } else {
                $main_row['imgsource'] = NV_STATIC_URL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
            }

            $other_rows = [];
            if (!empty($list)) {
                foreach ($list as $row) {
                    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];

                    if ($row['homeimgthumb'] == 1) {
                        $row['imgsource'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
                    } elseif ($row['homeimgthumb'] == 2) {
                        $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
                    } elseif ($row['homeimgthumb'] == 3) {
                        $row['imgsource'] = $row['homeimgfile'];
                    } elseif (!empty($module_config[$module_name]['show_no_image'])) {
                        $row['imgsource'] = NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'];
                    } else {
                        $row['imgsource'] = NV_STATIC_URL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
                    }

                    $other_rows[] = $row;
                }
            }

            $stpl = new \NukeViet\Template\NVSmarty();
            $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
            $stpl->assign('LANG', $nv_Lang);
            $stpl->assign('CONFIGS', $block_config);
            $stpl->assign('MAIN_ROW', $main_row);
            $stpl->assign('OTHER_ROWS', $other_rows);

            return $stpl->fetch('block_newscenter.tpl');
        }
    }
}

if (defined('NV_SYSTEM')) {
    $module = $block_config['module'];
    $content = nv_news_block_newscenter($block_config);
}
