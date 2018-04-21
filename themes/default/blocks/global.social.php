<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 04 May 2014 12:41:32 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_menu_theme_social')) {
    /**
     * nv_menu_theme_social_config()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_menu_theme_social_config($module, $data_block, $lang_block)
    {
        $html = '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['facebook'] . ':</label>';
        $html .= '	<div class="col-sm-18"><input type="text" name="config_facebook" class="form-control" value="' . $data_block['facebook'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['google_plus'] . ':</label>';
        $html .= '	<div class="col-sm-18"><input type="text" name="config_google_plus" class="form-control" value="' . $data_block['google_plus'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['youtube'] . ':</label>';
        $html .= '	<div class="col-sm-18"><input type="text" name="config_youtube" class="form-control" value="' . $data_block['youtube'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['twitter'] . ':</label>';
        $html .= '	<div class="col-sm-18"><input type="text" name="config_twitter" class="form-control" value="' . $data_block['twitter'] . '"/></div>';
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
        $return['config']['facebook'] = $nv_Request->get_title('config_facebook', 'post');
        $return['config']['google_plus'] = $nv_Request->get_title('config_google_plus', 'post');
        $return['config']['youtube'] = $nv_Request->get_title('config_youtube', 'post');
        $return['config']['twitter'] = $nv_Request->get_title('config_twitter', 'post');
        return $return;
    }

    /**
     * nv_menu_theme_social()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_menu_theme_social($block_config)
    {
        global $global_config, $site_mods, $lang_global;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.social.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.social.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.social.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('BLOCK_THEME', $block_theme);
        $xtpl->assign('DATA', $block_config);
        if (!empty($block_config['facebook'])) {
            $xtpl->parse('main.facebook');
        }
        if (!empty($block_config['google_plus'])) {
            $xtpl->parse('main.google_plus');
        }
        if (!empty($block_config['youtube'])) {
            $xtpl->parse('main.youtube');
        }
        if (!empty($block_config['twitter'])) {
            $xtpl->parse('main.twitter');
        }
        if (isset($site_mods['feeds'])) {
            $xtpl->assign('FEEDS_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=feeds');
            $xtpl->parse('main.feeds');
        }
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_theme_social($block_config);
}
