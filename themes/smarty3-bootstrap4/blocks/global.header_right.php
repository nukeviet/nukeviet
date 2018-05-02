
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

if (!nv_function_exists('nv_block_header_right')) {


    function nv_block_config_header_right($module, $data_block, $nv_Lang)

    {
        $html .='';


        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $nv_Lang->getBlock('facebook') . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_facebook" class="form-control" value="' . $data_block['facebook'] . '"/></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $nv_Lang->getBlock('google_plus') . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_google_plus" class="form-control" value="' . $data_block['google_plus'] . '"/></div>';
        $html .= '</div>';


        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $nv_Lang->getBlock('twitter') . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_twitter" class="form-control" value="' . $data_block['twitter'] . '"/></div>';
        $html .= '</div>';





        return $html;
    }

    /**
     * nv_menu_theme_social_submit()
     *
     * @param mixed $module
     * @param mixed $nv_Lang
     * @return
     */
    function nv_block_config_header_right_submit($module, $nv_Lang)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();

        $return['config']['facebook'] = $nv_Request->get_title('config_facebook', 'post');
        $return['config']['google_plus'] = $nv_Request->get_title('config_google_plus', 'post');

        $return['config']['twitter'] = $nv_Request->get_title('config_twitter', 'post');

        return $return;
    }

    /**
     * nv_block_header_right()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_header_right($block_config)
    {
        global $global_config, $nv_Lang;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.header_right.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.header_right.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\NvSmarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $tpl->assign('row',$block_config);
        return $tpl->fetch('global.header_right.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_header_right($block_config);
}