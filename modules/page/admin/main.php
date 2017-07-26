<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['list'];
$array = array();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ASC';
$_rows = $db->query($sql)->fetchAll();
$num = sizeof($_rows);

if ($num < 1) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content');
}

$array_status = array(
    $lang_module['inactive'],
    $lang_module['active']
);

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$i = 0;
foreach ($_rows as $row) {
    $row['url_view'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
    $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id'];
    $row['checkss'] = md5($row['id'] . NV_CHECK_SESSION);
    for ($i = 1; $i <= $num; ++$i) {
        $xtpl->assign('WEIGHT', array(
            'w' => $i,
            'selected' => ($i == $row['weight']) ? ' selected="selected"' : ''
        ));

        $xtpl->parse('main.row.weight');
    }

    foreach ($array_status as $key => $val) {
        $xtpl->assign('STATUS', array(
            'key' => $key,
            'val' => $val,
            'selected' => ($key == $row['status']) ? ' selected="selected"' : ''
        ));

        $xtpl->parse('main.row.status');
    }

    //Chức năng copy
    if ($page_config['copy_page'] == 1) {
        $url_copy = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;copy=1&amp;id=' . $row['id'];
        $xtpl->assign('URL_COPY', $url_copy);
        $xtpl->parse('main.row.copy_page');
    }

    $is_excdata = 0;
    if ($global_config['idsite'] > 0 and isset($site_mods['excdata']) and isset($push_content['module'][$module_name]) and $row['status'] == 1) {
        $count = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $site_mods['excdata']['module_data'] . '_sended WHERE id_content=' . $row['id'] . ' AND module=' . $db->quote($module_name))->fetchColumn(); {
            $is_excdata = 1;
            $row['url_send'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=excdata&amp;' . NV_OP_VARIABLE . '=send&amp;module=' . $module_name . '&amp;id=' . $row['id'];
        }
    }

    $row['edit_time'] = nv_date('H:i d/m/y', $row['edit_time']);
    $row['add_time'] = nv_date('H:i d/m/y', $row['add_time']);

    $xtpl->assign('ROW', $row);

    if ($is_excdata) {
        $xtpl->parse('main.row.excdata');
    }

    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
