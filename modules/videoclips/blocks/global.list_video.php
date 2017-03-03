<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 02:27:09 GMT
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!nv_function_exists('nv4_block_list_video')) {

    function nv_block_config_list_video($module, $data_block, $lang_block)
    {
        global $site_mods;
        
        $html_input = '';
        $html = '';
        $html = '<tr>';
        $html .= '<td>' . $lang_block['numrow'] . '</td>';
        $html .= '<td><input type="text" class="form-control w200" name="config_numrow" size="5" value="' . $data_block['numrow'] . '"/></td>';
        $html .= '</tr>';
        return $html;
    }

    function nv_block_config_list_video_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        return $return;
    }

    function nv4_block_list_video($block_config)
    {
        global $global_config, $db, $site_mods, $module_name, $module_info, $module_file;
        
        $mod_name = $block_config['module'];
        $db->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $site_mods[$mod_name]['module_data'] . '_clip')
            ->where('status = 1')
            ->order('id DESC')
            ->limit($block_config['numrow']);
        
        $sth = $db->prepare($db->sql());
        $sth->execute();
        $list = $sth->fetchAll();
        
        if (isset($site_mods[$mod_name])) {
            $mod_file = $site_mods[$mod_name]['module_file'];
            $mod_data = $site_mods[$mod_name]['module_data'];
            $mod_upload = $site_mods[$mod_name]['module_upload'];
        }
        
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $mod_file . '/block_list_video.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $mod_file . '/block_list_video.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }
        
        $xtpl = new XTemplate('block_list_video.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('TEMPLATE', $block_theme);
        
        foreach ($list as $row) {
            if (!empty($row['img'])) {
                $imageinfo = nv_ImageInfo(NV_ROOTDIR . '/' . $row['img'], 120, true, NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $mod_upload);
                $row['image'] = $imageinfo['src'];
            } else {
                $row['image'] = NV_BASE_SITEURL . "themes/" . $block_theme . "/images/" . $mod_file . "/video.png";
            }
            
            $row['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=video-' . $row['alias'], true);
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.loop');
        }
        $xtpl->parse('main');
        return $xtpl->text('main');
    }

}

if (defined('NV_SYSTEM')) {
    $content = nv4_block_list_video($block_config);
}