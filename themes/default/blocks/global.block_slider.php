<?php

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_function_exists('nv_block_slider')) {
    function nv_block_slider($block_config) {
        global $db, $global_config, $lang_global, $language_array;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.block_slider.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.block_slider.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.block_slider.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('BLOCK_THEME', $block_theme);

        $banners = $db->query('SELECT file_name, click_url, target FROM nv4_banners_rows');
        foreach ($banners as $banner) {
            $xtpl->assign('ROW', $banner);
            $xtpl->parse('main.slider_loop');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_slider($block_config);
}
