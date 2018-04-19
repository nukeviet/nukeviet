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

if (!nv_function_exists('nv_block_blog')) {

    /**
     * nv_block_blog()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_blog($block_config)
    {
        global $global_config, $lang_global;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/template/blog.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/template/blog.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $smarty = new Smarty();
        $smarty->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/template');
        $smarty->enableSecurity();
        $smarty->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);

        return $smarty->fetch('blog.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_blog($block_config);
}