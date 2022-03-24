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

if (!nv_function_exists('nv_menu_theme_default_footer')) {
    /**
     * nv_menu_theme_default_footer_config()
     *
     * @param string $module
     * @param array  $data_block
     * @param array  $lang_block
     * @return string
     */
    function nv_menu_theme_default_footer_config($module, $data_block, $lang_block)
    {
        global $site_mods;

        if (empty($data_block['module_in_menu']) or !is_array($data_block['module_in_menu'])) {
            $data_block['module_in_menu'] = [];
        }

        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['module_in_menu'] . ':</label>';
        $html .= '<div class="col-sm-18">';
        foreach ($site_mods as $modname => $modvalues) {
            $checked = in_array($modname, $data_block['module_in_menu'], true) ? ' checked="checked"' : '';
            $html .= '<div class="w150 pull-left"><div class="ellipsis"><label style="text-align: left"><input type="checkbox" ' . $checked . ' value="' . $modname . '" name="module_in_menu[]">' . $modvalues['custom_title'] . '</label></div></div>';
        }
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_menu_theme_default_footer_submit()
     *
     * @param string $module
     * @param array  $lang_block
     * @return array
     */
    function nv_menu_theme_default_footer_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config']['module_in_menu'] = $nv_Request->get_typed_array('module_in_menu', 'post', 'string');

        return $return;
    }

    /**
     * nv_menu_theme_default_footer()
     *
     * @param array $block_config
     * @return string
     */
    function nv_menu_theme_default_footer($block_config)
    {
        global $global_config, $site_mods, $lang_global;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.menu_footer.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.menu_footer.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.menu_footer.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('BLOCK_THEME', $block_theme);
        $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);

        $a = 0;
        foreach ($site_mods as $modname => $modvalues) {
            if (in_array($modname, $block_config['module_in_menu'], true) and !empty($modvalues['funcs'])) {
                $_array_menu = ['title' => $modvalues['custom_title'], 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname];
                $xtpl->assign('FOOTER_MENU', $_array_menu);
                $xtpl->parse('main.footer_menu');
                ++$a;
            }
        }
        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_theme_default_footer($block_config);
}
