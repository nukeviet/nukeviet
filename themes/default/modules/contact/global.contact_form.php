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

if (!nv_function_exists('nv_block_contact_form')) {
    /**
     * nv_block_contact_form()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_contact_form($block_config)
    {
        global $site_mods, $module_name, $nv_Lang;

        if (!isset($site_mods[$block_config['module']]) or $block_config['module'] == $module_name) {
            return '';
        }

        $blockJs = theme_file_exists($block_config['real_theme'] . '/js/contact.js') ? $block_config['real_theme'] : 'default';
        $blockCss = theme_file_exists($block_config['real_theme'] . '/css/contact.css') ? $block_config['real_theme'] : 'default';

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('JS', NV_STATIC_URL . 'themes/' . $blockJs . '/js/contact.js');
        $stpl->assign('CSS', NV_STATIC_URL . 'themes/' . $blockCss . '/css/contact.css');
        $stpl->assign('MODULE', $block_config['module']);

        return $stpl->fetch('block.contact_form.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_contact_form($block_config);
}
