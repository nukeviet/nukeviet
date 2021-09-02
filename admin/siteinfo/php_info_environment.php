<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$page_title = $lang_module['environment_php'];

require_once NV_ROOTDIR . '/includes/core/phpinfo.php';

$xtpl = new XTemplate('environment_php.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$array = phpinfo_array(16, 1);
$caption = $lang_module['environment_php'];
$thead = [$lang_module['variable'], $lang_module['value']];

if (!empty($array['Environment'])) {
    $xtpl->assign('CAPTION', $caption);
    $xtpl->assign('THEAD0', $thead[0]);
    $xtpl->assign('THEAD1', $thead[1]);

    $a = 0;
    foreach ($array['Environment'] as $key => $value) {
        $xtpl->assign('KEY', $key);
        $xtpl->assign('VALUE', $value);
        $xtpl->parse('main.loop');
        ++$a;
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
