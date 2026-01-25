<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('save_data');

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
$file_name = date('Y-m-d-H-i-s') . '_backupdata_' . NV_CHECK_SESSION . '.' . $file_ext;

$log_dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup';
if ($global_config['idsite']) {
    $log_dir .= '/' . $global_config['site_dir'];
}
$contents['filename'] = $log_dir . '/' . $file_name;

include NV_ROOTDIR . '/includes/core/dump.php';
$result = nv_dump_save($contents);

if (!empty($result)) {
    $file = explode('_', $file_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('savefile'), 'File name: ' . end($file), $admin_info['userid']);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('save.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('SAVE_STATUS', $result);

$contents = $tpl->fetch('save.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
