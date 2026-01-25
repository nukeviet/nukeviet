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

        // Multiple languages
        $langs = [];
        if ($global_config['lang_multi'] and count($global_config['allow_sitelangs']) > 1) {
            foreach ($global_config['allow_sitelangs'] as $lang_i) {
                $langs[] = [
                    'name' => $language_array[$lang_i]['name'],
                    'url' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_i,
                    'sel' => $lang_i == NV_LANG_DATA ? true : false
                ];
            }
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('LANGS', $langs);
        return $stpl->fetch('global.block_language.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_language($block_config);
}
