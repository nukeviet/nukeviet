<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thinh (thinhwebhp@gmail.com)
 * @Copyright (C) 2014 Mr.Thinh. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tuesday, 21 June 2016 12:41:32 GMT
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_function_exists('nv_slider')) {
    // function nv_theme_contact_config($module, $data_block, $lang_block)
    // {
	// 	$html = '<div class="form-group">';
    //     $html .= '<label class="control-label col-sm-6">' . $lang_block['title'] . ':</label>';
    //     $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_title" value="' . $data_block['title'] . '"></div>';
    //     $html .= '</div>';

    //     return $html;
    // }

    // function nv_theme_contact_submit($module, $lang_block)
    // {
    //     global $nv_Request;
    //     $return = array();
    //     $return['error'] = array();
	// 	$return['config']['config_title'] = $nv_Request->get_title('config_title', 'post');
    //     return $return;
    // }

    function nv_slider($block_config)
    {
        global $global_config, $site_mods, $lang_global, $lang_block;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.block_slider.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.block_slider.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.block_slider.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
        $xtpl->assign('LANG', $lang_block);
        $xtpl->assign('TEMPLATE', $block_theme);
        $xtpl->assign('DATA', $block_config);
		
        // if (! empty($block_config['title'])) {
        //     $xtpl->parse('main.title');
        // }
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_slider($block_config);
}