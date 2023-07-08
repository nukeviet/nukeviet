<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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

        $tooltip_position = [
            'top' => $nv_Lang->getModule('tooltip_position_top'),
            'bottom' => $nv_Lang->getModule('tooltip_position_bottom'),
            'left' => $nv_Lang->getModule('tooltip_position_left'),
            'right' => $nv_Lang->getModule('tooltip_position_right')
        ];

        $html = '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getModule('numrow') . ':</label>';
        $html .= '	  <div class="col-sm-18"><input type="text" name="config_numrow" class="form-control" value="' . $data_block['numrow'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getModule('width') . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '      <input type="width" name="config_width" class="form-control" value="' . $data_block['width'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getModule('height') . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '    <input type="height" name="config_height" class="form-control" value="' . $data_block['height'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getModule('length_title') . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '    <input type="text" class="form-control" name="config_length_title" value="' . $data_block['length_title'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getModule('length_hometext') . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '	  <input type="text" class="form-control" name="config_length_hometext" value="' . $data_block['length_hometext'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getModule('length_othertitle') . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '	  <input type="text" class="form-control" name="config_length_othertitle" value="' . $data_block['length_othertitle'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getModule('showtooltip') . ':</label>';
        $html .= '<div class="col-sm-18">';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-4">';
        $html .= '<div class="checkbox"><label><input type="checkbox" value="1" name="config_showtooltip" ' . ($data_block['showtooltip'] == 1 ? 'checked="checked"' : '') . ' /></label>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-10">';
        $html .= '<div class="input-group margin-bottom-sm">';
        $html .= '<div class="input-group-addon">' . $nv_Lang->getModule('tooltip_position') . '</div>';
        $html .= '<select name="config_tooltip_position" class="form-control">';

        foreach ($tooltip_position as $key => $value) {
            $html .= '<option value="' . $key . '" ' . ($data_block['tooltip_position'] == $key ? 'selected="selected"' : '') . '>' . $value . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-10">';
        $html .= '<div class="input-group">';
        $html .= '<div class="input-group-addon">' . $nv_Lang->getModule('tooltip_length') . '</div>';
        $html .= '<input type="text" class="form-control" name="config_tooltip_length" value="' . $data_block['tooltip_length'] . '"/>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getModule('nocatid') . ':</label>';
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY sort ASC';
        $list = $nv_Cache->db($sql, '', $module);
        $html .= '<div class="col-sm-18">';
        $html .= '<div style="height: 160px; overflow: auto">';
        foreach ($list as $l) {
            if ($l['status'] == 1 or $l['status'] == 2) {
                $xtitle_i = '';
                if ($l['lev'] > 0) {
                    for ($i = 1; $i <= $l['lev']; ++$i) {
                        $xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }
                $data_block['nocatid'] = !empty($data_block['nocatid']) ? $data_block['nocatid'] : [];
                $html .= $xtitle_i . '<label><input type="checkbox" name="config_nocatid[]" value="' . $l['catid'] . '" ' . ((in_array((int) $l['catid'], array_map('intval', $data_block['nocatid']), true)) ? ' checked="checked"' : '') . '</input>' . $l['title'] . '</label><br />';
            }
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_news_newscenter_submit()
     *
     * @param string $module
     * @param array  $lang_block
     * @return array
     */
    function nv_block_config_news_newscenter_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_title('config_tooltip_position', 'post', 0);
        $return['config']['tooltip_length'] = $nv_Request->get_title('config_tooltip_length', 'post', 0);
        $return['config']['length_title'] = $nv_Request->get_int('config_length_title', 'post', 0);
        $return['config']['length_hometext'] = $nv_Request->get_int('config_length_hometext', 'post', 0);
        $return['config']['length_othertitle'] = $nv_Request->get_int('config_length_othertitle', 'post', 0);
        $return['config']['width'] = $nv_Request->get_int('config_width', 'post', '');
        $return['config']['height'] = $nv_Request->get_int('config_height', 'post', '');
        $return['config']['nocatid'] = $nv_Request->get_typed_array('config_nocatid', 'post', 'int', []);

        return $return;
    }

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
            $main_row['titleclean60'] = nv_clean60($main_row['title'], $block_config['length_title']);
            $main_row['target_blank'] = $main_row['external_link'] ? 'target="_blank"' : '';
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

            !empty($block_config['length_hometext']) && $main_row['hometext'] = nv_clean60(strip_tags($main_row['hometext']), $block_config['length_hometext']);

            $other_rows = [];
            if (!empty($list)) {
                foreach ($list as $row) {
                    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
                    $row['titleclean60'] = nv_clean60($row['title'], $block_config['length_title']);
                    $row['target_blank'] = $row['external_link'] ? 'target="_blank"' : '';

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

                    $row['hometext_clean'] = $block_config['showtooltip'] ? nv_clean60(strip_tags($row['hometext']), $block_config['tooltip_length'], true) : '';
                    $row['titleclean60'] = nv_clean60($row['title'], $block_config['length_othertitle']);
                    $other_rows[] = $row;
                }
            }

            list($template, $dir) = get_module_tpl_dir('block_newscenter.tpl', true);
            $xtpl = new XTemplate('block_newscenter.tpl', $dir);
            $xtpl->assign('lang', \NukeViet\Core\Language::$lang_module);
            $xtpl->assign('TEMPLATE', $template);

            $xtpl->assign('main', $main_row);
            if (!empty($other_rows)) {
                foreach ($other_rows as $row) {
                    $xtpl->assign('othernews', $row);

                    if ($block_config['showtooltip']) {
                        $xtpl->assign('TOOLTIP_POSITION', $block_config['tooltip_position']);
                        $xtpl->parse('main.othernews.tooltip');
                    }

                    $xtpl->parse('main.othernews');
                }
            }

            $xtpl->parse('main');

            return $xtpl->text('main');
        }
    }
}

if (defined('NV_SYSTEM')) {
    $module = $block_config['module'];
    $content = nv_news_block_newscenter($block_config);
}
