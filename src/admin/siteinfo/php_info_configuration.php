<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('configuration_php');

require_once NV_ROOTDIR . '/includes/core/phpinfo.php';

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('configuration_php.tpl'));
$tpl->assign('LANG', $nv_Lang);

$array = phpinfo_array(4, 1);
$tpl->assign('DATA', empty($array['PHP Core']) ? [] : $array['PHP Core']);

$contents = $tpl->fetch('configuration_php.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
