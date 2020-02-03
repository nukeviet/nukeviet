<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:49
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    die('Stop!!!');
}

$tables = $nv_Request->get_array('tables', 'post', array());
$type = $nv_Request->get_title('type', 'post', '');
$ext = $nv_Request->get_title('ext', 'post', '');

if (empty($tables)) {
    $tables = array();
} elseif (!is_array($tables)) {
    $tables = array( $tables );
}

$tab_list = array();

$result = $db->query("SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'");
while ($item = $result->fetch(3)) {
    $tab_list[] = $item[0];
}
$result->closeCursor();

$contents = array();
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

include NV_ROOTDIR . '/includes/core/dump.php' ;
$result = nv_dump_save($contents);

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$error = '';
if (empty($result)) {
    $error = $nv_Lang->getModule('save_error', NV_LOGS_DIR . '/dump_backup');
} else {
    $file = explode('_', $file_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('savefile'), 'File name: ' . end($file), $admin_info['userid']);
    $tpl->assign('LINK_DOWN', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getfile&amp;filename=' . $file_name . '&amp;checkss=' . md5($file_name . NV_CHECK_SESSION));
}

$tpl->assign('ERROR', $error);
$page_title = $nv_Lang->getModule('save_data');

$contents = $tpl->fetch('save.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
