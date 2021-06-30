<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    exit('Stop!!!');
}

$tables = $nv_Request->get_array('tables', 'post', []);
$type = $nv_Request->get_title('type', 'post', '');
$ext = $nv_Request->get_title('ext', 'post', '');

if (empty($tables)) {
    $tables = [];
} elseif (!is_array($tables)) {
    $tables = [$tables];
}

$tab_list = [];

$result = $db->query("SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'");
while ($item = $result->fetch(3)) {
    $tab_list[] = $item[0];
}
$result->closeCursor();

$contents = [];
$contents['tables'] = (empty($tables)) ? $tab_list : array_values(array_intersect($tab_list, $tables));
$contents['type'] = ($type != 'str') ? 'all' : 'str';
$contents['savetype'] = ($ext != 'sql') ? 'gz' : 'sql';

$file_ext = ($contents['savetype'] == 'sql') ? 'sql' : 'sql.gz';
$file_name = NV_CHECK_SESSION . '_backupdata_' . date('Y-m-d-H-i', time()) . '.' . $file_ext;

$log_dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup';
if ($global_config['idsite']) {
    $log_dir .= '/' . $global_config['site_dir'];
}
$contents['filename'] = $log_dir . '/' . $file_name;

include NV_ROOTDIR . '/includes/core/dump.php';
$result = nv_dump_save($contents);

$xtpl = new XTemplate('save.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

if (empty($result)) {
    $xtpl->assign('ERROR', sprintf($lang_module['save_error'], NV_LOGS_DIR . '/dump_backup'));
    $xtpl->parse('main.error');
} else {
    $file = explode('_', $file_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['savefile'], 'File name: ' . end($file), $admin_info['userid']);

    $xtpl->assign('LINK_DOWN', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getfile&amp;filename=' . $file_name . '&amp;checkss=' . md5($file_name . NV_CHECK_SESSION));

    $xtpl->parse('main.result');
}

$page_title = $lang_module['save_data'];

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
