<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 08 Feb 2014 06:33:39 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_facebook_comment_box_blocks')) {
    /**
     * nv_block_config_facebook_comment_box_blocks()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_facebook_comment_box_blocks($module, $data_block, $lang_block)
    {
        $html = '';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['facebookappid'] . ':</label>';
        $html .= '	<div class="col-sm-18"><input class="form-control" type="text" name="config_facebookappid" value="' . $data_block['facebookappid'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['width'] . ':</label>';
        $html .= '	<div class="col-sm-18"><input class="form-control" type="text" name="config_width" value="' . $data_block['width'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= ' <label class="control-label col-sm-6">' . $lang_block['numpost'] . ':</label>';
        $html .= ' <div class="col-sm-18"><input class="form-control" type="text" name="config_numpost" value="' . $data_block['numpost'] . '"/></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['scheme'] . ':</label>';
        $html .= '	<div class="col-sm-9"> <select class="form-control" name="config_scheme"> ';

        $se1 = ($data_block['scheme'] == 'light') ? 'selected="selected"' : '';
        $se2 = ($data_block['scheme'] == 'dark') ? 'selected="selected"' : '';

        $html .= ' <option value="light"' . $se1 . '> Light </option>';
        $html .= ' <option value="dark"' . $se2 . ' >Dark </option>';

        $html .= ' <\select>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * nv_block_config_facebook_comment_box_blocks_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_facebook_comment_box_blocks_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['facebookappid'] = $nv_Request->get_title('config_facebookappid', 'post', 0);
        $return['config']['width'] = $nv_Request->get_string('config_width', 'post', 0);
        $return['config']['numpost'] = $nv_Request->get_int('config_numpost', 'post', 0);
        $return['config']['scheme'] = $nv_Request->get_title('config_scheme', 'post', 0);

        return $return;
    }

    /**
     * nv_facebook_comment_box_blocks()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_facebook_comment_box_blocks($block_config)
    {
        global $client_info, $module_name;
        $content = '';
        if (!defined('FACEBOOK_JSSDK')) {
            $lang = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';
            $facebookappid = (isset($module_config[$module_name]['facebookappid'])) ? $module_config[$module_name]['facebookappid'] : $block_config['facebookappid'];

            $content .= "<div id=\"fb-root\"></div>
			<script type=\"text/javascript\">
			 (function(d, s, id) {
			 var js, fjs = d.getElementsByTagName(s)[0];
			 if (d.getElementById(id)) return;
			 js = d.createElement(s); js.id = id;
			 js.src = \"//connect.facebook.net/" . $lang . "/all.js#xfbml=1&appId=" . $facebookappid . "\";
			 fjs.parentNode.insertBefore(js, fjs);
			 }(document, 'script', 'facebook-jssdk'));
			</script>";
            define('FACEBOOK_JSSDK', true);
        }
        $content .= '<div class="fb-comments" data-href="' . $client_info['selfurl'] . '" data-num-posts="' . $block_config['numpost'] . '" data-width="' . $block_config['width'] . '" data-colorscheme="' . $block_config['scheme'] . '"></div>';

        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_facebook_comment_box_blocks($block_config);
}
