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

if (!nv_function_exists('nv_block_navigation')) {


    /**
     * nv_block_config_tophits_blocks()
     *
     * @param mixed $module
     * @param mixed $nv_Lang
     * @param mixed $lang_block
     * @return
     */
    function nv_block_navigation_config($module, $data_block, $nv_Lang)

    {
        global $nv_Cache, $site_mods, $db;
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $nv_Lang->getBlock('title1') . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="config_title1" class="form-control w100" size="5" value="' . $data_block['title1'] . '"/></div>';
        $html .= '  </div>';

        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $nv_Lang->getBlock('category') . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="config_category" class="form-control w100" size="5" value="' . $data_block['category'] . '"/></div>';
        $html .= '  </div>';

        $html .= '</div>';
        return $html;
    }


    /**
     * nv_block_config_tophits_blocks_submit()
     *
     * @param mixed $module
     * @param mixed $nv_Lang
     * @return
     */
    function nv_block_navigation_config_submit($module, $nv_Lang)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['config_title1'] = $nv_Request->get_title('config_title1', 'post');
        $return['config']['config_category'] = $nv_Request->get_title('config_category', 'post');
        return $return;
    }


    function nv_block_navigation($block_config)
    {
        global $global_config, $nv_Lang;
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.navigation.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.navigation.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\NvSmarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);

        return $tpl->fetch('global.navigation.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_navigation($block_config);
}