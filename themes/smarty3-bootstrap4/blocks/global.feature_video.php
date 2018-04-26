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

if (!nv_function_exists('nv_block_feature_video')) {

    /**
     * nv_block_config_video()
     *
     * @param mixed $mod_name
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_video($mod_name, $data_block, $lang_block)
    {
        global $db, $site_mods, $nv_Lang;
        $mod_name = 'videoclips';
        //print_r($data_block); die("ok");

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getBlock('topicvideo') . ':</label>';
        $html .= '	<div class="col-sm-18"><select name="config_idtopic" class="form-control"><option value="0">' . $nv_Lang->getBlock('topicvideo_all') . '</option>';

        $db->sqlreset()->select('*')->from(NV_PREFIXLANG . '_' . $site_mods[$mod_name]['module_data'] . '_topic')->where('status= 1')->order('weight ASC');
        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            $sl = ($data_block['idtopic'] == $row['id']) ? ' selected="selected"' : '';
            $html .= '<option value="' . $row['id'] . '"' . $sl . '>' . $row['title'] . '</option>';
        }
        $html .= '</select></div>';


        return $html;
    }

    /**
     * nv_block_config_video_submit()
     *
     * @param mixed $mod_name
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_video_submit($mod_name, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['idtopic'] = $nv_Request->get_int('config_idtopic', 'post', 0);

        return $return;
    }



    /**
     * nv_block_feature_video()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_feature_video($block_config)
    {
        global $global_config, $nv_Lang, $db;
        $idvideo = $block_config['idtopic'];

        $sql = "SELECT * FROM nv4_vi_videoclips_clip WHERE tid = $idvideo";
        $stmt = $db->query($sql);
        $row = $stmt->fetch();

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.feature_video.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.feature_video.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\NvSmarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);

        $tpl->assign('row',$row);
        if(preg_match("/^(http(s)?\:)?\/\/([w]{3})?\.youtube[^\/]+\/watch\?v\=([^\&]+)\&?(.*?)$/is", $row['externalpath'], $m)){
            $tpl->assign('code',$m['4']);

        }

        return $tpl->fetch('global.feature_video.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_feature_video($block_config);
}