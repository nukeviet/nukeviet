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

if (!nv_function_exists('nv_block_slidecate')) {



    /**
     * nv_block_slidecate_config()
     *
     * @param mixed $module
     * @param mixed $nv_Lang
     * @param mixed $lang_block
     * @return
     */
    function nv_block_slidecate_config($module, $data_block, $nv_Lang)

    {
        global $nv_Cache, $site_mods, $db;
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getBlock('category') . ':</label>';
        $html .= '<div class="col-sm-9"><select name="config_category" class="form-control">';
        $sql = 'SELECT catid, title FROM ' . NV_PREFIXLANG . '_' . $site_mods['news']['module_data'] . '_cat WHERE lev=0';
        $list = $nv_Cache->db($sql, '', 'news');

        foreach ($list as $l) {
            $html .= '<option value="' . $l['catid'] . '" ' . (($data_block['catid'] == $l['id']) ? ' selected="selected"' : '') . '>' . $l['title'] . '</option>';
        }
        $html .= '</select></div>';
        $html .= '</div>';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getBlock('numrow') . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="config_numrow" class="form-control w100" size="5" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '  </div>';
        $html .= '  </div>';
        return $html;
    }


    /**
     * nv_block_navigation_config_submit()
     *
     * @param mixed $module
     * @param mixed $nv_Lang
     * @return
     */
    function nv_block_slidecate_config_submit($module, $nv_Lang)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['config_category'] = $nv_Request->get_title('config_category', 'post');

        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        return $return;
    }

    /**
     * nv_block_slidecate()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_slidecate($block_config)
    {

        global $global_config, $nv_Lang, $db;
        $category = $block_config['config_category'];
        $numrow = $block_config['numrow'];
        $sql = "SELECT * FROM nv4_vi_news_$category LIMIT $numrow";
        $stmt = $db->query($sql);
        $row = $stmt->fetchAll();
        $row1 = $row[0];
        $l = array();
        for($i=1; $i<$numrow; $i++){
            $l[]= $i;
        }


        //print_r($row1); die("ok");
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.slidecate.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.slidecate.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\Smarty();

        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $tpl->assign('row',$row);
        $tpl->assign('row1',$row1);
        $tpl->assign('l',$l);
        return $tpl->fetch('global.slidecate.tpl');

    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_slidecate($block_config);
}