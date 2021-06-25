<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_data_config_html')) {
    /**
     * nv_block_data_config_html()
     *
     * @param string $module
     * @param array  $data_block
     * @param array  $lang_block
     * @return string
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
     * @param string $module
     * @param array  $lang_block
     * @return array
     */
    function nv_block_data_config_html_submit($module, $lang_block)
    {
        global $nv_Request;

        $htmlcontent = $nv_Request->get_editor('htmlcontent', '', NV_ALLOWED_HTML_TAGS);
        $htmlcontent = strtr($htmlcontent, [
            "\r\n" => '',
            "\r" => '',
            "\n" => ''
        ]);

        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['htmlcontent'] = $htmlcontent;

        return $return;
    }

    /**
     * nv_block_global_html()
     *
     * @param array $block_config
     * @return mixed
     */
    function nv_block_global_html($block_config)
    {
        return $block_config['htmlcontent'];
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_global_html($block_config);
}
