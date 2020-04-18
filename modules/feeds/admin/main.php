<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_RSS_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['content'];

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . 'Content.txt';

if ($nv_Request->get_int('save', 'post') == '1') {
    $bodytext = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
    file_put_contents($content_file, $bodytext);

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$bodytext = '';
if (file_exists($content_file)) {
    $bodytext = file_get_contents($content_file);
    $bodytext = nv_editor_br2nl($bodytext);
}

$is_edit = $nv_Request->get_int('is_edit', 'get', 0);
if (empty($bodytext)) {
    $is_edit = 1;
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

if ($is_edit) {
    $bodytext = htmlspecialchars(nv_editor_br2nl($bodytext));

    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $xtpl->assign('DATA', nv_aleditor('bodytext', '100%', '300px', $bodytext));
    } else {
        $xtpl->assign('DATA', '<textarea style=\'width: 100%\' name=\'bodytext\' id=\'bodytext\' cols=\'20\' rows=\'8\'>' . $bodytext . '</textarea>');
    }

    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

    $xtpl->parse('main.edit');
} else {
    $xtpl->assign('EDIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;is_edit=1');
    $xtpl->assign('DATA', $bodytext);

    $xtpl->parse('main.add');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
