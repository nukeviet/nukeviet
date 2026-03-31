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

if (!nv_function_exists('nv_menu_theme_default_footer')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_menu_theme_default_footer_config($module, $data_block)
    {
        global $nv_Lang, $site_mods;

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(__DIR__);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('CONFIG', $data_block);
        $tpl->assign('SITE_MODS', $site_mods);

        return $tpl->fetch('global.menu_footer.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_menu_theme_default_footer_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config']['module_in_menu'] = $nv_Request->get_typed_array('module_in_menu', 'post', 'title', []);

        return $return;
    }

    /**
     * @param array $block_config
     * @return string
     */
    function nv_menu_theme_default_footer($block_config)
    {
        global $site_mods;

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($block_config['real_path']);
        $tpl->assign('DATA', $block_config);
        $tpl->assign('SITE_MODS', $site_mods);

        return $tpl->fetch('global.menu_footer.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_theme_default_footer($block_config);
}
