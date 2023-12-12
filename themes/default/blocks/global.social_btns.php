<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_menu_theme_social_btns')) {
    /**
     * nv_menu_theme_social_btns_config()
     *
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

        $html = '<script>$((function(){$("body").on("click",".social-add",(function(){var t=$(this).parents(".social-item"),a=t.clone();$("input",a).attr("value","").val(""),t.after(a)})),$("body").on("click",".social-del",(function(){var t=$(this).parents(".social-btns"),a=$(this).parents(".social-item");$(".social-item",t).length>1?a.remove():$("input",a).attr("value","").val("")}))}));</script>';
        $html .= '<div class="social-btns">';
        foreach ($names as $key => $name) {
            $html .= '<div class="row margin-bottom social-item">';
            $html .= '	<div class="col-sm-6"><div class="input-group margin-bottom"><span class="input-group-btn"><button class="btn btn-default social-add" type="button"><i class="fa fa-plus"></i></button><button class="btn btn-default social-del" type="button"><i class="fa fa-times"></i></button></span><input type="text" name="social_name[]" class="form-control" value="' . $name . '" placeholder="' . $nv_Lang->getModule('social_name') . '"/></div></div>';
            $html .= '	<div class="col-sm-10"><div class="margin-bottom"><input type="text" name="social_url[]" class="form-control" value="' . $urls[$key] . '" placeholder="' . $nv_Lang->getModule('social_url') . '"/></div></div>';
            $html .= '	<div class="col-sm-4"><div class="margin-bottom"><input type="text" name="social_icon[]" class="form-control" value="' . $icons[$key] . '" placeholder="' . $nv_Lang->getModule('social_icon') . '"/></div></div>';
            $html .= '	<div class="col-sm-4"><div class="input-group margin-bottom"><span class="input-group-addon">#</span><input type="text" name="social_color[]" class="form-control" value="' . $colors[$key] . '" placeholder="' . $nv_Lang->getModule('social_color') . '"/></div></div>';
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_menu_theme_social_btns_submit()
     *
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
     * nv_menu_theme_social_btns()
     *
     * @param array $block_config
     * @return string
     */
    function nv_menu_theme_social_btns($block_config)
    {
        global $nv_Lang;

        if (empty($block_config['name'])) {
            return '';
        }

        $block_config['name'] = array_map('trim', explode(',', $block_config['name']));
        $block_config['url'] = array_map('trim', explode(',', $block_config['url']));
        $block_config['icon'] = array_map('trim', explode(',', $block_config['icon']));
        $block_config['color'] = array_map('trim', explode(',', $block_config['color']));

        $block_config['name'][] = 'feeds';
        $block_config['url'][] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=feeds';
        $block_config['icon'][] = 'fa fa-rss';
        $block_config['color'][] = 'ff9900';

        $socials = [];
        foreach ($block_config['name'] as $key => $name) {
            $socials[] = [
                'name' => $name,
                'url' => $block_config['url'][$key],
                'icon' => !empty($block_config['icon'][$key]) ? $block_config['icon'][$key] : 'fa fa-share-alt',
                'color' => !empty($block_config['color'][$key]) ? $block_config['color'][$key] : 'ff6600'
            ];
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('SOCIALS', $socials);

        return $stpl->fetch('global.social_btns.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_theme_social_btns($block_config);
}
