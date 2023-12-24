<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

global $global_config, $module_name, $module_info, $module_file, $client_info, $nv_Lang;

$stpl = new \NukeViet\Template\NVSmarty();
$stpl->setTemplateDir($block_config['real_path'] . '/smarty');
$stpl->assign('LANG', $nv_Lang);
$stpl->assign('LINK_CONTENT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['content']);
$stpl->assign('LINK_ADD_CONTENT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['content'] . '&amp;contentid=0&amp;checkss=' . md5('0' . NV_CHECK_SESSION));

$content = $stpl->fetch('block_content.tpl');
