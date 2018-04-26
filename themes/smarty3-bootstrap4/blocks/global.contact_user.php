<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */
if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_contact_user')) {


    /**
     * nv_department_info()
     *
     * @return
     */
    function nv_block_config_contact_department($module, $data_block, $nv_Lang)
    {
        global $site_mods, $nv_Cache;
        $module = 'contact';
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getBlock('departmentid') . ':</label>';
        $html .= '<div class="col-sm-9"><select name="config_departmentid" class="form-control">';
        $sql = 'SELECT id, full_name FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department WHERE act=1';
        $list = $nv_Cache->db($sql, 'id', $module);

        foreach ($list as $l) {
            $html .= '<option value="' . $l['id'] . '" ' . (($data_block['departmentid'] == $l['id']) ? ' selected="selected"' : '') . '>' . $l['full_name'] . '</option>';
        }
        $html .= '</select></div>';
        $html .= '</div>';

        return $html;
    }

    function nv_block_config_contact_department_submit($module, $nv_Lang)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['departmentid'] = $nv_Request->get_int('config_departmentid', 'post', 0);
        return $return;
    }
    /**
     * nv_block_contact_user()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_contact_user($block_config)
    {
        global $global_config, $site_mods, $db, $nv_Lang;
        $departmentid = $block_config['departmentid'];


        $sql = "SELECT * FROM nv4_vi_contact_supporter WHERE departmentid = $departmentid LIMIT 1";
        $stmt = $db->query($sql);
        $row = $stmt->fetch();
        $others = unserialize($row['others']);
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.contact_user.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.contact_user.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\NvSmarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $tpl->assign('row',$row);
        $tpl->assign('others',$others);
        //print_r($row); die("ok");

        return $tpl->fetch('global.contact_user.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_contact_user($block_config);
}