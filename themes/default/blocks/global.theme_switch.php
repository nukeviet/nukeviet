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

if (!nv_function_exists('nv_block_theme_switch')) {
    /**
     * nv_block_theme_switch()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_theme_switch($block_config)
    {
        global $global_config, $nv_Lang;

        if (empty($global_config['array_user_allowed_theme']) or sizeof($global_config['array_user_allowed_theme']) < 2) {
            return '';
        }

        $themes = [];
        foreach ($global_config['array_user_allowed_theme'] as $theme) {
            $themes[] = [
                'key' => $theme,
                'title' => ucfirst($theme),
                'sel' => $theme == $global_config['site_theme'] ? true : false
            ];
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('CONFIG', $block_config);
        $stpl->assign('TOKEND', NV_CHECK_SESSION);
        $stpl->assign('THEMES', $themes);

        return $stpl->fetch('global.theme_switch.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_theme_switch($block_config);
}
