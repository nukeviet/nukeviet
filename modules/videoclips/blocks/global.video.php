<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2014 11:34:27 AM
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!nv_function_exists('nv_block_video')) {

    function nv_block_config_video($mod_name, $data_block, $lang_block)
    {
        global $db, $site_mods;
        
        $html = '';
        $html .= '<tr>';
        $html .= '	<td>Topic Video</td>';
        $html .= '	<td><select name="config_idtopic"><option value="0">---------------</option>';
        
        $db->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $site_mods[$mod_name]['module_data'] . '_topic')
            ->where('status= 1')
            ->order('weight ASC');
        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            $sl = ($data_block['idtopic'] == $row['id']) ? ' selected="selected"' : '';
            $html .= '<option value="' . $row['id'] . '"' . $sl . '>' . $row['title'] . '</option>';
        }
        $html .= '</select></td>';
        
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '	<td>Number Video</td>';
        $html .= '	<td><input type="text" name="config_numrow" class="form-control w100" size="5" value="' . $data_block['numrow'] . '"/></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '	<td>Other Video</td>';
        $html .= '	<td><input type="text" name="config_other" class="form-control w100" size="5" value="' . $data_block['other'] . '"/></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '	<td>Length Title Video</td>';
        $html .= '	<td><input type="text" name="config_length" class="form-control w100" size="5" value="' . $data_block['length'] . '"/></td>';
        $html .= '</tr>';
        
        return $html;
    }

    function nv_block_config_video_submit($mod_name, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['idtopic'] = $nv_Request->get_int('config_idtopic', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['other'] = $nv_Request->get_int('config_other', 'post', 0);
        $return['config']['length'] = $nv_Request->get_int('config_length', 'post', 0);
        return $return;
    }

    function nv_block_video($block_config)
    {
        global $module_array_cat, $module_info, $db, $module_config, $global_config, $site_mods, $module_name, $module_file;
        
        $mod_name = $block_config['module'];
        $mod_file = $site_mods[$mod_name]['module_file'];
        $mod_data = $site_mods[$mod_name]['module_data'];
        
        $mod_template = $global_config['module_theme'];
        
        if (!file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $mod_file)) {
            if (file_exists(NV_ROOTDIR . '/themes/default/modules/' . $mod_file)) {
                $mod_template = 'default';
            }
        }
        
        $block_theme = file_exists(NV_ROOTDIR . '/themes/' . $mod_template . '/modules/' . $mod_file . '/block_video.tpl') ? $mod_template : 'default';
        
        $xtpl = new XTemplate('block_video.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('TEMPLATE', $mod_template);
        $xtpl->assign('MODULE_NAME', $mod_name);
        $xtpl->assign('MODULE_FILE', $mod_file);
        
        $array_block_video = array();
        $limit = $block_config['numrow'] + $block_config['other'];
        $db->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $mod_data . '_clip')
            ->order('id DESC')
            ->limit($limit);
        if (empty($block_config['idtopic'])) {
            $db->where('status= 1');
        } else {
            $db->where('tid=' . $block_config['idtopic'] . ' AND status= 1');
        }
        $result = $db->query($db->sql());
        $i = 0;
        while ($row = $result->fetch()) {
            $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
            $row['titlevideo'] = nv_clean60($row['title'], $block_config['length']);
            
            if (!empty($row['img'])) {
                $imageinfo = nv_ImageInfo(NV_ROOTDIR . '/' . $row['img'], 120, true, NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name);
                $row['img'] = $imageinfo['src'];
            } else {
                $row['img'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $mod_file . "/video.png";
            }
            $row['hometext60'] = nv_clean60($row['hometext'], 60);
            
            $xtpl->assign('DATA', $row);
            
            if ($i < $block_config['numrow']) {
                //https://www.youtube.com/watch?v=CNvBRHdSIKQ
                if (preg_match("/^(http(s)?\:)?\/\/([w]{3})?\.youtube[^\/]+\/watch\?v\=([^\&]+)\&?(.*?)$/is", $row['externalpath'], $m)) {
                    $xtpl->assign('CODE', $m[4]);
                    $xtpl->parse('main.youtube');
                } else if (preg_match("/(http(s)?\:)?\/\/youtu?\.be[^\/]?\/([^\&]+)$/isU", $row['externalpath'], $m)) {
                    $xtpl->assign('CODE', $m[3]);
                    $xtpl->parse('main.youtube');
                } else {
                    $row['filepath'] = urlencode(!empty($row['internalpath']) ? NV_BASE_SITEURL . $row['internalpath'] : $row['externalpath']);
                    $xtpl->assign('DETAILCONTENT', $row);
                    $xtpl->parse('main.player');
                }
            } else {
                $xtpl->parse('main.other');
            }
            ++$i;
        }
        $xtpl->parse('main');
        return $xtpl->text('main');
    }

}

if (defined('NV_SYSTEM')) {
    $content = nv_block_video($block_config);
}
