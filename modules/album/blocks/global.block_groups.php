<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_news_groups')) {
    /**
     * nv_block_config_news_groups()
     *
     * @param string $module
     * @param array  $data_block
     * @param array  $lang_block
     * @return string
     */
    function nv_block_config_news_groups($module, $data_block, $lang_block)
    {
        global $nv_Cache, $site_mods;

        $tooltip_position = [
            'top' => $lang_block['tooltip_position_top'],
            'bottom' => $lang_block['tooltip_position_bottom'],
            'left' => $lang_block['tooltip_position_left'],
            'right' => $lang_block['tooltip_position_right']
        ];
        $html_input = '';
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['blockid'] . ':</label>';
        $html .= '<div class="col-sm-9"><select name="config_blockid" class="form-control">';
        $html .= '<option value="0"> -- </option>';
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_block_cat ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, '', $module);
        foreach ($list as $l) {
            $html_input .= '<input type="hidden" id="config_blockid_' . $l['bid'] . '" value="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $site_mods[$module]['alias']['groups'] . '/' . $l['alias'] . '" />';
            $html .= '<option value="' . $l['bid'] . '" ' . (($data_block['blockid'] == $l['bid']) ? ' selected="selected"' : '') . '>' . $l['title'] . '</option>';
        }
        $html .= '</select>';
        $html .= $html_input;
        $html .= '<script type="text/javascript">';
        $html .= '	$("select[name=config_blockid]").change(function() {';
        $html .= '		$("input[name=title]").val($("select[name=config_blockid] option:selected").text());';
        $html .= '		$("input[name=link]").val($("#config_blockid_" + $("select[name=config_blockid]").val()).val());';
        $html .= '	});';
        $html .= '</script>';
        $html .= '</div></div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['title_length'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_title_length" size="5" value="' . $data_block['title_length'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_numrow" size="5" value="' . $data_block['numrow'] . '"/></div>';
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

        return $html;
    }

    /**
     * nv_block_config_news_groups_submit()
     *
     * @param string $module
     * @param array  $lang_block
     * @return array
     */
    function nv_block_config_news_groups_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['blockid'] = $nv_Request->get_int('config_blockid', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 20);
        $return['config']['showtooltip'] = $nv_Request->get_int('config_showtooltip', 'post', 0);
        $return['config']['tooltip_position'] = $nv_Request->get_string('config_tooltip_position', 'post', 0);
        $return['config']['tooltip_length'] = $nv_Request->get_string('config_tooltip_length', 'post', 0);

        return $return;
    }

    /**
     * nv_block_news_groups()
     *
     * @param array $block_config
     * @return string|void
     */
    function nv_block_news_groups($block_config)
    {
        global $module_array_cat, $site_mods, $module_config, $global_config, $nv_Cache, $db;
        $module = $block_config['module'];
        $show_no_image = $module_config[$module]['show_no_image'];
        $blockwidth = $module_config[$module]['blockwidth'];

        $db->sqlreset()
            ->select('t1.id, t1.catid, t1.title, t1.alias, t1.homeimgfile, t1.homeimgthumb,t1.hometext,t1.publtime,t1.external_link')
            ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows t1')
            ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_block t2 ON t1.id = t2.id')
            ->where('t2.bid= ' . $block_config['blockid'] . ' AND t1.status= 1')
            ->order('t2.weight ASC')
            ->limit($block_config['numrow']);
        $list = $nv_Cache->db($db->sql(), '', $module);

        if (!empty($list)) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/news/block_groups.tpl')) {
                $block_theme = $global_config['module_theme'];
            } else {
                $block_theme = 'default';
            }
            $xtpl = new XTemplate('block_groups.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/news');
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);

            foreach ($list as $l) {
                $l['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $module_array_cat[$l['catid']]['alias'] . '/' . $l['alias'] . '-' . $l['id'] . $global_config['rewrite_exturl'];
                if ($l['homeimgthumb'] == 1) {
                    $l['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $l['homeimgfile'];
                } elseif ($l['homeimgthumb'] == 2) {
                    $l['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $l['homeimgfile'];
                } elseif ($l['homeimgthumb'] == 3) {
                    $l['thumb'] = $l['homeimgfile'];
                } elseif (!empty($show_no_image)) {
                    $l['thumb'] = NV_BASE_SITEURL . $show_no_image;
                } else {
                    $l['thumb'] = '';
                }

                $l['blockwidth'] = $blockwidth;

                $l['hometext_clean'] = strip_tags($l['hometext']);
                $l['hometext_clean'] = nv_clean60($l['hometext_clean'], $block_config['tooltip_length'], true);

                if (!$block_config['showtooltip']) {
                    $xtpl->assign('TITLE', 'title="' . $l['title'] . '"');
                }

                $l['title_clean'] = nv_clean60($l['title'], $block_config['title_length']);

                if ($l['external_link']) {
                    $l['target_blank'] = 'target="_blank"';
                }

                $xtpl->assign('ROW', $l);
                if (!empty($l['thumb'])) {
                    $xtpl->parse('main.loop.img');
                }
                $xtpl->parse('main.loop');
            }

            if ($block_config['showtooltip']) {
                $xtpl->assign('TOOLTIP_POSITION', $block_config['tooltip_position']);
                $xtpl->parse('main.tooltip');
            }

            $xtpl->parse('main');

            return $xtpl->text('main');
        }
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
