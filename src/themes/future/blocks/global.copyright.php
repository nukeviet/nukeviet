<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_copyright_info')) {
    /**
     * @param string $module
     * @param array $data_block
     * @return string
     */
    function nv_copyright_info_config($module, $data_block)
    {
        global $nv_Lang;

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(__DIR__);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('CONFIG', $data_block);

        return $tpl->fetch('global.copyright.config.tpl');
    }

    /**
     * @return array
     */
    function nv_copyright_info_submit()
    {
        global $nv_Request;

        $return = [];
        $return['error'] = [];
        $return['config']['copyright_by'] = $nv_Request->get_title('copyright_by', 'post');
        $return['config']['copyright_url'] = $nv_Request->get_title('copyright_url', 'post');
        $return['config']['design_by'] = $nv_Request->get_title('design_by', 'post');
        $return['config']['design_url'] = $nv_Request->get_title('design_url', 'post');
        $return['config']['siteterms_url'] = $nv_Request->get_title('siteterms_url', 'post');

        return $return;
    }

    /**
     * @param array $block_config
     * @return string
     */
    function nv_copyright_info($block_config)
    {
        global $global_config, $nv_Lang;

        empty($block_config['copyright_by']) && $block_config['copyright_by'] = $global_config['site_name'];
        empty($block_config['copyright_url']) && $block_config['copyright_url'] = 'http://' . $global_config['my_domains'][0];

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($block_config['real_path']);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('DATA', $block_config);

        return $tpl->fetch('global.copyright.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_copyright_info($block_config);
}
