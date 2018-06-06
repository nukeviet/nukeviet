
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

if (!nv_function_exists('nv_block_footer_left')) {

    /**
     * nv_block_footer_left()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_footer_left($block_config)
    {
        global $global_config, $nv_Lang;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.footer_left.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.footer_left.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\Smarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);

        return $tpl->fetch('global.footer_left.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_footer_left($block_config);
}