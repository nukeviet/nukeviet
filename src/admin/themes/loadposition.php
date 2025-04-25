<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$theme1 = $nv_Request->get_title('theme1', 'post', '');
$theme2 = $nv_Request->get_title('theme2', 'post', '');

$checkss = $nv_Request->get_title('checkss', 'post', '');
if ($checkss !== md5(NV_CHECK_SESSION . '_' . $module_name . '_xcopyblock_' . $admin_info['userid'])) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Session error!!!'
    ]);
}

if (preg_match($global_config['check_theme'], $theme1) and preg_match($global_config['check_theme'], $theme2) and $theme1 != $theme2 and file_exists(NV_ROOTDIR . '/themes/' . $theme1 . '/config.ini') and file_exists(NV_ROOTDIR . '/themes/' . $theme2 . '/config.ini')) {
    // Theme nguồn
    $position1 = array_column(nv_get_blocks($theme1, false), 'tag');

    // Theme đích
    $positions = nv_get_blocks($theme2, false);
    $position2 = array_column($positions, 'tag');

    $array = [];
    $position_intersect = array_intersect($position1, $position2);
    foreach ($positions as $position) {
        if (!in_array($position['tag'], $position_intersect, true)) {
            continue;
        }
        $array[] = [
            'tag' => $position['tag'],
            'name' => $position['name']
        ];
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('xcopyblock-position.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('ARRAY', $array);

    nv_jsonOutput([
        'success' => 1,
        'text' => '',
        'html' => $tpl->fetch('xcopyblock-position.tpl')
    ]);
}

nv_jsonOutput([
    'success' => 0,
    'text' => 'Request error!!!'
]);
