<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 06, 2011, 06:31:13 AM
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!function_exists('nv_laws_block_subject')) {
    /**
     * nv_block_config_laws_subject()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_laws_subject($module, $data_block, $lang_block)
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['title_length'] . ':</label>';
        $html .= '<div class="col-sm-9">';
        $html .= "<select name=\"config_title_length\" class=\"form-control\">\n";
        $html .= "<option value=\"\">" . $lang_block['title_length'] . "</option>\n";
        for ($i = 0; $i < 100; ++$i) {
            $html .= "<option value=\"" . $i . "\" " . (($data_block['title_length'] == $i) ? " selected=\"selected\"" : "") . ">" . $i . "</option>\n";
        }
        $html .= "</select>\n";
        $html .= '</div';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_laws_subject_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_laws_subject_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 0);
        return $return;
    }

    /**
     * nv_laws_block_subject()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_laws_block_subject($block_config)
    {
        global $db, $lang_module, $module_info, $site_mods, $global_config, $nv_laws_listsubject, $module_name, $nv_Cache;

        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        $mod_file = $site_mods[$module]['module_file'];

        if ($module != $module_name) {
            $nv_laws_listsubject = array();
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $mod_data . "_subject ORDER BY weight ASC";
            $list = $nv_Cache->db($sql, 'id', $module_name);
            foreach ($list as $row) {
                $nv_laws_listsubject[$row['id']] = $row;
            }
        }

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $mod_file . '/block_subject.tpl')) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate("block_subject.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file);

        foreach ($nv_laws_listsubject as $cat) {
            $cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=subject/" . $cat['alias'];
            $cat['title0'] = nv_clean60($cat['title'], $block_config['title_length']);

            $xtpl->assign('DATA', $cat);
            $xtpl->parse('main.loop');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_laws_block_subject($block_config);
}