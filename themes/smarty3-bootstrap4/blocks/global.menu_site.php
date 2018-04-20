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

if (!nv_function_exists('nv_block_menu_site')) {

    /**
     * nv_block_menu_site()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_menu_site($block_config)
    {
        global $global_config, $lang_global;
        

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.menu_site.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.menu_site.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\NvSmarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);
        $logo = preg_replace('/\.[a-z]+$/i', '.svg', $global_config['site_logo']);
        if (!file_exists(NV_ROOTDIR . '/' . $logo)) {
            $logo = $global_config['site_logo'];
        }
        $_logo = array(
            'src' => NV_BASE_SITEURL . $logo,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA,
            'width' => $size[0],
            'height' => $size[1]
        );
        $tpl->assign('logo', $_logo);
        return $tpl->fetch('global.menu_site.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_menu_site($block_config);
}