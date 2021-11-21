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

if (!nv_function_exists('nv_block_theme_switch')) {
    /**
     * nv_block_theme_switch()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_theme_switch($block_config)
    {
        global $global_config, $lang_global;

        if (empty($global_config['array_user_allowed_theme'])) {
            return '';
        }

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.theme_switch.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.theme_switch.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.theme_switch.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('GLANG', $lang_global);
        $xtpl->assign('BLOCK_THEME', $block_theme);
        $xtpl->assign('CONFIG', $block_config);
        $xtpl->assign('TOKEND', NV_CHECK_SESSION);

        foreach ($global_config['array_user_allowed_theme'] as $theme) {
            $xtpl->assign('USER_THEME', [
                'key' => $theme,
                'title' => $theme,
                'selected' => $theme == $global_config['site_theme'] ? ' selected="selected"' : '',
            ]);
            $xtpl->parse('main.loop');
        }

        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_theme_switch($block_config);
}
