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

global $module_name, $site_mods, $global_config, $lang_global;

$content = '';
if ($module_name != $block_config['module'] and defined('NV_SYSTEM')) {
    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/contact/block.contact_form.tpl')) {
        $block_theme = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/contact/block.contact_form.tpl')) {
        $block_theme = $global_config['site_theme'];
    } else {
        $block_theme = 'default';
    }

    $blockJs = file_exists(NV_ROOTDIR . '/themes/' . $block_theme . '/js/contact.js') ? $block_theme : 'default';
    $blockCss = file_exists(NV_ROOTDIR . '/themes/' . $block_theme . '/css/contact.css') ? $block_theme : 'default';

    $xtpl = new XTemplate('block.contact_form.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/contact');
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('JS', NV_STATIC_URL . 'themes/' . $blockJs . '/js/contact.js');
    $xtpl->assign('CSS', NV_STATIC_URL . 'themes/' . $blockJs . '/css/contact.css');
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $block_theme);
    $xtpl->assign('MODULE', $block_config['module']);

    $xtpl->parse('main');
    $content = $xtpl->text('main');
}
