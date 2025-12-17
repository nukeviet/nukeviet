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

global $module_name, $nv_Lang, $site_mods, $module_config, $global_config;

$content = '';
if ($module_name != $block_config['module'] and defined('NV_SYSTEM') and isset($site_mods[$block_config['module']])) {
    addition_module_assets($block_config['module'], 'both');
    [$block_theme, $dir] = get_block_tpl_dir('block.contact_form.tpl', $block_config['module'], true);

    $nv_Lang->loadModule($site_mods[$block_config['module']]['module_file'], false, true);

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir($dir);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('TEMPLATE', $block_theme);
    $tpl->assign('CONFIG', $block_config);
    $tpl->assign('REQUEST_FORM', md5($block_config['module'] . '_request_form_' . NV_CHECK_SESSION));
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('fcode', nv_module_captcha($block_config['module'])));

    $content = $tpl->fetch('block.contact_form.tpl');
    $nv_Lang->changeLang();
}
