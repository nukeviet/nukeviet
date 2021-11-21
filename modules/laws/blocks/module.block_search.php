<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 06, 2011, 06:31:13 AM
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!function_exists('nv_law_block_search')) {
    /**
     * nv_block_config_laws_search()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_laws_search($module, $data_block, $lang_block)
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['style'] . ':</label>';
        $html .= ' <div class="col-sm-9">';
        $html .= "<select name=\"config_style\" class=\"form-control\">\n";
        $sel = $data_block['style'] == 'center' ? 'selected="selected"' : '';
        $html .= '<option value="center" ' . $sel . '>Center</option>';
        $sel = $data_block['style'] == 'vertical' ? 'selected="selected"' : '';
        $html .= '<option value="vertical" ' . $sel . '>Vertical</option>';
        $html .= "</select>\n";
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['search_advance'] . ':</label>';
        $html .= '<div class="col-sm-18">';
        $ck = (isset($data_block['search_advance']) and $data_block['search_advance']) ? 'checked="checked"' : '';
        $html .= " <label><input type=\"checkbox\" name=\"config_search_advance\" value=\"1\" " . $ck . ">" . $lang_block['search_advance_note'] . "</label>\n";
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_laws_search_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_laws_search_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['style'] = $nv_Request->get_string('config_style', 'post', '');
        $return['config']['search_advance'] = $nv_Request->get_int('config_search_advance', 'post', 0);
        return $return;
    }

    /**
     * nv_law_block_search()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_law_block_search($block_config)
    {
        global $my_head, $db, $lang_module, $site_mods, $global_config, $module_info, $module_file, $nv_laws_listsubject, $nv_laws_listarea, $nv_laws_listcat, $module_name, $module_config, $nv_Request, $module_data, $nv_Cache;

        $module = $block_config['module'];
        $module_data = $site_mods[$module]['module_data'];
        $module_file = $site_mods[$module]['module_file'];

        $block_file_name = $block_config['style'] == 'center' ? 'block_search_center.tpl' : 'block_search_vertical.tpl';

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/' . $block_file_name)) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate($block_file_name, NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_info['module_theme']);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('TEMPLATE', $module_info['template']);
        $xtpl->assign('MODULE_FILE', $module_file);
        $xtpl->assign('BLOCKID', $block_config['bid']);

        if (!$global_config['rewrite_enable']) {
            $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
            $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
            $xtpl->assign('MODULE_NAME', $module_name);
            $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
            $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
            $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . "index.php");
            $xtpl->parse('main.no_rewrite');
        } else {
            $xtpl->assign('FORM_ACTION', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=search', true));
        }

        $skey = nv_substr($nv_Request->get_title('q', 'get', '', 1), 0, NV_MAX_SEARCH_LENGTH);

        $sfrom = nv_substr($nv_Request->get_title('sfrom', 'get', ''), 0, 10);
        $sto = nv_substr($nv_Request->get_title('sto', 'get', ''), 0, 10);

        $xtpl->assign('FROM', $sfrom);
        $xtpl->assign('TO', $sto);

        $sarea = $nv_Request->get_int('area', 'get', 0);
        $scat = $nv_Request->get_int('cat', 'get', 0);
        $ssubject = $nv_Request->get_int('subject', 'get', 0);
        $sstatus = $nv_Request->get_int('status', 'get', 0);
        $approval = $nv_Request->get_int('approval', 'get', 2);
        $examineid = $nv_Request->get_int('examine', 'get', 0);
        $ssigner = $nv_Request->get_int('signer', 'get', 0);
        $is_advance = $nv_Request->get_int('is_advance', 'get', 0);

        $_nv_laws_listarea = array(
            0 => array(
                "id" => 0,
                "name" => $lang_module['search_area'],
                "alias" => ""
            )
        ) + $nv_laws_listarea;

        if($module_config[$module_name]['activecomm']==0){
            $xtpl->parse('main.pubtime');
        }
        foreach ($_nv_laws_listarea as $area) {
            $xtpl->assign('KEY', $area['id']);
            $xtpl->assign('TITLE', $area['name']);

            $xtpl->assign('SELECTED', $area['id'] == $sarea ? " selected=\"selected\"" : "");
            $xtpl->parse('main.area');
        }

        $nv_laws_listcat = array(
            0 => array(
                "id" => 0,
                "name" => $lang_module['search_cat'],
                "alias" => ""
            )
        ) + $nv_laws_listcat;

        foreach ($nv_laws_listcat as $area) {
            $xtpl->assign('KEY', $area['id']);
            $xtpl->assign('TITLE', $area['name']);

            $xtpl->assign('SELECTED', $area['id'] == $scat ? " selected=\"selected\"" : "");
            $xtpl->parse('main.cat');
        }

        $_nv_laws_listsubject = [];
        if (!empty($nv_laws_listsubject)) {
            $_nv_laws_listsubject = array(
                0 => array(
                    "id" => 0,
                    "title" => $lang_module['search_subject'],
                    "alias" => ""
                )
            ) + $nv_laws_listsubject;
        }

        foreach ($_nv_laws_listsubject as $area) {
            $xtpl->assign('KEY', $area['id']);
            $xtpl->assign('TITLE', $area['title']);

            $xtpl->assign('SELECTED', $area['id'] == $ssubject ? " selected=\"selected\"" : "");
            $xtpl->parse('main.subject');
        }

        $nv_list_status = array();
        $arr_approval = array(
            2 => $lang_module['s_app_status_all'],
            0 => $lang_module['e0'],
            1 => $lang_module['e1']
        );

        $nv_list_status[] = array(
            "id" => 0,
            "title" => $lang_module['s_status_all'],
            "selected" => ""
        );
        $nv_list_status[] = array(
            "id" => 1,
            "title" => $lang_module['s_status_1'],
            "selected" => 1 == $sstatus ? " selected=\"selected\"" : ""
        );
        $nv_list_status[] = array(
            "id" => 2,
            "title" => $lang_module['s_status_2'],
            "selected" => 2 == $sstatus ? " selected=\"selected\"" : ""
        );
        if ($module_config[$module_name]['activecomm']) {
            foreach ($arr_approval as $key => $app) {
                $xtpl->assign('APP', array(
                    'key' => $key,
                    'title' => $app,
                    'selected' => $key == $approval ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.approval.loop');
            }
            $xtpl->parse('main.approval');
        } else {
            foreach ($nv_list_status as $status) {
                $xtpl->assign('status', $status);
                $xtpl->parse('main.exptime.status');
            }
            $xtpl->parse('main.exptime');
        }

        $list_examine = array();
        if ($module_config[$module_name]['activecomm']) {
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_examine ORDER BY weight ASC"; //print_r($sql);die('ok');
            $_query = $db->query($sql);
            while ($_row = $_query->fetch()) {
                $list_examine[$_row['id']] = $_row['title'];
            }
            foreach ($list_examine as $key => $examine) {
                $xtpl->assign('EXAMINE', array(
                    'key' => $key,
                    'title' => $examine,
                    'selected' => $key == $examineid ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.examine.loop');
            }
            $xtpl->parse('main.examine');
        }

        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_signer ORDER BY title ASC";
        $list = array(
            0 => array(
                "id" => 0,
                "title" => $lang_module['s_signer_all'],
                "alias" => ""
            )
        ) + $nv_Cache->db($sql, 'id', $module_name);
        foreach ($list as $row) {
            $xtpl->assign('KEY', $row['id']);
            $xtpl->assign('TITLE', $row['title']);

            $xtpl->assign('SELECTED', $row['id'] == $ssigner ? " selected=\"selected\"" : "");
            $xtpl->parse('main.signer');
        }
        $xtpl->assign('Q', $skey);

        $block_config['search_advance'] = !isset($block_config['search_advance']) ? 0 : $block_config['search_advance'];
        if ($block_config['search_advance']) {
            if (!$is_advance) {
                $xtpl->assign('LANG_ADVANCE', $lang_module['search_advance']);
                $xtpl->assign('IS_ADVANCE', 0);
                $xtpl->parse('main.is_advance');
                $xtpl->parse('main.is_advance_btn.is_advance_class');
            } else {
                $xtpl->assign('LANG_ADVANCE', $lang_module['search_simple']);
            }
            $xtpl->parse('main.is_advance_btn');
        } else {
            $xtpl->assign('LANG_ADVANCE', $lang_module['search_simple']);
            $xtpl->assign('IS_ADVANCE', 1);
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_law_block_search($block_config);
}
