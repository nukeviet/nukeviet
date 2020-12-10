<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (! defined('NV_IS_FILE_THEMES')) {
    die('Stop!!!');
}

$theme1 = $nv_Request->get_title('theme1', 'get');
$theme2 = $nv_Request->get_title('theme2', 'get');

$position1 = $position2 = array();

if (preg_match($global_config['check_theme'], $theme1) and preg_match($global_config['check_theme'], $theme2) and $theme1 != $theme2 and file_exists(NV_ROOTDIR . '/themes/' . $theme1 . '/config.ini') and file_exists(NV_ROOTDIR . '/themes/' . $theme2 . '/config.ini')) {
    // theme 1
    $xml = @simplexml_load_file(NV_ROOTDIR . '/themes/' . $theme1 . '/config.ini') or nv_info_die($lang_global['error_404_title'], $lang_module['block_error_fileconfig_title'], $lang_module['block_error_fileconfig_content'], 404);

    $content = $xml->xpath('positions');
    //array
    $positions = $content[0]->position;
    //object

    for ($i = 0, $count = sizeof($positions); $i < $count; ++$i) {
        $position1[] = $positions[$i]->tag;
    }

    // theme 2
    $xml = @simplexml_load_file(NV_ROOTDIR . '/themes/' . $theme2 . '/config.ini') or nv_info_die($lang_global['error_404_title'], $lang_module['block_error_fileconfig_title'], $lang_module['block_error_fileconfig_content'], 404);

    $content = $xml->xpath('positions');
    //array
    $positions = $content[0]->position;
    //object

    for ($i = 0, $count = sizeof($positions); $i < $count; ++$i) {
        $position2[] = $positions[$i]->tag;
    }

    $diffarray = array_diff($position1, $position2);
    $diffarray = array_diff($position1, $diffarray);

    $xtpl = new XTemplate('loadposition.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    for ($i = 0, $count = sizeof($diffarray); $i < $count; ++$i) {
        $position1[] = $positions[$i]->tag;

        $xtpl->assign('NAME', ( string )$positions[$i]->tag);
        $xtpl->assign('VALUE', ( string )$positions[$i]->name);

        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}
