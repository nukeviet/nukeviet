<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:58
 */

if (! defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

$page_title = $lang_module['configuration_php'];

require_once NV_ROOTDIR . '/includes/core/phpinfo.php';

$xtpl = new XTemplate('configuration_php.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$array = phpinfo_array(4, 1);
$caption = $lang_module['configuration_php'];
$thead = array( $lang_module['directive'], $lang_module['local_value'], $lang_module['master_value'] );

if (! empty($array['PHP Core'])) {
    $xtpl->assign('CAPTION', $caption);
    $xtpl->assign('THEAD0', $thead[0]);
    $xtpl->assign('THEAD1', $thead[1]);
    $xtpl->assign('THEAD2', $thead[2]);

    $a = 0;
    foreach ($array['PHP Core'] as $key => $value) {
        $xtpl->assign('KEY', $key);

        if (! is_array($value)) {
            $xtpl->assign('VALUE', $value);
            $xtpl->parse('main.loop.if');
        } else {
            $xtpl->assign('VALUE0', $value[0]);
            $xtpl->assign('VALUE1', $value[1]);
            $xtpl->parse('main.loop.else');
        }

        $xtpl->parse('main.loop');
        ++$a;
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
