<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$xtpl = new XTemplate('department.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$a = 0;
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department ORDER BY weight';
$array_department = $db->query($sql)->fetchAll();
$count_department = sizeof($array_department);
foreach ($array_department as $row) {
    ++$a;
    $xtpl->assign('ROW', [
        'full_name' => $row['full_name'],
        'email' => $row['email'],
        'phone' => preg_replace("/(\[|&#91;)[^\]]*(&#93;|\])$/", '', $row['phone']),
        'fax' => $row['fax'],
        'id' => $row['id'],
        'url_department' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'],
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=row&amp;id=' . $row['id']
    ]);

    for ($i = 1; $i <= $count_department; ++$i) {
        $opt = [
            'value' => $i,
            'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
        ];
        $xtpl->assign('WEIGHT', $opt);
        $xtpl->parse('main.row.option');
    }

    $array = [$lang_global['disable'], $lang_global['active'], $lang_module['department_no_home']];

    foreach ($array as $key => $val) {
        $xtpl->assign('STATUS', [
            'key' => $key,
            'selected' => $key == $row['act'] ? ' selected="selected"' : '',
            'title' => $val
        ]);

        $xtpl->parse('main.row.status');
    }

    $xtpl->parse($row['is_default'] ? 'main.row.check' : 'main.row.notcheck');

    $xtpl->parse('main.row');
}
if (empty($a)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=row');
}

$xtpl->assign('URL_ADD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=row');

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['department_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
