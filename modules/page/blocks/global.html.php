<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 10, 2011 6:04:30 PM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_data_config_html')) {
    /**
     * nv_block_data_config_html()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_data_config_html($module, $data_block, $lang_block)
    {
        if (defined('NV_EDITOR')) {
            require NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
        }

        $htmlcontent = htmlspecialchars(nv_editor_br2nl($data_block['htmlcontent']));
        if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
            $html = nv_aleditor('htmlcontent', '100%', '150px', $htmlcontent);
        } else {
            $html = '<textarea style="width: 100%" name="htmlcontent" id="htmlcontent" cols="20" rows="8">' . $htmlcontent . '</textarea>';
        }

        return '<div class="clearfix">' . $lang_block['htmlcontent'] . ':<br>' . $html . '</div>';
    }

    /**
     * nv_block_data_config_html_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_data_config_html_submit($module, $lang_block)
    {
        global $nv_Request;

        $htmlcontent = $nv_Request->get_editor('htmlcontent', '', NV_ALLOWED_HTML_TAGS);
        $htmlcontent = strtr($htmlcontent, array(
            "\r\n" => '',
            "\r" => '',
            "\n" => ''
        ));

        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['htmlcontent'] = $htmlcontent;

        return $return;
    }

    /**
     * nv_block_global_html()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_global_html($block_config)
    {
        return $block_config['htmlcontent'];
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_global_html($block_config);
}
