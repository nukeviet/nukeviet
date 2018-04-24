
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

if (!nv_function_exists('nv_block_social_icon')) {


    function nv_menu_theme_social_config($module, $data_block, $lang_block)

    {
        $html .='';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['title1'] . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_title1" class="form-control" value="' . $data_block['title1'] . '"/></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['facebook'] . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_facebook" class="form-control" value="' . $data_block['facebook'] . '"/></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['google_plus'] . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_google_plus" class="form-control" value="' . $data_block['google_plus'] . '"/></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' .  $lang_block['youtube'] . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_youtube" class="form-control" value="' . $data_block['youtube'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['twitter'] . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_twitter" class="form-control" value="' . $data_block['twitter'] . '"/></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['instagram'] . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_instagram" class="form-control" value="' . $data_block['instagram'] . '"/></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['github'] . ':</label>';
        $html .= '  <div class="col-sm-18"><input type="text" name="config_github" class="form-control" value="' . $data_block['github'] . '"/></div>';
        $html .= '</div>';



        return $html;
    }

    /**
     * nv_menu_theme_social_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_menu_theme_social_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config']['title1'] = $nv_Request->get_title('config_title1', 'post');
        $return['config']['facebook'] = $nv_Request->get_title('config_facebook', 'post');
        $return['config']['google_plus'] = $nv_Request->get_title('config_google_plus', 'post');
        $return['config']['youtube'] = $nv_Request->get_title('config_youtube', 'post');
        $return['config']['twitter'] = $nv_Request->get_title('config_twitter', 'post');
        $return['config']['instagram'] = $nv_Request->get_title('config_instagram', 'post');
        $return['config']['github'] = $nv_Request->get_title('config_github', 'post');
        return $return;
    }

    /**
     * nv_block_social_icon()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_social_icon($block_config)

    {
        global $global_config, $lang_global;
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.social_icon.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.social_icon.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\NvSmarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $tpl->assign('row',$block_config);

        return $tpl->fetch('global.social_icon.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_social_icon($block_config);
}