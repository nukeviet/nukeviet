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

if (!nv_function_exists('nv_block_language')) {
    /**
     * nv_block_language()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_language($block_config)
    {
        global $global_config, $nv_Lang, $language_array;

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/blocks/global.block_language.tpl');
        $xtpl = new XTemplate('global.block_language.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('BLOCK_THEME', $block_theme);
        $xtpl->assign('SELECT_LANGUAGE', $nv_Lang->getGlobal('langsite'));

        // Multiple languages
        if ($global_config['lang_multi'] and sizeof($global_config['allow_sitelangs']) > 1) {
            foreach ($global_config['allow_sitelangs'] as $lang_i) {
                $xtpl->assign('LANGSITENAME', $language_array[$lang_i]['name']);
                $xtpl->assign('LANGSITEURL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_i);

                if (NV_LANG_DATA != $lang_i) {
                    $xtpl->parse('main.language.langitem');
                } else {
                    $xtpl->parse('main.language.langcuritem');
                }
            }

            $xtpl->parse('main.language');
        }

        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_language($block_config);
}
