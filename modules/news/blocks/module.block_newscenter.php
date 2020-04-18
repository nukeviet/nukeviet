<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_news_block_newscenter')) {
    /**
     * nv_block_config_news_newscenter()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_news_newscenter($module, $data_block, $lang_block)
    {
        global $nv_Cache, $site_mods;

        $tooltip_position = array(
            'top' => $lang_block['tooltip_position_top'],
            'bottom' => $lang_block['tooltip_position_bottom'],
            'left' => $lang_block['tooltip_position_left'],
            'right' => $lang_block['tooltip_position_right']
        );

        $html = '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '	  <div class="col-sm-18"><input type="text" name="config_numrow" class="form-control" value="' . $data_block['numrow'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['width'] . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '      <input type="width" name="config_width" class="form-control" value="' . $data_block['width'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['height'] . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '    <input type="height" name="config_height" class="form-control" value="' . $data_block['height'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['length_title'] . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '    <input type="text" class="form-control" name="config_length_title" value="' . $data_block['length_title'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['length_hometext'] . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '	  <input type="text" class="form-control" name="config_length_hometext" value="' . $data_block['length_hometext'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['length_othertitle'] . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '	  <input type="text" class="form-control" name="config_length_othertitle" value="' . $data_block['length_othertitle'] . '"/>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['showtooltip'] . ':</label>';
        $html .= '<div class="col-sm-18">';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-4">';
        $html .= '<div class="checkbox"><label><input type="checkbox" value="1" name="config_showtooltip" ' . ($data_block['showtooltip'] == 1 ? 'checked="checked"' : '') . ' /></label>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-10">';
        $html .= '<div class="input-group margin-bottom-sm">';
        $html .= '<div class="input-group-addon">' . $lang_block['tooltip_position'] . '</div>';
        $html .= '<select name="config_tooltip_position" class="form-control">';

        foreach ($tooltip_position as $key => $value) {
            $html .= '<option value="' . $key . '" ' . ($data_block['tooltip_position'] == $key ? 'selected="selected"' : '') . '>' . $value . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-10">';
        $html .= '<div class="input-group">';
        $html .= '<div class="input-group-addon">' . $lang_block['tooltip_length'] . '</div>';
        $html .= '<input type="text" class="form-control" name="config_tooltip_length" value="' . $data_block['tooltip_length'] . '"/>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['nocatid'] . ':</label>';
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
                $data_block['nocatid'] = !empty($data_block['nocatid']) ? $data_block['nocatid'] : array();
                $html .= $xtitle_i . '<label><input type="checkbox" name="config_nocatid[]" value="' . $l['catid'] . '" ' . ((in_array($l['catid'], $data_block['nocatid'])) ? ' checked="checked"' : '') . '</input>' . $l['title'] . '</label><br />';
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
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_news_newscenter_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_title('config_tooltip_position', 'post', 0);
        $return['config']['tooltip_length'] = $nv_Request->get_title('config_tooltip_length', 'post', 0);
        $return['config']['length_title'] = $nv_Request->get_int('config_length_title', 'post', 0);
        $return['config']['length_hometext'] = $nv_Request->get_int('config_length_hometext', 'post', 0);
        $return['config']['length_othertitle'] = $nv_Request->get_int('config_length_othertitle', 'post', 0);
        $return['config']['width'] = $nv_Request->get_int('config_width', 'post', '');
        $return['config']['height'] = $nv_Request->get_int('config_height', 'post', '');
        $return['config']['nocatid'] = $nv_Request->get_typed_array('config_nocatid', 'post', 'int', array());
        return $return;
    }

    /**
     * nv_news_block_newscenter()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_news_block_newscenter($block_config)
    {
        global $nv_Cache, $module_data, $module_name, $module_upload, $global_array_cat, $global_config, $lang_module, $db, $module_config, $module_info;
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
            $xtpl = new XTemplate('block_newscenter.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
            $xtpl->assign('lang', $lang_module);
            $xtpl->assign('TEMPLATE', $module_info['template']);

            $_first = true;
            foreach ($list as $row) {
                $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
                $row['titleclean60'] = nv_clean60($row['title'], $block_config['length_title']);

                if ($row['external_link']) {
                    $row['target_blank'] = 'target="_blank"';
                }

                if ($_first) {
                    $_first = false;
                    $width = isset($block_config['width']) ? $block_config['width'] : 400;
                    $height = isset($block_config['height']) ? $block_config['height'] : 268;

                    if ($row['homeimgfile'] != '' and ($imginfo = nv_is_image(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['homeimgfile'])) != array()) {
                        $image = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];

                        if ($imginfo['width'] <= $width and $imginfo['height'] <= $height) {
                            $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
                            $row['width'] = $imginfo['width'];
                        } else {
                            $basename = preg_replace('/(.*)(\.[a-z]+)$/i', $module_name . '_' . $row['id'] . '_\1_' . $width . '-' . $height . '\2', basename($image));
                            if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename)) {
                                $imginfo = nv_is_image(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename);
                                $row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                                $row['width'] = $imginfo['width'];
                            } else {
                                $_image = new NukeViet\Files\Image($image, NV_MAX_WIDTH, NV_MAX_HEIGHT);
                                $_image->resizeXY($width, $height);
                                $_image->save(NV_ROOTDIR . '/' . NV_TEMP_DIR, $basename);
                                if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename)) {
                                    $row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                                    $row['width'] = $_image->create_Image_info['width'];
                                }
                            }
                        }
                    } elseif (nv_is_url($row['homeimgfile'])) {
                        $row['imgsource'] = $row['homeimgfile'];
                        $row['width'] = $width;
                    } elseif (!empty($module_config[$module_name]['show_no_image'])) {
                        $row['imgsource'] = NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'];
                        $row['width'] = $width;
                    } else {
                        $row['imgsource'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
                        $row['width'] = $width;
                    }

                    if (!empty($block_config['length_hometext'])) {
                        $row['hometext'] = nv_clean60(strip_tags($row['hometext']), $block_config['length_hometext']);
                    }

                    $xtpl->assign('main', $row);
                } else {
                    if ($row['homeimgthumb'] == 1) {
                        $row['imgsource'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
                    } elseif ($row['homeimgthumb'] == 2) {
                        $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
                    } elseif ($row['homeimgthumb'] == 3) {
                        $row['imgsource'] = $row['homeimgfile'];
                    } elseif (!empty($module_config[$module_name]['show_no_image'])) {
                        $row['imgsource'] = NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'];
                    } else {
                        $row['imgsource'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
                    }

                    if ($block_config['showtooltip']) {
                        $row['hometext_clean'] = strip_tags($row['hometext']);
                        $row['hometext_clean'] = nv_clean60($row['hometext_clean'], $block_config['tooltip_length'], true);
                    }
                    $row['titleclean60'] = nv_clean60($row['title'], $block_config['length_othertitle']);
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
