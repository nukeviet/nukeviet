<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!nv_function_exists('nv_law_block_newg')) {
    /**
     * nv_block_config_new_laws()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_new_laws($module, $data_block, $lang_block)
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_numrow" value="' . $data_block['numrow'] . '" /></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['title_length'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_title_length" value="' . $data_block['title_length'] . '" /><span class="help-block">' . $lang_block['title_note'] . '</span></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['show_code'] . ':</label>';
        $ck = $data_block['show_code'] ? 'checked="checked"' : '';
        $html .= '<div class="col-sm-18"><input type="checkbox" name="config_show_code" value="1" ' . $ck . ' /></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['direction'] . ':</label>';
        $html .= '<div class="col-sm-18"><select name="config_direction" class="form-control">';
        $sl = $data_block['direction'] == 'none' ? 'selected="selected"' : '';
        $html .= '<option value="none" ' . $sl . ' >' . $lang_block['direction_none'] . '</option>';
        $sl = $data_block['direction'] == 'up' ? 'selected="selected"' : '';
        $html .= '<option value="up" ' . $sl . ' >' . $lang_block['direction_up'] . '</option>';
        $sl = $data_block['direction'] == 'down' ? 'selected="selected"' : '';
        $html .= '<option value="down" ' . $sl . ' >' . $lang_block['direction_down'] . '</option>';
        $html .= '</select></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['duration'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_duration" value="' . $data_block['duration'] . '" /></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['pauseOnHover'] . ':</label>';
        $ck = $data_block['pauseOnHover'] ? 'checked="checked"' : '';
        $html .= '<div class="col-sm-18"><input type="checkbox" name="config_pauseOnHover" value="1" ' . $ck . ' /></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['duplicated'] . ':</label>';
        $ck = $data_block['duplicated'] ? 'checked="checked"' : '';
        $html .= '<div class="col-sm-18"><input type="checkbox" name="config_duplicated" value="1" ' . $ck . ' /></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['order'] . ':</label>';
        $html .= '<div class="col-sm-18"><select name="config_order" class="form-control">';
        $sel = $data_block['order'] == '1' ? 'selected="selected"' : '';
        $html .= '<option value="1" ' . $sel . ' >' . $lang_block['order_pub_new'] . '</option>';
        $sel = $data_block['order'] == '2' ? 'selected="selected"' : '';
        $html .= '<option value="2" ' . $sel . ' >' . $lang_block['order_pub_old'] . '</option>';
        $sel = $data_block['order'] == '3' ? 'selected="selected"' : '';
        $html .= '<option value="3" ' . $sel . ' >' . $lang_block['order_addtime_new'] . '</option>';
        $sel = $data_block['order'] == '4' ? 'selected="selected"' : '';
        $html .= '<option value="4" ' . $sel . ' >' . $lang_block['order_addtime_old'] . '</option>';
        $html .= '</select></div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * nv_block_config_new_laws_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_new_laws_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 0);
        $return['config']['show_code'] = $nv_Request->get_int('config_show_code', 'post', 0);
        $return['config']['direction'] = $nv_Request->get_title('config_direction', 'post', 'none');
        $return['config']['duration'] = $nv_Request->get_int('config_duration', 'post', 0);
        $return['config']['pauseOnHover'] = $nv_Request->get_int('config_pauseOnHover', 'post', 0);
        $return['config']['duplicated'] = $nv_Request->get_int('config_duplicated', 'post', 0);
        $return['config']['order'] = $nv_Request->get_int('config_order', 'post', 1);
        return $return;
    }

    /**
     * nv_law_block_newg()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_law_block_newg($block_config)
    {
        global $module_info, $lang_module, $global_config, $site_mods, $db, $my_head, $module_name, $nv_laws_listcat, $nv_Cache;

        $module = $block_config['module'];
        $data = $site_mods[$module]['module_data'];
        $modfile = $site_mods[$module]['module_file'];

        $numrow = (isset($block_config['numrow'])) ? $block_config['numrow'] : 10;
        $title_length = (isset($block_config['title_length'])) ? $block_config['title_length'] : 0;
        $show_code = (isset($block_config['show_code'])) ? $block_config['show_code'] : 1;

        $order = ($block_config['order'] == 2 or $block_config['order'] == 4) ? "ASC" : "DESC";
        $order_param = ($block_config['order'] == 1 or $block_config['order'] == 2) ? "publtime" : "addtime";

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $data . '_row WHERE status=1 ORDER BY ' . $order_param . ' ' . $order . ' LIMIT 0,' . $numrow;
        $result = $db->query($sql);
        $numrow = $result->rowCount();

        if (!empty($numrow)) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $modfile . '/block_new_law.tpl')) {
                $block_theme = $global_config['module_theme'];
            } else {
                $block_theme = 'default';
            }

            if ($module_name != $module) {
                $sql = "SELECT id, parentid, alias, title, introduction, keywords, newday FROM " . NV_PREFIXLANG . "_" . $data . "_cat ORDER BY parentid,weight ASC";
                $nv_laws_listcat = $nv_Cache->db($sql, 'id', $module);

                $my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/css/laws.css\" />";

                $temp_lang_module = $lang_module;
                $lang_module = array();
                include NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php';
                $lang_block_module = $lang_module;
                $lang_module = $temp_lang_module;
            } else {
                $lang_block_module = $lang_module;
            }

            $xtpl = new XTemplate('block_new_law.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $modfile);
            $xtpl->assign('LANG', $lang_block_module);
            $xtpl->assign('TEMPLATE', $block_theme);

            while ($row = $result->fetch()) {
                $newday = $row['publtime'] + (86400 * $nv_laws_listcat[$row['cid']]['newday']);

                $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $site_mods[$module]['alias']['detail'] . '/' . $row['alias'];
                $row['link'] = $link;

                if ($title_length > 0) {
                    $row['stitle'] = nv_clean60($row['title'], $title_length);
                } else {
                    $row['stitle'] = $row['title'];
                }

                $row['publtime'] = nv_date('d/m/Y', $row['publtime']);
				$row['addtime'] = nv_date('d/m/Y', $row['addtime']);

                $xtpl->assign('ROW', $row);

                if ($show_code) {
                    $xtpl->parse('main.loop.code');
                }

                if ($newday >= NV_CURRENTTIME) {
                    $xtpl->parse('main.loop.newday');
                }

                $xtpl->parse('main.loop');
            }

            if ($block_config['direction'] != 'none') {
                $block_config['pauseOnHover'] = $block_config['pauseOnHover'] ? 'true' : 'false';
                $block_config['duplicated'] = $block_config['duplicated'] ? 'true' : 'false';
                $xtpl->assign('DATA', $block_config);
                $xtpl->parse('main.marquee_data');
                $xtpl->parse('main.marquee_js');
            }

            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods, $module_name;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $content = nv_law_block_newg($block_config);
    }
}
