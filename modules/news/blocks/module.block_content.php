<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

global $global_config, $module_name, $module_info, $module_file, $client_info;

list($template, $dir) = get_module_tpl_dir('block_content.tpl', true);
$xtpl = new XTemplate('block_content.tpl', $dir);
$xtpl->assign('TEMPLATE', $template);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('LINK_CONTENT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['content']);
$xtpl->assign('LINK_ADD_CONTENT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['content'] . '&amp;contentid=0&amp;checkss=' . md5('0' . NV_CHECK_SESSION));

$xtpl->parse('main');
$content = $xtpl->text('main');
