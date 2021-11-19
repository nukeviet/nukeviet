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

if (! nv_function_exists('nv_theme_slide')) {
    function nv_theme_slide_config($module, $data_block, $lang_block)
    {
        $html = '<div class="form-group">';
    	$html .= '<label class="control-label col-sm-6">' . $lang_block['name'] . ': </label>';
        $html .= '<div class="col-sm-18"><input type="text" name="config_name" class="form-control" value="' . $data_block['name'] . '"/></div><div/>';
		
        $html .= '<div class="form-group">';
    	$html .= '<label class="control-label col-sm-6">' . $lang_block['url'] . ': </label>';
        $html .= '<div class="col-sm-18"><input type="text" name="config_name" class="form-control" value="' . $data_block['url'] . '"/></div><div/>';

        return $html;
    }

    function nv_theme_slide_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
		$return['config']['name'] = $nv_Request->get_title('config_name', 'post');
		$return['config']['url'] = $nv_Request->get_title('config_url', 'post');
        return $return;
    }

    function nv_theme_slide($block_config)
    {
        global $global_config, $site_mods, $lang_global, $lang_block;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.block_slide.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.block_slide.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = '';
        }

        $xtpl = new XTemplate('global.block_slide.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_theme_slide($block_config);
}
