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

if (!nv_function_exists('nv_menu_theme_social_btns')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_menu_theme_social_btns_config($module, $data_block)
    {
        global $nv_Lang;

        $names = array_map('trim', explode(',', $data_block['name']));
        $urls = array_map('trim', explode(',', $data_block['url']));
        $icons = array_map('trim', explode(',', $data_block['icon']));
        $colors = array_map('trim', explode(',', $data_block['color']));

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(__DIR__);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('CONFIG', $data_block);

        $tpl->assign('NAMES', $names);
        $tpl->assign('URLS', $urls);
        $tpl->assign('ICONS', $icons);
        $tpl->assign('COLORS', $colors);

        return $tpl->fetch('global.social_btns.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_menu_theme_social_btns_submit($module)
    {
        global $nv_Request;

        $names = $nv_Request->get_typed_array('social_name', 'post', 'title', []);
        $urls = $nv_Request->get_typed_array('social_url', 'post', 'title', []);
        $icons = $nv_Request->get_typed_array('social_icon', 'post', 'title', []);
        $colors = $nv_Request->get_typed_array('social_color', 'post', 'title', []);

        $return = [];
        $return['error'] = [];
        $return['config']['name'] = [];
        $return['config']['url'] = [];
        $return['config']['icon'] = [];
        $return['config']['color'] = [];

        if (!empty($names)) {
            foreach ($names as $key => $name) {
                if (!empty($name) and !empty($urls[$key]) and nv_is_url($urls[$key])) {
                    $return['config']['name'][] = $name;
                    $return['config']['url'][] = $urls[$key];
                    $return['config']['icon'][] = $icons[$key];
                    $return['config']['color'][] = $colors[$key];
                }
            }
        }
        $return['config']['name'] = !empty($return['config']['name']) ? implode(',', $return['config']['name']) : '';
        $return['config']['url'] = !empty($return['config']['url']) ? implode(',', $return['config']['url']) : '';
        $return['config']['icon'] = !empty($return['config']['icon']) ? implode(',', $return['config']['icon']) : '';
        $return['config']['color'] = !empty($return['config']['color']) ? implode(',', $return['config']['color']) : '';

        return $return;
    }

    /**
     * @param array $block_config
     * @return string
     */
    function nv_menu_theme_social_btns($block_config)
    {
        global $nv_Lang, $site_mods;

        if (empty($block_config['name'])) {
            return '';
        }

        $block_config['name'] = array_map('trim', explode(',', $block_config['name']));
        $block_config['url'] = array_map('trim', explode(',', $block_config['url']));
        $block_config['icon'] = array_map('trim', explode(',', $block_config['icon']));
        $block_config['color'] = array_map('trim', explode(',', $block_config['color']));

        if (isset($site_mods['feeds']))  {
            $block_config['name'][] = $site_mods['feeds']['custom_title'];
            $block_config['url'][] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=feeds';
            $block_config['icon'][] = 'fa-solid fa-rss';
            $block_config['color'][] = 'ff9900';
        }

        $socials = [];
        foreach ($block_config['name'] as $key => $name) {
            $socials[] = [
                'name' => $name,
                'url' => $block_config['url'][$key],
                'icon' => !empty($block_config['icon'][$key]) ? $block_config['icon'][$key] : 'fa-solid fa-share-nodes',
                'color' => !empty($block_config['color'][$key]) ? $block_config['color'][$key] : 'ff6600'
            ];
        }

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($block_config['real_path']);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('SOCIALS', $socials);

        return $tpl->fetch('global.social_btns.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_theme_social_btns($block_config);
}
